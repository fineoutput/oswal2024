@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
<style>
    
.container{
	max-width: 1230px;
	width: 100%;
}



#description{
	font-size: 24px;
}

.form-wrap{
	background: rgba(255,255,255,1);
	width: 100%;
	max-width: 100%;
	padding: 50px;
	margin: 0 auto;
	position: relative;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
	-webkit-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
	-moz-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
	box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
}
.form-wrap:before{
	content: "";
	width: 90%;
	height: calc(100% + 60px);
	left: 0;
	right: 0;
	margin: 0 auto;
	position: absolute;
	top: -30px;
	background: #d71727;
	z-index: -1;
	opacity: 0.8;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
	-webkit-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
	-moz-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
	box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
}
.form-group{
	margin-bottom: 25px;
}
.form-group > label{
	display: block;
	font-size: 15px;	
	color: #000;
}
.custom-control-label{
	color: #000;
	font-size: 16px;
}
.form-control{
	height: 50px;
	background: #ecf0f4;
	border-color: transparent;
	padding: 0 15px;
	font-size: 16px;
	-webkit-transition: all 0.3s ease-in-out;
	-moz-transition: all 0.3s ease-in-out;
	-o-transition: all 0.3s ease-in-out;
	transition: all 0.3s ease-in-out;
}
.form-control:focus{
	border-color: #00bcd9;
	-webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	-moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
}
textarea.form-control{
	height: 160px;
	padding-top: 15px;
	resize: none;
}

.btn{
	padding: .657rem .75rem;
	font-size: 18px;
	letter-spacing: 0.050em;
	-webkit-transition: all 0.3s ease-in-out;
	-moz-transition: all 0.3s ease-in-out;
	-o-transition: all 0.3s ease-in-out;
	transition: all 0.3s ease-in-out;
}

.btn-primary {
  color: #fff;
  background-color: #d71727;
  border-color: #d71727;
}

.btn-primary:hover {
  color: #00bcd9;
  background-color: #ffffff;
  border-color: #00bcd9;
	-webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	-moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
}

.btn-primary:focus, .btn-primary.focus {
  color: #00bcd9;
  background-color: #ffffff;
  border-color: #00bcd9;
  -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	-moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
}

.btn-primary:not(:disabled):not(.disabled):active, .btn-primary:not(:disabled):not(.disabled).active,
.show > .btn-primary.dropdown-toggle {
  color: #00bcd9;
  background-color: #ffffff;
  border-color: #00bcd9;
}

.btn-primary:not(:disabled):not(.disabled):active:focus, .btn-primary:not(:disabled):not(.disabled).active:focus,
.show > .btn-primary.dropdown-toggle:focus {
  -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	-moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
}
</style>


<div class="container">
	<header class="header">
		<h1 id="title" class="text-center">Dealer Application Form</h1>
		<p id="description" class="text-center">Please fill out the form below with your personal and business details.</p>
	</header>
	<div class="form-wrap section-padding">
		<form action=" {{ route('dealer_contact') }} " method="post" enctype="multipart/form-data">
		@csrf
			<h2 class="mb-4">Personal Details</h2>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="name" class="form-label">Name</label>
						<input type="text" class="form-control" value="{{old('name') }}" id="name" name="name" required>
                        @error('name')
                    {{$message}}
                    @enderror
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="age" class="form-label">Age</label>
						<input type="number" class="form-control" value="{{old('age') }}" id="age" name="age" required>
                        @error('age')
                    {{$message}}
                    @enderror
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="qualification" class="form-label">Qualification</label>
						<input type="text" class="form-control" value="{{old('qualification') }}" id="qualification" name="qualification" required>
                        @error('qualification')
                    {{$message}}
                    @enderror
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="city" class="form-label">City/Town/Village</label>
						<input type="text" class="form-control" value="{{old('city') }}" name="city" id="city" required>
                        @error('city')
                    {{$message}}
                    @enderror
					</div>
				</div>
			</div>

			<!-- Continue personal details -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="state" class="form-label">State</label>
						<select name="state" id="dealerstate" style="width: 100%;" onchange="getCity('{{ route('getcity') }}', 'city-container3')">
                        <option value="99999">Choose State</option>
                        @foreach (App\Models\State::all() as $state)
                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                        @endforeach
                    </select>
                   
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="district" class="form-label">District</label>
						<select id="city-container3" name="city" class="form-control">
                          <option value="">----- Select City -----</option>
                         </select>
					</div>
				</div>
			</div>

			<h2 class="mb-4 mt-5">Business Details</h2>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="firmName" class="form-label">Name of the Firm</label>
						<input type="text" class="form-control" id="firmName" value="{{old('firmname') }}" name="firmname" required>
                        @error('firmname')
                    {{$message}}
                    @enderror
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="firmaddress" class="form-label">Full Address of the Firm</label>
						<input type="text" class="form-control" id="firmAddress" value="{{old('firmaddress') }}" name="firmaddress" required>
                        @error('firmaddress')
                    {{$message}}
                    @enderror
					</div>
				</div>
			</div>
			
			<!-- Continue Business Details similarly -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="businessname" class="form-label">Current Name of Business</label>
						<input type="text" class="form-control" id="currentBusinessName" value="{{old('businessname') }}" name="businessname" required>
                        @error('businessname')
                    {{$message}}
                    @enderror
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="businessexperience" class="form-label">No. of Years Experience in Current Business</label>
						<input type="number" class="form-control" id="yearsExperience" value="{{old('businessexperience') }}" name="businessexperience" required>
                        @error('businessexperience')
                    {{$message}}
                    @enderror
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="businesstype" class="form-label">Business Type</label>
						<input type="text" class="form-control" id="BusinessType" value="{{old('businesstype') }}" name="businesstype" required>
                        @error('businesstype')
                    {{$message}}
                    @enderror
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="mobile" class="form-label">Mobile</label>
						<input type="number" class="form-control" id="yearsExperience" value="{{old('mobile') }}" name="mobile" required>
                        @error('mobile')
                    {{$message}}
                    @enderror
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="annualturnover" class="form-label">Annual Turnover</label>
						<input type="number" class="form-control" id="annualTurnover" value="{{old('annualturnover') }}" name="annualturnover" required>
                        @error('annualturnover')
                    {{$message}}
                    @enderror
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="type" class="form-label">Vehicle (Type)</label>
						<input type="text" class="form-control" id="vehicleType" value="{{old('type') }}" name="type" required>
                        @error('type')
                    {{$message}}
                    @enderror
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="vehicle" class="form-label">Vehicle (Model)</label>
						<input type="text" class="form-control" id="vehicleModel" value="{{old('vehicle') }}" name="vehicle" required>
                        @error('vehicle')
                    {{$message}}
                    @enderror
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="vehicle_count" class="form-label">Vehicle (How Many)</label>
						<input type="text" class="form-control" id="vehicleModel" value="{{old('vehicle_count') }}" name="vehicle_count" required>
                        @error('vehicle_count')
                    {{$message}}
                    @enderror
					</div>
				</div>
			</div>
            <div class="row">
            <div class="col-md-6">
					<div class="form-group">
						<label for="manpower" class="form-label">Manpower/Salesman</label>
						<input type="number" class="form-control" id="manpower" value="{{old('manpower') }}" name="manpower" required>
                        @error('manpower')
                    {{$message}}
                    @enderror
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="capacity" class="form-label">Capacity to Invest</label>
						<select class="form-control" id="capacity" required>
							<option value="">Select Investment Capacity</option>
							<option value="10-15 lac">10-15 lac</option>
						</select>
					</div>
				</div>
			</div>
            <div class="row">
            <!-- <div class="col-md-6">
					<div class="form-group">
						<label for="exitingmanpower" class="form-label">Existing Manpower/Salesman</label>
						<input type="number" class="form-control" id="exitingmanpower" required>
					</div>
				</div> -->
				<div class="col-md-6">
					<div class="form-group">

                    <label for="file" class="form-label">Attachment (Upload GST Certificate)</label>

                    <input type="file" class="form-control" id="gstCertificate" value="{{old('gstcertificate') }}" name="gstcertificate" required>

                    @error('gstcertificate')

                    	{{$message}}

                    @enderror

					</div>
				</div>
			</div>
            <div class="row">
            <div class="col-md-6">
					<div class="form-group">
						<label for="agencyName" class="form-label">Reference By: Name / Agency Name</label>
						<input type="text" class="form-control" id="agencyName" value="{{old('agencyName') }}" name="agencyName" required>
                        @error('agencyName')
                    {{$message}}
                    @enderror
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
                    <label for="cityName" class="form-label">Reference By: City Name</label>
                    <input type="text" class="form-control" id="cityName" value="{{old('details') }}" name="details" required>
                    @error('details')
                    {{$message}}
                    @enderror
					</div>
				</div>
			</div>
            <div class="row">
            <div class="col-md-6">
					<div class="form-group">
                    <label for="interestReason" class="form-label">Why Are You Interested in Becoming a Dealer for OSWAL SOAP GROUP?</label>
                    <textarea class="form-control" id="interestReason" rows="3" value="{{old('details') }}" name="details" required></textarea>
                    @error('details')
                    {{$message}}
                    @enderror
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
                    <label for="businessbrief" class="form-label">Your Current Business Brief Description of Infrastructure (Storage Space, Etc.)</label>
                    <textarea class="form-control" id="businessDescription" value="{{old('businessbrief') }}" name="businessbrief" rows="3" required></textarea>
                    @error('businessbrief')
                    {{$message}}
                    @enderror
					</div>
				</div>
			</div>
			
			<!-- Repeat form structure for the rest of the fields -->
			<div class="form-group">
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</form>
	</div>
</div>

    </div>
    
    @endsection