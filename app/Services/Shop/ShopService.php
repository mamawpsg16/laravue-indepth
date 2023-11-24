<?php
namespace App\Services\Shop;

use App\Models\Shop\Shop;

class ShopService{
    public function __construct(){

    }

    public function store($request){
        $data = $request->validated();
        $file_name = '';
        $hashed_name = '';

        if($request->hasFile('image')){
            $file = $request->file('image');
            $file_name = $file->getClientOriginalName();
            // $hash_name = 'image'.uniqid().date("Y-m-d"); // Generate a unique, random name...
            $hashed_name = $file->hashName();
            $image_path = $file->storeAs('shop/images',$hashed_name,'public');
        }

        $shop = Shop::create([
                                'name' => $data['name'],
                                'description' => $data['description'],
                                'user_id' => $request->user()->id,
                                'image_name' => $file_name,
                                'image' => $hashed_name,
                                'active' => 1,
                            ]);

                            
        $shop->location()->create([
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'country' => $data['country'],
            'zip_code' => $data['zip_code'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);

        $shop->contactInformation()->createMany($data['contact_details']);
        
        $shop->otherDetails()->create($data['other_details']);
        return $shop;
    }
}