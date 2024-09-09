@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-12" style="text-align:justify !important;">
			<button id="myButton">Click Me</button>

			<button id="failureButton">Failure</button>
			<h4 class="text-black font-weight-normal line-h-2"><span class="text-theme">Oswal Soap Group </span>was established by Late Shri Uttamchand Deshraj Jain in 1956 in Johari Bazaar, Jaipur with his hard work
				and efforts. The soap’s high quality was the key to attract people and made it their first choice.

				Later on, Late Shri Uttamchand Deshraj Jain’s sons took a step forward and got the business registered and copyright as “Oswal
				Soap Group” and started handling his whole business. Following this, they set up their production centers and stores
				to produce and sell their soaps in different cities, towns and villages.

				In 1995, Uttamchand Deshraj ji’s third generation took over the business and appointed many agencies and stores to sell their
				product. Moreover, seeing the need of time, the Oswal Soap Group launched many new products like washing
				powder, detergent powder, bath soap, cleaning powder, tea, different spices, phenol, liquid hand wash and dish wash,
				floor cleaner, glass cleaner, rice, oil, broom, sanitary pad, sanitizer, pulse etc. All of these products offered by Oswal
				Soap Group are readily available at reasonable prices at wholesale and retail stores in Rajasthan, Punjab, Haryana,
				Gujarat, Maharashtra, Madhya Pradesh, etc. To increase their business, Oswal Soap Group has decided to launch
				many new products in near future for which research and efforts are being made continuously.

				With time, the demand for Oswal products is increasing and coming from more cities. So, the group has a desire to
				appoint more agencies in different cities. So, the interested candidates and agencies are requested to contact.

				With over 65 years of experience and a billion satisfied customers, Oswal Soap Group became one of the largest
				manufacturers of the daily-use commodities. We have a network of over 1000 distributors, 2.5 lacs wholesale retailers
				and 800+ employees, who are working hard to provide superior quality products to the consumers.</h4>
		</div>
	</div>

	<div class="row mt-5">
		<div class="col-md-4 col-12 m-auto">
			<img class="d-flex m-auto w-50" src="{{asset('images/oswalowner.jpg')}}">
			<p class="name_top">Late Shri Uttam Chand desraj</p>
		</div>
	</div>
	<div class="row mt-5 good_e">
		<div class="col-md-3 col-12 m-auto">
			<div class="round_name">
				<p class="botmname_top">Devendra Jain Director</p>
			</div>
		</div>
		<div class="col-md-3 col-12 m-auto">
			<div class="round_name">
				<p class="botmname_top">Surendra Jain Director</p>
			</div>
		</div>
		<div class="col-md-3 col-12 m-auto">
			<div class="round_name">
				<p class="botmname_top">Yatendra Jain Director</p>
			</div>
		</div>
		<div class="col-md-3 col-12 m-auto">
			<div class="round_name">
				<p class="botmname_top">Virendra Jain Director</p>
			</div>
		</div>
	</div>
	<div class="row mt-5 mb-5 good_e">
		<div class="title-effect title-effect-2">
			<div class="ellipse"></div> <i class="la la-btc"></i>
		</div>
		<div class="col-md-6 m-auto">
			<h2 class="brother">Eight Brothers</h2>
		</div>
	</div>
	<div class="row newefe " style="position:relative;">

		<div class="col-md-3 col-12 m-auto">
			<div class="round_name">
				<p class="botmname_top">Harsh Jain Director</p>
			</div>
		</div>
		<div class="col-md-3 col-12 m-auto">
			<div class="round_name">
				<p class="botmname_top">Ajay Jain Director</p>
			</div>
		</div>
		<div class="col-md-3 col-12   m-auto">
			<div class="round_name">
				<p class="botmname_top">Sanjay Jain Director</p>
			</div>
		</div>
		<div class="col-md-3 col-12 m-auto">
			<div class="round_name">
				<p class="botmname_top">Achal Jain Director</p>
			</div>
		</div>

		<div class="col-md-3 col-12 m-auto">
			<div class="round_name">
				<p class="botmname_top">Gaurav Jain Director</p>
			</div>
		</div>
		<div class="col-md-3 col-12 m-auto">
			<div class="round_name">
				<p class="botmname_top">Saurabh Jain Director</p>
			</div>
		</div>
		<div class="col-md-3 col-12 m-auto">
			<div class="round_name">
				<p class="botmname_top">Shrenik Jain Director</p>
			</div>
		</div>
		<div class="col-md-3 col-12 m-auto">
			<div class="round_name">
				<p class="botmname_top">Hemank Jain Director</p>
			</div>
		</div>
	</div>
</div>
@endsection