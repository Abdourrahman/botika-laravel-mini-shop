<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;
use App\Models\ShippingType;
use App\Models\ShippingAddress;
use App\Cart\Contracts\CartInterface;
use App\Mail\OrderCreated;
use Illuminate\Support\Facades\Mail;

class Checkout extends Component
{

    /**
     * shippingTypeId
     *
     * @var mixed
     */
    public $shippingTypeId;

    /**
     * shippingType
     *
     * @var mixed
     */
    public $shippingTypes;


    /**
     * ShippingAddress
     *
     * @var mixed
     */
    protected $shippingAddress;

    /**
     * userShippingAddressId
     *
     * @var mixed
     */
    public $userShippingAddressId;

    /**
     * validationAttributes
     *
     * @var array
     */
    protected $validationAttributes = [
        'accountForm.email' => 'email address',
        'shippingForm.address' => 'shipping address',
        'shippingForm.city' => 'shipping city',
        'shippingForm.postcode' => 'shipping postcode',
        'shippingTypeId' => 'required|exists:shipping_types,id',
    ];

    protected $messages = [
        'accountForm.email.unique' => 'Seems you already have an account. Please sign in to place an order.',
        'shippingForm.address.required' => 'Your :attribute is required.'
    ];

    public $accountForm = [
        'email' => 'user@gmail.com'
    ];
    public $shippingForm = [
        'address' => 'Adr Line',
        'city' => 'Tolga',
        'postcode' => '07003'
    ];

    /**
     * checkout
     *
     * @return void
     */
    public function checkout(CartInterface $cart)
    {
        $this->validate();

        $this->shippingAddress = ShippingAddress::query();

        if (auth()->user()) {
            $this->shippingAddress = $this->shippingAddress->whereBelongsTo(auth()->user());
        }

        ($this->shippingAddress = $this->shippingAddress->firstOrCreate($this->shippingForm))
            ?->user()
            ->associate(auth()->user())->save();

        // order
        $order = Order::make(array_merge($this->accountForm, [
            'subtotal' => $cart->subtotal()
        ]));


        // if the user is logged in 
        $order->user()->associate(auth()->user());
        $order->shippingAddress()->associate($this->shippingAddress);
        $order->shippingType()->associate($this->shippingType);
        // save the order
        $order->save();

        $order->variations()->attach(
            $cart->contents()->mapWithKeys(function ($variation) {
                return [
                    $variation->id => [
                        'quantity' => $variation->pivot->quantity
                    ]
                ];
            })
                ->toArray()
        );

        $cart->contents()->each(function ($variation) {
            $variation->stocks()->create([
                'amount' => 0 - $variation->pivot->quantity
            ]);
        });

        $cart->removeAll();

        // send email when order created
        Mail::to($order->email)->send(new OrderCreated($order));

        $cart->destroy();

        if (!auth()->user()) {
            return redirect()->route('orders.confirmation', $order);
        }

        return redirect()->route('orders');
    }

    /**
     * getUserShippingAddressesProperty
     *
     * @return void
     */
    public function getUserShippingAddressesProperty()
    {
        return auth()->user()?->shippingAddresses;
    }

    public function updatedUserShippingAddressId($id)
    {

        if (!$id) {
            $this->shippingForm = "";
            return;
        }

        $this->shippingForm = $this->userShippingAddresses->find($id)->only('address', 'city', 'postcode');
    }


    public function rules()
    {
        return [
            'accountForm.email' => 'required|email|max:255|unique:users,email' . (auth()->user() ? ',' . auth()->user()->id : ''),
            'shippingForm.address' => 'required|max:255'

        ];
    }

    /**
     * mount
     *
     * @return void
     */
    public function mount()
    {
        $this->shippingTypes = ShippingType::orderBy('price', 'asc')->get();
        $this->shippingTypeId = $this->shippingTypes->first()->id;

        if ($user = auth()->user()) {
            $this->accountForm['email'] = $user->email;
        }
    }

    /**
     * getShippingTypeProperty
     *
     * @return void
     */
    public function getShippingTypeProperty()
    {
        return $this->shippingTypes->find($this->shippingTypeId);
    }

    /**
     * getTotalProperty
     *
     * @param  mixed $cart
     * @return void
     */
    public function getTotalProperty(CartInterface $cart)
    {
        return $cart->subtotal() + $this->shippingType->price;
    }
    /**
     * render
     *
     * @param  mixed $cart
     * @return void
     */
    public function render(CartInterface $cart)
    {
        return view('livewire.checkout', [
            'cart' => $cart,
        ]);
    }
}
