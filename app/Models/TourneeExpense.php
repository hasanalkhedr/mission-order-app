<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourneeExpense extends Model
{
    protected $fillable = [
        'tournee_id',
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
    ];
    protected $casts = [
        'expense_date' => 'date'
    ];
    public function tournee()
    {
        return $this->belongsTo(Tournee::class);
    }
}
