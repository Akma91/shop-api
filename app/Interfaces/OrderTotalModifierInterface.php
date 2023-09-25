<?php

namespace App\Interfaces;


interface OrderTotalModifierInterface
{
    public function executeOrterTotalModifierPlugins($orderTotal): array;
}