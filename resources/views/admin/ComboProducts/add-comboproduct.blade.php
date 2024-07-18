@extends('admin.base_template')

@section('main')
    {{-- @dd($comboproduct) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">
                            @if ($comboproduct != null)
                                Edit
                            @else
                                Add New
                            @endif Combo Products
                        </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);"> Combo Products</a></li>

                            <li class="breadcrumb-item active">
                                @if ($comboproduct != null)
                                    Edit
                                @else
                                    Add
                                @endif Combo Products
                            </li>

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

                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                                                aria-hidden="true">&times;</span>

                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger" role="alert">

                                        {{ session('error') }}

                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                                                aria-hidden="true">&times;</span>

                                    </div>
                                @endif

                                <!-- End show success and error messages -->

                                <h4 class="mt-0 header-title">
                                    @if ($comboproduct != null)
                                        Edit
                                    @else
                                        Add
                                    @endif Combo Products Form
                                </h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('comboproduct.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($comboproduct != null)
                                        <input type="hidden" name="comboproduct_id" value="{{ $comboproduct->id }}">
                                    @endif

                                    <div class="form-group row">

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">
                                                
                                                <select class="form-control" name="main_product" id="main_product" onchange="getType('{{route('gift-card-1.get-type')}}' , 'main_product', 'main_type_container')">

                                                    <option>----Select product-----</option>

                                                    @foreach ($products as $product)

                                                        <option
                                                            value="{{ $product->id }}"{{ old('main_product') == $product->id || (isset($comboproduct) && $comboproduct->main_product == $product->id) ? ' selected' : '' }}>
                                                            {{ $product->name }}
                                                        </option>

                                                    @endforeach

                                                </select>

                                                <label for="-image3"> Main Product &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('main_product')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">
                                                
                                                <select class="form-control" name="main_type" id="main_type_container">

                                                    <option>---- Choose Type First -----</option>

                                                    @if ($comboproduct != null)

                                                        @foreach ($maintypes as $type)

                                                            <option
                                                                value="{{  $type->id }}"{{ old('main_type') == $type->id || (isset($comboproduct) && $comboproduct->main_type == $type->id) ? ' selected' : '' }}>
                                                                {{ $type->type_name }}
                                                            </option>

                                                        @endforeach

                                                    @endif

                                                </select>

                                                <label for="main_type"> Main Type &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('main_type')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                     
                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">
                                                
                                                <select class="form-control" name="combo_product" id="combo_product" onchange="getType('{{route('gift-card-1.get-type')}}' , 'combo_product', 'combo_type_container')">

                                                    <option>----Select product-----</option>

                                                    @foreach ($products as $product)

                                                        <option
                                                            value="{{ $product->id }}"{{ old('combo_product') == $product->id || (isset($comboproduct) && $comboproduct->combo_product == $product->id) ? ' selected' : '' }}>
                                                            {{ $product->name }}
                                                        </option>

                                                    @endforeach

                                                </select>

                                                <label for="-image3"> Combo Product &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('combo_product')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">
                                                
                                                <select class="form-control" name="combo_type" id="combo_type_container">

                                                    <option>---- Choose Type First -----</option>

                                                    @if ($comboproduct != null)

                                                        @foreach ($combotypes as $type2)

                                                            <option
                                                                value="{{  $type2->id }}"{{ old('combo_type') == $type2->id || (isset($comboproduct) && $comboproduct->combo_type == $type2->id) ? ' selected' : '' }}>
                                                                {{ $type2->type_name }}
                                                            </option>

                                                        @endforeach

                                                    @endif

                                                </select>

                                                <label for="combo_type"> Combo Type &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('combo_type')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>


                                 

                                    </div>

                                    <div class="form-group row">

                                        <div class="form-group">

                                            <div class="w-100 text-center">

                                                <button type="submit" style="margin-top: 10px;" class="btn btn-danger"><i
                                                        class="fa fa-user"></i> Submit</button>

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
