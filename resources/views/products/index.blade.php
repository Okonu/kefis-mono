@extends('layouts.app')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Warehouse Products</h1>
        <p class="mb-4">This table shows the products in the warehouse, one can dispacth inventory to the store by clicking the button dispatch.</p>

        <!-- DataTales Example -->
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
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->inventory }}</td>
                                    <td>
                                        @if ($product->fulfilledOrders && $product->fulfilledOrders->count() > 0)
                                            Fulfilled
                                        @else
                                            Unfulfilled
                                        @endif
                                    </td>
                                    <td>
                                        @if ($product->fulfilledOrders && $product->fulfilledOrders->count() > 0)
                                            {{ $product->fulfilledOrders->first()->order_number }}
                                        @else
                                            NA
                                        @endif
                                    </td>
                                    <td>
                                        @if (!$product->fulfilledOrders || $product->fulfilledOrders->count() === 0)
                                            <button class="btn btn-success" onclick="dispatchProduct({{ $product->id }})">Dispatch</button>
                                        @else
                                            <!-- If fulfilled, show a disabled button -->
                                            <button class="btn btn-success" disabled>Dispatch</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        <div id="successMessage" class="alert alert-success alert-dismissible fade show d-none" role="alert">
            Product dispatched successfully!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<script>
    async function dispatchProduct(product_id) {
    try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/products/${product_id}/dispatch`, {
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

        const successMessage = document.getElementById('successMessage');
        successMessage.classList.remove('d-none');

        const inventoryCell = document.querySelector(`tr[data-product-id="${product_id}"] td[data-inventory]`);
        if (inventoryCell) {
            inventoryCell.innerText = data.product.inventory;
        }
    } catch (error) {
        console.error('Error dispatching product:', error);
    }
}

    document.addEventListener('DOMContentLoaded', () => {
        const successMessage = document.getElementById('successMessage');
        successMessage.addEventListener('click', () => {
            successMessage.classList.add('d-none');
        });
    });
</script>
@endsection
