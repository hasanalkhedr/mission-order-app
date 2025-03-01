<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\MissionOrder;
use App\Models\Bareme;
use App\Notifications\MemoireMissionOrderLevelNotification;
use App\Notifications\MissionOrderLevelNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class MissionOrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = auth()->user()->employee->role;
        switch ($role) {
            case 'employee':
                $missionOrders = MissionOrder::where('employee_id', '=', auth()->user()->employee->id)->when($search, function ($query, $search) {
                    return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
                })->paginate(10);
                break;
            case 'supervisor':
                $dep_ids = Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray();

                $missionOrders = MissionOrder::whereHas('employee', function ($query) use ($dep_ids) {
                    $query->whereIn('department_id', $dep_ids); // Corrected to use whereIn
                })->when($search, function ($query, $search) {
                    return $query->where('order_number', 'like', '%' . $search . '%')
                        ->orWhere('purpose', 'like', '%' . $search . '%');
                })->paginate(10);
                break;
            case 'hr':
            case 'sg':
                $missionOrders = MissionOrder::when($search, function ($query, $search) {
                    return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
                })->paginate(10);
                break;
            default:
                $missionOrders = null;
                break;
        }
        return view('mission_orders.index', compact('missionOrders', 'search'));
    }
    public function show(MissionOrder $missionOrder)
    {
        $dep_ids = Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray();
        if (
            (auth()->user()->employee->role == 'supervisor' && !in_array($missionOrder->employee->department_id,$dep_ids))
            || (auth()->user()->employee->role == 'employee' && $missionOrder->employee->id != auth()->user()->employee->id)
        ) {
            abort(404);
        } else {
            return view('mission_orders.show', compact('missionOrder'));
        }
    }
    public function showReport(Request $request, MissionOrder $missionOrder)
    {
        return view('mission_orders.mission_order_report', compact('missionOrder'));
    }
    public function create()
    {
        if (auth()->user()->employee->allow_order) {
            $baremes = Bareme::all();
            $mission_number = MissionOrder::generateOrderNumber();
            return view('mission_orders.create', compact('baremes', 'mission_number'));
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
            switch (Auth::user()->employee->role) {
                case 'employee':
                    $employee_dep_id = Auth::user()->employee->department_id;
                    $sg_dep_id = array_column(Department::where('name', 'like', 'Secrétariat Général')
                        ->get('id')->toArray(), 'id');
                    if (in_array($employee_dep_id, $sg_dep_id))
                        $status = 'hr_approve';
                    else {
                        $status = 'sup_approve';
                    }
                    //$status = 'sup_approve';
                    break;
                case 'supervisor':
                    $employee_dep_id = Auth::user()->employee->department_id;
                    $sg_dep_id = array_column(Department::where('name', 'like', 'Secrétariat Général')
                        ->get('id')->toArray(), 'id');
                    if (!in_array($employee_dep_id, $sg_dep_id))
                        $status = 'hr_approve';
                    else {
                        $status = 'sg_approve';
                    }
                    break;
                case 'hr':
                    $status = 'sg_approve';
                    break;
                case 'sg':
                    $status = 'sg_approve';
                    break;
            }
        }
        $missionOrder = MissionOrder::create(array_merge($request->all(), ['budget_text' => $budget_text, 'status' => $status]));
        $notification = new MissionOrderLevelNotification($missionOrder);
        switch ($missionOrder->status) {
            case 'sup_approve':
                if ($missionOrder->employee->department->manager) {
                    $missionOrder->employee->department->manager->user->notify($notification);
                } else {
                    $missionOrder->status = 'hr_approve';
                    $missionOrder->save();
                    $users = User::whereHas('employee', function ($query) {
                        $query->where('role', 'hr');
                    })->get();
                    foreach ($users as $user) {
                        $user->notify($notification);
                    }
                }
                break;
            case 'hr_approve':
                $users = User::whereHas('employee', function ($query) {
                    $query->where('role', 'hr');
                })->get();
                foreach ($users as $user) {
                    $user->notify($notification);
                }
                break;
            case 'sg_approve':
                $users = User::whereHas('employee', function ($query) {
                    $query->where('role', 'sg');
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
            return view('mission_orders.edit', compact('missionOrder', 'baremes'));
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
            switch (Auth::user()->employee->role) {
                case 'employee':
                    $employee_dep_id = Auth::user()->employee->department_id;
                    $sg_dep_id = array_column(Department::where('name', 'like', 'Secrétariat Général')
                        ->get('id')->toArray(), 'id');
                    if (in_array($employee_dep_id, $sg_dep_id))
                        $status = 'hr_approve';
                    else {
                        $status = 'sup_approve';
                    }
                    //$status = 'sup_approve';
                    break;
                case 'supervisor':
                    $employee_dep_id = Auth::user()->employee->department_id;
                    $sg_dep_id = array_column(Department::where('name', 'like', 'Secrétariat Général')
                        ->get('id')->toArray(), 'id');
                    if (!in_array($employee_dep_id, $sg_dep_id))
                        $status = 'hr_approve';
                    else {
                        $status = 'sg_approve';
                    }
                    break;
                case 'hr':
                    $status = 'sg_approve';
                    break;
                case 'sg':
                    $status = 'sg_approve';
                    break;
            }
        }
        $missionOrder->update(array_merge($request->all(), ['budget_text' => $budget_text, 'status' => $status]));
        $notification = new MissionOrderLevelNotification($missionOrder);
        switch ($missionOrder->status) {
            case 'sup_approve':
                if ($missionOrder->employee->department->manager) {
                    $missionOrder->employee->department->manager->user->notify($notification);
                } else {
                    $missionOrder->status = 'hr_approve';
                    $missionOrder->save();
                    $users = User::whereHas('employee', function ($query) {
                        $query->where('role', 'hr');
                    })->get();
                    foreach ($users as $user) {
                        $user->notify($notification);
                    }
                }
                break;
            case 'hr_approve':
                $users = User::whereHas('employee', function ($query) {
                    $query->where('role', 'hr');
                })->get();
                foreach ($users as $user) {
                    $user->notify($notification);
                }
                break;
            case 'sg_approve':
                $users = User::whereHas('employee', function ($query) {
                    $query->where('role', 'sg');
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
        $role = auth()->user()->employee->role;
        switch ($role) {
            case 'employee':
                $missionOrders = MissionOrder::where('employee_id', '=', auth()->user()->employee->id)
                    ->where('status', 'like', 'approved')->when($search, function ($query, $search) {
                        return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
                    })->paginate(10);
                break;
            case 'supervisor':
                $dep_ids = Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray();

                $missionOrders = MissionOrder::whereHas('employee', function ($query) use ($dep_ids) {
                    $query->whereIn('department_id', $dep_ids);
                })->where('status', 'like', 'approved')->when($search, function ($query, $search) {
                    return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
                })->paginate(10);
                break;
            case 'hr':
            case 'sg':
                $missionOrders = MissionOrder::when($search, function ($query, $search) {
                    return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
                })->where('status', 'like', 'approved')->paginate(10);
                break;
            default:
                $missionOrders = null;
                break;
        }
        return view('mission_orders.m_index', compact('missionOrders', 'search'));
    }
    public function m_show(Request $request, MissionOrder $missionOrder)
    {
        $dep_ids = Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray();

        if (
            (auth()->user()->employee->role == 'supervisor' && !in_array($missionOrder->employee->department_id,$dep_ids))
            || (auth()->user()->employee->role == 'employee' && $missionOrder->employee->id != auth()->user()->employee->id)
        ) {
            abort(404);
        } else {
            return view('mission_orders.m_show', compact('missionOrder'));
        }
    }
    public function m_create(Request $request, MissionOrder $missionOrder)
    {
        return view('mission_orders.m_create', compact('missionOrder'));
    }
    public function m_update(Request $request, MissionOrder $missionOrder)
    {
        $request->validate([
            'no_ded_accomodation' => 'required|numeric',
            'no_ded_meals' => 'required|numeric',
            'advance' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'memor_date' => 'required|date|after_or_equal:end_date',
        ]);
        $action = $request->input('action');
        $memor_status = '';
        if ($action === 'partialSubmit') {
            $missionOrder->update($request->all());
            return redirect()->route('mission_orders.m_create', $missionOrder);
        } else if ($action === 'draft') {
            $memor_status = 'draft';
        } else if ($action === 'submit') {
            switch (Auth::user()->employee->role) {
                case 'employee':
                    $employee_dep_id = Auth::user()->employee->department_id;
                    $sg_dep_id = array_column(Department::where('name', 'like', 'Secrétariat Général')
                        ->get('id')->toArray(), 'id');
                    if (in_array($employee_dep_id, $sg_dep_id))
                        $memor_status = 'hr_approve';
                    else {
                        $memor_status = 'sup_approve';
                    }
                    break;
                case 'supervisor':
                    $employee_dep_id = Auth::user()->employee->department_id;
                    $sg_dep_id = array_column(Department::where('name', 'like', 'Secrétariat Général')
                        ->get('id')->toArray(), 'id');
                    if (!in_array($employee_dep_id, $sg_dep_id))
                        $memor_status = 'hr_approve';
                    else {
                        $memor_status = 'sg_approve';
                    }
                    break;
                case 'hr':
                    $memor_status = 'sg_approve';
                    break;
                case 'sg':
                    $memor_status = 'sg_approve';
                    break;
            }
        }
        $missionOrder->update(array_merge($request->all(), ['memor_status' => $memor_status]));

        $notification = new MemoireMissionOrderLevelNotification($missionOrder);
        switch ($missionOrder->memor_status) {
            case 'sup_approve':
                if ($missionOrder->employee->department->manager) {
                    $missionOrder->employee->department->manager->user->notify($notification);
                } else {
                    $missionOrder->memor_status = 'hr_approve';
                    $missionOrder->save();
                    $users = User::whereHas('employee', function ($query) {
                        $query->where('role', 'hr');
                    })->get();
                    foreach ($users as $user) {
                        $user->notify($notification);
                    }
                }
                break;
            case 'hr_approve':
                $users = User::whereHas('employee', function ($query) {
                    $query->where('role', 'hr');
                })->get();
                foreach ($users as $user) {
                    $user->notify($notification);
                }
                break;
            case 'sg_approve':
                $users = User::whereHas('employee', function ($query) {
                    $query->where('role', 'sg');
                })->get();
                foreach ($users as $user) {
                    $user->notify($notification);
                }
                break;
        }

        return redirect()->route('mission_orders.m_index');
    }
    public function m_report(Request $request, MissionOrder $missionOrder)
    {
        return view('mission_orders.memoire_report', compact('missionOrder'));
    }
    public function m_destroy(Request $request, MissionOrder $missionOrder)
    {
        $missionOrder->expenses()->delete();
        $missionOrder->update([
            'no_ded_accomodation' => 0,
            'no_ded_meals' => 0,
            'advance' => 0,
            'total_amount' => 0,
            'memor_status' => null,
        ]);
        return redirect()->route('mission_orders.m_index');
    }
}
