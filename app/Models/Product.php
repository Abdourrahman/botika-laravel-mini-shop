<?php

namespace App\Models;

use App\Models\Scopes\LiveScope;
use Cknow\Money\Money;

use Laravel\Scout\Searchable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use function Clue\StreamFilter\fun;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use Searchable;

    public  static function booted()
    {
        static::addGlobalScope(new LiveScope());
    }
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

    /**
     * categories
     *
     * @return void
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    protected $guarded = ['*'];

    /**
     * toSearchableArray
     *
     * @return void
     */
    public function toSearchableArray()
    {

        return array_merge(
            [
                'id' => $this->id,
                'title' => $this->title,
                'slug' => $this->slug,
                'price' => $this->price,
                'category_ids' => $this->categories->pluck('id')->toArray(),
            ],
            $this->variations->groupBy('type')->mapWithKeys(fn ($variation, $key) => [
                $key => $variation->pluck('title')
            ])->toArray()
        );
    }
}
