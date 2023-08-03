@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Warehouse Products</h1>
    <p class="mb-4">This table shows the products in the warehouse, one can dispatch inventory to the store by clicking the button "Dispatch".</p>
    
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
                        <!-- Products will be populated dynamically using JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productsTable = document.getElementById('productsTable');
        const successMessage = document.getElementById('successMessage');

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
            productsTable.innerHTML = '';
            products.forEach(product => {
                if (product.id && product.name && product.inventory && product.fulfilledStatus && product.orderNumber) {
                    const row = productsTable.insertRow();
                    row.setAttribute('data-product-id', product.id);
                    row.innerHTML = `
                        <td>${product.name}</td>
                        <td data-inventory>${product.inventory}</td>
                        <td data-fulfilled-status>${product.fulfilledStatus}</td>
                        <td>${product.orderNumber}</td>
                        <td>
                            <button class="btn btn-primary dispatch-button" data-product-id="${product.id}">Dispatch</button>
                        </td>
                    `;
                } else {
                    console.error('Product data is missing required properties:', product);
                }
            });
            attachDispatchEventListeners();
        }

        function attachDispatchEventListeners() {
            const dispatchButtons = document.querySelectorAll('.dispatch-button');
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

                const fulfilledStatusCell = document.querySelector(`tr[data-product-id="${productID}"] td[data-fulfilled-status]`);
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

        successMessage.addEventListener('click', () => {
            successMessage.classList.add('d-none');
        });

        updateProductsTable();
    });
</script>

@endsection
