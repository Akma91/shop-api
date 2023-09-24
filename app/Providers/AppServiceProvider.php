<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Filters\Product\ProductFilter;
use App\Interfaces\ProductFilterInterface;
use App\Interfaces\ProductSorterInterface;
use App\Sorters\Product\ProductSorter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductFilterInterface::class, ProductFilter::class);
        $this->app->bind(ProductSorterInterface::class, ProductSorter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
