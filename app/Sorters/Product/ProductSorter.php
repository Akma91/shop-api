<?php

namespace App\Sorters\Product;

use App\Interfaces\ProductSorterInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductSorter implements ProductSorterInterface 
{
    private const ORDER_BY_PARAMETER = 'orderBy';
    private const ORDER_ASC_PARAMETER_VALUE = 'asc';
    private const ORDER_DESC_PARAMETER_VALUE = 'desc';
    private const ORDER_BY_POSSIBLE_VALUES = ['name', 'price'];

    private Builder $productQuery;

    public function assignSortersToProductQuery(Builder $productQuery, Request $request)
    {
        $this->productQuery = $productQuery;

        if(!$this->orderByParameterExist($request) || !$this->isOrderByParameterInPossibleValues($request)){
            return $productQuery;
        }

        return $this->assignOrderByByValueAndType($request);
    }

    private function assignOrderByByValueAndType(Request $request)
    {
        $orderByValue = $request->query('orderBy');
        $orderByType = $request->query('orderType');

        $queryColumnName = $this->mapGetParameterToDatabaseColumn($orderByValue);

        if ($request->query('orderType') === self::ORDER_ASC_PARAMETER_VALUE || !$orderByType){
            $this->productQuery->orderBy($queryColumnName, 'asc');
        } else if ($request->query('orderType') === self::ORDER_DESC_PARAMETER_VALUE){
            $this->productQuery->orderBy($queryColumnName, 'desc');
        }

        return $this->productQuery;
    }

    private function orderByParameterExist(Request $request)
    {
        return $request->has(self::ORDER_BY_PARAMETER);
    }

    private function isOrderByParameterInPossibleValues(Request $request)
    {
        return in_array($request->query(self::ORDER_BY_PARAMETER), self::ORDER_BY_POSSIBLE_VALUES);
    }

    private function mapGetParameterToDatabaseColumn(string $orderByValue)
    {
        //TODO make separate mapper for this
        if($orderByValue === 'price'){
            $orderByValue = 'applied_price';
        }

        return $orderByValue;
    }
}
