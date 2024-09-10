@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

    @php
        $OrderDetails = $orderdetails->orderDetails;

        $addr_string = "Doorflat {$userAddress->doorflat}, ";

        if (!empty($userAddress->landmark)) {
            $addr_string .= "{$userAddress->landmark}, ";
        }

        $addr_string .= "{$userAddress->address}, {$userAddress->location_address}, {$userAddress->zipcode}";

        $giftCards = App\Models\GiftCard::where('is_active', 1)->get();

        $promocodes = App\Models\Promocode::where('is_active', 1)->get();

    @endphp

 <div id="paymentContainer">

 </div>

    <div class="shopping_cart_sect">

        <div> </div>

    </div>

    <div class="container">

        <div class="py-5 text-center">

            <h2>Checkout Page</h2>

            <p class="lead" style="font-size: 0.8rem;">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Incidunt consequatur aperiam laboriosam?/p></p>

        </div>

        <div class="row">

            <div class="col-md-4 order-md-2 mb-4">

                <h4 class="d-flex justify-content-between align-items-center mb-3">

                    <span class="text-muted">Your cart</span>

                    <span class="badge badge-secondary badge-pill">3</span>

                </h4>

                <ul class="list-group mb-3">

                    @foreach ($OrderDetails as $orderItem)
                        @php

                            $product = $orderItem->product;

                            $type = $orderItem->type;

                            $category = $product->category;
                        @endphp

                        <li class="list-group-item d-flex justify-content-between lh-condensed">

                            <div class="clls">

                                <img width="80px" src="{{ asset($product->img1) }}" alt="" />

                                <div class="cehc_txt">

                                    <h6 class="text-danger">{{ $product->name }}</h6>

                                    <small class="text-muted">{{ $category->name }}</small>

                                </div>

                            </div>

                            <span class="text-muted"> {{ $orderItem->quantity }} * {{ $type->selling_price }}</span>

                        </li>
                    @endforeach

                    <li class="list-group-item d-flex justify-content-between bg-light">

                        <div class="text-danger">

                            <h6 class="my-0">Shiping Charge</h6>

                        </div>

                        <span class="text-danger">+₹{{ $orderdetails->delivery_charge }}</span>

                    </li>

                    <li class="list-group-item d-flex justify-content-between bg-light">

                        <div class="text-success">

                            <h6 class="my-0">Promo code</h6>

                            @if($orderdetails->promocode)

                                @php

                                    $promocode = App\Models\Promocode::find($orderdetails->promocode);

                                @endphp

                                @if($promocode)

                                    <small id="promoCodeName">{{ $promocode->promocode }}</small>

                                @endif

                            @else

                                <small id="promoCodeName">No promo code applied</small>

                            @endif

                        </div>


                        <span class="text-success" id="promoCodeAmount">-{{ formatPrice($orderdetails->promo_deduction_amount) }}</span>

                    </li>

                    <li class="list-group-item d-flex justify-content-between bg-light">

                        <div class="text-success">

                            <h6 class="my-0">Wallet Amount</h6>

                        </div>

                        <span class="text-success" id="discountWalletAmount"> -{{formatPrice($orderdetails->extra_discount) }} </span>

                    </li>

                    <li class="list-group-item d-flex justify-content-between bg-light">

                        <div class="text-success">

                            <h6 class="my-0">Gift Card</h6>

                            @if($orderdetails->gift_id)

                                @php

                                    $giftCard = App\Models\GiftCard::find($orderdetails->gift_id);

                                @endphp

                                @if($giftCard)

                                    <small id="giftCardName">{{ $giftCard->name }}</small>

                                @endif

                            @else

                                <small id="giftCardName">No Gift Card applied</small>

                            @endif

                        </div>

                        <span class="text-success" id="GiftCardAmount">+{{formatPrice($orderdetails->gift_amt) }}</span>

                    </li>

                    <li class="list-group-item d-flex justify-content-between bg-light d-none" id="CodCaharges">

                        <div class="text-danger">

                            <h6 class="my-0">Cod Charg</h6>

                        </div>

                        <span class="text-danger" id='codChargeAmount'> </span>

                    </li>

                    <li class="list-group-item d-flex justify-content-between">

                        <span>Total (INR)</span>

                        <strong id="totalorderAmount">₹{{$orderdetails->total_amount}}</strong>

                    </li>

                </ul>

                <div class="gift-card-section ribbon" id="giftCardSection">

                    <div class="age_class d-flex justify-content-center">

                        <p><b>Click here to select a gift card</b></p>

                    </div>

                </div>

                <div class="gift-card-list" id="giftCardList">

                    @foreach ($giftCards as $key => $giftCard)

                        <div class="gift-card-item" data-id="{{$giftCard->id }}" onclick="applyGiftCard('{{$giftCard->id }}')">

                            <img src="{{ asset($giftCard->image) }}" alt="Gift Card 1" />

                            <p>{{ $giftCard->name }} ₹{{ $giftCard->price }} </p>

                        </div>

                    @endforeach

                </div>

                <div class="promo-section">

                    <div class="promo-title">Choose Your Promo Code</div>

                    <div class="promo-options">

                        @foreach ($promocodes as $promocode)

                        <button class="promo-option" onclick="applyPromocode('{{ $promocode->promocode }}')" data-code="{{ $promocode->promocode }}">{{ $promocode->percent }}% OFF</button>

                        @endforeach

                        {{-- <button class="promo-option" data-code="PROMO20">20% OFF</button>

                        <button class="promo-option" data-code="PROMO30">30% OFF</button> --}}

                    </div>

                    <input type="text" id="promoCodeInput" class="promo-code-input"
                        placeholder="Enter or select promo code" readonly />

                    <button class="apply-button" id="applyButton">Apply Code</button>

                </div>

            </div>

            <div class="col-md-8 order-md-1">

                <div class="chek_rees d-flex gap-5">

                    <h4 class="mb-3">Billing address</h4>

                </div>

                <form class="needs-validation" method="POST" novalidate id="placeOrder">

                    @csrf
                    <input type="hidden" id="totalorderAmounti" name="totalamount" value="{{$orderdetails->total_amount}}">

                    <input type="hidden" name="order_id" value="{{$orderdetails->id}}">

                    <div class="row">

                        <div class="col-md-12 mb-3">

                            <label for="firstName">Customer Name</label>

                            <input type="text" readonly class="form-control" id="firstName" placeholder=""
                                value="{{ $userAddress->name }}" />

                        </div>

                        <div class="col-md-12 mb-3">

                            <label for="state">State</label>

                            <input type="text" readonly class="form-control" id="state" placeholder=""
                                value="{{ $userAddress->states->state_name }}" />

                        </div>

                    </div>

                    <div class="mb-3">

                        <label for="City"> City</label>

                        <div class="input-group">

                            <input type="text" readonly class="form-control" id="City" placeholder="City"
                                value="{{ $userAddress->citys->city_name }}" />

                        </div>

                    </div>


                    <div class="mb-3">

                        <label for="address">Address</label>

                        <input type="text" readonly class="form-control" id="address" placeholder="address"
                            value="{{ $addr_string }}" />


                    </div>

                    <div class="mb-3">

                        <label for="address2">Landmark </label>

                        <input type="text" readonly class="form-control" id="address2" placeholder="Apartment or suite"
                            value="{{ $userAddress->landmark }}" />

                    </div>

                    <div class="row">

                        <div class="col-md-12 mb-3">

                            <label for="pin">Pin</label>

                            <input type="text" readonly class="form-control" id="zip" placeholder="pincode"
                                value="{{ $userAddress->zipcode }}" />

                        </div>

                    </div>


                    <div class="mt-2 order_review mb-2 p-3" style="margin-bottom: 10px; padding: 10px 30px; padding-bottom: 0px !important;" id="wallet_div">

                        <div class="form-group-1" style="margin-bottom: 15px;">

                            <div class="heading_s1">

                                <h4 style="font-size: 17px; font-family: Muli, sans-serif !important;">Apply Wallet</h4>

                            </div>

                            <input type="checkbox" onchange="applyWallet()" @if($orderdetails->extra_discount != 0) checked @endif name="wallet" id="wallet" class="wallet" value="1" />

                            <label class="wallet-size mb-0" for="wallet" id="totalwalletAmount"> Wallet (₹{{ Auth::user()->wallet_amount ?? 0 }})</label>

                        </div>

                    </div>

                    <hr class="mb-4" />

                    <h4 class="mb-3">Payment</h4>

                    <div class="payment_method" style="padding: 0px 13px; margin-bottom: 0px;">

                        <div class="heading_s1 pt-2">

                            <p style="color: red; padding-bottom: 0px; margin-bottom: 0px;" class="payment-par">Delivery

                                Free Above ₹2499</p>

                            <h4 class="payment" style="font-family: Muli, sans-serif !important;">Payment Mode</h4>

                        </div>

                        <div class="payment_option" id="payy">

                            <div class="custome-radio">

                                <input class="form-check-input payment_option payment_emthod" onchange="updateAmount(2)" type="radio"
                                    name="payment_option" id="exampleRadios4" value="2" />

                                <label class="form-check-label" for="exampleRadios4">Online Payment</label> <br />

                                <span class="higlight"> Get <span style="color: #ff324d;">upto 2%</span> discount on
                                    prepaid order</span>

                            </div>

                            <div class="custome-radio">
                                <input class="form-check-input payment_option payment_emthod" onchange="updateAmount(1)" type="radio" name="payment_option" id="exampleRadios3" checked value="1"/>

                                <label class="form-check-label" for="exampleRadios3">Cash On Delivery (COD)</label> <br />

                                <span class="higlight"> <span style="color: #ff324d;">₹10</span> Will be charged extra for
                                    cash on delivery</span>

                            </div>

                        </div>

                        <div class="paymet_photos d-flex gap-5">

                            <div class="razorPay">

                                <img width="80px" src="images/download.png" alt="" />

                            </div>

                            <div class="phonePay">

                                <img width="80px" src="images/download (1).png" alt="" />

                            </div>

                            <div class="Paytm">

                                <img width="80px" src="images/download (2).png" alt="" />

                            </div>

                        </div>

                    </div>

                    <hr class="mb-4" />

                    <button type="button" class="animated-button" onclick="placeOrder()"><span>Place Order</span> <span></span></button>

                    {{-- <div id="fixedButton" class="store_data d-flex butn-fxd hidden-button d-lg-none"
                        style="bottom: 0 !important;">

                        <a href="#">

                            <button class="btn btn-warning btn-block btn-lg"><span>Place Order</span>

                                <span></span></button>

                        </a>

                        <div class="para_sil">

                            <p class="mb-0">Price ₹20</p>

                            <a style="color: #fff;" href="#payy">

                                <p>view Details</p>

                            </a>

                        </div>

                    </div> --}}

                </form>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
$(document).ready(function() {
    updateAmount(1);
});

function applyWallet() {
    const order_id = "{{ $orderdetails->id }}";
    const totalorderAmount = $('#totalorderAmount');
    const total_amount = totalorderAmount.text();
    const status = $('#wallet').is(':checked') ? 1 : 0;

    const discountWalletAmount = $('#discountWalletAmount');
    const totalwalletAmount = $('#totalwalletAmount');


    $.ajax({
        url: "{{ route('checkout.apply-wallet') }}",
        type: 'POST',
        data: {
            order_id: order_id,
            status: status,
            amount: total_amount,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            discountWalletAmount.text(`-${response.discount}`);
            totalwalletAmount.text(`Wallet(${response.wallet_amount})`);
            totalorderAmount.text(response.total_amount);
            $('#totalorderAmounti').val(convertCurrencyToFloat(response.total_amount));
        },
        error: function(xhr) {
            console.error('An error occurred while applying the wallet option.');
        }
    });
}

function applyPromocode(promoode) {

    const order_id = "{{ $orderdetails->id }}";

    const totalorderAmount = $('#totalorderAmount');

    const promoCodeName    = $('#promoCodeName');

    const promoCodeAmount  = $('#promoCodeAmount');

    const total_amount = totalorderAmount.text();

    $.ajax({
        url: "{{ route('checkout.apply-promocode') }}",
        type: 'POST',
        data: {
            order_id: order_id,
            promoode: promoode,
            amount: total_amount,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            promoCodeAmount.text(`-${response.promo_discount}`);
            promoCodeName.text(response.promocode_name);
            totalorderAmount.text(response.total_amount);
            $('#totalorderAmounti').val(convertCurrencyToFloat(response.total_amount));
        },
        error: function(xhr) {
            console.error('An error occurred while applying the wallet option.');
        }
    });
}

function applyGiftCard(giftCardID) {

    const order_id = "{{ $orderdetails->id }}";

    const totalorderAmount = $('#totalorderAmount');

    const giftCardName    = $('#giftCardName');

    const GiftCardAmount  = $('#GiftCardAmount');

    const total_amount = totalorderAmount.text();

    $.ajax({
        url: "{{ route('checkout.apply-gift-card') }}",
        type: 'POST',
        data: {
            order_id: order_id,
            gift_card_id: giftCardID,
            amount: total_amount,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            GiftCardAmount.text(`+${response.amount}`);
            giftCardName.text(response.name);
            totalorderAmount.text(response.total_amount);
            $('#totalorderAmounti').val(convertCurrencyToFloat(response.total_amount));
        },
        error: function(xhr) {
            console.error('An error occurred while applying the wallet option.');
        }
    });
}

function updateAmount(type) {

    const codContainer = $('#CodCaharges');

    const codChargeAmount = $('#codChargeAmount');

    const totalorderAmount = $('#totalorderAmount');

    const codCharge = parseFloat('{{ getConstant()->cod_charge }}');

    let total_amount;

    if(type == 1) {

      total_amount = convertCurrencyToFloat(totalorderAmount.text()) + codCharge;

      totalorderAmount.text(`₹${total_amount}`);

       $('#totalorderAmounti').val(total_amount);

      codChargeAmount.text(`₹${codCharge}`);

      codContainer.removeClass('d-none').addClass('d-block');

    }else{

      total_amount = convertCurrencyToFloat(totalorderAmount.text()) - codCharge;

      $('#totalorderAmounti').val(total_amount);

      totalorderAmount.text(`₹${total_amount}`)

      codChargeAmount.text(`₹${codCharge}`);

      codContainer.removeClass('d-block').addClass('d-none');

    }
}

function convertCurrencyToFloat(value) {

    const cleanedValue = value.replace(/[^\d.]/g, '');

    return parseFloat(cleanedValue);
}

function placeOrder() {

    $.ajax({
        url: "{{ route('checkout.place-order') }}",
        type: 'POST',
        data: $('#placeOrder').serialize(),
        success: function(response) {
            // console.log();
            if (response.data.form != 1) {

                var options = {
                    "key": "{{ config('services.razorpay.key_id') }}",
                    "amount": response.data.amount,
                    "currency": "INR",
                    "name": "OSwal",
                    "description": "Test Transaction",
                    "image": "{{ asset('images/oswal-logo.png') }}",
                    "order_id": response.data.razor_order_id, // Razorpay Order ID
                    "callback_url": "{{ url('/checkout/verify-payment') }}", // Callback for payment verification
                    "prefill": {
                        "name": response.data.name,
                        "email": response.data.email,
                        "phone": response.data.phone
                    },
                    "notes": {
                        "address": "Razorpay Corporate Office"
                    },
                    "theme": {
                        "color": "#3399cc"
                    }
                };

                // Initialize and open the Razorpay payment gateway
                var rzp1 = new Razorpay(options);
                rzp1.open();
            }else{
               
                window.location.href=`{{ route('checkout.order-success', ['order_id' => '__ORDER_ID__']) }}`.replace('__ORDER_ID__', response.data.order_id);;
                
            }
        },
        error: function(xhr) {
            console.error('An error occurred while processing the payment option.');
        }
    });
}



</script>

@endpush
