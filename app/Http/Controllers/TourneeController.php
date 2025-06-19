<?php

namespace App\Http\Controllers;

use App\Models\ChancelleryRate;
use App\Models\Department;
use App\Models\Bareme;
use App\Models\Employee;
use App\Models\Tournee;
use App\Models\TourneeDestination;
use App\Models\User;
use App\Notifications\MemoireTourneeLevelNotification;
use App\Notifications\TourneeLevelNotification;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TourneeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $employee = auth()->user()->employee;
        if ($employee->hasRole('sg') || $employee->hasRole('director') || $employee->hasRole('controller')) {
            $tournees = Tournee::when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->orderBy('id', 'desc')->paginate(10);
        } else if ($employee->hasRole('supervisor')) {
            $dep_ids = Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray();
            $tournees = Tournee::whereHas('employee', function ($query) use ($dep_ids) {
                $query->whereIn('department_id', $dep_ids); // Corrected to use whereIn
            })->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')
                    ->orWhere('purpose', 'like', '%' . $search . '%');
            })->orderBy('id', 'desc')->paginate(10);
        } else {
            $tournees = Tournee::where('employee_id', '=', auth()->user()->employee->id)->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->orderBy('id', 'desc')->paginate(10);
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
            //$bareme = Bareme::where('pays', '=', 'LIBAN')->limit(1)->get();
            $baremes = Bareme::where('pays', 'LIKE', '%INDE%')->orWhere('pays', 'like', '%France%')->get();
            $tour_number = Tournee::generateOrderNumber();
            $chancellery_rate = ChancelleryRate::currentRate()->rate;
            return view('tournees.create', compact('baremes', 'tour_number', 'chancellery_rate'));
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
            'bareme_id' => 'required',
            'charge' => 'required',
            'ijm' => 'required',
            'destinations' => 'required|array|min:1',
            'destinations.*.departure_location' => 'required|string',
            'destinations.*.arrive_location' => 'required|string',
            'destinations.*.start_date' => 'required|date',
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
                    $maxAdvanceInLocal = $maxAdvance * ChancelleryRate::currentRate()->rate;
                    if ($value > $maxAdvanceInLocal) {
                        $fail("Le montant dépasse 75% du total hébergement (max: " . number_format($maxAdvanceInLocal, 2) . " Roupie indienne (INR))");
                    }
                }
            ],
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
        $advance = $request->advance ? $request->advance : 0;
        $tournee = Tournee::create(array_merge($request->except(['advance']), ['status' => $status, 'advance' => $advance]));

        $destinations = $request->input('destinations');
        foreach ($destinations as $destination) {
            TourneeDestination::create(array_merge($destination, ['tournee_id' => $tournee->id]));
        }
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
            //$bareme = Bareme::where('pays', '=', 'LIBAN')->limit(1)->get();
            $baremes = Bareme::where('pays', 'LIKE', '%INDE%')->orWhere('pays', 'like', '%France%')->get();
            $chancellery_rate = ChancelleryRate::currentRate()->rate;
            return view('tournees.edit', compact('tournee', 'baremes', 'chancellery_rate'));
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
            'bareme_id' => 'required',
            'charge' => 'required',
            'ijm' => 'required',
            'destinations' => 'required|array|min:1',
            'destinations.*.departure_location' => 'required|string',
            'destinations.*.arrive_location' => 'required|string',
            'destinations.*.start_date' => 'required|date',
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
                    $maxAdvanceInLocal = $maxAdvance * ChancelleryRate::currentRate()->rate;
                    if ($value > $maxAdvanceInLocal) {
                        $fail("Le montant dépasse 75% du total hébergement (max: " . number_format($maxAdvanceInLocal, 2) . " Roupie indienne (INR))");
                    }
                }
            ],
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
        $advance = $request->advance ? $request->advance : 0;
        $tournee->update(array_merge($request->except(['advance']), ['status' => $status, 'advance' => $advance]));

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
            })->where('status', 'like', 'approved')->orderBy('id', 'desc')->paginate(10);
        } else if ($employee->hasRole('supervisor')) {
            $dep_ids = Department::where('manager_id', Auth::user()->employee->id)->pluck('id')->toArray();

            $tournees = Tournee::whereHas('employee', function ($query) use ($dep_ids) {
                $query->whereIn('department_id', $dep_ids);
            })->where('status', 'like', 'approved')->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
            })->orderBy('id', 'desc')->paginate(10);
        } else {
            $tournees = Tournee::where('employee_id', '=', auth()->user()->employee->id)
                ->where('status', 'like', 'approved')->when($search, function ($query, $search) {
                    return $query->where('order_number', 'like', '%' . $search . '%')->orWhere('purpose', 'like', '%' . $search . '%');
                })->orderBy('id', 'desc')->paginate(10);
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
            $current_rate = ChancelleryRate::currentRate()->rate;
            return view('tournees.m_show', compact('tournee', 'current_rate'));
        } else {
            abort(404);
        }
    }
    public function m_create(Request $request, Tournee $tournee)
    {
        $current_rate = ChancelleryRate::currentRate()->rate;
        return view('tournees.m_create', compact('tournee', 'current_rate'));
    }
    public function m_update(Request $request, Tournee $tournee)
    {
        $request->validate([
            'no_ded_accomodation' => 'required|numeric',
            'no_ded_meals' => 'required|numeric',
            //'advance' => 'required|numeric',
            'total_amount' => 'required|decimal:0,4',
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
        $current_rate = ChancelleryRate::currentRate()->rate;
        return view('tournees.memoire_report', compact('tournee', 'director', 'current_rate'));
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
        return redirect()->route('mission_orders.m_index');
    }
}
