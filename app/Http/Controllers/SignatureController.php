<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Signature;
use Illuminate\Http\Request;
use Storage;

class SignatureController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $employee = auth()->user()->employee;

        $query = Signature::with('employee');

        if ($employee->hasRole('sg') || $employee->hasRole('director') || $employee->hasRole('controller')) {
            // Admin/privileged users see all signatures with search
            $query->whereHas('employee', function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
            $employees = Employee::all();
        } elseif ($employee->hasRole('supervisor')) {
            // Supervisors see signatures from their department only
            $query->whereHas('employee', function ($query) use ($employee, $search) {
                $query->where('department_id', $employee->department_id)
                    ->where(function ($q) use ($search) {
                        $q->where('first_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%");
                    });
            });
            $employees = $employee->department->employees;
        } else {
            // Regular employees/attached see only their own signatures
            $query->where('employee_id', $employee->id);
            $employees = [$employee];
        }

        $signatures = $query->paginate(10);

        return view('signatures.index', compact('signatures','employees', 'search'));
    }
    public function show(Signature $signature)
    {
        $employee = auth()->user()->employee;
        if ($employee->hasRole('sg') || $employee->hasRole('director') || $employee->hasRole('controller')) {
            $employees = Employee::all();
        } elseif ($employee->hasRole('supervisor')) {
            $employees = $employee->department->employees;
        } else {
            $employees = [$employee];
        }
        return view('signatures.show', compact('signature', 'employees'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'signature_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $signature = Signature::create(array_merge($request->all(), ['status' => 'draft']));
        if ($request->hasFile('signature_path')) {
            // Store the image in 'storage/app/public/signatures'
            $file = $request->file('signature_path');
            $filename = 'SIGNATURE_'.$signature->employee->first_name .'_'. $signature->employee->last_name . '.' . $file->getClientOriginalExtension(); // e.g. 1609459200.jpeg
            $path = $file->storeAs('signatures', $filename, 'public');

            // Save the image path to the user's profile
            $signature->signature_path = $path;
        }
        $signature->save();
        return redirect()->route('signatures.index');
    }
    public function update(Request $request, Signature $signature)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'signature_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $signature->update($request->all());
        if ($request->hasFile('signature_path')) {
            // Store the image in 'storage/app/public/signatures'
            $file = $request->file('signature_path');
            $filename = 'SIGNATURE_'.$signature->employee->first_name .'_'. $signature->employee->last_name . '.' . $file->getClientOriginalExtension(); // e.g. 1609459200.jpeg

            $path = $file->storeAs('signatures', $filename, 'public');

            // Save the image path to the user's profile
            $signature->signature_path = $path;
        }
        $signature->save();
        return redirect()->route('signatures.index');
    }
    public function destroy(Signature $signature)
    {
        try {
            // Get the file path before deleting the record
            $filePath = $signature->signature_path;

            // Delete the database record
            $signature->delete();

            // Delete the associated file if it exists
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->route('signatures.index')
                           ->with('success', 'Signature deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Signature deletion failed: '.$e->getMessage());

            return back()->with('error', 'Failed to delete signature. Please try again.');
        }
    }
}
