<style>
        #alv_slider {
    /* height: 100vh; */
    width: 100%;
}

.settts {
    position: relative;
    height: 100vh !important;
    width: 100%;
    background-size: cover;
    background-repeat: no-repeat;
}

.stationary-text {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 10;
}

.slide-text {
    color: #f8161c;
    font-size: 6rem;
    text-align:center;
    font-family: popins;
    text-shadow: 2px 2px 7px rgba(0, 0, 0, 0.8);
}
@media (min-width: 900px) and (max-width: 1280px) {
    .settts {
        background-size: contain;
    }
    .stationary-text {
    bottom: -30px;
}
}
@media (min-width: 312px) and (max-width: 900px) {
    .settts {
        height: 200px !important;
        background-size: contain;
    }
    .slide-text {
    font-size: 1rem;
}
.module {
    margin-top: -90px !important;
    padding: 83px 0px;
}
.home-orangepatch-yellow {
    font-size: 15px;
}
.home-orangepatch {
    font-size: 15px;
}
}
    </style>
<!-- <section class="hero-section d-none d-lg-block">

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

</section> -->

<div id="alv_slider" class="splide">
        <div class="splide__track">
            <ul class="splide__list">
            @foreach (App\Models\Websliders2::where('is_active', 1)->orderBy('id', 'desc')->get() as $slider)
                <li class="splide__slide settts" style="background-image: url('{{ asset($slider->image) }}');">
            @endforeach      
                <!-- </li>
                <li class="splide__slide settts" style="background-image: url('{{ asset('images/web_slide2.png') }}');">
                    
                </li>
                <li class="splide__slide settts" style="background-image: url('{{ asset('images/web_slide3.png') }}'); ">
                    
                </li> -->
                <!-- Add more slides as needed -->
            </ul>
        </div>
        <div class="stationary-text">
        <div class="slide-text">Oswal <span><img style="width: 10%;" src="{{asset('images/oswal-logo.png')}}" alt=""></span> Soap</div>
    </div>
    </div>

<!-- <section id="mobile_slide" class="mobile_hero splide d-lg-none">
    
    <div class="splide__track">
        
        <ul class="splide__list">
            
            @foreach (App\Models\Websliders2::where('is_active', 1)->orderBy('id', 'desc')->limit(10)->get() as $slider)
                <li class="splide__slide" style="padding: 0;">

                    <img width="100%" height="100%" src="{{ asset($slider->image) }}" alt="" />

                </li>
            @endforeach

        </ul>

    </div>

</section> --> -->
@push('scripts')
<script>
        document.addEventListener('DOMContentLoaded', function () {
            new Splide('#alv_slider', {
                type       : 'loop',
                heightRatio: 1,
                drag       : true, // Allows for drag to scroll
                pagination : false,
                arrows     : false,
            }).mount();
        });
</script>
@endpush