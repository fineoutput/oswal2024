@php
    $relatedproducts = sendProduct($categoryId, false, false, false, false, false, false, false);
@endphp

@foreach ($relatedproducts as $product)

    @php

        $productType = $product->type->filter(function ($type) use ($globalState, $globalCity) {
            return $type->state_id == $globalState && $type->city_id == $globalCity;
        });

        $product->load('cart', 'wishlist');

        $cart = null;
        $wishlist = null;

        if (Auth::check()) {

            $cart = $product->cart->firstWhere('user_id', Auth::user()->id);
            $wishlist = $product->wishlist->firstWhere('user_id', Auth::user()->id);
        } else {

            $cart = $product->cart->firstWhere('persistent_id', request()->cookie('persistent_id'));
        }

    @endphp

    <li class="splide__slide">

        <form id="addtocart{{ $product->id }}">

            @csrf

            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <input type="hidden" name="category_id" value="{{ $product->category_id }}">

            <input type="hidden" name="cart_from" value="2">

            <div class="product_category_product_part" style="position: relative;">

                <div class="product_part_upper">
                    <a href="{{ route('product-detail' ,['slug' => $product->url]) }}">
                    <div class="card_upper_img">

                        <img src="{{ asset($product->img2) }}" alt="Primary Image" class="first-image" style="width: 100%; height: 100%;" />

                        <img src="{{ asset($product->img1) }}" alt="PrimaryImage" class="secound-image" style="width: 100%; height: 100%;"/>

                    </div>
                    </a>

                    <div class="wishlist_icons{{ $product->id }}" style="position: absolute; top: 30px; left: 10px; z-index: 10;">

                        @auth()

                            @if($wishlist)
                                <a href="javascript:void(0)" class="wishlist-icon" onclick="toggleWishList({{ $product->id }})">
                                    <i class="fa-solid fa-heart colored_icon" style="color: #f20232;"></i>
                                </a>
                            @else
                                <a href="javascript:void(0)" class="wishlist-icon" onclick="toggleWishList({{ $product->id }})">
                                    <i class="fa-regular fa-heart hollow_icon" style="color: #cdd5e5;"></i>
                                </a>
                            @endif

                        @endauth

                    </div>

                </div>

                <div class="product_part_lower" id="web_product_{{ $product->id }}">

                    <svg class="savage" width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">

                        <path d="M28.9499 0C28.3999 0 27.9361 1.44696 27.9361 2.60412V27.9718L24.5708 25.9718L21.2055 27.9718L17.8402 25.9718L14.4749 27.9718L11.1096 25.9718L7.74436 27.9718L4.37907 25.9718L1.01378 27.9718V2.6037C1.01378 1.44655 0.549931 0 0 0H28.9499Z" fill="#c92323"></path>

                        <text x="50%" y="50%" font-size="6" text-anchor="middle" alignment-baseline="central" fill="#ffffff" dy=".3em">
                            @if ($productType->isNotEmpty())
                                {{ percentOff($productType->first()->del_mrp, $productType->first()->selling_price, true) }}
                            @endif
                        </text>

                    </svg>

                    <div class="upper_txt">

                        <h4>{{ $product->name }}</h4>

                        @if ($productType->isNotEmpty())

                            <div class="rates">

                                <del>

                                    <p class="prev_rate">{{ formatPrice($productType->first()->del_mrp) }}</p>

                                </del>

                                <p>{{ formatPrice($productType->first()->selling_price) }}</p>

                                <input type="hidden" name="type_price" value="{{ $productType->first()->selling_price }}">

                            </div>

                        @endif

                    </div>

                    <div class="upper_common d-flex">

                        <div class="upper_txt_input">

                            <input type="hidden" name="type_id" value="{{ $productType->first()->id }}">

                            <select name="type_{{ $product->id }}" onchange="renderProduct('{{ $product->id }}', '{{ route('home.getproduct') }}', 'type_{{ $product->id }}')">

                                <option value="type">Type</option>

                                @foreach ($productType as $type)
                                    <option value="{{ $type->id }}"
                                        {{ $loop->first ? 'selected' : '' }}>
                                        {{ $type->type_name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <div class="upper_txt_qty">

                            <div class="quant" id="quantity-section{{ $product->id }}" @if ($cart == null) style="display: none;" @endif>

                                <div class="input-group" style="display: flex; align-items: center;">

                                    <button type="button" class="btn btn-outline-secondary btn-decrement"
                                        style="margin-right: 5px;" id="btn-decrement{{ $product->id }}"
                                        onclick="decrement({{ $product->id }})">-</button>

                                    <input class="qv-quantity form-control quantity-input"
                                        id="quantity-input{{ $product->id }}" type="number"
                                        name="quantity" min="0" value="{{ $cart->quantity ?? 0 }}"
                                        size="1" max="5" step="1"
                                        style="width: 60px; text-align: center;" />

                                    <button type="button" class="btn btn-outline-secondary btn-increment"
                                        style="margin-left: 5px;" id="btn-increment{{ $product->id }}"
                                        onclick="increment({{ $product->id }})">+</button>

                                </div>

                            </div>

                            <div class="add_to_cart_button" id="add-to-cart-section{{ $product->id }}" @if ($cart != null) style="display: none;" @endif onclick="manageCart({{ $product->id }})">

                                <button type="button"> <span>Add</span> </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </li>

@endforeach
