<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PriceList;
use App\Models\Product;
use App\Models\UserContractPrice;

error_reporting(E_ALL);

class OrderController extends Controller
{
    public function store()
    {
        $validatedData = request()->validate([
            'user_id' => 'required|integer',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|string',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer',
            'items.*.price_list_name' => 'required|string',
        ]);

        //$order = Order::create($validatedData);

        $order = Order::create([
            'user_id' => $validatedData['user_id'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
        ]);

        foreach ($validatedData['items'] as $orderItemData) {
            $orderItem = new OrderItem($orderItemData);
            $orderItemSku = $orderItemData['sku'];

            $priceList = PriceList::where('name', $orderItemData['price_list_name'])
                ->whereHas('products', function ($query) use ($orderItemSku) {
                    $query->where('products.sku', $orderItemSku);
                })->first();

            
            //$userContractPrice = UserContractPrice::where('user_id', $validatedData['user_id'])->where('sku', $orderItemSku)->first();
            $userContractPrice = UserContractPrice::where('user_id', 1)->where('sku', '18381')->first();

            $appliedPrice = $userContractPrice->price ?: $priceList->price;

            $orderItem->appliedUnitPrice = $appliedPrice;


            // RADI:
            // $priceList = PriceList::where('name', $orderItemData['price_list_name'])->wherePivot('sku', $orderItemData['sku'])->firstOrFail();
            // $product = $priceList->products()->wherePivot('sku', $orderItemData['sku'])->firstOrFail();

           // $userContractPrice = UserContractPrice::where('user_id', $validatedData['user_id'])->where('sku', $orderItemData['sku'])->first();

            //$order->orderItems()->save($orderItem);
        }

        return response()->json(
            ['message' => 'Order created successfully', 
            'order' => $order, 
            'priceList' => $priceList, 
            'userContract' => $userContractPrice,
            'appliedUnitPrice' => $appliedPrice], 201
        );
    }

    /*
                                     $table->integer('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
    
        {
   "user_id": 15,
   "first_name": "Matej",
   "last_name": "Akma",
   "email": "matejakmacic12@gmail.com",
   "phone": "095/8152637",
   "items": [
      {
        "sku": 101,
        "product_name": "Product A",
        "quantity": 2,
        "price_list_name": "Default"
      },
      {
        "sku": 1000,
        "product_name": "Product B",
        "quantity": 2,
        "price_list_name": "Default"
      }
    ]
}*/
}
