@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View User</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">User</a></li>

                            <li class="breadcrumb-item active">View User</li>

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

                                    <div class="col-md-2"> 

                                        <a class="btn btn-info cticket" href="{{ route('user.create') }}" role="button" style="margin-left: 20px;"> Add User</a>

                                    </div>

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

                                                    <th data-priority="3">Referral Code</th>

                                                    <th data-priority="3">Wallet Amount</th>

                                                    <th data-priority="6">Status</th>

                                                    <th data-priority="6">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($users as $key => $user)
                                                <tr>
                                                    <td>{{ ++$key }}</td>

                                                    <td>{{ $user->first_name }}</td>

                                                    <td>{{ $user->contact }}</td>

                                                    <td>{{ $user->referral_code }}</td>

                                                    <td>{{ $user->wallet_amount }}</td>

                                                    <td> 
                                                        @if($user->is_active == 1)  
                                                           <p class="label pull-right status-active">Active</p>  
                                                        @else 
                                                           <p class="label pull-right status-inactive">InActive</p> 
                                                        @endif
                                                    </td>

                                                    <td>
                                                        
                                                        <div class="btn-group" id="btns<?php echo $key ?>">

                                                            @if ($user->is_active == 0)

                                                            <a href="{{route('user.update-status',['active',base64_encode($user->id)])}}" data-toggle="tooltip" data-placement="top" title="Active"><i class="fas fa-check success-icon"></i></a>

                                                            @else

                                                            <a href="{{route('user.update-status',['inactive',base64_encode($user->id)])}}" data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fas fa-times danger-icon"></i></a>

                                                            @endif

                                                            <a href="{{route('user.create',[base64_encode($user->id)])}}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>

                                                            <a href="javascript:();" class="dCnf" mydata="<?php echo $key ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>

                                                        </div>

                                                          <!-- Button to trigger modal -->
                                                          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#walletModal" data-id="{{ $user->id }}" data-name="{{ $user->first_name }}" data-wallet="{{ $user->wallet_amount }}">
                                                            Edit Wallet Amount
                                                        </button>

                                                        <div style="display:none" id="cnfbox<?php echo $key ?>">
                                                            <p> Are you sure delete this </p>
                                                            <a href="{{route('user.destroy', base64_encode($user->id))}}" class="btn btn-danger">Yes</a>
                                                            <a href="javascript:();" class="cans btn btn-default" mydatas="<?php echo $key ?>">No</a>
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
