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
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'integer',
    ];


    protected function productImage(): Attribute
    {
        return new Attribute(
            get: fn () => asset('storage/product/images/'.$this->image),
        );
    }
}
