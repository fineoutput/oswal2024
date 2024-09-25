@php
    $totalProducts = sendProduct(false, false, false, true, false, false, false) ?? collect();

    $totalProducts = is_array($totalProducts) ? $totalProducts : $totalProducts->toArray();

    // Check if products exist
    if (count($totalProducts) > 0) {
        $chunkedProducts = array_chunk($totalProducts, 4);
    } else {
        $chunkedProducts = [];
    }

    $products = [];

    foreach ($chunkedProducts as $chunk) {
        $products[] = $chunk;
    }

    $i = 0;
@endphp
<!-- ////////////////////Keep the style and script of this file in this file only -->
<style>
    .splide {
    overflow: hidden;
}

.splide__track {
    position: relative;
    overflow: hidden;
    
}

.splide__list {
    display: flex;
    transition: transform 0.3s ease-in-out;
}

.splide__slide {
     /* Adjust based on your design */
    flex-shrink: 0;
}

.vidhr_rabel {
    height: 300px;
}
</style>
<section class="product-sect py-5 hot-deals" style="background-image: url('{{ asset('images/rice1.png') }}');">
    <div class="container-fluid">
        <h2 class="text-center sect-text mb-5" style="color: #373737;">Hot Deals Products</h2>

        <div class="splide" id="splide-hot-deals">
    <div class="splide__track">
        <ul class="splide__list">
            @foreach ($products as $item)
                @foreach ($item as $product)
                    @php
                        $productType = App\Models\Type::where('state_id', $globalState)
                            ->where('city_id', $globalCity)
                            ->where('product_id', $product['id'])
                            ->first();
                    @endphp
                    @if($productType)
                        <li class="splide__slide">
                            <div class="one_card">
                                <div class="card_upper_img vidhr_rabel">
                                    <a href="{{ route('product-detail' ,['slug' => $product['url']]) }}">
                                        <img src="{{ asset($product['img2']) }}" alt="Primary Image" class="primary-image img-responsive" style="  width: 100%;
    height: 100%;
    object-fit: fill !important;"/>
                                        <img src="{{ asset($product['img1']) }}" alt="Secondary Image" class="secondary-image img-responsive" style="  width: 100%;
    height: 100%;
    object-fit: fill !important;"/>
                                    </a>
                                </div>
                                <div class="lower_cntnt_prod" style="background-color: #fff;">
                                    <div class="card__size">
                                        <b>
                                            <p>{{ $product['name'] }}</p>
                                        </b>
                                        <p>{{ formatPrice($productType->selling_price) }}</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach
            @endforeach
        </ul>
    </div>
</div>


        <div class="inner_stop text-center">
            <a href="{{ route('category-list' ,['type' => 'hot']) }}">
                <button class="btn-11 animated-button" type="button">
                    <span>Shop Now</span>
                    <span></span>
                </button>
            </a>
        </div>
    </div>
</section>
<!-- /////////////Hot Deals Product section ENDS////////// -->

@php
    $image = App\Models\Slider2::where('is_active', 1)->orderBy('id', 'desc')->limit(1)->first();
@endphp

<!-- /////////////Banner section STARTS////////// -->
@if($image)
<div class="post_banner py-10" style="background-image: url('{{ asset($image->image) }}');">
    <div class="container-fluid">
        <div class="row"></div>
    </div>
</div>
@endif
<!-- /////////////Banner section ENDS////////// -->

<div class="video_about_sect">
    <div class="container-fluid naminfs">
        <iframe width="100%" height="100%" src="https://www.youtube.com/embed/p8uBkLXcZnA?si=AEJOO1AUaAypsj9U" 
                title="YouTube video player" 
                frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
        </iframe>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Splide('#splide-hot-deals', {
            type: 'loop',
            perPage: 4,  // Show 4 items at once
            perMove: 1,  // Move 1 slide at a time
            focus: 'center',  // Focus the center slide
            autoplay: true,  // Enable autoplay
            pagination: true,
            arrows: true,
            gap: '1rem',  // Adjust gap between slides
            drag: true,  // Enable dragging functionality
            breakpoints: {
                768: {
                    perPage: 1,  // Show 1 item on smaller screens
                },
            },
        }).mount();
    });
</script>


