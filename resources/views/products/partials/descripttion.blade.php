@php

$productType = $product->type->filter(function ($type) use ($globalState, $globalCity) {
    return $type->state_id == $globalState && $type->city_id == $globalCity;
});

$product->load('cart', 'wishlist');

$cart = Auth::check() 
    ? $product->cart->firstWhere('user_id', Auth::user()->id) 
    : $product->cart->firstWhere('persistent_id', request()->cookie('persistent_id'));

$wishlist = Auth::check() 
    ? $product->wishlist->firstWhere('user_id', Auth::user()->id) 
    : null;

@endphp

<form id="addtocart{{ $product->id }}">

@csrf

<input type="hidden" name="product_id" value="{{ $product->id }}">

<input type="hidden" name="category_id" value="{{ $product->category_id }}">

<input type="hidden" name="cart_from" value="2">

<div class="details-product-content">

    <div class="prdd_name">

        <h2 class="details-product-title">{{ $product->name }}</h2>

        <div class="wishlist_icons{{ $product->id }}">

            @auth
                <a href="javascript:void(0)" class="wishlist-icon" onclick="toggleWishList({{ $product->id }})">
                    <i class="{{ $wishlist ? 'fa-solid fa-heart colored_icon' : 'fa-regular fa-heart hollow_icon' }}" style="color: {{ $wishlist ? '#f20232' : '#cdd5e5' }}"></i>
                </a>
            @endauth

        </div>

    </div>

    <a href="#" class="details-product-link">visit oswal store</a>

    <div class="details-product-rating">

        {!! renderStarRating(2) !!}

    </div>


    <div class="details-product-price">

        <p class="details-last-price">Market Price: <span>{{ formatPrice($productType->first()->del_mrp) }}</span></p>

        <p class="details-new-price">Selling Price: <span>{{ formatPrice($productType->first()->selling_price) }}</span></p>

        <input type="hidden" name="type_price" value="{{ $productType->first()->selling_price }}">

    </div>

    <div class="details-product-detail">
        <h2>about this item:</h2>
        {{-- <button class="accordion-toggle">Show More</button> --}}
        <div class="collapsible-content">
            <p>{{ $product->long_desc }}</p>
            {{-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur, perferendis eius.
                Dignissimos, labore suscipit. Unde.</p> --}}
        </div>
        <ul>
            {{-- <li>Type: <span>Cleaner</span></li> --}}
            <li>Available: <span>in stock</span></li>
            <li>Category: <span> {{ $product->category->name }}</span></li>
            {{-- <li>Shipping Area: <span>All over the world</span></li> --}}
            <li>Shipping Fee: <span>Free</span></li>
        </ul>
    </div>

    <input type="hidden" name="type_id" value="{{ $productType->first()->id }}">

    <select name="type_{{ $product->id }}" style="width: 30%; border: 1px solid #d1caca;" onchange="renderProduct('{{ $product->id }}', '{{ route('home.getproduct') }}', 'type_{{ $product->id }}')">

        <option value="type">Type</option>

        @foreach ($productType as $type)
            <option value="{{ $type->id }}"
                {{ $loop->first ? 'selected' : '' }}>
                {{ $type->type_name }}
            </option>
        @endforeach

    </select>

    <div class="details-purchase-info">

        <div class="set_insider">

            <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2 ripple"
                onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                <i class="fas fa-minus"></i>
            </button>

            <input style="border: 1px solid #d8172863 !important;" id="form1" min="1" name="quantity"
                value="{{ $cart->quantity ?? 1 }}" type="number" max="{{ getConstant()->quantity }}" class="form-control form-control-sm carts_puts" />

            <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2 ripple_set"
                onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                <i class="fas fa-plus"></i>
            </button>
        </div>

        <button type="button" class="details-btn" onclick="manageCart({{ $product->id }})">Add to Cart <i class="fas fa-shopping-cart"></i></button>
        <!-- <button type="button" class="details-btn"><i class="fa-solid fa-heart colored_icon"
                        style="color: #f20232; display:none;"></i>Add to Wishlist </button> -->
    </div>

    <div class="details-social-links">
        <p class="m-0">Share At:</p>
        <a href="https://www.facebook.com/oswalsoaps/">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://x.com/oswallgroup">
            <i class="fa-brands fa-x-twitter"></i>
        </a>
        <a href="https://www.instagram.com/oswalsoapgroup/">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="https://www.youtube.com/channel/UCjfUqRsCurC0bbbmDlJuc3w">
            <i class="fab fa-youtube"></i>
        </a>
        <a href="https://www.linkedin.com/in/oswal-products-375979114">
            <i class="fab fa-linkedin"></i>
        </a>
    </div>
</div>

</form>
