<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\MissionOrder;
use App\Models\Tournee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $missionCount = 0;
        $memoireCount = 0;
        $tourneeCount = 0;
        $tourneeMemoireCount = 0;
        $employee = auth()->user()->employee;
        if ($employee->hasRole('sg')) {
            $missionCount += MissionOrder::where('status', 'sg_approve')->count();
            $memoireCount += MissionOrder::where('memor_status', 'sg_approve')->count();
            $tourneeCount += Tournee::where('status', 'sg_approve')->count();
            $tourneeMemoireCount += Tournee::where('memor_status', 'sg_approve')->count();
            //$missionOrders = MissionOrder::orderBy('id', 'desc')->get();
        }
        if ($employee->hasRole('controller')) {
            $dep_ids = Department::where('controller_id', $employee->id)->pluck('id')->toArray();
            $missionOrders = MissionOrder::whereHas('employee', function ($query) use ($dep_ids) {
                $query->whereIn('department_id', $dep_ids);
            })->get();
            $tournees = Tournee::whereHas('employee', function ($query) use ($dep_ids) {
                $query->whereIn('department_id', $dep_ids);
            })->get();
            $missionCount += $missionOrders->where('status', 'controller_approve')->count();
            $memoireCount += $missionOrders->where('memor_status', 'controller_approve')->count();
            $tourneeCount += $tournees->where('status', 'controller_approve')->count();
            $tourneeMemoireCount += $tournees->where('memor_status', 'controller_approve')->count();
        }
        if ($employee->hasRole('supervisor')) {
            $dep_ids = Department::where('manager_id', $employee->id)->pluck('id')->toArray();
            $missionOrders = MissionOrder::whereHas('employee', function ($query) use ($dep_ids) {
                $query->whereIn('department_id', $dep_ids);
            })->get();
            $tournees = Tournee::whereHas('employee', function ($query) use ($dep_ids) {
                $query->whereIn('department_id', $dep_ids);
            })->get();
            $missionCount += $missionOrders->where('status', 'sup_approve')->count();
            $memoireCount += $missionOrders->where('memor_status', 'sup_approve')->count();
            $tourneeCount += $tournees->where('status', 'sup_approve')->count();
            $tourneeMemoireCount += $tournees->where('memor_status', 'sup_approve')->count();
        }
        if ($employee->hasRole('employee')) {
            $missionOrders = MissionOrder::where('employee_id', '=', $employee->id)->orderBy('id', 'desc')->get();
            $tournees = Tournee::where('employee_id', '=', $employee->id)->orderBy('id', 'desc')->get();
            $missionCount += $missionOrders->where('status', 'draft')->count();
            $memoireCount += $missionOrders->where('memor_status', 'draft')->count();
            $tourneeCount += $tournees->where('status', 'draft')->count();
            $tourneeMemoireCount += $tournees->where('memor_status', 'draft')->count();
        }
        return view('dashboard', compact('missionCount', 'memoireCount', 'tourneeCount', 'tourneeMemoireCount'));
    }
}
