@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

<section class="mt-5">

    <div class="container-fluid pl-5 pr-5 pt-3 pb-5">

        <div class="row register_row">

            <div class="col-md-6">

                @if(session()->has('address_id') && $place == null)

                <form action="{{ route('checkout.process') }}" method="get" id="autoSubmitForm">
                    @csrf
                    <input type="hidden" name="address_id" value="{{ session()->get('address_id') }}" />
                </form>

                <script>
                    window.onload = function() {
                        document.getElementById('autoSubmitForm').submit();
                    };
                </script>


                @else

                <form action="{{ route('checkout.process') }}" method="get">

                    @csrf

                    <div class="row">

                        <div class="col-md-12 text-center stkey" style="justify-content: space-around; z-index: 0;">

                            <div>

                                <h2>Select Address</h2>

                            </div>

                            <div class="mt-3 mb-2 sxcds" style="margin-left: 37px;">

                                <button type="submit" class="animated-button">

                                    <span>Continue</span> <span></span>

                                </button>

                            </div>

                        </div>

                        @forelse ($address_data as $address)

                        <div class="col-md-12">

                            <div class="bottom-11 p-3 over">

                                <div class="row add_sel" onclick="selectAddress('{{ $address->id }}')">

                                    <div class="col-1 col-md-1 p-0" style="text-align: end;">
                                        <input type="radio" id="address_{{ $address->id }}" name="address_id" value="{{ $address->id }}" required />
                                    </div>

                                    <div class="col-10 col-md-11">
                                        <p class="bottom-m"><b>Name:</b> <a>{{ $address->name }}</a></p>
                                        <p class="bottom-m"><b>Address:</b> <a>{{ $address->custom_address }}</a></p>
                                        <p class="bottom-m"><b>State:</b> <a>{{ $address->states->state_name }}</a></p>
                                        <p class="bottom-m"><b>City:</b> <a>{{ $address->citys->city_name }}</a></p>
                                        <p class="bottom-m"><b>Landmark:</b> <a>{{ $address->landmark }}</a></p>
                                        <p class="bottom-m"><b>Pincode:</b> <a>{{ $address->zipcode }}</a></p>
                                    </div>

                                </div>

                                <div style="display: flex; justify-content: end;">

                                    <a href="{{ route('user.add-address',['redirect' => 'checkout', 'id' => base64_encode($address->id)]) }}" class="mr-2">

                                        <button type="button" class="animated-button">

                                            <span><i class="fa-solid fa-pencil"></i></span>

                                            <span></span>

                                        </button>

                                    </a>

                                </div>

                            </div>

                        </div>

                        @empty
                        <div class="w-100 text-center mt-5">

                            <h5 class="text-center" style="color: #ff324d;">Please add an address for checkout</h5>

                        </div>
                        @endforelse


                    </div>

                </form>

                @endif


            </div>

            <div class="col-md-6">

                <div style="position: sticky; top: 120px;">

                    {{-- <div class="row">

                            <div class="col-md-12 text-center"

                                style="margin: 16px 0px 9px 0px !important ; border-bottom: 1px solid #dbdbdb;">

                                <h2 style="margin-bottom: 22px;">Add New Address</h2>

                            </div>

                        </div> --}}

                    <div class="row">
                        <div class="col-sm-8 col-12 mt-2">

                            <button class="btn btn-fill-out btn-block col-sm-8 mb-3" disabled

                                style="display: none;"><i

                                    class="fa fa-spinner fa-spin"></i>Loading</button>

                            <a href="{{ route('user.add-address', ['redirect' => 'checkout']) }}">
                                <button class="animated-button">
                                    <span>Add New Address</span>
                                    <span></span>
                                </button>
                            </a>
                        </div>
                        {{-- <div class="col-md-12">
                                
                                <form method="POST" action="CHANGE_TO_YOUR_FORM_ACTION" enctype="multipart/form-data">
                                    
                                    <div class="row">
                                        
                                        <div class="form-group col-lg-6">
                                            
                                            <input type="text" required class="form-control" id="fname"
                                            
                                                name="fname" placeholder="First name *" />
                                                
                                        </div>
                                        
                                        <div class="form-group col-lg-6">
                                            
                                            <input type="text" required class="form-control" id="lname"
                                            
                                                name="lname" placeholder="Last name *" />
                                                
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="form-group">
                                        
                                        <input class="form-control" type="email" id="email" name="email"
                                        
                                            placeholder="Email Address " />
                                            
                                    </div>
                                    
                                    <div class="row">
                                        
                                        <div class="form-group col-lg-6">
                                            
                                            <input class="form-control" maxlength="10" minlength="10" required
                                            
                                                type="text" id="phonenumber" name="phonenumber"
                                                
                                                placeholder="Phone Number *" />
                                                
                                        </div>
                                        
                                        <div class="form-group col-lg-6">
                                            
                                            <input class="form-control" maxlength="6" minlength="6" required
                                            
                                                type="text" id="pincode" name="pincode" placeholder="Pincode *" />
                                                
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="row">
                                        
                                        <div class="form-group col-lg-6">
                                            
                                            <div class="custom_select">
                                                
                                                <select class="form-control" id="state" name="state" required>
                                                    
                                                    <option value="">---- Select State ----</option>
                                                    
                                                    <!-- State Options Placeholder -->
                                                    
                                                    <option value="STATE_NAME">STATE_NAME</option>
                                                    
                                                </select>
                                                
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="form-group col-lg-6">
                                            
                                            <input class="form-control" id="city" required type="text"
                                            
                                                name="city" placeholder="City *" />
                                                
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="form-group">
                                        
                                        <input class="form-control" required type="text" id="address"
                                        
                                            name="address" placeholder="Address *" />
                                            
                                    </div>
                                    

                                    
                                    <div class="row detailborder">
                                        
                                        <div class="col-sm-8 col-12 mt-2">
                                            
                                            <button class="btn btn-fill-out btn-block col-sm-8 mb-3" disabled
                                            
                                                style="display: none;"><i
                                                
                                                    class="fa fa-spinner fa-spin"></i>Loading</button>
                                                    
                                            <button class="animated-button"><span>Add New
                                                
                                                    Address</span><span></span></button>
                                                    
                                        </div>
                                        
                                    </div>
                                    
                                </form>
                                
                            </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>  

@endsection
<script>
    function selectAddress(addressId) {
        const radioInput = document.getElementById(`address_${addressId}`);
        if (radioInput) {
            radioInput.checked = true;
        }
    }
</script>