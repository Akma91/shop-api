<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface ProductSorterInterface
{
    public function assignSortersToProductQuery(Builder $productQuery, Request $request);
}