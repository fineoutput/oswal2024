@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

<div class="container">
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <h2 class="text-center">Manufacturing Units</h2>
            </div>
        </div>
    </div>

    <div class="manufactureing_sect">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="image-fluid">
                        <img class="img-fluid w-50 m-auto d-flex" src="images/oswal_main.png" alt="">
                    </div>
                </div>
                <div class="col-lg-6  col-md-6 col-12 fac_heading m-auto p-4 featured-item style-5" style="box-shadow:0px 0px 15px 0px rgb(72 73 121 / 15%);">


                    <b>Name</b>
                    <p>Aditya Industries</p>
  
  
                      <b>GSTIN NO.</b>
                      <p>08AAGFA8405K1ZC  </p>
  
  
  
                      <b>Address</b>
                      <p>G-1025, F-1026, F-1027, Phase III, Sitapura Ind. Area, Jaipur-302022</p>
  
          </div>
            </div>
        </div>
    </div>
    <div class="manufactureing_sect">
        <div class="container">
            <div class="row">
                <div class="col-lg-6  col-md-6 col-12 fac_heading m-auto p-4 featured-item style-5" style="box-shadow:0px 0px 15px 0px rgb(72 73 121 / 15%);">


                    <b>Name</b>
                    <p>Aditya Industries</p>
  
  
                      <b>GSTIN NO.</b>
                      <p>08AAGFA8405K1ZC  </p>
  
  
  
                      <b>Address</b>
                      <p>G-1025, F-1026, F-1027, Phase III, Sitapura Ind. Area, Jaipur-302022</p>
  
          </div>
                <div class="col-lg-6">
                    <div class="image-fluid">
                        <img class="img-fluid w-50 m-auto d-flex" src="images/oswal_main.png" alt="">
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    @endsection