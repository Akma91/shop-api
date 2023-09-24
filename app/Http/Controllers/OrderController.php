<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

//TODO obrisati ovo
error_reporting(E_ALL);

class OrderController extends Controller
{
    public function store()
    {

        //userId bi možda trebao biti optional
        $validatedData = request()->validate([
            'user_id' => 'integer',
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

        $orderPrice = 0;

        foreach ($validatedData['items'] as $orderItemData) {
            $orderItem = new OrderItem($orderItemData);
            $orderItemSku = $orderItemData['sku'];
            $priceListName = $orderItemData['price_list_name'];
            $orderItemQuantity = $orderItemData['quantity'];

            $productWithRequestedPriceList = Product::where('sku', $orderItemSku)
                ->whereHas('priceLists', function ($query) use ($priceListName) {
                    $query->where('name', $priceListName);
                })
                ->with(['priceLists' => function ($query) use ($priceListName) {
                    $query->where('name', $priceListName);
                }])
                ->first();

            if ($productWithRequestedPriceList) {
                $productPriceListPrice = $productWithRequestedPriceList->priceLists->first()->pivot->price;
            }

            if($validatedData['user_id']){
                $productContractListPrice = DB::table('products_contract_lists')
                ->leftJoin('contract_lists', 'products_contract_lists.contract_list_id', '=', 'contract_lists.id')
                ->where('user_id', $validatedData['user_id'])
                ->where('sku', $orderItemSku)
                ->select('price')
                ->first()->price;
            }

            //TODO dodati logiku sa novom arh


            $appliedPrice = $productContractListPrice ?: $productPriceListPrice;

            $appliedPriceWithQuantity = $appliedPrice * $orderItemQuantity;

            $orderPrice += $appliedPriceWithQuantity;

            //$orderItem->applied_unit_price = $appliedPrice;

            //$order->orderItems()->save($orderItem);
        }

        return response()->json(
            ['message' => 'Order created successfully', 
            'order' => $order, 
            'priceList' =>  $productPriceListPrice, 
            'userContract' => $productContractListPrice,
            'appliedUnitPrice' => $appliedPrice,
            'appliedFullPrice' => $appliedPriceWithQuantity,
            'orderPrice' => $orderPrice], 201
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
