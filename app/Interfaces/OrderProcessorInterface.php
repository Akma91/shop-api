<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface OrderProcessorInterface
{
    public function processOrder(array $orderData);
}