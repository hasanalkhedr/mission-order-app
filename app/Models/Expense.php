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
        'visits'
    ];
    protected $casts = [
        'expense_date' => 'date'
    ];
    public function missionOrder()
    {
        return $this->belongsTo(MissionOrder::class);
    }
}
