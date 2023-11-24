<?php
namespace App\Http\Controllers\Product;


use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;

class ProductController extends Controller
{
    private $productService;
    public function __construct(){
        $this->productService = new ProductService();
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
        $product = $this->productService->store($request);

        return response()->json(['status' => 200, 'message' => 'Product added successfully', 'product' => $product->toArray()]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        
        return response()->json(['status' => 200, 'product' => $product->toArray()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Product $product)
    {
     
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $this->productService->update($request, $product);
                        
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

        $this->productService->destroy($product);

        return response()->json(['status' => 200, 'message' => 'Product deleted successfully']);
    }


    public function import(Request $request){
        return $this->productService->upload($request);
    }
}
