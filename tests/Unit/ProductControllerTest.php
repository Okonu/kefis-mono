<?php

namespace Tests\Unit;

use App\Events\InventoryChangeEvent;
use App\Models\FulfilledOrder;
use App\Models\Product;
use App\Models\StoreProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanDispatchAProduct()
    {
        $product = Product::factory()->create();

        $response = $this->postJson(route('products.dispatch', ['product' => $product->id]));

        $response->assertOk();
        $this->assertDatabaseHas('store_products', [
            'name' => $product->name,
            'inventory' => $product->inventory,
        ]);
        $this->assertDatabaseHas('fulfilled_orders', ['product_id' => $product->id]);
    }

    public function testItCanReduceInventory()
    {
        $product = Product::factory()->create(['inventory' => 20]);
     
        $response = $this->patchJson(route('products.reduceInventory', ['product' => $product->id]), $requestPayload);

        $response->assertOk();
        $response->assertJson(['message' => 'Inventory reduced successfully']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'inventory' => 15]);
    }

    public function testItAutomaticallyReordersWhenInventoryBelowPredefinedLevel()
    {
        $predefinedLevel = 10;
        $product = Product::factory()->create(['inventory' => $predefinedLevel]);

        $response = $this->patchJson(route('products.reduceInventory', ['product' => $product->id]), ['quantity' => $predefinedLevel]);

        $response->assertOk();
        $response->assertJson(['message' => 'Inventory reduced successfully']);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'inventory' => 60]);
    }
}