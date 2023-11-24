<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopContactInformation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    // In the ShopContactInformation model
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
