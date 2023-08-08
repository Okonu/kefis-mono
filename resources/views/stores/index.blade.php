@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">Store Inventory</h1>
    <p class="mb-4">
        This is the inventory table for the Store products, which includes both Fulfilled Orders (sales made when the inventory is above 10) and Unfulfilled orders (sales made when the inventory is below 10 products). When a sale is made and the inventory is reduced below 10, the inventory is auto-reordered, and the table is updated.
    </p>

    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStoreProductModal">Add Product</button>
    </div>

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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
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
                    <tbody id="orderData">
                       
                    </tbody>
                </table>
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

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editStoreProductForm">
                        <input type="hidden" id="editProductId">
                        <div class="mb-3">
                            <label for="editProductName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="editProductName" name="editProductName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editInventory" class="form-label">Inventory</label>
                            <input type="number" class="form-control" id="editInventory" name="editInventory" required min="0">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateProductButton">Update Product</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Product Modal -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProductModalLabel">Delete Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this product?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>


</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const storeProductsTable = document.getElementById('storeProductsTable');
        const deleteProductModal = document.getElementById('deleteProductModal');
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');

        // Edit Product Modal
        const editProductModal = document.getElementById('editProductModal');
        const updateProductButton = document.getElementById('updateProductButton');

        //add store product
        const addProductForm = document.getElementById('addStoreProductForm');
        const addProductButton = document.getElementById('addProductButton');


        fetchData();

        async function fetchData() {
            try {
                const response = await fetch("{{ route('store-products') }}");
                const data = await response.json();
                populateStoreProductsTable(data.storeProducts);
                populateFulfilledOrdersTable(data.fulfilledOrders);
                attachDeleteEventListeners();
            } catch (error) {
                console.error("Error fetching data:", error);
            }
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

            storeProductsTable.addEventListener('click', handleTableButtonClick);
        }

        function populateFulfilledOrdersTable(fulfilledOrders) {
            const fulfilledOrdersTable = document.getElementById('fulfilledOrdersTable');
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

        function handleTableButtonClick(event) {
            const target = event.target;

            if (target.classList.contains('edit-button')) {
                const productID = target.getAttribute('data-product-id');
                fetchEditData(productID);
            } else if (target.classList.contains('delete-button')) {
                const productID = target.getAttribute('data-product-id');
                const deleteProductModal = document.getElementById('deleteProductModal');
                deleteProductModal.setAttribute('data-product-id', productID);
                $('#deleteProductModal').modal('show');
            } else if (target.classList.contains('sale-button')) {
                const productID = target.getAttribute('data-product-id');
                handleSaleButtonClick(productID);
            }
        }

        addProductButton.addEventListener('click', async () => {
    const productName = document.getElementById('name').value;
    const productInventory = document.getElementById('inventory').value;

    try {
        const response = await fetch(`/store_products`, { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                name: productName,
                inventory: productInventory,
            }),
        });

        const responseData = await response.json();
        console.log('Server response:', responseData);

        $('#addStoreProductModal').modal('hide');
        fetchData();
    } catch (error) {
        console.error('Error adding product:', error);
    }
});


        updateProductButton.addEventListener('click', async () => {
            const productID = document.getElementById('editProductId').value;
            const productName = document.getElementById('editProductName').value;
            const productInventory = document.getElementById('editInventory').value;

            await updateProduct(productID, productName, productInventory);
        });


        async function fetchEditData(productID) {
            try {
                const response = await fetch(`/store_products/${productID}`);
                const productData = await response.json();

                document.getElementById('editProductId').value = productData.store_product.id;
                document.getElementById('editProductName').value = productData.store_product.name;
                document.getElementById('editInventory').value = productData.store_product.inventory;

                $('#editProductModal').modal('show');
            } catch (error) {
                console.error('Error fetching edit data:', error);
            }
        }

        async function updateProduct(productID, productName, productInventory) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(`/store_products/${productID}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({
                        name: productName,
                        inventory: productInventory,
                    }),
                });

                const responseData = await response.json();
                console.log('Server response:', responseData);

                $('#editProductModal').modal('hide');
                fetchData(); 
            } catch (error) {
                console.error('Error editing product:', error);
            }
        }

        async function deleteProduct(productID) {
            try {
                const response = await fetch(`/store_products/${productID}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                if (data.success) {
                    console.log(data.message);
                    $('#deleteProductModal').modal('hide');
                    fetchData(); // Fetch updated data and repopulate the table
                } else {
                    console.error('Failed to delete product:', data.message);
                }
            } catch (error) {
                console.error('Error deleting product:', error);
            }
        }

        function removeProductFromTable(productID) {
            const productRow = document.querySelector(`[data-product-id="${productID}"]`);
            if (productRow) {
                productRow.remove();
            }
        }

        async function handleSaleButtonClick(productID) {
            try {
                const response = await fetch(`/store_products/${productID}/reduce-inventory`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        quantity: 1
                    })
                });

                const data = await response.json();
                console.log(data.message);

                fetchData();
            } catch (error) {
                console.error('Error reducing inventory:', error);
            }
        }

        function attachEditEventListeners() {
            const editButtons = document.querySelectorAll('.edit-button');
            editButtons.forEach(button => {
                button.addEventListener('click', handleEditButtonClick);
            });
        }

        function attachDeleteEventListeners() {
            const deleteButtons = document.querySelectorAll('.delete-button');
            deleteButtons.forEach(button => {
                button.addEventListener('click', handleDeleteButtonClick);
            });
        }

        function handleDeleteButtonClick(event) {
            const productID = event.currentTarget.getAttribute('data-product-id');
            deleteProductModal.setAttribute('data-product-id', productID);
            $('#deleteProductModal').modal('show');
            confirmDeleteButton.addEventListener('click', () => {
                deleteProduct(productID);
            });
        }
    });
</script>

@endsection
