<?php

namespace App\Models;


use Cknow\Money\Money;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory;
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
     * variations
     *
     * @return void
     */
    public function variations()
    {
        return $this->hasMany(Variation::class);
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
