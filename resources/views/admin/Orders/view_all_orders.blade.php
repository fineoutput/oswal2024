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
                                            <th>Order ID</th>

                                            {{-- <th data-priority="1">BY Admin</th> --}}

                                            <th data-priority="3">User</th>

                                            <th data-priority="3">Total Amount</th>

                                            <th data-priority="6">Promocode</th>

                                            <th data-priority="6">User Address</th>

                                            <th data-priority="6">Location Address</th>

                                            <th data-priority="6">User Mob.</th>

                                            <th data-priority="6">City</th>

                                            <th data-priority="6">State</th>

                                            <th data-priority="6">ZipCode</th>

                                            <th data-priority="6">Payment Type</th>

                                            <th data-priority="6">Cod Charge</th>

                                            <th data-priority="6">Order Status</th>

                                            <th data-priority="6">Delivery Status</th>

                                            <th data-priority="6">Order Track Id</th>

                                            <th data-priority="6">Rejected By</th>

                                            <th data-priority="6">Last Update Date</th>

                                            <th data-priority="6">Order Date</th>

                                            <th data-priority="6">Order Products</th>

                                            <th data-priority="6">Order From</th>

                                            <th data-priority="6">Order Rating</th>
                                            <th data-priority="6">Rating Description</th>

                                            <th data-priority="6">Gift</th>

                                            <th data-priority="6">Gift 1</th>

                                            <th data-priority="6">Remark</th>
                                            <th data-priority="7">Order Weight</th>
                                            <th data-priority="7">Order Status</th>

                                            <th data-priority="8">Action</th>

                                        </tr>

                                    </thead>

                                    <tbody>



                                        @foreach ($orders as $key => $order)

                                        <tr>
                                            <td>{{ ++$key }}</td>

                                            {{-- <td>{{ $order->user->first_name }}</td> --}}

                                            <td>{{ $order->id ?? '' }}</td>
                                            <td>{{ $order->user->first_name }}</td>

                                            <td> {{ formatPrice($order->total_amount) }}</td>

                                            <td>
                                                @if ($order->promocodes != null && $order->promocodes != '' )

                                                {{ $order->promocodes->promocode }}

                                                @endif
                                            </td>
                                            @php
                                            $custom_address = optional($order->address)->doorflat . ' ' .
                                            optional($order->address)->landmark . ' ' .
                                            optional($order->address)->address . ' ' .
                                            optional($order->address)->zipcode;
                                            @endphp

                                            <td> {{ $custom_address ?? '' }}</td>

                                            <td> {{ $order->address->location_address ?? '' }}</td>

                                            <td> {{ $order->user->contact ?? '' }}</td>

                                            <td> {{ $order->address->citys->city_name ?? '' }}</td>

                                            <td> {{ $order->address->states->state_name ?? '' }}</td>

                                            <td> {{ $order->address->zipcode ?? '' }}</td>

                                            <td>
                                                @if ($order->payment_type == 1)
                                                Cash On Delivery
                                                @else
                                                Online Payment
                                                @endif
                                            </td>

                                            <td> {{ $order->cod_charge ?? '' }}</td>

                                            <td>
                                                @if ($order->order_status == 1)
                                                <span class="label label-primary"
                                                    style="font-size:13px;">New Order</span>
                                                @elseif ($order->order_status == 2)
                                                <span class="label label-success"
                                                    style="font-size:13px;">Accepted</span>
                                                @elseif ($order->order_status == 3)
                                                <span class="label label-info"
                                                    style="font-size:13px;">Dispatched</span>
                                                @elseif ($order->order_status == 4)
                                                <span class="label label-success"
                                                    style="font-size:13px;">Delivered</span>
                                                @elseif ($order->order_status == 5)
                                                <span class="label label-danger"
                                                    style="font-size:13px;">Rejected</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($order->delivery_status == 0)
                                                <span class="label label-warning" style="font-size:13px;">None</span>
                                                @elseif ($order->delivery_status == 1)
                                                @php
                                                // Check if the transferOrder exists before accessing it
                                                $deliveryBoy = $order->transferOrder ? $order->transferOrder->deliveryBoy : null;
                                                @endphp
                                                @if ($deliveryBoy)
                                                Transfered To ({{$deliveryBoy->name}})
                                                @else
                                                <span class="label label-danger" style="font-size:13px;">Transfer not found</span>
                                                @endif
                                                @elseif ($order->delivery_status == 2)
                                                @php
                                                // Check if the transferOrder exists before accessing it
                                                $deliveryBoy = $order->transferOrder ? $order->transferOrder->deliveryBoy : null;
                                                @endphp
                                                @if ($deliveryBoy)
                                                <span class="label label-info" style="font-size:13px;">
                                                    Accepted By ({{$deliveryBoy->name}})
                                                </span>
                                                @else
                                                <span class="label label-danger" style="font-size:13px;">Delivery Boy not found</span>
                                                @endif
                                                @elseif ($order->delivery_status == 3)
                                                <span class="label label-success" style="font-size:13px;">Delivered</span>
                                                @endif
                                            </td>

                                            <td> {{ $order->track_id ?? '' }}</td>

                                            <td>
                                                {{ getRejectedByDetails($order->rejected_by, $order->rejected_by_id) }}
                                            </td>

                                            <td>

                                                @php
                                                $newDate = \Carbon\Carbon::parse($order->last_update_date);
                                                @endphp
                                                {{ $newDate->format('j F, Y, g:i a') }}

                                            </td>

                                            <td>

                                                @php
                                                $newDate = \Carbon\Carbon::parse($order->date);
                                                @endphp
                                                {{ $newDate->format('j F, Y, g:i a') }}

                                            </td>

                                            <td>
                                                @if ($order->orderDetails->count() > 0)
                                                @foreach ($order->orderDetails as $index => $order2pro)
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
                                                $output = \Illuminate\Support\Str::limit($output, 50);
                                                @endphp

                                                {!! $output !!}
                                                @if ($index < $order->orderDetails->count() - 1)
                                                    ,<br>
                                                    @endif
                                                    @endforeach
                                                    @else
                                                    N/A
                                                    @endif
                                            </td>


                                            <td> {{ $order->order_from ?? '' }}</td>
                                            <td> @if($order->average_rating == '5')
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                @elseif ($order->average_rating == '4')
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                @elseif ($order->average_rating == '3')
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                @elseif ($order->average_rating == '2')
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                @elseif ($order->average_rating == '1')
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                @else
                                                No Rating Found
                                                @endif
                                            </td>
                                            <td>{{$order->ratingdescriptions ?? ''}}</td>

                                            <td>
                                                @if($order->gift_id != null && $order->gift_id != '' && $order->gift_id != 0)
                                                {{ $order->gift->name }}
                                                @else
                                                No Gift Card
                                                @endif
                                            </td>

                                            <td>
                                                @if($order->gift1_id != null && $order->gift1_id != '' && $order->gift1_id != 0 && $order->gift1 != null)
                                                {{ $order->gift1->name }}
                                                @else
                                                No Gift Card
                                                @endif
                                            </td>

                                            <td>{{ $order->remarks ?? '' }}</td>
                                            <td>{{ $order->total_order_weight ?? '' }}</td>
                                            <td>
                                                @if($order->order_status == 1)
                                                New Order
                                                @elseif($order->order_status == 2)
                                                Accept Order Confirm
                                                @elseif($order->order_status == 3)
                                                Dispatch Order
                                                @elseif($order->order_status == 4)
                                                Completed Order
                                                @else
                                                Rejected Order
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
                                                                <a href="{{ route('order.update-status', ['id' => base64_encode($order->id), 'status' => base64_encode(5)]) }}">
                                                                    Reject
                                                                </a>
                                                            </li>

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

                                                            <li>
                                                                <a href="{{ route('order.view-product', ['id' => base64_encode($order->id)]) }}">
                                                                    View Products
                                                                </a>
                                                            </li>

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