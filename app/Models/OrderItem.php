<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'product_name',
        'quantity',
        'price_list_name',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
