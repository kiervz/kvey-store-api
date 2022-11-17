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
        "price",
        "qty"
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
}
