@extends('admin.base_template')

@section('main')
    {{-- @dd($recent) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">
                            @if ($recent != null)
                                Edit
                            @else
                                Add New
                            @endif Recent
                        </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);"> Recents </a></li>

                            <li class="breadcrumb-item active">
                                @if ($recent != null)
                                    Edit
                                @else
                                    Add
                                @endif Recent
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
                                    @if ($recent != null)
                                        Edit
                                    @else
                                        Add
                                    @endif Recent Form
                                </h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('recent.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($recent != null)
                                        <input type="hidden" name="recent_id" value="{{ $recent->id }}">
                                    @endif

                                    <div class="form-group row">

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">
                                                
                                                <select class="form-control" name="product_id" id="product-container">

                                                    <option>----Select Product-----</option>

                                                        @foreach ($products as $product)

                                                            <option
                                                                value="{{ $product->id }}"{{ old('product_id') == $product->id || (isset($recent) && $recent->product_id == $product->id) ? ' selected' : '' }}>
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
                                                    value="{{ $recent ? $recent->recent : old('recent') }}"
                                                    name="recent" placeholder="Enter recent" required>

                                                <label for="recent">Recent &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('recent')
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
