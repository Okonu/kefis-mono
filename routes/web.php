<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreProductController;
use App\Models\Product;
use App\Models\StoreFulfilledOrder;
use App\Models\StoreProduct;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/store', function () {
    $storeProducts = StoreProduct::all();

    $fulfilledOrders = StoreFulfilledOrder::all();

    return view('stores.index', compact('storeProducts', 'fulfilledOrders'));
});

Route::get('/orders', function () {
    return view('products.orders');
});

Route::get('/index', function () {
    $products = Product::with('fulfilledOrders')->get();

    return view('products.index', compact('products'));
});

Route::post('products/{product_id}/reduce-inventory', [ProductController::class, 'reduceInventory'])->name('reduceInventory');

Route::post('products/{product_id}/dispatch', [ProductController::class, 'dispatchProduct'])->name('dispatch');

Route::post('store_products/{store_product}/reduce-inventory', [StoreProductController::class, 'reduceInventory'])->name('reduce-inventory');

// Route::get('store_products', [StoreProductController::class, 'index']);

Route::get('processed_orders', [ProductController::class, 'processedOrders']);
