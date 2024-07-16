@extends('admin.base_template')

@section('main')
{{-- @dd($promocode) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title"> @if($promocode != null) Edit @else Add New @endif  Promocode </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);"> Promocode</a></li>

                            <li class="breadcrumb-item active">@if($promocode != null) Edit @else Add @endif  Promocode</li>

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
                                
                                <h4 class="mt-0 header-title"> @if($promocode != null) Edit @else Add @endif Promocode Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('promocode.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($promocode != null) <input type="hidden" name="promocode_id" value="{{$promocode->id}}"> @endif
                                    
                                    <div class="form-group row">

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $promocode ? $promocode->promocode : old('promocode') }}" name="promocode" placeholder="Enter promocode" required>

                                                <label class="mb-2" for="promocode"> Promocode  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('promocode')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $promocode ? $promocode->percent : old('percent') }}" name="percent" placeholder="Enter percent" required>

                                                <label class="mb-2" for="percent">Percent &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('percent')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <select class="form-control" name="type" id="type">

                                                    <option >----select Type-----</option>
                                                    
                                                        <option value="1" {{ (old('type') == 1 || (isset($promocode) && $promocode->type == 1 )) ? ' selected' : '' }}>One Time </option>
                                                        <option value="2" {{ (old('type') == 2 || (isset($promocode) && $promocode->type == 2 )) ? ' selected' : '' }}>Every Time</option>

                                                </select>

                                                <label for="type"> Type &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('tuype')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" value="{{ $promocode ? $promocode->minimum_amount : old('minimum_amount') }}" type="text" id="minimum_amount" name="minimum_amount" placeholder="minimum_amount">
                                                 
                                                <label class="mb-2" for="minimum_amount">Minimum Amount  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('minimum_amount')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" value="{{ $promocode ? $promocode->maximum_gift_amount : old('maximum_gift_amount') }}" type="text" id="maximum_gift_amount" name="maximum_gift_amount" placeholder="maximum_gift_amount">
                                                 
                                                <label class="mb-2" for="maximum_gift_amount">Maximum Gift Amount  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('maximum_gift_amount')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="checkbox" class="form-check-input " value="1" id="for_admin" name="for_admin"

                                                    {{ (old('for_admin') == 1 || (isset($promocode) && $promocode->for_admin == 1)) ? 'checked' : '' }}>

                                                <label class="form-check-label mb-2" for="for_admin">For Admin only <span style="color:red;">*</span></label>

                                            </div>

                                            @error('for_admin')
 
                                               <div style="color:red">{{ $message }}</div>
 
                                            @enderror

                                        </div>
                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="{{ isset($promocode) ? $promocode->expiry_date : old('expiry_date') }}">

                                                <label class="form-label" for="expiry_date">Expiry Date<span style="color:red;">*</span></label>

                                            </div>

                                            @error('expiry_date')

                                                <div style="color:red;">{{ $message }}</div>

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
