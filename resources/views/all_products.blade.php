@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

<div class="container section_padding">

    @include('products.partials.product-list', $products)
</div>

@endsection