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
        'cid'  => encrypt(0),
        'pcid' => encrypt(0),
    ];
@endphp
<div class="content">

    <div class="container-fluid">

        <div class="row">

            <div class="col-sm-12">

                <div class="page-title-box">

                    <h4 class="page-title">View Range</h4>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="javascript:void(0);">Range</a></li>

                        <li class="breadcrumb-item active">View Range</li>

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

                                    <h4 class="mt-0 header-title">View Range List</h4>

                                </div>

                                <div class="col-md-2">
                                    @php
                                    $back_id = $id;
                                    $id = encrypt($id);
                                @endphp
                                    <a class="btn btn-info" href="{{ route('vendor.type.subtype', $id) }}" role="button">Add Range</a>

                                </div>

                                <div class="col-md-2">

                                    <a class="btn btn-info" href="{{ route('vendor.type.index', $routeParameters) }}" role="button">Back</a>

                                </div>

                                {{-- <div class="col-md-2">

                                    <a class="btn btn-info" href="{{ route('vendor.type.update_all',$routeParameters) }}" role="button">Update all</a>

                                </div> --}}

                                {{-- <div class="col-md-2">

                                    <button type="submit" class="btn btn-info" form="update_city_type" > Update Selected </button>

                                </div> --}}

                            </div>


                            <hr class="my-4">

                            <div class="table-rep-plugin">
                                
                                <div class="table-responsive b-0" data-pattern="priority-columns">

                                    <form action="{{ route('vendor.type.update_city_type') }}" method="post" id="update_city_type">

                                        @csrf
                                        
                                        
                                        <table id="userTable" class="table table-striped">
                                            
                                            <thead>
                                                
                                                <tr>
                                                    
                                                    {{-- <th>*</th> --}}
                                                    
                                                    <th>#</th>
                                                    
                                                    <th data-priority="1">Type id</th>
                                                    
                                                    <th data-priority="3">Selling Price</th>

                                                    <th data-priority="3">Start Range</th>

                                                    <th data-priority="3">End Range</th>
                                                    <th data-priority="6">Action</th>
                                                    
                                                </tr>
                                                
                                            </thead>

                                            <tbody>

                                                @foreach ($types as $key => $value)

                                                    <tr>

                                                        {{-- <td><input type="checkbox" value="{{ $value->id }}" name="type_id[]"></td> --}}

                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ $value->type_id }}</td>
                                                        <td>{{ $value->selling_price }}</td>
                                                        <td>{{ $value->start_range }}</td>
                                                        <td>{{ $value->end_range }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <a href="{{ route('vendor.type.subtype.edit', ['id' => Crypt::encrypt($value->id)]) }}" data-toggle="tooltip" data-placement="top" title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                

                                                                <a href="javascript:;" class="dCnf" mydata="{{ $key }}" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>
                                                                

                                                            </div>


                                                            <div style="display:none" id="cnfbox{{ $key }}">

                                                                

                                                                <p>Are you sure you want to delete this?</p>

                                                                <a href="{{ route('vendor.type.sub.delete', ['id' => Crypt::encrypt($value->id)]) }}" class="btn btn-danger">Yes</a>

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
