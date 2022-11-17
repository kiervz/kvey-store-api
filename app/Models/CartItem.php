<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "product_id",
        "qty",
        "selected",
        "status"
    ];

    public static $statusDescription = [
        'P' => 'Pending',
        'C' => 'Completed',
        'D' => 'Deleted'
    ];

    public function getStatusTextAttribute()
    {
        return static::$statusDescription[$this->attributes['status']] ?? 'P';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
