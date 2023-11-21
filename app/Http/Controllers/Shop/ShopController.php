<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Shop\StoreRequest;
use App\Http\Requests\Shop\UpdateRequest;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shops = Shop::orderBy('id', 'desc')->get();

        return response()->json(['status' => '200', 'shops' => $shops]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
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
        return response()->json(['status' => 200 ,'shop' => $shop]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        return response()->json(['status' => 200 ,'shop' => $shop]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Shop $shop)
    {
        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }
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
                            'image_name' => !$request->hasFile('image') ? $shop->image_name : $file_name,
                            'image' => !$request->hasFile('image') ? $shop->image : $hashed_name
                        ]);

        return response()->json(['status' => 200, 'message' => 'Shop updated successfully', 'shop' => $shop->toArray()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {   
        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        $shop->delete();

        return response()->json(['status' => 200, 'message' => 'Shop deleted successfully']);
    }

}
