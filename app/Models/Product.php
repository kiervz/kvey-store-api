<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "brand_id",
        "category_id",
        "sku",
        "name",
        "slug",
        "unit_price",
        "discount",
        "actual_price",
        "stock",
        "description",
        "is_post"
    ];
}
