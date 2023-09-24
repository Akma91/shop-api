<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $hidden = ['pivot'];

    public function contractLists()
    {
        return $this->belongsToMany(ContractList::class, 'products_contract_lists', 'sku', 'contract_list_id', 'sku', 'id')->withPivot('price');
    }

    public function priceLists()
    {
        return $this->belongsToMany(PriceList::class, 'products_price_lists', 'sku', 'price_list_id', 'sku', 'id')->withPivot('price');
    }

    public function productCategories()
    {
        return $this->belongsToMany(ProductCategory::class, 'products_product_categories', 'sku', 'product_category_id', 'sku', 'id');
    }

    /*public function getAppliedPriceAttribute()
    {

        $contractPriceCount = $this->contractLists->where('pivot.price', '>', 0)->count();
        $priceListPriceCount = $this->priceLists->where('pivot.price', '>', 0)->count();

        if ($contractPriceCount > 1 || $priceListPriceCount > 1) {
            return null;
        }

        if ($contractPriceCount === 1 && $priceListPriceCount === 1) {
            return $this->contractLists->sum('pivot.price');
        }

        if($priceListPriceCount === 1){
            return $this->priceLists->sum('pivot.price');
        }

        if($contractPriceCount === 1){
            return $this->contractLists->sum('pivot.price');
        }

        return null;
    }*/
}