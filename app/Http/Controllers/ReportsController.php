<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\MissionOrder;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public $employees;// = collect();
    public function index()
    {
        $employee = auth()->user()->employee;
        switch ($employee->role) {
            case 'employee':
                $employees = collect([$employee]);
                break;
            case 'supervisor':
                $employees = $employee->department->employees->with('missionOrders', 'tournees');
                break;
            case 'hr':
            case 'sg':
                $employees = Employee::all();
                break;
        }
        return view('reports.index', compact('employees'));
    }
    public function generateReport(Request $request)
    {
        $employees = array_map(function ($e) {
            return new Employee((array) $e);
        }, json_decode($request->employees ?? '[]', true));
        $employee_id = $request->employee_id;
        $from_date = Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d H:i:s');
        $to_date = Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d H:i:s');
        $missions = [];
        $tournees = [];
        if ($employee_id == 0) {
            foreach ($employees as $e) {
                foreach ($e->missionOrders->whereBetween('start_date', [$from_date, $to_date]) as $m) {
                    $missions[] = $m;
                }
                foreach ($e->tournees->whereBetween('start_date', [$from_date, $to_date]) as $t) {
                    //dd($t->start_date, $from_date, $to_date);
                    $tournees[] = $t;
                }
            }
        } else if ($employee_id) {
            $e = Employee::find($employee_id);
            foreach ($e->missionOrders as $m) {
                $missions[] = $m;
            }
            foreach ($e->tournees as $t) {
                $tournees[] = $t;
            }
        }
        $employee = Employee::find($employee_id);
        return view('reports.report', compact('missions', 'tournees', 'employee', 'from_date', 'to_date'));
    }
}
