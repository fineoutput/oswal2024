@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

@php
    $products = sendProduct(false, false, false, false, false, $query, false , 6);
@endphp

<input type="hidden" value="{{ route('getproducts', ['slug' => $query, 'type' => 'search']) }}" id="category-url-route">

@include('products.partials.product-list', $products)

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            
            var url = $('#category-url-route').val()

            renderproductview(url);

        });
    </script>
@endpush