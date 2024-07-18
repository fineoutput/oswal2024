@extends('admin.base_template')

@section('main')
    {{-- @dd($trending ,) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">
                            @if ($trending != null)
                                Edit
                            @else
                                Add New
                            @endif Trending
                        </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);"> Trendings </a></li>

                            <li class="breadcrumb-item active">
                                @if ($trending != null)
                                    Edit
                                @else
                                    Add
                                @endif Trending
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
                                    @if ($trending != null)
                                        Edit
                                    @else
                                        Add
                                    @endif Trending Form
                                </h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ $tranding_url }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($trending != null)
                                        <input type="hidden" name="trending_id" value="{{ $trending->id }}">
                                    @endif

                                    <div class="form-group row">

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">
                                                
                                                <select class="form-control" name="product_id" id="product-container">

                                                    <option>----Select Product-----</option>

                                                        @foreach ($products as $product)

                                                            <option
                                                                value="{{ $product->id }}"{{ old('product_id') == $product->id || (isset($trending) && $trending->product_id == $product->id) ? ' selected' : '' }}>
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

                                                <input type="text" class="form-control"
                                                    value="{{ $trending ? $trending->trending : old('trending') }}"
                                                    name="trending" placeholder="Enter trending" required>

                                                <label for="trending">Trending &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('trending')
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
