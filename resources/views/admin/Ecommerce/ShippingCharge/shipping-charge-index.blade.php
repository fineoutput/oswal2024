@extends('admin.base_template')

@section('main')
<style>
    .table-responsive {
        overflow-x: auto;
    }

    .table-responsive input[type="text"] {
        width: auto;
        min-width: 50px; 
        max-width: 100%;
    }

    .table-responsive th, .table-responsive td {
        white-space: nowrap;
    }
</style>
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View Shipping charge</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Shipping charge</a></li>

                            <li class="breadcrumb-item active">View Shipping charge</li>

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

                                    <div class="col-md-6"> <h4 class="mt-0 header-title">View Shipping charge List</h4> </div>

                                    <div class="col-md-2"> 

                                        <a class="btn btn-info cticket" href="{{ route('shipping-charge.create') }}" role="button" style="margin-left: 20px;"> Add Shipping_charge</a>

                                    </div>

                                    <div class="col-md-1"> 

                                        <a class="btn btn-info cticket" href="{{ route('shipping-charge.create-city') }}" role="button" style="margin-left: 20px;"> Add City</a>

                                    </div>

                                    <div class="col-md-3"> 

                                        <a class="btn btn-info cticket" href="{{ route('shipping-charge.set-all-shipping-charges') }}" role="button" style="margin-left: 20px;"> Add All Shiping Charges</a>

                                    </div>
                                    
                                </div>


                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <div class="table-rep-plugin">

                                    <div class="table-responsive b-0" data-pattern="priority-columns">

                                        <table id="userTable" class="table  table-striped">

                                            <thead>

                                                <tr>

                                                    <th>#</th>

                                                    <th data-priority="1">State</th>

                                                    <th data-priority="2">City</th>

                                                    <th data-priority="3">Weight 1</th>

                                                    <th data-priority="6">Shipping Charge 1</th>

                                                    <th data-priority="3">Weight 2</th>

                                                    <th data-priority="6">Shipping Charge 2</th>

                                                    <th data-priority="3">Weight 3</th>

                                                    <th data-priority="6">Shipping Charge 3</th>

                                                    <th data-priority="3">Weight 4</th>

                                                    <th data-priority="6">Shipping Charge 4</th>

                                                    <th data-priority="3">Weight 5</th>
                                                    
                                                    <th data-priority="6">Shipping Charge 5</th>

                                                    <th data-priority="3">Weight 6</th>

                                                    <th data-priority="6">Shipping Charge 6</th>

                                                    <th data-priority="3">Status</th>

                                                    <th data-priority="3">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($shippingCharges as $key => $shippingCharge)
                                                <tr>
                                                    <td class="text-center">{{ ++$key }}</td>

                                                    <td class="text-center">{{ $shippingCharge->state->state_name ?? '' }}</td>

                                                    <td class="text-center">{{ $shippingCharge->city->city_name ?? '' }}</td>

                                                    <td class="text-center">{{ $shippingCharge->weight1 }}</td>
                                                    
                                                    <td class="text-center">{{ $shippingCharge->shipping_charge1 }}</td>

                                                    <td class="text-center">{{ $shippingCharge->weight2 }}  </td>

                                                    <td class="text-center">{{ $shippingCharge->shipping_charge2 }}  </td>

                                                    <td class="text-center">{{ $shippingCharge->weight3 }}  </td>

                                                    <td class="text-center">{{ $shippingCharge->shipping_charge3 }}  </td>

                                                    <td class="text-center">{{ $shippingCharge->weight4 }}  </td>

                                                    <td class="text-center">{{ $shippingCharge->shipping_charge4 }}  </td>

                                                    <td class="text-center">{{ $shippingCharge->weight5 }}  </td>

                                                    <td class="text-center">{{ $shippingCharge->shipping_charge5 }}  </td>

                                                    <td class="text-center">{{ $shippingCharge->weight6 }}  </td>

                                                    <td class="text-center">{{ $shippingCharge->shipping_charge6 }}  </td>
                                                    
                                                    <td class="text-center"> 
                                                        @if($shippingCharge->is_active == 1)  
                                                           <p class="label pull-right status-active">Active</p>  
                                                        @else 
                                                           <p class="label pull-right status-inactive">InActive</p> 
                                                        @endif
                                                    </td>

                                                    <td>
                                                        
                                                        <div class="btn-group" id="btns<?php echo $key ?>">

                                                            @if ($shippingCharge->is_active == 0)

                                                                <a href="{{route('shipping-charge.update-status',['active',base64_encode($shippingCharge->id)])}}" data-toggle="tooltip" data-placement="top" title="Active"><i class="fas fa-check success-icon"></i></a>

                                                            @else

                                                                <a href="{{route('shipping-charge.update-status',['inactive',base64_encode($shippingCharge->id)])}}" data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fas fa-times danger-icon"></i></a>

                                                            @endif

                                                            <a href="{{route('shipping-charge.edit',base64_encode($shippingCharge->id))}}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>

                                                            <a href="javascript:();" class="dCnf" mydata="<?php echo $key ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>

                                                        </div>

                                                        <div style="display:none" id="cnfbox<?php echo $key ?>">
                                                            <p> Are you sure delete this </p>
                                                            <a href="{{route('shipping-charge.destroy', base64_encode($shippingCharge->id))}}" class="btn btn-danger">Yes</a>
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
