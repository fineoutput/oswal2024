@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
<style>
    .message-box{
  display: flex;
  justify-content: center;
  /* padding-top: 20vh;
  padding-bottom: 20vh; */
}
.success-container{
  background: white;
  height: 480px;
  width: 90%;
box-shadow: 5px 5px 10px grey;
  text-align: center;
}
.confirm-green-box{
  width: 100%;
  height: 140px;
  background: #d7f5da;
}


.monserrat-font{
  font-family: 'Montserrat', sans-serif;
  letter-spacing: 2px;
}





/* --------------- site wide START ----------------- */



/* 
 * Setting the site variables, example of how to use
 * color:var(--text-1);
 *
 */



.verticle-align{
  text-align:center;
  display:flex;
  align-items:center;
  justify-content:center;
}

.no-style{
  padding:0;
  margin:0;
}

</style>
<div style="background:">
<div class="container">

  <div class="row">
    <div class="col-12 ">
      <div class="message-box">
      <div class="success-container">
        
        <br>
        <!-- <img src="https://scontent-lcy1-1.xx.fbcdn.net/v/t1.6435-9/31301640_2114242505489348_3921532491046846464_n.png?_nc_cat=104&ccb=1-3&_nc_sid=973b4a&_nc_ohc=pfOalMq8BzUAX-k-rhY&_nc_ht=scontent-lcy1-1.xx&oh=3af014dd12fa6e3d1816a3425a80e516&oe=609BE04A" alt="" style="height: 100px;"> -->
        <br>
        <div style="padding-left: 5%; padding-right: 5%">
        <hr
        </div>
        <br>
        <h1 class="monserrat-font" style="color: Grey">Thank you for your order</h1>
        <br>
          
          <div class="confirm-green-box">
            <br>
            <h5>ORDER CONFIRMATION</h5>
            <p>Your order #2465 has been sucessful!</p>
            <p>Thank you for choosing Oswal Soap. You will shortly receive a confirmation email.</p>
          </div>

        <br>
         <button id="create-btn" class="btn btn-ouioui-secondary margin-left-5px"><a href="{{route('/')}}">Back to shop</a></button> 
      </div>
        </div>
    </div>
  </div>

</div>
</div>
@endsection