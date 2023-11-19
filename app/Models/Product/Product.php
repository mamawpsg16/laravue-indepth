<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['product_image'];

    protected function productImage(): Attribute
    {
        return new Attribute(
            get: fn () => asset('storage/product/images/0ubZTIDktuYSlGMsY1wduq9u8R3qPvamHJpqqu0E.jpg'),
        );
    }
}
