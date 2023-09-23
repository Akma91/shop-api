<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $productQuery = Product::query();

        return $productQuery->with('priceLists')->paginate();
        //return $product->toJson();
    }

    public function list()
    {
        return Product::paginate();
    }

    public function filter(Request $request)
    {
        $productQuery = Product::query();

        //name
        if ($request->query('name')) {
            $productQuery->where('products.name', $request->query('name'));
        }

        // category
        if ($request->query('productCategory')) {
            $productCategory = $request->query('productCategory');
            $productQuery->whereHas('productCategories', function ($productQuery) use ($productCategory) {
                $productQuery->where('name', $productCategory);
            });
        }

        //format
        $productQuery->with(['productCategories:name']);


        //price
        ////////////////////////////
        ////////////////////////////

        if($request->query('priceList')){
            $priceList = $request->query('priceList');

            /*$productQuery->with(['priceLists' => function ($query) use ($priceList) {
                $query->select('name', 'price')
                    ->where('price_lists.name', $priceList);
            }]);*/

            $productQuery->whereHas('priceLists', function ($productQuery) use ($priceList) {
                $productQuery->where('name', $priceList);
            });

            //$productQuery->with('priceLists')->where('price_lists.name', $priceList);
        }

        $productQuery->with(['priceLists:name,price']);

        $sortedProducts = $productQuery->get()->sortBy(function ($product) {
            $price = $product->priceLists->where('name', 'itaque')->first();
        
            return $price ? $price->price : 0;
        });

        ////////////////////////////
        ////////////////////////////


        return $sortedProducts->values();;
    }

    //http://localhost/api/products/filter/?useId=1&priceList=&aqua&maxPrice=999200&minPrice=111&name=neko%20ime&productCategory=lolo&orderBy=price&orderType=asc
    //?name=inventore&productCategory=iure
}
