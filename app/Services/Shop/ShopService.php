<?php
namespace App\Services\Shop;

use App\Models\Shop\Shop;
use App\Models\Shop\ShopLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShopService{
    public function __construct(){

    }

    public function store($request){
        try {
           return DB::transaction(function()use($request){
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
                ]);

                $shop->contactInformation()->createMany($data['contact_details']);
                
                $shop->otherDetails()->create($data['other_details']);

                return $shop;
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function update($request, $shop){
        try {
            return DB::transaction(function () use($request, $shop){
                $data = $request->validated();
                $file_name = '';
                $hashed_name = '';
                $file_path = "shop/images/".$shop->image;
                
                if($request->hasFile('image')){
            
                    if (Storage::disk('public')->exists($file_path)) {
                        Storage::disk('public')->delete($file_path);
                    }
        
                    $file = $request->file('image');
                    $file_name = $file->getClientOriginalName();
                    // $hash_name = 'image'.uniqid().date("Y-m-d"); // Generate a unique, random name...
                    $hashed_name = $file->hashName();
                    $image_path = $file->storeAs('shop/images',$hashed_name,'public');
                }
                $shop->update([
                                'name' => $data['name'],
                                'description' => $data['description'],
                            ]);
                if($file_name){
                    $shop->image_name =  $file_name;
                    $shop->image =  $hashed_name;
                    $shop->save();
                }
                // dd('SAVE');
                $location = ShopLocation::where('shop_id', $shop->id)->update([
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'country' => $data['country'],
                    'zip_code' => $data['zip_code'],
                    // 'latitude' => $data['latitude'] == "null" ? "" : $data['latitude'],
                    // 'longitude' => $data['longitude'] == "null" ? "" : $data['longitude']
                ]);

                $shop->contactInformation()->delete();

                $shop->contactInformation()->createMany($data['contact_details']);
                
                $shop->otherDetails()->delete();
                $shop->otherDetails()->create([
                    'facebook' => $data['other_details']['facebook'],
                    'twitter' => $data['other_details']['twitter'],
                    'instagram' => $data['other_details']['instagram'],
                    'tiktok' => $data['other_details']['tiktok'],
                    'shipping_policy' => $data['other_details']['shipping_policy'],
                    'returns_policy' => $data['other_details']['returns_policy'],
                ]);
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}