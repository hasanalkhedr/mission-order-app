<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\MissionOrder;
use Illuminate\Http\Request;
use Storage;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        $missionOrder = MissionOrder::find($request->input('mission_order_id'));
        $request->validate([
            'mission_order_id' => 'required',
            'amount' => 'required|decimal:0,3',
            'currency' => 'required',
            'expense_date' => 'required|date|after_or_equal:'.$missionOrder->start_date.'|before_or_equal:'.$missionOrder->end_date,
            'description' => 'required',
            'expense_document' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],
    [
        'expense_date.after_or_equal' => 'Le champ date de dépense doit être une date postérieure ou égale à :date.',
        'expense_date.before_or_equal' => 'Le champ date de dépense doit être une date antérieure ou égale à :date.',
    ]);

        $expense = Expense::create($request->all());
        if ($request->hasFile('expense_document')) {
            // Store the image in 'storage/app/public/profile_pictures'
            $file = $request->file('expense_document');
            $filename = $request->input('mission_order_id') . '-m-'. $expense->id . '.'.$file->getClientOriginalExtension(); // e.g. 1609459200.jpeg

            $path = $file->storeAs('expense_documents', $filename, 'public');

            // Save the image path to the user's profile
            $expense->expense_document = $path;
            $expense->save();
        }
        return redirect()->route('mission_orders.m_create',$request->input('mission_order_id'));
    }
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'required',
            'expense_date' => 'required|date|after_or_equal:'.$expense->missionOrder->start_date.'|before_or_equal:'.$expense->missionOrder->end_date,
            'description' => 'required',
            'expense_document' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $expense->update($request->all());
        if ($request->hasFile('expense_document')) {
            // Store the image in 'storage/app/public/profile_pictures'
            $file = $request->file('expense_document');
            $filename = $request->input('mission_order_id') . '-'. $expense->id . '.'.$file->getClientOriginalExtension(); // e.g. 1609459200.jpeg

            $path = $file->storeAs('expense_documents', $filename, 'public');

            // Save the image path to the user's profile
            $expense->expense_document = $path;
            $expense->save();
        }
        return redirect()->route('mission_orders.m_create',$request->input('mission_order_id'));
    }
    public function destroy(Expense $expense)
    {
        $mission_order_id = $expense->mission_order_id;
        $expense->delete();
        return redirect()->route('mission_orders.m_create',$mission_order_id);
    }

    public function download_document(Expense $expense) {
        $filePath = $expense->expense_document;

    // Ensure the file exists
    if (!Storage::disk('public')->exists($filePath)) {
        abort(404, 'File not found.');
    }

    // Download the file from the 'public' disk
    return Storage::disk('public')->download($filePath);
    }
}
