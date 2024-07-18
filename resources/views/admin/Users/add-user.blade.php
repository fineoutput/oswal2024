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
                            @endif User
                        </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);"> User </a></li>

                            <li class="breadcrumb-item active">
                                @if ($user != null)
                                    Edit
                                @else
                                    Add
                                @endif User
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
                                    @endif User Form
                                </h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($user != null)
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    @endif

                                    <div class="form-group row">

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control"
                                                    value="{{ $user ? $user->first_name : old('first_name') }}"
                                                    name="first_name" placeholder="Enter name" required>

                                                <label for="first_name">Name &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('first_name')
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
                                                    value="{{ $user ? $user->contact : old('contact') }}"
                                                    name="contact" placeholder="Enter contact" required>

                                                <label for="contact"> Contact &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('contact')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input type="password" class="form-control"
                                                    value="{{ $user ? '': old('password') }}"
                                                    name="password" placeholder="Enter Password" required>

                                                <label for="password"> password &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('password')
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
