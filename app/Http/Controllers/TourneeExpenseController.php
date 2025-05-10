<?php

namespace App\Http\Controllers;

use App\Models\TourneeExpense;
use App\Models\TourneeExpenseExpense;
use App\Models\Tournee;
use Illuminate\Http\Request;
use Storage;

class TourneeExpenseController extends Controller
{
    public function store(Request $request)
    {
        $tournee = Tournee::find($request->input('tournee_id'));
        $request->validate([
            'tournee_id' => 'required',
            'amount' => 'required|numeric',
            'currency' => 'required',
            'expense_date' => 'required|date|after_or_equal:'.$tournee->start_date.'|before_or_equal:'.$tournee->end_date,
            'description' => 'required',
           // 'expense_document' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
           'expense_document' => 'required|file|mimes:jpg,jpeg,png,gif,pdf|max:4096',
        ]);

        $expense = TourneeExpense::create($request->all());
        if ($request->hasFile('expense_document')) {
            // Store the image in 'storage/app/public/profile_pictures'
            $file = $request->file('expense_document');
            $filename = $request->input('tournee_id') . '-t-'. $expense->id . '.'.$file->getClientOriginalExtension(); // e.g. 1609459200.jpeg

            $path = $file->storeAs('expense_documents', $filename, 'public');

            // Save the image path to the user's profile
            $expense->expense_document = $path;
            $expense->save();
        }
        return redirect()->route('tournees.m_create',$request->input('tournee_id'));
    }
    public function update(Request $request, TourneeExpense $expense)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'required',
            'expense_date' => 'required|date|after_or_equal:'.$expense->tournee->start_date.'|before_or_equal:'.$expense->tournee->end_date,

            'description' => 'required',
            //'expense_document' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'expense_document' => 'required|file|mimes:jpg,jpeg,png,gif,pdf|max:4096',
        ]);

        $expense->update($request->all());
        if ($request->hasFile('expense_document')) {
            // Store the image in 'storage/app/public/profile_pictures'
            $file = $request->file('expense_document');
            $filename = $request->input('tournee_id') . '-'. $expense->id . '.'.$file->getClientOriginalExtension(); // e.g. 1609459200.jpeg

            $path = $file->storeAs('expense_documents', $filename, 'public');

            // Save the image path to the user's profile
            $expense->expense_document = $path;
            $expense->save();
        }
        return redirect()->route('tournees.m_create',$request->input('tournee_id'));
    }
    public function destroy(TourneeExpense $tournee_expense)
    {
        $tournee_id = $tournee_expense->tournee_id;
        $tournee_expense->delete();
        return redirect()->route('tournees.m_create',$tournee_id);
    }

    public function download_document(TourneeExpense $expense) {
        $filePath = $expense->expense_document;

    // Ensure the file exists
    if (!Storage::disk('public')->exists($filePath)) {
        abort(404, 'File not found.');
    }

    // Download the file from the 'public' disk
    return Storage::disk('public')->download($filePath);
    }
}
