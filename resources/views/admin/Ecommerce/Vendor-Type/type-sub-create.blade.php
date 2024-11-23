@extends('admin.base_template')

@section('main')

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ isset($data) ? 'Edit Range' : 'Add New Range' }}</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Range</a></li>
                        <li class="breadcrumb-item active">{{ isset($data) ? 'Edit' : 'Add New' }}</li>
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
                            <!-- Show success and error messages -->
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <h4 class="mt-0 header-title">{{ isset($data) ? 'Edit Range Form' : 'Add New Range Form' }}</h4>
                            <hr style="margin-bottom: 50px; background-color: darkgrey;">

                            <form action="{{ isset($data) ? route('vendor.type.subtype.update') : route('vendor.type.sub.store') }}" method="post">
                                @csrf
                                {{-- @if (isset($data))
                                    @method('PUT')
                                @endif --}}

                                <input type="hidden" name="edit_type_id" class="form-control" value="{{ $data->edit_type_id ?? old('edit_type_id') }}" />
                                <input type="hidden" name="sub_type_id" class="form-control" value="{{ $data->id ?? ' ' }}" />

                                <div class="form-group row">
                                    <!-- Start Range -->
                                    <div class="col-sm-4">
                                        <input type="hidden" name="type_id" value="{{ $data->type_id ?? $id }}">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="start_range" placeholder="Enter start range" required value="{{ $data->start_range ?? old('start_range') }}">
                                            <label for="start_range">First Number Of Range &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('start_range')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- End Range -->
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="end_range" placeholder="Enter end range" required value="{{ $data->end_range ?? old('end_range') }}">
                                            <label for="end_range">Last Number Of Range &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('end_range')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- MRP -->
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="mrp" name="mrp" placeholder="Enter MRP" required  value="{{ $data->mrp ?? old('mrp') }}" >
                                            <label for="mrp">MRP &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('mrp')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <!-- GST Percentage -->
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="gst_percentage" name="gst_percentage" placeholder="Enter GST %" required  value="{{ $data->gst_percentage ?? old('gst_percentage') }}" >
                                            <label for="gst_percentage">GST % &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('gst_percentage')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Selling Price (without GST) -->
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="selling_price_gst" name="selling_price_gst" placeholder="Selling Price (without GST)" required onkeyup="calculatePrices()" value="{{ $data->selling_price_gst ?? old('selling_price_gst') }}">
                                            <label for="selling_price_gst">Selling Price (without GST) &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('selling_price_gst')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- GST Price -->
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="gst_percentage_price" name="gst_percentage_price" placeholder="GST Price" value="{{ $data->gst_percentage_price ?? old('gst_percentage_price') }}">
                                            <label for="gst_percentage_price">GST Price &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('gst_percentage_price')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <!-- Selling Price (with GST) -->
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="selling_price" name="selling_price" placeholder="Selling Price (with GST)" required value="{{ $data->selling_price ?? old('selling_price') }}">
                                            <label for="selling_price">Selling Price (with GST)</label>
                                        </div>
                                        @error('selling_price')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- <!-- Weight -->
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="weight" name="weight" placeholder="Weight" value="{{ $data->weight ?? old('weight') }}">
                                            <label for="weight">Weight (in gm)&nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('weight')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Rate -->
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="rate" name="rate" placeholder="Rate" required value="{{ $data->rate ?? old('rate') }}">
                                            <label for="rate">Rate &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('rate')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div> --}}
                                </div>

                                <div class="form-group row">
                                    <div class="form-group">
                                        <div class="w-100 text-center">
                                            <button type="submit" style="margin-top: 10px;" class="btn btn-danger">
                                                <i class="fa fa-save"></i> {{ isset($data) ? 'Update' : 'Submit' }}
                                            </button>
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
<script>
    window.onload = function() {
        console.log('JavaScript Loaded');

        function calculatePrices() {
            console.log('Calculating Prices');

            // Get Selling Price and GST Percentage values
            var sellingPrice = parseFloat(document.getElementById('selling_price_gst').value) || 0; // Use selling_price_gst
            var gstPercentage = parseFloat(document.getElementById('gst_percentage').value) || 0;

            // Calculate GST Price
            var gstPrice = (sellingPrice * gstPercentage) / 100;

            // Calculate Selling Price (with GST)
            var sellingPriceWithGST = sellingPrice + gstPrice;

            // Fill in the fields
            document.getElementById('gst_percentage_price').value = gstPrice.toFixed(2); // GST Price
            document.getElementById('selling_price').value = sellingPriceWithGST.toFixed(2); // Selling Price (with GST)
        }

        // Attach event listeners
        document.getElementById('selling_price_gst').addEventListener('keyup', calculatePrices); // Listen to Selling Price changes
        document.getElementById('gst_percentage').addEventListener('keyup', calculatePrices); // Listen to GST changes
    }
</script>




@endsection
