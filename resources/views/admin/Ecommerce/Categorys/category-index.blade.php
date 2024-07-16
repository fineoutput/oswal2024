@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View Category</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Category</a></li>

                            <li class="breadcrumb-item active">View Category</li>

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

                                    <div class="col-md-10"> <h4 class="mt-0 header-title">View Category List</h4> </div>

                                    <div class="col-md-2"> 

                                        <a class="btn btn-info cticket" href="{{ route('category.create') }}" role="button" style="margin-left: 20px;"> Add Category</a>

                                    </div>

                                </div>


                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <div class="table-rep-plugin">

                                    <div class="table-responsive b-0" data-pattern="priority-columns">

                                        <table id="userTable" class="table  table-striped">

                                            <thead>

                                                <tr>

                                                    <th>#</th>

                                                    <th data-priority="1">Name</th>

                                                    <th data-priority="3">Short Description</th>

                                                    <th data-priority="1">Long Description</th>

                                                    <th data-priority="3">Sequence</th>

                                                    <th data-priority="3">Image</th>

                                                    <th data-priority="3">App Image</th>

                                                    <th data-priority="6">Slide Image1</th>

                                                    <th data-priority="6">Slide Image2</th>

                                                    <th data-priority="6">Slide Image3</th>

                                                    <th data-priority="6">Status</th>

                                                    <th data-priority="6">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($categorys as $key => $category)
                                                <tr>
                                                    <td>{{ ++$key }}</td>

                                                    <td>{{ $category->name }}</td>

                                                    <td>{{ $category->short_disc }}</td>

                                                    <td>{{ $category->long_desc }}</td>

                                                    <td>{{ $category->sequence }}</td>

                                                    <td> <img src="{{asset($category->image)}}" alt="" width="100px" height="100px"> </td>
                                                    
                                                    <td> <img src="{{asset($category->app_image)}}" alt="" width="100px" height="100px"></td>
                                                    
                                                    <td>
                                                        @if($category->slide_img1)
                                                            <img src="{{ asset($category->slide_img1) }}" alt="" width="100px" height="100px">
                                                        @else
                                                            image not found
                                                        @endif
                                                    </td>
                                                    
                                                    <td>
                                                        @if($category->slide_img2 )
                                                            <img src="{{ asset($category->slide_img2) }}" alt="" width="100px" height="100px">
                                                        @else
                                                            image not found
                                                        @endif
                                                    </td>
                                                    
                                                    <td>
                                                        @if($category->slide_img3 )
                                                            <img src="{{ asset($category->slide_img3) }}" alt="" width="100px" height="100px">
                                                        @else
                                                            image not found
                                                        @endif
                                                    </td>
                                                    
                                                    <td> 
                                                        @if($category->is_active == 1)  
                                                           <p class="label pull-right status-active">Active</p>  
                                                        @else 
                                                           <p class="label pull-right status-inactive">InActive</p> 
                                                        @endif
                                                    </td>

                                                    <td>
                                                        
                                                        <div class="btn-group" id="btns<?php echo $key ?>">

                                                            @if ($category->is_active == 0)

                                                            <a href="{{route('category.update-status',['active',base64_encode($category->id)])}}" data-toggle="tooltip" data-placement="top" title="Active"><i class="fas fa-check success-icon"></i></a>

                                                            @else

                                                            <a href="{{route('category.update-status',['inactive',base64_encode($category->id)])}}" data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fas fa-times danger-icon"></i></a>

                                                            @endif

                                                            <a href="{{route('category.create',[base64_encode($category->id)])}}" data-toggle="tooltip" data-placement="top" title="Active"><i class="fas fa-edit"></i></a>

                                                            <a href="javascript:();" class="dCnf" mydata="<?php echo $key ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>

                                                        </div>

                                                        <div style="display:none" id="cnfbox<?php echo $key ?>">
                                                            <p> Are you sure delete this </p>
                                                            <a href="{{route('category.destroy', base64_encode($category->id))}}" class="btn btn-danger">Yes</a>
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
