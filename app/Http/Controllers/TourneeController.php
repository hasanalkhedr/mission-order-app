<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Bareme;
use App\Models\Employee;
use App\Models\Tournee;
use App\Models\User;
use App\Notifications\MemoireTourneeLevelNotification;
use App\Notifications\TourneeLevelNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class TourneeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $employee = auth()->user()->employee;
        if ($employee->hasRole('sg') || $employee->hasRole('director') || $employee->hasRole('controller')) {
            $tournees = Tournee::when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->paginate(10);
        } else if ($employee->hasRole('supervisor')) {
            $dep_ids = Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray();
            $tournees = Tournee::whereHas('employee', function ($query) use ($dep_ids) {
                $query->whereIn('department_id', $dep_ids); // Corrected to use whereIn
            })->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')
                    ->orWhere('purpose', 'like', '%' . $search . '%');
            })->paginate(10);
        } else {
            $tournees = Tournee::where('employee_id', '=', auth()->user()->employee->id)->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->paginate(10);
        }
        return view('tournees.index', compact('tournees', 'search'));
    }
    public function show(Tournee $tournee)
    {
        $employee = auth()->user()->employee;
        $dep_ids = Department::where('manager_id', $employee->id)->pluck('id')->toArray();
        if (
            $employee->hasRole('sg') ||
            $employee->hasRole('controller') ||
            $employee->hasRole('director') ||
            ($employee->hasRole('supervisor') && in_array($tournee->employee->department_id, $dep_ids)) ||
            ($employee->hasRole('employee') && $tournee->employee->id == $employee->id) ||
            ($employee->hasRole('attached') && $tournee->employee->id == $employee->id)
        ) {
            return view('tournees.show', compact('tournee'));
        } else {
            abort(404);
        }
    }
    public function showReport($id)
    {
        $tournee = Tournee::findOrFail($id);
        $director = Employee::whereJsonContains('roles', 'director')->first();
        return view('tournees.tournee_report', compact('tournee', 'director'));
    }
    public function create()
    {
        if (auth()->user()->employee->allow_order) {
            $bareme = Bareme::where('pays', '=', 'LIBAN')->limit(1)->get();
            $tour_number = Tournee::generateOrderNumber();
            return view('tournees.create', compact('bareme', 'tour_number'));
        } else {
            return abort(403, 'You are not authorized to do this');
        }
    }
    public function store(Request $request, Tournee $tournee)
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

        ]);
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
        $tournee = Tournee::create(array_merge($request->all(), ['status' => $status]));
        $notification = new TourneeLevelNotification($tournee);
        switch ($tournee->status) {
            case 'sup_approve':
                if ($tournee->employee->department->manager) {
                    $tournee->employee->department->manager->user->notify($notification);
                } else {
                    $tournee->status = 'director_approve';
                    $tournee->save();
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
        return redirect()->route('tournees.index');
    }
    public function edit(Tournee $tournee)
    {
        if ($tournee->employee_id == auth()->user()->employee->id) {
            $bareme = Bareme::where('pays', '=', 'LIBAN')->limit(1)->get();
            return view('tournees.edit', compact('tournee', 'bareme'));
        } else {
            return abort(403, 'Unauthorized Action, you are not allowed to modify other employees tournees');
        }
    }
    public function update(Request $request, Tournee $tournee)
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
        ]);
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
        $tournee->update(array_merge($request->all(), ['status' => $status]));
        $notification = new TourneeLevelNotification($tournee);
        switch ($tournee->status) {
            case 'sup_approve':
                if ($tournee->employee->department->manager) {
                    $tournee->employee->department->manager->user->notify($notification);
                } else {
                    $tournee->status = 'director_approve';
                    $tournee->save();
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
        return redirect()->route('mission_orders.show', $tournee->id);
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
        if ($employee->hasRole('sg') || $employee->hasRole('director') || $employee->hasRole('controller')) {
            $tournees = Tournee::when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->where('status', 'like', 'approved')->paginate(10);
        } else if ($employee->hasRole('supervisor')) {
            $dep_ids = Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray();

            $tournees = Tournee::whereHas('employee', function ($query) use ($dep_ids) {
                $query->whereIn('department_id', $dep_ids);
            })->where('status', 'like', 'approved')->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->paginate(10);
        } else {
            $tournees = Tournee::where('employee_id', '=', auth()->user()->employee->id)
                ->where('status', 'like', 'approved')->when($search, function ($query, $search) {
                    return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
                })->paginate(10);
        }
        return view('tournees.m_index', compact('tournees', 'search'));
    }
    public function m_show(Request $request, Tournee $tournee)
    {
        $employee = auth()->user()->employee;
        $dep_ids = Department::where('manager_id', $employee->id)->pluck('id')->toArray();
        if (
            $employee->hasRole('sg') ||
            $employee->hasRole('controller') ||
            $employee->hasRole('director') ||
            ($employee->hasRole('supervisor') && in_array($tournee->employee->department_id, $dep_ids)) ||
            ($employee->hasRole('employee') && $tournee->employee->id == $employee->id) ||
            ($employee->hasRole('attached') && $tournee->employee->id == $employee->id)
        ) {
            return view('tournees.m_show', compact('tournee'));
        } else {
            abort(404);
        }
    }
    public function m_create(Request $request, Tournee $tournee)
    {
        return view('tournees.m_create', compact('tournee'));
    }
    public function m_update(Request $request, Tournee $tournee)
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
            $tournee->update($request->all());
            return redirect()->route('tournees.m_create', $tournee);
        } else if ($action === 'draft') {
            $memor_status = 'draft';
        } else if ($action === 'submit') {
            $memor_status = 'controller_approve';
        }
        $tournee->update(array_merge($request->all(), ['memor_status' => $memor_status]));
        $notification = new MemoireTourneeLevelNotification($tournee);
        $users = User::whereHas('employee', function ($query) {
            $query->whereJsonContains('roles', 'controller');
        })->get();
        foreach ($users as $user) {
            $user->notify($notification);
        }
        return redirect()->route('tournees.m_index');
    }
    public function m_report(Request $request, Tournee $tournee)
    {
        $director = Employee::whereJsonContains('roles', 'director')->first();
        return view('tournees.memoire_report', compact('tournee', 'director'));
    }
    public function m_destroy(Request $request, Tournee $tournee)
    {
        $tournee->expenses()->delete();
        $tournee->update([
            'no_ded_accomodation' => 0,
            'no_ded_meals' => 0,
            'advance' => 0,
            'total_amount' => 0,
            'memor_status' => null,
        ]);
        return redirect()->route('mission_orders.m_index');
    }
}
