<?php

namespace App\Http\Livewire;

use App\Cart\Contracts\CartInterface;
use Livewire\Component;

class Navigation extends Component

{
    protected $listeners = [
        'cart.updated' => '$refresh'
    ];

    public function getCartProperty(CartInterface $cart)
    {
        return $cart;
    }

    public function render()
    {
        return view('livewire.navigation');
    }
}
