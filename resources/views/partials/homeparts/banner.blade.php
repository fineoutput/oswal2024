<section class="hero-section d-none d-lg-block">

    <div class="row no-gutters">

        @foreach (App\Models\Websliders2::where('is_active', 1)->orderBy('id', 'desc')->limit(2)->get() as $slider)

            <div class="col-lg-6 col-sm-12 sets">

                <div class="hero-head">


                    <div class="hearo-head-img" style="background-image: url('{{ asset($slider->image) }}');">

                        <p>{{ $slider->link }}</p>

                        {{-- <h3>
                        
                        Luxurious <br />
                        
                        Organic Soaps
                        
                    </h3> --}}

                    </div>

                </div>

            </div>

        @endforeach

        {{-- <div class="col-lg-6 col-sm-12 sets">
            
            <div class="hero-head-sec" style="background-image: url('{{ asset('images/222s.jpg') }}');">
                
                <div class="hearo-head-img-sec">
                    
                    <p>Elevate Your Skincare Routine</p>
                    
                    <h3>
                        
                        Luxurious <br />
                        
                        Organic Soaps
                        
                    </h3>
                    
                </div>
                
            </div>
            
        </div> --}}

    </div>

</section>


<section id="mobile_slide" class="mobile_hero splide d-lg-none">

    <div class="splide__track">

        <ul class="splide__list">

            <li class="splide__slide" style="padding: 0;">

                <img width="100%" height="100%" src="{{ asset('images/running_bannner.jpg') }}" alt="" />

            </li>

            <li class="splide__slide" style="padding: 0;">

                <img width="100%" height="100%" src="{{ asset('images/lemon_banner.jpg') }}" alt="" />

            </li>

            <li class="splide__slide" style="padding: 0;">

                <img width="100%" height="100%" src="{{ asset('images/samosa_banner.jpg') }}" alt="" />

            </li>

        </ul>

    </div>

</section>
