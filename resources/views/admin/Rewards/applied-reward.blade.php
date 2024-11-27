@extends('admin.base_template')

@section('main')
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View Vendor Reward</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);"> Vendor Reward</a></li>

                            <li class="breadcrumb-item active">View Vendor Reward</li>

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

                                {{-- <div class="row">

                                    <div class="col-md-9"> <h4 class="mt-0 header-title">View Reward</h4> </div>

                                    <div class="col-md-2"> 

                                        <a class="btn btn-info cticket" href="{{ route('reward.create') }}" role="button" style="margin-left: 20px;"> Add Reward</a>

                                    </div>

                                </div> --}}


                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <div class="table-rep-plugin">

                                    <div class="table-responsive b-0" data-pattern="priority-columns">

                                        <table id="userTable" class="table  table-striped">

                                            <thead>

                                                <tr>

                                                    <th>#</th>

                                                    <th data-priority="1">Vendor Name</th>

                                                    <th data-priority="3">Reward Name</th>

                                                    <th data-priority="3">Images</th>

                                                    <th data-priority="3">Weight</th>

                                                    <th data-priority="3">Status</th>

                                                    <th data-priority="6">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($rewards as $key => $reward)
                                                <tr>
                                                    <td>{{ ++$key }}</td>

                                                    <td>{{ $reward->vendor->first_name }}</td>

                                                    <td>{{ $reward->reward_name }}</td>

                                                    <td>

                                                        @if ($reward->reward_image != null)
                                                            <img src="{{ asset($reward->reward_image)}}" width="50px" height="50px" alt="">
                                                        @endif 

                                                   </td>
                                                   <td>{{ formatWeight($reward->vendor->orders->sum('total_order_weight')) }} </td>

                                                    <td> 
                                                        @if($reward->status == 1) 
                                                           <p class="label pull-right status-active">Applied</p>  
                                                        @elseif ($reward->status == 3) 
                                                           <p class="label pull-right status-inactive">Rejected</p> 
                                                        @else
                                                        <p class="label pull-right status-active">Accepted</p>  
                                                        @endif
                                                    </td>

                                                    <td>
                                                        
                                                        <div class="btn-group" id="btns<?php echo $key ?>">

                                                            @if ($reward->status != 2 && $reward->status != 3)

                                                            <a href="{{route('reward.status',['accepted',base64_encode($reward->id)])}}" data-toggle="tooltip" data-placement="top" title="Accepted">Accepted</a>

                                                            <a href="{{route('reward.status',['rejected',base64_encode($reward->id)])}}" data-toggle="tooltip" data-placement="top" title="Rejected">Rejected </a>
                                                         
                                                            @endif

                                                         
                                                            {{-- <a href="javascript:();" class="dCnf" mydata="<?php echo $key ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a> --}}

                                                        </div>

                                                        {{-- <div style="display:none" id="cnfbox<?php echo $key ?>">
                                                            <p> Are you sure delete this </p>
                                                            <a href="{{route('reward.destroy', base64_encode($reward->id))}}" class="btn btn-danger">Yes</a>
                                                            <a href="javascript:();" class="cans btn btn-default" mydatas="<?php echo $key ?>">No</a>
                                                        </div> --}}
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

