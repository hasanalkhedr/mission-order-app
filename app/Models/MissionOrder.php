<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class MissionOrder extends Model
{
    protected static function booted()
    {

        static::creating(function ($missionOrder) {
            $missionOrder->order_number = MissionOrder::generateOrderNumber();
        });
        static::updating(function ($missionOrder) {
            if ($missionOrder->ijm) {
                //claculate accomodation
                $missionOrder->no_accomodation = $missionOrder->start_date->diffInDays($missionOrder->end_date);

                if (strtotime($missionOrder->start_time) <= strtotime('05:00 AM')) {
                    $missionOrder->no_accomodation = $missionOrder->no_accomodation + 1;
                }

                //calculate meals
                $missionOrder->no_meals = 2 * ($missionOrder->start_date->diffInDays($missionOrder->end_date) - 1);
                if (strtotime($missionOrder->start_time) <= strtotime('12:00 PM')) {
                    $missionOrder->no_meals = $missionOrder->no_meals + 2;
                } else if (strtotime($missionOrder->start_time) <= strtotime('07:00 PM')) {
                    $missionOrder->no_meals = $missionOrder->no_meals + 1;
                }
                if (strtotime($missionOrder->end_time) >= strtotime('09:00 PM')) {
                    $missionOrder->no_meals = $missionOrder->no_meals + 2;
                } else if (strtotime($missionOrder->end_time) >= strtotime('02:00 PM')) {
                    $missionOrder->no_meals = $missionOrder->no_meals + 1;
                }
            }
        });
    }
// Method to generate the next order number
public static function generateOrderNumber()
{
    // Get the latest mission order by order_number
    $latestOrder = MissionOrder::orderBy('order_number', 'desc')->first();

    // Check if there is any existing order number
    if ($latestOrder) {
        // Extract the numeric part from the latest order number (e.g., '0001')
        $lastOrderNumber = intval(substr($latestOrder->order_number, -4));
        // Increment the numeric part
        $newOrderNumber = $lastOrderNumber + 1;
    } else {
        // If no previous order exists, start with 1
        $newOrderNumber = 1;
    }

    // Format the new order number as MIS-24-XXXX
    return 'MIS-' . (new \DateTime())->format('y') . '-'  . str_pad($newOrderNumber, 4, '0', STR_PAD_LEFT);
}
    protected $fillable = [
        'order_date',
        'order_number',
        'employee_id',
        'purpose',
        'description',
        'bareme_id',
        'no_meals',
        'no_accomodation',
        'no_ded_meals',
        'no_ded_accomodation',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'arrive_location',
        'departure_location',
        'total_amount',
        'currency',
        'status',
        'charge',
        'ijm',
        'assurance',
        'budget_text',
        'memor_status',
        'advance',
        'memor_date',
    ];
    protected $casts = [
        'order_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'string', // Treat time fields as strings
        'end_time' => 'string',
        'memor_date' => 'date',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function bareme()
    {
        return $this->belongsTo(Bareme::class);
    }

    public function approvals()
    {
        return $this->hasMany(MissionApprove::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function getExpensesByCurrency()
    {
        return $this->expenses()
            ->selectRaw('currency, SUM(amount) as total_amount')
            ->groupBy('currency')
            ->pluck('total_amount', 'currency')
            ->toArray();
    }

    public function getMemoireTotals()
    {
        $expensesTotals = $this->getExpensesByCurrency();
        //$ex = $expensesTotals[$this->bareme->currency] ?? 0;
        $ex = $expensesTotals['Roupie indienne'] ?? 0;
        //$expensesTotals[$this->bareme->currency] = $ex + $this->total_amount-$this->advance;
        $expensesTotals['Roupie indienne'] = $ex + $this->total_amount-$this->advance;
        return $expensesTotals;
       // return array_merge($expensesTotals, [$this->bareme->currency => $this->total_amount-$this->advance]);
    }

    public function getMissionAprroves()
    {
        return $this->approvals()->where('memor_status', '=', null)->get();
    }
    public function getMemoirApproves()
    {
        return $this->approvals()->where('status', '=', null)->get();

    }
}


