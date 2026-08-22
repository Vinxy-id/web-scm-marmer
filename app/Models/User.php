<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'ikm_name',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class, 'created_by');
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class, 'user_id');
    }

    public function isRole(string ...$roles): bool
    {
        return in_array($this->role, $roles);
    }
}
