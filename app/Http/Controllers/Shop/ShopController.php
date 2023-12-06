<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop\Shop;
use Illuminate\Http\Request;
use App\Services\Shop\ShopService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Shop\StoreRequest;
use App\Http\Requests\Shop\UpdateRequest;

class ShopController extends Controller
{
    private $shopService;
    public function __construct(){
        $this->shopService = new ShopService();
    }
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
        $shop = $this->shopService->store($request);

        return response()->json(['status' => 200 ,'shop' => $shop]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {   
        $shop = Shop::with(['location','contactInformation','otherDetails'])->findOrFail($id);

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

       $this->shopService->update($request, $shop);
       
       $shop->load(['location', 'contactInformation', 'otherDetails']);
    
        return response(['status' => 200, 'message' => 'Shop updated successfully', 'shop' => $shop->toArray()]);
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
