<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopLocation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function setLongitudeAttribute($value)
    {
       $data = $this->attributes['longitude'] = $value == "null" ? null : $value;
    }
    public function setLatitudeAttribute($value)
    {
       $this->attributes['latitude'] = $value == "null" ? null : $value;
    }


}
