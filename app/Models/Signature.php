<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    protected $fillable = [
        'employee_id',
        'signature_path',
        'status',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}
