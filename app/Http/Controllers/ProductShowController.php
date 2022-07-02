<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductShowController extends Controller
{
    public function __invoke(Product $product)
    {
        return view(
            'products.show',
            ['product' => $product]
        );
    }
}
