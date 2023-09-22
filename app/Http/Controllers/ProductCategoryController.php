<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function list(ProductCategory $productCategory)
    {
        $productCategory->load('products');
        return $productCategory->toJson();
    }
}