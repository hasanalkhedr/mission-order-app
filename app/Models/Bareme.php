<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bareme extends Model
{
    protected static function booted() {
        //when creating bareme
        static::creating(function($bareme) {
            if($bareme->meal_cost==0 && $bareme->accomodation_cost==0)
            {
            $bareme->meal_cost = round($bareme->pays_per_day * 17.5 /100, 3);
            $bareme->accomodation_cost = round($bareme->pays_per_day * 65/100, 3);
            }
        });
        static::updating(function($bareme) {
            if($bareme->meal_cost==0 && $bareme->accomodation_cost==0)
            {
            $bareme->meal_cost = round($bareme->pays_per_day * 17.5 /100, 3);
            $bareme->accomodation_cost = round($bareme->pays_per_day * 65/100, 3);
            }
        });
    }
    protected $fillable = ['pays', 'currency', 'pays_per_day', 'meal_cost', 'accomodation_cost'];

    public function missionOrders()
    {
        return $this->hasMany(MissionOrder::class);
    }
}

