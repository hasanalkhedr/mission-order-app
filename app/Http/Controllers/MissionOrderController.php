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
class MissionOrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $employee = auth()->user()->employee;
        if ($employee->hasRole('sg') || $employee->hasRole('director') || $employee->hasRole('controller')) {
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

        return view('mission_orders.index', compact('missionOrders', 'search'));
    }
    public function show(MissionOrder $missionOrder)
    {
        $employee = auth()->user()->employee;
        $dep_ids = Department::where('manager_id', $employee->id)->pluck('id')->toArray();
        if (
            $employee->hasRole('sg') ||
            $employee->hasRole('controller') ||
            $employee->hasRole('director') ||
            ($employee->hasRole('supervisor') && in_array($missionOrder->employee->department_id, $dep_ids)) ||
            ($employee->hasRole('employee') && $missionOrder->employee->id == $employee->id) ||
            ($employee->hasRole('attached') && $missionOrder->employee->id == $employee->id)
        ) {
            return view('mission_orders.show', compact('missionOrder'));
        } else {
            abort(404);
        }
    }
    public function showReport(Request $request, MissionOrder $missionOrder)
    {
        $director = Employee::whereJsonContains('roles', 'director')->first();
        return view('mission_orders.mission_order_report', compact('missionOrder', 'director'));
    }
    public function create()
    {
        if (auth()->user()->employee->allow_order) {
            $baremes = Bareme::where('pays','LIKE',  '%INDE%')->orWhere('pays', 'like', '%France%')->get();
            $mission_number = MissionOrder::generateOrderNumber();
            $chancellery_rate = ChancelleryRate::currentRate()->rate;
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
            'assurance' => 'required',
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
                    $maxAdvanceInLocal = $maxAdvance * ChancelleryRate::currentRate()->rate;
                    if ($value > $maxAdvanceInLocal) {
                        $fail("Le montant dépasse 75% du total hébergement (max: " . number_format($maxAdvanceInLocal, 2) . " Roupie indienne (INR))");
                    }
                }
            ],
            'expenses' => 'nullable|array',
            'expenses.*.type' => 'required|string|in:transport,extra_meal,other',
            'expenses.*.description' => 'required|string',
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
            if ($employee->hasRole('sg') || $employee->hasRole('director') || $employee->hasRole('controller')) {
                $status = 'sg_approve';
            } else if ($employee->hasRole('attached') || $employee->hasRole('supervisor')) {
                $status = 'director_approve';
            } else if ($employee->hasRole('employee')) {
                $status = 'sup_approve';
            } else {
                $status = 'draft';
            }
        }
        $advance = $request->advance ? $request->advance : 0;
        $missionOrder = MissionOrder::create(array_merge($request->except(['advance']), ['budget_text' => $budget_text, 'status' => $status, 'advance' => $advance,]));

$expenses = $request->input('expenses');
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
                    $missionOrder->status = 'director_approve';
                    $missionOrder->save();
                    $users = User::whereHas('employee', function ($query) {
                        $query->whereJsonContains('roles', 'director');
                    })->get();
                    foreach ($users as $user) {
                        $user->notify($notification);
                    }
                }
                break;
            case 'director_approve':
                $users = User::whereHas('employee', function ($query) {
                    $query->whereJsonContains('roles', 'director');
                })->get();
                foreach ($users as $user) {
                    $user->notify($notification);
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
            $chancellery_rate = ChancelleryRate::currentRate()->rate;
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
            'assurance' => 'required',
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
                    $maxAdvanceInLocal = $maxAdvance * ChancelleryRate::currentRate()->rate;
                    if ($value > $maxAdvanceInLocal) {
                        $fail("Le montant dépasse 75% du total hébergement (max: " . number_format($maxAdvanceInLocal, 2) . " Roupie indienne (INR))");
                    }
                }
            ],
            'expenses' => 'nullable|array',
            'expenses.*.type' => 'required|string|in:transport,extra_meal,other',
            'expenses.*.description' => 'required|string',
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
            if ($employee->hasRole('sg') || $employee->hasRole('director') || $employee->hasRole('controller')) {
                $status = 'sg_approve';
            } else if ($employee->hasRole('attached') || $employee->hasRole('supervisor')) {
                $status = 'director_approve';
            } else if ($employee->hasRole('employee')) {
                $status = 'sup_approve';
            } else {
                $status = 'draft';
            }
        }
        $advance = $request->advance ? $request->advance : 0;
        $missionOrder->update(array_merge($request->except(['advance']),
            ['budget_text' => $budget_text, 'status' => $status, 'advance' => $advance,]));

$expenses = $request->input('expenses');
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
                    $missionOrder->status = 'director_approve';
                    $missionOrder->save();
                    $users = User::whereHas('employee', function ($query) {
                        $query->whereJsonContains('roles', 'director');
                    })->get();
                    foreach ($users as $user) {
                        $user->notify($notification);
                    }
                }
                break;
            case 'director_approve':
                $users = User::whereHas('employee', function ($query) {
                    $query->whereJsonContains('roles', 'director');
                })->get();
                foreach ($users as $user) {
                    $user->notify($notification);
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
        if ($employee->hasRole('sg') || $employee->hasRole('director') || $employee->hasRole('controller')) {
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

        return view('mission_orders.m_index', compact('missionOrders', 'search'));
    }
    public function m_show(Request $request, MissionOrder $missionOrder)
    {
        $employee = auth()->user()->employee;
        $dep_ids = Department::where('manager_id', $employee->id)->pluck('id')->toArray();

        if (
            $employee->hasRole('sg') ||
            $employee->hasRole('controller') ||
            $employee->hasRole('director') ||
            ($employee->hasRole('supervisor') && in_array($missionOrder->employee->department_id, $dep_ids)) ||
            ($employee->hasRole('employee') && $missionOrder->employee->id == $employee->id) ||
            ($employee->hasRole('attached') && $missionOrder->employee->id == $employee->id)
        ) {
            $current_rate = ChancelleryRate::currentRate()->rate;
            return view('mission_orders.m_show', compact('missionOrder', 'current_rate'));
        } else {
            abort(404);
        }
    }
    public function m_create(Request $request, MissionOrder $missionOrder)
    {
        $current_rate = ChancelleryRate::currentRate()->rate;
        return view('mission_orders.m_create', compact('missionOrder', 'current_rate'));
    }
    public function m_update(Request $request, MissionOrder $missionOrder)
    {
        $request->validate([
            'no_ded_accomodation' => 'required|numeric',
            'no_ded_meals' => 'required|numeric',
            //'advance' => 'required|numeric',
            'total_amount' => 'required|decimal:0,4',
            'memor_date' => 'required|date|after_or_equal:end_date',
        ]);
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
        $director = Employee::whereJsonContains('roles', 'director')->first();
        $current_rate = ChancelleryRate::rateOfDate($missionOrder->order_date)->rate;
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
