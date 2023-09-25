<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface OrderItemProcessorInterface
{
    public function processOrderItems(array $orderData, array $orderItemsData);
    public function getAggregatedPrice(): int;
}