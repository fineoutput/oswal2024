@extends('layouts.app')

@section('title', $title ?? '') 

@section('content')

<section class="h-100 h-custom" style="background-color: #d818281f;">

    <div class="container py-5 h-100">

        <div class="row d-flex justify-content-center align-items-center h-100">

            <div class="col-12">

                <div class="card-registration card-registration-2" style="border-radius: 15px; background-color: #fff;">

                    <div class="card-body p-0">

                        <div class="row g-0">

                            <div class="col-lg-9">

                                <div class="p-5">

                                    <div class="d-flex justify-content-between align-items-center mb-5">

                                        <h1 class="fw-bold mb-0">Shopping Cart</h1>

                                        <h6 class="mb-0 text-muted">{{ count($cartItems) }} items</h6>

                                    </div>

                                    <hr class="my-4 script" />

                                    <!-- Headings for Product Details -->

                                    <div class="row mb-4 d-flex script">

                                        <div class="col-12 col-md-2 text-center text-md-start">

                                            <h6>

                                                Product <br />

                                                Image

                                            </h6>

                                        </div>

                                        <div class="col-12 col-md-2 text-center text-md-start">

                                            <h6>

                                                Product <br />

                                                Name

                                            </h6>

                                        </div>

                                        <div class="col-12 col-md-2 text-center text-md-start">
                                            <h6>Quantity</h6>
                                        </div>

                                        <div class="col-12 col-md-1 text-center text-md-start">
                                            <h6>Price</h6>
                                        </div>

                                        <div class="col-12 col-md-2 text-center text-md-start">
                                            <h6>Type</h6>
                                        </div>

                                        <div class="col-12 col-md-2 text-center text-md-start">
                                            <h6>SubTotal</h6>
                                        </div>

                                        <div class="col-12 col-md-1 text-center text-md-end p-0">
                                            <h6>Remove</h6>
                                        </div>

                                    </div>

                                    <hr class="my-4" />

                                    <?php $totalAmount = 0; ?>

                                    @forelse ($cartItems as $cartdata) 
                                    
                                    @php
                                     $product = $cartdata->product; 
                                     
                                     $category = $cartdata->category; 
                                     
                                     $productType = $product->type->filter(function ($type) use ($globalState, $globalCity) { 

                                        return $type->state_id == $globalState && $type->city_id == $globalCity; 

                                    });
                                    
                                    $totalAmount += $cartdata->total_qty_price;
                                    
                                    @endphp

                                    <div class="row mb-4 d-flex justify-content-between align-items-center flex-column flex-md-row" id="cartrow{{ $product->id }}">

                                        <div class="col-12 col-md-2 d-flex justify-content-center justify-content-md-start">

                                            <img src="{{ asset($product->img1) }}" class="img-fluid rounded-3" alt="Mirch Powder" />

                                            <a href="#!" class="text-muted d-lg-none"><i class="fas fa-times"></i></a>

                                        </div>

                                        <div class="col-12 col-md-2 text-center text-md-start">

                                            <h6 class="text-muted">{{ $product->name }}</h6>

                                            <h6 class="mb-0">{{ $category->name }}</h6>
                                            
                                        </div>

                                        <div class="col-12 col-md-2 d-flex justify-content-center justify-content-md-start mt-2 mt-md-0">
                                            <!-- Decrease Quantity Button -->
                                            <button class="btn btn-link p-2 ripple" onclick="changeQuantity(this, '{{$cartdata->id}}', '{{$cartdata->type->selling_price}}', 'down')" style="font-size: 10px;">
                                                <i class="fas fa-minus"></i>
                                            </button>

                                            <!-- Quantity Input -->
                                            <input
                                                id="form1"
                                                min="1"
                                                max="5"
                                                name="quantity"
                                                value="{{ $cartdata->quantity }}"
                                                type="number"
                                                class="form-control form-control-sm carts_puts intern_bord"
                                                onkeyup="UpdateQuantity('{{$cartdata->id}}','{{$cartdata->type->selling_price}}', this)"
                                            />

                                            <!-- Increase Quantity Button -->
                                            <button class="btn btn-link p-2 ripple_set" style="font-size: 10px;" onclick="changeQuantity(this, '{{$cartdata->id}}', '{{$cartdata->type->selling_price}}', 'up')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>

                                        <div class="col-12 col-md-1 d-flex flex-column align-items-center align-items-md-start mt-2 mt-md-0">
                                            <h6 class="mb-0">{{ formatPrice($cartdata->type->selling_price) }}</h6>
                                        </div>

                                        <div class="col-12 col-md-2 d-flex flex-column align-items-center align-items-md-start mt-2 mt-md-0">
                                            <select name="quality" id="qty_select" class="form-select intern_bord">
                                                <option value="type">Type</option>

                                                @foreach ($productType as $type)

                                                <option value="{{ $type->id }}" {{ $type->id == $cartdata->type->id ? 'selected' : '' }}> {{ $type->type_name }} </option>

                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-2 d-flex flex-column align-items-center align-items-md-start mt-2 mt-md-0">
                                            <h6 class="mb-0">{{ formatPrice($cartdata->total_qty_price) }}</h6>
                                        </div>

                                        <div class="col-12 col-md-1 d-flex justify-content-center justify-content-md-end mt-2 mt-md-0 d-none d-lg-block" onclick="removeToCart('{{ $product->id }}' ,'{{$cartdata->id  }}')">
                                            <a href="#!" class="text-muted"><i class="fas fa-times"></i></a>
                                        </div>
                                    </div>

                                    <hr class="my-4" />

                                    @empty No Product Found @endforelse
                                </div>
                            </div>

                            <div class="col-lg-3 bg-grey">
                                <div class="p-5">
                                    <h2 class="fw-bold mb-5 mt-2 pt-1 color:red;">Summary</h2>

                                    <hr class="my-4" />

                                    <div class="d-flex justify-content-between mb-4">
                                        <h5 class="text-muted">Items</h5>

                                        <h5>{{ count($cartItems) }}</h5>
                                    </div>

                                    <hr class="my-4" />

                                    <div class="d-flex justify-content-between mb-5">
                                        <h5 class="text-muted">Total</h5>

                                        <h5>{{formatPrice($totalAmount) }}</h5>
                                    </div>

                                    @if (Auth::check())
                                    <a href="{{ route('checkout.get-address') }}">
                                        <button id="fixedButton" class="btn btn-warning btn-block btn-lg butn-fxd hidden-button"><span>Proceed to Pay</span> <span> </span></button>
                                    </a>
                                    @else
                                    <a href="javascript::void(0)" onclick="showModal(event)">
                                        <button id="fixedButton" class="btn btn-warning btn-block btn-lg butn-fxd hidden-button"><span>Proceed to Pay</span> <span> </span></button>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection @push('scripts')

<script>
    function removeToCart(Pid, cid) {
        $.ajax({
            url: "{{ route('cart.remove-to-cart') }}",
            type: "GET",
            data: {
                cart_id: cid,
            },
            success: function (response) {
                $(`#cartrow${Pid}`).remove();
                location.reload();
            },
            error: function (xhr) {
                console.error("An error occurred while removing from the wishlist.");
            },
        });
    }

    function UpdateQuentity(cid, price) {
        $.ajax({
            url: "{{ route('cart.update-qty') }}",
            type: "GET",
            data: {
                cart_id: cid,
                qty: cid,
                price: price,
            },
            success: function (response) {
                // location.reload();
            },
            error: function (xhr) {
                console.error("An error occurred while removing from the wishlist.");
            },
        });
    }

    function changeQuantity(button, cartId, price, direction) {
        let inputField = $(button).parent().find("input[type=number]").get(0);

        if (direction === "up") {
            inputField.stepUp();
        } else {
            inputField.stepDown();
        }

        UpdateQuantity(cartId, price, inputField);
    }

    function UpdateQuantity(cartId, price, inputField) {
        let quantity = $(inputField).val();

        if (quantity <= 0) {
            alert("Quantity cannot be zero or negative.");
            return;
        } else if (quantity > 5) {
            alert("Quantity cannot be greater than 5.");
            return;
        }

        $.ajax({
            url: "{{ route('cart.update-qty') }}",
            type: "GET",
            data: {
                cart_id: cartId,
                qty: quantity,
                price: price,
            },
            success: function (response) {
                console.log("Quantity updated successfully");
                location.reload();
            },
            error: function (xhr) {
                console.error("An error occurred while updating the cart quantity.");
            },
        });
    }
</script>
@endpush
