<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function priceLists()
    {
        return $this->belongsToMany(PriceList::class, 'products_price_lists', 'sku', 'price_list_id', 'sku', 'id');
    }

    public function productCategories()
    {
        return $this->belongsToMany(ProductCategory::class, 'products_product_categories', 'sku', 'product_category_id', 'sku', 'id');
    }
}
