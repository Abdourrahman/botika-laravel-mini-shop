<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartIndexController extends Controller
{
    public function __invoke()
    {
        return view('cart.index');
    }
}
