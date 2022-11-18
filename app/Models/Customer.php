<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "phone",
        "birthday",
        "gender",
        "address 1",
        "address 2",
        "province",
        "city",
        "zip_code",
        "country_code"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
