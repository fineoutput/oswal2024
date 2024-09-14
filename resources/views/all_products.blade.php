@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

<input type="hidden" value="{{ route('getproducts', ['slug' => $query, 'type' => 'search']) }}" id="category-url-route">

<div class="row" id="product-list-container">


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