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
<!-- Include SweetAlert2 CSS and JS via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">




    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Oswal')</title>

</head>

<body>

    @include('partials.header')

    <main role="main">

        @yield('content')

    </main>

    @include('partials.footer')

    @include('partials.login')

    @include('partials.notifaction')

</body>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>

<script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js" type="module"></script>

<script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js" nomodule></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/splidejs/4.1.4/js/splide.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('js/app.js') }}"></script>

<script>
    function addToCart($pid) {

        event.preventDefault();
        $.ajax({
            url: "{{ route('cart.add-to-cart') }}",
            type: 'POST',
            data: $(`#addtocart${$pid}`).serialize(),
            success: function(response) {

                if(response.success){

                      $('#cart_count').text(response.count);
                     showNotification(response.message, 'success');
                }else{
                    showNotification(response.message, 'error');
                }

            },
            error: function(xhr) {
                if(!response.success){
                     showNotification(response.message, 'error');
                }else{
                    showNotification('An error occurred while loading the category details and products.', 'error');
                }

            }
        });

    }

    function toggleWishList(productId) {

        event.preventDefault();

        let $wishlistIcon = $(`.wishlist-icon[onclick="toggleWishList(${productId})"] i`);

        if ($wishlistIcon.hasClass('fa-regular')) {

            $.ajax({
                url: "{{ route('wishlist.store') }}",
                type: 'POST',
                data: $(`#addtocart${productId}`).serialize(),

                success: function(response) {
                    if(response.success) {
                        $wishlistIcon.removeClass('fa-regular hollow_icon').addClass('fa-solid colored_icon');
                        $wishlistIcon.css('color', '#f20232');

                        $('#wishlist_count').text(response.count);
                        
                        showNotification(response.message, 'success');
                    }else{
                        showNotification(response.message, 'error');
                    }
                },
                error: function(xhr) {
                    console.error('An error occurred while adding to the wishlist.');
                }
            });
        } else {

            $.ajax({
                url: "{{ route('wishlist.destroy') }}",
                type: 'GET',
                data: {
                    product_id: productId
                },
                success: function(response) {

                    if(response.success) {

                        $wishlistIcon.removeClass('fa-solid colored_icon').addClass('fa-regular hollow_icon');
                        $wishlistIcon.css('color', '#cdd5e5');

                        
                        $('#wishlist_count').text(response.count);


                        
                        
                    }else{
                        showNotification(response.message, 'error');
                    }
                },
                error: function(xhr) {
                    console.error('An error occurred while removing from the wishlist.');
                }
            });
        }
    }

    function showNotification(message, type) {

        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;

        // Create the remove button
        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-btn';
        removeBtn.innerHTML = '&times;';
        removeBtn.addEventListener('click', () => notification.remove());

        // Append the remove button to the notification
        notification.appendChild(removeBtn);

        // Append the notification to the body or a specific container
        document.body.appendChild(notification);

        // Animate and remove after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    function getCity(url, cityContainerId) {
       
        const stateSelectorId = cityContainerId === 'city-container1' ? '#addressstate' : '#typesstate';
        const selectedId = $(stateSelectorId).val();

        if (selectedId) {

            $.ajax({
                url: url,
                type: "GET",
                data: { state_id: selectedId },
                success: function(response) {
                    
                    if (Array.isArray(response)) {
                    
                        let optionsHtml = '<option value="">----- Select City -----</option>';
                        
                        $.each(response, function(key, value) {
                            optionsHtml += `<option value="${value.id}">${value.city_name}</option>`;
                        });

                        $(`#${cityContainerId}`).html(optionsHtml);

                    } else {

                        console.error('Unexpected response format:', response);

                    }

                },
                error: function(xhr) {
                    console.error("Error:", xhr.responseText);
                }
            });

        } else {
        
            $(`#${cityContainerId}`).empty().append('<option value="">----- Select City -----</option>');
        }
    }


</script>

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



@stack('scripts')

</html>
