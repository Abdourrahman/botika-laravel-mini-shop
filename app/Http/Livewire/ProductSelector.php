<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ProductSelector extends Component
{
    // {{ $product->variations->sortBy('order')->groupBy('type')->first() }}    
    /**
     * product
     *
     * @var mixed
     */
    public $product;

    public $initialVariation;

    /**
     * mount
     *
     * @return void
     */
    public function mount()
    {
        $this->initialVariation = $this->product->variations->sortBy('order')->groupBy('type')->first();
    }


    public function render()
    {
        return view('livewire.product-selector');
    }
}
