@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View Footer Image</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Footer Image</a></li>

                            <li class="breadcrumb-item active">View Footer Image</li>

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

                                    <div class="col-md-9"> <h4 class="mt-0 header-title">View Footer Image</h4> </div>

                                    <div class="col-md-2"> 

                                        <a class="btn btn-info cticket" href="{{ route('testimonial.create') }}" role="button" style="margin-left: 20px;"> Add Footer Image</a>

                                    </div>

                                </div>


                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <div class="table-rep-plugin">

                                    <div class="table-responsive b-0" data-pattern="priority-columns">

                                        <table id="userTable" class="table  table-striped">

                                            <thead>

                                                <tr>

                                                    <th>#</th>

                                                    <th data-priority="1"> Name</th>

                                                    <th data-priority="3">Image</th>

                                                    <th data-priority="3">Description</th>

                                                    <th data-priority="3">Rating</th>

                                                    <th data-priority="3">Status</th>

                                                    <th data-priority="6">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($footerimages as $key => $footerimage)
                                                <tr>
                                                    <td>{{ ++$key }}</td>

                                                    <td>{{ $footerimage->name }}</td>

                                                    <td>

                                                        @if ($footerimage->image != null)
                                                            <img src="{{ asset($footerimage->image)}}" width="50px" height="50px" alt="">
                                                        @endif 

                                                   </td>

                                                   <td>{{ $footerimage->description }}</td>

                                                   <td>{{ $footerimage->rating }}</td>

                                                    <td> 
                                                        @if($footerimage->is_active == 1)  
                                                           <p class="label pull-right status-active">Active</p>  
                                                        @else 
                                                           <p class="label pull-right status-inactive">InActive</p> 
                                                        @endif
                                                    </td>

                                                    <td>
                                                        
                                                        <div class="btn-group" id="btns<?php echo $key ?>">

                                                            @if ($footerimage->is_active == 0)

                                                            <a href="{{route('testimonial.update-status',['active',base64_encode($footerimage->id)])}}" data-toggle="tooltip" data-placement="top" title="Active"><i class="fas fa-check success-icon"></i></a>

                                                            @else

                                                            <a href="{{route('testimonial.update-status',['inactive',base64_encode($footerimage->id)])}}" data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fas fa-times danger-icon"></i></a>

                                                            @endif

                                                            <a href="{{route('testimonial.create',[base64_encode($footerimage->id)])}}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>

                                                            <a href="javascript:();" class="dCnf" mydata="<?php echo $key ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>

                                                        </div>

                                                        <div style="display:none" id="cnfbox<?php echo $key ?>">
                                                            <p> Are you sure delete this </p>
                                                            <a href="{{route('testimonial.destroy', base64_encode($footerimage->id))}}" class="btn btn-danger">Yes</a>
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