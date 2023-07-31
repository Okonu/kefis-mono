<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('cors')->group(function () {
Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('products', [ProductController::class, 'show'])->name('api.products');

Route::post('products/{product_id}/reduce-inventory', [ProductController::class, 'reduceInventory']);

Route::post('products/{product_id}/dispatch', [ProductController::class, 'dispatchProduct']);

Route::post('store_products/{store_product}/reduce-inventory', [StoreProductController::class, 'reduceInventory']);

Route::get('store_products', [StoreProductController::class, 'show']);

Route::get('processed_orders', [ProductController::class, 'processedOrders']);
// });
