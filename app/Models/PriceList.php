<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    use HasFactory;

    protected $hidden = ['pivot'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_price_lists', 'price_list_id', 'sku', 'id', 'sku');
    }
}
