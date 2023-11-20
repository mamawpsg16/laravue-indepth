<?php

namespace App\Http\Controllers\Shop;

use App\Http\Requests\Shop\StoreRequest;
use App\Http\Controllers\Controller;
use App\Models\Shop\Shop;
use Illuminate\Http\Request;
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

        $shop = Shop::create([
                                'name' => $data['name'],
                                'description' => $data['description'],
                                'user_id' => $request->user()->id,
                                'active' => 1,
                            ]);
        return response()->json(['status' => 200 ,'shop' => $shop]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

        $shop->update([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        return response()->json(['status' => 200, 'message' => 'Shop updated successfully', 'data' => $shop]);
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
