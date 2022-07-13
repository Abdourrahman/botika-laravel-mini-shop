<?php

namespace App\Cart;

use App\Models\User;
use App\Models\Variation;
use App\Models\Cart as ModelsCart;
use App\Cart\Contracts\CartInterface;
use Illuminate\Session\SessionManager;

class Cart implements CartInterface
{
    /**
     * instance
     *
     * @var mixed
     */
    protected $instance;

    /**
     * __construct
     *
     * @param  mixed $session
     * @return void
     */
    public function __construct(protected SessionManager $session)
    {
    }

    /**
     * exists
     *
     * @return void
     */
    public function exists()
    {
        return $this->session->has(config('cart.session.key'));
    }

    /**
     * create
     *
     * @return void
     */
    public function create(?User $user = null)
    {
        $instance = ModelsCart::make();

        if ($user) {
            $instance->user()->associate($user);
        }
        $instance->save();


        $this->session->put(config('cart.session.key'), $instance->uuid);
    }

    /**
     * contents
     *
     * @return void
     */
    public function contents()
    {
        return $this->instance()->variations;
    }

    /**
     * contentsCount
     *
     * @return void
     */
    public function contentsCount()
    {
        return $this->contents()->count();
    }


    /**
     * add
     *
     * @param  mixed $variation
     * @param  mixed $quantity
     * @return void
     */
    public function add(Variation $variation, $quantity = 1)
    {

        if ($existingVariation = $this->getVariation($variation)) {

            $quantity += $existingVariation->pivot->quantity;
        }


        $this->instance()->variations()->syncWithoutDetaching([
            $variation->id => [
                'quantity' => min($quantity, $variation->stockCount())
            ]
        ]);
    }

    /**
     * isEmpty
     *
     * @return void
     */
    public function isEmpty()
    {
        return $this->contents()->count() == 0;
    }

    public function remove(Variation $variation)
    {
        $this->instance()->variations()->detach($variation);
    }

    /**
     * changeQuantity
     *
     * @param  mixed $variation
     * @param  mixed $quantity
     * @return void
     */
    public function changeQuantity($variation, $quantity)
    {
        $this->instance()->variations()->updateExistingPivot($variation->id, [
            'quantity' => min($quantity, $variation->stockCount())
        ]);
    }

    /**
     * getVariation
     *
     * @param  mixed $variation
     * @return void
     */
    public function getVariation(Variation $variation)
    {
        return $this->instance()->variations->find($variation->id);
    }
    /**
     * instance
     *
     * @return void
     */
    protected function instance()
    {
        if ($this->instance) {
            return $this->instance;
        }

        return $this->instance =  ModelsCart::query()
            ->with('variations.product', 'variations.ancestorsAndSelf', 'variations.descendantsAndSelf.stocks',  'variations.media')
            ->whereUuid($this->session->get(config('cart.session.key')))->first();
    }


    public function subtotal()
    {
        return  $this->instance()->variations->reduce(function ($carry, $variation) {
            return $carry + ($variation->price * $variation->pivot->quantity);
        });
    }

    public function formattedSubtotal()
    {
        return money($this->subtotal());
    }
}
