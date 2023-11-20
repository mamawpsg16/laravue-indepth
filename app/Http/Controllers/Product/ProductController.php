<?php
namespace App\Http\Controllers\Product;


use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;

class ProductController extends Controller
{
    private $uploadService;
    public function __construct(){
        $this->uploadService = new ProductService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get()->toArray();
        return response()->json(['status' => 200, 'products' => $products]); 
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
            $image_path = $file->storeAs('product/images',$hashed_name,'public');
        }


        $product = Product::create([
                            'name' => $data['name'],
                            'description' => $data['description'],
                            'quantity' => $data['quantity'],
                            'price' => $data['price'],
                            'image_name' => $file_name,
                            'image' => $hashed_name,
                            'active' => 1,
                        ]);
        return response()->json(['status' => 200, 'message' => 'Product added successfully', 'product' => $product->toArray()]);
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
    public function update(UpdateRequest $request, Product $product)
    {
     
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $data = $request->validated();

        $file_name = '';
        $hashed_name = '';
        $file_path = "product/images/".$product->image;
        
        if($request->hasFile('image')){
    
            if (Storage::disk('public')->exists($file_path)) {
                Storage::disk('public')->delete($file_path);
            }

            $file = $request->file('image');
            $file_name = $file->getClientOriginalName();
            // $hash_name = 'image'.uniqid().date("Y-m-d"); // Generate a unique, random name...
            $hashed_name = $file->hashName();
            $image_path = $file->storeAs('product/images',$hashed_name,'public');
        }
        $product->update([
                            'name' => $data['name'],
                            'description' => $data['description'],
                            'quantity' => $data['quantity'],
                            'price' => $data['price'],
                            'image_name' => !$request->hasFile('image') ? $product->image_name : $file_name,
                            'image' => !$request->hasFile('image') ? $product->image : $hashed_name
                        ]);
                        
        return response()->json(['status' => 200, 'message' => 'Product added successfully', 'product' => $product->toArray()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {   
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $file_path = "product/images/".$product->image;

        if (Storage::disk('public')->exists($file_path)) {
            Storage::disk('public')->delete($file_path);
        }

        $product->delete();

        return response()->json(['status' => 200, 'message' => 'Product deleted successfully']);
    }


    public function import(Request $request){
        return $this->uploadService->upload($request);
    }
}
