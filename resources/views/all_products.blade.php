@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

@php
    $products = sendProduct(false, false, false, false, false, $query, false , 6);
@endphp

<input type="hidden" value="{{ route('getproducts', ['slug' => $query, 'type' => 'search']) }}" id="category-url-route">
<div class="container section-padding">
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12">
            <h1 class="d-flex justify-content-center align-items-center ">All Products</h1>
        </div>
    </div>
    <div class="row">

        @include('products.partials.product-list', $products)
    </div>
</div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            
            var url = $('#category-url-route').val()

            renderproductview(url);

        });
    </script>
@endpush