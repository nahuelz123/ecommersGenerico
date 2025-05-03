<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::Latest()->take(10)->get();

        return view('home', compact('products'));
    }
}
