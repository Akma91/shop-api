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
        Product::factory(100)->create();
        UserContractPrice::factory(100)->create();
        PriceList::factory(100)->create();
        ProductCategory::factory(100)->create();

        $this->productPriceListPivotSeeder();
        $this->productProductsCategoryPivotSeeder();
        /*Product::factory()
            ->hasAttached(
                PriceList::factory()->count(10),
                ['sku' => Product::all()->random()->sku],
            )
            ->create();
        Product::factory()
            ->hasAttached(
                PriceList::factory()->count(10),
                ['sku' => Product::all()->random()->sku],
            )
            ->create();
        /*PriceList::factory()
        ->hasAttached(
            Product::factory()->count(3)
        )
        ->create();*/
    }

    private function productPriceListPivotSeeder()
    {
        $priceLists = PriceList::all();
        $products = Product::all();

        foreach ($priceLists as $priceList) {
            $priceList->products()->attach($products->random());
        }
    }

    private function productProductsCategoryPivotSeeder()
    {
        $productCategories = ProductCategory::all();
        $products = Product::all();

        foreach ($productCategories as $productCategory) {
            $productCategory->products()->attach($products->random());
        }
    }
}
