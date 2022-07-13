<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Cart\Contracts\CartInterface;

class Navigation extends Component

{
    /**
     * searchQuery
     *
     * @var string
     */
    public $searchQuery = '';

    /**
     * listeners
     *
     * @var array
     */
    protected $listeners = [
        'cart.updated' => '$refresh'
    ];

    public function getCartProperty(CartInterface $cart)
    {
        return $cart;
    }

    public function clearSearch()
    {
        $this->searchQuery = '';
    }

    public function render()
    {
        $products = Product::search($this->searchQuery)->get();

        return view('livewire.navigation', [
            'products' => $products
        ]);
    }
}
