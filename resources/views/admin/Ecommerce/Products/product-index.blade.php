@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View Products</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>

                            <li class="breadcrumb-item active">View Products</li>

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

                                    <div class="col-md-8"> <h4 class="mt-0 header-title">View Products List</h4> </div>

                                    <div class="col-md-2"> 

                                        <a class="btn btn-info cticket" href="{{ route('product.create') }}" role="button" style="margin-left: 20px;"> Add Products</a>

                                    </div>

                                    <div class="col-md-2"> 

                                        <a class="btn btn-info cticket" href="{{ route('product.category') }}" role="button" style="margin-left: 20px;"> Back</a>

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

                                                    <th data-priority="3">Category</th>

                                                    <th data-priority="1">HSN Code</th>

                                                    <th data-priority="3">Description</th>

                                                    <th data-priority="3">Image 1</th>

                                                    <th data-priority="3">Hot Selling</th>

                                                    <th data-priority="3">Featured Product</th>

                                                    <th data-priority="6">Status</th>

                                                    <th data-priority="6">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($products as $key => $product)
                                                <tr>
                                                    <td>{{ ++$key }}</td>

                                                    <td>{{ $product->name }}</td>

                                                    <td>{{ $product->category->name }}</td>

                                                    <td>{{ $product->hsn_code }}</td>
                                                    
                                                    <td>{{ $product->long_desc }}</td>

                                                    <td> <img src="{{asset($product->img1)}}" alt="" width="100px" height="100px"> </td>

                                                    <td> 
                                                        @if($product->is_hot == 1)  
                                                           <p class="label pull-right status-active">Yes</p>  
                                                        @else 
                                                           <p class="label pull-right status-inactive">No</p> 
                                                        @endif
                                                    </td>

                                                    <td> 
                                                        @if($product->is_featured == 1)  
                                                           <p class="label pull-right status-active">Yes</p>  
                                                        @else 
                                                           <p class="label pull-right status-inactive">No</p> 
                                                        @endif
                                                    </td>
                                                    
                                                    
                                                    <td> 
                                                        @if($product->is_active == 1)  
                                                           <p class="label pull-right status-active">Active</p>  
                                                        @else 
                                                           <p class="label pull-right status-inactive">InActive</p> 
                                                        @endif
                                                    </td>

                                                    <td>
                                                        
                                                        <div class="btn-group" id="btns<?php echo $key ?>">

                                                            @if ($product->is_active == 0)

                                                            <a href="{{route('product.update-status',['active',base64_encode($product->id)])}}" data-toggle="tooltip" data-placement="top" title="Active"><i class="fas fa-check success-icon"></i></a>

                                                            @else

                                                            <a href="{{route('product.update-status',['inactive',base64_encode($product->id)])}}" data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fas fa-times danger-icon"></i></a>

                                                            @endif

                                                            <a href="{{route('product.create',base64_encode($product->id))}}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>

                                                            @if ( $product->product_view == 3 )
                                                                
                                                                <a href="{{route('type.index',['pid' => encrypt($product->id), 'cid' => encrypt($product->category_id),'pcid' => encrypt($product->product_category_id)])}}" data-toggle="tooltip" data-placement="top" title="Type">Type</a>

                                                                <a href="{{route('vendor.type.index',['pid' => encrypt($product->id), 'cid' => encrypt($product->category_id),'pcid' => encrypt($product->product_category_id)])}}" data-toggle="tooltip" data-placement="top" title="Type">Vendor Type</a>

                                                            @elseif ($product->product_view == 2)

                                                                <a href="{{route('vendor.type.index',['pid' => encrypt($product->id), 'cid' => encrypt($product->category_id),'pcid' => encrypt($product->product_category_id)])}}" data-toggle="tooltip" data-placement="top" title="Type">Vendor Type</a>
                                                            
                                                            @else

                                                                <a href="{{route('type.index',['pid' => encrypt($product->id), 'cid' => encrypt($product->category_id),'pcid' => encrypt($product->product_category_id)])}}" data-toggle="tooltip" data-placement="top" title="Type">Type</a>
                                                                
                                                            @endif


                                                            <a href="javascript:();" class="dCnf" mydata="<?php echo $key ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>

                                                        </div>

                                                        <div style="display:none" id="cnfbox<?php echo $key ?>">
                                                            <p> Are you sure delete this </p>
                                                            <a href="{{route('product.destroy',['pid' => encrypt($product->id),'id'=>base64_encode($product->id)])}}" class="btn btn-danger">Yes</a>
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
