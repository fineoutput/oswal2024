@extends('admin.base_template')

@section('main')
{{-- @dd($crm) --}}
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View Crm Setting </h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="javascript:void(0);">Crm</a></li>

                            <li class="breadcrumb-item active"> Edit Crm Setting</li>

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
                                
                                <h4 class="mt-0 header-title">Constant Form</h4>

                                <hr style="margin-bottom: 50px;background-color: darkgrey;">

                                <form action="{{ route('setting.crm') }}" method="post" enctype="multipart/form-data">

                                    @csrf

                                    <input type="hidden" name="crm_id" value="{{$crm->id}}">
                                    
                                    <div class="form-group row">

                                        
                                        <div class="col-sm-4 mb-3">

                                            <div class="form-floating">

                                                <input class="form-control" type="file" id="img" name="img" placeholder="img">
                                                 
                                                @if ($crm != null)
                                                    
                                                  <img src="{{asset($crm->logo)}}" width="100px" height="100px">

                                                @endif

                                                <label class="mb-2" for="img">logo </label>

                                            </div>

                                            @error('img')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input type="text" class="form-control" value="{{ $crm ? $crm->sitename : old('sitename') }}" name="sitename" placeholder="site name" required>

                                                <label for="name">Site name &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('sitename')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $crm ? $crm->phone : old('phone') }}" id="phone" name="phone" placeholder="phone">

                                                <label for="phone">Phone &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('phone')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>
                                        
                                        
                                        <div class="col-sm-12">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $crm ? $crm->address : old('address') }}" id="address" name="address" placeholder="address">

                                                <label for="address">Address &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('address')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-12">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $crm ? $crm->instagram_link : old('instagram_link') }}" id="instagram_link" name="instagram_link" placeholder="instagram_link">

                                                <label for="instagram_link">Instagram link &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('instagram_link')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-12">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $crm ? $crm->facebook_link : old('facebook_link') }}" id="facebook_link" name="facebook_link" placeholder="facebook_link">

                                                <label for="facebook_link">Facebook link  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('facebook_link')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-12">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $crm ? $crm->youtube_link : old('youtube_link') }}" id="youtube_link" name="youtube_link" placeholder="youtube_link">

                                                <label for="youtube_link">Youtube link  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('youtube_link')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-12">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $crm ? $crm->twitter_link : old('twitter_link') }}" id="twitter_link" name="twitter_link" placeholder="twitter_link">

                                                <label for="twitter_link">twitter link  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('twitter_link')

                                                <div style="color:red">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class="col-sm-12">

                                            <div class="form-floating">

                                                <input class="form-control" type="text" value="{{ $crm ? $crm->linkedin_link : old('linkedin_link') }}" id="linkedin_link" name="linkedin_link" placeholder="linkedin_link">

                                                <label for="linkedin_link">linkedin link  &nbsp;<span style="color:red;">*</span></label>

                                            </div>

                                            @error('linkedin_link')

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
