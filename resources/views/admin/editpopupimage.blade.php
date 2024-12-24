@extends('admin.base_template')
@section('main')
<!-- Start content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Add Popupp image</h4>
                    <ol class="breadcrumb" style="display:none">
                        <!-- <li class="breadcrumb-item"><a href="javascript:void(0);">CMS</a></li> -->
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Popup Image</a></li>
                        <li class="breadcrumb-item active">Add Popup Image</li>
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
                            <h4 class="mt-0 header-title">Add Popup Image</h4>
                            <hr style="margin-bottom: 50px;background-color: darkgrey;">
                            <form action="{{ route('popup.update', $popup->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                            
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="form-label" style="margin-left: 10px" for="image">Image</label>
                                        <input class="form-control" style="margin-left: 10px" type="file" id="image" name="image">
                                        @if($popup->image)
                                            <div>
                                                <img src="{{ asset($popup->image) }}" alt="Current Image" style="max-width: 100px; margin-top: 10px;">
                                                <br>
                                                <label for="remove_image">
                                                    <input type="checkbox" id="remove_image" name="remove_image" value="1"> Remove current image
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="form-label" style="margin-left: 10px" for="web_image">Web Image</label>
                                        <input class="form-control" style="margin-left: 10px" type="file" id="web_image" name="web_image">
                                        @if($popup->web_image)
                                            <div>
                                                <img src="{{ asset($popup->web_image) }}" alt="Current Web Image" style="max-width: 100px; margin-top: 10px;">
                                                <br>
                                                <label for="remove_web_image">
                                                    <input type="checkbox" id="remove_web_image" name="remove_web_image" value="1"> Remove current web image
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                    <div class="w-100 text-center">
                                        <button type="submit" style="margin-top: 10px;" class="btn btn-danger"><i class="fa fa-user"></i> Submit</button>
                                    </div>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
        <!-- end page content-->
    </div> <!-- container-fluid -->
</div> <!-- content -->
@endsection