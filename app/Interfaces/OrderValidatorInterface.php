<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface OrderValidatorInterface
{
    public function validateReceivedOrder(Request $request);
}