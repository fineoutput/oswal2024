@extends('admin.base_template')

@section('main')
{{-- @dd($pageTittle) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        

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
                                
                                <h4 class="mt-0 header-title"> @if($pageTittle != null) Edit @else Add @endif Popup Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('store.store') }}" method="post">
                                    @csrf

                                    <div class="form-group row">

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" value="{{ old('store_name') }}" name="store_name" placeholder="Enter Store Name" required>
                                                <label class="mb-2" for="store_name">Store Name <span style="color:red;">*</span></label>
                                            </div>
                                            @error('store_name')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" value="{{ old('operator_name') }}" name="operator_name" placeholder="Enter Operator Name" required>
                                                <label class="mb-2" for="operator_name">Operator Name <span style="color:red;">*</span></label>
                                            </div>
                                            @error('operator_name')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" value="{{ old('phone_no') }}" name="phone_no" placeholder="Enter Phone Number" required>
                                                <label class="mb-2" for="phone_no">Phone Number <span style="color:red;">*</span></label>
                                            </div>
                                            @error('phone_no')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" value="{{ old('GST_No') }}" name="GST_No" placeholder="Enter GST Number">
                                                <label class="mb-2" for="GST_No">GST Number</label>
                                            </div>
                                            @error('GST_No')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <textarea class="form-control" name="address" placeholder="Enter Address" style="height: 100px;" required>{{ old('address') }}</textarea>
                                                <label class="mb-2" for="address">Address <span style="color:red;">*</span></label>
                                            </div>
                                            @error('address')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <select class="form-control" id="state_id" name="state_id" required>
                                                    <option value="">Select State</option>
                                                    @foreach($states as $state)
                                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                                    @endforeach
                                                </select>
                                                <label class="mb-2" for="state_id">State <span style="color:red;">*</span></label>
                                            </div>
                                            @error('state_id')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <select class="form-control" id="city_id" name="city_id" required>
                                                    <option value="">Select City</option>
                                                </select>
                                                <label class="mb-2" for="city_id">City <span style="color:red;">*</span></label>
                                            </div>
                                            @error('city_id')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" value="{{ old('locality') }}" name="locality" placeholder="Enter Locality" required>
                                                <label class="mb-2" for="locality">Locality <span style="color:red;">*</span></label>
                                            </div>
                                            @error('locality')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" value="{{ old('shop_code') }}" name="shop_code" placeholder="Enter Shop Code" required>
                                                <label class="mb-2" for="shop_code">Shop Code <span style="color:red;">*</span></label>
                                            </div>
                                            @error('shop_code')
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#state_id').on('change', function () {
            var stateId = $(this).val();

            if (stateId) {
                  var url = "{{ route('store.getCities', ':id') }}";
                url = url.replace(':id', stateId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        $('#city_id').empty().append('<option value="">Select City</option>');

                        $.each(data, function (key, city) {
                            $('#city_id').append('<option value="' + city.id + '">' + city.city_name + '</option>');
                        });
                    },
                    error: function () {
                        alert('Error fetching cities.');
                    }
                });
            } else {
                $('#city_id').empty().append('<option value="">Select City</option>');
            }
        });
    });
</script>

@endsection
