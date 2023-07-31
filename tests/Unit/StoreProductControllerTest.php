<?php

namespace Tests\Unit;

use App\Models\StoreProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class StoreProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAutomaticallyReordersWhenInventoryBelowPredefinedLevel()
    {
   
        $predefinedLevel = 10;
        $storeProduct = StoreProduct::factory()->create(['inventory' => $predefinedLevel]);

        $response = $this->patchJson(route('store-products.reduceInventory', ['storeProduct' => $storeProduct->id]), ['quantity' => $predefinedLevel]);

        $response->assertOk();
        $response->assertJson(['message' => 'Inventory reduced successfully']);

        $this->assertDatabaseHas('store_products', ['id' => $storeProduct->id, 'inventory' => 50]);
    }
    
}