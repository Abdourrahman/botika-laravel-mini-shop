<?php

namespace App\Models;

use App\Models\Presenters\OrderPresenter;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use function Clue\StreamFilter\fun;

class Order extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    public $fillable = [
        'email',
        'subtotal',
        'plcaed_at',
        'packaged_at',
        'shipped_at'
    ];

    /**
     * timestamps
     *
     * @var array
     */
    public $timestamps = [
        'placed_at',
        'packaged_at',
        'shipped_at'
    ];

    /**
     * statuses
     *
     * @var array
     */
    public $statuses = [
        'placed_at',
        'packaged_at',
        'shipped_at'
    ];

    /**
     * booted
     *
     * @return void
     */
    public static function booted()
    {
        static::creating(function (Order $order) {
            $order->placed_at = now();
            $order->uuid = (string) Str::uuid();
        });
    }

    /**
     * user
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * shippingType
     *
     * @return void
     */
    public function shippingType()
    {
        return $this->belongsTo(ShippingType::class);
    }

    /**
     * shippingAddress
     *
     * @return void
     */
    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    /**
     * formattedSubtotal
     *
     * @return void
     */
    public function formattedSubtotal()
    {
        return money($this->subtotal);
    }

    /**
     * status
     *
     * @return void
     */
    public function status()
    {

        return collect($this->statuses)->last(
            function ($status) {
                return filled($this->{$status});
            }
        );
    }

    /**
     * variations
     *
     * @return void
     */
    public function variations()
    {
        return $this->belongsToMany(Variation::class)->withPivot(['quantity'])->withTimestamps();
    }

    public function presenter()
    {
        return new OrderPresenter($this);
    }
}
