<?php

namespace App\Cart;

use App\Cart\Contracts\CartInterface;
use App\Models\Cart as ModelsCart;
use App\Models\User;
use Illuminate\Session\SessionManager;

class Cart implements CartInterface
{
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
}
