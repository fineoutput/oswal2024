@php
   $testimonials = App\Models\Testimonial::where('is_active', 1)->orderBy('id', 'desc')->limit(10)->get();

@endphp

@if (count($testimonials)>0)
    
<section class="testtimonial_sect section_padding">

    <div class="container">

        <div class="row">

            <div class="col-lg-12 col-sm-12 col-md-12">

                <div class="testimoni_section">

                    <div class="testimoni_section_title text-center">

                        <h2>Testimonials</h2>

                    </div>

                </div>

            </div>

            <div id="product-splide_name" class="splide">

                <div class="splide__track">

                    <ul class="splide__list">

                        @forelse ($testimonials as $value)
                            
                        <li class="splide__slide">

                            <div class="card set_card">

                                <div class="img">

                                    <img src="{{ asset($value->image) }}" alt="img" draggable="false" />

                                </div>

                                <h2>Blanche Pearson</h2>

                                <span class="text-center">{{ $value->description }}</span>

                                <div class="rating">

                                    @php
                                        $ratings = [
                                            5 => 'Awesome',
                                            4 => 'Great',
                                            3 => 'Very good',
                                            2 => 'Good',
                                            1 => 'Bad'
                                        ];

                                        $currentRating = $value->rating;

                                    @endphp

                                    @foreach ($ratings as $value => $title)

                                    <input type="radio" id="star{{ $value }}" name="rating" value="{{ $value }}" {{ $value <= $currentRating ? 'checked' : '' }} />

                                    <label class="star" for="star{{ $value }}" title="{{ $title }}" aria-hidden="true"></label>

                                    @endforeach
                                    
                                </div>

                            </div>

                        </li>

                        @empty
                            Not Found 
                        @endforelse

                        {{-- <li class="splide__slide">

                            <div class="card set_card">

                                <div class="img">

                                    <img src="https://t4.ftcdn.net/jpg/03/50/40/93/240_F_350409330_2bqhjowfBmrqEia5U8lBsGrvD7h8EIo6.jpg" alt="img" draggable="false" />

                                </div>

                                <h2>Blanche Pearson</h2>

                                <span class="text-center">Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa enim tenetur exercitationem.</span>

                                <div class="rating">

                                    <input type="radio" id="star5" name="rating" value="5" />

                                    <label class="star" for="star5" title="Awesome" aria-hidden="true"></label>

                                    <input type="radio" id="star4" name="rating" value="4" />

                                    <label class="star" for="star4" title="Great" aria-hidden="true"></label>

                                    <input type="radio" id="star3" name="rating" value="3" />

                                    <label class="star" for="star3" title="Very good" aria-hidden="true"></label>

                                    <input type="radio" id="star2" name="rating" value="2" />

                                    <label class="star" for="star2" title="Good" aria-hidden="true"></label>

                                    <input type="radio" id="star1" name="rating" value="1" />

                                    <label class="star" for="star1" title="Bad" aria-hidden="true"></label>

                                </div>

                            </div>

                        </li>

                        <li class="splide__slide">

                            <div class="card set_card">

                                <div class="img">

                                    <img src="https://t4.ftcdn.net/jpg/03/50/40/93/240_F_350409330_2bqhjowfBmrqEia5U8lBsGrvD7h8EIo6.jpg" alt="img" draggable="false" />

                                </div>

                                <h2>Blanche Pearson</h2>

                                <span class="text-center">Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa enim tenetur exercitationem.</span>

                                <div class="rating">

                                    <input type="radio" id="star5" name="rating" value="5" />

                                    <label class="star" for="star5" title="Awesome" aria-hidden="true"></label>

                                    <input type="radio" id="star4" name="rating" value="4" />

                                    <label class="star" for="star4" title="Great" aria-hidden="true"></label>

                                    <input type="radio" id="star3" name="rating" value="3" />

                                    <label class="star" for="star3" title="Very good" aria-hidden="true"></label>

                                    <input type="radio" id="star2" name="rating" value="2" />

                                    <label class="star" for="star2" title="Good" aria-hidden="true"></label>

                                    <input type="radio" id="star1" name="rating" value="1" />

                                    <label class="star" for="star1" title="Bad" aria-hidden="true"></label>

                                </div>

                            </div>

                        </li> --}}

                        <!-- Repeat for other slides -->
                        <!-- Make sure to use the same format for other items -->
                    </ul>

                </div>

            </div>

        </div>

    </div>

</section>

@endif

