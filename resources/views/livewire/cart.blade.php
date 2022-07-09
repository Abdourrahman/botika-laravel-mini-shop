@if (!$cart->isEmpty())
<div class="overflow-hidden grid grid-cols-6 grid-flow-col gap-4">
    <div class="p-6 bg-white border-b border-gray-200 col-span-4 -mt-3 self-start">
        @foreach ($cart->contents() as $variation )
        <livewire:cart-item :variation="$variation" :key="$variation->id" />
        @endforeach
    </div>

    <div class="p-6 bg-white border-b border-gray-200 col-span-2 self-start">
        <div class="space-y-4">
            <div class="space-y-1">
                <div class="space-y-1 flex items-center justify-between">
                    <div class="font-semibold">Subtotal</div>
                    <h1 class="font-semibold text-xl">
                        Formnatted subtotal
                    </h1>
                </div>
            </div>

            <a href="/checkout">Checkout</a>
        </div>

    </div>
</div>
@else
<div class="p-6 bg-white border-b border-gray-200">
    Your cart is empty
</div>
@endif