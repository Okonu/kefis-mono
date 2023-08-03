<?php

namespace App\Http\Controllers;

use App\Models\StoreFulfilledOrder;
use App\Models\StoreProduct;
use Illuminate\Http\Request;

class StoreProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $storeProducts = StoreProduct::all();

        $fulfilledOrders = $this->getFulFilledOrders();

        return response()->json(['storeProducts' => $storeProducts, 'fulfilledOrders' => $fulfilledOrders]);
        // return view('stores.index', compact('storeProducts', 'fulfilledOrders'));
    }

    private function getFulFilledOrders()
    {
        return StoreFulfilledOrder::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'inventory' => 'required|integer|min:0',
        ]);

        $storeProduct = StoreProduct::create($request->all());

        return response()->json(['message' => 'Store product created successfully', 'store_product' => $storeProduct], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(StoreProduct $storeProduct)
    {
        $storeProducts = StoreProduct::all();

        return response()->json($storeProducts);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StoreProduct $storeProduct)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'inventory' => 'required|integer|min:0',
        ]);

        $storeProduct->update($request->all());

        return response()->json(['message' => 'Store product updated successfully', 'store_product' => $storeProduct]);
    }

    public function reduceInventory(StoreProduct $storeProduct, Request $request)
    {
        $this->validate($request, [
            'quantity' => 'required|integer|min:1',
        ]);

        $quantityToReduce = $request->input('quantity');

        if ($storeProduct->inventory - $quantityToReduce < 10) {
            $reorderQuantity = 50;
            $storeProduct->inventory = max(10, $storeProduct->inventory - $quantityToReduce + $reorderQuantity);
        } else {
            $storeProduct->inventory -= $quantityToReduce;
        }

        $fulfilledOrder = null;
        if ($storeProduct->inventory >= 10) {
            $orderNumber = ''.str_pad(StoreFulfilledOrder::count() + 1, 6, '0', STR_PAD_LEFT);
            $fulfilledOrder = StoreFulfilledOrder::create([
                'product_name' => $storeProduct->name,
                'quantity' => $quantityToReduce,
                'order_number' => $orderNumber,
            ]);
        }

        $storeProduct->save();

        return response()->json([
            'message' => 'Inventory reduced successfully',
            'store_product' => $storeProduct,
            'fulfillment_status' => $storeProduct->inventory >= 10 ? 'Fulfilled' : 'Unfulfilled',
            'fulfillment_details' => $fulfilledOrder,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreProduct $storeProduct)
    {
        $storeProduct->delete();

        return response()->json(['message' => 'Store product deleted successfully']);
    }
}
