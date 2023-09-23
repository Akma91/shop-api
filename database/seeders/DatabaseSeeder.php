<?php

namespace Database\Seeders;

use App\Models\PriceList;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\UserContractPrice;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Product::factory(1000)->create();
        UserContractPrice::factory(1000)->create();
        PriceList::factory(100)->create();
        ProductCategory::factory(500)->create();

        $this->productPriceListPivotSeeder();
        $this->productProductsCategoryPivotSeeder();
    }

    private function productPriceListPivotSeeder()
    {
        $priceLists = PriceList::all();
        $products = Product::all();

        foreach ($priceLists as $priceList) {
            $priceList->products()->attach($products->random());
        }

        foreach ($products as $key => $product) {
            if($key == 0 || $key % 10 == 0){
                $randomPriceList = $priceLists->random();
            }
            
            $product->priceLists()->attach($randomPriceList);
        }
    }

    private function productProductsCategoryPivotSeeder()
    {
        $productCategories = ProductCategory::all();
        $products = Product::all();

        foreach ($productCategories as $productCategory) {
            $productCategory->products()->attach($products->random());
        }

        foreach ($products as $key => $product) {
            if($key == 0 || $key % 10 == 0){
                $randomProductCategory = $productCategories->random();
            }
            
            $product->productCategories()->attach($randomProductCategory);
        }
    }
}
