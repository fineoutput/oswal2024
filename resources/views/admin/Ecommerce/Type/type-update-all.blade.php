@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title"> @if($type != null) Update  @else Add New @endif  Type </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Type</a></li>

                            <li class="breadcrumb-item active">@if($type != null) Update @else Add New @endif Type</li>

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
                                
                                <h4 class="mt-0 header-title"> @if($type != null) Edit @else Add @endif Type Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('type.update-all-data') }}" method="post">

                                    @csrf

                                    <input type="hidden" name="product_id"  class="form-control" placeholder="" required value="{{ $p_id }}" />

                                    <input type="hidden" name="category_id"  class="form-control" placeholder="" required value="{{ $c_id }}" />

                                    <input type="hidden" name="product_category_id"  class="form-control" placeholder="" required value="{{ $pc_id }}" />

                                    @if ($type != null) <input type="hidden" name="type_id" value="{{$type->id}}"> @endif

                                    <div class="form-group row">

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <select class="form-control" name="type_name" id="type_name">

                                                    <option >----select Type-----</option>

                                                    @foreach ($type_data1 as $type_name)
                                                    
                                                        <option value="{{ $type_name }}"{{ (old('type_name') == $type_name ) ? ' selected' : '' }}>{{ $type_name }}</option>
                                                        
                                                    @endforeach

                                                </select>

                                                <label for="type_name">Type  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('type_name')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $type ? $type->del_mrp : old('del_mrp') }}" name="del_mrp" placeholder="Enter mrp" >

                                                <label for="del_mrp">MRP &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('del_mrp')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="number" id="gst_percentage" onkeyup="calculatePrices('mrp' ,'gst_percentage','gst_percentage_price' ,'selling_price')" class="form-control" value="{{ $type ? $type->gst_percentage : old('gst_percentage') }}" placeholder="Enter Gst %"  name="gst_percentage" >

                                                <label for="gst_percentage">GST % &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('gst_percentage')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" onkeyup="calculatePrices('mrp' ,'gst_percentage','gst_percentage_price' ,'selling_price')"  value="{{ $type ? $type->mrp : old('mrp')}}" placeholder="Selling Price (without GST)"  id="mrp" name="mrp" required>

                                                <label for="mrp">Selling Price (without GST) &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('mrp')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $type ? $type->gst_percentage_price : old('gst_percentage_price') }}" id="gst_percentage_price" name="gst_percentage_price" placeholder="Description Hindi">

                                                <label for="gst_percentage_price">GST Price &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('gst_percentage_price')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $type ? $type->selling_price : old('selling_price') }}" id="selling_price" name="selling_price" placeholder="Enter selling_price" required>

                                                <label for="selling_price">Selling Price (with GST)</label>

                                            </div>

                                            @error('selling_price')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $type ? $type->weight : old('weight')}}"  id="weight" placeholder="Weight" name="weight">

                                                <label for="weight">Weight &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('weight')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $type ? $type->rate : old('rate')}}" id="rate" name="rate" placeholder="Rate" required>

                                                <label for="rate">Rate  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('rate')

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

