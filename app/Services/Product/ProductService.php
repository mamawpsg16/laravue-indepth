<?php
namespace App\Services\Product;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService{
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
        return $product;
    }

    public function update($request, $product){
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
        return true;
    }

    public function destroy($product){
        $file_path = "product/images/".$product->image;

        if (Storage::disk('public')->exists($file_path)) {
            Storage::disk('public')->delete($file_path);
        }

        $product->delete();

        return $product;
    }
    public  function upload($request){
        if($request->hasFile('csv')){
            $file = $request->file('csv');
            $file_name = $file->getClientOriginalName();
            // $hash_name = 'image'.uniqid().date("Y-m-d"); // Generate a unique, random name...
            $hashed_name = $file->hashName();
            $stored_path = $file->storeAs('product/temp',$hashed_name,'public');
        }
        $file_path  = Storage::path("public/product/temp/".$hashed_name);
        $storage_path = str_replace(["\\","/"],"\\\\",$file_path);
        $response = $this->storeImportedData($storage_path, $stored_path);

        return response()->json(['status' => $response ,'message' => 'Product added successfully']);
    }

    private function storeImportedData($storage_path, $stored_path){
        try {
            $pdo = DB::connection()->getpdo();
     
             $pdo->exec(
                            "LOAD DATA LOCAL INFILE '{$storage_path}' 
                            INTO TABLE products
                            FIELDS TERMINATED BY '\t'
                            ENCLOSED BY '\"'
                            LINES TERMINATED  BY '\\n'
                            (name,price,quantity,description)"
                        );

            Storage::disk('public')->delete($stored_path);

            return  200;
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}