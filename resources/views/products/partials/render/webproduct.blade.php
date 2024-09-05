<svg class="savage" width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">

    <path
        d="M28.9499 0C28.3999 0 27.9361 1.44696 27.9361 2.60412V27.9718L24.5708 25.9718L21.2055 27.9718L17.8402 25.9718L14.4749 27.9718L11.1096 25.9718L7.74436 27.9718L4.37907 25.9718L1.01378 27.9718V2.6037C1.01378 1.44655 0.549931 0 0 0H28.9499Z"
        fill="#c92323"></path>

    <text x="50%" y="50%" font-size="6" text-anchor="middle" alignment-baseline="central" fill="#ffffff" dy=".3em">

        {{ percentOff($seltedType->del_mrp, $seltedType->selling_price, true) }}

    </text>

</svg>

<div class="upper_txt det_txt">

    <h4>{{ $product->name }}</h4>

    <div class="rates">

        <del>

            <p class="prev_rate">{{ formatPrice($seltedType->del_mrp) }}</p>

        </del>

        <p>{{ formatPrice($seltedType->selling_price) }}</p>

    </div>
</div>

<div class="upper_common d-flex">

    <div class="upper_txt_input">

        <select name="type_{{ $product->id }}"
            onchange="renderProduct('{{ $product->id }}', '{{ route('getproduct') }}', 'type_{{ $product->id }}')">

            <option value="type">Type</option>

            @foreach ($productType as $type)
                <option value="{{ $type->id }}" {{ $type->id == $seltedType->id ? 'selected' : '' }}>
                    {{ $type->type_name }}
                </option>
            @endforeach

        </select>

    </div>

    <div class="upper_txt_qty det_txt_qnt">

        <div class="quant" id="quantity-section" style="display: none;">

            <div class="input-group det_input_grp" style="display: flex; align-items: center;">

                <button type="button" class="btn btn-outline-secondary btn-decrement"
                    style="margin-right: 5px;">-</button>

                <input class="qv-quantity form-control quantity-input" type="number" name="quantity" min="1"
                    value="1" size="1" step="1" style="width: 60px; text-align: center;" />

                <button type="button" class="btn btn-outline-secondary btn-increment"
                    style="margin-left: 5px;">+</button>

            </div>

        </div>

        <div class="add_to_cart_button" id="add-to-cart-section">

            <a href="#">

                <button>

                    <span>Add</span>

                </button>

            </a>

        </div>

    </div>

</div>