@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">Send Reply </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Send Reply </a></li>

                            <li class="breadcrumb-item active">Send Reply</li>

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
                                
                                <h4 class="mt-0 header-title"> Send Reply</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('contact-us.store') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    <input type="hidden" name="contactus_id" value="{{ $contactus->id }}" id="">

                                    <div class="form-group row">

                                        <div class="col-sm-6">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ old('reciver_name') }}" name="reciver_name" placeholder="Enter reciver_name" required>

                                                <label for="reciver_name">Reciver Name&nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('reciver_name')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                    </div>

                                    <div class="form-group row">
                                    
                                        <div class="col-sm-12">

                                            <div class="form-floating">

                                                <textarea type="text" class="form-control ckeditor" name="msg" placeholder="Enter msg" required> 
                                                    {{  old('msg') }}
                                                </textarea>

                                                {{-- <label for="city">Email Message &nbsp;<span style="color:red;">*</span></label> --}}

                                            </div>

                                            @error('msg')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                    </div>

                                    <div class="form-group row">
                                     
                                        <div class="form-group">

                                            <div class="w-100 text-center">

                                                <button type="submit" style="margin-top: 10px;" class="btn btn-danger"><i class="fa fa-user"></i> Send</button>

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
