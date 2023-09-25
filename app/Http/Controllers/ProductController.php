<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Interfaces\ProductFilterInterface;
use App\Interfaces\ProductSorterInterface;

class ProductController extends Controller
{
    private ProductFilterInterface $productFilter;
    private ProductSorterInterface $productSorter;

    public function __construct(ProductFilterInterface $productFilter, ProductSorterInterface $productSorter) 
    {
        $this->productFilter = $productFilter;
        $this->productSorter = $productSorter;
    }

    public function show(Product $product): string
    {
        return $product->toJson();
    }

    public function list()
    {
        return Product::paginate();
    }

    public function filter()
    {
        $productQuery = $this->productFilter->assignFiltersToProductQuery(request());
        $productQuery = $this->productSorter->assignSortersToProductQuery($productQuery, request());

        return $productQuery->paginate()->appends(request()->query());
    }
}
