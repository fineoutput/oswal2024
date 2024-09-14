@extends('admin.base_template')

@section('main')
{{-- @dd($constant) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View Constant </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">constant</a></li>

                            <li class="breadcrumb-item active"> Edit Constant</li>

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
                                
                                <h4 class="mt-0 header-title">Constant Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('setting.constant') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    <input type="hidden" name="constant_id" value="{{$constant->id}}">
                                    
                                    <div class="form-group row">

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $constant ? $constant->cod_charge : old('cod_charge') }}" name="cod_charge" placeholder="Enter cod charge" required>

                                                <label for="name">COD Charges &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('cod_charge')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $constant ? $constant->wallet_use_amount : old('wallet_use_amount') }}" id="wallet_use_amount" name="wallet_use_amount" placeholder="wallet_use_amount">

                                                <label for="wallet_use_amount">Wallet Deduct Amount (in percent) &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('wallet_use_amount')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>
                                        
                                        
                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $constant ? $constant->gift_min_amount : old('gift_min_amount') }}" id="gift_min_amount" name="gift_min_amount" placeholder="gift_min_amount">

                                                <label for="gift_min_amount">Gift Amount &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('gift_min_amount')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $constant ? $constant->cod_max_process_amount : old('cod_max_process_amount') }}" id="cod_max_process_amount" name="cod_max_process_amount" placeholder="cod_max_process_amount">

                                                <label for="cod_max_process_amount">COD Max. Process Amount &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('cod_max_process_amount')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $constant ? $constant->quantity : old('quantity') }}" id="quantity" name="quantity" placeholder="quantity">

                                                <label for="quantity">Quantity Limit &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('quantity')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $constant ? $constant->referrer_amount : old('referrer_amount') }}" id="referrer_amount" name="referrer_amount" placeholder="referrer_amount">

                                                <label for="referrer_amount">Referrer Amount &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('referrer_amount')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $constant ? $constant->referee_amount : old('referee_amount') }}" id="referee_amount" name="referee_amount" placeholder="referee_amount">

                                                <label for="referee_amount">Referee Amount &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('referee_amount')

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
