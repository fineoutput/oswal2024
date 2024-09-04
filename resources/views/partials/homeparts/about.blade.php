<div class="renwew_sect section-padding mb-5"

    style="background-image: url(https://www.bikaji.com/pub/media/wysiwyg/about_bg.jpg); margin-top: -10px;">

    <div class="container">

        <div class="row">

            <div class="col-lg-12 col-sm-12 col-md-12 renwer_upper">

                <div class="renew_head">

                    <h2 class="module-title text-center">Oswal</h2>

                    <div class="renew_head_img">

                        <img src="{{ asset('images/roya.') }}png" alt="" />

                    </div>

                    <div class="module-subtitle">

                        <br />

                        OSWAL SOAP GROUP With Over 65 Years Of Experience And More Than 150 Million Satisfied Customers,

                        Oswal Soap Group Became One Of The Largest Manufacturers Of The Daily-Use Commodities. We

                        Provide Superior

                        Quality Products To The Consumers

                    </div>

                </div>

            </div>

        </div>

    </div>

    <img class="coach" src="{{ asset('images/small.png') }}" class="image-top" alt="Backgroung Image" />

    <section class="module module_aboutus active"

        style="background-color: #51d5b0; padding-top: 72px; margin-top: 250px;">

        <!--class removed hidden-xs-->

        <img src="{{ asset('images/b.png') }}" class="floating balloon_left" alt="Balloon" />

        <div class="row">

            <div class="col-lg-12 col-sm-12 col-md-12">

                <div class="abt_time d-flex justify-content-center">

                    <div class="abt_time_img animate__animated animate__fadeInUp">

                        <img class="celeb_img" src="{{ asset('images/68.png') }}" alt="" />

                        <div class="insider_time">

                            <div class="insider_time_cust">

                                <h4>15cr+</h4>

                                <p>Customers</p>

                            </div>

                            <div class="insider_time_city">

                                <h4>200+</h4>

                                <p>Cities</p>

                            </div>

                            <div class="insider_time_retail">

                                <h4>2.5 Lac+</h4>

                                <p>Retailers</p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>
<section class="module"

    style="background-image: url(https://www.bikaji.com/pub/media/wysiwyg/3.png); margin-top: -160px; z-index: 9; background-size: cover; background-position: 30% 50%; text-align: center;">


    <img src="{{ asset('images/b.png') }}" class="floating balloon_right" alt="Balloon" />


    <div class="home-orangepatch">

        PRESERVING CULTURE<br />

        THROUGH ETHNIC-CLEANING

    </div>

    <div class="home-orangepatch-yellow">FOR OVER 68 YEARS.</div>

</section>


<section class="module active"

    style="background-image: url('{{ asset('images/4-Recovered.jpg') }}'); margin-top: -100px; background-position: center bottom; background-size: cover;">

    <div class="container">

        <div class="row">

            <div class="col-sm-6">

                <h5 class="font-alt"

                    style="font-size: 30px; font-weight: bold; font-family: 'Cormorant', serif !important;">ABOUT US -

                    Oswal Soap</h5>

                <br />

                <p style="line-height: 26px; font-size: 14px;">

                    Oswal Soap Group was established by Late Shri Uttamchand Deshraj Jain in 1956 in Johari Bazaar,

                    Jaipur with his hard work and efforts. The soap’s high quality was the key to attract people and

                    made it their

                    first choice. Later on, Late Shri Uttamchand Deshraj Jain’s sons took a step forward and got the

                    business registered and copyright as “Oswal Soap Group” and started handling his whole business.

                </p>

            </div>

        </div>

    </div>

</section>

<div class="splide_secound_set mb-5">
    
    <div class="splide" id="splide3">
        
        <div class="splide__track">
            
            <ul class="splide__list">
                
                @foreach (App\Models\Offer::where('is_active', 1)->orderBy('id', 'desc')->limit(10)->get() as $key => $offerslider)

                    <li class="splide__slide">
                        
                        <div class="slide-content">
                            
                            <img src="{{ asset($offerslider->image) }}" alt="Slide 1 Image 1" />
                            
                        </div>
                        
                    </li>

                @endforeach
                
                {{-- <li class="splide__slide">
                    
                    <div class="slide-content">
                        
                        <img src="{{ asset('images/banner_sect.png') }}" alt="Slide 2 Image 2" />

                    </div>
                    
                </li>
                
                <li class="splide__slide">
                    
                    <div class="slide-content">
                        
                        <img src="{{ asset('images/banner_sect2.png') }}" alt="Slide 2 Image 3" />
                        
                    </div>
                    
                </li> --}}
                
                <!-- Add more slides as needed -->
                
            </ul>
            
        </div>
        
    </div>
    
</div>
