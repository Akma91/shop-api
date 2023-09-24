<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        return $product->toJson();
    }

    public function list()
    {
        return Product::paginate();
    }

    public function filter(Request $request)
    {
        $productQuery = Product::query();

        $productQuery->select('products.*');

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

        ///// format categories
        $productQuery->with(['productCategories:name']);


        $coalesceArray = ['NULL'];

        //priceList
        ////////////////////////////
        ////////////////////////////

        if($request->query('priceList')){
            $priceList = $request->query('priceList');

            $productQuery->join('products_price_lists', 'products.sku', '=', 'products_price_lists.sku')
                ->join('price_lists', 'products_price_lists.price_list_id', '=', 'price_lists.id')
                ->where('price_lists.name', $priceList)
                ->selectRaw('products_price_lists.price as price_from_price_list')
                ->with(['priceLists' => function ($query) use ($priceList) {
                    $query->where('name', $priceList);
                }]);

            $coalesceArray[] = 'products_price_lists.price';
        }


        ////////////////////////////
        ////////////////////////////

        //contractList
        ////////////////////////////
        ////////////////////////////

        if($request->query('userId')){
            $userId = $request->query('userId');

            $productQuery->leftJoin('products_contract_lists', function ($join) use ($userId) {
                $join->on('products.sku', '=', 'products_contract_lists.sku')
                    ->join('contract_lists', 'products_contract_lists.contract_list_id', '=', 'contract_lists.id')
                    ->where('contract_lists.user_id', '=', $userId);
            }, 'filtered_contract_list');
            $productQuery->selectRaw('products_contract_lists.price as price_from_contract_list');


            $coalesceArray[] = 'products_contract_lists.price';
        }

        $coalesceArray = array_reverse($coalesceArray);

        $productQuery->selectRaw('COALESCE(' . implode(', ', $coalesceArray) . ', 0) AS applied_price');

        ////////////////////////////
        ////////////////////////////


        //price
        ////////////////////////////
        ////////////////////////////

        if($request->query('minPrice')){
            $productQuery->having('applied_price', '>', (int)$request->query('minPrice'));
        }

        if($request->query('maxPrice')){
            $productQuery->having('applied_price', '<', (int)$request->query('maxPrice'));
        }

        ////////////////////////////
        ////////////////////////////



        //sort
        ////////////////////////////
        ////////////////////////////
        if($request->query('orderBy') && ($request->query('orderBy') === 'name' || $request->query('orderBy') === 'price')){
            $column = $request->query('orderBy');

            if($column === 'price'){
                $column = 'applied_price';
            }

            if ($request->query('orderType') === 'asc' || !$request->query('orderType')){
                $productQuery->orderBy($column, 'asc');
            } else if ($request->query('orderType') === 'desc'){
                $productQuery->orderBy($column, 'desc');
            }

        }

        return $productQuery->paginate()->appends(request()->query());
    }

    //http://localhost/api/products/filter/?useId=1&priceList=&aqua&maxPrice=999200&minPrice=111&name=neko%20ime&productCategory=lolo&orderBy=price&orderType=asc
    //?name=inventore&productCategory=iure
}
