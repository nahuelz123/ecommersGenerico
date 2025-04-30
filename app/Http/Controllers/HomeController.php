<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::inRandomOrder()->take(10)->get();

        return view('home', compact('products'));
    }
}
