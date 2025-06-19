<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourneeDestination extends Model
{
    use HasFactory;
    protected $fillable = [
        'tournee_id',
        'departure_location',
        'arrive_location',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'string', // Treat time fields as strings
        'end_time' => 'string',
    ];

    public function tournee()
    {
        return $this->belongsTo(Tournee::class);
    }

    public function nbOfAccomodation()
    {
        $no_accomodation = $this->start_date->diffInDays($this->end_date);
        if (strtotime($this->start_time) <= strtotime('05:00 AM')) {
            $no_accomodation = $no_accomodation + 1;
        }
        return $no_accomodation;
    }

    public function nbOfMeals()
    {
        $no_meals = 2 * ($this->start_date->diffInDays($this->end_date) - 1);
        if (strtotime($this->start_time) <= strtotime('12:00 PM')) {
            $no_meals = $no_meals + 2;
        } else if (strtotime($this->start_time) <= strtotime('07:00 PM')) {
            $no_meals = $no_meals + 1;
        }
        if (strtotime($this->end_time) >= strtotime('09:00 PM')) {
            $no_meals = $no_meals + 2;
        } else if (strtotime($this->end_time) >= strtotime('02:00 PM')) {
            $no_meals = $no_meals + 1;
        }
        return $no_meals;
    }
}
