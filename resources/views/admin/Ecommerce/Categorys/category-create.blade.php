@extends('admin.base_template')

@section('main')
{{-- @dd($category) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title"> @if($category != null) Edit @else Add @endif  Category </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Category</a></li>

                            <li class="breadcrumb-item active">@if($category != null) Edit @else Add @endif Category</li>

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
                                
                                <h4 class="mt-0 header-title"> @if($category != null) Edit @else Add @endif Category Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('category.store') }}" method="post" enctype="multipart/form-data">

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

                                                <input class="form-control" type="text" value="{{ $category ? $category->short_disc : old('short-description') }}" id="short-description" name="short-description" placeholder="Short Description" required>

                                                <label for="short-description"> Short Description &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('short-description')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $category ? $category->long_desc : old('long-description')}}" id="long-description" name="long-description" placeholder="Long Description" required>

                                                <label for="long-description"> Long Description &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('long-description')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="number" value="{{ $category ? $category->sequence : old('sequence')}}" id="sequence" name="sequence" placeholder="Enter sequence" required>

                                                <label for="sequence">Enter sequence &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('sequence')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="file" class="form-control" value="{{ old('image') }}" id="image" name="image" placeholder="Enter image" {{ $category == null ? 'required' : '' }} >

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

                                                <input type="file" class="form-control" value="{{ old('app-image') }}" id="app-image" name="app-image" placeholder="App image" {{ $category == null ? 'required' : '' }}>

                                                @if ($category != null)
                                                    
                                                   <img src="{{asset($category->app_image)}}" alt="app_image" width="50px" height="50px">

                                                @endif

                                                <label for="app image">App Image &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('app-image')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="file" class="form-control" value="{{ old('slider-image1') }}" id="slider-image1" name="slider-image1" placeholder="Slider image1">

                                                @if ($category != null && $category->slide_img1 != null)
                                                    
                                                  <img src="{{asset($category->slide_img1)}}" alt="app_image" width="50px" height="50px">

                                                @endif

                                                <label for="slider-image1">Slider Image1 </label>

                                            </div>

                                            @error('slider-image1')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="file" class="form-control" value="{{ old('slider-image2') }}" id="slider-image2" name="slider-image2" placeholder=" slider-image2" >

                                                @if ($category != null && $category->slide_img2 != null)
                                                    
                                                   <img src="{{asset($category->slide_img2)}}" alt="app_image" width="50px" height="50px">

                                                @endif

                                                <label for="slider-image2">Slider Image2 </label>

                                            </div>

                                            @error('slider-image2')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>
                                        
                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="file" class="form-control" value="{{ old('slider-image3') }}" id="slider-image3" name="slider-image3" placeholder="Slider image3" >

                                                @if ($category != null && $category->slide_img3 != null)
                                                    
                                                  <img src="{{asset($category->slide_img3)}}" alt="app_image" width="50px" height="50px">

                                                @endif

                                                <label for="slider-image3">Slider Image3 </label>

                                            </div>

                                            @error('slider-image3')

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
