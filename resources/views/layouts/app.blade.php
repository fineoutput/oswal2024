<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link href="https://fonts.googleapis.com/css2?family=Marcellus&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://unpkg.com/ionicons@5.5.2/dist/css/ionicons.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet" />

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />

    <title>@yield('title', 'Oswal')</title>

</head>

<body>

    @include('partials.header')

        <main role="main">

            @yield('content')

        </main>

    @include('partials.footer')

    @include('partials.login')

</body>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script> 

    <script src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"> </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>

    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js" type="module"></script>

    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js" nomodule></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/splidejs/4.1.4/js/splide.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize AOS (Animate On Scroll)
            AOS.init();

            // Initialize Splide sliders
            new Splide("#third_sliders", {
                type: "loop",
                perPage: 3,
                autoplay: true,
                interval: 3000,
            }).mount();

            new Splide("#mobile_slide", {
                type: "loop",
                perPage: 1,
                heightRatio: 0.5,
            }).mount();

            new Splide("#matters", {
                type: "loop",
                perPage: 1,
                height: "100%",
                direction: "ttb",
                arrows: true,
                pagination: true,
            }).mount();
        });

        // Function to check if an element is in viewport
        function isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }

        // Function to add animation class
        function addAnimationClass() {
            const animatedElements = document.querySelectorAll(".animated-section");

            animatedElements.forEach((element) => {
                if (isInViewport(element)) {
                    element.classList.add("animate__animated", "animate__fadeInUp");
                } else {
                    element.classList.remove("animate__animated", "animate__fadeInUp");
                }
            });
        }

        // Event listener for scroll
        window.addEventListener("scroll", addAnimationClass);

        // Initial check in case elements are already in view
        document.addEventListener("DOMContentLoaded", addAnimationClass);
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
</html>
