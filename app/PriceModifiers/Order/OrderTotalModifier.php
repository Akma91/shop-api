<?php

namespace App\PriceModifiers\Order;

use App\Interfaces\OrderTotalModifierInterface;
use App\Interfaces\OrderTotalPercentageDiscountModifierPluginInterface;
use App\Interfaces\OrderTotalTaxModifierPluginInterface;

class OrderTotalModifier implements OrderTotalModifierInterface
{
    private array $orderTotalModifiers = [];

    public function __construct(
        OrderTotalPercentageDiscountModifierPluginInterface $orderTotalPercentageDiscountModifierPlugin, 
        OrderTotalTaxModifierPluginInterface $orderTotalTaxModifierPlugin
    )
    {
        $this->orderTotalModifiers[] = $orderTotalPercentageDiscountModifierPlugin;
        $this->orderTotalModifiers[] = $orderTotalTaxModifierPlugin;
    }

    public function executeOrterTotalModifierPlugins($orderTotal): array
    {
        $priceModifiers = [];

        foreach($this->orderTotalModifiers as $orderTotalModifier){
            if($orderTotalModifier->isApplicable($orderTotal)){
                $priceModifiers[] = $orderTotalModifier->modifyOrderTotal($orderTotal);
            }
        }

        return $priceModifiers;
    }
}