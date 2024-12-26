<style>
 
 .modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 999999; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0, 0, 0, 0.35); /* Black w/ opacity */
 }
 
 /* Modal Content (Image) */
 .modal-content {
  margin: auto;
  display: flex;
  /* width: 80%; */
  max-width: 700px;
 }
 
 /* The Close Button */
 .close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
 }
 
 .close:hover,
 .close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
 }
 
 
 
 
 /* Modal Content */
 .modal-content {
     position: relative;
     background-color: #fefefe;
     margin: auto;
     padding: 0;
     border: 1px solid #888;
     width: 80%;
     box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
     animation-name: animatetop;
     animation-duration: 0.4s;
     display: flex; /* Add this to make the content a flex container */
     flex-direction: column;
     border-radius: 20px;
     -webkit-animation-name: zoom;
     -webkit-animation-duration: 0.6s;
     animation-name: zoom;
     animation-duration: 0.6s;
     justify-content: center;
     align-items: center;
 }
 
 @-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)}
  to {-webkit-transform:scale(1)}
 }
 
 @keyframes zoom {
  from {transform:scale(0)}
  to {transform:scale(1)}
 }
 /* Modal Left (Form) */
 .modal-left {
     width: 50%; /* Set the width to 50% */
     padding: 20px; /* Add some padding */
 }
 
 /* Modal Right (Image) */
 .modal-right {
     width: 100%; /* Set the width to 50% */
 }
 
 /* Close Button */
 .close {
     color: #000000;
     float: right;
     font-size: 28px;
     font-weight: bold;
 }
 
 .close:hover,
 .close:focus {
     color: black;
     text-decoration: none;
     cursor: pointer;
 }
 .modal-right img {
     height: 100%;
     padding: 10px;
     border-radius: 30px;
 }
 .modal-left input[type="submit"] {
     width: 100%;
     border-radius: 15px;
 }
 .modal-left input {
     border: 1px solid #cdd6df;
     padding: 10px;
     border-radius: 20px;
     font-weight: 700;
     font-size: 13px;
 }
 .modal-left h4 {
     margin-bottom: 0px;
 }
 .modal-left p {
     margin-bottom: 20px;
 }
 @media (max-width: 600px){
  .modal-content {
    flex-direction: column;
  }
  .modal-left{
    width: 100%;
  }
  .modal-right{
    width: 100%;
  }
  .modal-left input {
    padding: 10px;
    margin-bottom: 10px;
}
 }
    </style>

<div id="myModal" class="modal">
    <div class="modal-content">
        <div class="modal-left">
            {{-- <h4 style="text-align: center;">Oswal Products</h4> --}}
        </div>
        <div class="modal-right">
            @if($latestPopupImage->web_image)
            <img src="{{ asset($latestPopupImage->web_image) }}" alt="Popup Image" style="width:100%;">
            @else
            <p>No Image Found</p>
            @endif
        </div>
        <span class="close">&times;</span>
    </div>
</div>
<section class="module"

    style="background-image: url('{{ asset('images/3.webp') }}'); margin-top: -160px; z-index: 9; background-size: cover; background-position: 30% 50%; text-align: center;">


    <img src="{{ asset('images/b.webp') }}" loading="lazy" class="floating balloon_right" alt="Balloon" />


    <div class="home-orangepatch">

        PRESERVING CULTURE<br />

        THROUGH ETHNIC-CLEANING

    </div>

    <div class="home-orangepatch-yellow">FOR OVER 68 YEARS.</div>
  

</section>


<section class="module active"

    style="background-image: url('{{ asset('images/4-Recovered.webp') }}'); margin-top: -100px; background-position: center bottom; background-size: cover;">

    <div class="container">

        <div class="row">

            <div class="col-sm-6">

                <h5 class="font-alt"

                    style="font-size: 30px; font-weight: bold; font-family: 'Cormorant', serif !important;">ABOUT US -

                    Oswal Soap</h5>

                <br />

                <p style="line-height: 26px; font-size: 18px;">

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

<!-- <div class="container-fluid effect" style="margin:1.5rem 0rem;">
<img src="{{asset('images/oswal-68 (1).png')}}" width="100%" alt="">
</div> -->
<div class="container-fluid mt-2 mb-2 year_section" >
    <div class="row">
        <div class="col-lg-3 p-0">
        <iframe class="year_section_video"  src="https://www.youtube.com/embed/JQ6TS7Y5cHg?si=8xbB9DprV9ByiXS2" title="YouTube video player" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>  </div>
        <div class="col-lg-6 since_year p-0">
    <img class="year-bg" src="{{asset('images/oswal_gropup.webp')}}" class="year-bg" alt="">
</div>
        <div class="col-lg-3 p-0">
            <iframe class="year_section_video2" src="https://www.youtube.com/embed/g2ZRAzwYVwc?si=yAz6sMLLDKfFU7s4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
    </div>
</div>
<div class="splide_secound_set mb-5" style="background-image: url('{{ asset('images/navratri1.webp') }}');">
    
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

<!-- <div class="video_about_sect" >
<div class="container-fluid naminfs">
<iframe width="100%" height="100%" src="https://www.youtube.com/embed/n1TFv3Gxi4E?si=pUaKpl4Jyr44s_A8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
</div>
</div> -->
<div class="totalcontainer" data-aos="fade-up" data-aos-duration="1000"> 
        <div class="laya-please layer-1"></div>
        <div class="laya-please layer-2"></div>
        <div class="container1">
        <div class="laya-please layer-3" 
     style="background-image: url('{{ asset('images/lay3.png') }}');">
</div>

<div class="laya-please layer-4"
 style="background-image: url('{{ asset('images/lay4-2.png') }}');
 ">
</div>
            <div class="laya-please layer-5"
            style="background-image: url('{{ asset('images/lay5-2.png') }}');
 "></div>
            <div class="laya-please layer-6"></div>
        </div>
        <div class="container2">
            <div class="laya-please layer-7" style="background-image: url('{{ asset('images/lay7-2.webp') }}');   "></div>
            <div class="laya-please layer-8"></div>
        </div>
    </div>

    <script>
        const elems = document.querySelectorAll('.laya-please');
        const layer2 = document.querySelector('.layer-2');
        const layer3 = document.querySelector('.layer-3');
        const layer4 = document.querySelector('.layer-4');
        const layer5 = document.querySelector('.layer-5');
        const layer6 = document.querySelector('.layer-6');
        const layer7 = document.querySelector('.layer-7');
        const layer8 = document.querySelector('.layer-8');

        setTimeout(function () {
            elems.forEach(function (elem) {
                elem.style.animation = "none";
            });
        }, 1500);

        document.body.addEventListener('mousemove', function (e) {
            if (!e.currentTarget.dataset.triggered) {
                elems.forEach(function (elem) {
                    if (elem.getAttribute('style')) {
                        elem.style.transition = "all .5s";
                        elem.style.transform = "none";
                    }
                });
            }
            e.currentTarget.dataset.triggered = true;

            let width = window.innerWidth / 2;
            let mouseMoved2 = ((width - e.pageX) / 50);
            let mouseMoved3 = ((width - e.pageX) / 40);
            let mouseMoved4 = ((width - e.pageX) / 30);
            let mouseMoved5 = ((width - e.pageX) / 20);
            let mouseMoved6 = ((width - e.pageX) / 10);
            let mouseMoved7 = ((width - e.pageX) / 5);

            layer3.style.transform = "translateX(" + mouseMoved2 + "px)";
            layer4.style.transform = "translateX(" + mouseMoved3 + "px)";
            layer5.style.transform = "translateX(" + mouseMoved4 + "px)";
            layer6.style.transform = "translateX(" + mouseMoved5 + "px)";
            layer7.style.transform = "translateX(" + mouseMoved6 + "px)";
            layer8.style.transform = "translateX(" + mouseMoved7 + "px)";
        });

        document.body.addEventListener('mouseleave', function () {
            elems.forEach(function (elem) {
                elem.style.transition = "all .5s";
                elem.style.transform = "none";
            });
        });

        document.body.addEventListener('mouseenter', function () {
            elems.forEach(function (elem) {
                setTimeout(function () {
                    elem.style.transition = "none";
                }, 500);
            });
        });
    </script>
<script>
    window.onload = function () {
        // Check if the popup has been shown before
        if (!localStorage.getItem("popupShown")) {
            setTimeout(function () {
                var modal = document.getElementById("myModal");
                if (modal) {
                    modal.style.display = "block";
                }
                // Set a flag in localStorage to remember the popup was shown
                localStorage.setItem("popupShown", "true");
            }, 3000);
        }
    };

    var span = document.getElementsByClassName("close")[0];
    if (span) {
        span.onclick = function () {
            var modal = document.getElementById("myModal");
            if (modal) {
                modal.style.display = "none";
            }
        };
    }

    window.onclick = function (event) {
        var modal = document.getElementById("myModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
</script>