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
        $missionOrder = MissionOrder::findOrFail($request->input('mission_order_id'));
        $rules = [
            'mission_order_id' => 'required',
            'type' => 'required|in:transport,extra_meal,extra_accomodation,other',
            'amount' => 'required|decimal:0,3',
            'currency' => 'required',
            'expense_date' => 'required|date|after_or_equal:' . $missionOrder->start_date . '|before_or_equal:' . $missionOrder->end_date,
            //'description' => 'required',
            'expense_document' => 'required|file|mimes:jpg,jpeg,png,gif,pdf|max:4096',
            'passenger' => 'nullable',
            'distance' => 'nullable',
            'material' => 'nullable',
            'visits' => 'nullable',
        ];

        // Conditional validation based on expense type
        if ($request->type === 'transport') {
            $rules['transport_type'] = 'required|in:plane,train,taxi_uber,public_transport,car_rental_with_driver,autre';
            $rules['transport_details'] = 'nullable|string|max:255';
        } elseif ($request->type === 'extra_meal' || $request->type === 'extra_accomodation') {
            $rules['meal_location'] = 'required|string|max:255';
            //$rules['meal_participants'] = 'required|integer|min:1';
        }

        // Custom error messages
        $messages = [
            'expense_date.after_or_equal' => 'Le champ date de dépense doit être une date postérieure ou égale à :date.',
            'expense_date.before_or_equal' => 'Le champ date de dépense doit être une date antérieure ou égale à :date.',
            'type.required' => 'Le type de dépense est requis',
            'type.in' => 'Le type de dépense doit être soit "transport" ou "repas supplémentaire"',
            'transport_type.required' => 'Le type de transport est requis',
            'transport_type.in' => 'Le type de transport sélectionné est invalide',
            'meal_location.required' => 'Le lieu du repas est requis',
            'meal_participants.required' => 'Le nombre de participants est requis',
            'meal_participants.integer' => 'Le nombre de participants doit être un nombre entier',
            'meal_participants.min' => 'Le nombre de participants doit être au moins 1',
        ];

        $validatedData = $request->validate($rules, $messages);

        // Create the expense with basic fields
        $expenseData = [
            'mission_order_id' => $validatedData['mission_order_id'],
            'type' => $validatedData['type'],
            'amount' => $validatedData['amount'],
            'currency' => $validatedData['currency'],
            'expense_date' => $validatedData['expense_date'],
            //'description' => $validatedData['description'],
            'expense_document' => $validatedData['expense_document'],
            'passenger' => $validatedData['passenger'] ?? 0,
            'distance' => $validatedData['distance'] ?? 0,
            'material' => $validatedData['material'] ?? 0,
            'visits' => $validatedData['visits'] ?? 0,
        ];

        // Add type-specific fields
        if ($validatedData['type'] === 'transport' || $request->type === 'extra_accomodation') {
            $expenseData['transport_type'] = $validatedData['transport_type'];
            //$expenseData['transport_details'] = $validatedData['transport_details'] ?? null;
        } elseif ($validatedData['type'] === 'extra_meal' || $validatedData['type'] === 'extra_accomodation') {
            $expenseData['meal_location'] = $validatedData['meal_location'];
            //$expenseData['meal_participants'] = $validatedData['meal_participants'];
        }

        $expense = Expense::create($expenseData);

        // Handle file upload
        if ($request->hasFile('expense_document')) {
            $file = $request->file('expense_document');
            $filename = $request->input('mission_order_id') . '-m-' . $expense->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('expense_documents', $filename, 'public');
            $expense->expense_document = $path;
            $expense->save();
        }

        return redirect()->route('mission_orders.m_create', $request->input('mission_order_id'))
            ->with('success', 'Dépense créée avec succès');
    }
    public function update(Request $request, Expense $expense)
    {
        // Base validation rules
        $rules = [
            'type' => 'required|in:transport,extra_meal,extra_accomodation,other',
            'amount' => 'required|numeric',
            'currency' => 'required',
            'expense_date' => 'required|date|after_or_equal:' . $expense->missionOrder->start_date . '|before_or_equal:' . $expense->missionOrder->end_date,
            //'description' => 'required',
            'expense_document' => 'sometimes|file|mimes:jpg,jpeg,png,gif,pdf|max:4096', // Changed to 'sometimes'
            'passenger' => 'nullable',
            'distance' => 'nullable',
            'material' => 'nullable',
            'visits' => 'nullable',
        ];

        // Conditional validation based on expense type
        if ($request->type === 'transport') {
            $rules['transport_type'] = 'required|in:plane,train,taxi_uber,public_transport,car_rental_with_driver,autre';
            //$rules['transport_details'] = 'nullable|string|max:255';
        } elseif ($request->type === 'extra_meal' || $request->type === 'extra_accomodation') {
            $rules['meal_location'] = 'required|string|max:255';
           // $rules['meal_participants'] = 'required|integer|min:1';
        }

        // Custom error messages
        $messages = [
            'expense_date.after_or_equal' => 'Le champ date de dépense doit être une date postérieure ou égale à :date.',
            'expense_date.before_or_equal' => 'Le champ date de dépense doit être une date antérieure ou égale à :date.',
            'type.required' => 'Le type de dépense est requis',
            'type.in' => 'Le type de dépense doit être soit "transport" ou "repas supplémentaire"',
            'transport_type.required' => 'Le type de transport est requis',
            'transport_type.in' => 'Le type de transport sélectionné est invalide',
            'meal_location.required' => 'Le lieu du repas est requis',
            'meal_participants.required' => 'Le nombre de participants est requis',
            'meal_participants.integer' => 'Le nombre de participants doit être un nombre entier',
            'meal_participants.min' => 'Le nombre de participants doit être au moins 1',
            'expense_document.mimes' => 'Le fichier doit être de type: pdf, jpg, jpeg, png ou gif',
            'expense_document.max' => 'Le fichier ne doit pas dépasser 4MB',
        ];

        $validatedData = $request->validate($rules, $messages);

        // Prepare the data for update
        $updateData = [
            'type' => $validatedData['type'],
            'amount' => $validatedData['amount'],
            'currency' => $validatedData['currency'],
            'expense_date' => $validatedData['expense_date'],
            //'description' => $validatedData['description'],
            'passenger' => $validatedData['passenger'] ?? 0,
            'distance' => $validatedData['distance'] ?? 0,
            'material' => $validatedData['material'] ?? 0,
            'visits' => $validatedData['visits'] ?? 0,
        ];

        // Add type-specific fields
        if ($validatedData['type'] === 'transport') {
            $updateData['transport_type'] = $validatedData['transport_type'];
            $updateData['transport_details'] = $validatedData['transport_details'] ?? null;
            // Clear meal fields if they exist
            $updateData['meal_location'] = null;
            $updateData['meal_participants'] = null;
        } elseif ($validatedData['type'] === 'extra_meal'|| $validatedData['type'] === 'extra_accomodation') {
            $updateData['meal_location'] = $validatedData['meal_location'];
            //$updateData['meal_participants'] = $validatedData['meal_participants'];
            // Clear transport fields if they exist
            $updateData['transport_type'] = null;
            $updateData['transport_details'] = null;
        }

        // Handle file upload if a new file was provided
        if ($request->hasFile('expense_document')) {
            $file = $request->file('expense_document');
            $filename = $expense->mission_order_id . '-m-' . $expense->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('expense_documents', $filename, 'public');
            $updateData['expense_document'] = $path;
        }

        $expense->update($updateData);

        return redirect()->route('mission_orders.m_create', $expense->mission_order_id)
            ->with('success', 'Dépense mise à jour avec succès');
    }
    public function destroy(Expense $expense)
    {
        $mission_order_id = $expense->mission_order_id;
        $expense->delete();
        return redirect()->route('mission_orders.m_create', $mission_order_id);
    }

    public function download_document(Expense $expense)
    {
        $filePath = $expense->expense_document;

        // Ensure the file exists
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Download the file from the 'public' disk
        return Storage::disk('public')->download($filePath);
    }
}
