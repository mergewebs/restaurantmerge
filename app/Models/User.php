<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'passcode',
        'role',
        'assigned_area',
        'can_discount',
        'can_refund',
        'can_cancel_orders',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'passcode',
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
            'passcode' => 'hashed',
            'last_login_at' => 'datetime',
            'can_discount' => 'boolean', 'can_refund' => 'boolean', 'can_cancel_orders' => 'boolean',
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'passcode';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['owner', 'admin', 'superadmin', 'demo']);
    }

    public function isDemo(): bool
    {
        return $this->role === 'demo';
    }

    public function isUserAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    public function isWaiter(): bool
    {
        return $this->role === 'waiter';
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function schedules(){return $this->hasMany(StaffSchedule::class);}
    public function attendances(){return $this->hasMany(StaffAttendance::class);}
    public function assignedTables(){return $this->hasMany(RestaurantTable::class, 'assigned_user_id');}
    public function assignedOrders(){return $this->hasMany(Order::class, 'assigned_user_id');}
    public function mayDiscount(): bool{return $this->isAdmin() || $this->can_discount;}
    public function mayRefund(): bool{return $this->isAdmin() || $this->can_refund;}
    public function mayCancelOrders(): bool{return $this->isAdmin() || $this->can_cancel_orders;}
}
