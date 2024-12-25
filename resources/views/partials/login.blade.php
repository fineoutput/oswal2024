<style>
    .resend_button {
    max-width: 100px;
    background: #FF9800;
    color: #fff;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 1px;
    transition: 0.5s;
    border: none;
    height: 41px;
}
</style>
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

                                <div class="headd_logs d-flex">

                                    <img width="80px" src="{{ asset('images/oswal-logo.png') }}" alt="" />

                                    <h2 style="text-align: center; color: #e91e63; font-size: 25px;">Oswal Ecommerce</h2>

                                </div>

                                <h2>Sign In</h2>

                                <input type="number" id="phone_no" name="phone_no" placeholder="Phone"  maxlength="10" oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);"  />
                        
                                <p class="error" id="lphone_no" style="color: red; margin-left: 8px;"></p>
                             
                                <input type="submit" value="Login" />

                                <p class="bb-signup">
                                    Don't have an account? <a href="#" onclick="toggleForm();">Sign Up.</a>
                                </p>

                            </form>

                            <!-- Login OTP Form (Initially Hidden) -->
                            <form id="loginOtp" style="display: none;" onsubmit="SubmitOtpForm(event)">

                                <div class="headd_logs d-flex">

                                    <img width="80px" src="{{ asset('images/oswal-logo.png') }}" alt="" />

                                    <h2 style="text-align: center; color: #e91e63; font-size: 25px;">Oswal Ecommerce</h2>
                                </div>
                                <h2 >Enter OTP</h2>
                                <p class="error" id="lsucessmsg" style="color: green; margin-left: 8px;">

                                <div id="otpField">

                                    <input type="text" id="loginOtpv" name="otp" placeholder="Enter OTP" />

                                    <p class="error" id="lotpError" style="color: red; margin-left: 8px;"> </p>
                                </div>

                                <p id="loginResendButton"></p>
                                <input type="submit" value="Submit OTP" />
                                <button class="resend_button" type="button" onclick="handleLoginFormSubmit(event)">Resend OTP</button>
                                
                            </form>


                        </div>

                    </div>

                    <div class="bb-user bb-signupBx">

                        <div class="bb-formBx">

                            <form id="registerForm" onsubmit="handleSignupFormSubmit(event)">

                                <h2>Create an account</h2>

                                <input type="text" name="username" placeholder="Name" />
                              
                                <p class="error" id="username" style="color: red; margin-left: 8px;"> </p>
                               

                                <input type="text" name="email" placeholder="Email Address" value="{{ old('email') }}" />
                              
                                <p class="error" id="email" style="color: red; margin-left: 8px;"> </p>
                               

                                <input type="number" id="signupPhoneNo" name="contact" placeholder="Phone" value="{{ old('phone_no') }}" />
                               
                                <p class="error" id="contact" style="color: red; margin-left: 8px;"> </p>


                                <input type="text" name="referral_code" placeholder="Referral Code" value="{{ old('referral_code') }}" />

                                <p class="error" id="referral_code" style="color: red; margin-left: 8px;"> </p>


                                <input type="submit" value="Sign Up" />

                                <p class="bb-signup">Already have an account? <a href="#" onclick="toggleForm();">Sign in.</a> </p>

                            </form>

                            <!-- Signup OTP Form (Initially Hidden) -->
                            <form id="signupOtpForm" style="display: none;" onsubmit="SubmitSignupOtpForm(event)">

                                <div class="headd_logs d-flex">
                                    
                                    <img width="80px" src="{{ asset('images/oswal-logo.png') }}" alt="Oswal Ecommerce Logo" />
                                    
                                    <h2 style="text-align: center; color: #e91e63; font-size: 25px;">Oswal Ecommerce</h2>
                                    
                                </div>
                            
                                <h2>Enter OTP</h2>
                                
                                <p class="error" id="ssucessmsg" style="color: green; margin-left: 8px;"></p>
                            
                                <input type="text" id="signupOtpField" name="otp" placeholder="OTP" required />
                            
                                <p class="error" id="sotpError" style="color: red; margin-left: 8px;"></p>
                                <p id="signupResendButton"></p>
                                <input type="submit" value="Submit OTP" />
                                <button class="resend_button" type="button" onclick="handleSignupFormSubmit(event)">Resend OTP</button>
                            
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
        const container = document.querySelector(".bb-container");
        container.classList.toggle("bb-active");
    };

    const showModal = (event) => {
        event.preventDefault();
        document.getElementById("bb-modal").style.display = "block";
    };

    const closeModal = () => {
        document.getElementById("bb-modal").style.display = "none";
    };

    // Close the modal when clicking outside of it
    window.onclick = function (event) {
        const modal = document.getElementById("bb-modal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    // Handle Login Form Submission
    // const handleLoginFormSubmit = (event) => {

    //     event.preventDefault();

    //     const phoneInput = document.getElementById("phone_no").value; 

    //     const loginForm = document.getElementById("loginForm");

    //     const loginOtpForm = document.getElementById("loginOtp"); 

    //     const errorLphone = document.getElementById("lphone_no");

    //     const lsucessmsg = document.getElementById("lsucessmsg"); 

    //     // Clear previous error message
    //     errorLphone.textContent = '';

    //     lsucessmsg.textContent = '';

    //     if (phoneInput) {

    //         $.ajax({
    //             url: "{{ route('login') }}",
    //             type: 'POST',
    //             data: $('#loginForm').serialize(), 

    //             success: function(response) {

    //                 if (response.success) {
                      
    //                     loginForm.style.display = "none";

    //                     loginOtpForm.style.display = "block";
                        
    //                     lsucessmsg.textContent = response.message;

    //                 } else {
                        
    //                     errorLphone.textContent = response.message || 'Something went wrong, please try again.';
    //                 }
    //             },
    //             error: function(xhr) {
    //                 if (!response.success) {

    //                   errorLphone.textContent = response.message

    //                 }else{

    //                     console.error(xhr.responseText);

    //                     errorLphone.textContent = 'An error occurred. Please try again.';
    //                 }
                   
    //             }
    //         });

    //     } else {
          
    //         errorLphone.textContent = "Please enter your phone number.";

    //     }
    // };


    const handleLoginFormSubmit = (event) => {
    event.preventDefault();

    const phoneInput = document.getElementById("phone_no").value;
    const loginForm = document.getElementById("loginForm");
    const loginOtpForm = document.getElementById("loginOtp");
    const errorLphone = document.getElementById("lphone_no");
    const lsucessmsg = document.getElementById("lsucessmsg");

    // Get the Resend OTP button
    const resendButton = document.getElementById("loginResendButton");

    // Clear previous error message
    errorLphone.textContent = '';
    lsucessmsg.textContent = '';

    if (phoneInput) {
        $.ajax({
            url: "{{ route('login') }}",
            type: 'POST',
            data: $('#loginForm').serialize(),

            success: function(response) {
                if (response.success) {
                    // Hide the login form and show the OTP form
                    loginForm.style.display = "none";
                    loginOtpForm.style.display = "block";

                    lsucessmsg.textContent = response.message;

                    // Start the 60-second countdown for the resend OTP button
                    startResendTimer(resendButton);

                } else {
                    errorLphone.textContent = response.message || 'Something went wrong, please try again.';
                }
            },
            error: function(xhr) {
                errorLphone.textContent = 'An error occurred. Please try again.';
                console.error(xhr.responseText);
            }
        });

    } else {
        errorLphone.textContent = "Please enter your phone number.";
    }
};

// Function to start the 60-second countdown
function startResendTimer(button) {
    let countdownSeconds = 60; // 60-second countdown

    // Disable the Resend OTP button
    button.disabled = true;

    // Update the button text with the remaining time
    button.textContent = `Resend OTP (${countdownSeconds}s)`;

    // Start the countdown
    const countdownInterval = setInterval(() => {
        countdownSeconds--;

        // Update the button text
        button.textContent = `Resend OTP (${countdownSeconds}s)`;

        // Once the countdown reaches 0, re-enable the button
        if (countdownSeconds <= 0) {
            clearInterval(countdownInterval);
            button.disabled = false;
            button.textContent = 'Resend OTP'; // Reset the button text
        }
    }, 1000); // Update every second (1000 milliseconds)
}



    const SubmitOtpForm = (event) => {

        event.preventDefault();

        const otp = document.getElementById("loginOtpv").value; 

        const lotpError = document.getElementById("lotpError"); 

        lotpError.textContent = '';

        if (otp) {
            $.ajax({
                url: "{{ route('login.otp') }}", 
                type: 'POST',
                data: $('#loginOtp').serialize(),
            
                success: function(response) {
                    // console.log(response);

                    if (response.success) {

                        showNotification(response.message, 'success');

                        // window.location.href = response.redirect_url;
                        closeModal();
                        window.location.reload();
                        
                    } else {
                        
                        lotpError.textContent = response.message || 'Invalid OTP, please try again.';
                    }
                },
                error: function(xhr) {

                    if (!response.success) {

                       sotpError.textContent = response.message

                    }else{

                        console.error(xhr.responseText); 
    
                        lotpError.textContent = 'An error occurred. Please try again later.';
                    }
                }

            });

        } else {
        
            lotpError.textContent = 'Please enter your OTP.';
        }
    }


    // Handle Signup Form Submission
    // const handleSignupFormSubmit = (event) => {

    //     event.preventDefault(); 

    //     // registerForm.style.display = "none";
    //     // signupOtpForm.style.display = "block";
    //     const phoneInput = document.getElementById("signupPhoneNo").value;

    //     const registerForm = document.getElementById("registerForm");

    //     const signupOtpForm = document.getElementById("signupOtpForm");

    //     const sphone_no = document.getElementById("contact");

    //     const ssucessmsg = document.getElementById("ssucessmsg"); 

    //     document.querySelectorAll('.error').forEach(el => el.textContent = '');
        
    //     if (phoneInput) {

    //        $.ajax({
    //             url: "{{ route('register') }}",
    //             type: 'POST',
    //             data: $('#registerForm').serialize(), 

    //             success: function(response) {
      
    //                 if (response.success) {
                      
    //                     registerForm.style.display = "none";

    //                     signupOtpForm.style.display = "block";

    //                     ssucessmsg.textContent = response.message;

    //                 } else {

    //                     const errors = response.message;

    //                     for (const field in errors) {
                            
    //                         if (errors.hasOwnProperty(field)) {
                               
    //                             document.getElementById(field).textContent = errors[field][0];
    //                         }
    //                     }
    //                 }
    //             },
    //             error: function(xhr) {

    //                 if (!response.success) {

    //                     sphone_no.textContent = response.message

    //                 }else{

    //                     console.error(xhr.responseText);

    //                     sphone_no.textContent = 'An error occurred. Please try again.';
    //                 }
                    
    //             }

    //         });

           
    //     } else {

    //         sphone_no.textContent = "Please enter your phone number";

    //     }
    // };


    const handleSignupFormSubmit = (event) => {
    event.preventDefault();

    const phoneInput = document.getElementById("signupPhoneNo").value;

    const registerForm = document.getElementById("registerForm");
    const signupOtpForm = document.getElementById("signupOtpForm");

    const sphone_no = document.getElementById("contact");
    const ssucessmsg = document.getElementById("ssucessmsg");

    // const resendButton = document.getElementById("loginResendButton");

    document.querySelectorAll('.error').forEach(el => el.textContent = '');

    if (phoneInput) {

        $.ajax({
            url: "{{ route('register') }}",
            type: 'POST',
            data: $('#registerForm').serialize(),

            success: function(response) {
                if (response.success) {
                    // Hide the register form and show the OTP form
                    registerForm.style.display = "none";
                    signupOtpForm.style.display = "block";

                    // Display success message
                    ssucessmsg.textContent = response.message;

                    // Start the 60-second countdown for Resend OTP button
                    startResendTimer(document.getElementById("signupResendButton"));

                } else {
                    const errors = response.message;
                    for (const field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            document.getElementById(field).textContent = errors[field][0];
                        }
                    }
                }
            },
            error: function(xhr) {
                if (!response.success) {
                    sphone_no.textContent = response.message;
                } else {
                    console.error(xhr.responseText);
                    sphone_no.textContent = 'An error occurred. Please try again.';
                }
            }

        });

    } else {
        sphone_no.textContent = "Please enter your phone number";
    }
};

// Function to start the 60-second countdown for Resend OTP button
function startResendTimer(button) {
    let countdownSeconds = 60; // 60-second countdown

    // Disable the Resend OTP button
    button.disabled = true;

    // Update the button text with the remaining time
    button.textContent = `Resend OTP (${countdownSeconds}s)`;

    // Start the countdown
    const countdownInterval = setInterval(() => {
        countdownSeconds--;

        // Update the button text
        button.textContent = `Resend OTP (${countdownSeconds}s)`;

        // Once the countdown reaches 0, re-enable the button
        if (countdownSeconds <= 0) {
            clearInterval(countdownInterval);
            button.disabled = false;
            button.textContent = 'Resend OTP'; // Reset the button text
        }
    }, 1000); // Update every second (1000 milliseconds)
}


    const SubmitSignupOtpForm = (event) => {

        event.preventDefault();

        const otp = document.getElementById("signupOtpField").value; 

        const sotpError = document.getElementById("sotpError"); 

        sotpError.textContent = '';

        if (otp) {
            $.ajax({
                url: "{{ route('register.otp') }}", 
                type: 'POST',
                data: $('#signupOtpForm').serialize(),
            
                success: function(response) {
                    
                    if (response.success) {

                        showNotification(response.message, 'success');

                        // window.location.href = response.redirect_url;
                        closeModal();
                        window.location.reload();

                    } else {
                        
                        sotpError.textContent = response.message || 'Invalid OTP, please try again.';
                    }
                },
                error: function(xhr) {

                    if (!response.success) {

                        sotpError.textContent = response.message

                    }else{

                        console.error(xhr.responseText); 
    
                        sotpError.textContent = 'An error occurred. Please try again later.';
                    }

                }

            });

        } else {

            sotpError.textContent = 'Please enter your OTP.';
        }
    }


</script>
