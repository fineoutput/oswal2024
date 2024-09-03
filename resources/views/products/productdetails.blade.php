@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

    <section class="product_details_section">

        <div class="container">

            <div class="details-card-wrapper">

                <div class="details-card">

                    <!-- card left -->

                    @include('products.partials.image',$images)

                    <!-- card right -->

                    @include('products.partials.descripttion',$product)

                </div>

            </div>

        </div>

    </section>

    <hr />

    @if ($product->video != null)

        <div class="video_sect_prod">

            <div class="container">

                <div class="row">

                    <div class="col-lg-12 col-sm-12 col-md-12">

                        <div class="oswla_vide_txt text-center">

                            <h2>Product video</h2>

                            <p>{{ $product->long_desc }}</p>

                        </div>

                    </div>

                </div>

                <div class="video_block">

                    <div class="bgvideo-1586366347102 txt-right bg-herovideo" data-block-id="1586366347102"

                        data-block-type="background-video">

                        <div class="hero-video">

                            <video id="Mp41586366347102" src="{{ $product->video }}" loop="" muted="" playsinline="" autoplay=""></video>

                        </div>

                        <div class="para_animate" data-aos="fade-up" data-aos-duration="1000">

                            <h4 class="parallax-banner__title pb-4" style="color: #b7813b;">

                                Oswal <br /> Organic Soap

                            </h4>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <hr />

    @endif

    <div class="other_products d-none d-lg-block">

        <div class="container">

            <div class="row">

                <div class="col-lg-12 col-sm-12 col-md-12 text-center">

                    <h2>Other Related Products</h2>

                </div>

            </div>

        </div>

    </div>
    
    <div class="container d-none d-lg-block">

        <div class="splide" id="product-splide">

            <div class="splide__track">

                <ul class="splide__list">

                    @include('products.partials.relatedProduct.web-product',['categoryId' => $product->category_id])

                </ul>

            </div>

        </div>

    </div>
  
    <section class="product-sect py-5 d-lg-none">

        <div class="container">

            <div class="row">

                <div class="col-lg-12 col-sm-12 col-md-12 col-12">

                    <div class="product-head-text">

                        <h2 class="text-center sect-text mb-5 aos-init aos-animate" data-aos=""

                            style="color: #373737; text-align: center;" data-aos-duration="800">

                            Other Related Products

                        </h2>

                    </div>

                </div>

            </div>

            <section class="product-sect py-5">

                <div class="container">

                    <div class="splide" id="product-splide_index">

                        <div class="splide__track">

                            <ul class="splide__list">

                                @include('products.partials.relatedProduct.mobile-product',['categoryId' => $product->category_id])

                            </ul>

                        </div>

                    </div>

                </div>

            </section>

        </div>

    </section>

@endsection
