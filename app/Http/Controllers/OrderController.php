<?php

namespace App\Http\Controllers;

use App\Interfaces\OrderProcessorInterface;
use App\Interfaces\OrderValidatorInterface;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    private OrderValidatorInterface $orderValidator;
    private OrderProcessorInterface $orderProcessor;

    public function __construct(OrderValidatorInterface $orderValidator, OrderProcessorInterface $orderProcessor) 
    {
        $this->orderValidator = $orderValidator;
        $this->orderProcessor = $orderProcessor;
    }

    public function store()
    {
        $orderData = $this->orderValidator->validateReceivedOrder(request());

        if($this->isOrderDataInvalid($orderData)){
            return $orderData;
        }

        $processedOrderData = $this->orderProcessor->processOrder($orderData);

        if($this->errorOccurredOnOrderProcessing($processedOrderData)){
            return $processedOrderData;
        }

        return response()->json(
            ['message' => 'Success', 'order_id' => $processedOrderData->id], 201
        );
    }

    private function isOrderDataInvalid($orderData)
    {
        return $orderData instanceof JsonResponse && $orderData->getStatusCode() === 422;
    }

    private function errorOccurredOnOrderProcessing($orderData)
    {
        return $orderData instanceof JsonResponse && $orderData->getStatusCode() === 500;
    }
}
