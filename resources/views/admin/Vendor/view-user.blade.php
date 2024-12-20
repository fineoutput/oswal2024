@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View {{ $pageTittle }}</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">{{ $pageTittle }}</a></li>

                            <li class="breadcrumb-item active">View {{ $pageTittle }}</li>

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

                                <div class="row">

                                    <div class="col-md-9"> <h4 class="mt-0 header-title">View User</h4> </div>

                                    {{-- <div class="col-md-2"> 

                                        <a class="btn btn-info cticket" href="{{ route('user.create') }}" role="button" style="margin-left: 20px;"> Add  {{ $pageTittle }}</a>

                                    </div> --}}

                                </div>


                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <div class="table-rep-plugin">

                                    <div class="table-responsive b-0" data-pattern="priority-columns">

                                        <table id="userTable" class="table  table-striped">

                                            <thead>

                                                <tr>

                                                    <th>#</th>

                                                    <th data-priority="1">Name</th>

                                                    <th data-priority="3">Contact</th>

                                                    <th data-priority="3">Shop Name</th>

                                                    <th data-priority="3">State</th>

                                                    <th data-priority="3">City</th>

                                                    <th data-priority="3">Address</th>

                                                    <th data-priority="3">PinCode</th>

                                                    <th data-priority="3">Addhar Front image</th>

                                                    <th data-priority="3">Addhar Back image</th>

                                                    <th data-priority="3">Gst No. </th>

                                                    <th data-priority="3">Wallet Amount</th>
                                                    <th data-priority="5">Date</th>

                                                    <th data-priority="6">Status</th>

                                                    <th data-priority="6">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>
                                                
                                                @foreach ($users as $key => $user)
                                                    @php
                                                        $vendor = $user->vendor;

                                                    @endphp
                                                    
                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ $user->first_name }}</td>
                                                        <td>{{ $user->contact }}</td>

                                                        <td>{{ $vendor ? $vendor->shopname : 'Not Found' }}</td>
                                                        <td>{{ $vendor && $vendor->state ? $vendor->state->state_name : 'Not Found' }}</td>
                                                        <td>{{ $vendor && $vendor->city ? $vendor->city->city_name : 'Not Found' }}</td>
                                                        <td>{{ $vendor ? $vendor->address : 'Not Found' }}</td>

                                                        <td>{{ $vendor ? $vendor->pincode : 'Not Found' }}</td>

                                                        <td>
                                                            @if ($vendor && $vendor->addhar_front_image)
                                                                <img src="{{ asset($vendor->addhar_front_image) }}" alt="" height="100px" width="100px">
                                                            @else
                                                                Not Found
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if ($vendor && $vendor->addhar_back_image)
                                                                <img src="{{ asset($vendor->addhar_back_image) }}" alt="" height="100px" width="100px">
                                                            @else
                                                                Not Found
                                                            @endif
                                                        </td>

                                                        <td>{{ $vendor ? $vendor->gstno : 'Not Found' }}</td>
                                                        <td>{{ $user->Date_amount }}</td>
                                                        <td>
                                                            @php
                                                            $newDate = \Carbon\Carbon::parse($user->created_at);
                                                        @endphp
                                                        {{ $newDate->format('j F, Y, g:i a') }}
                                                    </td>
                                                        <td>
                                                            @if($user->is_active == 1)  
                                                                <p class="label pull-right status-active">Active</p>  
                                                            @else 
                                                                <p class="label pull-right status-inactive">Inactive</p> 
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <div class="btn-group" id="btns{{ $key }}">
                                                                @if ($user->is_active == 0)
                                                                    <a href="{{ route('user.vendor.update-status', ['active', base64_encode($user->id)]) }}" data-toggle="tooltip" data-placement="top" title="Active">
                                                                        <i class="fas fa-check success-icon"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('user.vendor.update-status', ['inactive', base64_encode($user->id)]) }}" data-toggle="tooltip" data-placement="top" title="Inactive">
                                                                        <i class="fas fa-times danger-icon"></i>
                                                                    </a>
                                                                @endif
                                                            </div>

                                                            <div style="display:none" id="cnfbox{{ $key }}">
                                                                <p>Are you sure you want to delete this?</p>
                                                                <a href="{{ route('user.destroy', base64_encode($user->id)) }}" class="btn btn-danger">Yes</a>
                                                                <a href="javascript:;" class="cans btn btn-default" mydatas="{{ $key }}">No</a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div> <!-- end col -->

                </div> <!-- end row -->

            </div>

        </div> <!-- container-fluid -->

    </div> <!-- content -->


    <div class="modal fade" id="walletModal" tabindex="-1" aria-labelledby="walletModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="walletModalLabel">Edit Wallet Amount</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body">

                    <form id="walletForm" action="{{ route('user.update-wallet') }}" method="POST">

                        @csrf
                        <input type="hidden" name="user_id" id="user_id">

                        <div class="form-group row">

                            <div class="col-sm-6 mb-3">

                                <div class="form-floating">

                                    <input type="number" class="form-control" id="wallet_amount" name="wallet_amount" placeholder="Enter Amount" required>

                                    <label for="wallet_amount">Wallet Amount &nbsp;<span style="color:red;">*</span></label>

                                </div>

                                @error('wallet_amount')

                                    <div style="color:red">{{ $message }}</div>

                                @enderror

                            </div>

                            <div class="col-sm-6 mb-3">

                                <div class="form-floating">

                                    <select class="form-select" id="type" name="type" required>

                                        <option value="credit">Credit</option>

                                        <option value="debit">Debit</option>

                                    </select>

                                    <label for="type" class="form-label">Transaction Type &nbsp;<span style="color:red;">*</span></label>

                                </div>

                                @error('type')

                                    <div style="color:red">{{ $message }}</div>

                                @enderror

                            </div>

                            <div class="col-sm-12 mb-3">

                                <div class="form-floating">

                                    <input type="text" class="form-control" id="description" name="description" placeholder="description" required>

                                    <label for="description" class="form-label">Description &nbsp;<span style="color:red;">*</span></label>

                                </div>

                                @error('description')

                                    <div style="color:red">{{ $message }}</div>

                                @enderror

                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary">Save changes</button>

                    </form>

                </div>

            </div>

        </div>

    </div>


@endsection
