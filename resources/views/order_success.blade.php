@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
<style>
  .success_btmns {
    display: flex;
    justify-self: center;
    align-items: center;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
}
</style>
<div style="background:">
  <div class="container">

    <div class="row section-padding">
      
      <div class="section">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-md-8">
              <div class="text-center order_complete">
                <i class="fas fa-check-circle" style="color: green; font-size:80px;"></i>
                <div class="heading_s1">
                  <b>
                    <h1>Your order is successfully placed!</h1>
                  </b>
                </div>
                <p>Your order <b> #{{ (isset($response)) ? $response['order_id'] :$order_id  }} </b>has been successfull!</p>
                <p>Thank you for choosing Oswal Soap. You will shortly receive a confirmation email.</p>
                <div class="success_btmns">
                <a href="{{url('/user')}}"><button type="submit" class="animated-button">

                    <span>View Order</span> <span></span>

                  </button></a>
                <a href="{{route('/')}}"><button type="submit" class="animated-button">

                    <span>Continue Shopping</span> <span></span>

                  </button></a>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection