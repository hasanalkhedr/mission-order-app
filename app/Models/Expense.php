<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'mission_order_id',
        'amount',
        'currency',
        'description',
        'expense_document',
        'expense_date',
        'type',
        'transport_type',
        'transport_details',
        'meal_location',
        'meal_participants',
        'passenger',
        'distance',
        'material',
        'visits',
        'reimbursement_amount',
        'reimbursement_currency',
        'direct_amount',
        'direct_currency',
        'total_inr',
    ];
    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
        'reimbursement_amount' => 'decimal:2',
        'direct_amount' => 'decimal:2',
        'total_inr' => 'decimal:2',
        'passenger' => 'boolean',
        'distance' => 'boolean',
        'material' => 'boolean',
        'visits' => 'boolean',
    ];
    public function missionOrder()
    {
        return $this->belongsTo(MissionOrder::class);
    }
}
