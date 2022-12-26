<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        "customer_id",
        "contact_person",
        "mobile",
        "address",
        "province",
        "city",
        "barangay",
        "address_tag"
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
