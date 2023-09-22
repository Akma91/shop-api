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
        Schema::create('products_price_lists', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->unsignedBigInteger('price_list_id');//staviti da kombinacije ove dvije vrijednosti mora biti unique

            $table->foreign('sku')->references('sku')->on('products');
            $table->foreign('price_list_id')->references('id')->on('price_lists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_price_lists');
    }
};
