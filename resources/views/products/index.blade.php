@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Warehouse Products</h1>
    <p class="mb-4">This table shows the products in the warehouse, one can dispatch inventory to the store by clicking the button "Dispatch".</p>
    
    <div class="mb-3">
        <a href="#" class="btn btn-success" data-toggle="modal" data-target="#addProductModal">Add New Product</a>
    </div>

    <!-- Success Message -->
    <div id="successMessage" class="alert alert-success alert-dismissible fade show d-none" role="alert">
        Product dispatched successfully!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Products Table</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="productsTable" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Inventory</th>
                            <th>Fulfilled Status</th>
                            <th>Order Number</th>
                            <th>Dispatch</th>
                        </tr>
                    </thead>
                    <tbody id="orderData">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add New Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addProductForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="productName">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="productName">
                        </div>
                        <div class="form-group">
                            <label for="productInventory">Inventory</label>
                            <input type="text" class="form-control" id="productInventory" name="productInventory">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editProductForm" >
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editName">Product Name</label>
                            <input type="text" class="form-control" id="editName" name="editName">
                        </div>
                        <div class="form-group">
                            <label for="editInventory">Inventory</label>
                            <input type="text" class="form-control" id="editInventory" name="editInventory">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Product Modal -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProductModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this product?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productsTable = document.getElementById('productsTable');
        const successMessage = document.getElementById('successMessage');
        const deleteProductModal = document.getElementById('deleteProductModal');
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');
        const editProductModal = document.getElementById('editProductModal');
        const editProductForm = document.getElementById('editProductForm');
        const editNameInput = document.getElementById('editName');
        const editInventoryInput = document.getElementById('editInventory');
        const addProductForm = document.getElementById('addProductForm');

        async function updateProductsTable() {
            try {
                const response = await fetch("{{ route('products') }}");
                const data = await response.json();
                populateProductsTable(data.products);
            } catch (error) {
                console.error('Error updating table:', error);
            }
        }

        function populateProductsTable(products) {
            const tbody = productsTable.querySelector('tbody');
            tbody.innerHTML = '';
            products.forEach(product => {
                if (product.id && product.name && product.inventory && product.fulfilledStatus && product.orderNumber) {
                    const row = document.createElement('tr');
                    row.setAttribute('data-product-id', product.id);
                    row.innerHTML = `
                        <td>${product.name}</td>
                        <td data-inventory>${product.inventory}</td>
                        <td data-fulfilled-status>${product.fulfilledStatus}</td>
                        <td>${product.orderNumber}</td>
                        <td>
                            ${product.dispatchButton}
                            <button class="btn btn-primary edit-button" data-product-id="${product.id}">Edit</button>
                            <button class="btn btn-danger delete-button" data-product-id="${product.id}">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                } else {
                    console.error('Product data is missing required properties:', product);
                }
            });
            attachDispatchEventListeners();
            attachEditEventListeners();
            attachDeleteEventListeners();
        }

        function attachDispatchEventListeners() {
            const dispatchButtons = productsTable.querySelectorAll('.dispatch-button');
            dispatchButtons.forEach(button => {
                button.addEventListener('click', handleDispatchButtonClick);
            });
        }

        async function handleDispatchButtonClick(event) {
            const productID = event.currentTarget.getAttribute('data-product-id');

            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch(`/products/${productID}/dispatch`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                });

                if (!response.ok) {
                    throw new Error('Network Error');
                }

                const data = await response.json();
                console.log(data.message);

                successMessage.classList.remove('d-none');

                const fulfilledStatusCell = productsTable.querySelector(`tr[data-product-id="${productID}"] td[data-fulfilled-status]`);
                if (fulfilledStatusCell) {
                    if (fulfilledStatusCell.innerText === 'Unfulfilled') {
                        fulfilledStatusCell.innerText = 'Fulfilled';
                    }
                }

                updateProductsTable();
            } catch (error) {
                console.error('Error dispatching product:', error);
            }
        }

        addProductForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            const productName = document.getElementById('productName').value;
            const productInventory = document.getElementById('productInventory').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('/products', {
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

                $('#addProductModal').modal('hide');
                updateProductsTable();
            } catch (error) {
                console.error('Error adding product:', error);
            }
        });

        function deleteProduct(productID) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/products/${productID}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                },
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
                updateProductsTable();
                $(deleteProductModal).modal('hide');
            })
            .catch(error => {
                console.error('Error deleting product:', error);
            });
        }

        function handleDeleteButtonClick(event) {
            const productID = event.currentTarget.getAttribute('data-product-id');
            $(deleteProductModal).modal('show');

            function deleteProductHandler() {
                deleteProduct(productID);
            }

            confirmDeleteButton.removeEventListener('click', deleteProductHandler);
            confirmDeleteButton.addEventListener('click', deleteProductHandler);
        }

        function attachDeleteEventListeners() {
            const deleteButtons = productsTable.querySelectorAll('.delete-button');
            deleteButtons.forEach(button => {
                button.removeEventListener('click', handleDeleteButtonClick);
                button.addEventListener('click', handleDeleteButtonClick);
            });
        }

        function findProductByID(productID) {
            const products = productsTable.querySelectorAll('tbody tr');
            for (const product of products) {
                if (product.getAttribute('data-product-id') === productID) {
                    const name = product.querySelector('td:nth-child(1)').innerText;
                    const inventory = product.querySelector('td[data-inventory]').innerText;
                    return { name, inventory };
                }
            }
            return null;
        }

        function attachEditEventListeners() {
            const editButtons = productsTable.querySelectorAll('.edit-button');
            editButtons.forEach(button => {
                button.addEventListener('click', handleEditButtonClick);
            });
        }

        function handleEditButtonClick(event) {
            const productID = event.currentTarget.getAttribute('data-product-id');
            const product = findProductByID(productID);
            if (product) {
                editNameInput.value = product.name;
                editInventoryInput.value = product.inventory;
                editProductForm.action = `/products/${productID}`;
                editProductForm.removeEventListener('submit', handleEditFormSubmit);
                editProductForm.addEventListener('submit', e => {
                    e.preventDefault();
                    handleEditFormSubmit(productID);
                });
                $(editProductModal).modal('show');
            }
        }

        async function handleEditFormSubmit(productID) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const formData = new FormData(editProductForm);

            try {
                const response = await fetch(`/products/${productID}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                    },
                    body: formData,
                });

                const responseData = await response.json();
                console.log('Server response:', responseData);

                $(editProductModal).modal('hide');
                updateProductsTable();
            } catch (error) {
                console.error('Error editing product:', error);
            }
        }

        successMessage.addEventListener('click', () => {
            successMessage.classList.add('d-none');
        });

        updateProductsTable();
    });
</script>


@endsection
