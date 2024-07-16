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

                        <h4 class="page-title">View Cart</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Cart</a></li>

                            <li class="breadcrumb-item active">View Cart</li>

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

                                <div class="row">

                                    <div class="col-md-6"> <h4 class="mt-0 header-title">View Cart List</h4> </div>
                                    
                                </div>


                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <div class="table-rep-plugin">

                                    <div class="table-responsive b-0" data-pattern="priority-columns">

                                        <table id="userTable" class="table  table-striped">

                                            <thead>

                                                <tr>

                                                    <th>#</th>

                                                    <th data-priority="1">User</th>

                                                    <th data-priority="2">Product</th>

                                                    <th data-priority="3">Type</th>

                                                    <th data-priority="6">Type Price</th>

                                                    <th data-priority="3">Quantity</th>

                                                    <th data-priority="6">Total</th>

                                                    <th data-priority="3">Checkout Status</th>

                                                    <th data-priority="6">Date</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($carts as $key => $value)
                                                
                                                <tr>
                                                    <td class="text-center">{{ ++$key }}</td>

                                                    <td class="text-center">{{ $shippingCharge->state->state_name }}</td>

                                                    <td class="text-center">{{ $shippingCharge->city->city_name }}</td>

                                                    <td class="text-center">{{ $shippingCharge->weight1 }}</td>
                                                    
                                                    <td class="text-center">{{ $shippingCharge->shipping_charge1 }}</td>

                                                    <td class="text-center">{{ $shippingCharge->weight2 }}  </td>

                                                    <td class="text-center">{{ $shippingCharge->shipping_charge2 }}  </td>

                                                    <td class="text-center">{{ $shippingCharge->weight3 }}  </td>

                                                    <td class="text-center">{{ $shippingCharge->shipping_charge3 }}  </td>

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
