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
                            @endif Gift Card
                        </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);"> Gift Card </a></li>

                            <li class="breadcrumb-item active">
                                @if ($giftcard != null)
                                    Edit
                                @else
                                    Add
                                @endif Gift Card
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

                                <form action="{{ route('gift-card.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($giftcard != null)
                                        <input type="hidden" name="giftcard_id" value="{{ $giftcard->id }}">
                                    @endif

                                    <div class="form-group row">

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control"
                                                    value="{{ $giftcard ? $giftcard->name : old('name') }}"
                                                    name="name" placeholder="Enter name" required>

                                                <label for="name">Name &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('name')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control"
                                                    value="{{ $giftcard ? $giftcard->description : old('description') }}"
                                                    name="description" placeholder="Enter description" required>

                                                <label for="description"> Description &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('description')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control"
                                                    value="{{ $giftcard ? $giftcard->price : old('price') }}"
                                                    name="price" placeholder="Enter price" required>

                                                <label for="price"> Price &nbsp;<span
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
