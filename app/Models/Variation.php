<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Variation extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;

    /**
     * formattedPrice
     *
     * @return void
     */
    public function formattedPrice()
    {
        return money($this->price);
    }

    /**
     * inStock
     *
     * @return Boolean
     */
    public function inStock()
    {
        return $this->stockCount() > 0;
    }

    /**
     * outStock
     *
     * @return Boolean
     */
    public function outOfStock()
    {
        return !$this->inStock();
    }

    /**
     * lowStock
     *
     * @return void
     */
    public function lowStock()
    {
        return !$this->outOfStock() && $this->stockCount() <= 5;
    }

    public function stockCount()
    {
        return $this->descendantsAndSelf->sum(
            fn ($variation) => $variation->stocks->sum('amount')
        );
    }

    /**
     * stocks
     *
     * @return void
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * product
     *
     * @return void
     */
    public function product()
    {
        $this->belongsTo(Product::class);
    }
}
