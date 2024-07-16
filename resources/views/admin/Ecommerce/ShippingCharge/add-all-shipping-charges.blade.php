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

                        <h4 class="page-title"> Add New Shipping_charge </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Shipping_charge</a></li>

                            <li class="breadcrumb-item active"> Add New Shipping_charge</li>

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
                                
                                <h4 class="mt-0 header-title">Add Shipping_charge Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('shipping-charge.shipping-charges-store') }}" method="post" enctype="multipart/form-data">

                                    @csrf
                                    
                                    <div class="form-group row">

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <select class="form-control select2  p-0 pt-2" name="state_id" id="state">

                                                    <option value=" " >----select State-----</option>

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

                                    </div>

                                    <div class="form-group row ">

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <select class="form-control p-0 pt-2" name="weight1">

                                                    <option >----select Option-----</option>

                                                    @foreach ($weghit1 as $val1)
                                                    
                                                        <option value="{{ $val1 }}"{{ (old('weight1') == $val1) ? ' selected' : '' }}>{{ $val1 }}</option>
                                                        
                                                    @endforeach

                                                </select>
                                                
                                                <label for="weight1">Weight 1 </label>

                                            </div>

                                            @error('weight1')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{old('shipping_charge1')}}" id="shipping_charge1" name="shipping_charge1" placeholder="shipping_charge1" required>

                                                <label for="shipping_charge1">Shipping_charge 1 &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('shipping_charge1')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <select class="form-control p-0 pt-2" name="weight2">

                                                    <option >----select Option-----</option>

                                                    @foreach ($weghit2 as $val2)
                                                    
                                                        <option value="{{ $val2 }}"{{ (old('weight2') == $val2) ? ' selected' : '' }}>{{ $val2 }}</option>
                                                        
                                                    @endforeach

                                                </select>

                                                <label for="weight2"> Weight 2</label>

                                            </div>

                                            @error('weight2')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{old('shipping_charge2')}}" id="shipping_charge2" name="shipping_charge2" placeholder="Enter shipping_charge2" required>

                                                <label for="shipping_charge2">Shipping_charge 2 </label>

                                            </div>

                                            @error('shipping_charge2')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <select class="form-control p-0 pt-2" name="weight3">

                                                    <option >----select Option-----</option>

                                                    @foreach ($weghit3 as $val3)
                                                    
                                                        <option value="{{ $val3 }}"{{ (old('weight3') == $val3) ? ' selected' : '' }}>{{ $val3 }}</option>
                                                        
                                                    @endforeach

                                                </select>

                                                <label for="weight3">Weight 3 </label>

                                            </div>

                                            @error('weight3')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{old('shipping_charge3')}}" id="shipping_charge3" name="shipping_charge3" placeholder="shipping_charge3" required>

                                                <label for="shipping_charge3">Shipping_charge 3 &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('shipping_charge3')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <select class="form-control p-0 pt-2" name="weight4">

                                                    <option >----select Option-----</option>

                                                    @foreach ($weight4 as $val4)
                                                    
                                                        <option value="{{ $val4 }}"{{ (old('weight4') == $val4) ? ' selected' : '' }}>{{ $val4 }}</option>
                                                        
                                                    @endforeach

                                                </select>

                                                <label for="weight4"> Weight 4</label>

                                            </div>

                                            @error('weight4')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{old('shipping_charge4')}}" id="shipping_charge4" name="shipping_charge4" placeholder="Enter shipping_charge4" required>

                                                <label for="shipping_charge4">Shipping_charge 4 </label>

                                            </div>

                                            @error('shipping_charge4')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <select class="form-control p-0 pt-2" name="weight5">

                                                    <option >----select Option-----</option>

                                                    @foreach ($weight5 as $val5)
                                                    
                                                        <option value="{{ $val5 }}"{{ (old('weight5') == $val5) ? ' selected' : '' }}>{{ $val5 }}</option>
                                                        
                                                    @endforeach

                                                </select>

                                                <label for="weight5">Weight 5 </label>

                                            </div>

                                            @error('weight5')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ old('shipping_charge5')}}" id="shipping_charge5" name="shipping_charge5" placeholder="shipping_charge5" required>

                                                <label for="shipping_charge5">Shipping_charge 5 &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('shipping_charge5')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <select class="form-control p-0 pt-4" name="weight6">

                                                    <option >----select Option-----</option>

                                                    @foreach ($weight6 as $val6)
                                                    
                                                        <option value="{{ $val6 }}"{{ (old('weight6') == $val6) ? ' selected' : '' }}>{{ $val6 }}</option>
                                                        
                                                    @endforeach

                                                </select>

                                                <label for="weight6"> Weight 6</label>

                                            </div>

                                            @error('weight6')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ old('shipping_charge6')}}" id="shipping_charge6" name="shipping_charge6" placeholder="Enter shipping_charge6" required>

                                                <label for="shipping_charge6">Shipping_charge 6 </label>

                                            </div>

                                            @error('shipping_charge6')

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
