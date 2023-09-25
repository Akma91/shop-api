<?php

namespace App\PriceModifiers\Order\Plugins;

use App\Interfaces\OrderTotalTaxModifierPluginInterface;

class OrderTotalTaxModifierPlugin implements OrderTotalTaxModifierPluginInterface 
{
    private const MODIFIER_TITLE = 'TAX';
    private const TAX_PERCENTAGE = 25;

    public function isApplicable(int $orderTotal): bool
    {
        return true;
    }

    public function modifyOrderTotal(int $orderTotal): array
    {
        return ['MODIFIER_TITLE' => self::MODIFIER_TITLE, 'VALUE' => self::TAX_PERCENTAGE, 'FORMAT' => '%'];
    }
}