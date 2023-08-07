@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Store Inventory</h1>
    <p class="mb-4">This is the inventory table for the Store products, has both Fulfilled Orders(sales made when the inventory is above 10) <br> and Unfulfilled orders(sales made when the inventory is below 10 products)<br>
        When a sale is made and inventory is reduced below 10, the inventory is auto reordered and the table is autopopulated</p>

    <!-- Store Products Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Store Products</h6>
            <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addStoreProductModal">Add Product</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="storeProductsTable" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Inventory</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Store Product Modal -->
<div class="modal fade" id="addStoreProductModal" tabindex="-1" aria-labelledby="addStoreProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStoreProductModalLabel">Add Store Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addStoreProductForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="inventory" class="form-label">Inventory</label>
                        <input type="number" class="form-control" id="inventory" name="inventory" required min="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addProductButton">Add Product</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const storeProductsTable = document.getElementById('storeProductsTable');
        const fulfilledOrdersTable = document.getElementById('fulfilledOrdersTable');
        const addProductButton = document.getElementById('addProductButton');
        const addStoreProductForm = document.getElementById('addStoreProductForm');
        const addStoreProductModal = new bootstrap.Modal(document.getElementById('addStoreProductModal'));

        // Fetch and populate store products and fulfilled orders
        fetchData();

        function fetchData() {
            fetch("{{ route('store-products') }}")
                .then(response => response.json())
                .then(data => {
                    populateStoreProductsTable(data.storeProducts);
                    populateFulfilledOrdersTable(data.fulfilledOrders);
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        function populateStoreProductsTable(storeProducts) {
            storeProductsTable.innerHTML = '';

            storeProducts.forEach(product => {
                const row = storeProductsTable.insertRow();
                row.innerHTML = `
                    <td>${product.name}</td>
                    <td>${product.inventory}</td>
                    <td>
                        <button class="edit-button" data-product-id="${product.id}">Edit</button>
                        <button class="delete-button" data-product-id="${product.id}">Delete</button>
                        <button class="sale-button" data-product-id="${product.id}">Sale</button>
                    </td>
                `;
            });

            // Add event listeners for edit, delete, and sale buttons
            const editButtons = document.querySelectorAll('.edit-button');
            editButtons.forEach(button => {
                button.addEventListener('click', handleEditButtonClick);
            });

            const deleteButtons = document.querySelectorAll('.delete-button');
            deleteButtons.forEach(button => {
                button.addEventListener('click', handleDeleteButtonClick);
            });

            const saleButtons = document.querySelectorAll('.sale-button');
            saleButtons.forEach(button => {
                button.addEventListener('click', handleSaleButtonClick);
            });
        }

        // Handle Edit Button Click
        function handleEditButtonClick(event) {
            const productID = event.target.getAttribute('data-product-id');
            // Fetch and handle edit data here
            console.log(`Edit product with ID: ${productID}`);
        }

        // Handle Delete Button Click
        function handleDeleteButtonClick(event) {
            const productID = event.target.getAttribute('data-product-id');
            // Delete product using productID
            console.log(`Delete product with ID: ${productID}`);
            fetchData(); // Refresh the table after successful deletion
        }

        // Handle Sale Button Click
        async function handleSaleButtonClick(event) {
            const productID = event.target.getAttribute('data-product-id');
            const quantity = 1; 

            try {
                const response = await fetch(`/store_products/${productID}/reduce-inventory`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        quantity: quantity
                    })
                });

                const data = await response.json();
                console.log(data.message);

                fetchData();
            } catch (error) {
                console.error('Error reducing inventory:', error);
            }
        }

        // Populate Fulfilled Orders Table
        function populateFulfilledOrdersTable(fulfilledOrders) {
            fulfilledOrdersTable.innerHTML = '';

            fulfilledOrders.forEach(order => {
                const row = fulfilledOrdersTable.insertRow();
                row.innerHTML = `
                    <td>${order.product_name}</td>
                    <td>${order.quantity}</td>
                    <td>${order.order_number}</td>
                `;
            });
        }

        // Add Product Button Click
        addProductButton.addEventListener('click', async () => {
            try {
                const response = await fetch("{{ route('storeProducts') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name: addStoreProductForm.name.value,
                        inventory: addStoreProductForm.inventory.value
                    })
                });

                const data = await response.json();
                console.log(data.message);

                addStoreProductForm.reset();
                addStoreProductModal.hide();
                fetchData(); // Refresh the table after successful addition
            } catch (error) {
                console.error('Error adding product:', error);
            }
        });
    });
</script>
@endsection