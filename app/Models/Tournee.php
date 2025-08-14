<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournee extends Model
{
    use HasFactory;
    protected static function booted(){
        static::creating(function ($tournee) {
            $tournee->order_number = Tournee::generateOrderNumber();
        });
        static::updating(function ($tournee) {
            if ($tournee->ijm) {
                $tournee->no_accomodation = 0;
                $tournee->no_meals = 0;
                foreach ($tournee->tourneeDestinations as $destination) {
                    $tournee->no_accomodation += $destination->nbOfAccomodation();
                    $tournee->no_meals += $destination->nbOfMeals();
                }
            }
        });
    }
    public static function generateOrderNumber(){
        $latestOrder = Tournee::orderBy('order_number', 'desc')->first();
        if ($latestOrder) {
            $lastOrderNumber = intval(substr($latestOrder->order_number, -4));
            $newOrderNumber = $lastOrderNumber + 1;
        } else {
            $newOrderNumber = 1;
        }
        return 'TOR-' . (new \DateTime())->format('y') . '-' . str_pad($newOrderNumber, 4, '0', STR_PAD_LEFT);
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
        'total_amount',
        'currency',
        'status',
        'charge',
        'ijm',
        'budget_text',
        'memor_status',
        'memor_date',
        'advance',
        'reception_fees',
        'repas'
    ];
    protected $casts = [
        'order_date' => 'date',
        'memor_date' => 'date',
    ];
    public function tourneeDestinations()
    {
        return $this->hasMany(TourneeDestination::class);
    }
    public function firstDestination()
    {
        return $this->hasOne(TourneeDestination::class)->oldestOfMany(); // or ->orderBy('some_column')
    }
    public function lastDestination()
    {
        return $this->hasOne(TourneeDestination::class)->latestOfMany();
    }
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
        return $this->hasMany(TourneeApprove::class);
    }

    public function expenses()
    {
        return $this->hasMany(TourneeExpense::class);
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
        $expensesTotals['Roupie indienne'] = $ex + $this->total_amount - $this->advance;
        return $expensesTotals;
        //return array_merge($expensesTotals, [$this->bareme->currency => $this->total_amount-$this->advance]);
    }
    public function getTourneeAprroves()
    {
        return $this->approvals()->where('memor_status', '=', null)->get();
    }
    public function getTourneeMemoirApproves()
    {
        return $this->approvals()->where('status', '=', null)->get();

    }

}
