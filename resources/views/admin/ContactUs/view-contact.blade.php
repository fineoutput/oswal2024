@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View Contact Us Queries</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Contact Us Queries</a></li>

                            <li class="breadcrumb-item active">View Contact Us Queries</li>

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


                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <div class="table-rep-plugin">

                                    <div class="table-responsive b-0" data-pattern="priority-columns">

                                        <table id="userTable" class="table  table-striped">

                                            <thead>

                                                <tr>

                                                    <th>#</th>

                                                    <th data-priority="1">Name</th>

                                                    <th data-priority="3">Email</th>

                                                    <th data-priority="1">Phone</th>

                                                    <th data-priority="6">Message</th>

                                                    <th data-priority="6">Replay</th>

                                                    <th data-priority="6">Replay Message</th>

                                                    <th data-priority="6">Date</th>

                                                    <th data-priority="6">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($contactuss as $key => $contactus)
                                                <tr>
                                                    <td>{{ ++$key }}</td>

                                                    <td>{{ $contactus->full_name }}</td>

                                                    <td> {{ $contactus->email  }}</td>

                                                    <td>{{ $contactus->phone }}</td>

                                                    <td> {{ $contactus->message }}</td>
                                                    
                                                    <td> 
                                                        @if($contactus->reply == 1)  
                                                           <p class="label pull-right status-active">Replied</p>  
                                                        @else 
                                                           <p class="label pull-right status-inactive">Not Replied</p> 
                                                        @endif
                                                    </td>

                                                    <td> {!!  $contactus->reply_message  !!}</td>
                                                    
                                                    <td> {{ $contactus->cur_date }}</td>

                                                    <td>
                                                        
                                                         <div class="btn-group" id="btns<?php echo $key ?>">

                                                            <a href="{{route('contact-us.send-reply',base64_encode($contactus->id))}}" data-toggle="tooltip" data-placement="top" title="reply">reply</a>

                                                            <a href="javascript:();" class="dCnf" mydata="<?php echo $key ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>

                                                        </div> 

                                                         <div style="display:none" id="cnfbox<?php echo $key ?>">
                                                            <p> Are you sure delete this </p>
                                                            <a href="{{route('contact-us.destroy', base64_encode($contactus->id))}}" class="btn btn-danger">Yes</a>
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
