<?php

namespace App\Models;


use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * formattedPrice
     *
     * @return void
     */
    public function formattedPrice()
    {
        return money($this->price);
    }
}
