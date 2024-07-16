@extends('admin.base_template')

@section('main')

<style>
    .select2-container{
        margin-top: 20px !important;
    }
</style>

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title"> = Add New City </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">City</a></li>

                            <li class="breadcrumb-item active"> Add City</li>

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
                                
                                <h4 class="mt-0 header-title"> Add New City Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('shipping-charge.store-city') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    <div class="form-group row ">

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <select class="form-control select2  p-0 pt-2" name="state_id" id="state">

                                                    <option >----select State-----</option>

                                                    @foreach ($states as $state)
                                                    
                                                        <option value="{{ $state->id }}"{{ (old('state_id') == $state->id || (isset($shippind_charge) && $shippind_charge->category_id == $state->id)) ? ' selected' : '' }}>{{ $state->state_name }}</option>
                                                        
                                                    @endforeach

                                                </select>

                                                <label class="p-0" for="state">State  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('state_id')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ old('City_name') }}" name="City_name" placeholder="City_name">

                                                <label for="City_name">City </label>

                                            </div>

                                            @error('City_name')

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
