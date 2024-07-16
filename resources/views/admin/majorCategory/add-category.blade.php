@extends('admin.base_template')

@section('main')
{{-- @dd($category) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title"> @if($category != null) Edit @else Add @endif Major  Category </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Category</a></li>

                            <li class="breadcrumb-item active">@if($category != null) Edit @else Add @endif Major  Category</li>

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
                                
                                <h4 class="mt-0 header-title"> @if($category != null) Edit @else Add @endif Major Category Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('majorcategory.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($category != null) <input type="hidden" name="category_id" value="{{$category->id}}"> @endif
                                    
                                    <div class="form-group row">

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $category ? $category->name : old('name') }}" name="name" placeholder="Enter name" required>

                                                <label for="name">Enter Name &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('name')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $category ? $category->short_dis : old('short-description') }}" id="short-description" name="short-description" placeholder="Short Description">

                                                <label for="short-description"> Short Description &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('short-description')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $category ? $category->long_desc : old('long-description')}}" id="long-description" name="long-description" placeholder="Long Description">

                                                <label for="long-description"> Long Description &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('long-description')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="file" class="form-control" value="{{ old('image') }}" id="image" name="image" placeholder="Enter image">

                                                @if ($category != null)
                                                    
                                                    <img src="{{asset($category->image)}}" alt="image" width="50px" height="50px">

                                                @endif

                                                <label for="image">Image &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('image')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <select class="form-control" name="status" id="status">

                                                    <option value="" {{ is_null(old('status')) && (!isset($category) || is_null($category->is_active)) ? 'selected' : '' }}>----select status-----</option>

                                                    <option value="1" {{ (old('status') == '1' || (isset($category) && $category->is_active == 1)) ? 'selected' : '' }}>Active</option>

                                                    <option value="0" {{ (old('status') == '0' || (isset($category) && $category->is_active == 0)) ? 'selected' : '' }}>Inactive</option>

                                                </select>
                                                
                                                <label for="slider-image3">Status</label>

                                            </div>

                                            @error('status')

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
