<?php

namespace App\Processors\Order;

use App\Interfaces\OrderItemProcessorInterface;
use App\Interfaces\OrderProcessorInterface;
use App\Interfaces\OrderTotalModifierInterface;
use App\Models\Order;
use Exception;

class OrderProcessor implements OrderProcessorInterface 
{
    private OrderItemProcessorInterface $orderItemProcessor;
    private OrderTotalModifierInterface $orderTotalModifier;
    private int $orderTotal = 0;
    private array $priceModificators = [];

    public function __construct(OrderItemProcessorInterface $orderItemProcessor, OrderTotalModifierInterface $orderTotalModifier) 
    {
        $this->orderItemProcessor = $orderItemProcessor;
        $this->orderTotalModifier = $orderTotalModifier;
    }

    public function processOrder(array $orderData)
    {
        $this->resetOrderProcessing();

        try{
            $processedOrderItems = $this->orderItemProcessor->processOrderItems($orderData, $orderData['items']);
        }
        catch (Exception $e) {
            return response()->json(['error' => 'Error occurred!', 'message' => $e->getMessage()], 500);
        }

        $this->orderTotal = $this->orderItemProcessor->getAggregatedPrice();
        $this->priceModificators[] = $this->orderTotalModifier->executeOrterTotalModifierPlugins($this->orderTotal);

        $createdOrder = $this->createOrder($orderData);

        foreach($processedOrderItems as $processedOrderItem){
            $processedOrderItem->order_id = $createdOrder->id;
            $processedOrderItem->save();
        }

        return $createdOrder;

    }

    private function createOrder(array $orderData): Order
    {
        return Order::create([
            'user_id' => $orderData['user_id'],
            'first_name' => $orderData['first_name'],
            'last_name' => $orderData['last_name'],
            'email' => $orderData['email'],
            'phone' => $orderData['phone'],
            'total_price_modifiers' => json_encode($this->priceModificators), //TODO won't save, see the reason
        ]);
    }

    private function resetOrderProcessing(): void
    {
        $this->orderTotal = 0;
        $this->priceModificators = [];
    }
}