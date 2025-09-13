<?php

namespace App\Http\Controllers;

use App\Models\ChancelleryRate;
use App\Models\Department;
use App\Models\Employee;
use App\Models\MissionOrder;
use App\Models\Bareme;
use App\Notifications\MemoireMissionOrderLevelNotification;
use App\Notifications\MissionOrderLevelNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Expense;
use Storage;
use Validator;
class MissionOrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $employee = auth()->user()->employee;
        if ($employee->hasRole('sg') || $employee->hasRole('controller')) {
            $missionOrders = MissionOrder::when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->orderBy('id','desc')->paginate(10);
        } else if ($employee->hasRole('supervisor')) {
            $dep_ids = Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray();
            $missionOrders = MissionOrder::whereHas('employee', function ($query) use ($dep_ids) {
                $query->whereIn('department_id', $dep_ids); // Corrected to use whereIn
            })->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')
                    ->orWhere('purpose', 'like', '%' . $search . '%');
            })->orderBy('id','desc')->paginate(10);
        } else {
            $missionOrders = MissionOrder::where('employee_id', '=', auth()->user()->employee->id)->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->orderBy('id','desc')->paginate(10);
        }
        $countries = MissionOrder::get()
            ->pluck('arrive_location')
            ->unique()
            ->filter()
            ->values();
        // $countries = MissionOrder::with('bareme')
        //     ->get()
        //     ->pluck('bareme.pays')
        //     ->unique()
        //     ->filter()
        //     ->values();
        $employees = Employee::select('id', 'first_name', 'last_name')->get();

        return view('mission_orders.index', compact('missionOrders', 'search', 'countries', 'employees'));
    }
    public function show(MissionOrder $missionOrder)
    {
        $employee = auth()->user()->employee;
        $dep_ids = Department::where('manager_id', $employee->id)->pluck('id')->toArray();
        if (
            $employee->hasRole('sg') ||
            $employee->hasRole('controller') ||
            ($employee->hasRole('supervisor') && in_array($missionOrder->employee->department_id, $dep_ids)) ||
            ($employee->hasRole('employee') && $missionOrder->employee->id == $employee->id) ) {
            return view('mission_orders.show', compact('missionOrder'));
        } else {
            abort(404);
        }
    }
    public function showReport(Request $request, MissionOrder $missionOrder)
    {
        $director = Employee::whereJsonContains('roles', 'sg')->first();
        return view('mission_orders.mission_order_report', compact('missionOrder', 'director'));
    }
    public function create()
    {
        if (auth()->user()->employee->allow_order) {
            $baremes = Bareme::where('pays','LIKE',  '%INDE%')->orWhere('pays', 'like', '%France%')->get();
            $mission_number = MissionOrder::generateOrderNumber();
            $chancellery_rate = ChancelleryRate::currentRate();
            return view('mission_orders.create', compact('baremes', 'mission_number', 'chancellery_rate'));
        } else {
            return abort(403, 'You are not authorized to do this');
        }
    }
    public function store(Request $request, MissionOrder $missionOrder)
    {
        $request->validate([

            //'order_date' => 'required|date|before_or_equal:start_date',
            'employee_id' => 'required',
            'purpose' => 'required',

            'arrive_location' => 'required',
            'departure_location' => 'required',

            'bareme_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'charge' => 'required',
            'ijm' => 'required',
            //'assurance' => 'required',
            'return_location' => 'nullable',
            'advance' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $bareme = Bareme::find($request->bareme_id);
                    $start = Carbon::parse($request->start_date . ' ' . $request->start_time);
                    $end = Carbon::parse($request->end_date . ' ' . $request->end_time);
                    // Calculate full calendar days difference
                    $diffDays = abs($end->diffInDays($start));

                    $totalDays = $diffDays;

                    // Add extra day if start time is before 5 AM
                    if ($start->hour < 5) {
                        $totalDays += 1;
                    }

                    $maxAdvance = $totalDays * $bareme->accomodation_cost * 0.75;
                    $maxAdvanceInLocal = $maxAdvance * ChancelleryRate::currentRate()->eur_rate;
                    if ($value > $maxAdvanceInLocal) {
                        $fail("Le montant dépasse 75% du total hébergement (max: " . number_format($maxAdvanceInLocal, 2) . " Roupie indienne (INR))");
                    }
                }
            ],
            'expenses' => 'nullable|array',
            'expenses.*.type' => 'nullable|string|in:transport,extra_meal,visa,Receptions,other',
            'expenses.*.description' => 'nullable|string',
            'repas' => 'required',
            'endMission_location' => 'nullable',
            'start_time2' => 'nullable|date_format:H:i',
            'end_time2' => 'nullable|date_format:H:i'
        ]);
        $ids = array_column(Bareme::where('pays', 'like', '%France%')->get('id')->toArray(), 'id');
        $bareme_id = $request->input('bareme_id');
        $assurance = $request->input('assurance');
        $budget_text = '';
        if (in_array($bareme_id, $ids)) {
            $budget_text = 'Imputation budgétaire : 625-11 et 625-61';
        } else {
            $budget_text = 'Imputation budgétaire : 625-12 et 625-62';
        }
        $budget_text .= $assurance == 0 ? '' : ' et 647-1';
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
        $missionOrder = MissionOrder::create(array_merge($request->except(['advance']), ['budget_text' => $budget_text, 'status' => $status, 'advance' => $advance,]));

$expenses = $request->input('expenses') ?? [];
        foreach ($expenses as $expense) {
            Expense::create(array_merge($expense,
                [
                    'amount' => 0,
                    'currency' => 'EURO',
                    'expense_date' => $missionOrder->start_date,
                    'expense_document' => '',
                    'mission_order_id' => $missionOrder->id
                ]));
        }

        $notification = new MissionOrderLevelNotification($missionOrder);
        switch ($missionOrder->status) {
            case 'sup_approve':
                if ($missionOrder->employee->department->manager) {
                    $missionOrder->employee->department->manager->user->notify($notification);
                } else {
                    $missionOrder->status = 'sg_approve';
                    $missionOrder->save();
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
                    $user->notify($notification);
                }
                break;
        }
        return redirect()->route('mission_orders.index');
    }
    public function edit(MissionOrder $missionOrder)
    {
        if ($missionOrder->employee_id == auth()->user()->employee->id) {
            $baremes = Bareme::all();
            $chancellery_rate = ChancelleryRate::rateOfDate($missionOrder->start_date);
            return view('mission_orders.edit', compact('missionOrder', 'baremes', 'chancellery_rate'));
        } else {
            return abort(403, 'Unauthorized Action, you are not allowed to modify other employees missions');
        }
    }
    public function update(Request $request, MissionOrder $missionOrder)
    {
        $request->validate([
            //'order_date' => 'required|date|before_or_equal:start_date',
            'employee_id' => 'required',
            'purpose' => 'required',

            'arrive_location' => 'required',
            'departure_location' => 'required',

            'bareme_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'charge' => 'required',
            'ijm' => 'required',
            //'assurance' => 'required',
            'return_location' => 'nullable',
            'advance' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request, $missionOrder) {
                    $bareme = Bareme::find($request->bareme_id);
                    $start = Carbon::parse($request->start_date . ' ' . $request->start_time);
                    $end = Carbon::parse($request->end_date . ' ' . $request->end_time);
                    // Calculate full calendar days difference
                    $diffDays = abs($end->diffInDays($start));

                    $totalDays = $diffDays;

                    // Add extra day if start time is before 5 AM
                    if ($start->hour < 5) {
                        $totalDays += 1;
                    }

                    $maxAdvance = $totalDays * $bareme->accomodation_cost * 0.75;
                    $maxAdvanceInLocal = $maxAdvance * ChancelleryRate::rateOfDate($missionOrder->start_date)->eur_rate;
                    if ($value > $maxAdvanceInLocal) {
                        $fail("Le montant dépasse 75% du total hébergement (max: " . number_format($maxAdvanceInLocal, 2) . " Roupie indienne (INR))");
                    }
                }
            ],
            'expenses' => 'nullable|array',
            'expenses.*.type' => 'nullable|string|in:transport,visa,Receptions,extra_meal,other',
            'expenses.*.description' => 'nullable|string',
            'repas' => 'required',
        ]);
        $ids = array_column(Bareme::where('pays', 'like', '%France%')->get('id')->toArray(), 'id');
        $bareme_id = $request->input('bareme_id');
        $assurance = $request->input('assurance');
        $budget_text = '';
        if (in_array($bareme_id, $ids)) {
            $budget_text = 'Imputation budgétaire : 625-11 et 625-61';
        } else {
            $budget_text = 'Imputation budgétaire : 625-12 et 625-62';
        }
        $budget_text .= $assurance == 0 ? '' : ' et 647-1';
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
        $missionOrder->update(array_merge($request->except(['advance']),
            ['budget_text' => $budget_text, 'status' => $status, 'advance' => $advance,]));

$expenses = $request->input('expenses') ?? [];
        $existingIds = $missionOrder->expenses()->pluck('id')->toArray();
        $updatedIds = [];
        foreach ($expenses as $expense) {
            if (isset($expense['id'])) {
                // Update existing destination
                $existedExpense = Expense::find($expense['id']);
                if ($existedExpense) {
                    $existedExpense->update($expense);
                    $updatedIds[] = $existedExpense->id;
                }
            } else {
                // Create new destination
                $newExpense = $missionOrder->expenses()->create(array_merge($expense,
                [
                    'amount' => 0,
                    'currency' => 'EURO',
                    'expense_date' => $missionOrder->start_date,
                    'expense_document' => '',
                    'mission_order_id' => $missionOrder->id
                ]));
                $updatedIds[] = $newExpense->id;
            }
        }
        $toDelete = array_diff($existingIds, $updatedIds);

        if (!empty($toDelete)) {
            Expense::whereIn('id', $toDelete)->delete();
        }

        $notification = new MissionOrderLevelNotification($missionOrder);
        switch ($missionOrder->status) {
            case 'sup_approve':
                if ($missionOrder->employee->department->manager) {
                    $missionOrder->employee->department->manager->user->notify($notification);
                } else {
                    $missionOrder->status = 'sg_approve';
                    $missionOrder->save();
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
                    $user->notify($notification);
                }
                break;
        }
        return redirect()->route('mission_orders.index');
    }
    public function changeDates(Request $request, MissionOrder $missionOrder)
    {
        $request->validate([
            'order_date' => 'required|date|before_or_equal:start_date',
            'start_date' => 'required|date|after-or_equal:order_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
        $missionOrder->update($request->all());
        return redirect()->route('mission_orders.show', $missionOrder->id);
    }
    public function destroy(MissionOrder $missionOrder)
    {
        $missionOrder->delete();

        return redirect()->route('mission_orders.index');
    }
    public function m_index(Request $request)
    {
        $search = $request->input('search');
        $employee = auth()->user()->employee;
        if ($employee->hasRole('sg') || $employee->hasRole('controller')) {
            $missionOrders = MissionOrder::when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->where('status', 'like', 'approved')->orderBy('id','desc')->paginate(10);
        } else if ($employee->hasRole('supervisor')) {
            $dep_ids = Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray();
            $missionOrders = MissionOrder::whereHas('employee', function ($query) use ($dep_ids) {
                $query->whereIn('department_id', $dep_ids);
            })->where('status', 'like', 'approved')->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->orderBy('id','desc')->paginate(10);
        } else {
            $missionOrders = MissionOrder::where('employee_id', '=', auth()->user()->employee->id)
                ->where('status', 'like', 'approved')->when($search, function ($query, $search) {
                    return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
                })->orderBy('id','desc')->paginate(10);
        }
        $countries = MissionOrder::get()
            ->pluck('arrive_location')
            ->unique()
            ->filter()
            ->values();
        // $countries = MissionOrder::with('bareme')
        //     ->get()
        //     ->pluck('bareme.pays')
        //     ->unique()
        //     ->filter()
        //     ->values();
        $employees = Employee::select('id', 'first_name', 'last_name')->get();

        return view('mission_orders.m_index', compact('missionOrders', 'search', 'countries', 'employees'));
    }
    public function m_show(Request $request, MissionOrder $missionOrder)
    {
        $employee = auth()->user()->employee;
        $dep_ids = Department::where('manager_id', $employee->id)->pluck('id')->toArray();

        if (
            $employee->hasRole('sg') ||
            $employee->hasRole('controller') ||
            ($employee->hasRole('supervisor') && in_array($missionOrder->employee->department_id, $dep_ids)) ||
            ($employee->hasRole('employee') && $missionOrder->employee->id == $employee->id)) {
            $current_rate = ChancelleryRate::rateOfDate($missionOrder->memor_date);
            return view('mission_orders.m_show', compact('missionOrder', 'current_rate'));
        } else {
            abort(404);
        }
    }
    public function m_create(Request $request, MissionOrder $missionOrder)
    {
        $current_rate = ChancelleryRate::rateOfDate($missionOrder->memor_date);
        return view('mission_orders.m_create', compact('missionOrder', 'current_rate'));
    }
//     public function m_update(Request $request, MissionOrder $missionOrder)
//     {
//         //dd($request->input('expenses'));
//         $request->validate([
//             //'no_ded_accomodation' => 'required|numeric',
//             //'no_ded_meals' => 'required|numeric',
//             //'advance' => 'required|numeric',
//             //'total_amount' => 'required|decimal:0,4',
//             'memor_date' => 'required|date|after_or_equal:end_date',

//             'expenses' => 'required|array',
//             'expenses.*.type' => 'required|string|in:meal,accommodation,extra_meal,accommodation_extra,transport,visa,Receptions,other',
//             'expenses.*.expense_id' => 'nullable|integer|exists:expenses,id',
//             'expenses.*.transport_type' => 'required_if:expenses.*.type,transport|string',
//             'expenses.*.description' => 'nullable|string|max:255',
//             'expenses.*.reimbursement_amount' => 'required|numeric|min:0',
//             'expenses.*.reimbursement_currency' => 'required|string|in:INR,EUR,USD',
//             'expenses.*.direct_amount' => 'required|numeric|min:0',
//             'expenses.*.direct_currency' => 'required|string|in:INR,EUR,USD',
//             'expenses.*.total_inr' => 'sometimes|numeric|min:0',
//             'expenses.*.receipt' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',

//             'totals' => 'required|array',
//             'totals.reimbursement' => 'required|numeric|min:0',
//             'totals.direct' => 'required|numeric|min:0',
//             'totals.grand_total' => 'required|numeric|min:0',
//         ]);
//         $expenses = $request->input('expenses');
//         $deletedExpenses = array_diff($missionOrder->expenses->pluck('id')->toArray(),array_column($expenses,'expense_id'));
//         foreach($deletedExpenses as $expenseID) {
//             Expense::find($expenseID)->delete();
//         }
//         $totals = $request->input('totals');
//         foreach($expenses as $index => $expense) {
//             $receiptPath = null;
//             if ($request->hasFile("expenses.{$index}.receipt")) {
//                 $file = $request->file("expenses.{$index}.receipt");
//                 $receiptPath = $file->store('expense-receipts', 'public');
//             }
//             if(isset($expense['expense_id'])) {
//                 $storedExpense = Expense::find($expense['expense_id']);
//                 if ($receiptPath) {
//                     $expense['expense_document'] = $receiptPath;
//                 }
//                 $storedExpense->update($expense);
//             } else if($expense['type'] === 'accommodation') {
//                 $missionOrder->update([
//                     'acc_reimbursement_amount' => $expense['reimbursement_amount'],
//                     'acc_reimbursement_currency' => $expense['reimbursement_currency'],
//                     'acc_direct_amount' => $expense['direct_amount'],
//                     'acc_direct_currency' => $expense['direct_currency'],
//                     'acc_total_inr' => $expense['total_inr'],
//                 ]);
//             } else if($index !== 'INDEX'){

//                 Expense::create(array_merge($expense,
//                 ['mission_order_id'=>$missionOrder->id,
//                 'expense_date'=>$missionOrder->memor_date,
//                 'expense_document'=> '']));
//             }
//         }

//         $action = $request->input('action');
//         $memor_status = null;
//         if ($action === 'partialSubmit') {
//             $missionOrder->update($request->all());
//             return redirect()->route('mission_orders.m_create', $missionOrder);
//         } else if ($action === 'draft') {
//             $memor_status = 'draft';
//         } else if ($action === 'submit') {
//             $memor_status = 'controller_approve';
//         }
//         $missionOrder->update(array_merge($request->all(), ['memor_status' => $memor_status]));
// $missionOrder->update([
//         'expense_reimbursement_total' => $totals['reimbursement'],
//         'expense_direct_total' => $totals['direct'],
//         'expense_grand_total' => $totals['grand_total'],
//     ]);
//         $notification = new MemoireMissionOrderLevelNotification($missionOrder);
//         $users = User::whereHas('employee', function ($query) {
//             $query->whereJsonContains('roles', 'controller');
//         })->get();
//         foreach ($users as $user) {
//             $user->notify($notification);
//         }
//         return redirect()->route('mission_orders.m_index');
//     }

    public function m_update(Request $request, MissionOrder $missionOrder)
{
    // Custom validation rules
    $validator = Validator::make($request->all(), [
        'memor_date' => 'required|date|after_or_equal:end_date',
        'expenses' => 'required|array',
        'expenses.*.type' => 'required|string|in:meal,accommodation,extra_meal,accommodation_extra,transport,visa,Receptions,other',
        'expenses.*.expense_id' => 'nullable|integer|exists:expenses,id',
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

    $expenses = $request->input('expenses');
    $deletedExpenses = array_diff($missionOrder->expenses->pluck('id')->toArray(), array_column($expenses, 'expense_id'));

    foreach ($deletedExpenses as $expenseID) {
        $expense = Expense::find($expenseID);
        // Delete the associated file if it exists
        if ($expense->expense_document) {
            Storage::disk('public')->delete($expense->expense_document);
        }
        $expense->delete();
    }

    $totals = $request->input('totals');

    foreach ($expenses as $index => $expenseData) {
        $receiptPath = null;

        // Handle file upload if present
        if ($request->hasFile("expenses.$index.receipt")) {
            $file = $request->file("expenses.$index.receipt");
            $receiptPath = $file->store('expense-receipts', 'public');
        }
        // Use existing receipt if no new file was uploaded
        elseif (!empty($expenseData['existing_receipt'])) {
            $receiptPath = $expenseData['existing_receipt'];
        }

        if (isset($expenseData['expense_id'])) {
            $storedExpense = Expense::find($expenseData['expense_id']);

            // Delete old file if it's being replaced
            if ($receiptPath && $receiptPath !== $storedExpense->expense_document && $storedExpense->expense_document) {
                Storage::disk('public')->delete($storedExpense->expense_document);
            }

            $updateData = $expenseData;
            if ($receiptPath) {
                $updateData['expense_document'] = $receiptPath;
            }

            $storedExpense->update($updateData);
        } else if ($expenseData['type'] === 'accommodation') {
            $missionOrder->update([
                'acc_reimbursement_amount' => $expenseData['reimbursement_amount'],
                'acc_reimbursement_currency' => $expenseData['reimbursement_currency'],
                'acc_direct_amount' => $expenseData['direct_amount'],
                'acc_direct_currency' => $expenseData['direct_currency'],
                'acc_total_inr' => $expenseData['total_inr'],
            ]);
        } else if ($index !== 'INDEX') {
            Expense::create(array_merge($expenseData, [
                'mission_order_id' => $missionOrder->id,
                'expense_date' => $missionOrder->memor_date,
                'expense_document' => $receiptPath ?: '',
            ]));
        }
    }

    // Rest of your controller method remains the same...
    $action = $request->input('action');
    $memor_status = null;

    if ($action === 'partialSubmit') {
        $missionOrder->update($request->all());
        return redirect()->route('mission_orders.m_create', $missionOrder);
    } else if ($action === 'draft') {
        $memor_status = 'draft';
    } else if ($action === 'submit') {
        $memor_status = 'controller_approve';
    }

    $missionOrder->update(array_merge($request->all(), ['memor_status' => $memor_status]));

    $missionOrder->update([
        'expense_reimbursement_total' => $totals['reimbursement'],
        'expense_direct_total' => $totals['direct'],
        'expense_grand_total' => $totals['grand_total'],
    ]);

    $notification = new MemoireMissionOrderLevelNotification($missionOrder);
    $users = User::whereHas('employee', function ($query) {
        $query->whereJsonContains('roles', 'controller');
    })->get();

    foreach ($users as $user) {
        $user->notify($notification);
    }

    return redirect()->route('mission_orders.m_index');
}
    public function m_report(Request $request, MissionOrder $missionOrder)
    {
        $director = Employee::whereJsonContains('roles', 'sg')->first();
        $current_rate = ChancelleryRate::rateOfDate($missionOrder->memor_date);
        return view('mission_orders.memoire_report', compact('missionOrder', 'director', 'current_rate'));
    }
    public function m_destroy(Request $request, MissionOrder $missionOrder)
    {
        $missionOrder->expenses()->delete();
        $missionOrder->update([
            'no_ded_accomodation' => 0,
            'no_ded_meals' => 0,
            //'advance' => 0,
            'total_amount' => 0,
            'memor_status' => null,
        ]);
        return redirect()->route('mission_orders.m_index');
    }
}
