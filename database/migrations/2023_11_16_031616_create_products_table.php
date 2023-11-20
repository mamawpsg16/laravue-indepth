<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default('0.00');
            $table->integer('quantity')->nullable()->default(0);
            $table->foreignId('category_id')->nullable()->constrained();
            $table->foreignId('brand_id')->nullable()->constrained();
            $table->foreignId('shop_id')->nullable()->constrained();
            $table->string('image_name')->nullable();
            $table->string('image')->nullable();
            $table->integer('active')->default(1);
            $table->timestamps();
            // Add other product columns as needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
