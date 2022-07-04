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
     * product
     *
     * @return void
     */
    public function product()
    {
        $this->belongsTo(Product::class);
    }
}
