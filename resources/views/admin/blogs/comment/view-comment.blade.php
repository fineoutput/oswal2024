@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View Comments</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Comments</a></li>

                            <li class="breadcrumb-item active">View Comments</li>

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

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <div class="table-rep-plugin">

                                    <div class="table-responsive b-0" data-pattern="priority-columns">

                                        <table id="userTable" class="table  table-striped">

                                            <thead>

                                                <tr>

                                                    <th>#</th>

                                                    <th data-priority="1">Name</th>

                                                    <th data-priority="3">Email</th>

                                                    <th data-priority="1">Comment</th>

                                                    <th data-priority="6">Date</th>

                                                    <th data-priority="6">Status</th>

                                                    <th data-priority="6">Reply</th>

                                                    <th data-priority="6">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($comments as $key => $comment)
                                                <tr>
                                                    <td>{{ ++$key }}</td>

                                                    <td>{{ $comment->name }}</td>

                                                    <td>{{ $comment->email }}</td>

                                                    <td>{{ $comment->comment }}</td>

                                                    <td>{{ $comment->created_at }}</td>

                                                    <td> 
                                                        @if($comment->is_active == 1)  
                                                           <p class="label pull-right status-active">Active</p>  
                                                        @else 
                                                           <p class="label pull-right status-inactive">InActive</p> 
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if($comment->reply_status == 1)  
                                                            <p class="label pull-right status-active">Replied</p>  
                                                        @else 
                                                            <p class="label pull-right status-inactive">Not Replied</p> 
                                                        @endif
                                                        
                                                   </td>

                                                    <td>
                                                        
                                                        <div class="btn-group" id="btns<?php echo $key ?>">

                                                            @if ($comment->is_active == 0)

                                                            <a href="{{route('blog.comment-update-status',['active',base64_encode($comment->id)])}}" data-toggle="tooltip" data-placement="top" title="Active"><i class="fas fa-check success-icon"></i></a>

                                                            @else

                                                            <a href="{{route('blog.comment-update-status',['inactive',base64_encode($comment->id)])}}" data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fas fa-times danger-icon"></i></a>

                                                            @endif

                                                            <a href="{{route('blog.add-replay',[base64_encode($comment->id)])}}" data-toggle="tooltip" data-placement="top" title="add Replay"><i class="fas fa-edit"></i></a>

                                                            <a href="{{route('blog.edit-replay',[base64_encode($comment->id)])}}" data-toggle="tooltip" data-placement="top" title="Edit Replay"><i class="fas fa-edit"></i></a>

                                                            <a href="javascript:();" class="dCnf" mydata="<?php echo $key ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>

                                                        </div>

                                                        <div style="display:none" id="cnfbox<?php echo $key ?>">
                                                            <p> Are you sure delete this </p>
                                                            <a href="{{route('blog.comment-destroy', base64_encode($comment->id))}}" class="btn btn-danger">Yes</a>
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
