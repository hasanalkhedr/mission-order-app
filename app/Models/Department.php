<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'manager_id', 'controller_id'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class);
    }

    public function controller()
    {
        return $this->belongsTo(Employee::class, 'controller_id');
    }
    public function missionOrders()
    {
        return $this->hasManyThrough(MissionOrder::class,Employee::class);
    }
    public function tournees()
    {
        return $this->hasManyThrough(Tournee::class,Employee::class);
    }
}
