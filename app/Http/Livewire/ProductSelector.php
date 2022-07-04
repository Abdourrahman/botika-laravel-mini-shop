<?php

namespace App\Http\Livewire;

use App\Models\Variation;
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

    /**
     * initialVariation
     *
     * @var mixed
     */
    public $initialVariation;


    public $skuVariant;

    /**
     * mount
     *
     * @return void
     */
    public function mount()
    {
        $this->initialVariation = $this->product->variations->sortBy('order')->groupBy('type')->first();
    }


    /**
     * listeners
     *
     * @var array
     */
    protected $listeners = [
        'skuVariantSelected'
    ];

    

    /**
     * skuVariantSelected
     *
     * @param  mixed $variantId
     * @return void
     */
    public function skuVariantSelected($variantId)
    {
        if (!$variantId) {
            $this->skuVariant = null;
            return;
        }
        $this->skuVariant = Variation::find($variantId);
    }

    /**
     * render
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.product-selector');
    }
}
