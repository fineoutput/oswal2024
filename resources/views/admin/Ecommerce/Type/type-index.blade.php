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

@php
    $routeParameters = [
        'pid'  => encrypt($p_id),
        'cid'  => encrypt($c_id),
        'pcid' => encrypt($pc_id),
    ];
@endphp

<div class="content">

    <div class="container-fluid">

        <div class="row">

            <div class="col-sm-12">

                <div class="page-title-box">

                    <h4 class="page-title">View Type</h4>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="javascript:void(0);">Type</a></li>

                        <li class="breadcrumb-item active">View Type</li>

                    </ol>

                </div>

            </div>

        </div>
        
        <div class="page-content-wrapper">

            <div class="row">

                <div class="col-12">

                    <div class="card m-b-20">

                        <div class="card-body">

                            @if (session('success'))

                                <div class="alert alert-success alert-dismissible" role="alert">

                                    {{ session('success') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                                        <span aria-hidden="true">&times;</span>

                                    </button>

                                </div>

                            @endif

                            @if (session('error'))

                                <div class="alert alert-danger alert-dismissible" role="alert">

                                    {{ session('error') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                                        <span aria-hidden="true">&times;</span>

                                    </button>

                                </div>

                            @endif

                            <div class="row">

                                <div class="col-md-4">

                                    <h4 class="mt-0 header-title">View Type List</h4>

                                </div>

                                <div class="col-md-2">

                                    <a class="btn btn-info" href="{{ route('type.create', $routeParameters) }}" role="button">Add Type</a>

                                </div>

                                <div class="col-md-2">

                                    <a class="btn btn-info" href="{{ route('product.index', encrypt($pc_id)) }}" role="button">Back</a>

                                </div>

                                <div class="col-md-2">

                                    <a class="btn btn-info" href="{{ route('type.update_all',$routeParameters) }}" role="button">Update all</a>

                                </div>

                                <div class="col-md-2">

                                    <button type="submit" class="btn btn-info" form="update_city_type" > Update Selected </button>

                                </div>

                            </div>


                            <hr class="my-4">

                            <div class="table-rep-plugin">
                                
                                <div class="table-responsive b-0" data-pattern="priority-columns">

                                    <form action="{{ route('type.update_city_type') }}" method="post" id="update_city_type">

                                        @csrf

                                        <input type="hidden" name="category_id" value="{{$c_id  }}">

                                        <input type="hidden" name="product_id" value="{{ $p_id }}">

                                        <input type="hidden" name="product_category_id" value="{{ $pc_id }}">
                                        
                                        <table id="userTable" class="table table-striped">
                                            
                                            <thead>
                                                
                                                <tr>
                                                    
                                                    <th>*</th>
                                                    
                                                    <th>#</th>
                                                    
                                                    <th data-priority="1">Type id</th>
                                                    
                                                    <th data-priority="3">Type Name</th>
                                                    
                                                    <th data-priority="1">State</th>
                                                    
                                                    <th data-priority="3">City</th>
                                                    
                                                    <th data-priority="3">Mrp</th>
                                                    
                                                    <th data-priority="3">Gst %</th>
                                                    
                                                    <th data-priority="6">Selling Price (without Gst)</th>
                                                    
                                                    <th data-priority="6">GST % Price</th>
                                                    
                                                    <th data-priority="6">Selling Price</th>
                                                    
                                                    <th data-priority="6">Status</th>
                                                    
                                                    <th data-priority="6">Action</th>
                                                    
                                                </tr>
                                                
                                            </thead>

                                            <tbody>

                                                @foreach ($types as $key => $value)

                                                    <tr>

                                                        <td><input type="checkbox" value="{{ $value->id }}" name="type_id[]"></td>

                                                        <td>{{ ++$key }}</td>

                                                        <td>{{ $value->id }}</td>

                                                        <td>{{ $value->type_name }}</td>

                                                        <td>{{ $value->state->state_name }}</td>

                                                        <td>{{ $value->city->city_name }}</td>

                                                        <td><input type="text" id="delmrp{{$value->id}}" class="form-control" value="{{ $value->del_mrp }}" name="del_mrp{{$value->id}}"></td>

                                                        <td><input type="text" id="gst_percentage{{$value->id}}" onkeyup="calculatePrices('mrp{{$value->id}}' ,'gst_percentage{{$value->id}}','gst_percentage_price{{$value->id}}' ,'selling_price{{$value->id}}')" class="form-control" value="{{ $value->gst_percentage }}" name="gst_percentage{{$value->id}}"></td>

                                                        <td><input type="text" id="mrp{{$value->id}}" onkeyup="calculatePrices('mrp{{$value->id}}' ,'gst_percentage{{$value->id}}','gst_percentage_price{{$value->id}}' ,'selling_price{{$value->id}}')" class="form-control" value="{{ $value->mrp }}" name="mrp{{$value->id}}"></td>

                                                        <td><input type="text" id="gst_percentage_price{{$value->id}}" class="form-control" value="{{ $value->gst_percentage_price }}" name="gst_percentage_price{{$value->id}}"></td>

                                                        <td><input type="text" id="selling_price{{$value->id}}" class="form-control" value="{{ $value->selling_price }}" name="selling_price{{$value->id}}"></td>

                                                        <td>

                                                            <p class="label pull-right {{ $value->is_active ? 'status-active' : 'status-inactive' }}">

                                                                {{ $value->is_active ? 'Active' : 'Inactive' }}

                                                            </p>

                                                        </td>

                                                        <td>

                                                            <div class="btn-group" id="btns{{ $key }}">

                                                                @php

                                                                    $routeParameters['tid'] = encrypt($value->id);

                                                                @endphp

                                                                @if ($value->is_active == 0)

                                                                    @php $routeParameters['status'] = 'active'; @endphp

                                                                    <a href="{{ route('type.update-status', $routeParameters) }}" data-toggle="tooltip" data-placement="top" title="Active"><i class="fas fa-check success-icon"></i></a>

                                                                @else

                                                                    @php $routeParameters['status'] = 'inactive'; @endphp

                                                                    <a href="{{ route('type.update-status', $routeParameters) }}" data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fas fa-times danger-icon"></i></a>

                                                                @endif

                                                                <a href="{{ route('type.edit', $routeParameters) }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>

                                                                <a href="javascript:;" class="dCnf" mydata="{{ $key }}" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>

                                                            </div>


                                                            <div style="display:none" id="cnfbox{{ $key }}">

                                                                @if(array_key_exists('status', $routeParameters))

                                                                    @unset($routeParameters['status'])

                                                                @endif

                                                                <p>Are you sure you want to delete this?</p>

                                                                <a href="{{ route('type.destroy', $routeParameters) }}" class="btn btn-danger">Yes</a>

                                                                <a href="javascript:;" class="cans btn btn-default" mydatas="{{ $key }}">No</a>

                                                            </div>

                                                        </td>

                                                    </tr>

                                                @endforeach

                                            </tbody>

                                        </table>

                                    </form>

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
