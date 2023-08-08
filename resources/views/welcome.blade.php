@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Store Products
                </div>
                <div class="card-body">
                    <p>This card contains information about Store Products.</p>
                    <a href="/store" class="btn btn-primary">Go to Store</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Warehouse Products
                </div>
                <div class="card-body">
                    <p>This card contains information about Warehouse Products.</p>
                    <a href="/index" class="btn btn-primary">Go to Warehouse Products</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Fulfilled Orders
                </div>
                <div class="card-body">
                    <p>This card contains information about Fulfilled Orders.</p>
                    <a href="/orders" class="btn btn-primary">Go to Fulfilled Orders</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
