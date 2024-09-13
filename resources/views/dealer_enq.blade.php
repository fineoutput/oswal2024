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

<!-- <div class="container">
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-md-12">
                <h2>Dealer's Enquiry form
                </h2>
                <p>Looking Forword To Becoming A Dealer? .Share Yourt Details Here.
                </p>
            </div>
        </div>
    </div>

    <div class="container my-5 dealer_puts">
        <div class="row"> 
            <div class="col-6">
                <form>
                    <h2 class="mb-4">Personal Details</h2>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" class="form-control" id="age" required>
                    </div>
                    <div class="mb-3">
                        <label for="qualification" class="form-label">Qualification</label>
                        <input type="text" class="form-control" id="qualification" required>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City/Town/Village</label>
                        <input type="text" class="form-control" id="city" required>
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <select class="form-select" id="state" required>
                            <option value="">Select State</option>
                            <option value="AR">Arunachal Pradesh [AR]</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="district" class="form-label">District</label>
                        <select class="form-select" id="district" required>
                            <option value="">Select District</option>
                            <option value="Anjaw">Anjaw</option>
                        </select>
                    </div>

                    <h2 class="mb-4 mt-5">Business Details</h2>
                    <div class="mb-3">
                        <label for="firmName" class="form-label">Name of the Firm</label>
                        <input type="text" class="form-control" id="firmName" required>
                    </div>
                    <div class="mb-3">
                        <label for="firmAddress" class="form-label">Full Address of the Firm</label>
                        <input type="text" class="form-control" id="firmAddress" required>
                    </div>
                    <div class="mb-3">
                        <label for="currentBusinessName" class="form-label">Current Name of Business</label>
                        <input type="text" class="form-control" id="currentBusinessName" required>
                    </div>
                    <div class="mb-3">
                        <label for="yearsExperience" class="form-label">No. of Years Experience in Current Business</label>
                        <input type="number" class="form-control" id="yearsExperience" required>
                    </div>
                    <div class="mb-3">
                        <label for="businessType" class="form-label">Business Type</label>
                        <input type="text" class="form-control" id="businessType" required>
                    </div>
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile</label>
                        <input type="text" class="form-control" id="mobile" required>
                    </div>
                    <div class="mb-3">
                        <label for="annualTurnover" class="form-label">Annual Turnover</label>
                        <input type="text" class="form-control" id="annualTurnover" required>
                    </div>
                    <div class="mb-3">
                        <label for="businessDescription" class="form-label">Your Current Business Brief Description of Infrastructure (Storage Space, Etc.)</label>
                        <textarea class="form-control" id="businessDescription" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="vehicleType" class="form-label">Vehicle (Type)</label>
                        <input type="text" class="form-control" id="vehicleType" required>
                    </div>
                    <div class="mb-3">
                        <label for="vehicleModel" class="form-label">Vehicle (Model)</label>
                        <input type="text" class="form-control" id="vehicleModel" required>
                    </div>
                    <div class="mb-3">
                        <label for="vehicleCount" class="form-label">Vehicle (How Many)</label>
                        <input type="number" class="form-control" id="vehicleCount" required>
                    </div>
                    <div class="mb-3">
                        <label for="investmentCapacity" class="form-label">Capacity to Invest</label>
                        <select class="form-select" id="investmentCapacity" required>
                            <option value="">Select Investment Capacity</option>
                            <option value="10-15">10-15 lac</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="manpower" class="form-label">Manpower/Salesman</label>
                        <input type="number" class="form-control" id="manpower" required>
                    </div>
                    <div class="mb-3">
                        <label for="existingManpower" class="form-label">Existing Manpower/Salesman</label>
                        <input type="number" class="form-control" id="existingManpower" required>
                    </div>
                    <div class="mb-3">
                        <label for="gstCertificate" class="form-label">Attachment (Upload GST Certificate)</label>
                        <input type="file" class="form-control" id="gstCertificate" required>
                    </div>
                    <div class="mb-3">
                        <label for="referenceName" class="form-label">Reference By: Name / Agency Name</label>
                        <input type="text" class="form-control" id="referenceName" required>
                    </div>
                    <div class="mb-3">
                        <label for="referenceCity" class="form-label">Reference By: City Name</label>
                        <input type="text" class="form-control" id="referenceCity" required>
                    </div>
                    <div class="mb-3">
                        <label for="interestReason" class="form-label">Why Are You Interested in Becoming a Dealer for OSWAL SOAP GROUP?</label>
                        <textarea class="form-control" id="interestReason" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="animated-button"><span>Submit</span><span></span></button>
                    
                    
            </div>
        </div>
        
        <div class="container">
	<header class="header">
		<h1 id="title" class="text-center">Survey Form</h1>
		<p id="description" class="text-center">
			Thank you for taking the time to help us improve the platform
		</p>
	</header>
	<div class="form-wrap">	
		<form id="survey-form">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label id="name-label" for="name">Name</label>
						<input type="text" name="name" id="name" placeholder="Enter your name" class="form-control" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label id="email-label" for="email">Email</label>
						<input type="email" name="email" id="email" placeholder="Enter your email" class="form-control" required>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label id="number-label" for="number">Age <small>(optional)</small></label>
						<input type="number" name="age" id="number" min="10" max="99" class="form-control" placeholder="Age" >
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>current role</label>
						<select id="dropdown" name="role" class="form-control" required>
							<option disabled selected value>Select</option>
							<option value="student">Student</option>
							<option value="job">Full Time Job</option>
							<option value="learner">Full Time Learner</option>
							<option value="preferNo">Prefer not to say</option>
							<option value="other">Other</option>
						</select>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Would you recommend survey to a friend?</label>
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="customRadioInline1" value="Definitely" name="customRadioInline1" class="custom-control-input" checked="">
							<label class="custom-control-label" for="customRadioInline1">Definitely</label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="customRadioInline2" value="Maybe" name="customRadioInline1" class="custom-control-input">
							<label class="custom-control-label" for="customRadioInline2">Maybe</label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="customRadioInline3" value="Not sure" name="customRadioInline1" class="custom-control-input">
							<label class="custom-control-label" for="customRadioInline3">Not sure</label>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>This survey useful yes or no?</label>
						<div class="custom-control custom-checkbox custom-control-inline">
							<input type="checkbox" class="custom-control-input" name="yes" value="yes" id="yes" checked="">
							<label class="custom-control-label" for="yes">Yes</label>
						</div>
						<div class="custom-control custom-checkbox custom-control-inline">
							<input type="checkbox" class="custom-control-input" name="no" value="no" id="no">
							<label class="custom-control-label" for="no">No</label>
						</div>
					</div>
				</div>
			</div>


			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>Leave Message</label>
						<textarea  id="comments" class="form-control" name="comment" placeholder="Enter your comment here..."></textarea>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-4">
					<button type="submit" id="submit" class="btn btn-primary btn-block">Submit Survey</button>
				</div>
			</div>

		</form>
	</div>	
</div> -->

<div class="container">
	<header class="header">
		<h1 id="title" class="text-center">Dealer Application Form</h1>
		<p id="description" class="text-center">Please fill out the form below with your personal and business details.</p>
	</header>
	<div class="form-wrap section-padding">
		<form>
			<h2 class="mb-4">Personal Details</h2>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="name" class="form-label">Name</label>
						<input type="text" class="form-control" id="name" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="age" class="form-label">Age</label>
						<input type="number" class="form-control" id="age" required>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="qualification" class="form-label">Qualification</label>
						<input type="text" class="form-control" id="qualification" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="city" class="form-label">City/Town/Village</label>
						<input type="text" class="form-control" id="city" required>
					</div>
				</div>
			</div>

			<!-- Continue personal details -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="state" class="form-label">State</label>
						<select class="form-control" id="state" required>
							<option value="">Select State</option>
							<option value="AR">Arunachal Pradesh [AR]</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="district" class="form-label">District</label>
						<select class="form-control" id="district" required>
							<option value="">Select District</option>
							<option value="Anjaw">Anjaw</option>
						</select>
					</div>
				</div>
			</div>

			<h2 class="mb-4 mt-5">Business Details</h2>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="firmName" class="form-label">Name of the Firm</label>
						<input type="text" class="form-control" id="firmName" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="firmAddress" class="form-label">Full Address of the Firm</label>
						<input type="text" class="form-control" id="firmAddress" required>
					</div>
				</div>
			</div>
			
			<!-- Continue Business Details similarly -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="currentBusinessName" class="form-label">Current Name of Business</label>
						<input type="text" class="form-control" id="currentBusinessName" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="yearsExperience" class="form-label">No. of Years Experience in Current Business</label>
						<input type="number" class="form-control" id="yearsExperience" required>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="BusinessType" class="form-label">Business Type</label>
						<input type="text" class="form-control" id="BusinessType" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="mobile" class="form-label">Mobile</label>
						<input type="number" class="form-control" id="yearsExperience" required>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="annualTurnover" class="form-label">Annual Turnover</label>
						<input type="number" class="form-control" id="annualTurnover" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="vehicleType" class="form-label">Vehicle (Type)</label>
						<input type="text" class="form-control" id="vehicleType" required>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="vehicleModel" class="form-label">Vehicle (Model)</label>
						<input type="text" class="form-control" id="vehicleModel" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="vehicleHowMany" class="form-label">Vehicle (How Many)</label>
						<input type="number" class="form-control" id="vehicleHowMany" required>
					</div>
				</div>
			</div>
            <div class="row">
            <div class="col-md-6">
					<div class="form-group">
						<label for="manpower" class="form-label">Manpower/Salesman</label>
						<input type="number" class="form-control" id="manpower" required>
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
            <div class="col-md-6">
					<div class="form-group">
						<label for="exitingmanpower" class="form-label">Existing Manpower/Salesman</label>
						<input type="number" class="form-control" id="exitingmanpower" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
                    <label for="gstCertificate" class="form-label">Attachment (Upload GST Certificate)</label>
                    <input type="file" class="form-control" id="gstCertificate" required="">
					</div>
				</div>
			</div>
            <div class="row">
            <div class="col-md-6">
					<div class="form-group">
						<label for="agencyName" class="form-label">Reference By: Name / Agency Name</label>
						<input type="text" class="form-control" id="agencyName" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
                    <label for="cityName" class="form-label">Reference By: City Name</label>
                    <input type="text" class="form-control" id="cityName" required="">
					</div>
				</div>
			</div>
            <div class="row">
            <div class="col-md-6">
					<div class="form-group">
                    <label for="interestReason" class="form-label">Why Are You Interested in Becoming a Dealer for OSWAL SOAP GROUP?</label>
                    <textarea class="form-control" id="interestReason" rows="3" required=""></textarea>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
                    <label for="businessDescription" class="form-label">Your Current Business Brief Description of Infrastructure (Storage Space, Etc.)</label>
                    <textarea class="form-control" id="businessDescription" rows="3" required=""></textarea>
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