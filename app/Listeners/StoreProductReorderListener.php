<?php

namespace App\Listeners;

use App\Events\InventoryChangeEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreProductReorderListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(InventoryChangeEvent $event)
    {
        $storeProduct = $event->product;

        $storeProduct->inventory += 10;

        $storeProduct->save();
    }
}
