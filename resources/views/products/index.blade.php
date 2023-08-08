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



@endsection
