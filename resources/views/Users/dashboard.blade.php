@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
    
<div class="bottom-bar d-lg-none">

    <div class="bottom-bar-item">

        <a href="index.html"><i class="fa-solid fa-home"></i></a>

    </div>

    <div class="form" id="search2">

        <input type="text" class="input2" placeholder="Search Here" />

    </div>

    <button style="border: none; background: none;" class="search-btn2">

        <i style="font-size: 20px; color: red;" class="fa fa-search" id="naming" aria-hidden="true"></i>

    </button>

    <div class="bottom-bar-item">

        <a href="wishlist_page.html"><i class="fa-solid fa-heart"></i></a>

    </div>

    <div class="bottom-bar-item">

        <a href="cart.html"><i class="fa-solid fa-shopping-cart"></i></a>

    </div>

    <div class="bottom-bar-item">

        <a href="myorder_detail.html"><i class="fa-solid fa-user"></i></a>

    </div>

</div>

<!-- ///////HEADER TOP ENDS////////// -->
<section class="h-100 gradient-custom">
    
    <div class="container py-5 h-100">
        
        <div class="row d-flex justify-content-center align-items-center h-100">
            
            <div class="col-lg-10 col-xl-8">
                
                <div class="card" style="border-radius: 10px;">
                    
                    <div class="card-header px-4 py-5">
                        
                        <h5 class="mb-0">
                            
                            Thanks for your Order, <b><span style="color: #000;">User</span>!</b>
                            
                        </h5>
                        
                    </div>
                    <div class="card-body p-4">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            
                            <p class="lead fw-normal mb-0" style="color: #ffc107;">Receipt</p>
                            
                            <p class="small mb-0">Receipt Voucher : 1KAU9-84UIL</p>
                            
                        </div>
                        
                        <div class="card shadow-0 border mb-4">
                            
                            <div class="card-body">
                                
                                <div class="order_id d-flex justify-content-between">
                                    
                                    <p class="small mb-0">Order ID: #11442</p>
                                    
                                    <p>Date: 25-07-2024</p>
                                    
                                </div>
                                
                                <div class="row">
                                    
                                    <div class="col-md-2">
                                        
                                        <img src="images/mirch.jpg" class="img-fluid" alt="Phone" />
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0">Oswal Mirch</p>
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0 small">oswal Masale</p>
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0 small">250gm</p>
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0 small">Qty: 3</p>
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0 small">₹55</p>
                                        
                                    </div>
                                    
                                </div>
                                
                                <hr class="mb-4" style="background-color: #e0e0e0; opacity: 1;" />
                                
                            </div>
                            
                            <div class="card-body">
                                
                                <div class="row">
                                    
                                    <div class="col-md-2">
                                        
                                        <img src="images/dhaniya.jpg" class="img-fluid" alt="Phone" />
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0">Dhaniya Powder</p>
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0 small">Oswal Masale</p>
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0 small">500gm</p>
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0 small">Qty: 2</p>
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0 small">₹50</p>
                                        
                                    </div>
                                    
                                </div>
                                
                                <hr class="mb-4" style="background-color: #e0e0e0; opacity: 1;" />
                                
                            </div>
                            
                        </div>
                        

                        <div class="card shadow-0 border mb-4">

                            <div class="card-body">

                                <div class="order_id d-flex justify-content-between">

                                    <p class="small mb-0">Order ID: #20442</p>

                                    <p>Date: 25-07-2024</p>

                                </div>

                                <div class="row">

                                    <div class="col-md-2">

                                        <img src="images/dhaniya.jpg" class="img-fluid" alt="Phone" />

                                    </div>

                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">

                                        <p class="mb-0">Dhaniya Powder</p>

                                    </div>

                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">

                                        <p class="mb-0 small">Oswal Masale</p>

                                    </div>

                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">

                                        <p class="mb-0 small">500gm</p>

                                    </div>

                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">

                                        <p class="mb-0 small">Qty: 2</p>

                                    </div>

                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">

                                        <p class="mb-0 small">₹50</p>

                                    </div>

                                </div>

                                <hr class="mb-4" style="background-color: #e0e0e0; opacity: 1;" />

                            </div>

                        </div>

                    </div>
             
                </div>

            </div>

        </div>

    </div>

</section>

@endsection
