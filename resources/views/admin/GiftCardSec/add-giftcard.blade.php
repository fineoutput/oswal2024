@extends('admin.base_template')

@section('main')
    {{-- @dd($giftcard) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">
                            @if ($giftcard != null)
                                Edit
                            @else
                                Add New
                            @endif Gift Card 1
                        </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);"> Gift Card 1</a></li>

                            <li class="breadcrumb-item active">
                                @if ($giftcard != null)
                                    Edit
                                @else
                                    Add
                                @endif Gift Card 1
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
                                    @if ($giftcard != null)
                                        Edit
                                    @else
                                        Add
                                    @endif Gift Card Form
                                </h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('gift-card-1.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($giftcard != null)
                                        <input type="hidden" name="giftcard_id" value="{{ $giftcard->id }}">
                                    @endif

                                    <div class="form-group row">

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">
                                                
                                                <select class="form-control" name="product_id" id="product_id" onchange="getType('{{route('gift-card-1.get-type')}}' , 'product_id', 'type_container')">

                                                    <option>----Select product-----</option>

                                                    @foreach ($products as $product)

                                                        <option
                                                            value="{{ $product->id }}"{{ old('product_id') == $product->id || (isset($giftcard) && $giftcard->product_id == $product->id) ? ' selected' : '' }}>
                                                            {{ $product->name }}
                                                        </option>

                                                    @endforeach

                                                </select>

                                                <label for="-image3">Product &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('product_id')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">
                                                
                                                <select class="form-control" name="type_id" id="type_container">

                                                    <option>---- Choose Type First -----</option>

                                                    @if ($giftcard != null)

                                                        @foreach ($types as $type)

                                                            <option
                                                                value="{{  $type->id }}"{{ old('type_id') == $type->id || (isset($giftcard) && $giftcard->type_id == $type->id) ? ' selected' : '' }}>
                                                                {{ $type->type_name }}
                                                            </option>

                                                        @endforeach

                                                    @endif

                                                </select>

                                                <label for="type_id"> Type &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('type_id')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control"
                                                    value="{{ $giftcard ? $giftcard->price : old('price') }}"
                                                    name="price" placeholder="Enter price" required>

                                                <label for="price"> Minimum order price  &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('price')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input class="form-control" type="file" id="img" name="img"
                                                    placeholder="img">

                                                @if ($giftcard != null)
                                                    <img src="{{ asset($giftcard->image) }}" width="100px" height="100px">
                                                @endif

                                                <label class="mb-2" for="img">Image </label>

                                            </div>

                                            @error('img')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input class="form-control" type="file" id="img" name="appimg"
                                                    placeholder="img">

                                                @if ($giftcard != null)
                                                    <img src="{{ asset($giftcard->appimage) }}" width="100px" height="100px">
                                                @endif

                                                <label class="mb-2" for="img">App Image </label>

                                            </div>

                                            @error('img')
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
