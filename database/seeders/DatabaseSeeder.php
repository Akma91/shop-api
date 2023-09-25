<?php

namespace Database\Seeders;

use App\Models\ContractList;
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
        ContractList::factory(10)->create();
        PriceList::factory(50)->create();
        ProductCategory::factory(500)->create();

        $this->productContractListPivotSeeder();
        $this->productPriceListPivotSeeder();
        $this->productProductsCategoryPivotSeeder();
    }

    private function productContractListPivotSeeder()
    {
        $contractLists = ContractList::all();

        $products = Product::all();
        $oneThirdCount = Product::all()->count() / 3;
        $productsToPutInContractLists = $products->take($oneThirdCount);

        $filledCombinations = [];
        foreach ($productsToPutInContractLists as $key => $product) {
            for ($x = 0; $x <= 5; $x++) {

                // ovo se može obrisati
                if($key == 0 || $key % 10 == 0){
                    $randomContractList = $contractLists->random();
                }
    
                $fillCombination = $product->sku . '-' . $randomContractList->id;
            
                if (!in_array($fillCombination, $filledCombinations)) {
                    $product->contractLists()->attach($randomContractList, ['price' => random_int(1000, 1000000)]);
                    $filledCombinations[] = $fillCombination;
                }
              } 
        }
    }

    private function productPriceListPivotSeeder()
    {
        $priceLists = PriceList::all();
        $products = Product::all();


        $filledCombinations = [];
        foreach ($products as $product) {
            for ($x = 0; $x <= 10; $x++) {
                $randomPriceList = $priceLists->random();

                $fillCombination = $product->sku . '-' . $randomPriceList->id;
                
                if (!in_array($fillCombination, $filledCombinations)) {
                    $product->priceLists()->attach($randomPriceList, ['price' => random_int(1000, 1000000)]);
                    $filledCombinations[] = $fillCombination;
                }
            }
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
