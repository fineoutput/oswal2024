@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

@php
$OrderDetails = $orderdetails->orderDetails;

$addr_string = "Doorflat {$userAddress->doorflat}, ";

if (!empty($userAddress->landmark)) {

$addr_string .= "{$userAddress->landmark}, ";
}

$addr_string .= "{$userAddress->address}, {$userAddress->citys->city_name }, {$userAddress->states->state_name}, {$userAddress->zipcode}";

$giftCards = App\Models\GiftCard::where('is_active', 1)->get();

$promocodes = App\Models\Promocode::where('is_active', 1)->get();

$promoStatus = DB::table('gift_promo_status')->where('id', 1)->value('is_active');

$giftCardStatus = DB::table('gift_promo_status')->where('id', 2)->value('is_active');

@endphp

<div class="shopping_cart_sect">

    <div> </div>

</div>

<div class="container">

    <div class="py-5 text-center">

        <h2>Checkout Page</h2>

        <p class="lead" style="font-size: 0.8rem;">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Incidunt consequatur aperiam laboriosam?</p>

    </div>

    <div class="row">

        <div class="col-md-6 order-md-2 mb-4">

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



                    </div>
                    <div class="cehc_txt text-center">

                        <h6 class="">{{ $product->name }} <span style="color:#d81828; font-size:1rem; font-family:'Courier New', Courier, monospace;"></span></h6>

                        <small class="text-muted">{{ $category->name }}</small>
                        <p>({{ formatPrice($type->selling_price) }} <b> X {{ $orderItem->quantity }})</b></p>
                    </div>
                    <span class="text-muted"> {{formatPrice($type->selling_price*$orderItem->quantity) }}</span>

                </li>
                @endforeach

                <li class="list-group-item d-flex justify-content-between bg-light">

                    <div class="text-success">

                        <h6 class="my-0"><b>Cart Total</b></h6>

                    </div>

                    <span class="text-success">{{ formatPrice($orderdetails->sub_total)}}</span>

                </li>

                <li class="list-group-item d-flex justify-content-between bg-light">

                    <div class="text-danger">

                        <h6 class="my-0">Shiping Charge</h6>

                    </div>

                    <span class="text-danger">+₹{{ $orderdetails->delivery_charge }}</span>

                </li>

                @if($promoStatus == 1)
                <li class="list-group-item d-flex justify-content-between bg-light">
                    <div class="text-success">
                        <h6 class="my-0">Promo code</h6>

                        @php
                        $promocode = $orderdetails->promocode ? App\Models\Promocode::find($orderdetails->promocode) : null;
                        @endphp

                        <small id="promoCodeName">
                            @if($promocode)
                            {{ $promocode->promocode }}
                            @else
                            No promo code applied
                            @endif
                        </small>
                    </div>

                    <span class="text-success" id="promoCodeAmount">

                        -{{ formatPrice($orderdetails->promo_deduction_amount) }}

                    </span>

                    <button class="btn btn-danger @unless($orderdetails->promocode) d-none @endunless" id="removpromo" onclick="removePromocode()">Remove</button>
                </li>
                @endif

                <li class="list-group-item d-flex justify-content-between bg-light">

                    <div class="text-success">

                        <h6 class="my-0">Wallet Amount</h6>

                    </div>

                    <span class="text-success" id="discountWalletAmount"> -{{formatPrice($orderdetails->extra_discount) }} </span>

                </li>

                @if($giftCardStatus == 1)

                <li class="list-group-item d-flex justify-content-between bg-light">

                    <div class="text-success">

                        <h6 class="my-0">Gift Card</h6>

                        @php
                        $giftCard = $orderdetails->gift_id ? App\Models\GiftCard::find($orderdetails->gift_id) : null;
                        @endphp

                        <small id="giftCardName">
                            @if($giftCard)
                            {{ $giftCard->name }}
                            @else
                            No Gift Card applied
                            @endif
                        </small>
                    </div>

                    <span class="text-success" id="GiftCardAmount">

                        +{{ formatPrice($orderdetails->gift_amt) }}

                    </span>

                    <!-- <button class="btn btn-danger @unless($orderdetails->gift_id) d-none @endunless" id="removegiftCard" onclick="removeGiftCard()">Remove</button> -->
                </li>
                @endif

                <li class="list-group-item d-flex justify-content-between bg-light d-none" id="CodCaharges">

                    <div class="text-danger">

                        <h6 class="my-0">COD Charges</h6>

                    </div>

                    <span class="text-danger" id='codChargeAmount'> </span>

                </li>
    
                <li class="list-group-item d-flex justify-content-between">
<hr>
                    <span><b>Total (INR)</b></span>

                    <strong id="totalorderAmount">{{formatPrice($orderdetails->total_amount)}}</strong>

                </li>

            </ul>

            @if($giftCardStatus == 1)

            <div class="gift-card-section ribbon" id="giftCardSection">

                <div class="age_class d-flex justify-content-center">

                    <p><b id="cleargiftsecation">Click here to select a gift card</b></p>

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

            @endif

            @if($promoStatus == 1)

            <div class="promo-section">

                <div class="promo-title">Choose Your Promo Code</div>
                <button class="toggle-options-button" id="toggleButtonpromo">Choose Promo Codes</button>

                <div class="promo-options" id="promoOptions" style="display: none;">


                    @foreach ($promocodes as $promocode)

                    <button class="promo-option" onclick="applyPromocode('{{ $promocode->promocode }}')" data-code="{{ $promocode->promocode }}">{{ $promocode->percent }}% OFF</button>

                    @endforeach

                    {{-- <button class="promo-option" data-code="PROMO20">20% OFF</button>

                            <button class="promo-option" data-code="PROMO30">30% OFF</button> --}}

                </div>

                <!-- <input type="text" id="promoCodeInput" class="promo-code-input"
                            placeholder="Enter or select promo code" readonly />

                        <button class="apply-button" id="applyButton">Apply Code</button> -->

            </div>

            @endif

        </div>

        <div class="col-md-6 order-md-1">

            <div class="chek_rees d-flex gap-5">

                <h4 class="mb-3">Billing address</h4>

                <a href="{{ route('checkout.get-address',['place' => 'checkout']) }}">
                    <button id="fixedButton" class="btn btn-warning btn-block btn-lg butn-fxd hidden-button"><span>Change Address</span> <span> </span></button>
                </a>

            </div>

            <div class="mt-2 order_review mb-2 p-3" style="margin-bottom: 10px; padding: 10px 30px; padding-bottom: 0px !important;">

                <div class="form-group-1" style="margin-bottom: 15px;">

                    <div class="heading_s1">

                        <h4 style="font-size: 17px; font-family: Muli, sans-serif !important;">User Address</h4>

                    </div>

                    <span>{{ $userAddress->name }} , {{ $addr_string }} </span>

                </div>

            </div>


            <form class="needs-validation" method="POST" novalidate id="placeOrder">

                @csrf
                <input type="hidden" id="totalorderAmounti" name="totalamount" value="{{$orderdetails->total_amount}}">

                <input type="hidden" name="order_id" value="{{$orderdetails->id}}">

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
                            <input class="form-check-input payment_option payment_emthod" onchange="updateAmount(1)" type="radio" name="payment_option" id="exampleRadios3" checked value="1" />

                            <label class="form-check-label" for="exampleRadios3">Cash On Delivery (COD)</label> <br />

                            <span class="higlight"> <span style="color: #ff324d;">₹{{getConstant()->cod_charge;}}</span> Will be charged extra for
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

                {{-- <div id="fixedButton" class="store_data d-flex butn-fxd hidden-button d-lg-none" style="bottom: 0 !important;">

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

                    </div>  --}}

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

        const status = $('#wallet').is(':checked') ? 1 : 0;

        const discountWalletAmount = $('#discountWalletAmount');

        const totalwalletAmount = $('#totalwalletAmount');

        $.ajax({
            url: "{{ route('checkout.apply-wallet') }}",
            type: 'POST',
            data: {
                order_id: order_id,
                status: status,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {

                discountWalletAmount.text(`-${response.discount}`);

                totalwalletAmount.text(`Wallet(${response.wallet_amount})`);

                if ($('input[name="payment_option"]:checked').val() == 1) {

                    final_amount = response.cod_amount;

                } else {

                    final_amount = response.prepared_amount;

                }

                totalorderAmount.text(final_amount);

                $('#totalorderAmounti').val(convertCurrencyToFloat(final_amount));

                showNotification(response.message, 'success');

            },
            error: function(xhr) {
                console.error('An error occurred while applying the wallet option.');
            }

        });
    }

    function applyPromocode(promoode) {

        const order_id = "{{ $orderdetails->id }}";

        const totalorderAmount = $('#totalorderAmount');

        const promoCodeName = $('#promoCodeName');

        const promoCodeAmount = $('#promoCodeAmount');

        const total_amount = "{{ $orderdetails->sub_total }}";

        const removpromo = $('#removpromo');

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

                if (response.success) {

                    promoCodeAmount.text(`-${response.promo_discount}`);

                    promoCodeName.text(response.promocode_name);

                    if ($('input[name="payment_option"]:checked').val() == 1) {

                        final_amount = response.cod_amount;

                    } else {

                        final_amount = response.prepared_amount;
                    }

                    totalorderAmount.text(final_amount);

                    removpromo.removeClass('d-none')

                    $('#totalorderAmounti').val(convertCurrencyToFloat(final_amount));

                    showNotification(response.message, 'success');

                } else {

                    showNotification(response.message, 'error');
                }
            },
            error: function(xhr) {
                console.error('An error occurred while applying the wallet option.');
            }
        });
    }

    function removePromocode() {

        const order_id = "{{ $orderdetails->id }}";

        const totalorderAmount = $('#totalorderAmount');

        const promoCodeName = $('#promoCodeName');

        const promoCodeAmount = $('#promoCodeAmount');

        const removpromo = $('#removpromo');

        $.ajax({
            url: "{{ route('checkout.remove-promocode') }}",
            type: 'POST',
            data: {
                order_id: order_id,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {

                if (response.success) {

                    promoCodeAmount.text(`-${response.promo_discount}`);

                    promoCodeName.text(response.promocode_name);

                    if ($('input[name="payment_option"]:checked').val() == 1) {

                        final_amount = response.cod_amount;

                    } else {

                        final_amount = response.prepared_amount;
                    }

                    totalorderAmount.text(final_amount);

                    $('#totalorderAmounti').val(convertCurrencyToFloat(final_amount));

                    removpromo.addClass('d-none')

                    showNotification(response.message, 'success');

                } else {

                    showNotification(response.message, 'error');

                }

            },
            error: function(xhr) {
                console.error('An error occurred while applying the wallet option.');
            }
        });
    }

    function applyGiftCard(giftCardID) {

        const order_id = "{{ $orderdetails->id }}";

        const totalorderAmount = $('#totalorderAmount');

        const giftCardName = $('#giftCardName');

        const GiftCardAmount = $('#GiftCardAmount');

        const total_amount = "{{ $orderdetails->sub_total }}";

        const removegiftCard = $('#removegiftCard');

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

                if (response.success) {

                    GiftCardAmount.text(`+${response.amount}`);

                    giftCardName.text(response.name);

                    if ($('input[name="payment_option"]:checked').val() == 1) {

                        final_amount = response.cod_amount;

                    } else {

                        final_amount = response.prepared_amount;
                    }

                    totalorderAmount.text(final_amount);

                    $('#totalorderAmounti').val(convertCurrencyToFloat(final_amount));

                    removegiftCard.removeClass('d-none')

                    showNotification(response.message, 'success');

                } else {

                    showNotification(response.message, 'error');

                }
            },
            error: function(xhr) {
                showNotification(response.message, 'error');
                console.error('An error occurred while applying the wallet option.');
            }
        });
    }

function setupSelectableSection(sectionId, listId, itemClass) {

const sectionElement = document.getElementById(sectionId);
const listElement = document.getElementById(listId);

// Toggle list visibility when the section is clicked
sectionElement.addEventListener('click', () => {
  listElement.style.display = listElement.style.display === 'block' ? 'none' : 'block';
});

// Handle gift card item selection
document.querySelectorAll(`.${itemClass}`).forEach(item => {
  item.addEventListener('click', function () {
    // Deselect all items and select the clicked one
    document.querySelectorAll(`.${itemClass}`).forEach(i => i.classList.remove('selected'));
    this.classList.add('selected');

    // Update the selected gift card information in the section
    const selectedText = this.querySelector('p').innerText;
    const selectedImageSrc = this.querySelector('img').src;
    sectionElement.innerHTML = `
      <p>${selectedText}</p>
      <img src="${selectedImageSrc}" alt="Selected" style="width: 40px; margin-left: 10px;">
      <button class="btn btn-danger" id="removeSelected" style="margin-left: 10px;">Remove</button>
    `;

    // Hide the list after selection
    listElement.style.display = 'none';

    // Add event listener for the remove button
    document.getElementById('removeSelected').addEventListener('click', function () {
      const giftCardID = item.getAttribute('data-id'); // Assuming the gift card ID is stored in a data attribute

      // AJAX request to remove the gift card
      removeGiftCard(giftCardID);

      // Clear selection and reset section content
      document.querySelectorAll(`.${itemClass}`).forEach(i => i.classList.remove('selected'));
      sectionElement.innerHTML = '<p>Select a product</p>'; // Reset the section content

      // Show the list again
      listElement.style.display = 'block';
    });
  });
});
}

function removeGiftCard(giftCardID) {
const order_id = "{{ $orderdetails->id }}";

const totalorderAmount = $('#totalorderAmount');
const giftCardName = $('#giftCardName');
const GiftCardAmount = $('#GiftCardAmount');
const removegiftCard = $('#removegiftCard');
const cleargiftsecation = $('#cleargiftsecation');

$.ajax({
  url: "{{ route('checkout.remove-gift-card') }}",
  type: 'POST',
  data: {
    order_id: order_id,
    gift_card_id: giftCardID, // Pass the gift card ID to the server
    _token: "{{ csrf_token() }}"
  },
  success: function(response) {
    if (response.success) {
      GiftCardAmount.text(`+${response.amount}`);
      giftCardName.text(response.name);

      let final_amount;
      if ($('input[name="payment_option"]:checked').val() == 1) {
        final_amount = response.cod_amount;
      } else {
        final_amount = response.prepared_amount;
      }

      totalorderAmount.text(final_amount);
      cleargiftsecation.text('Click here to select a gift card');
      $('#totalorderAmounti').val(convertCurrencyToFloat(final_amount));
      removegiftCard.addClass('d-none');

      showNotification(response.message, 'success');
    } else {
      showNotification(response.message, 'error');
    }
  },
  error: function(xhr) {
    showNotification('An error occurred while removing the gift card.', 'error');
    console.error('An error occurred while removing the gift card.');
  }
});
}


    function updateAmount(type) {

        const codContainer = $('#CodCaharges');

        const codChargeAmount = $('#codChargeAmount');

        const totalorderAmount = $('#totalorderAmount');

        const codCharge = parseFloat('{{ getConstant()->cod_charge }}');

        let total_amount;

        if (type == 1) {

            total_amount = convertCurrencyToFloat(totalorderAmount.text()) + codCharge;

            totalorderAmount.text(`₹${total_amount}`);

            $('#totalorderAmounti').val(total_amount);

            codChargeAmount.text(`+₹${codCharge}`);

            codContainer.removeClass('d-none').addClass('d-block');

        } else {

            total_amount = convertCurrencyToFloat(totalorderAmount.text()) - codCharge;

            $('#totalorderAmounti').val(total_amount);

            totalorderAmount.text(`₹${total_amount}`)

            codChargeAmount.text(`+₹${codCharge}`);

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
                } else {

                    window.location.href = `{{ route('checkout.order-success', ['order_id' => '__ORDER_ID__']) }}`.replace('__ORDER_ID__', response.data.order_id);;

                }
            },
            error: function(xhr) {
                console.error('An error occurred while processing the payment option.');
            }
        });
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

            if(!response.success){

                showNotification(response.message, 'error');  
                return;
            }

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