@extends('admin.base_template')

@section('main')
{{-- @dd($notifaction) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title"> @if($notifaction != null) Edit @else Add New @endif Push Notification </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);"> achievements</a></li>

                            <li class="breadcrumb-item active">@if($notifaction != null) Edit @else Add @endif  Push Notification</li>

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
                                
                                <h4 class="mt-0 header-title"> @if($notifaction != null) Edit @else Add @endif  Push Notification Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('notification.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($notifaction != null) <input type="hidden" name="notifaction_id" value="{{$notifaction->id}}"> @endif
                                    
                                    <div class="form-group row">


                                            <div class="form-floating">

                                                <select class="form-control" name="category_id" id="category_id" onchange="getProduct('{{ route('notification.get-product') }}')">

                                                    <option>----select Category-----</option>

                                                    @foreach ($categories as $categorie)

                                                        <option value="{{ $categorie->id }}"{{ old('category_id') == $categorie->id || (isset($notifaction) && $notifaction->category_id == $categorie->id) ? ' selected' : '' }}>{{ $categorie->name }}</option>

                                                    @endforeach

                                                </select>

                                                <label for="-image3">Category &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('category_id')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>



                                            <div class="form-floating">
                                                
                                                <select class="form-control" name="product_id" id="product-container">

                                                    <option>----No product-----</option>

                                                    @if ($notifaction != null)

                                                        @foreach ($products as $product)

                                                            <option
                                                                value="{{ $product->id }}"{{ old('product_id') == $product->id || (isset($notifaction) && $notifaction->product_id == $product->id) ? ' selected' : '' }}>
                                                                {{ $product->name }}
                                                            </option>

                                                        @endforeach

                                                    @endif

                                                </select>

                                                <label for="-image3">Product &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('product_id')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                    
                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $notifaction ? $notifaction->title : old('title') }}" name="title" placeholder="Enter title" required>

                                                <label class="mb-2" for="title"> Title  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('title')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $notifaction ? $notifaction->description : old('description') }}" name="description" placeholder="Enter description" required>

                                                <label class="mb-2" for="description">Description &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('description')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>


                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <select class="form-select" name="type" id="">
                                                    <option selected disabled value="">Select Type</option>
                                                    <option value="OswalSoap">Retailer</option>
                                                    <option value="OswalVendor">Vendor</option>
                                                    <option value="OswalSoap">Both</option>
                                                </select>
                                                <label class="mb-2" for="type">Type &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('type')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-12 mb-3">

                                            <div class="form-floating">

                                                <input class="form-control" type="file" id="img" name="img" placeholder="img">
                                                 
                                                @if ($notifaction != null)
                                                    
                                                  <img src="{{asset($notifaction->image)}}" width="100px" height="100px">

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
