<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['id', 'first_name', 'last_name', 'email', 'phone', 'department_id', 'profile_image', 'is_supervisor', 'recieve_email', 'allow_order', 'user_id', 'roles', 'position', 'administrativ_residence', 'service', 'accountant'];

    protected $casts = [
        'roles' => 'array',
        'accountant' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function missionOrders()
    {
        return $this->hasMany(MissionOrder::class);
    }
    public function tournees()
    {
        return $this->hasMany(Tournee::class);
    }

    public function approvals()
    {
        return $this->hasMany(MissionApprove::class, 'approval_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function managed_departments()
    {
        return $this->hasMany(Department::class);
    }

    public function controlled_departments()
    {
        return $this->hasMany(Department::class, 'controller_id');
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles ?? []);
    }

    public function addRole($role)
    {
        $roles = $this->roles ?? [];

        if (!in_array($role, $roles)) {
            $roles[] = $role;
            $this->roles = $roles;
        }

        return $this;
    }

    public function removeRole($role)
    {
        $roles = $this->roles ?? [];

        if (($key = array_search($role, $roles)) !== false) {
            unset($roles[$key]);
            $this->roles = array_values($roles); // Reindex array
        }

        return $this;
    }

    public function syncRoles(array $roles)
    {
        $this->roles = array_unique($roles);
        return $this;
    }
    public function getRoles()
    {
        return collect($this->roles)->map(fn($role) => config('globals.roles.' . $role))->toArray();
    }

    public function signature()
    {
        return $this->hasOne(Signature::class);
    }

    public function scopeAccountant($query)
    {
        return $query->where('accountant', true);
    }
}

