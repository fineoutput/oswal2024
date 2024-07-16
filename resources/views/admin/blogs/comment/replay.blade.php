@extends('admin.base_template')

@section('main')
{{-- @dd($replay) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title"> @if($replay != null) Edit @else Add @endif Reply </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Reply</a></li>

                            <li class="breadcrumb-item active">@if($replay != null) Edit @else Add @endif Blog</li>

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

                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span>

                                    </div>

                                @endif

                                @if (session('error'))

                                    <div class="alert alert-danger" role="alert">

                                        {{ session('error') }}

                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span>

                                    </div>

                                @endif

                                <!-- End show success and error messages -->
                                
                                <h4 class="mt-0 header-title"> @if($replay != null) Edit @else Add @endif Reply Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('blog.replay-store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    @if ($replay != null) <input type="hidden" name="replay_id" value="{{$comment->id}}"> @endif

                                    <input type="hidden" name="comment_id" value="{{$comment->id}}"> 

                                    <div class="form-group row">

                                        <div class="col-sm-12">

                                            <div class="form-floating">
                                                
                                                <p> User commint : {{ $comment->comment }}</p>

                                            </div>

                                        </div>

                                    </div>
                                    
                                    <div class="form-group row">

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $replay ? $comment->name : old('name') }}" name="name" placeholder="Enter name" required>

                                                <label for="title">Name &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('name')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $replay ? $comment->reply : old('reply') }}" name="reply" placeholder="Enter reply" required>

                                                <label for="reply">Reply &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('reply')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                    </div>

                                    <div class="form-group row">
                                     
                                        <div class="form-group">

                                            <div class="w-100 text-center">

                                                <button type="submit" style="margin-top: 10px;" class="btn btn-danger"><i class="fa fa-user"></i> Submit</button>

                                            </div>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div> <!-- end col -->

                </div> <!-- end row -->

            </div>

        </div> <!-- container-fluid -->

    </div> <!-- content -->

@endsection
