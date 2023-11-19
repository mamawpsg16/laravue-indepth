<?php
namespace App\Http\Controllers\Product;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Product\StoreRequest;
use App\Models\Product\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::get()->toArray();
        return response()->json(['status' => 200, 'products' => $products]); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $file_name = '';
        $hashed_name = '';
        if($request->hasFile('image')){
            $file = $request->file('image');
            $file_name = $file->getClientOriginalName();
            // $hash_name = 'image'.uniqid().date("Y-m-d"); // Generate a unique, random name...
            $hashed_name = $file->hashName();
            $image_path = $file->storeAs('product/images',$hashed_name,'public');
        }

        $data = $request->validated();

        $product = Product::create([
                            'name' => $data['name'],
                            'description' => $data['description'],
                            'quantity' => $data['quantity'],
                            'price' => $data['price'],
                            'image_name' => $file_name,
                            'image' => $hashed_name
                        ]);

        return response()->json(['status' => 200, 'message' => 'Product added successfully']);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function import(Request $request){
        if($request->hasFile('csv')){
            $file = $request->file('csv');
            $file_name = $file->getClientOriginalName();
            // $hash_name = 'image'.uniqid().date("Y-m-d"); // Generate a unique, random name...
            $hashed_name = $file->hashName();
            $image_path = $file->storeAs('product/temp',$hashed_name,'public');
        }

    }
}
