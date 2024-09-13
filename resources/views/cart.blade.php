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

                                <div class="p-5" id="cart-container">

                                    <div class="d-flex justify-content-between align-items-center mb-5">

                                        <h1 class="fw-bold mb-0">Shopping Cart</h1>

                                        <h6 class="mb-0 text-muted">{{ count($cartItems) }} items</h6>

                                    </div>

                                    <hr class="my-4 script" />

                                    <!-- Headings for Product Details -->

                                    <div class="row mb-4 d-flex script">

                                        <div class="col-12 col-md-2 text-center text-md-start">

                                            <h6 class="d-none d-lg-block">

                                                Product <br />

                                                Image

                                            </h6>

                                        </div>

                                        <div class="col-12 col-md-2 text-center text-md-start">

                                            <h6 class="d-none d-lg-block">

                                                Product <br />

                                                Name

                                            </h6>

                                        </div>

                                        <div class="col-12 col-md-2 text-center text-md-start d-none d-lg-block">
                                            <h6>Quantity</h6>
                                        </div>

                                        <div class="col-12 col-md-1 text-center text-md-start d-none d-lg-block">
                                            <h6>Price</h6>
                                        </div>

                                        <div class="col-12 col-md-2 text-center text-md-start d-none d-lg-block">
                                            <h6>Type</h6>
                                        </div>

                                        <div class="col-12 col-md-2 text-center text-md-start d-none d-lg-block">
                                            <h6>SubTotal</h6>
                                        </div>

                                        <div class="col-12 col-md-1 text-center text-md-end p-0 d-none d-lg-block">
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

                                    <div class="row mb-4 d-flex justify-content-between align-items-center flex-column flex-md-row cartrow" id="cartrow{{ $product->id }}">

                                        <div class="col-12 col-md-2 d-flex justify-content-center justify-content-md-start">

                                            <img src="{{ asset($product->img1) }}" class="img-fluid rounded-3" alt="Mirch Powder" />

                                            <a href="#!" onclick="removeToCart('{{ $product->id }}' ,'{{$cartdata->id  }}')" class="text-muted d-lg-none"><i class="fas fa-times"></i></a>

                                        </div>

                                        <div class="col-12 col-md-2 text-center text-md-start">

                                            <h6 class="text-muted">{{ $product->name }}</h6>

                                            <h6 class="mb-0">{{ $category->name }}</h6>
                                            
                                        </div>

                                        <div class="col-12 col-md-2 d-flex justify-content-center justify-content-md-start mt-2 mt-md-0">

                                            <!-- Decrease Quantity Button -->
                                            <button class="btn btn-link p-2 ripple" onclick="changeQuantity('{{$cartdata->id}}', '{{$product->id}}', 'down')" style="font-size: 10px;">
                                                <i class="fas fa-minus"></i>
                                            </button>

                                            <!-- Quantity Input -->
                                            <input
                                                id="quantity{{ $product->id }}" 
                                                min="1"
                                                max="5"
                                                name="quantity"
                                                value="{{ $cartdata->quantity }}"
                                                type="number"
                                                class="form-control form-control-sm carts_puts intern_bord"
                                                onkeyup="UpdateQuantity('{{$cartdata->id}}','{{$cartdata->type->selling_price}}' ,'{{ $product->id }}')"
                                            />

                                            <!-- Increase Quantity Button -->
                                            <button class="btn btn-link p-2 ripple_set" style="font-size: 10px;" onclick="changeQuantity('{{$cartdata->id}}', '{{$product->id}}', 'up')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>

                                        <div class="col-12 col-md-1 d-flex flex-column align-items-center align-items-md-start mt-2 mt-md-0">
                                            <h6 class="mb-0" id="sellingpricse{{ $product->id }}">{{ formatPrice($cartdata->type->selling_price) }}</h6>
                                        </div>

                                        <div class="col-12 col-md-2 d-flex flex-column align-items-center align-items-md-start mt-2 mt-md-0">
                                            <select
                                                name="type{{ $product->id }}"
                                                id="qty_selec{{ $product->id }}"
                                                class="intern_bord form-select"
                                                onchange="updateQuantity('{{ $cartdata->id }}','{{ $product->id }}')"
                                            >
                                                <option value="" disabled>Select Type</option>
                                                @foreach ($productType as $type)
                                                    <option
                                                        value="{{ $type->id }}"
                                                        {{ $type->id == $cartdata->type->id ? 'selected' : '' }}
                                                    >
                                                        {{ $type->type_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        

                                        <div class="col-12 col-md-2 d-flex flex-column align-items-center align-items-md-start mt-2 mt-md-0">
                                            <h6 class="mb-0" id="cartqty{{ $product->id }}">{{ formatPrice($cartdata->total_qty_price) }}</h6>
                                        </div>

                                        <div class="col-12 col-md-1 d-flex justify-content-center justify-content-md-end mt-2 mt-md-0 d-none d-lg-block" onclick="removeToCart('{{ $product->id }}' ,'{{$cartdata->id  }}')">
                                            <a href="javascript:void(0)" class="text-muted"><i class="fas fa-times"></i></a>
                                        </div>

                                        {{-- <div class="col-12 col-md-1 d-flex justify-content-center justify-content-md-end mt-2 mt-md-0 d-none d-lg-block">
                                            <a href="{{ route('cart.remove-to-cart',['cart_id' =>$product->id])}}" class="text-muted"><i class="fas fa-times"></i></a>
                                        </div> --}}

                                    </div>

                                    <hr class="my-4" id="underline{{ $product->id }}" />

                                    @empty No Product Found @endforelse
                                    
                                </div>

                            </div>

                            <div class="col-lg-3 bg-grey">

                                <div class="p-5">

                                    <h2 class="fw-bold mb-5 mt-2 pt-1 color:red;">Summary</h2>

                                    <hr class="my-4" />

                                    <div class="d-flex justify-content-between mb-4">

                                        <h5 class="text-muted" >Items</h5>

                                        <h5 id="cart_counts">{{ count($cartItems) }}</h5>
                                    </div>

                                    <hr class="my-4" />

                                    <div class="d-flex justify-content-between mb-5">

                                        <h5 class="text-muted">Total</h5>

                                        <h5 id="totalamount">{{formatPrice($totalAmount) }}</h5>

                                    </div>

                                    @if (Auth::check())

                                        @if (count($cartItems) > 0)
                                            <a href="{{ route('checkout.get-address') }}" id="proceedToPayLink">
                                                <button id="fixedButton" class="btn btn-warning btn-block btn-lg butn-fxd hidden-button w-100"><span>Proceed to Pay</span> <span> </span></button>
                                            </a>
                                        @else   
                                            <a href="javascript:void(0);" onclick="showNotification('Your cart is empty.', 'error');">
                                                <button id="fixedButton" class="btn btn-warning btn-block btn-lg butn-fxd hidden-button w-100">
                                                    <span>Proceed to Pay</span>
                                                </button>
                                            </a>                                        
                                        @endif

                                    @else
                                        <a href="javascript:void(0);" onclick="showModal(event)">
                                            <button id="fixedButton" class="btn btn-warning btn-block btn-lg butn-fxd hidden-button w-100"><span>Proceed to Pay</span> <span> </span></button>
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

                $(`#underline${Pid}`).remove();

                $('#totalamount').text(response.totalAmount);

                $('#cart_count').text(response.count);

                $('#cart_counts').text(response.count);

                if ($('div.cartrow').length <= 0) {
                    
                    $('#cart-container').append('<div class="no-products">No Product Found</div>');

                    $('#proceedToPayLink').attr('href', 'javascript:void(0)').attr('onclick', "showNotification('Your cart is empty.', 'error');");
                }

                showNotification(response.message, 'success');
            },
            error: function (xhr) {
                console.error("An error occurred while removing from the wishlist.");
            },
        });
    }

    function changeQuantity(cartId, productId, direction) {

        let inputField = $(`#quantity${productId}`);

        let currentValue = parseInt(inputField.val(), 10);

        let newValue = direction === 'up' ? currentValue + 1 : currentValue - 1;

        if (newValue >= 1 && newValue <= 5) {
            
            inputField.val(newValue);

            updateQuantity(cartId, productId);
        }
        
    }

    function updateQuantity(cartId, productId) {
   
        let quantity = $(`#quantity${productId}`).val();

        let typeId = $(`#qty_selec${productId}`).val();

        let sellingPrice = $(`#sellingpricse${productId}`)

        let subtotal = $(`#cartqty${productId}`);

        let totalAmount = $('#totalamount');

        if (quantity < 1 || quantity > 5) {
            showNotification("Quantity must be between 1 and 5.", 'error');
            return;
        }

        $.ajax({
            url: "{{ route('cart.update-qty') }}",
            type: "GET",
            data: {
                cart_id: cartId,
                type_id: typeId, 
                qty: quantity,
            },
            success: function (response) {

                if (response.success) {

                    sellingPrice.text(response.selling_price);

                    subtotal.text(response.total_qty_price);

                    totalAmount.text(response.totalamount);

                    showNotification(response.message, 'success');

                } else {
                    
                    showNotification((response.message || "An error occurred while updating the quantity."), 'success');
                }
            },
            error: function (xhr) {
                console.error("An error occurred while updating the cart quantity.");
                console.error(xhr.responseText);
            }
        });
    }

</script>
@endpush
