@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
    @php
        if($type != null){
            $hot = true;
        }else {
            $hot = false;
        }
        $categorys = sendCategory() ?? [];

        $products = sendProduct($categorys[0]->id, false, false, $hot , false, false, false, 6);
    @endphp

    <section class="category_main">

        <input type="hidden" value="{{ route('getproducts', ['slug' => $categorys[0]->url , 'type' => 'category']) }}" id="category-url-route">

        <section class="category_banner_img d-none d-lg-block" style="background-image: url('{{ asset('images/category_banner.jpg') }}');"></section>
        {{-- <section class="category_banner_img d-none d-lg-block" style="background-image: url('{{ asset($categorys[0]->image) }}');"></section> --}}

        <section class="category_home">

            <div class="container">

                <div class="row">

                    <div class="col-lg-12 d-flex justify-content-center py-5">

                        <div class="product_category_content">

                            <div class="category_upper_title text-center">

                                <h1>All Products</h1>

                                <p id="category-description">{{ $categorys[0]->long_desc }}</p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <section class="py-2 product_category_section">

            <div class="container-fluid">

                <div class="row">

                    <div class="col-lg-12 col-sm-12 col-md-12 product_category_title">

                        <h2>Products</h2>

                        {{-- @include('products.partials.filterprice') --}}

                    </div>

                    <div class="row">

                        <div class="col-lg-3 col-3">

                            <hr />

                            @include('products.partials.category')

                        </div>

                        <div class="col-lg-9 col-9">

                            <hr />

                            <div class="row">

                                <div class="col-lg-12 col-sm-12 col-md-12 product_category_title">

                                    <h2 id="category_name">{{ $categorys[0]->name }}</h2>

                                    <div class="price_select">

                                        <div class="inner_sorting d-flex"></div>

                                    </div>

                                </div>

                            </div>

                            @include('products.partials.product-list', $products)

                            <hr />

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </section>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            
            var url = $('#category-url-route').val()

            renderproductview(url);

        });
    </script>
@endpush
