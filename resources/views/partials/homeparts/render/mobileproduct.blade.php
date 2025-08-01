@php
    $product->load('cart');

    $cart = Auth::check() 
            ? $product->cart->firstWhere('user_id', Auth::user()->id) 
            : $product->cart->firstWhere('persistent_id', request()->cookie('persistent_id'));

@endphp

<svg class="savage" width="29" height="28" viewBox="0 0 29 28"fill="none" xmlns="http://www.w3.org/2000/svg">

    <path
        d="M28.9499 0C28.3999 0 27.9361 1.44696 27.9361 2.60412V27.9718L24.5708 25.9718L21.2055 27.9718L17.8402 25.9718L14.4749 27.9718L11.1096 25.9718L7.74436 27.9718L4.37907 25.9718L1.01378 27.9718V2.6037C1.01378 1.44655 0.549931 0 0 0H28.9499Z"
        fill="#c92323">
    </path>

    <text x="50%" y="50%" font-size="6" text-anchor="middle" alignment-baseline="central" fill="#ffffff" dy=".3em">

        @isset($seltedType)
        {{ percentOff($seltedType->del_mrp, $seltedType->selling_price, true) }}
        @else
            N/A
        @endisset

    </text>

</svg>

<div class="upper_txt mobile_upper_txt">

    <h4>{{ $product->name ?? '' }}</h4>

    <div class="rates mobile_rates">

        <del>

            <p class="prev_rate">
                @isset($seltedType->del_mrp)
                    {{ formatPrice($seltedType->del_mrp) }}
                @else
                    N/A
                @endisset
            </p>


        </del>

        <p>{{ formatPrice($seltedType->selling_price ?? 0) }}</p>

        <input type="hidden" name="type_price" value="{{ $seltedType->selling_price ?? '' }}">

    </div>

</div>

<div class="upper_common d-flex mobile_common">

    <div class="upper_txt_input">

        <input type="hidden" name="type_id" value="{{ $seltedType->id ?? '' }}">

        <select name="mob_type_{{ $product->id ?? '' }}" onchange="renderProduct('{{ $product->id ?? '' }}', '{{ route('home.getproduct') }}', 'mob_type_{{ $product->id ?? '' }}')">

            <option value="type">Type</option>

            @foreach ($productType as $type)

                <option value="{{ $type->id ?? '' }}" {{ $type->id == $seltedType->id ? 'selected' : '' }}>

                    {{ $type->type_name ?? '' }}

                </option>

            @endforeach

        </select>

    </div>

    <div class="button-container addButton mobile_btns">

        <span class="buttonText" id="mob_add-to-cart-section{{ $product->id ?? '' }}"  @if ($cart) style="display: none;" @endif onclick="manageCart({{ $product->id ?? '' }})">Add</span>

        <div class="controlButtons" @if (!$cart) style="display: none;" @endif id="mob_quantity-section{{ $product->id ?? '' }}">

            <div class="increment-decrement">

                <button class="btn-decrease" id="btn-decrement{{ $product->id ?? '' }}"  onclick="decrement({{ $product->id ?? '' }})">-</button>

                <span class="number-display{{ $product->id ?? '' }}">1</span>

                <input id="mob_quantity-input{{ $product->id ?? '' }}" type="hidden" name="quantity" value="{{ $cart->quantity ?? 0 }}"
                size="1" />

                <button class="btn-increase" id="btn-increment{{ $product->id ?? '' }}" onclick="increment({{ $product->id ?? '' }})">+</button>

            </div>

        </div>

    </div>

</div>
