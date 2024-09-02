@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

    <section class="h-100 h-custom" style="background-color: #d818281f;">

        <div class="container py-5 h-100">

            <div class="row d-flex justify-content-center align-items-center h-100">

                <div class="col-12">

                    <div class="card-registration card-registration-2" style="border-radius: 15px; background-color: #fff">

                        <div class="card-body p-0">

                            <div class="row g-0">

                                <div class="col-lg-8">

                                    <div class="p-5">

                                        <div class="d-flex justify-content-between align-items-center mb-5">

                                            <h1 class="fw-bold mb-0">Shopping Cart</h1>

                                            <h6 class="mb-0 text-muted">3 items</h6>

                                        </div>

                                        <hr class="my-4 script" />


                                        <!-- Headings for Product Details -->

                                        <div class="row mb-4 d-flex script">

                                            <div class="col-12 col-md-2 text-center text-md-start">

                                                <h6>Product Image</h6>

                                            </div>

                                            <div class="col-12 col-md-3 text-center text-md-start">

                                                <h6>Product Name</h6>

                                            </div>

                                            <div class="col-12 col-md-3 text-center text-md-start">

                                                <h6>Quantity</h6>

                                            </div>

                                            <div class="col-12 col-md-1 text-center text-md-start">

                                                <h6>Price</h6>

                                            </div>

                                            <div class="col-12 col-md-2 text-center text-md-start">

                                                <h6>Quality</h6>

                                            </div>

                                            <div class="col-12 col-md-1 text-center text-md-end p-0">

                                                <h6>Remove</h6>

                                            </div>

                                        </div>

                                        <hr class="my-4" />


                                        <!-- Product 1 -->

                                        <div class="row mb-4 d-flex justify-content-between align-items-center flex-column flex-md-row">

                                            <div class="col-12 col-md-2 d-flex justify-content-center justify-content-md-start">

                                                <img src="images/mirch.jpg" class="img-fluid rounded-3" alt="Mirch Powder" />

                                                <a href="#!" class="text-muted d-lg-none"><i class="fas fa-times"></i></a>

                                            </div>

                                            <div class="col-12 col-md-3 text-center text-md-start">

                                                <h6 class="text-muted">Mirch</h6>

                                                <h6 class="mb-0">Oswal Masale</h6>

                                            </div>

                                            <div class="col-12 col-md-3 d-flex justify-content-center justify-content-md-start mt-2 mt-md-0">

                                                <button class="btn btn-link px-2 ripple" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">

                                                    <i class="fas fa-minus"></i>

                                                </button>

                                                <input id="form1" min="0" name="quantity" value="1" type="number" class="form-control form-control-sm carts_puts intern_bord" />

                                                <button class="btn btn-link px-2 ripple_set" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">

                                                    <i class="fas fa-plus"></i>

                                                </button>

                                            </div>

                                            <div class="col-12 col-md-1 d-flex flex-column align-items-center align-items-md-start mt-2 mt-md-0">

                                                <h6 class="mb-0">₹30</h6>

                                            </div>

                                            <div class="col-12 col-md-2 d-flex flex-column align-items-center align-items-md-start mt-2 mt-md-0">

                                                <select name="quality" id="qty_select" class="form-select intern_bord">

                                                    <option value="type">Type</option>

                                                    <option value="1kg">1kg</option>

                                                    <option value="250gm">250gm</option>

                                                </select>

                                            </div>

                                            <div class="col-12 col-md-1 d-flex justify-content-center justify-content-md-end mt-2 mt-md-0 d-none d-lg-block">

                                                <a href="#!" class="text-muted"><i class="fas fa-times"></i></a>

                                            </div>

                                        </div>


                                        <hr class="my-4" />


                                        <!-- Product 2 -->

                                        <!-- Repeat the product structure for each additional product as needed -->

                                        <div class="row mb-4 d-flex justify-content-between align-items-center flex-column flex-md-row">

                                            <div class="col-12 col-md-2 d-flex justify-content-center justify-content-md-start">

                                                <img src="images/haldi.jpg" class="img-fluid rounded-3" alt="Haldi Powder" />

                                                <a href="#!" class="text-muted d-lg-none"><i class="fas fa-times"></i></a>

                                            </div>

                                            <div class="col-12 col-md-3 text-center text-md-start">

                                                <h6 class="text-muted">Haldi</h6>

                                                <h6 class="mb-0">Oswal Masale</h6>

                                            </div>

                                            <div class="col-12 col-md-3 d-flex justify-content-center justify-content-md-start mt-2 mt-md-0">

                                                <button class="btn btn-link px-2 ripple" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">

                                                    <i class="fas fa-minus"></i>

                                                </button>

                                                <input id="form1" min="0" name="quantity" value="1" type="number" class="form-control form-control-sm carts_puts intern_bord" />

                                                <button class="btn btn-link px-2 ripple_set" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">

                                                    <i class="fas fa-plus"></i>

                                                </button>

                                            </div>

                                            <div class="col-12 col-md-1 d-flex flex-column align-items-center align-items-md-start mt-2 mt-md-0">

                                                <h6 class="mb-0">₹30</h6>

                                            </div>

                                            <div class="col-12 col-md-2 d-flex flex-column align-items-center align-items-md-start mt-2 mt-md-0">

                                                <select name="quality" id="qty_select" class="form-select intern_bord">

                                                    <option value="type">Type</option>

                                                    <option value="1kg">1kg</option>

                                                    <option value="250gm">250gm</option>

                                                </select>

                                            </div>

                                            <div class="col-12 col-md-1 d-flex justify-content-center justify-content-md-end mt-2 mt-md-0 d-none d-lg-block">

                                                <a href="#!" class="text-muted"><i class="fas fa-times"></i></a>

                                            </div>

                                        </div>


                                        <hr class="my-4" />


                                        <!-- Product 3 -->

                                        <!-- Repeat the product structure for each additional product as needed -->

                                        <div class="row mb-4 d-flex justify-content-between align-items-center flex-column flex-md-row">

                                            <div class="col-12 col-md-2 d-flex justify-content-center justify-content-md-start">

                                                <img src="images/dhaniya.jpg" class="img-fluid rounded-3" alt="Dhaniya Powder" />

                                                <a href="#!" class="text-muted d-lg-none"><i class="fas fa-times"></i></a>

                                            </div>

                                            <div class="col-12 col-md-3 text-center text-md-start">

                                                <h6 class="text-muted">Dhaniya</h6>

                                                <h6 class="mb-0">Oswal Masale</h6>

                                            </div>

                                            <div class="col-12 col-md-3 d-flex justify-content-center justify-content-md-start mt-2 mt-md-0">

                                                <button class="btn btn-link px-2 ripple" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">

                                                    <i class="fas fa-minus"></i>

                                                </button>

                                                <input id="form1" min="0" name="quantity" value="1" type="number" class="form-control form-control-sm carts_puts intern_bord" />

                                                <button class="btn btn-link px-2 ripple_set" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">

                                                    <i class="fas fa-plus"></i>

                                                </button>

                                            </div>

                                            <div class="col-12 col-md-1 d-flex flex-column align-items-center align-items-md-start mt-2 mt-md-0">

                                                <h6 class="mb-0">₹30</h6>

                                            </div>

                                            <div class="col-12 col-md-2 d-flex flex-column align-items-center align-items-md-start mt-2 mt-md-0">

                                                <select name="quality" id="qty_select" class="form-select intern_bord">

                                                    <option value="type">Type</option>

                                                    <option value="1kg">1kg</option>

                                                    <option value="250gm">250gm</option>

                                                </select>

                                            </div>

                                            <div class="col-12 col-md-1 d-flex justify-content-center justify-content-md-end mt-2 mt-md-0 d-none d-lg-block">

                                                <a href="#!" class="text-muted"><i class="fas fa-times"></i></a>

                                            </div>

                                        </div>


                                        <hr class="my-4" />


                                        <div class="pt-5">

                                            <h6 class="mb-0">

                                                <a href="#!" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Back to shop</a>

                                            </h6>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-lg-4 bg-grey">

                                    <div class="p-5">

                                        <h2 class="fw-bold mb-5 mt-2 pt-1 color:red;">Summary</h2>

                                        <hr class="my-4" />


                                        <div class="d-flex justify-content-between mb-4">

                                            <h5 class="text-muted">Items</h5>

                                            <h5>₹90</h5>

                                        </div>


                                        <hr class="my-4" />


                                        <div class="d-flex justify-content-between mb-5">

                                            <h5 class="text-muted">Total</h5>

                                            <h5>₹90</h5>

                                        </div>

                                        <a href="checkout.html">

                                            <button id="fixedButton" class="btn btn-warning btn-block btn-lg butn-fxd hidden-button"><span>Proceed to Pay</span> <span></span></button>

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection