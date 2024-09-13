@extends('admin.base_template')

@section('main')
    {{-- @dd($slider) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">
                            @if ($slider != null)
                                Edit
                            @else
                                Add New
                            @endif Slider
                        </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);"> sliders </a></li>

                            <li class="breadcrumb-item active">
                                @if ($slider != null)
                                    Edit
                                @else
                                    Add
                                @endif Slider
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
                                    @if ($slider != null)
                                        Edit
                                    @else
                                        Add
                                    @endif slider Form
                                </h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('slider.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($slider != null)
                                        <input type="hidden" name="slider_id" value="{{ $slider->id }}">
                                    @endif

                                    <div class="form-group row">

                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">

                                                <select class="form-control" name="category_id" id="category_id" onchange="getProduct('{{ route('slider.get-product') }}')">

                                                    <option>----select Category-----</option>

                                                    @foreach ($categories as $categorie)

                                                        <option value="{{ $categorie->id }}"{{ old('category_id') == $categorie->id || (isset($slider) && $slider->category_id == $categorie->id) ? ' selected' : '' }}>{{ $categorie->name }}</option>

                                                    @endforeach

                                                </select>

                                                <label for="-image3">Category &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('category_id')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>


                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">
                                                
                                                <select class="form-control" name="product_id" id="product-container">

                                                    <option>----No product-----</option>

                                                    @if ($slider != null)

                                                        @foreach ($products as $product)

                                                            <option
                                                                value="{{ $product->id }}"{{ old('product_id') == $product->id || (isset($slider) && $slider->product_id == $product->id) ? ' selected' : '' }}>
                                                                {{ $product->name }}
                                                            </option>

                                                        @endforeach

                                                    @endif

                                                </select>

                                                <label for="-image3">Product &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('product_id')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>


                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control"
                                                    value="{{ $slider ? $slider->slider_name : old('slider_name') }}"
                                                    name="slider_name" placeholder="Enter slider_name" required>

                                                <label for="slider_name">Slider Name &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('slider_name')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">

                                                <input class="form-control" type="file" id="img" name="img"
                                                    placeholder="img">

                                                @if ($slider != null)
                                                    <img src="{{ asset($slider->image) }}" width="100px" height="100px">
                                                @endif

                                                <label class="mb-2" for="img">Image </label>

                                            </div>

                                            @error('img')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control"
                                                    value="{{ $slider ? $slider->app_slider_name : old('app_slider_name') }}"
                                                    name="app_slider_name" placeholder="Enter slider_name" required>

                                                <label for="app_slider_name">App Slider Name &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('app_slider_name')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">

                                                <input class="form-control" type="file" id="img2" name="img2"
                                                    placeholder="img2">

                                                @if ($slider != null)
                                                    <img src="{{ asset($slider->app_image) }}" width="100px" height="100px">
                                                @endif

                                                <label class="mb-2" for="img">App Image </label>

                                            </div>

                                            @error('img2')
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
