@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">Dealer's Enquiry</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Dealer's Enquiry</a></li>

                            <li class="breadcrumb-item active">View Dealer List</li>

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

                                    <div class="col-md-12"> <h4 class="mt-0 header-title">View Users</h4> </div>

                                </div>


                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <div class="table-rep-plugin">

                                    <div class="table-responsive b-0" data-pattern="priority-columns">

                                        <table id="userTable" class="table  table-striped">

                                            <thead>

                                                <tr>

                                                    <th>#</th>

                                                    <th data-priority="1">Name</th>

                                                    <th data-priority="3">Age</th>

                                                    <th data-priority="1">Qualification</th>

                                                    <th data-priority="6">City</th>

                                                    <th data-priority="6">District</th>

                                                    <th data-priority="6">State</th>

                                                    <th data-priority="6">Mobile</th>

                                                    <th data-priority="6">Firm name</th>

                                                    <th data-priority="6">Firm Address</th>

                                                    <th data-priority="6">Business name</th>

                                                    <th data-priority="6">Business Experience</th>

                                                    <th data-priority="6">Business Type</th>

                                                    <th data-priority="6">Annual Turnover</th>

                                                    <th data-priority="6">Curr Bussberif</th>

                                                    <th data-priority="6">Infra</th>

                                                    <th data-priority="6">Vehicle</th>

                                                    <th data-priority="6">invest</th>

                                                    <th data-priority="6">ManPower</th>

                                                    <th data-priority="6">Ref. Name</th>

                                                    <th data-priority="6">Ref. City</th>

                                                    <th data-priority="6">Answer</th>

                                                    <th data-priority="6">File</th>

                                                    <th data-priority="6">Date</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($dealers as $key => $dealer)
                                                <tr>
                                                    <td>{{ ++$key }}</td>

                                                    <td>{{ $dealer->name }}</td>

                                                    <td>{{ $dealer->age }}</td>

                                                    <td>{{ $dealer->qualification }}</td>

                                                    <td>{{ $dealer->city }}</td>

                                                    <td>{{ $dealer->district }}</td>

                                                    <td>{{ $dealer->state }}</td>

                                                    <td>{{ $dealer->phone }}</td>

                                                    <td>{{ $dealer->firmname }}</td>

                                                    <td>{{ $dealer->firmaddress }}</td>

                                                    <td>{{ $dealer->businessname }}</td>

                                                    <td>{{ $dealer->businessexperience }}</td>

                                                    <td>{{ $dealer->businesstype }}</td>

                                                    <td>{{ $dealer->annualturnover }}</td>

                                                    <td>{{ $dealer->currbussberif }}</td>

                                                    <td>{{ $dealer->infra }}</td>

                                                    <td>{{ $dealer->vehicle }}</td>

                                                    <td>{{ $dealer->invest }}</td>

                                                    <td>{{ $dealer->manpower }}</td>

                                                    <td>{{ $dealer->ref_name }}</td>

                                                    <td>{{ $dealer->ref_city_name }}</td>

                                                    <td>{{ $dealer->ans }}</td>

                                                    <td>{{ $dealer->file }}</td>

                                                    <td>{{ date('j F, Y, g:i a', strtotime($dealer->date))}}</td>

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
