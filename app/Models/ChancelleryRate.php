<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChancelleryRate extends Model
{
    protected $fillable = ['eur_rate', 'usd_rate', 'month_year', 'status'];

    protected $dates = ['month_year'];

    protected $casts = [
        'month_year' => 'date',
    ];

    // Get current month's rate
    public static function currentRate()
    {
        $currentMonth = now()->format('Y-m-01');
        return self::where('month_year', $currentMonth)->first();
    }

    public static function rateOfDate($date)
    {
        $month = $date->format('Y-m-01');
        return self::where('month_year', $month)->first();
    }

    // Check if current month has a rate
    public static function hasCurrentRate()
    {
        return (bool) self::currentRate();
    }

    // Get EUR rate with fallback
    public function getEurRateAttribute($value)
    {
        return $value ?? 0;
    }

    // Get USD rate with fallback
    public function getUsdRateAttribute($value)
    {
        return $value ?? 0;
    }
}
