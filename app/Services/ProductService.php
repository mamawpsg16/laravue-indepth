<?php
namespace App\Services;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService{
    public function __construct(){

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
        $response = $this->store($storage_path, $stored_path);

        return response()->json(['status' => $response ,'message' => 'Product added successfully']);
    }

    private function store($storage_path, $stored_path){
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