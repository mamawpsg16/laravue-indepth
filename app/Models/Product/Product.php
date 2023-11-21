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
        $asset =  $this->image ? "/product/images/".$this->image : "/default/no_image.png";
        return new Attribute(
            get: fn () => asset("storage".$asset),
        );
    }

    public function setDescriptionAttribute($value)
    {
       $this->attributes['description'] = $value == "null" ? '' : $value;
    }
}
