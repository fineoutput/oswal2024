@extends('admin.base_template')

@section('main')
{{-- @dd($blog) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title"> @if($blog != null) Edit @else Add @endif Blog </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Blog</a></li>

                            <li class="breadcrumb-item active">@if($blog != null) Edit @else Add @endif Blog</li>

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
                                
                                <h4 class="mt-0 header-title"> @if($blog != null) Edit @else Add @endif Blog Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('blog.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($blog != null) <input type="hidden" name="blog_id" value="{{$blog->id}}"> @endif
                                    
                                    <div class="form-group row">

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $blog ? $blog->title : old('title') }}" name="title" placeholder="Enter title" required>

                                                <label for="title">Blog Title  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('title')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $blog ? $blog->auther : old('auther') }}" name="auther" placeholder="Enter auther" required>

                                                <label for="auther">Auther Name &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('auther')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $blog ? $blog->short_des : old('short_des') }}" name="short_des" placeholder="Enter short_des" required>

                                                <label for="short_des">Short Description &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('short_des')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $blog ? $blog->long_des : old('long_des') }}" name="long_des" placeholder="Enter long_des" required>

                                                <label for="long_des">Long Description &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('long_des')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $blog ? $blog->keywords : old('keywords') }}" name="keywords" placeholder="Enter keywords" required>

                                                <label for="keywords">Keywords &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('keywords')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $blog ? $blog->meta : old('meta') }}" name="meta" placeholder="Enter meta" required>

                                                <label for="meta">Meta Description &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('meta')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="file" value="{{ $blog ? $blog->image : old('img') }}" id="img" name="img" placeholder="img">

                                                <label for="img">Image (image should be 350 X 200)  &nbsp;<span style="color:red;">*</span></label>

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
