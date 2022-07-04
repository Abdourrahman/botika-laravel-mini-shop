<div class="space-y-6">
    @if ($initialVariation)
    <livewire:product-variation-dropdown :variations="$initialVariation" />
    @endif

    @if ($skuVariant)
    <div class="space-y-6">
        <div class="font-semibold text-lg">
            {{ $skuVariant->formattedPrice() }}
        </div>
        <x-button>Add to cart</x-button>
    </div>
    @endif

</div>