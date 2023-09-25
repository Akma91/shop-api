<?php

namespace App\Interfaces;


interface OrderTotalModifierPluginInterface
{
    public function isApplicable(int $orderTotal): bool;
    public function modifyOrderTotal(int $orderTotal): array;
}