<?php

namespace App\Http\Controllers;

use App\Events\InventoryChangeEvent;
use App\Models\FulfilledOrder;
use App\Models\Product;
use App\Models\StoreProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('fulfilledOrders')->get();

        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'inventory' => 'required|string',
        ]);

        Product::create($request->all());

        return response()->json(['success' => true, 'message' => 'Product created successfully.']);
    }

    /**
     * Reduce the inventory of a product.
     */
    public function reduceInventory(Request $request, Product $product)
    {
        $productInventory = intval($product->inventory);

        if ($productInventory === null) {
            return response()->json(['message' => 'Product inventory is not set'], 400);
        }

        $this->validate($request, [
            'quantity' => 'required|integer|min:1|max:'.$productInventory,
        ]);

        $quantityToReduce = $request->input('quantity');
        $product->decrement('inventory', $quantityToReduce);

        if ($product->inventory <= 10) {
            $product->inventory = 10;
            $product->save();
        }

        event(new InventoryChangeEvent($product, $quantityToReduce));

        $product->refresh();

        return response()->json([
            'message' => 'Inventory reduced successfully',
            'product' => $product,
        ]);
    }

    /**
     * Dispatch a product and create a fulfilled order.
     */
    public function dispatchProduct($product_id)
    {
        $product = Product::findOrFail($product_id);

        StoreProduct::create([
            'name' => $product->name,
            'inventory' => $product->inventory,
        ]);

        $fulfilledOrder = new FulfilledOrder([
            'product_id' => $product->id,
            'status' => 'fulfilled',
            'order_number' => str_pad(FulfilledOrder::count() + 1, 6, '0', STR_PAD_LEFT),
        ]);

        $fulfilledOrder->save();

        return response()->json(['message' => 'Product dispatched successfully']);
    }

    /**
     * Get a list of processed orders with product details.
     */
    public function processedOrders()
    {
        $processedOrders = FulfilledOrder::whereNotNull('order_number')
            ->with('product:id,name,inventory')
            ->get(['product_id', 'order_number']);

        return response()->json($processedOrders);
    }

    public function show(Request $request)
    {
        $products = Product::with('fulfilledOrders')->get();

        $data = ['products' => []];

        foreach ($products as $product) {
            $fulfilledStatus = $product->fulfilledOrders && $product->fulfilledOrders->count() > 0 ? 'Fulfilled' : 'Unfulfilled';
            $orderNumber = $product->fulfilledOrders && $product->fulfilledOrders->count() > 0 ? $product->fulfilledOrders->first()->order_number : 'NA';
            $dispatchButton = !$product->fulfilledOrders || $product->fulfilledOrders->count() === 0
                ? '<button class="btn btn-success dispatch-button" data-product-id="'.$product->id.'">Dispatch</button>'
                : '<button class="btn btn-success" disabled>Dispatch</button>';

            $data['products'][] = [
                'id' => $product->id,
                'name' => $product->name,
                'inventory' => $product->inventory,
                'fulfilledStatus' => $fulfilledStatus,
                'orderNumber' => $orderNumber,
                'dispatchButton' => $dispatchButton,
            ];
        }

        return response()->json($data);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json(['success' => true, 'message' => 'Success product deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'inventory' => 'required',
        ]);

        $product->update([
            'name' => $request->input('name'),
            'inventory' => $request->input('inventory'),
        ]);

        return response()->json(['success' => true, 'product' => $product, 'message' => 'Successfully updated']);
    }
}
