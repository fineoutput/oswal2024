<svg class="savage" width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">

    <path
        d="M28.9499 0C28.3999 0 27.9361 1.44696 27.9361 2.60412V27.9718L24.5708 25.9718L21.2055 27.9718L17.8402 25.9718L14.4749 27.9718L11.1096 25.9718L7.74436 27.9718L4.37907 25.9718L1.01378 27.9718V2.6037C1.01378 1.44655 0.549931 0 0 0H28.9499Z"
        fill="#c92323"></path>

    <text x="50%" y="50%" font-size="6" text-anchor="middle" alignment-baseline="central"fill="#ffffff" dy=".3em">

        {{ percentOff($seltedType->del_mrp, $seltedType->selling_price, true) }}

    </text>

</svg>

<div class="upper_txt det_txt mobile_det">

    <h4>{{ $product->name }}</h4>

</div>

<div class="mobile_common">

    <div class="d-flex" style="font-size: 0.8rem; gap: 5px;">

        <del style="color: red;">{{ formatPrice($seltedType->del_mrp) }}</del>

        <p>{{ formatPrice($seltedType->selling_price) }}</p>

    </div>

</div>

<div class="upper_txt_qty det_txt_qnt mobile_input_btn">

    <div class="upper_txt_input mobile_input">

        <select name="mob_type_{{ $product->id }}"
            onchange="renderProduct('{{ $product->id }}', '{{ route('getproduct') }}', 'mob_type_{{ $product->id }}')">

            <option value="type">Type</option>

            @foreach ($productType as $type)
                <option value="{{ $type->id }}" {{ $type->id == $seltedType->id ? 'selected' : '' }}>
                    {{ $type->type_name }}
                </option>
            @endforeach

        </select>

    </div>

    <div class="button-container addButton">

        <span class="buttonText">Add</span>

        <div class="controlButtons hidden">

            <div class="increment-decrement">

                <button class="btn-decrease">-</button>

                <span class="number-display">1</span>

                <button class="btn-increase">+</button>

            </div>

        </div>

    </div>

</div>