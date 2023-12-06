<?php

namespace App\Models\Shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['shop_image'];
    
   
   
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location(){

        return $this->hasOne(ShopLocation::class);
    }
    public function otherDetails(){

        return $this->hasOne(ShopOtherDetails::class);
    }

    public function contactInformation(){
        return $this->hasMany(ShopContactInformation::class);
    }

    protected function shopImage(): Attribute
    {
        $asset =  $this->image ? "/shop/images/".$this->image : "/default/no_image.png";
        return new Attribute(
            get: fn () => asset("storage".$asset),
        );
    }
     // Mutator
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = $value == "null" ? '' : $value;
    }
}
