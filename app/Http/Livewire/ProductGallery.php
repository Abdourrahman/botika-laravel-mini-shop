<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ProductGallery extends Component
{
    /**
     * product
     *
     * @var mixed
     */
    public $product;

    /**
     * selectedImageUrl
     *
     * @var mixed
     */
    public $selectedImageUrl;

    /**
     * mount
     *
     * @return void
     */
    public function mount()
    {
        $this->selectedImageUrl = $this->product->getFirstMediaUrl();
    }

    /**
     * selectImage
     *
     * @param  mixed $url
     * @return void
     */
    public function selectImage($url)
    {
        $this->selectedImageUrl = $url;
    }

    /**
     * render
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.product-gallery');
    }
}
