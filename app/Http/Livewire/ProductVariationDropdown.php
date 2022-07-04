<?php

namespace App\Http\Livewire;

use App\Models\Variation;
use Livewire\Component;

class ProductVariationDropdown extends Component
{
    /**
     * variations
     *
     * @var mixed
     */
    public $variations;

    /**
     * selectedVariation
     *
     * @var mixed
     */
    public $selectedVariation;


    /**
     * getSelectedVariationModelProperty
     *
     * @return void
     */
    public function getSelectedVariationModelProperty()
    {
        if (!$this->selectedVariation) {
            return;
        }
        return Variation::find($this->selectedVariation);
    }

    /**
     * updatedSelectedVariation
     *
     * @return void
     */
    public function updatedSelectedVariation()
    {
        $this->emitTo('product-selector', 'skuVariantSelected', null);
        if ($this->selectedVariationModel?->sku) {
            $this->emitTo('product-selector', 'skuVariantSelected', $this->selectedVariation);
        }
    }

    public function render()
    {
        return view('livewire.product-variation-dropdown');
    }
}
