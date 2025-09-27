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
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                                <!-- End show success and error messages -->
                                
                                <h4 class="mt-0 header-title"> @if($pageTittle != null) Edit @else Add @endif Store Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">
                                <form action="{{ route('store.update', base64_encode($oswalstore->id)) }}" method="post">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group row">

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="store_name" value="{{ old('store_name', $oswalstore->store_name) }}" placeholder="Enter Store Name" required>
                                                <label class="mb-2" for="store_name">Store Name <span style="color:red;">*</span></label>
                                            </div>
                                            @error('store_name')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="operator_name" value="{{ old('operator_name', $oswalstore->operator_name) }}" placeholder="Enter Operator Name" required>
                                                <label class="mb-2" for="operator_name">Operator Name <span style="color:red;">*</span></label>
                                            </div>
                                            @error('operator_name')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" name="phone_no" value="{{ old('phone_no', $oswalstore->phone_no) }}" placeholder="Enter Phone Number" required>
                                                <label class="mb-2" for="phone_no">Phone Number <span style="color:red;">*</span></label>
                                            </div>
                                            @error('phone_no')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="GST_No" value="{{ old('GST_No', $oswalstore->GST_No) }}" placeholder="Enter GST Number">
                                                <label class="mb-2" for="GST_No">GST Number</label>
                                            </div>
                                            @error('GST_No')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <textarea class="form-control" name="address" placeholder="Enter Address" style="height: 100px;" required>{{ old('address', $oswalstore->address) }}</textarea>
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
                                                        <option value="{{ $state->id }}" {{ old('state_id', $oswalstore->state_id) == $state->id ? 'selected' : '' }}>
                                                            {{ $state->state_name }}
                                                        </option>
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
                                                  @foreach($cities as $city)
                                                    <option value="{{ $city->id }}" {{ old('city_id', $oswalstore->city_id) == $city->id ? 'selected' : '' }}>
                                                        {{ $city->city_name }}
                                                    </option>
                                                        @endforeach
                                                </select>
                                                <label class="mb-2" for="city_id">City <span style="color:red;">*</span></label>
                                            </div>
                                            @error('city_id')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="locality" value="{{ old('locality', $oswalstore->locality) }}" placeholder="Enter Locality" required>
                                                <label class="mb-2" for="locality">Locality <span style="color:red;">*</span></label>
                                            </div>
                                            @error('locality')
                                                <div style="color:red">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="shop_code" value="{{ old('shop_code', $oswalstore->shop_code) }}" placeholder="Enter Shop Code" required>
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
                                                <button type="submit" style="margin-top: 10px;" class="btn btn-danger"><i class="fa fa-user"></i> Update</button>
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
        let selectedStateId = "{{ old('state_id', $oswalstore->state_id) }}";
        let selectedCityId = "{{ old('city_id', $oswalstore->city_id) }}";

        function loadCities(stateId, selectedCityId = null) {
            if (stateId) {
                  var url = "{{ route('store.getCities', ':id') }}";
                url = url.replace(':id', stateId);
                $.ajax({
                     url: url,
                    type: 'GET',
                    success: function (data) {
                        $('#city_id').empty().append('<option value="">Select City</option>');

                        $.each(data, function (key, city) {
                            let isSelected = (selectedCityId == city.id) ? 'selected' : '';
                            $('#city_id').append('<option value="' + city.id + '" ' + isSelected + '>' + city.city_name + '</option>');
                        });
                    },
                    error: function () {
                        alert('Error fetching cities.');
                    }
                });
            } else {
                $('#city_id').empty().append('<option value="">Select City</option>');
            }
        }

        // 🔄 Trigger AJAX when state changes
        $('#state_id').on('change', function () {
            loadCities($(this).val());
        });

        // 🚀 Trigger on page load if editing
        if (selectedStateId) {
            $('#state_id').val(selectedStateId); // Just to be safe
            loadCities(selectedStateId, selectedCityId);
        }
    });
</script>

@endsection
