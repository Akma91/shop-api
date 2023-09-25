<?php

namespace App\Validators\Order;

use App\Interfaces\OrderValidatorInterface;
use Illuminate\Http\Request;
use Exception;

class OrderValidator implements OrderValidatorInterface 
{
    public function validateReceivedOrder(Request $request)
    {
        try {
            $validatedResponse = $request->validate([
                'user_id' => 'integer',
                'first_name' => 'required|string|min:1|max:50',
                'last_name' => 'required|string|min:1|max:50',
                'email' => 'required|string|email:rfc,dns',
                'phone' => 'required|string|regex:/[\+0-9 \-]{6,20}/',
                'items' => 'required|array|min:1',
                'items.*.sku' => 'required|string|min:3|max:15',
                'items.*.product_name' => 'required|string|min:1|max:100',
                'items.*.quantity' => 'required|numeric|between:1,100',
                'items.*.price_list_name' => 'required|string|min:1|max:50',
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Data not valid!', 'message' => $e->getMessage()], 422);
        }

        return $validatedResponse;
    }
}
