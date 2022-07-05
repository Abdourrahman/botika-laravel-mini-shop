<?php

namespace App\Http\Controllers;

use App\Cart\Contracts\CartInterface;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * __invoke
     *
     * @return void
     */
    public function __invoke()
    {

        $categories = Category::tree()->get()->toTree();

        return view('home', [
            'categories' => $categories
        ]);
    }
}
