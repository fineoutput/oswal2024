<section class="bb-section">
    <div id="bb-modal" class="bb-modal">
        <div class="bb-modal-content">
            <span class="bb-close" onclick="closeModal();">&times;</span>
            <section class="bb-section">
                <div class="bb-container">
                    <!-- Login Form Section -->
                    <div class="bb-user bb-signinBx">
                        <div class="bb-imgBx"><img src="{{ asset('images/banner_sect.jpeg') }}" alt="" /></div>

                        <div class="bb-formBx">
                            <!-- Login Form -->
                            <form id="loginForm" method="POST" action="{{ route('login') }}" onsubmit="handleLoginFormSubmit(event)">
                                @csrf
                                <div class="headd_logs d-flex">
                                    <img width="80px" src="{{ asset('images/oswal-logo.png') }}" alt="" />
                                    <h2 style="text-align: center; color: #e91e63; font-size: 25px;">Oswal Ecommerce</h2>
                                </div>
                                <h2>Sign In</h2>

                                <input type="number" id="phone_no" name="phone_no" placeholder="Phone" />
                                @error('phone_no')
                                <P class="error" id="lphone_no" style="color: red; margin-left: 8px;">{{ $message }}</P>
                                @enderror

                                <input type="submit" value="Login" />

                                <p class="bb-signup">
                                    Don't have an account?
                                    <a href="#" onclick="toggleForm();">Sign Up.</a>
                                </p>
                            </form>

                            <!-- Login OTP Form (Initially Hidden) -->
                            <form id="loginOtp" action="" style="display: none;">
                            <div class="headd_logs d-flex">
                                    <img width="80px" src="{{ asset('images/oswal-logo.png') }}" alt="" />
                                    <h2 style="text-align: center; color: #e91e63; font-size: 25px;">Oswal Ecommerce</h2>
                                </div>
                                <h2>Enter OTP</h2>
                                <div id="otpField">
                                    <input type="text" name="otp" placeholder="Enter OTP" />
                                    @error('otp')
                                    <P class="error" id="otpError" style="color: red; margin-left: 8px;">{{ $message }}</P>
                                    @enderror
                                </div>
                                <input type="submit" value="Submit OTP" />
                            </form>
                        </div>
                    </div>

                    <!-- Signup Form Section -->
                    <div class="bb-user bb-signupBx">
                        <div class="bb-formBx">
                            <!-- Signup Form -->
                            <form id="registerForm" method="POST" action="{{ route('register') }}" onsubmit="handleSignupFormSubmit(event)">
                                <h2>Create an account</h2>
                                @csrf
                                <input type="text" name="username" placeholder="Name" value="{{ old('username') }}" />
                                @error('username')
                                <P class="error" id="username" style="color: red;margin-left: 8px;">{{ $message }}</P>
                                @enderror

                                <input type="text" name="email" placeholder="Email Address" value="{{ old('email') }}" />
                                @error('email')
                                <P class="error" id="email" style="color: red;margin-left: 8px;">{{ $message }}</P>
                                @enderror

                                <input type="number" id="signupPhoneNo" name="phone_no" placeholder="Phone" value="{{ old('phone_no') }}" />
                                @error('phone_no')
                                <P class="error" id="phone_no" style="color: red;margin-left: 8px;">{{ $message }}</P>
                                @enderror

                                <input type="text" name="referral_code" placeholder="Referral Code" value="{{ old('referral_code') }}" />
                                @error('referral_code')
                                <P class="error" id="referral_code" style="color: red;margin-left: 8px;">{{ $message }}</P>
                                @enderror

                                <input type="submit" value="Sign Up" />

                                <p class="bb-signup">
                                    Already have an account?
                                    <a href="#" onclick="toggleForm();">Sign in.</a>
                                </p>
                            </form>

                            <!-- Signup OTP Form (Initially Hidden) -->
                            <form id="signupOtp" action="" style="display: none;">
                            <div class="headd_logs d-flex">
                                    <img width="80px" src="{{ asset('images/oswal-logo.png') }}" alt="" />
                                    <h2 style="text-align: center; color: #e91e63; font-size: 25px;">Oswal Ecommerce</h2>
                                </div>
                                <h2>Enter OTP</h2>
                                <input type="number" id="signupOtpField" name="otp" placeholder="OTP" />
                                <input type="submit" value="Submit OTP" />
                            </form>
                        </div>
                        <div class="bb-imgBx"><img src="{{ asset('images/banner_sect.jpeg') }}" alt="" /></div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>

<script>
    let toggleForm = () => {
  const container = document.querySelector('.bb-container');
  container.classList.toggle('bb-active');
};

const showModal = (event) => {
  event.preventDefault();
  document.getElementById('bb-modal').style.display = 'block';
};

const closeModal = () => {
  document.getElementById('bb-modal').style.display = 'none';
};

// Close the modal when clicking outside of it
window.onclick = function (event) {
  const modal = document.getElementById('bb-modal');
  if (event.target == modal) {
    modal.style.display = 'none';
  }
};
    // Handle Login Form Submission
    const handleLoginFormSubmit = (event) => {
        event.preventDefault(); // Prevent form from submitting
        const phoneInput = document.getElementById('phone_no').value;
        const loginForm = document.getElementById('loginForm');
        const loginOtpForm = document.getElementById('loginOtp');

        if (phoneInput) {
            // Hide the login form and show the OTP form
            loginForm.style.display = 'none';
            loginOtpForm.style.display = 'block';
        } else {
            alert('Please enter your phone number');
        }
    };

    // Handle Signup Form Submission
    const handleSignupFormSubmit = (event) => {
        event.preventDefault(); // Prevent form from submitting
        const phoneInput = document.getElementById('signupPhoneNo').value;
        const registerForm = document.getElementById('registerForm');
        const signupOtpForm = document.getElementById('signupOtp');

        if (phoneInput) {
            // Hide the register form and show the OTP form
            registerForm.style.display = 'none';
            signupOtpForm.style.display = 'block';
        } else {
            alert('Please enter your phone number');
        }
    };
</script>
