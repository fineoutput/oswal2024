@php
    $product->load('cart');

    $cart = null;

    if (Auth::check()) {

        $cart = $product->cart->firstWhere('user_id', Auth::user()->id);

    } else {

        $cart = $product->cart->firstWhere('persistent_id', request()->cookie('persistent_id'));
    }
@endphp
<svg class="savage" width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path
        d="M28.9499 0C28.3999 0 27.9361 1.44696 27.9361 2.60412V27.9718L24.5708 25.9718L21.2055 27.9718L17.8402 25.9718L14.4749 27.9718L11.1096 25.9718L7.74436 27.9718L4.37907 25.9718L1.01378 27.9718V2.6037C1.01378 1.44655 0.549931 0 0 0H28.9499Z"
        fill="#c92323"></path>
    <text x="50%" y="50%" font-size="6" text-anchor="middle" alignment-baseline="central" fill="#ffffff" dy=".3em">
        {{ percentOff($seltedType->del_mrp ?? 0, $seltedType->selling_price ?? 0, true) }}
    </text>
</svg>

<div class="upper_txt">

    <h4>{{ $product->name ?? '' }}</h4>

    @if ($productType->isNotEmpty())
        <div class="rates">

            <del>

                <p class="prev_rate">{{ formatPrice($seltedType->del_mrp ?? 'N/A') }}</p>

            </del>

            <p>{{ formatPrice($seltedType->selling_price ?? 'N/A') }}</p>

            <input type="hidden" name="type_price" value="{{ $seltedType->selling_price ?? '' }}">

        </div>
    @endif

</div>

<div class="upper_common d-flex">

    <div class="upper_txt_input">
        <input type="hidden" name="type_id" value="{{ $seltedType->id ?? '' }}">
        <select name="type_{{ $product->id ?? '' }}"
            onchange="renderProduct('{{ $product->id ?? '' }}', '{{ route('home.getproduct') }}', 'type_{{ $product->id ?? '' }}')">

            <option value="type">Type</option>

            @foreach ($productType as $type)
                <option value="{{ $type->id ?? '' }}" {{ $type->id == $seltedType->id ? 'selected' : '' }}>
                    {{ $type->type_name ?? '' }}
                </option>
            @endforeach

        </select>

    </div>

    <div class="upper_txt_qty">

        <div class="quant" id="quantity-section{{ $product->id ?? '' }}"
            @if ($cart == null) style="display: none;" @endif>

            <div class="input-group" style="display: flex; align-items: center;">

                <button type="button" class="btn btn-outline-secondary btn-decrement" style="margin-right: 5px;"
                    id="btn-decrement{{ $product->id ?? '' }}" onclick="decrement({{ $product->id ?? '' }})">-</button>

                <input class="qv-quantity form-control quantity-input" id="quantity-input{{ $product->id }}"
                    type="number" name="quantity" min="0" value="{{ $cart->quantity ?? 0 }}" size="1"
                    max="5" step="1" style="width: 60px; text-align: center;" />

                <button type="button" class="btn btn-outline-secondary btn-increment" style="margin-left: 5px;"
                    id="btn-increment{{ $product->id ?? '' }}" onclick="increment({{ $product->id ?? '' }})">+</button>

            </div>

        </div>

        <div class="add_to_cart_button" id="add-to-cart-section{{ $product->id ?? '' }}"
            @if ($cart != null) style="display: none;" @endif onclick="manageCart({{ $product->id ?? '' }})">

            <button> <span>Add</span> </button>

        </div>

    </div>

</div>
