@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View Delivery User</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">User</a></li>

                            <li class="breadcrumb-item active">View Delivery User</li>

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

                                    <div class="col-md-9"> <h4 class="mt-0 header-title">View Delivery User</h4> </div>

                                    <div class="col-md-2"> 

                                        <a class="btn btn-info cticket" href="{{ route('delivery.create') }}" role="button" style="margin-left: 20px;"> Add User</a>

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

                                                    <th data-priority="1">Role Type</th>

                                                    <th data-priority="3">Contact</th>

                                                    <th data-priority="3">Email</th>

                                                    <th data-priority="3">Photo</th>

                                                    <th data-priority="3">Pincode</th>

                                                    <th data-priority="3">Password</th>

                                                    <th data-priority="6">Status</th>

                                                    <th data-priority="6">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($users as $key => $user)
                                                <tr>
                                                    <td>{{ ++$key }}</td>

                                                    <td>{{ $user->name }}</td>

                                                    <td>
                                                        
                                                        @if ( $user->role_type == 2)

                                                            Vendor

                                                        @else

                                                            Delivery Boy

                                                        @endif

                                                    </td>

                                                    <td>{{ $user->phone }}</td>

                                                    <td>{{ $user->email }}</td>

                                                    <td>
                                                        @if ($user->image != null)
                                                          <img src="{{ asset($user->image) }}" width="100px" height="100px">
                                                        @else
                                                          Sorry No image Found
                                                        @endif
                                                    </td>


                                                    <td>{{ $user->pincode }}</td>

                                                    <td>{{ $user->password }}</td>

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

                                                            <a href="{{route('delivery.update-status',['active',base64_encode($user->id)])}}" data-toggle="tooltip" data-placement="top" title="Active"><i class="fas fa-check success-icon"></i></a>

                                                            @else

                                                            <a href="{{route('delivery.update-status',['inactive',base64_encode($user->id)])}}" data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fas fa-times danger-icon"></i></a>

                                                            @endif

                                                            <a href="{{route('delivery.create',[base64_encode($user->id)])}}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>

                                                            <a href="javascript:();" class="dCnf" mydata="<?php echo $key ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>

                                                        </div>

                                                          <!-- Button to trigger modal -->
                                                          <a href="{{route('delivery.order',[base64_encode($user->id)])}}" data-toggle="tooltip" data-placement="top" title="View Orders">Orders</a>

                                                        <div style="display:none" id="cnfbox<?php echo $key ?>">
                                                            <p> Are you sure delete this </p>
                                                            <a href="{{route('delivery.destroy', base64_encode($user->id))}}" class="btn btn-danger">Yes</a>
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

@endsection
