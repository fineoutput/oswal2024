@extends('admin.base_template')
@section('main')
    {{-- @dd($sticker) --}}

    <!-- Add jQuery library first -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> --}}

    <style>
        
.selectpicker option
{
        border: none;
        background-color: white;
        outline: none;
        -webkit-appearance: none;
        -moz-appearance : none;
        color: #14B1B2;
        font-weight: bold;
        font-size: 30px;
        margin: 0;
        padding-left: 0;
        margin-top: -20px;
        background: none;
    }
.selectpicker
{
        border: none;
        background-color: white;
        outline: none;
        -webkit-appearance: none;
        -moz-appearance : none;
        color: #14B1B2;
        font-weight: bold;
        font-size: 30px;
        margin: 0;
        padding-left: 0;
        margin-top: -20px;
        background: none;
    }
    </style>
      <style>
        .selectpicker option
        {
                border: none;
                background-color: white;
                outline: none;
                -webkit-appearance: none;
                -moz-appearance : none;
                color: #14B1B2;
                font-weight: bold;
                font-size: 30px;
                margin: 0;
                padding-left: 0;
                margin-top: -20px;
                background: none;
            }
        select.selectpicker
        {
                border: none;
                background-color: white;
                outline: none;
                -webkit-appearance: none;
                -moz-appearance : none;
                color: #14B1B2;
                font-weight: bold;
                font-size: 30px;
                margin: 0;
                padding-left: 0;
                margin-top: -20px;
                background: none;
            }</style>

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js">
    </script>


    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">
                            @if ($sticker != null)
                                Edit
                            @else
                                Add New
                            @endif Sticker
                        </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);"> Sticker </a></li>

                            <li class="breadcrumb-item active">
                                @if ($sticker != null)
                                    Edit
                                @else
                                    Add
                                @endif Sticker
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
                                    @if ($sticker != null)
                                        Edit
                                    @else
                                        Add
                                    @endif Sticker Form
                                </h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('reward.vendor_store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($sticker != null)
                                        <input type="hidden" name="sticker_id" value="{{ $sticker->id }}">
                                    @endif

                                    <div class="form-group row">

                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">
      
                                            <div class="box">
                                            <select name="product_id" class="selectpicker des" data-show-subtext="false" data-live-search="true" style="-webkit-appearance: none;">
                                                <option selected disabled>Select Product</option>
                                                @foreach($products as $value)
                                                        <option value="{{$value->id ?? ''}}"
                                                            @if($value->id == $sticker?->product?->id ?? $sticker->id) selected @endif>
                                                            {{$value->name ?? ''}}
                                                        </option>
                                                    @endforeach
                                            </select>
                                                
                                                </div>
     
                                            
                                                {{-- <input type="text" id="product-search" placeholder="Search for a product..." onkeyup="filterProducts()">

                                                <select class="form-control" name="product_id" id="product_id">
                                                    <option selected disabled value="">Select</option>
                                                    @foreach($products as $value)
                                                        <option value="{{$value->id ?? ''}}"
                                                            @if($value->id == $sticker?->product?->id ?? $sticker->id) selected @endif>
                                                            {{$value->name ?? ''}}
                                                        </option>
                                                    @endforeach
                                                </select> --}}
                                                   
                                                
                                                {{-- <label for="product_id">Select Product &nbsp;<span style="color:red;">*</span></label> --}}
                                            </div>

                                            @error('name')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>


                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control"
                                                    value="{{ $sticker ? $sticker->name : old('name') }}"
                                                    name="name" placeholder="Enter name" required>

                                                <label for="name">Reward Name &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('name')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">

                                                <input class="form-control" type="file" id="img" name="img"
                                                    placeholder="img">

                                                @if ($sticker != null)
                                                    <img src="{{ asset($sticker->image) }}" width="100px" height="100px">
                                                @endif

                                                <label class="mb-2" for="img">Image </label>

                                            </div>

                                            @error('img')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">

                                                <input type="number" class="form-control" id="quantity"
                                                    value="{{ $sticker ? $sticker->quantity : old('quantity') }}"
                                                    name="quantity" placeholder="Enter quantity" required onkeyup="calculateTotalWeight()">

                                                <label for="quantity">Quentity &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('quantity')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" id="type"
                                                    value="{{ $sticker ? $sticker->price : old('price') }}"
                                                    name="price" placeholder="Enter type" required onkeyup="calculateTotalWeight()">

                                                <label for="type">Price &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('type')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-6 mb-3">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" id="totalweight"
                                                    value="{{ $sticker ? $sticker->weight : old('weight') }}"
                                                    name="weight" placeholder="Enter weight" required>

                                                <label for="weight">Total Weight &nbsp;<span
                                                        style="color:red;">*</span></label>

                                            </div>

                                            @error('weight')
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

{{-- <script>
  function filterProducts() {
    var searchQuery = document.getElementById('product-search').value.toLowerCase();

    var optionItems = document.querySelectorAll('#product_id option');

    optionItems.forEach(function(option) {
      var optionText = option.textContent.toLowerCase();
      
      if (optionText.includes(searchQuery)) {
        option.style.display = 'block';
      } else {
        option.style.display = 'none';
      }
    });
  }

</script> --}}




@endsection
