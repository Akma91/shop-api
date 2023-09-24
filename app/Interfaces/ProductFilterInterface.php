<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ProductFilterInterface
{
    public function assignFiltersToProductQuery(Request $request);
}