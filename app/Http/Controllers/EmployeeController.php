<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $roles = config('globals.roles');
        $departments = Department::all();
        $employee = auth()->user()->employee;
        if ($employee->hasRole('sg') || $employee->hasRole('hr')) {
            $employees = Employee::when($search, function ($query, $search) {
                return $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%');
            })->paginate(10);
        } else if ($employee->hasRole('supervisor')) {
            $employees = Employee::where('department_id', '=', auth()->user()->employee->department_id)->when($search, function ($query, $search) {
                return $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%');
            })->paginate(10);
        } else {
            $employees = Employee::where('id', '=', auth()->user()->employee->id)->paginate(10);
        }

        return view('employees.index', compact('employees', 'departments', 'roles', 'search'));
    }
    public function show(Employee $employee)
    {
        $departments = Department::all();
        $roles = config('globals.roles');
        return view('employees.show', compact('employee', 'departments', 'roles'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:employees',
            'password' => ['required', 'confirmed'],
            'phone' => 'nullable|numeric',
            'roles' => 'nullable|array',
            'roles.*' => 'in:employee,supervisor,hr,sg',
            'department_id' => 'required',
            /*'position' => 'required',
            'administrativ_residence' => 'required',
            'service' => 'required',*/
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => bcrypt('password'),
        ]);

        $employee = Employee::create(array_merge($request->all(), ['user_id' => $user->id]));
        $employee->syncRoles($request->role);
        if ($request->hasFile('profile_image')) {
            // Store the image in 'storage/app/public/profile_pictures'
            $file = $request->file('profile_image');
            $filename = $employee->first_name . $employee->last_name . '.' . $file->getClientOriginalExtension(); // e.g. 1609459200.jpeg

            $path = $file->storeAs('profile_images', $filename, 'public');

            // Save the image path to the user's profile
            $employee->profile_image = $path;
        }
        $employee->save();
        return redirect()->route('employees.index');
    }
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required', 'email', 'unique:employees,email,' . $employee->id],
            'phone' => 'nullable|numeric',
            'roles' => 'nullable|array',
            'roles.*' => 'in:employee,supervisor,hr,sg',
            'department_id' => 'required',
            /*'position' => 'required',
            'administrativ_residence' => 'required',
            'service' => 'required',*/
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $user = $employee->user;
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->save();
        $employee->update($request->all());
        $employee->syncRoles($request->role);
        if ($request->hasFile('profile_image')) {
            // Store the image in 'storage/app/public/profile_pictures'
            $file = $request->file('profile_image');
            $filename = $employee->first_name . $employee->last_name . '.' . $file->getClientOriginalExtension(); // e.g. 1609459200.jpeg

            $path = $file->storeAs('profile_images', $filename, 'public');
            // Save the image path to the user's profile
            $employee->profile_image = $path;
        }
        $employee->save();
        return redirect()->route('employees.index');
    }
    public function updatePassword(Request $request, Employee $employee)
    {
        $request->validate([
            'password' => ['required', 'confirmed'],
        ]);
        $user = $employee->user;
        $user->password = bcrypt($request->password);
        $user->save();
        return redirect()->route('employees.index');
    }
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index');
    }
}
