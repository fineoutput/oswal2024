@extends('admin.base_template')
@section('main')
<style>
    page-title-box {
        background-image: url("..\images\bg.jpg") !important;
    }
</style>


<!-- Start content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">Welcome to {{config('constants.options.SITE_NAME')}} Dashboard</li>
                    </ol>


                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="page-content-wrapper">
            <div class="row">
                
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger mini-stat position-relative">
                        <a href="#">
                            <div class="card-body">
                                <div class="mini-stat-desc">
                                    <h6 class="text-uppercase verti-label text-white-50">Address Visits</h6>
                                    <div class="text-white">
                                        <h6 class="text-uppercase mt-0 text-white-50">Address Visits</h6>
                                        <h3 class="mb-3 mt-0">{{$address ?? 0}}</h3>
                                        <div class="">
                                            <span class="ml-2">Address Visits</span>
                                        </div>
                                    </div>
                                    <div class="mini-stat-icon">
                                        <i class="mdi mdi-shopping display-2"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger mini-stat position-relative">
                        <a href="#">
                            <div class="card-body">
                                <div class="mini-stat-desc">
                                    <h6 class="text-uppercase verti-label text-white-50">Cart Additions</h6>
                                    <div class="text-white">
                                        <h6 class="text-uppercase mt-0 text-white-50">Cart Additions</h6>
                                        <h3 class="mb-3 mt-0">{{$addtocart ?? 0}}</h3>
                                        <div class="">
                                            <span class="ml-2">Cart Additions</span>
                                        </div>
                                    </div>
                                    <div class="mini-stat-icon">
                                        <i class="mdi mdi-shopping display-2"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger mini-stat position-relative">
                        <a href="#">
                            <div class="card-body">
                                <div class="mini-stat-desc">
                                    <h6 class="text-uppercase verti-label text-white-50">Cart Visits</h6>
                                    <div class="text-white">
                                        <h6 class="text-uppercase mt-0 text-white-50">Cart Visits</h6>
                                        <h3 class="mb-3 mt-0">{{$viewcart ?? 0}}</h3>
                                        <div class="">
                                            <span class="ml-2">Cart Visits</span>
                                        </div>
                                    </div>
                                    <div class="mini-stat-icon">
                                        <i class="mdi mdi-shopping display-2"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger mini-stat position-relative">
                        <a href="#">
                            <div class="card-body">
                                <div class="mini-stat-desc">
                                    <h6 class="text-uppercase verti-label text-white-50">WEBSITE USERS</h6>
                                    <div class="text-white">
                                        <h6 class="text-uppercase mt-0 text-white-50">WEBSITE USERS</h6>
                                        <h3 class="mb-3 mt-0">{{$VisitedUsers}}</h3>
                                        <div class="">
                                            <span class="ml-2">WEBSITE USERS</span>
                                        </div>
                                    </div>
                                    <div class="mini-stat-icon">
                                        <i class="mdi mdi-shopping display-2"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger mini-stat position-relative">
                        <a href="#">
                            <div class="card-body">
                                <div class="mini-stat-desc">
                                    <h6 class="text-uppercase verti-label text-white-50">Checkout</h6>
                                    <div class="text-white">
                                        <h6 class="text-uppercase mt-0 text-white-50">Checkout</h6>
                                        <h3 class="mb-3 mt-0">{{$checkout ?? 0}}</h3>
                                        <div class="">
                                            <span class="ml-2">Checkout</span>
                                        </div>
                                    </div>
                                    <div class="mini-stat-icon">
                                        <i class="mdi mdi-shopping display-2"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger mini-stat position-relative">
                        <a href="#">
                            <div class="card-body">
                                <div class="mini-stat-desc">
                                    <h6 class="text-uppercase verti-label text-white-50">TODAY`S MOBILE USERS</h6>
                                    <div class="text-white">
                                        <h6 class="text-uppercase mt-0 text-white-50">TODAY`S MOBILE USERS</h6>
                                        <h3 class="mb-3 mt-0">0</h3>
                                        <div class="">
                                            <span class="ml-2">TODAY`S MOBILE USERS</span>
                                        </div>
                                    </div>
                                    <div class="mini-stat-icon">
                                        <i class="mdi mdi-shopping display-2"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger mini-stat position-relative">
                        <a href="#">
                            <div class="card-body">
                                <div class="mini-stat-desc">
                                    <h6 class="text-uppercase verti-label text-white-50">TODAY`S WEB USERS</h6>
                                    <div class="text-white">
                                        <h6 class="text-uppercase mt-0 text-white-50">TODAY`S WEB USERS</h6>
                                        <h3 class="mb-3 mt-0">{{$VisitedUsersToday ?? 0}}</h3>
                                        <div class="">
                                            <span class="ml-2">TODAY`S WEB USERS</span>
                                        </div>
                                    </div>
                                    <div class="mini-stat-icon">
                                        <i class="mdi mdi-shopping display-2"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger mini-stat position-relative">
                        <a href="#">
                            <div class="card-body">
                                <div class="mini-stat-desc">
                                    <h6 class="text-uppercase verti-label text-white-50">TODAY`S ORDERS</h6>
                                    <div class="text-white">
                                        <h6 class="text-uppercase mt-0 text-white-50">TODAY`S ORDERS</h6>
                                        <h3 class="mb-3 mt-0">0</h3>
                                        <div class="">
                                            <span class="ml-2">TODAY`S ORDERS</span>
                                        </div>
                                    </div>
                                    <div class="mini-stat-icon">
                                        <i class="mdi mdi-shopping display-2"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div> 

                <div class="col-12 table-rep-plugin">

                    <div class="row">

                        <div class="col-12"> <h4 class="mt-0 header-title">Latest Page Views</h4> </div>

                    </div>

                    <div class="table-responsive b-0" data-pattern="priority-columns">

                        <table id="" class="table  table-striped">

                            <thead>

                                <tr>

                                    <th>#</th>

                                    <th data-priority="1">Browser</th>

                                    <th data-priority="3">Came From</th>

                                    <th data-priority="3">Platform</th>

                                    <th data-priority="6">Full Detail</th>

                                    <th data-priority="6">Date</th>

                                    <th data-priority="6">Web/Mobile</th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>
                                    <td>1</td>

                                    <td>Chrome	</td>

                                    <td> https://www.fineoutput.co.in/oswalwebsite/ </td>

                                    <td> Unknown Windows OS </td>

                                    <td>Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36 </td>

                                    <td> 2024-08-16 11:13:38 </td>

                                    <td> WebSite </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

{{-- /////////////////                 --}}
                <div class="col-12 table-rep-plugin mt-5">

                    <div class="row">

                        <div class="col-12"> <h4 class="mt-0 header-title">Category Views</h4> </div>

                    </div>

                    <div class="table-responsive b-0" data-pattern="priority-columns">

                        <table id="" class="table  table-striped">
                            <thead>
                                <tr>
                                    <th>Category id</th>
                                    <th>Category Name</th>
                                    <th>Category Count</th>
                                </tr>
                            </thead>

                        <tbody>
                            @foreach ($categoryData as $value)
                            <tr>
                                <td>#{{$value['id'] ?? ''}}</td>
                                <td>{{$value['name'] ?? ''}}</td>
                                <td>{{$value['visit_count'] ?? 0}}</td>
                            </tr>
                        @endforeach
                  
                        </tbody>


                        </table>

                    </div>

                </div>

                {{-- ////////////// --}}

            </div>

            <!-- end page content-->
        </div> <!-- container-fluid -->
    </div> <!-- content -->
</div> <!-- content -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#userTable').DataTable({
            responsive: true,
        });
        $(document.body).on('click', '.dCnf', function() {
            var i = $(this).attr("mydata");
            console.log(i);
            $("#btns" + i).hide();
            $("#cnfbox" + i).show();
        });
        $(document.body).on('click', '.cans', function() {
            var i = $(this).attr("mydatas");
            console.log(i);
            $("#btns" + i).show();
            $("#cnfbox" + i).hide();
        })
    });
</script>

@endsection