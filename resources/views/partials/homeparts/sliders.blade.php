<div class="secttion_slider">

    <h2 class="text-center">Offer Slider</h2>
{{-- sec --}}
</div>

<div class="splide_secound mb-5" style="background-image: url('{{ asset('images/navratri.png') }}');">

    <div class="splide" id="splide2">

        <div class="splide__track">

            <ul class="splide__list">

                @foreach (App\Models\Offer2::where('is_active', 1)->orderBy('id', 'desc')->limit(10)->get() as $key => $offerslider)
                
                    <li class="splide__slide">

                        <div class="slide-content">

                            <img src="{{ asset($offerslider->image) }}" alt="Slide 1 Image 1" />

                        </div>

                    </li>

                @endforeach

            </ul>

        </div>

    </div>

</div>

<div class="secttion_another_slider">

    <h2 class="text-center">Festival Slider</h2>

</div>

<div class="splide_first">

    <div class="splide" id="splide1">

        <div class="splide__track">

            <ul class="splide__list">

                @foreach (App\Models\Slider::where('is_active', 1)->orderBy('id', 'desc')->limit(10)->get() as $key => $slider)
                    
                    <li class="splide__slide">

                        <img src="{{ asset($slider->image) }}" alt="Slide {{ ++$key }}" width="100%"/>

                        <div class="slider_bg"></div>

                    </li>

                @endforeach

            </ul>

        </div>

    </div>

</div>

