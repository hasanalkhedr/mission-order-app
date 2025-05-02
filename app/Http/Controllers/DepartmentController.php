<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $departments = Department::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->paginate(10);
        return view('departments.index', compact('departments', 'search'));
    }
    public function show(Department $department)
    {
        return view('departments.show', compact('department'));
    }
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        Department::create($request->all());

        return redirect()->route('departments.index');
    }
    public function update(Request $request, Department $department)
    {
        $request->validate(['name' => 'required']);

        $department->update($request->all());
        // if (request('old_manager_id') != request('manager_id')) {
        $old_manager = Employee::find(request('old_manager_id'));
        $manager = Employee::find(request('manager_id'));
        if ($old_manager != null) {
            $old_manager->is_supervisor = false;
            $old_manager->removeRole('supervisor');
            $old_manager->addRole('employee');
            $old_manager->save();
        }
        if ($manager != null) {
            $manager->is_supervisor = true;
            $manager->addRole('supervisor');
            $manager->save();
        }
        //}
        return redirect()->route('departments.index');
    }
    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index');
    }
}
