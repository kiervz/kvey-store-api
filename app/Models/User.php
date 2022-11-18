<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ADMIN_ROLE = 1;
    public const CUSTOMER_ROLE = 2;
    public const STATUS_ACTIVE = 1;
    public const STATUS_BANNED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "ulid",
        "name",
        "email",
        "email_verified_at",
        "role_id",
        "status",
        "provider",
        "provider_id"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'provider_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'user_id', 'id')->whereNotIn('status', ['D', 'C']);
    }

    public function selectedCartItems()
    {
        return $this->hasMany(CartItem::class, 'user_id', 'id')->where('selected', 1)->whereNotIn('status', ['D', 'C']);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id', 'id');
    }
}
