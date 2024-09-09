<?php

use App\Models\Product;

if (!function_exists('checkIfProductHasVariants')) {
    function checkIfProductHasVariants($productMainId)
    {
        $productCount = Product::where('productMainId', $productMainId)->count();
        return $productCount > 1;
    }
}
