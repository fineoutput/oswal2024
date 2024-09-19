
    @php

        $productType = $webproduct->type->filter(function ($type) use ($globalState, $globalCity) {
            return $type->state_id == $globalState && $type->city_id == $globalCity;
        });

        $webproduct->load('cart', 'wishlist');

        $cart = Auth::check() 
            ? $webproduct->cart->firstWhere('user_id', Auth::user()->id) 
            : $webproduct->cart->firstWhere('persistent_id', request()->cookie('persistent_id'));
        
        $wishlist = Auth::check() 
            ? $webproduct->wishlist->firstWhere('user_id', Auth::user()->id) 
            : null;

    @endphp

    <li class="splide__slide">

        <form id="addtocart{{ $webproduct->id }}">

            @csrf

            <input type="hidden" name="product_id" value="{{ $webproduct->id }}">

            <input type="hidden" name="category_id" value="{{ $webproduct->category_id }}">

            <input type="hidden" name="cart_from" value="2">

            <div class="product_category_product_part" style="position: relative;">

                <div class="product_part_upper">
                    <a href="{{ route('product-detail' ,['slug' => $webproduct->url]) }}">
                    <div class="card_upper_img" style="width: 250px; height: 250px; ">

                        <img src="{{ asset($webproduct->img2) }}" alt="Primary Image" class="first-image" style="width: 100%; height: 100%; object-fit:contain; " />

                        <img src="{{ asset($webproduct->img1) }}" alt="PrimaryImage" class="secound-image" style="width: 100%; height: 100%; object-fit:contain;"/>

                    </div>
                    </a>

                    <div class="wishlist_icons{{ $webproduct->id }}" style="position: absolute; top: 30px; left: 10px; z-index: 10;">

                        @auth
                            <a href="javascript:void(0)" class="wishlist-icon" onclick="toggleWishList({{ $webproduct->id }})">
                                <i class="{{ $wishlist ? 'fa-solid fa-heart colored_icon' : 'fa-regular fa-heart hollow_icon' }}" style="color: {{ $wishlist ? '#f20232' : '#cdd5e5' }}"></i>
                            </a>
                        @endauth

                    </div>

                </div>

                <div class="product_part_lower" id="web_product_{{ $webproduct->id }}">

                    <svg class="savage" width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">

                        <path d="M28.9499 0C28.3999 0 27.9361 1.44696 27.9361 2.60412V27.9718L24.5708 25.9718L21.2055 27.9718L17.8402 25.9718L14.4749 27.9718L11.1096 25.9718L7.74436 27.9718L4.37907 25.9718L1.01378 27.9718V2.6037C1.01378 1.44655 0.549931 0 0 0H28.9499Z" fill="#c92323"></path>

                        <text x="50%" y="50%" font-size="6" text-anchor="middle" alignment-baseline="central" fill="#ffffff" dy=".3em">
                            @if ($productType->isNotEmpty())
                                {{ percentOff($productType->first()->del_mrp, $productType->first()->selling_price, true) }}
                            @endif
                        </text>

                    </svg>

                    <div class="upper_txt">

                        <h4>{{ $webproduct->name }}</h4>

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

                            <select name="type_{{ $webproduct->id }}" onchange="renderProduct('{{ $webproduct->id }}', '{{ route('home.getproduct') }}', 'type_{{ $webproduct->id }}')">

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

                            <div class="quant" id="quantity-section{{ $webproduct->id }}" @if (!$cart) style="display: none;" @endif>

                                <div class="input-group" style="display: flex; align-items: center;">

                                    <button type="button" class="btn btn-outline-secondary btn-decrement" style="margin-right: 5px;" id="btn-decrement{{ $webproduct->id }}" onclick="decrement({{ $webproduct->id }})">-</button>

                                    <input class="qv-quantity form-control quantity-input" id="quantity-input{{ $webproduct->id }}" type="number" name="quantity" min="0" value="{{ $cart->quantity ?? 0 }}" size="1" max="{{ getConstant()->quantity }}" step="1" style="width: 60px; text-align: center;" />

                                    <button type="button" class="btn btn-outline-secondary btn-increment" style="margin-left: 5px;" id="btn-increment{{ $webproduct->id }}" onclick="increment({{ $webproduct->id }})">+</button>

                                </div>

                            </div>


                            <div class="add_to_cart_button" id="add-to-cart-section{{ $webproduct->id }}" @if($cart) style="display: none;" @endif onclick="manageCart({{ $webproduct->id }})">

                                <button type="button"> <span>Add</span> </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </li>


