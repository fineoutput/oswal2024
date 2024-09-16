@php
    $totalProducts = sendProduct(false, false, false, true, false, false, false) ?? collect();

    $totalProducts = is_array($totalProducts) ? $totalProducts : $totalProducts->toArray();

    $chunkedProducts = array_chunk($totalProducts, 4);

    $products = [];

    foreach ($chunkedProducts as $chunk) { 
        $products[] = $chunk;
    }

    $i = 0;

@endphp



 <section class="product-sect py-5 hot-deals" style="background-image: url('{{ asset('images/rice1.png') }}');">

    <div class="container-fluid">

        <h2 class="text-center sect-text mb-5" style="color: #373737;">

            Hot Deals Products

        </h2>

        <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">

            <div class="carousel-inner">

                @foreach ($products as $item)
                    
                    <div class="carousel-item @if($i== 0) active @endif">

                        <div class="row">

                            @foreach ($item as $product)

                                @php
                                    $productType = App\Models\Type::where('state_id', $globalState)
                                    ->where('city_id', $globalCity)
                                    ->where('product_id', $product['id'])
                                    ->first();
                                   
                                @endphp

                                <div class="col-lg-3 col-md-6 col-12">

                                    <div class="one_card">

                                        <div class="card_upper_img">

                                            <a href="{{ route('product-detail' ,['slug' => $product['url']]) }}">

                                                <img src="{{asset($product['img2'])}}" alt="Primary Image" class="primary-image img-responsive" />

                                                <img src="{{asset($product['img1'])}}" alt="Secondary Image" class="secondary-image img-responsive" />

                                           </a>

                                            {{-- <div class="product-buttons">

                                                <button class="btn-cart" onclick="addToCart($product['id'])"><ion-icon name="cart-outline"></ion-icon></button>

                                                <button class="btn-wishlist" onclick="addtoWishList($product['id'])"><ion-icon name="heart-outline"></ion-icon></button>

                                            </div> --}}

                                        </div>

                                        <div class="lower_cntnt_prod" style=" background-color: #fff; ">

                                            <div class="card__size">

                                                <b>

                                                    <p>{{ $product['name'] }}</p>

                                                </b>

                                                {{-- <h4>{{ Illuminate\Support\Str::limit($product['long_desc'] , 40) }}</h4> --}}

                                                <p>{{ formatPrice($productType->selling_price) }}</p>

                                            </div>

                                            {{-- <div class="mobile-product-buttons">

                                                <button onclick="addToCart($product['id'])"class="btn-cart"><ion-icon name="cart-outline"></ion-icon></button>

                                                <button  onclick="addtoWishList($product['id'])" class="btn-wishlist"><ion-icon name="heart-outline"></ion-icon></button>

                                            </div> --}}

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                    @php
                        ++$i;
                    @endphp
                @endforeach

            </div>
            <!-- Carousel Controls with Custom Icons -->
            <button class="carousel-control-prev" type="button" data-bs-target="#product-carousel" data-bs-slide="prev">

                <span class="visually-hidden">Previous</span>

                <ion-icon name="caret-back-circle-outline" style="color: black; font-size: 2rem;"></ion-icon>

            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#product-carousel" data-bs-slide="next">

                <span class="visually-hidden">Next</span>

                <ion-icon name="caret-forward-circle-outline" style="color: black; font-size: 2rem;"></ion-icon>

            </button>

        </div>

        <div class="inner_stop text-center">

            <a href="{{ route('category-list' ,['type' => 'hot']) }}">
             <button class="btn-11 animated-button" type="button"><span>Shop Now</span> <span></span></button>
            </a>

        </div>

    </div>

</section>
<!-- /////////////Hot Deals Product section ENDS////////// -->

@php
  $image =   App\Models\Slider2::where('is_active', 1)->orderBy('id', 'desc')->limit(1)->first()
@endphp

<!-- /////////////Banner section STARTS////////// -->
@if($image)
<div class="post_banner py-10" style="background-image: url('{{ asset($image->image) }}');">

    <div class="container-fluid">
    
        <div class="row"></div>

                        </div>

</div>
@endif

<div class="video_about_sect">
    <div class="container-fluid naminfs">
    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/p8uBkLXcZnA?si=AEJOO1AUaAypsj9U" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
</div>
<!-- /////////////Banner section ENDS////////// -->
