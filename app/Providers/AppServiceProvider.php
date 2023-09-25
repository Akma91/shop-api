<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Filters\Product\ProductFilter;
use App\Interfaces\OrderItemProcessorInterface;
use App\Interfaces\OrderProcessorInterface;
use App\Interfaces\OrderTotalModifierInterface;
use App\Interfaces\OrderTotalPercentageDiscountModifierPluginInterface;
use App\Interfaces\OrderTotalTaxModifierPluginInterface;
use App\Interfaces\OrderValidatorInterface;
use App\Interfaces\ProductFilterInterface;
use App\Interfaces\ProductSorterInterface;
use App\PriceModifiers\Order\OrderTotalModifier;
use App\PriceModifiers\Order\Plugins\OrderTotalPercentageDiscountModifierPlugin;
use App\PriceModifiers\Order\Plugins\OrderTotalTaxModifierPlugin;
use App\Processors\OrderItem\OrderItemProcessor;
use App\Processors\Order\OrderProcessor;
use App\Sorters\Product\ProductSorter;
use App\Validators\Order\OrderValidator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductFilterInterface::class, ProductFilter::class);
        $this->app->bind(ProductSorterInterface::class, ProductSorter::class);

        $this->app->bind(OrderValidatorInterface::class, OrderValidator::class);
        $this->app->bind(OrderProcessorInterface::class, OrderProcessor::class);
        $this->app->bind(OrderItemProcessorInterface::class, OrderItemProcessor::class);

        $this->app->bind(OrderTotalModifierInterface::class, OrderTotalModifier::class);
        $this->app->bind(OrderTotalPercentageDiscountModifierPluginInterface::class, OrderTotalPercentageDiscountModifierPlugin::class);
        $this->app->bind(OrderTotalTaxModifierPluginInterface::class, OrderTotalTaxModifierPlugin::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
