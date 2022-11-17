<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "uuid",
        "user_id",
        "status",
        "total_amount",
        "type",
        "session_id",
        "submit_at",
        "process_at",
        "shipped_at",
        "delivered_at",
        "order_received_at"
    ];
}
