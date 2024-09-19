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

                                    <div class="col-md-2"> 

                                        <a class="btn btn-info cticket" href="javascript:window.history.back();" role="button" style="margin-left: 20px;">Back</a>


                                    </div>

                                </div>


                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <div class="table-rep-plugin">

                                    <div class="table-responsive b-0" data-pattern="priority-columns">

                                        <table id="userTable" class="table  table-striped">

                                            <thead>

                                                <tr>

                                                    <th>#</th>

                                                    <th data-priority="1">Product</th>

                                                    <th data-priority="3">Type</th>

                                                    <th data-priority="3">Quentity</th>

                                                    <th data-priority="6">Amount</th>

                                                    <th data-priority="6">Combo Product</th>

                                                    <th data-priority="6">Date</th>


                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($orders as $key => $order)
                                                    <tr>
                                                        <td>{{ ++$key }}</td>

                                                        <td>{{ $order->product->name }}</td>

                                                        <td>{{ $order->vendortype->type_name }}</td>

                                                        <td> {{ $order->quantity }}</td>

                                                        <td> {{ $order->amount }}</td>
                                                  
                                                        <td> N/A</td>

                                                        <td>

                                                            @php
                                                                $newDate = \Carbon\Carbon::parse($order->date);
                                                            @endphp
                                                            {{ $newDate->format('j F, Y, g:i a') }}

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
