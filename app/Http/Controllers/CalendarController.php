<?php

namespace App\Http\Controllers;

use App\Models\MissionOrder;
use App\Models\Tournee;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $events = [];
        $missions = collect();
        $tournees = collect();
        $employee = auth()->user()->employee;
        if($employee->hasRole('sg') || $employee->hasRole('director') || $employee->hasRole('controller')) {
            $missions = MissionOrder::all();
            $tournees = Tournee::all();
        } else if($employee->hasRole('supervisor')) {
            $missions = $employee->department->missionOrders;
            $tournees = $employee->department->tournees;
        } else {
            $missions = $employee->missionOrders;
            $tournees = $employee->tournees;
        }

        foreach ($missions as $mission) {
            $e = $mission->employee;
            $b = $mission->bareme;
            $title = "Employee: $e->first_name $e->last_name ,\nLocation:  $b->pays ,\nStatus: $mission->status,\nObjet: $mission->purpose ";
            $events[] = [
                'title' => $title,
                'start' => $mission->start_date,
                'end' => $mission->end_date,
                'classNames' => ['bg-blue-500', 'text-white-700', 'text-md'],
                'url' => route('mission_orders.show', $mission->id)
            ];
        }
        foreach ($tournees as $tournee) {
            $e = $tournee->employee;
            $b = $tournee->bareme;
            $title = "Employee: $e->first_name $e->last_name ,\nLocation:  $b->pays ,\nStatus: $tournee->status,\nObjet: $tournee->purpose ";
            $events[] = [
                'title' => $title,
                'start' => $tournee->start_date,
                'end' => $tournee->end_date,
                'classNames' => ['bg-green-500', 'text-white-700', 'text-md'],
                'url' => route('tournees.show', $tournee->id)
            ];
        }

        return view('calender', compact('events'));
    }
}
