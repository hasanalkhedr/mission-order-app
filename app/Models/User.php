<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use MBarlow\Megaphone\HasMegaphone;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasMegaphone;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
    public function pendingNotifications()
    {
        return $this->hasMany(PendingNotification::class);
    }

    public function createPendingNotificationForMission($title, $body, $link, $linkText)
    {
        $this->pendingNotifications()->create([
            'data' => [
                'title' => $title,
                'body' => $body,
                'link' => $link,
                'linkText' => $linkText
            ]
        ]);
    }
}
