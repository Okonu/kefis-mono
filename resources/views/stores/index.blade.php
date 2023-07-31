@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Store Inventory</h1>
        <p class="mb-4">This is the inventory table for the Store products, has both Fulfilled Orders(sales made when the inventory is above 10) <br> and Unfulfilled orders(sales made when the inventory is below 10 products)<br>
        When a sale is made and inventory is reduced below 10, the inventory is autoreorderd and table is autopopulated</p>

        <!-- Store Products Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Store Products</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="storeProductsTable" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Inventory</th>
                                <th>Sale</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($storeProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->inventory }}</td>
                                    <td>
                                        <button class="btn btn-warning" onclick="reduceInventory({{ $product->id }}, 1)">Sale</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Fulfilled Orders Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Fulfilled Orders</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="fulfilledOrdersTable" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Order Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fulfilledOrders as $order)
                                <tr>
                                    <td>{{ $order->product_name }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ $order->order_number }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <script>
        async function fetchStoreProducts() {
            try {
                const response = await fetch("/store_products");
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error fetching store products:', error);
                return [];
            }
        }

        async function reduceInventory(storeProduct_id, quantity) {
            try {
                const response = await fetch(`/store_products/${storeProduct_id}/reduce-inventory`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: quantity
                    })
                });
                const data = await response.json();
                console.log(data.message);

                const storeProductsTable = document.getElementById('storeProductsTable').getElementsByTagName('tbody')[0];
                const rowToUpdate = storeProductsTable.querySelector(`tr[data-product-id="${storeProduct_id}"]`);
                rowToUpdate.cells[1].innerText = data.store_product.inventory;

                if (data.fulfillment_details) {
                    const fulfilledOrdersTable = document.getElementById('fulfilledOrdersTable').getElementsByTagName('tbody')[0];
                    const newRow = fulfilledOrdersTable.insertRow();
                    const productNameCell = newRow.insertCell();
                    const quantityCell = newRow.insertCell();
                    const orderNumberCell = newRow.insertCell();

                    productNameCell.innerText = data.fulfillment_details.product_name;
                    quantityCell.innerText = data.fulfillment_details.quantity;
                    orderNumberCell.innerText = data.fulfillment_details.order_number;
                }
            } catch (error) {
                console.error('Error reducing inventory:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', async () => {
            const storeProductsData = await fetchStoreProducts();
            populateStoreProductsTable(storeProductsData);

            // populateFulfilledOrdersTable({});

        });
    </script>
@endsection
