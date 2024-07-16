@extends('admin.base_template')

@section('main')
{{-- @dd($retailShop) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title"> @if($retailShop != null) Edit @else Add @endif Shop </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Category</a></li>

                            <li class="breadcrumb-item active">@if($retailShop != null) Edit @else Add @endif Shop</li>

                        </ol>

                    </div>

                </div>

            </div>

            <!-- end row -->
            <div class="page-content-wrapper">

                <div class="row">

                    <div class="col-12">

                        <div class="card m-b-20">

                            <div class="card-body">

                                <!-- show success and error messages -->

                                @if (session('success'))

                                    <div class="alert alert-success" role="alert">

                                        {{ session('success') }}

                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span>

                                    </div>

                                @endif

                                @if (session('error'))

                                    <div class="alert alert-danger" role="alert">

                                        {{ session('error') }}

                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span>

                                    </div>

                                @endif

                                <!-- End show success and error messages -->
                                
                                <h4 class="mt-0 header-title"> @if($retailShop != null) Edit @else Add @endif Shop Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('shop.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($retailShop != null) <input type="hidden" name="shop_id" value="{{$retailShop->id}}"> @endif
                                    
                                    <div class="form-group row">

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $retailShop ? $retailShop->shop_name : old('shop_name') }}" name="shop_name" placeholder="Enter shop_name" required>

                                                <label for="shop_name">Shop Name &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('shop_name')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $retailShop ? $retailShop->person_name : old('person_name') }}" name="person_name" placeholder="Enter person_name" required>

                                                <label for="person_name">Person name &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('person_name')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $retailShop ? $retailShop->address : old('address') }}" name="address" placeholder="Enter address" required>

                                                <label for="address">Address &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('address')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $retailShop ? $retailShop->area : old('area') }}" name="area" placeholder="Enter area" required>

                                                <label for="area">Area &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('area')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $retailShop ? $retailShop->state : old('state') }}" name="state" placeholder="Enter state" required>

                                                <label for="state">State &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('state')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $retailShop ? $retailShop->city : old('city') }}" name="city" placeholder="Enter city" required>

                                                <label for="city">City &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('city')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $retailShop ? $retailShop->pincode : old('pincode') }}" id="pincode" name="pincode" placeholder="pincode">

                                                <label for="pincode">Pincode  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('pincode')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $retailShop ? $retailShop->phone1 : old('phone1') }}" id="phone1" name="phone1" placeholder="phone1">

                                                <label for="phone1">Phone Number 1 &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('phone1')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>
                                        
                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $retailShop ? $retailShop->phone2 : old('phone2') }}" id="phone2" name="phone2" placeholder="phone2">

                                                <label for="phone2">Phone Number 2  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('phone2')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $retailShop ? $retailShop->map : old('map') }}" id="map" name="map" placeholder="map">

                                                <label for="map">Map Location &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('map')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                    </div>

                                    <div class="form-group row">
                                     
                                        <div class="form-group">

                                            <div class="w-100 text-center">

                                                <button type="submit" style="margin-top: 10px;" class="btn btn-danger"><i class="fa fa-user"></i> Submit</button>

                                            </div>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div> <!-- end col -->

                </div> <!-- end row -->

            </div>

        </div> <!-- container-fluid -->

    </div> <!-- content -->

@endsection
