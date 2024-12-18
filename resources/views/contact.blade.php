@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
<style>
  .contact-info {
    display: inline-block;
    width: 100%;
    text-align: center;
    margin-bottom: 10px;
  }

  .contact-info-icon {
    margin-bottom: 15px;
  }

  .contact-info-item {
    background: #d61727;
    padding: 30px 0px;
  }

  .contact-page-sec .contact-page-form h2 {
    color: #071c34;
    text-transform: capitalize;
    font-size: 22px;
    font-weight: 700;
  }

  .contact-page-form .col-md-6.col-sm-6.col-xs-12 {
    padding-left: 0;
  }

  .contact-page-form.contact-form input {
    margin-bottom: 5px;
  }

  .contact-page-form.contact-form textarea {
    height: 110px;
  }

  .contact-page-form.contact-form input[type="submit"] {
    background: #071c34;
    width: 150px;
    border-color: #071c34;
  }

  .contact-info-icon i {
    font-size: 48px;
    color: #fda40b;
  }

  .contact-info-text p {
    margin-bottom: 0px;
  }

  .contact-info-text h2 {
    color: #fff;
    font-size: 22px;
    text-transform: capitalize;
    font-weight: 600;
    margin-bottom: 10px;
  }

  .contact-info-text span {
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    display: inline-block;
    width: 100%;
  }

  .contact-page-form input {
    background: #f7f7f7 none repeat scroll 0 0;
    border: 1px solid #f9f9f9;
    margin-bottom: 20px;
    padding: 12px 16px;
    width: 100%;
    box-shadow: 1px 1px 1px #00000029;
    border-radius: 4px;
  }

  .contact-page-form .message-input {
    display: inline-block;
    width: 100%;
    padding-left: 0;
  }

  .single-input-field textarea {
    background: #f9f9f9 none repeat scroll 0 0;
    border: 1px solid #f9f9f9;
    width: 100%;
    height: 120px;
    padding: 12px 16px;
    box-shadow: 1px 1px 1px #00000029;
    border-radius: 4px;
  }

  .single-input-fieldsbtn input[type="submit"] {
    background: #fda40b none repeat scroll 0 0;
    color: #fff;
    display: inline-block;
    font-weight: 600;
    padding: 10px 0;
    text-transform: capitalize;
    width: 150px;
    margin-top: 20px;
    font-size: 16px;
  }

  .single-input-fieldsbtn input[type="submit"]:hover {
    background: #071c34;
    transition: all 0.4s ease-in-out 0s;
    border-color: #071c34
  }

  .single-input-field h4 {
    color: #464646;
    text-transform: capitalize;
    font-size: 14px;
  }

  .contact-page-form {
    display: inline-block;
    width: 100%;
    margin-top: 30px;
  }

  .contact-page-map {
    margin-top: 36px;
  }

  .contact-page-form form {
    padding: 20px 15px 0;
  }
</style>
<section class="py-3 py-md-5 py-xl-8">
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6">
        <h2 class="mb-4 display-5 text-center">Stay Connected</h2>
        <hr class="w-50 mx-auto mb-5 mb-xl-9 border-dark-subtle">
      </div>
    </div>
  </div>
  <section class="contact-page-sec">
    <div class="container">

      <div class="row">
        <div class="col-md-8" style="box-shadow: -6px 7px 19px 0px #0000002e;">
          
          <div class="contact-page-form" method="post">

            <h2>Get in Touch</h2>

            <form action=" {{ route('contact_us') }} " method="post">

            @csrf

              <div class="row">

                <div class="col-md-6 col-sm-6 col-xs-12">

                  <div class="single-input-field">

                    <input type="text" placeholder="Your Name" name="name" required/>

                    @error('name')
                    {{$message}}
                    @enderror
                  </div>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">

                  <div class="single-input-field">

                    <input type="email" placeholder="E-mail" name="email" required />

                    @error('email')
                    {{$message}}
                    @enderror
                  </div>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">

                  <div class="single-input-field">

                    <input type="text" placeholder="Phone Number" name="phone" required/>
                    @error('phone')
                    {{$message}}
                    @enderror
                  </div>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">

                  <div class="single-input-field">

                    <input type="text" placeholder="Subject" name="subject" required/>
                    @error('subject')
                    {{$message}}
                    @enderror
                  </div>

                </div>

                <div class="col-md-12 message-input">
                  
                  <div class="single-input-field">

                    <textarea placeholder="Write Your Message" name="message" required></textarea>
                    @error('message')
                    {{$message}}
                    @enderror
                  </div>

                </div>

                <div class="single-input-fieldsbtn">

                  <input type="submit" value="Send Now" />

                </div>

              </div>

            </form>

          </div>
        </div>
        <div class="col-md-4">
          <div class="contact-page-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d135064.85821946795!2d75.78861412749082!3d26.895878794708338!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db15247d48d99%3A0xa4c35d5fb9c6cae3!2sUttam%20Chand%20Desraj%20-%20Oswal%20Soap%20Group!5e1!3m2!1sen!2sin!4v1726232884403!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
      <div class="row pt-5">
        <div class="col-md-4">
          <div class="contact-info">
            <div class="contact-info-item">
              <div class="contact-info-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="contact-info-text">
                <h2>E-mail</h2>
                <span>info@oswalsoap1956.com</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="contact-info">
            <div class="contact-info-item">
              <div class="contact-info-icon">
                <i class="fas fa-map-marked"></i>
              </div>
              <div class="contact-info-text">
                <h2>address</h2>
                <span>Uttam Chand Desraj 10/OB/35, Oswal Tower, Ring Road Project, Near Reliance Petrol Pump, Lakeshra, Agra Road, Jaipur-303012</span>
                <span></span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="contact-info">
            <div class="contact-info-item">
              <div class="contact-info-icon">
                <i class="fas fa-phone"></i>
              </div>
              <div class="contact-info-text">
                <h2>Phone Number</h2>
                <span>+91-91161 71956</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>


  @endsection