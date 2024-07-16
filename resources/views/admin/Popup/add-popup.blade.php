@extends('admin.base_template')

@section('main')
{{-- @dd($popup) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title"> @if($popup != null) Edit @else Add New @endif  Popup </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);"> achievements</a></li>

                            <li class="breadcrumb-item active">@if($popup != null) Edit @else Add @endif Popup</li>

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
                                
                                <h4 class="mt-0 header-title"> @if($popup != null) Edit @else Add @endif Popup Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('home.store-popup') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($popup != null) <input type="hidden" name="popup_id" value="{{$popup->id}}"> @endif
                                    
                                    <div class="form-group row">

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $popup ? $popup->name : old('name') }}" name="name" placeholder="Enter name" required>

                                                <label class="mb-2" for="name"> Name  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('name')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input class="form-control" type="file" id="img" name="img" placeholder="img">
                                                 
                                                @if ($popup != null)
                                                    
                                                  <img src="{{asset($popup->image)}}" width="100px" height="100px">

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
