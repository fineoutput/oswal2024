@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
<style>
    .hunn_list_itms {
    display: flex;
    justify-content: space-between;
    padding: 10px;
}
.hunn_icons {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.hunn_icons p {
    margin: 0;
}
.hunn_total{
    border-top: 1px dashed black ;
}
</style>
<section class="h-100 gradient-custom">
    
    <div class="container py-5 h-100">
        
        <div class="row d-flex justify-content-center align-items-center h-100">
            
            <div class="col-lg-10 col-xl-8">
                
                <div class="card" style="border-radius: 10px;color:black;background:none">
                    
                    <div class="card-header px-4 py-5" style="color:black;background:none">
                        
                        <h5 class="mb-0">
                            
                            Thanks for your Order, <b><span style="color: #000;">{{ Auth::user()->first_name }}</span>!</b>
                            
                        </h5>
                        
                    </div>
                    <div class="card-body p-4" style="color:black;background:none">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            
                            <p class="lead fw-normal mb-0" style="color: #ffc107;">Receipt</p>
                            
                            <p class="small mb-0">Receipt Voucher : 1KAU9-84UIL</p>
                            
                        </div>
                        
                        <div class="card shadow-0 border mb-4" style="color:black;background:none">
                            
                            <div class="card-body" style="color:black;background:none">
                                
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
                            
                            <div class="hun_details">
                                <h2>Order Details</h2>
                                <div class="hun_list">
                                    <div class="hunn_price_total hunn_list_itms">
                                        <h4> Item Total</h4>
                                        <p>{{$data['subtotal'] }}</p>
                                    </div>
                                    
                                    <div class="hunn_list_itms">
                                        <div class="hunn_icons">
                                            <img src="{{(asset('images/wallet (1).png'))}}" width="20%" alt="">
                                        <p>Wallet Discount</p>
                                        </div>
                                        <p>{{$data['wallet_discount'] }}</p>
                                    </div>

                                    <div class="hunn_list_itms">
                                        <div class="hunn_icons">
                                            <img src="{{(asset('images/coupons.png'))}}" width="20%" alt="">
                                        <p>Promo Discount</p>
                                        </div>
                                        <p>{{$data['promo_discount'] }}</p>
                                    </div>

                                    <div class="hunn_list_itms">
                                    <div class="hunn_icons">
                                    <img src="{{(asset('images/gift-card.png'))}}" width="20%" alt="">
                                        <p>Gift Card amount</p>
                                    </div>
                                        <p>{{$data['gift_amount']}}</p>
                                    </div>
                                    <div class="hunn_list_itms">
                                    <div class="hunn_icons">
                                    <img src="{{(asset('images/delivery.png'))}}" width="20%" alt="">
                                        <p>Shipping Charges</p>
                                        </div>
                                        <p>{{$data['delivery_charge']}}</p>
                                    </div>
                                    <div class="hunn_list_itms">
                                    <div class="hunn_icons">
                                    <img src="{{(asset('images/buy.png'))}}" width="20%" alt="">
                                        <p>COD Charges</p>
                                    </div>
                                        <p>{{$data['cod_charge']}}</p>
                                    </div>
                                    <div class="hunn_total hunn_list_itms">
                                        <h4>Bill Total</h4>
                                        <p>{{$data['total_amount'] }}</p>
                                    </div>
                                    <hr>
                                    {{$data['address']}}
                                </div>
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