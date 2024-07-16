@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title"> @if($product != null) Edit @else Add @endif  Product </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Product</a></li>

                            <li class="breadcrumb-item active">@if($product != null) Edit @else Add @endif Product</li>

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
                                
                                <h4 class="mt-0 header-title"> @if($product != null) Edit @else Add @endif Product Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('majorproduct.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($product != null) <input type="hidden" name="product_id" value="{{$product->id}}"> @endif
                                    
                                    <div class="form-group row">

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <select class="form-control" name="category" id="category">

                                                    <option >----select Category-----</option>

                                                    @foreach ($categories as $categorie)
                                                    
                                                        <option value="{{ $categorie->id }}"{{ (old('category') == $categorie->id || (isset($product) && $product->major_id == $categorie->id)) ? ' selected' : '' }}>{{ $categorie->name }}</option>
                                                        
                                                    @endforeach

                                                </select>

                                                <label for="-image3">Major Category &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('status')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $product ? $product->name : old('name') }}" name="name" placeholder="Enter name" required>

                                                <label for="name">Name &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('name')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <textarea class="form-control" name="short_dis" placeholder="Short Description ">{{ $product ? $product->short_dis : old('short_dis') }} </textarea>

                                                <label for="short_dis"> Short Description </label>

                                            </div>

                                            @error('short_dis')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <textarea class="form-control" id="long-description" name="long-description" placeholder="Long Description" required>{{ $product ? $product->long_dis : old('long-description') }}</textarea>

                                                <label for="long-description">Long Description 1 &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('long-description')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <textarea class="form-control" type="text" id="long-description2" name="long-description2" placeholder="Long Description2">  {{ $product ? $product->long_desc : old('long-description2')}}</textarea>

                                                <label for="long-description">Long Description 2 &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('long-description2')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $product ? $product->reguler_price : old('reguler_price')}}" id="reguler_price" name="reguler_price" placeholder="Enter reguler_price" required>

                                                <label for="reguler_price">Reguler Price &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('reguler_price')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $product ? $product->sale_price : old('sale_price')}}" id="sale_price" name="sale_price" placeholder="Enter sale_price">

                                                <label for="sale_price">Sales Price  </label>

                                            </div>

                                            @error('sale_price')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>


                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $product ? $product->video : old('video')}}" id="video" name="video" placeholder="Enter video">

                                                <label for="video">video </label>

                                            </div>

                                            @error('video')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="file" class="form-control" value="{{ old('img1') }}" id="image" name="img1" placeholder="Enter image" {{ $product == null ? 'required' : '' }} >

                                                @if ($product != null)
                                                    
                                                    <img src="{{asset($product->img1)}}" alt="image" width="50px" height="50px">

                                                @endif

                                                <label for="img1">Main Product Image &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('img1')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="file" class="form-control" value="{{ old('img2') }}" id="img2" name="img2" placeholder="img2" {{ $product == null ? 'required' : '' }}>

                                                @if ($product != null)
                                                    
                                                   <img src="{{asset($product->img2)}}" alt="img2" width="50px" height="50px">

                                                @endif

                                                <label for="img2">Background Image  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('img2')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="file" class="form-control" value="{{ old('img3') }}" id="img3" name="img3" placeholder="image3">

                                                @if ($product != null)
                                                    
                                                  <img src="{{asset($product->img3)}}" alt="image3" width="50px" height="50px">

                                                @endif

                                                <label for="img3"> Bottom Image  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('img3')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <select class="form-control" name="status" id="status">

                                                    <option value="" {{ (!isset($product) || is_null($product->is_active)) && is_null(old('status')) ? 'selected' : '' }}>----select status-----</option>

                                                    <option value="1" {{ (isset($product) && $product->is_active == 1) || (is_null($product) && old('status') == 1) ? 'selected' : '' }}>Active</option>

                                                    <option value="0" {{ (isset($product) && $product->is_active == 0) || (is_null($product) && old('status') == 0) ? 'selected' : '' }}>Inactive</option>

                                                </select>
                                                
                                                <label for="-image3">Status</label>

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
