<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with('fulfilledOrders')->get();

        return view('products.index', compact('products'));
    }
}
