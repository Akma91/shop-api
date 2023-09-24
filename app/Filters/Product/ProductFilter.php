<?php

namespace App\Filters\Product;

use App\Interfaces\ProductFilterInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilter implements ProductFilterInterface 
{
    private const PRODUCT_NAME_FILTER_PARAMETER = 'name';
    private const PRODUCT_CATEGORY_FILTER_PARAMETER = 'productCategory';
    private const PRICE_LIST_FILTER_PARAMETER = 'priceList';
    private const CONTRACT_LIST_FILTER_PARAMETER = 'userId';
    private const MIN_PRICE_FILTER_PARAMETER = 'minPrice';
    private const MAX_PRICE_FILTER_PARAMETER = 'maxPrice';

    private Builder $productQuery;
    private array $possiblePricesArray = ['NULL'];

    public function assignFiltersToProductQuery(Request $request)
    {
        $this->productQuery = Product::query();

        $this->productQuery->select('products.*');

        $this->addfilterByName($request);
        $this->addfilterByCategory($request);
        $this->addfilterByPriceListName($request);

        $this->extendWithContractListPrice($request); //TODO Maybe move this because it does not really fillter, only adds contract price if it exists
        $this->extendWithAppliedPrice(); //TODO this also

        $this->addfiltersByPriceRange($request);

        return $this->productQuery;
    }

    private function addfilterByName($request)
    {
        if(!$request->has(self::PRODUCT_NAME_FILTER_PARAMETER)){
            return;
        }

        $this->productQuery->where('products.name', $request->query(self::PRODUCT_NAME_FILTER_PARAMETER));
    }

    private function addfilterByCategory($request)
    {
        if(!$request->has(self::PRODUCT_CATEGORY_FILTER_PARAMETER)){
            return;
        }

        $productCategory = $request->query(self::PRODUCT_CATEGORY_FILTER_PARAMETER);

        $this->productQuery->whereHas('productCategories', function ($productQuery) use ($productCategory) {
            $productQuery->where('name', $productCategory);
        });

        $this->productQuery->with(['productCategories:name']);
    }

    private function addfilterByPriceListName($request)
    {
        if(!$request->has(self::PRICE_LIST_FILTER_PARAMETER)){
            return;
        }

        $priceList = $request->query(self::PRICE_LIST_FILTER_PARAMETER);

        $this->productQuery->join('products_price_lists', 'products.sku', '=', 'products_price_lists.sku')
            ->join('price_lists', 'products_price_lists.price_list_id', '=', 'price_lists.id')
            ->where('price_lists.name', $priceList)
            ->selectRaw('products_price_lists.price as price_from_price_list')
            ->with(['priceLists' => function ($query) use ($priceList) {
                $query->where('name', $priceList);
            }]);

        $this->possiblePricesArray[] = 'products_price_lists.price';
    }

    private function extendWithContractListPrice($request)
    {
        if(!$request->has(self::CONTRACT_LIST_FILTER_PARAMETER)){
            return;
        }

        $userId = $request->query(self::CONTRACT_LIST_FILTER_PARAMETER);

        $this->productQuery->leftJoin('products_contract_lists', function ($join) use ($userId) {
            $join->on('products.sku', '=', 'products_contract_lists.sku')
                ->join('contract_lists', 'products_contract_lists.contract_list_id', '=', 'contract_lists.id')
                ->where('contract_lists.user_id', '=', $userId);
        }, 'filtered_contract_list');
        $this->productQuery->selectRaw('products_contract_lists.price as price_from_contract_list');


        $this->possiblePricesArray[] = 'products_contract_lists.price';
    }

    private function addfiltersByPriceRange($request)
    {
        if($request->has(self::MIN_PRICE_FILTER_PARAMETER)){
            $this->productQuery->having('applied_price', '>', (int)$request->query(self::MIN_PRICE_FILTER_PARAMETER));
        }

        if($request->has(self::MAX_PRICE_FILTER_PARAMETER)){
            $this->productQuery->having('applied_price', '<', (int)$request->query(self::MAX_PRICE_FILTER_PARAMETER));
        }
    }

    private function extendWithAppliedPrice()
    {
        $coalesceArray = array_reverse($this->possiblePricesArray);
        $this->productQuery->selectRaw('COALESCE(' . implode(', ', $coalesceArray) . ', 0) AS applied_price');
    }

}
