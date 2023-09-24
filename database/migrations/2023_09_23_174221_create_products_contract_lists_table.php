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
        Schema::create('products_contract_lists', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->unsignedBigInteger('contract_list_id');
            $table->integer('price');

            $table->unique(['sku', 'contract_list_id']);

            $table->foreign('sku')->references('sku')->on('products');
            $table->foreign('contract_list_id')->references('id')->on('contract_lists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_contract_lists');
    }
};
