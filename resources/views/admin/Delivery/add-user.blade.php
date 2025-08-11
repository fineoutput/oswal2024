@extends('admin.base_template')

@section('main')
    {{-- @dd($user) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">
                            @if ($user != null)
                                Edit
                            @else
                                Add New
                            @endif Delivery User
                        </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);"> Delivery User </a></li>

                            <li class="breadcrumb-item active">
                                @if ($user != null)
                                    Edit
                                @else
                                    Add
                                @endif Delivery User
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
                                    @if ($user != null)
                                        Edit
                                    @else
                                        Add
                                    @endif Delivery User Form
                                </h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('delivery.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($user != null)
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    @endif

                                    <div class="form-group row">

                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control"
                                                    value="{{ $user ? $user->name : old('name') }}"
                                                    name="name" placeholder="Enter name" required>

                                                <label for="name">Name &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('name')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">

                                                <select class="form-control" name="store_id" id="store_id">
                                                    <option selected disabled value="">---- Select ----</option>
                                                    @foreach ($store as $item)
                                                        <option value="{{ $item->id ?? '' }}" {{ old('store_id', $user->store_id ?? '') == $item->id ? 'selected' : '' }}>
                                                        {{ $item->store_name ?? '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                
                                                <label for="-image3">Store</label>

                                            </div>

                                            @error('store_id')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>


                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">

                                                <select class="form-control" name="role_type" id="role_type">

                                                    <option value="" {{ (!isset($user) || is_null($user->role_type)) && is_null(old('role_type')) ? 'selected' : '' }}>----select role type-----</option>

                                                    <option value="1" {{ (isset($user) && $user->role_type == 1) || (is_null($user) && old('role_type') == 1) ? 'selected' : '' }}>delivery boy</option>

                                                    <option value="2" {{ (isset($user) && $user->role_type == 2) || (is_null($user) && old('role_type') == 2) ? 'selected' : '' }}>vendor</option>

                                                </select>
                                                
                                                <label for="-image3">Role type</label>

                                            </div>

                                            @error('role_type')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input type="email" class="form-control"
                                                    value="{{ $user ? $user->email : old('email') }}"
                                                    name="email" placeholder="Enter email" required>

                                                <label for="email"> Email &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('email')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control"
                                                    value="{{ $user ? $user->phone : old('phone') }}"
                                                    name="phone" placeholder="Enter phone" required>

                                                <label for="phone"> Phone &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('phone')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input class="form-control" type="file" id="img" name="img"
                                                    placeholder="img">

                                                @if ($user != null)
                                                    <img src="{{ asset($user->image) }}" width="100px" height="100px">
                                                @endif

                                                <label class="mb-2" for="img">Image </label>

                                            </div>

                                            @error('img')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input type="password" class="form-control"
                                                    value="{{ old('password') }}"
                                                    name="password" placeholder="Enter Password" >

                                                <label for="password"> password &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('password')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control"
                                                    value="{{ $user ? $user->pincode : old('pincode') }}"
                                                    name="pincode" placeholder="Enter pincode" required>

                                                <label for="pincode"> Pincode  &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('pincode')
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
