@extends('admin.base_template')

@section('main')
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View {{ $pageTitle }}</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">{{ $pageTitle }}</a></li>

                            <li class="breadcrumb-item active">View {{ $pageTitle }}</li>

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

                                    <div class="col-md-9">
                                        <h4 class="mt-0 header-title">View {{ $pageTitle }}</h4>
                                    </div>

                                    {{-- <div class="col-md-2"> 

                                        <a class="btn btn-info cticket" href="{{ route('orderfirst.create') }}" role="button" style="margin-left: 20px;"> Add order</a>

                                    </div> --}}

                                </div>


                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <div class="table-rep-plugin">

                                    <div class="table-responsive b-0" data-pattern="priority-columns">

                                        <table id="userTable" class="table  table-striped">

                                            <thead>

                                                <tr>

                                                    <th>#</th>

                                                    <th data-priority="1">Ids</th>

                                                    <th data-priority="3">User</th>

                                                    <th data-priority="6">User Address</th>

                                                    <th data-priority="6">Lat./Long.</th>

                                                    <th data-priority="6">DoorFlat</th>

                                                    <th data-priority="6">User Mob.</th>

                                                    <th data-priority="6">City</th>

                                                    <th data-priority="6">State</th>

                                                    <th data-priority="6">ZipCode</th>

                                                    <th data-priority="6">Payment Type</th>

                                                    <th data-priority="6">Delivery Status</th>

                                                    <th data-priority="6">Expected Delivery Date</th>

                                                    <th data-priority="6">Order Products</th>

                                                    <th data-priority="6">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($transferOrders as $key => $order)
                                                 @php
                                                   $order->load('orders');
                                                   $address = $order->orders->load('address')->address;
                                                   $user = $order->orders->load('user')->user;
                                                   $orderDetails= $order->orders->load('orderDetails')->orderDetails;
                                                   $promocodes= $order->orders->load('promocodes')->promocodes;
                                                //    dd($order->orders->delivery_status);
                                                 @endphp
                                                    <tr>
                                                        <td>{{ ++$key }}</td>

                                                        <td>OrderId-{{ $order->orders->id }}, AddressId - {{  $address->id }}</td>

                                                        <td>{{  $user->first_name ?? '' }}</td>

                                                        @php
                                                            $custom_address =
                                                                $address->doorflat .
                                                                ' ' .
                                                                $address->landmark .
                                                                ' ' .
                                                                $address->address .
                                                                ' ' .
                                                                $address->location_address .
                                                                ' ' .
                                                                $address->zipcode;
                                                        @endphp
                                                        <td> {{ $custom_address }}</td>

                                                        <td> {{ $address->latitude .' - '.$address->longitude }}</td>

                                                        <td> {{ $address->doorflat ?? '' }}</td>

                                                        <td> {{ $user->contact ?? '' }}</td>

                                                        <td> {{ $address->citys->city_name ?? '' }}</td>

                                                        <td> {{ $address->states->state_name ?? '' }}</td>

                                                        <td> {{ $address->zipcode ?? '' }}</td>

                                                        <td>
                                                            @if ($order->orders->payment_type == 1)
                                                                Cash On Delivery
                                                            @else
                                                                Online Payment
                                                            @endif
                                                        </td>
                                                        
                                                        <td>
                                                            @if ($order->orders->delivery_status == 0)
                                                                <span class="label label-warning"
                                                                    style="font-size:13px;">None</span>
                                                            @elseif ($order->orders->delivery_status == 1)
                                                              Transfered To ({{$dbname->name}}) 
                                                            @elseif ($order->orders->delivery_status == 2)
                                                            <span class="label label-info" style="font-size:13px;">
                                                                Accepted By ({{$dbname->name}})
                                                                </span>
                                                            @elseif ($order->orders->delivery_status == 3)
                                                                <span class="label label-success"
                                                                    style="font-size:13px;">Delivered</span>
                                                            @endif
                                                        </td>

                                                        <td>

                                                            @php
                                                                $newDate = \Carbon\Carbon::parse($order->last_update_date);
                                                            @endphp
                                                            {{ $newDate->format('j F, Y, g:i a') }}

                                                        </td>


                                                        <td>

                                                            @if ($orderDetails->count() > 0)
                                                                @foreach ($orderDetails as $index => $order2pro)
                                                                    @php
                                                                        $typeName = $order2pro->type
                                                                            ? $order2pro->type->type_name
                                                                            : '';
                                                                        $productName = $order2pro->product
                                                                            ? $order2pro->product->name
                                                                            : '';
                                                                        $quantity = $order2pro->quantity;
                                                                        $output = $productName
                                                                            ? "{$productName} ({$typeName} x {$quantity})"
                                                                            : 'N/A';
                                                                    @endphp

                                                                    {{ $output }}

                                                                    @if ($index < $orderDetails->count() - 1)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                N/A
                                                            @endif

                                                        </td>


                                                       <td>
                                                        <div class="btn-group" id="btns{{ $key }}">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                                    Action <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu" role="menu">
                                                    
                                                                    @if ($order->order_status == 1)
                                                                        <li>
                                                                            <a href="{{ route('order.update-status', ['id' => base64_encode($order->id), 'status' => base64_encode(2)]) }}">
                                                                                Accept Order Confirm
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ route('order.update-status', ['id' => base64_encode($order->id), 'status' => base64_encode(5)]) }}">
                                                                                Reject
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                    
                                                                    @if ($order->order_status == 2)
                                                                        <li>
                                                                            <a href="{{ route('order.update-status', ['id' => base64_encode($order->id), 'status' => base64_encode(3)]) }}">
                                                                                Dispatch Order
                                                                            </a>
                                                                        </li>
                                                    
                                                                        {{-- @if ($order->delivery_status == 0)
                                                                            <li>
                                                                                <a href="{{ route('orders.transferToDeliver', ['id' => base64_encode($order->id)]) }}">
                                                                                    Transfer Order To Delivery User
                                                                                </a>
                                                                            </li>
                                                                        @endif --}}
                                                                    @endif
                                                    
                                                                    @if ($order->order_status == 3)
                                                                        <li>
                                                                            <a href="{{ route('order.update-status', ['id' => base64_encode($order->id), 'status' => base64_encode(4)]) }}">
                                                                                Deliver Order
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                    
                                                                    @if ($dbname->role_type == 2)

                                                                    <li>
                                                                        <a href="{{ route('order.vendor.view-product', ['id' => base64_encode($order->orders->id)]) }}">
                                                                            View Products
                                                                        </a>
                                                                    </li>

                                                                    @else

                                                                    <li>
                                                                        <a href="{{ route('order.view-product', ['id' => base64_encode($order->orders->id)]) }}">
                                                                            View Products
                                                                        </a>
                                                                    </li>
                                                                    @endif
                                                    
                                                                    <li>
                                                                        <a href="{{ route('order.view-bill', ['id' => base64_encode($order->id)]) }}">
                                                                            View Bill
                                                                        </a>
                                                                    </li>
                                                    
                                                                    <li>
                                                                        <a href="{{ route('order.view-delivery-challan', ['id' => base64_encode($order->id)]) }}">
                                                                            View Delivery Challan
                                                                        </a>
                                                                    </li>
                                                    
                                                                    {{-- @if (empty($order->track_id))
                                                                        <li>
                                                                            <a href="{{ route('order.addTrackOrderView', ['id' => base64_encode($order->id)]) }}">
                                                                                Track Order
                                                                            </a>
                                                                        </li>
                                                                    @else
                                                                        <li>
                                                                            <a href="{{ route('order.updateTrackOrderView', ['id' => base64_encode($order->id)]) }}">
                                                                                Update Track Order
                                                                            </a>
                                                                        </li>
                                                                    @endif --}}
                                                    
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    
                                                        <div style="display:none" id="cnfbox{{ $key }}">
                                                            <p> Are you sure you want to delete this? </p>
                                                            <a href="{{ route('order.destroy', ['id' => base64_encode($order->id)]) }}" class="btn btn-danger">
                                                                Yes
                                                            </a>
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
@endsection
