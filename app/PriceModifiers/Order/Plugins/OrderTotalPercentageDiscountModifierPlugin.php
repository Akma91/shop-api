<?php

namespace App\PriceModifiers\Order\Plugins;

use App\Interfaces\OrderTotalPercentageDiscountModifierPluginInterface;

class OrderTotalPercentageDiscountModifierPlugin implements OrderTotalPercentageDiscountModifierPluginInterface 
{
    private const MODIFIER_TITLE = 'THRESHOLD_DISCOUNT';
    private const PRICE_THRESHOLD_TO_APPLY_DISCOUNT = 10000;
    private const DISCOUNT_PERCENTAGE = -10;

    public function isApplicable(int $orderTotal): bool
    {
        return $orderTotal > self::PRICE_THRESHOLD_TO_APPLY_DISCOUNT;
    }

    public function modifyOrderTotal(int $orderTotal): array
    {
        return ['MODIFIER_TITLE' => self::MODIFIER_TITLE, 'VALUE' => self::DISCOUNT_PERCENTAGE, 'FORMAT' => '%'];
    }
}