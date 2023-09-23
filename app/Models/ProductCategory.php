<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $hidden = ['pivot'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_product_categories', 'product_category_id', 'sku', 'id', 'sku');
    }
}
