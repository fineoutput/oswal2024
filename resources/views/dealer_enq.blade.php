@extends('layouts.app')

@section('title', $title ?? '')

@section('content')


<div class="container">
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
                </form>
            </div>
        </div>
        
    </div>
    
    @endsection