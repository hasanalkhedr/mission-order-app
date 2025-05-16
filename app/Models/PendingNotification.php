<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'data',
        'is_sent',
    ];

    protected $casts = [
        'data' => 'json',
    ];
}
