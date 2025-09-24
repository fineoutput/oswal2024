@extends('admin.base_template')
@section('main')
<!-- Start content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Add Team</h4>
                    <ol class="breadcrumb" style="display:none">
                        <!-- <li class="breadcrumb-item"><a href="javascript:void(0);">CMS</a></li> -->
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Team</a></li>
                        <li class="breadcrumb-item active">Add Team</li>
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
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                            </div>
                            @endif
                            @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                            </div>
                            @endif
                            <!-- End show success and error messages -->
                            <h4 class="mt-0 header-title">Add Team Form</h4>
                            <hr style="margin-bottom: 50px;background-color: darkgrey;">

                         <form action="{{ route('update_team_process', $Team_data->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('POST') {{-- or PATCH/PUT based on your route --}}
    
    <div class="form-group row">
        <!-- Name -->
        <div class="col-sm-4">
            <div class="form-floating">
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $Team_data->name) }}" placeholder="Enter name" required>
                <label for="name">Enter Name &nbsp;<span style="color:red;">*</span></label>
            </div>
            @error('name')
            <div style="color:red">{{$message}}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="col-sm-4">
            <div class="form-floating">
                <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $Team_data->email) }}" placeholder="Enter email" required>
                <label for="email">Email &nbsp;<span style="color:red;">*</span></label>
            </div>
            @error('email')
            <div style="color:red">{{$message}}</div>
            @enderror
        </div>

        <!-- Phone -->
        <div class="col-sm-4">
            <div class="form-floating">
                <input class="form-control" type="text" id="phone" name="phone" value="{{ old('phone', $Team_data->phone) }}" placeholder="Phone no. (Optional)" onkeypress="return isNumberKey(event)" maxlength="10" minlength="10">
                <label for="phone">Phone (optional)</label>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <!-- Address -->
        <div class="col-sm-4">
            <div class="form-floating">
                <input class="form-control" type="text" id="address" name="address" value="{{ old('address', $Team_data->address) }}" placeholder="Address (Optional)">
                <label for="address">Address (optional)</label>
            </div>
        </div>

        <!-- Password -->
        <div class="col-sm-4">
            <div class="form-floating">
                <input class="form-control" type="password" id="password" name="password" placeholder="Enter Password">
                <label for="password">Password (leave blank to keep same)</label>
            </div>
            @error('password')
            <div style="color:red">{{$message}}</div>
            @enderror
        </div>

        <!-- Power -->
        <div class="col-sm-4 mt-2">
            <select class="form-control" name="power" id="power" required>
                <option value="">Please select Type</option>
                <option value="1" {{ $Team_data->power == 1 ? 'selected' : '' }}>Super Admin</option>
                <option value="2" {{ $Team_data->power == 2 ? 'selected' : '' }}>Admin</option>
                <option value="3" {{ $Team_data->power == 3 ? 'selected' : '' }}>Manager</option>
            </select>
            @error('power')
            <div style="color:red">{{$message}}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <!-- Image -->
        <div class="col-sm-4"><br>
            <label class="form-label" style="margin-left: 10px" for="img">Image</label>
            <input class="form-control" style="margin-left: 10px" type="file" id="img" name="img">
            @if ($Team_data->image)
                <img src="{{ asset('upload_path/' . $Team_data->image) }}" alt="Image" width="80" class="mt-2" style="margin-left: 10px;">
            @endif
        </div>

        <!-- Services -->
     @php
    $selected_services = json_decode($Team_data->services, true) ?? [];
@endphp

<div class="col-sm-8 mt-3">
    <label class="form-label" for="services">Services &nbsp;<span style="color:red;">*</span></label>
    <div class="form-check-inline">
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="service" value="999"
                {{ in_array("999", $selected_services) ? 'checked' : '' }}> All
        </label>
    </div>

    @if (!empty($service_data))
        @foreach ($service_data as $service)
            <div class="form-check-inline">
                <label class="form-check-label" for="service_{{ $service->id }}">
                    <input type="checkbox" class="form-check-input" id="service_{{ $service->id }}"
                        name="services[]"
                        value="{{ $service->id }}"
                        {{ in_array($service->id, $selected_services) ? 'checked' : '' }}>
                    {{ $service->name }}
                </label>
            </div>
        @endforeach
    @endif
</div>

    </div>

    <div class="form-group">
        <div class="w-100 text-center">
            <button type="submit" style="margin-top: 10px;" class="btn btn-primary">
                <i class="fa fa-save"></i> Update
            </button>
        </div>
    </div>
</form>


                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
        <!-- end page content-->
    </div> <!-- container-fluid -->
</div> <!-- content -->
@endsection