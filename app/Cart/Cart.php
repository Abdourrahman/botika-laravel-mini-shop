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

        return $this->instance =  ModelsCart::whereUuid($this->session->get(config('cart.session.key')))->first();
    }
}
