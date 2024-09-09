<section class="bb-section">

    <div id="bb-modal" class="bb-modal">

        <div class="bb-modal-content">

            <span class="bb-close" onclick="closeModal();">&times;</span>

            <section class="bb-section">

                <div class="bb-container">

                    <div class="bb-user bb-signinBx">

                        <div class="bb-imgBx"><img src="{{ asset('images/banner_sect.jpeg') }}" alt="" /></div>

                        <div class="bb-formBx">

                            <form id="loginForm" method="POST" action="{{ route('login') }}">

                                @csrf

                                <div class="headd_logs d-flex">

                                    <img width="80px" src="{{ asset('images/oswal-logo.png') }}" alt="" />

                                    <h2 style="text-align: center; color: #e91e63; font-size: 25px;">Oswal Ecommerce

                                    </h2>

                                </div>

                                <h2>Sign In</h2>

                                <input type="number" name="phone_no" placeholder="Phone" />

                                @error('phone_no')
                                
                                    <P class="error" id="lphone_no" style="color: red;margin-left: 8px;">{{ $message }}</P>
                                
                                @enderror

                                <input type="submit" name="" value="Login" />

                                <p class="bb-signup">

                                    Don't have an account ?


                                    <a href="#" onclick="toggleForm();">

                                        Sign Up.

                                    </a>

                                </p>

                            </form>

                        </div>

                    </div>

                    <div class="bb-user bb-signupBx hide">

                        <div class="bb-formBx">

                            <form id="registerform" method="POST" action="{{ route('register') }}">

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
                                
                                <input type="number" name="phone_no" placeholder="Phone" value="{{ old('phone_no') }}" />
                                
                                @error('phone_no')
                                
                                    <P class="error" id="phone_no" style="color: red;margin-left: 8px;">{{ $message }}</P>
                                
                                @enderror
                                
                                <input type="text" name="referral_code" placeholder="Referral Code" value="{{ old('referral_code') }}" />
                                
                                @error('referral_code')
                                
                                    <P class="error" id="referral_code" style="color: red;margin-left: 8px;">{{ $message }}</P>
                                
                                 @enderror
                                 

                                {{-- <input type="number" name="otp" placeholder="OTP" /> --}}

                                <input type="submit" name="" value="Sign Up" />

                                <p class="bb-signup">

                                    Already have an account ?

                                    <a href="#" onclick="toggleForm();">Sign in.</a>

                                </p>

                            </form>

                        </div>

                        <div class="bb-imgBx"><img src="{{ asset('images/banner_sect.jpeg') }}" alt="" /></div>

                    </div>

                    <div class="bb-user bb-signupBx">

                        <div class="bb-formBx">

                            <form id="OtpForm" method="POST" action="{{ route('register.otp') }}">

                                <h2>Enter Your Otp </h2>

                                @csrf

                                <input type="text" name="otp" placeholder="Name" value="{{ old('username') }}" />
                                
                                @error('otp')
                                
                                    <P class="error" id="username" style="color: red;margin-left: 8px;">{{ $message }}</P>
                                
                                @enderror

                                <input type="submit" name="" value="Sign Up" />

                            </form>

                        </div>

                        <div class="bb-imgBx"><img src="{{ asset('images/banner_sect.jpeg') }}" alt="" /></div>

                    </div>

                </div>

            </section>

        </div>
    </div>
</section>

@push('scripts')
{{-- <script>

    function register($url) {
        $.ajax({
            url: $url,
            type: 'POST',
            data: $('#registerform').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    console.log(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    // Reset previous errors
                    $('.error').hide();
                    // Loop through errors and display them
                    $.each(errors, function(field, messages) {
                        $('#' + field).text(messages[0]).show();
                    });
                } else {
                    console.error('An error occurred during registration.');
                }
            }
        });
    }
</script> --}}
@endpush
