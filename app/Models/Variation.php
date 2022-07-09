<?php

namespace App\Models;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Variation extends Model implements HasMedia
{
    use HasFactory;
    use HasRecursiveRelationships;
    use InteractsWithMedia;

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
        return $this->belongsTo(Product::class);    
    }

    /**
     * registerMediaConversions
     *
     * @param  mixed $media
     * @return void
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb200x200')->fit(Manipulations::FIT_CROP, 200, 200);
    }

    /**
     * registerMediaCollections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')->useFallBackUrl(url('/storage/no-photo.png'));
    }
}
