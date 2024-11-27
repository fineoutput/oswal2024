@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View Career</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Career</a></li>

                            <li class="breadcrumb-item active">View Career</li>

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

                                                    <th data-priority="1">First Name</th>

                                                    <th data-priority="1">Last Name</th>

                                                    <th data-priority="3">Email</th>

                                                    <th data-priority="3">Phone</th>

                                                    <th data-priority="1">Message</th>

                                                    <th data-priority="1">Date</th>

                                                    <th data-priority="6">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($careers as $key => $career)
                                                <tr>
                                                    <td>{{ ++$key }}</td>

                                                    <td>{{ $career->fname }}</td>

                                                    <td>{{ $career->lname }}</td>

                                                    <td>{{ $career->email }}</td>

                                                    <td>{{ $career->phone }}</td>

                                                    <td>{{ $career->message }}</td>

                                                    <td> {{ $career->created_at }} </td>

                                                    <td>
                                                        
                                                        <div class="btn-group" id="btns<?php echo $key ?>">

                                                            <a href="javascript:();" class="dCnf" mydata="<?php echo $key ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>

                                                        </div>

                                                        <div style="display:none" id="cnfbox<?php echo $key ?>">
                                                            <p> Are you sure delete this </p>
                                                            <a href="{{route('home.destroy', base64_encode($career->id))}}" class="btn btn-danger">Yes</a>
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
