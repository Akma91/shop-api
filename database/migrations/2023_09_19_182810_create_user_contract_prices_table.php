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
        Schema::create('user_contract_prices', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('price');
            $table->string('sku');
            $table->timestamps();

            $table->primary(['user_id', 'sku']);

            $table->foreign('sku')->references('sku')->on('products')
            ->onDelete('cascade');
            // @TODO vidjeti onUpdate je li potreban
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_contract_prices');
    }
};
