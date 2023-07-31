<?php

namespace App\Listeners;

use App\Events\InventoryChangeEvent;
use App\Models\FulfilledOrder;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReorderListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(InventoryChangeEvent $event)
    {
        $product = $event->product;

        if ($product->inventory <= 0) {
            return;
        }

        if ($product->inventory <= Product::REORDER_QUANTITY) {
            $product->inventory += Product::REORDER_QUANTITY;
            $product->save();
        }

        $orderNumber = str_pad(FulfilledOrder::count() + 1, 6, '0', STR_PAD_LEFT);

        FulfilledOrder::create([
            'product_id' => $product->id,
            'status' => 'Unfulfilled',
            'order_number' => $orderNumber,
        ]);
    }
}
