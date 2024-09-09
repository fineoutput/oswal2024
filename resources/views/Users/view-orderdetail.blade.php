@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

<section class="h-100 gradient-custom">
    
    <div class="container py-5 h-100">
        
        <div class="row d-flex justify-content-center align-items-center h-100">
            
            <div class="col-lg-10 col-xl-8">
                
                <div class="card" style="border-radius: 10px;color:black;background-color:none">
                    
                    <div class="card-header px-4 py-5">
                        
                        <h5 class="mb-0">
                            
                            Thanks for your Order, <b><span style="color: #000;">{{ Auth::user()->first_name }}</span>!</b>
                            
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
                                    
                                    <p class="small mb-0">Order ID: #{{ $data['order_id'] }}</p>
                                    
                                    <p>Date: {{ date('Y-m-d' , strtotime($data['order_datetime']))}}</p>
                                    
                                </div>
                                
                                @foreach ($data['product'] as $product)

                                <div class="row">
                                        
                                    <div class="col-md-2">
                                        
                                        <img src="{{ $product['product_image'] }}" class="img-fluid" alt="Phone" />
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0">{{ $product['product_name'] }}</p>
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0 small">{{ $product['category_name'] }}</p>
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0 small">{{ $product['type_name'] }}</p>
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0 small">Qty: {{ $product['quantity'] }}</p>
                                        
                                    </div>
                                    
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        
                                        <p class="mb-0 small">{{ formatPrice($product['quantity_price']) }}</p>
                                        
                                    </div>

                                </div>

                                <hr class="mb-4" style="background-color: #e0e0e0; opacity: 1;" />

                                @endforeach
                                
                                
                            </div>
                            
                            {{-- <div class="card-body">
                                
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
                                
                            </div> --}}
                            
                        </div>
                        

                        {{-- <div class="card shadow-0 border mb-4">

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

                        </div> --}}

                    </div>
             
                </div>

            </div>

        </div>

    </div>

</section>

@endsection