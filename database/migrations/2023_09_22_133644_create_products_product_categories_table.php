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
        Schema::create('products_product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->unsignedBigInteger('product_category_id');//TODO needs to be unique

            $table->foreign('sku')->references('sku')->on('products');
            $table->foreign('product_category_id')->references('id')->on('product_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_product_categories');
    }
};
