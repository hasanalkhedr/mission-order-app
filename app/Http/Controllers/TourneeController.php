<?php

namespace App\Http\Controllers;

use App\Models\ChancelleryRate;
use App\Models\Department;
use App\Models\Bareme;
use App\Models\Employee;
use App\Models\Tournee;
use App\Models\TourneeDestination;
use App\Models\TourneeExpense;
use App\Models\User;
use App\Notifications\MemoireTourneeLevelNotification;
use App\Notifications\TourneeLevelNotification;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;
use Validator;
class TourneeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $employee = auth()->user()->employee;
        if ($employee->hasRole('sg')) {
            $tournees = Tournee::when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })
                ->orderByRaw("FIELD(status, 'sg_approve','draft', 'sup_approve', 'director_approve', 'approved', 'paid', 'rejected')")
                ->orderBy('id', 'desc')->paginate(10);
        } else if ($employee->hasRole('controller')) {
            $dep_ids = Department::where('controller_id', Auth::user()->employee->id)->pluck('id')->toArray();
            $tournees = Tournee::whereHas('employee', function ($query) use ($dep_ids) {
                $query->whereIn('department_id', $dep_ids); // Corrected to use whereIn
            })->orWhere('employee_id', '=', auth()->user()->employee->id)
            ->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')
                    ->orWhere('purpose', 'like', '%' . $search . '%');
            })->orderByRaw("FIELD(status, 'sup_approve','draft',  'director_approve', 'sg_approve', 'approved', 'paid', 'rejected')")
                ->orderBy('id', 'desc')->paginate(10);
        } else if ($employee->hasRole('supervisor')) {
            $dep_ids = Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray();
            $tournees = Tournee::whereHas('employee', function ($query) use ($dep_ids) {
                $query->whereIn('department_id', $dep_ids); // Corrected to use whereIn
            })->orWhere('employee_id', '=', auth()->user()->employee->id)
            ->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')
                    ->orWhere('purpose', 'like', '%' . $search . '%');
            })->orderByRaw("FIELD(status, 'sup_approve','draft',  'director_approve', 'sg_approve', 'approved', 'paid', 'rejected')")
                ->orderBy('id', 'desc')->paginate(10);
        } else {
            $tournees = Tournee::where('employee_id', '=', auth()->user()->employee->id)->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->orderBy('id', 'desc')->paginate(10);
        }

        $countries = Tournee::with('bareme')
            ->get()
            ->pluck('bareme.pays')
            ->unique()
            ->filter()
            ->values();
        $employees = Employee::select('id', 'first_name', 'last_name')->get();

        return view('tournees.index', compact('tournees', 'search', 'countries', 'employees'));
    }
    public function show(Tournee $tournee)
    {
        $employee = auth()->user()->employee;
        $dep_ids = Department::where('manager_id', $employee->id)->orWhere('controller_id', $employee->id)->pluck('id')->toArray();
        if (
            $employee->hasRole('sg') ||
            ($employee->hasRole('controller') && in_array($tournee->employee->department_id, $dep_ids)) ||
            ($employee->hasRole('supervisor') && in_array($tournee->employee->department_id, $dep_ids)) ||
            ($employee->hasRole('employee') && $tournee->employee->id == $employee->id)
        ) {
            return view('tournees.show', compact('tournee'));
        } else {
            abort(404);
        }
    }
    public function showReport($id)
    {
        $tournee = Tournee::findOrFail($id);
        $director = Employee::whereJsonContains('roles', 'sg')->first();
        return view('tournees.tournee_report', compact('tournee', 'director'));
    }
    public function create()
    {
        if (auth()->user()->employee->allow_order) {
            $baremes = Bareme::where('pays', 'LIKE', '%INDE%')->orWhere('pays', 'like', '%France%')->get();
            $tour_number = Tournee::generateOrderNumber();
            $chancellery_rate = ChancelleryRate::currentRate();
            return view('tournees.create', compact('baremes', 'tour_number', 'chancellery_rate'));
        } else {
            return abort(403, 'You are not authorized to do this');
        }
    }
    public function store(Request $request, Tournee $tournee)
    {
        $request->validate([
            'employee_id' => 'required',
            'purpose' => 'required',
            'bareme_id' => 'required',
            'charge' => 'required',
            'ijm' => 'required',
            'destinations' => 'required|array|min:1',
            'destinations.*.departure_location' => 'required|string',
            'destinations.*.arrive_location' => 'required|string',
            'destinations.*.start_date' => 'required|date',
            //'destinations.0.start_date' => 'required|date|after:'.now()->addDays(2),

            'destinations.0.start_date' => ['required', 'date', function ($attribute, $value, $fail) {
                $minDate = now()->addDays(2)->startOfDay()->format('Y-m-d');
                if ($value < $minDate) {
                    $fail("Le champ date de départ doit comporter une date postérieure au {$minDate} inclue.");
                }
            }],
            'destinations.*.start_time' => 'required',
            'destinations.*.end_date' => 'required|date',
            'destinations.*.end_time' => 'required',
            'advance' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $bareme = Bareme::find($request->bareme_id);
                    $destinations = $request->input('destinations');
                    $totalDays = 0;
                    foreach ($destinations as $destination) {
                        $start = Carbon::parse($destination['start_date'] . ' ' . $destination['start_time']);
                        $end = Carbon::parse($destination['end_date'] . ' ' . $destination['end_time']);
                        $totalDays += abs($end->diffInDays($start));
                    }
                    if ($start->hour < 5) {
                        $totalDays += 1;
                    }
                    $maxAdvance = $totalDays * $bareme->accomodation_cost * 0.75;
                    $maxAdvanceInLocal = $maxAdvance / (ChancelleryRate::currentRate() ? ChancelleryRate::currentRate()->eur_rate : 1);
                    if ($value > $maxAdvanceInLocal) {
                        $fail("Le montant dépasse 75% du total hébergement (max: " . number_format($maxAdvanceInLocal,2,'.',' ') . " Roupie indienne (INR))");
                    }
                }
            ],
            'expenses' => 'nullable|array',
            'expenses.*.type' => 'nullable|string|in:transport,visa,Receptions,extra_meal,other',
            'expenses.*.description' => 'nullable|string',
            'repas' => 'required',
        ]);
        $action = $request->input('action');
        $status = '';
        if ($action === 'draft') {
            $status = 'draft';
        } else if ($action === 'submit') {
            $employee = auth()->user()->employee;
            if ($employee->hasRole('sg') || $employee->hasRole('controller')) {
                $status = 'sg_approve';
            } else if ($employee->hasRole('supervisor')) {
                $status = 'sg_approve';
            } else if ($employee->hasRole('employee')) {
                $status = 'sup_approve';
            } else {
                $status = 'draft';
            }
        }
        $advance = $request->advance ? $request->advance : 0;
        $tournee = Tournee::create(array_merge($request->except(['advance']), ['status' => $status, 'advance' => $advance]));

        $destinations = $request->input('destinations');
        foreach ($destinations as $destination) {
            TourneeDestination::create(array_merge($destination, ['tournee_id' => $tournee->id]));
        }
        $expenses = $request->input('expenses') ?? [];
        foreach ($expenses as $expense) {
            TourneeExpense::create(array_merge(
                $expense,
                [
                    'amount' => 0,
                    'currency' => 'EURO',
                    'expense_date' => $tournee->firstDestination->start_date,
                    'expense_document' => '',
                    'tournee_id' => $tournee->id
                ]
            ));
        }

        TourneeExpense::create(
                [
                    'amount' => 0,
                    'currency' => 'INR',
                    'expense_date' => $tournee->firstDestination->start_date,
                    'expense_document' => '',
                    'mission_order_id' => $tournee->id,
                    'description' => '',
                    'type' => 'transport',
                    'transport_type' => 'Transport Avion',
                    'reimbursement_amount' => 0,
                    'reimbursement_currency' => 0,
                    'direct_amount' => 0,
                    'direct_currency' => 0,
                    'total_inr' => 0,
                ]
            );

        TourneeExpense::create(
                [
                    'amount' => 0,
                    'currency' => 'INR',
                    'expense_date' => $tournee->firstDestination->start_date,
                    'expense_document' => '',
                    'mission_order_id' => $tournee->id,
                    'description' => '',
                    'type' => 'transport',
                    'transport_type' => 'Transport en commun / Taxi(uber)',
                    'reimbursement_amount' => 0,
                    'reimbursement_currency' => 0,
                    'direct_amount' => 0,
                    'direct_currency' => 0,
                    'total_inr' => 0,
                ]
            );
        $notification = new TourneeLevelNotification($tournee);
        switch ($tournee->status) {
            case 'sup_approve':
                if ($tournee->employee->department->manager) {
                    $tournee->employee->department->manager->user->notify($notification);
                } else {
                    $tournee->status = 'sg_approve';
                    $tournee->save();
                    $users = User::whereHas('employee', function ($query) {
                        $query->whereJsonContains('roles', 'sg');
                    })->get();
                    foreach ($users as $user) {
                        $user->notify($notification);
                    }
                }
                break;
            case 'sg_approve':
                $users = User::whereHas('employee', function ($query) {
                    $query->whereJsonContains('roles', 'sg');
                })->get();
                foreach ($users as $user) {
                    if($user->employee->id != $tournee->employee_id) {
                        $user->notify($notification);
                    }
                }
                break;
        }
        return redirect()->route('tournees.index');
    }
    public function edit(Tournee $tournee)
    {
        if ($tournee->employee_id == auth()->user()->employee->id) {
            $baremes = Bareme::where('pays', 'LIKE', '%INDE%')->orWhere('pays', 'like', '%France%')->get();
            $chancellery_rate = ChancelleryRate::rateOfDate($tournee->firstDestination->start_date);
            return view('tournees.edit', compact('tournee', 'baremes', 'chancellery_rate'));
        } else {
            return abort(403, 'Unauthorized Action, you are not allowed to modify other employees tournees');
        }
    }
    public function update(Request $request, Tournee $tournee)
    {
        $request->validate([
            'employee_id' => 'required',
            'purpose' => 'required',
            'bareme_id' => 'required',
            'charge' => 'required',
            'ijm' => 'required',
            'destinations' => 'required|array|min:1',
            'destinations.*.departure_location' => 'required|string',
            'destinations.*.arrive_location' => 'required|string',
            'destinations.*.start_date' => 'required|date',
            //'destinations.0.start_date' => 'required|date|after:'.now()->addDays(2),

            'destinations.0.start_date' => ['required', 'date', function ($attribute, $value, $fail) {
                $minDate = now()->addDays(2)->startOfDay()->format('Y-m-d');
                if ($value < $minDate) {
                    $fail("Le champ date de départ doit comporter une date postérieure au {$minDate} inclue.");
                }
            }],
            'destinations.*.start_time' => 'required',
            'destinations.*.end_date' => 'required|date',
            'destinations.*.end_time' => 'required',
            'advance' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $bareme = Bareme::find($request->bareme_id);
                    $destinations = $request->input('destinations');
                    $totalDays = 0;
                    foreach ($destinations as $destination) {
                        $start = Carbon::parse($destination['start_date'] . ' ' . $destination['start_time']);
                        $end = Carbon::parse($destination['end_date'] . ' ' . $destination['end_time']);
                        $totalDays += abs($end->diffInDays($start));
                    }
                    if ($start->hour < 5) {
                        $totalDays += 1;
                    }
                    $maxAdvance = $totalDays * $bareme->accomodation_cost * 0.75;
                    $maxAdvanceInLocal = $maxAdvance / (ChancelleryRate::currentRate() ? ChancelleryRate::currentRate()->eur_rate : 1);
                    if ($value > $maxAdvanceInLocal) {
                        $fail("Le montant dépasse 75% du total hébergement (max: " . number_format($maxAdvanceInLocal,2,'.',' ') . " Roupie indienne (INR))");
                    }
                }
            ],
            'expenses' => 'nullable|array',
            'expenses.*.type' => 'nullable|string|in:transport,visa,Receptions,extra_meal,other',
            'expenses.*.description' => 'nullable|string',
            'repas' => 'required',
        ]);
        $action = $request->input('action');
        $status = '';
        if ($action === 'draft') {
            $status = 'draft';
        } else if ($action === 'submit') {
            $employee = auth()->user()->employee;
            if ($employee->hasRole('sg') || $employee->hasRole('controller')) {
                $status = 'sg_approve';
            } else if ($employee->hasRole('supervisor')) {
                $status = 'sg_approve';
            } else if ($employee->hasRole('employee')) {
                $status = 'sup_approve';
            } else {
                $status = 'draft';
            }
        }
        $advance = $request->advance ? $request->advance : 0;
        $tournee->update(array_merge($request->except(['advance']), ['status' => $status, 'advance' => $advance, 'order_date' => now()]));

        // Get existing destination IDs
        $existingIds = $tournee->tourneeDestinations()->pluck('id')->toArray();
        $updatedIds = [];

        // Process destinations
        foreach ($request->destinations as $destinationData) {
            if (isset($destinationData['id'])) {
                // Update existing destination
                $destination = TourneeDestination::find($destinationData['id']);
                if ($destination) {
                    $destination->update($destinationData);
                    $updatedIds[] = $destination->id;
                }
            } else {
                // Create new destination
                $newDestination = $tournee->tourneeDestinations()->create($destinationData);
                $updatedIds[] = $newDestination->id;
            }
        }

        // Delete destinations that weren't included or were marked for deletion
        $toDelete = array_diff($existingIds, $updatedIds);

        if (!empty($toDelete)) {
            TourneeDestination::whereIn('id', $toDelete)->delete();
        }

        $expenses = $request->input('expenses') ?? [];
        $existingIds = $tournee->expenses()->pluck('id')->toArray();
        $updatedIds = [];
        foreach ($expenses as $expense) {
            if (isset($expense['id'])) {
                // Update existing destination
                $existedExpense = TourneeExpense::find($expense['id']);
                if ($existedExpense) {
                    $existedExpense->update($expense);
                    $updatedIds[] = $existedExpense->id;
                }
            } else {
                // Create new destination
                $newExpense = $tournee->expenses()->create(array_merge(
                    $expense,
                    [
                        'amount' => 0,
                        'currency' => 'EURO',
                        'expense_date' => $tournee->firstDestination->start_date,
                        'expense_document' => '',
                        'tournee_id' => $tournee->id
                    ]
                ));
                $updatedIds[] = $newExpense->id;
            }
        }
        $toDelete = array_diff($existingIds, $updatedIds);

        if (!empty($toDelete)) {
            TourneeExpense::whereIn('id', $toDelete)->delete();
        }

        $notification = new TourneeLevelNotification($tournee);
        switch ($tournee->status) {
            case 'sup_approve':
                if ($tournee->employee->department->manager) {
                    $tournee->employee->department->manager->user->notify($notification);
                } else {
                    $tournee->status = 'sg_approve';
                    $tournee->save();
                    $users = User::whereHas('employee', function ($query) {
                        $query->whereJsonContains('roles', 'sg');
                    })->get();
                    foreach ($users as $user) {
                        $user->notify($notification);
                    }
                }
                break;
            case 'sg_approve':
                $users = User::whereHas('employee', function ($query) {
                    $query->whereJsonContains('roles', 'sg');
                })->get();
                foreach ($users as $user) {
                    if($user->employee->id != $tournee->employee_id) {
                        $user->notify($notification);
                    }
                }
                break;
        }
        return redirect()->route('tournees.index');
    }
    public function changeDates(Request $request, Tournee $tournee)
    {
        $request->validate([
            'order_date' => 'required|date|before_or_equal:start_date',
            'start_date' => 'required|date|after:order_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',

        ]);
        $tournee->update($request->all());
        return redirect()->route('tournees.show', $tournee->id);
    }
    public function destroy(Tournee $tournee)
    {
        $tournee->delete();

        return redirect()->route('tournees.index');
    }
    public function m_index(Request $request)
    {
        $search = $request->input('search');
        $employee = auth()->user()->employee;
        if ($employee->hasRole('sg') ) {
            $tournees = Tournee::when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->where('status', 'like', 'approved')
            ->orderByRaw("
                    CASE
                        WHEN memor_status = 'sg_approve' THEN 1
                        WHEN memor_status = 'controller_approve' THEN 2
                        WHEN memor_status = 'draft' THEN 3
                        WHEN memor_status IS NULL THEN 4
                        WHEN memor_status = 'approved' THEN 5
                        WHEN memor_status = 'paid' THEN 6
                        WHEN memor_status = 'rejected' THEN 7
                        ELSE 8
                    END
                ")->orderBy('id', 'desc')->paginate(10);
        } else if ($employee->hasRole('controller') ) {
             $dep_ids = Department::where('controller_id', Auth::user()->employee->id)->pluck('id')->toArray();

            $tournees = Tournee::whereHas('employee', function ($query) use ($dep_ids) {
                $query->whereIn('department_id', $dep_ids);
            })->orWhere('employee_id', '=', auth()->user()->employee->id)
            ->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->where('status', 'like', 'approved')
            ->orderByRaw("
                    CASE
                        WHEN memor_status = 'controller_approve' THEN 1
                        WHEN memor_status = 'sg_approve' THEN 2
                        WHEN memor_status = 'draft' THEN 3
                        WHEN memor_status IS NULL THEN 4
                        WHEN memor_status = 'approved' THEN 5
                        WHEN memor_status = 'paid' THEN 6
                        WHEN memor_status = 'rejected' THEN 7
                        ELSE 8
                    END
                ")->orderBy('id', 'desc')->paginate(10);
        }else if ($employee->hasRole('supervisor')) {
            $dep_ids = Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray();

            $tournees = Tournee::whereHas('employee', function ($query) use ($dep_ids) {
                $query->whereIn('department_id', $dep_ids);
            })->orWhere('employee_id', '=', auth()->user()->employee->id)
            ->where('status', 'like', 'approved')->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->orderBy('id', 'desc')->paginate(10);
        } else {
            $tournees = Tournee::where('employee_id', '=', auth()->user()->employee->id)
                ->where('status', 'like', 'approved')->when($search, function ($query, $search) {
                    return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
                })->orderBy('id', 'desc')->paginate(10);
        }
        $countries = Tournee::with('bareme')
            ->get()
            ->pluck('bareme.pays')
            ->unique()
            ->filter()
            ->values();
        $employees = Employee::select('id', 'first_name', 'last_name')->get();
        return view('tournees.m_index', compact('tournees', 'search', 'countries', 'employees'));
    }
    public function m_show(Request $request, Tournee $tournee)
    {
        $employee = auth()->user()->employee;
        $dep_ids = Department::where('manager_id', $employee->id)->orWhere('controller_id', $employee->id)->pluck('id')->toArray();
        if (
            $employee->hasRole('sg') ||
            ($employee->hasRole('controller') && in_array($tournee->employee->department_id, $dep_ids)) ||
            ($employee->hasRole('supervisor') && in_array($tournee->employee->department_id, $dep_ids)) ||
            ($employee->hasRole('employee') && $tournee->employee->id == $employee->id)
        ) {
            $current_rate = ChancelleryRate::rateOfDate($tournee->memor_date);
            return view('tournees.m_show', compact('tournee', 'current_rate'));
        } else {
            abort(404);
        }
    }
    public function m_create(Request $request, Tournee $tournee)
    {
        //if($tournee->end_date <= now()) {
            $current_rate = ChancelleryRate::rateOfDate($tournee->memor_date ?? $tournee->end_date);
            if($current_rate) {
                return view('tournees.m_create', compact('tournee', 'current_rate'));
            } else {
                abort(505);
            }
        /*} else {
            return back()->withErrors(['error' => 'vous ne pouvez pas ajouter de mémoire avant la fin de la mission']);
        }*/
    }
    public function m_update(Request $request, Tournee $tournee)
    {
        // Custom validation rules
        $validator = Validator::make($request->all(), [
            'memor_date' => 'required|date|after_or_equal:end_date',
            'expenses' => 'required|array',
            'expenses.*.type' => 'required|string|in:meal,accommodation,extra_meal,accommodation_extra,transport,visa,Receptions,other',
            'expenses.*.expense_id' => 'nullable|integer|exists:tournee_expenses,id',
            'expenses.*.transport_type' => 'required_if:expenses.*.type,transport|string',
            'expenses.*.description' => 'nullable|string|max:255',
            'expenses.*.reimbursement_amount' => 'required|numeric|min:0',
            'expenses.*.reimbursement_currency' => 'required|string|in:INR,EUR,USD',
            'expenses.*.direct_amount' => 'required|numeric|min:0',
            'expenses.*.direct_currency' => 'required|string|in:INR,EUR,USD',
            'expenses.*.total_inr' => 'sometimes|numeric|min:0',
            'expenses.*.receipt' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            'expenses.*.existing_receipt' => 'nullable|string',
            'totals' => 'required|array',
            'totals.reimbursement' => 'required|numeric|min:0',
            'totals.direct' => 'required|numeric|min:0',
            'totals.grand_total' => 'required|numeric|min:0',
        ]);

        // Manually handle file validation for each expense
        $expenses = $request->input('expenses', []);
        foreach ($expenses as $index => $expense) {
            if ($request->hasFile("expenses.$index.receipt")) {
                $file = $request->file("expenses.$index.receipt");
                $validator->after(function ($validator) use ($file, $index) {
                    if (!$file->isValid()) {
                        $validator->errors()->add("expenses.$index.receipt", "The receipt file is invalid.");
                    }

                    $allowedMimes = ['jpeg', 'png', 'jpg', 'gif', 'pdf'];
                    if (!in_array($file->getClientOriginalExtension(), $allowedMimes)) {
                        $validator->errors()->add("expenses.$index.receipt", "The receipt must be a file of type: jpeg, png, jpg, gif, pdf.");
                    }

                    if ($file->getSize() > 2048 * 1024) { // 2MB in bytes
                        $validator->errors()->add("expenses.$index.receipt", "The receipt may not be greater than 2MB.");
                    }
                });
            }
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $deletedExpenses = array_diff($tournee->expenses->pluck('id')->toArray(), array_column($expenses, 'expense_id'));

        foreach ($deletedExpenses as $expenseID) {
            $expense = TourneeExpense::find($expenseID);
            // Delete the associated file if it exists
            if ($expense->expense_document) {
                Storage::disk('public')->delete($expense->expense_document);
            }
            $expense->delete();
        }

        $totals = $request->input('totals');

        foreach ($expenses as $index => $expenseData) {
            $receiptPath = null;

            // Check if document was deleted (no existing_receipt and no new file)
            $documentDeleted = empty($expenseData['existing_receipt']) && !$request->hasFile("expenses.$index.receipt");

            // Handle file upload if present
            if ($request->hasFile("expenses.$index.receipt")) {
                $file = $request->file("expenses.$index.receipt");
                $receiptPath = $file->store('expense-receipts', 'public');
            }
            // Use existing receipt if no new file was uploaded and document wasn't deleted
            elseif (!empty($expenseData['existing_receipt']) && !$documentDeleted) {
                $receiptPath = $expenseData['existing_receipt'];
            }

            if (isset($expenseData['expense_id'])) {
                $storedExpense = TourneeExpense::find($expenseData['expense_id']);

                // Delete old file if it's being replaced or document was deleted
                if (($receiptPath && $receiptPath !== $storedExpense->expense_document && $storedExpense->expense_document) ||
                    ($documentDeleted && $storedExpense->expense_document)) {
                    Storage::disk('public')->delete($storedExpense->expense_document);
                }

                $updateData = $expenseData;
                if ($receiptPath) {
                    $updateData['expense_document'] = $receiptPath;
                } elseif ($documentDeleted) {
                    $updateData['expense_document'] = ''; // Clear the document path
                }

                $storedExpense->update($updateData);
            } else if ($expenseData['type'] === 'accommodation') {
                // Handle accommodation expense document deletion
                $currentAccDocument = $tournee->acc_expense_document;

                // Delete old file if it's being replaced or document was deleted
                if (($receiptPath && $receiptPath !== $currentAccDocument && $currentAccDocument) ||
                    ($documentDeleted && $currentAccDocument)) {
                    Storage::disk('public')->delete($currentAccDocument);
                }

                $updateData = [
                    'acc_reimbursement_amount' => $expenseData['reimbursement_amount'],
                    'acc_reimbursement_currency' => $expenseData['reimbursement_currency'],
                    'acc_direct_amount' => $expenseData['direct_amount'],
                    'acc_direct_currency' => $expenseData['direct_currency'],
                    'acc_total_inr' => $expenseData['total_inr'],
                    'acc_expense_document' => $receiptPath ?? ($documentDeleted ? null : $currentAccDocument),
                ];

                $tournee->update($updateData);
            } else if ($index !== 'INDEX') {
                // For new expenses, only set document if not deleted
                $expenseDocument = $documentDeleted ? '' : ($receiptPath ?: '');

                TourneeExpense::create(array_merge($expenseData, [
                    'tournee_id' => $tournee->id,
                    'expense_date' => $tournee->memor_date ?? $tournee->firstDestination->start_date,
                    'expense_document' => $expenseDocument,
                ]));
            }
        }

        // Rest of your controller method remains the same...
        $action = $request->input('action');
        $memor_status = null;

        if ($action === 'partialSubmit') {
            $tournee->update($request->all());
            return redirect()->route('tournees.m_create', $tournee);
        } else if ($action === 'draft') {
            $memor_status = 'draft';
        } else if ($action === 'submit') {
            $memor_status = 'controller_approve';
        }

        $tournee->update(array_merge($request->all(), ['memor_status' => $memor_status]));

        $tournee->update([
            'expense_reimbursement_total' => $totals['reimbursement'],
            'expense_direct_total' => $totals['direct'],
            'expense_grand_total' => $totals['grand_total'],
        ]);

        $notification = new MemoireTourneeLevelNotification($tournee);
        $dept_controller = $tournee->employee->department->controller;
        if ($dept_controller) {
            $dept_controller->user->notify($notification);
        } else {
            $tournee->update(['memor_status' => 'sg_approve']);
            $users = User::whereHas('employee', function ($query) {
                $query->whereJsonContains('roles', 'sg');
            })->get();
            foreach ($users as $user) {
                $user->notify($notification);
            }
        }
        // $users = User::whereHas('employee', function ($query) {
        //     $query->whereJsonContains('roles', 'controller');
        // })->get();

        // foreach ($users as $user) {
        //     if($user->employee->id != $tournee->employee_id) {
        //         $user->notify($notification);
        //     }
        // }

        return redirect()->route('tournees.m_index');
    }
    public function m_report(Request $request, Tournee $tournee)
    {
        $director = Employee::whereJsonContains('roles', 'sg')->first();
        $current_rate = ChancelleryRate::rateOfDate($tournee->memor_date ?? $tournee->end_date);
        if($current_rate) {
            return view('tournees.memoire_report', compact('tournee', 'director', 'current_rate'));
        } else {
            abort(505);
        }
    }
    public function m_destroy(Request $request, Tournee $tournee)
    {
        $tournee->expenses()->delete();
        $tournee->update([
            'no_ded_accomodation' => 0,
            'no_ded_meals' => 0,
            // 'advance' => 0,
            'total_amount' => 0,
            'memor_status' => null,
        ]);
        return redirect()->route('tournees.m_index');
    }
}
