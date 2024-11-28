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

        <section class="category_banner_img d-none d-lg-block sticky-top" id="category_id_banner_image" style="background-image: url('{{ asset('images/category_banner.jpg') }}');"></section>

        <section class="category_home">

            <div class="container">

                <div class="row">

                    <div class="col-lg-12 d-flex justify-content-center pt-3">

                        <div class="product_category_content">

                            <div class="category_upper_title text-center">

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <section class="py-2 product_category_section">

            <div class="container-fluid">

                <div class="row" style="--bs-gutter-x: 0;">

                    <div class="row" style="--bs-gutter-x: 0;">

                        <div class="col-lg-3 col-3">
                        <h4>Products</h2>
                            <hr />
                            
                            @include('products.partials.category')
                            
                        </div>
                        
                        <div class="col-lg-9 col-9">
                            <h4 id="category_name">{{ $categorys[0]->name }}</h2>

                            <hr />

                            <div class="row">

                                <div class="col-lg-12 col-sm-12 col-md-12 product_category_title">


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
