@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View Store</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Store</a></li>

                            <li class="breadcrumb-item active">View Store</li>

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

                                    <div class="col-md-9"> <h4 class="mt-0 header-title">View Store</h4> </div>

                                    <div class="col-md-2"> 

                                        <a class="btn btn-info cticket" href="{{ route('store.create') }}" role="button" style="margin-left: 20px;"> Add Store</a>

                                    </div>

                                </div>


                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <div class="table-rep-plugin">

                                    <div class="table-responsive b-0" data-pattern="priority-columns">

                                        <table id="userTable" class="table  table-striped">

                                            <thead>

                                                <tr>

                                                    <th>#</th>

                                                    <th data-priority="1">Store Name</th>

                                                    <th data-priority="3">Operator Name</th>
                                                    <th data-priority="3">Phone No</th>
                                                    <th data-priority="3">GST No</th>
                                                    <th data-priority="3">Address</th>
                                                    <th data-priority="3">State</th>
                                                    <th data-priority="3">City</th>
                                                    <th data-priority="3">Locality</th>
                                                    <th data-priority="3">Shop Code</th>

                                                    <th data-priority="3">Status</th>

                                                    <th data-priority="6">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($users as $key => $popup)
                                                <tr>
                                                    <td>{{ ++$key }}</td>

                                                    <td>{{ $popup->store_name  ?? '' }}</td>
                                                    <td>{{ $popup->operator_name  ?? '' }}</td>
                                                    <td>{{ $popup->phone_no  ?? '' }}</td>
                                                    <td>{{ $popup->GST_No  ?? '' }}</td>
                                                    <td>{{ $popup->address ?? '' }}</td>
                                                    <td>{{ $popup->state->state_name ?? '' }}</td>
                                                    <td>{{ $popup->cities->city_name ?? '' }}</td>
                                                    <td>{{ $popup->locality ?? '' }}</td>
                                                    <td>{{ $popup->shop_code ?? '' }}</td>
                                                    
                                                   

                                                    <td> 
                                                        @if($popup->status == 1)  
                                                           <p class="label pull-right status-active">Active</p>  
                                                        @else 
                                                           <p class="label pull-right status-inactive">InActive</p> 
                                                        @endif
                                                    </td>

                                                    <td>
                                                        
                                                        <div class="btn-group" id="btns<?php echo $key ?>">

                                                           @if ($popup->status == 2)
                                                            <a href="{{ route('store.updateStatus', base64_encode($popup->id)) }}"
                                                            data-toggle="tooltip" data-placement="top" title="Active">
                                                                <i class="fas fa-check success-icon"></i>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('store.updateStatus', base64_encode($popup->id)) }}"
                                                            data-toggle="tooltip" data-placement="top" title="Inactive">
                                                                <i class="fas fa-times danger-icon"></i>
                                                            </a>
                                                        @endif


                                                            <a href="{{route('store.edit',[base64_encode($popup->id)])}}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>

                                                         <a href="javascript:void(0);" 
                                                            class="fas fa-trash danger-icon dCnf" 
                                                            mydata="{{ $popup->id }}" 
                                                            data-toggle="tooltip" 
                                                            data-placement="top" 
                                                            title="Delete"> 
                                                            </a>
                                                    
                                                            {{-- <a href="javascript:();" class="dCnf" mydata="<?php echo $key ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a> --}}

                                                        </div>

                                                        {{-- <div style="display:none" id="cnfbox<?php echo $key ?>">
                                                            <p> Are you sure delete this </p>
                                                            <a href="{{route('achievements.destroy', base64_encode($popup->id))}}" class="btn btn-danger">Yes</a>
                                                            <a href="javascript:();" class="cans btn btn-default" mydatas="<?php echo $key ?>">No</a>
                                                        </div> --}}
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



    <script>
    $(document).ready(function () {
        $('.dCnf').click(function (e) {
            e.preventDefault();

            let id = $(this).attr('mydata');
            let url = "{{ route('store.destroy', ':id') }}";
            url = url.replace(':id', btoa(id)); // base64 encode the ID

            if (confirm('Are you sure you want to delete this store?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        alert(response.message);
                        location.reload(); // Reload or remove row dynamically
                    },
                    error: function (xhr) {
                        alert('Error deleting store.');
                    }
                });
            }
        });
    });
</script>


@endsection
