@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

    <section class="category_main">

        <section class="category_banner_img d-none d-lg-block" style="background-image: url('{{ asset('images/category_banner.jpg') }}');"></section>

        <section class="category_home">

            <div class="container">

                <div class="row">

                    <div class="col-lg-12 d-flex justify-content-center py-5">

                        <div class="product_category_content">

                            <div class="category_upper_title text-center">

                                <h1>All Products</h1>

                                <p>Organic soaps designed for sensitive skin, free from artificial essential oils that can irritate some individuals.</p>

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

                        @include('products.partials.filterprice')

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

                                    <h2>category name</h2>

                                    <div class="price_select">

                                        <div class="inner_sorting d-flex"></div>

                                    </div>

                                </div>

                            </div>

                             @include('products.partials.product-list')

                            <hr />

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </section>

@endsection