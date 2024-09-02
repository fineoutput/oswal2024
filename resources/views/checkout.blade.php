@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

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

                    <li class="list-group-item d-flex justify-content-between lh-condensed">

                        <div class="clls">

                            <img width="80px" src="images/mirch.jpg" alt="" />

                            <div class="cehc_txt">

                                <h6 class="text-danger">Mirchi Powder</h6>

                                <small class="text-muted">Brief description</small>

                            </div>

                        </div>

                        <span class="text-muted">₹12</span>

                    </li>

                    <li class="list-group-item d-flex justify-content-between lh-condensed">

                        <div class="clls">

                            <img width="80px" src="images/haldi2.jpg" alt="" />

                            <div class="cehc_txt">

                                <h6 class="text-danger">Haldi Powder</h6>

                                <small class="text-muted">Brief description</small>

                            </div>

                        </div>

                        <span class="text-muted">₹8</span>

                    </li>

                    <li class="list-group-item d-flex justify-content-between lh-condensed">

                        <div class="clls">

                            <img width="80px" src="images/dhaniya.jpg" alt="" />

                            <div class="cehc_txt">

                                <h6 class="text-danger">Dhaniya Powder</h6>

                                <small class="text-muted">Brief description</small>

                            </div>

                        </div>

                        <span class="text-muted">₹5</span>

                    </li>


                    <li class="list-group-item d-flex justify-content-between bg-light">

                        <div class="text-success">

                            <h6 class="my-0">Promo code</h6>

                            <small>EXAMPLECODE</small>

                        </div>

                        <span class="text-success">-₹5</span>

                    </li>

                    <li class="list-group-item d-flex justify-content-between">

                        <span>Total (INR)</span>

                        <strong>₹20</strong>

                    </li>

                </ul>

                <div class="gift-card-section ribbon" id="giftCardSection">

                    <div class="age_class d-flex justify-content-center">

                        <p><b>Click here to select a gift card</b></p>

                    </div>

                </div>

                <div class="gift-card-list" id="giftCardList">

                    <div class="gift-card-item" data-id="1">

                        <img src="https://cdn-icons-png.flaticon.com/128/7312/7312829.png" alt="Gift Card 1" />

                        <p>get 10% off for this item</p>

                    </div>

                    <div class="gift-card-item" data-id="2">

                        <img src="https://cdn-icons-png.flaticon.com/128/4293/4293183.png" alt="Gift Card 2" />

                        <p>₹20 off for this item</p>

                    </div>

                    <div class="gift-card-item" data-id="3">

                        <img src="https://cdn-icons-png.flaticon.com/128/7646/7646924.png" alt="Gift Card 3" />

                        <p>₹30 off for this item</p>

                    </div>

                </div>

                <div class="promo-section">

                    <div class="promo-title">Choose Your Promo Code</div>

                    <div class="promo-options">

                        <button class="promo-option" data-code="PROMO10">10% OFF</button>

                        <button class="promo-option" data-code="PROMO20">20% OFF</button>

                        <button class="promo-option" data-code="PROMO30">30% OFF</button>

                    </div>

                    <input type="text" id="promoCodeInput" class="promo-code-input" placeholder="Enter or select promo code" readonly />

                    <button class="apply-button" id="applyButton">Apply Code</button>

                </div>

            </div>

            <div class="col-md-8 order-md-1">

                <div class="chek_rees d-flex gap-5">

                    <h4 class="mb-3">Billing address</h4>

                    <p>or</p>

                    <a href="add_Checkout_address.html">

                        <button style="padding: 4px 12px;" class="animated-button small_btns">
                            <span>Choose Address</span><span></span>
                        </button>

                    </a>

                </div>

                <form class="needs-validation" novalidate>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label for="firstName">First name</label>

                            <input type="text" class="form-control" id="firstName" placeholder="" value="" required />

                            <div class="invalid-feedback">

                                Valid first name is required.

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label for="lastName">Last name</label>

                            <input type="text" class="form-control" id="lastName" placeholder="" value="" required />

                            <div class="invalid-feedback">

                                Valid last name is required.

                            </div>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label for="username">Username</label>

                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text">@</span>

                            </div>

                            <input type="text" class="form-control" id="username" placeholder="Username" required />

                            <div class="invalid-feedback" style="width: 100%;">

                                Your username is required.

                            </div>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label for="email">Email <span class="text-muted">(Optional)</span></label>

                        <input type="email" class="form-control" id="email" placeholder="you@example.com" />

                        <div class="invalid-feedback">

                            Please enter a valid email address for shipping updates.

                        </div>

                    </div>

                    <div class="mb-3">

                        <label for="address">Address</label>

                        <input type="text" class="form-control" id="address" placeholder="1234 Main St" required />

                        <div class="invalid-feedback">

                            Please enter your shipping address.

                        </div>

                    </div>

                    <div class="mb-3">

                        <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>

                        <input type="text" class="form-control" id="address2" placeholder="Apartment or suite" />

                    </div>

                    <div class="row">

                        <div class="col-md-5 mb-3">

                            <label for="State">State</label>

                            <select class="custom-select d-block w-100" id="State" required>

                                <option value="">Choose...</option>

                                <option value="">Choose...</option>

                                <option>Rajasthan</option>

                                <option>Punjab</option>

                                <option>Hariyana</option>

                                <option>Bihar</option>

                                <option>Tamil Nadu</option>

                                <option>Delhi</option>

                                <option>Mumbai</option>

                            </select>

                            <div class="invalid-feedback">

                                Please select a valid State.

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="city">City</label>

                            <select class="custom-select d-block w-100" id="city" required>

                                <option value="">Choose...</option>

                                <option>Rajasthan</option>

                                <option>Punjab</option>

                                <option>Hariyana</option>

                                <option>Bihar</option>

                                <option>Tamil Nadu</option>

                                <option>Delhi</option>

                                <option>Mumbai</option>

                            </select>

                            <div class="invalid-feedback">

                                Please provide a valid state.

                            </div>

                        </div>

                        <div class="col-md-3 mb-3">

                            <label for="pin">Pin</label>

                            <input type="text" class="form-control" id="zip" placeholder="" required />

                            <div class="invalid-feedback">

                                Pin code required.

                            </div>

                        </div>

                    </div>


                    <div class="mt-2 order_review mb-2 p-3" style="margin-bottom: 10px; padding: 10px 30px; padding-bottom: 0px !important;" id="wallet_div">

                        <div class="form-group-1" style="margin-bottom: 15px;">

                            <div class="heading_s1">

                                <h4 style="font-size: 17px; font-family: Muli, sans-serif !important;">Apply Wallet</h4>

                            </div>

                            <input type="checkbox" name="wallet" id="wallet" class="wallet" checked="" value="" />

                            <label class="wallet-size mb-0" for="wallet"> Wallet (₹0)</label>

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

                            <div class="custome-radio" onclick="update_amount(2)">

                                <input class="form-check-input payment_option payment_emthod" type="radio" name="payment_option" id="exampleRadios4" value="2" />

                                <label class="form-check-label" for="exampleRadios4">Online Payment</label> <br />

                                <span class="higlight"> Get <span style="color: #ff324d;">upto 2%</span> discount on prepaid order</span>

                            </div>

                            <div class="custome-radio" onclick="update_amount(1)">

                                <input class="form-check-input payment_option payment_emthod" required=""

                                    type="radio" name="payment_option" id="exampleRadios3" value="1"

                                    checked="" />

                                <label class="form-check-label" for="exampleRadios3">Cash On Delivery (COD)</label> <br />

                                <span class="higlight"> <span style="color: #ff324d;">₹10</span> Will be charged extra for cash on delivery</span>

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

                    <button class="animated-button"><span>Place Order</span> <span></span></button>

                    <div id="fixedButton" class="store_data d-flex butn-fxd hidden-button d-lg-none" style="bottom: 0 !important;">

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

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
