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

                                <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($product != null) <input type="hidden" name="product_id" value="{{$product->id}}"> @endif
                                    
                                    <div class="form-group row">

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <select class="form-control" name="category" id="category">

                                                    <option >----select Category-----</option>

                                                    @foreach ($categories as $categorie)
                                                    
                                                        <option value="{{ $categorie->id }}"{{ (old('category') == $categorie->id || (isset($product) && $product->category_id == $categorie->id)) ? ' selected' : '' }}>{{ $categorie->name }}</option>
                                                        
                                                    @endforeach

                                                </select>

                                                <label for="-image3">Category &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('status')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $product ? $product->name : old('name') }}" name="name" placeholder="Enter name" required>

                                                <label for="name">Name(english) &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('name')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $product ? $product->name_hi : old('name_hi') }}" name="name_hi" placeholder="Name(hindi)">

                                                <label for="name_hi">Name Hindi &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('name_hi')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $product ? $product->hsn_code : old('hsn_code') }}" name="hsn_code" placeholder="Name(hindi)">

                                                <label for="hsn_code">HSN Code </label>

                                            </div>

                                            @error('hsn_code')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $product ? $product->long_desc : old('long-description')}}" id="long-description" name="long-description" placeholder="Long Description" required>

                                                <label for="long-description">Description &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('long-description')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $product ? $product->long_desc_hi : old('long_desc_hi') }}" id="long_desc_hi" name="long_desc_hi" placeholder="Description Hindi">

                                                <label for="long_desc_hi">Description Hindi </label>

                                            </div>

                                            @error('long_desc_hi')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $product ? $product->video : old('video')}}" id="video" name="video" placeholder="Enter video">

                                                <label for="video">Video Link(Optional) </label>

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

                                                <label for="img1">Image &nbsp;<span style="color:red;">*</span></label>

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

                                                <label for="img2">Image &nbsp;<span style="color:red;">*</span></label>

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

                                                <label for="img3"> Image &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('img3')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="file" class="form-control" value="{{ old('img4') }}" id="img4" name="img4" placeholder=" img4" >

                                                @if ($product != null)
                                                    
                                                   <img src="{{asset($product->img4)}}" alt="image4" width="50px" height="50px">

                                                @endif

                                                <label for="img4">Image &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('img4')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        
                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="file" class="form-control" value="{{ old('img_app1') }}" id="img_app1" name="img_app1" placeholder="img_app1" >

                                                @if ($product != null && $product->img_app1 != null)
                                                    
                                                  <img src="{{asset($product->img_app1)}}" alt="img_app1" width="50px" height="50px">

                                                @endif

                                                <label for="img_app1">Image App &nbsp;<span style="color:red;">*</span> </label>

                                            </div>

                                            @error('img_app1')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="file" class="form-control" value="{{ old('img_app2') }}" id="img_app2" name="img_app2" placeholder="img_app2">

                                                @if ($product != null)
                                                    
                                                  <img src="{{asset($product->img_app2)}}" alt="app_image" width="50px" height="50px">

                                                @endif

                                                <label for="img_app2"> Image App &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('img_app2')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="file" class="form-control" value="{{ old('img_app3') }}" id="img_app3" name="img_app3" placeholder="img_app3" >

                                                @if ($product != null )
                                                    
                                                   <img src="{{asset($product->img_app3)}}" alt="img_app3" width="50px" height="50px">

                                                @endif

                                                <label for="img_app3"> Image App &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('img_app3')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>
                                        
                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="file" class="form-control" value="{{ old('img_app4') }}" id="img_app4" name="img_app4" placeholder="img_app4" >

                                                @if ($product != null)
                                                    
                                                  <img src="{{asset($product->img_app4)}}" alt="img_app4" width="50px" height="50px">

                                                @endif

                                                <label for="img_app4"> Image App &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('img_app4')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-2">

                                            <div class="form-floating">

                                                <select class="form-control" name="product_view" id="product_view">

                                                    <option value="" {{ (!isset($product) || is_null($product->product_view)) && is_null(old('product_view')) ? 'selected' : '' }}>select product view</option>

                                                    <option value="1" {{ (isset($product) && $product->product_view == 1) || (is_null($product) && old('product_view') == 1) ? 'selected' : '' }}>Retailer</option>

                                                    <option value="2" {{ (isset($product) && $product->product_view == 2) || (is_null($product) && old('product_view') == 2) ? 'selected' : '' }}>Reseller</option>

                                                    <option value="3" {{ (isset($product) && $product->product_view == 3) || (is_null($product) && old('product_view') == 3) ? 'selected' : '' }}>Both</option>

                                                </select>
                                                
                                                <label for="product_view">product_view</label>

                                            </div>

                                            @error('product_view')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-2">

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

                                        <div class="col-sm-2">

                                            <div class="form-floating">

                                                <select class="form-control" name="productCategorie" id="productCategorie">

                                                    <option >Select Product Type</option>

                                                    @foreach ($productCategories as $productCategorie)
                                                    
                                                        <option value="{{ $productCategorie->id }}"{{ (old('productCategorie') == $productCategorie->id || (isset($product) && $product->product_category_id ==  $productCategorie->id)) ? ' selected' : '' }}>{{ $productCategorie->category_name }}</option>
                                                        
                                                    @endforeach

                                                </select>

                                                <label for="-image3">Product Category  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('productCategorie')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-2">

                                            <div class="form-floating">

                                                <select class="form-control" name="hot_selling" id="hot_selling">

                                                    <option value="" {{ (!isset($product) || is_null($product->is_hot)) && is_null(old('hot_selling')) ? 'selected' : '' }}>----Please select-----</option>

                                                    <option value="1" {{ (isset($product) && $product->is_hot == 1) || (!isset($product) && old('hot_selling') == 1) ? 'selected' : '' }}>Yes</option>

                                                    <option value="0" {{ (isset($product) && $product->is_hot == 0) || (!isset($product) && old('hot_selling') == 0) ? 'selected' : '' }}>No</option>

                                                </select>

                                                <label for="-image3">Hot Selling &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('hot_selling')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-2">

                                            <div class="form-floating">

                                                <select class="form-control" name="is_featured" id="is_featured">

                                                    <option value="" {{ (!isset($product) || is_null($product->is_featured)) && is_null(old('is_featured')) ? 'selected' : '' }}>----Please select-----</option>

                                                    <option value="1" {{ (isset($product) && $product->is_featured == 1) || (!isset($product) && old('is_featured') == 1) ? 'selected' : '' }}>Yes</option>

                                                    <option value="0" {{ (isset($product) && $product->is_featured == 0) || (!isset($product) && old('is_featured') == 0) ? 'selected' : '' }}>No</option>

                                                </select>

                                                <label for="-image3">Featured Product &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('is_featured')

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
