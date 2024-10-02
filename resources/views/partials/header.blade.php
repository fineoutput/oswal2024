<style>
    .header_input-container {
        position: relative;
        width: 100%;
    }

    .input-simple {
        font-size: 16px;
        line-height: 1.5;
        border: 1px solid #c5cbd5;
        width: 100%;
        padding: 0.5em 2.5em 0.5em 1em;
        box-sizing: border-box;
    }

    .search-icon {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        cursor: pointer;
        pointer-events: auto; /* Ensures the icon is clickable */
    }
    .input-simple::placeholder {
        color: #838d99;
    }

    .input-simple:focus {
        outline: none;
        /* border: 1px solid #84A2FA; */
    }

    .header_icons p {
        font-size: 0.8rem;
		color:black !important
    }

    /* Media query for tablets and mobile devices */
    @media (max-width: 991px) {
        .header_input-container {
            display: none; /* Ensure opacity is 0 on tablet and mobile devices */
        }

        .header_input-container.active {
            display: block; /* Maintain opacity 0 when active on tablet and mobile devices */
        }
    }

    @media (max-width: 990px) {
        .responsive_logo {
            max-width: 30%; /* Adjust max width for tablets */
        }
    }

    @media (max-width: 768px) {
        .responsive_logo {
            max-width: 30%; /* Adjust max width for mobile devices */
        }
    }

    .header_icons svg {
        color: black;
        transition: color 0.3s ease;
    }
    .header_icons svg:hover {
        color: red;
    }
    .wishlist_icon {
        position: relative;
    }
    .count {
        width: 1rem;
        height: 1rem;
        position: absolute;
        top: 0px;
        right: 0px;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2px;
        border-radius: 50%;
        background-color: red;
    }
    .bag_icon {
        position: relative;
    }
    .count_bag {
        width: 1rem;
        height: 1rem;
        position: absolute;
        top: 0px;
        right: -8px;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2px;
        border-radius: 50%;
        background-color: red;
    }
    .nav-link {
        text-decoration: none; /* Remove underline from links */
        color: black; /* Default text color */
        font-weight: 600; /* Normal font weight */
        transition: color 0.3s ease; /* Smooth transition for color change */
    }

    .nav-link:hover {
        color: red; /* Color on hover */
    }
    a.nav-link_color {
        color: black;
        text-decoration: none;
    }

    /* Style for clicked links */
    a.nav-link_color.clicked {
        color: red; /* Change color when clicked */
        position: relative; /* Ensure positioning for pseudo-element */
    }

    /* Initial state of the mobile menu */
    .mobile-menu {
        width: 60%;
        height: 100vh;
        display: none;
        position: absolute;
        top: 0px; /* Adjust as needed */
        left: 0;
        right: 0;
        padding: 1rem;
        background: white;
        border: 1px solid #ddd;
        z-index: 1000;
    }

    /* When the menu is visible */
    .mobile-menu.show {
        display: block;
    }

    /* Style the menu items */
    .mobile-menu .nav-link {
        padding: 10px;
        text-align: start;
        display: block;
        color: #000;
        text-decoration: none;
    }

    .mobile-menu .nav-link:hover {
        background: #f8f8f8;
    }

    @media (max-width: 768px) {
        .nav-link {
            font-size: small;
            font-weight: 600;
        }
        .nav-link:hover {
            color: red;
        }
    }

    .dropdown-toggle {
        text-decoration: none;
    }
    .dropdown-toggle::after {
        display: none;
    }
    /* .dropdown-menu {
        position: absolute;
        inset: 0px auto auto 0px;
        margin: 0px;
        transform: translate3d(4px, 24.6667px, 0px);
        top: 1.5rem !important;
        left: -2rem !important;
        width: 250px;


		background-color: #fff;
        border-top: 5px solid #d19221 !important;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        z-index: 1000;
        width: 250px;
        padding: 1rem 2rem !important;
		
    } */

    .dropdown {
        position: relative;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: -100% !important;
        background-color: #fff;
        border-top: 5px solid #d19221 !important;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        z-index: 1000;
        width: 250px;
        padding: 1rem 2rem !important;

        /* Adjust width as needed */
    }

    .dropdown:hover .dropdown-menu {
        display: block;
        /* Show the dropdown menu on hover */
    }

    .dropdown-item {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
        text-decoration: none;
        transition: 0.5s;
    }

    .dropdown-item:hover {
        background-color: #fff6e6 !important;
        color: red !important;
      
    }
   .bottom_bar {
        position: fixed;
        bottom: 0;
        width: 100%;
        margin: 0 auto;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    .nav-box {
        display: flex;
        padding: 8px;
        background-color: #fff;
        box-shadow: 0px 0px 16px 0px #4444;
        border-radius: 8px;
    }

    .nav-container {
        display: flex;
        width: 100%;
        list-style: none;
        padding: 0%;
        justify-content: space-around;
    }

    .nav__item {
        display: flex;
        position: relative;
        padding: 2px;
    }

    /* .nav__item.active .nav__item-icon {
        margin-top: -26px;
        box-shadow: 0px 0px 16px 0px #4444;
    }

    .nav__item.active .nav__item-text {
        transform: scale(1);
    } */

    .nav__item-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #2f3046;
        text-decoration: none;
    }

    .nav__item-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7em;
        background-color: #fff;
        border-radius: 50%;
        height: 56px;
        width: 56px;
        transition: margin-top 250ms ease-in-out, box-shadow 250ms ease-in-out;
    }

    .nav__item-text {
        position: absolute;
        bottom: 0;
        transform: scale(0);
        transition: transform 250ms ease-in-out;
    }
</style>
@php $count = 0; if(Auth::check()){ $identifierColumn = 'user_id'; $identifierValue = Auth::user()->id; $count = App\Models\Wishlist::where('user_id', Auth::user()->id)->count(); }else{ $identifierColumn = 'persistent_id'; $identifierValue
= sendPersistentId(request()); } $cartCount = App\Models\Cart::where($identifierColumn, $identifierValue)->count(); @endphp
<!--------------------------------- Web Header Start--------------------------------------------------------- -->
<div class="container-fluid p-3 d-none d-lg-block shadow-lg sticky-top bg-light" style="z-index: 999;">
    <div class="row align-items-center">
        <div class="col-3 col-md-3">
            <div class="text-center mb-3 mb-md-0">
                <a href="{{ route('/') }}" class="nav-link_color">
                    <img
                        src="{{ asset('images/oswal-logo.png') }}"
                        alt="logo"
                        class="img-fluid a.nav-link_color { color: #000; text-decoration: none; } /* Style for clicked links */ a.nav-link_color.clicked { color: #D19221; /* Change color when clicked */ text-decoration: underline; /* Underline text when clicked */ position: relative; /* Ensure positioning for pseudo-element */ }"
                        style="max-width: 100px;"
                    />
                </a>
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="text-center mb-3 mb-md-0">
                <div class="header_input-container">

                <form action="{{ route('search') }}" method="get">
                    <input class="input-simple" type="text" placeholder="Search" name="query"/>
                    <span class="search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
                            <path fill="#838D99" d="M13.22 14.63a8 8 0 1 1 1.41-1.41l4.29 4.29a1 1 0 1 1-1.41 1.41l-4.29-4.29zm-.66-2.07a6 6 0 1 0-8.49-8.49 6 6 0 0 0 8.49 8.49z"></path>
                        </svg>
                    </span>

                </form>

                </div>
            </div>
        </div>
        <div class="col-md-3 header_icons">
            <div class="d-flex justify-content-center justify-content-md-cenetr text-center">
                <div class="me-4">
                    <div>
                        <div class="dropdown">
                            <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                                    <path
                                        d="M6.57757 15.4816C5.1628 16.324 1.45336 18.0441 3.71266 20.1966C4.81631 21.248 6.04549 22 7.59087 22H16.4091C17.9545 22 19.1837 21.248 20.2873 20.1966C22.5466 18.0441 18.8372 16.324 17.4224 15.4816C14.1048 13.5061 9.89519 13.5061 6.57757 15.4816Z"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                    <path d="M16.5 6.5C16.5 8.98528 14.4853 11 12 11C9.51472 11 7.5 8.98528 7.5 6.5C7.5 4.01472 9.51472 2 12 2C14.4853 2 16.5 4.01472 16.5 6.5Z" stroke="currentColor" stroke-width="1.5" />
                                </svg>
                            </a>

                            <ul class="dropdown-menu">
                                <!-- <li><a class="dropdown-item" href="#">Login</a></li> -->
                                @auth

                                <li><a class="dropdown-item" href="{{ url('/user') }}">dashBoard</a></li>

                                {{--
                                <li><a class="dropdown-item" href="{{ url('/user') }}">Account</a></li>
                                --}}

                                <li><a class="dropdown-item" href="{{ url('/logout') }}">Logout</a></li>

                                @else

                                <li onclick="showModal(event)"><a class="dropdown-item" href="#">Login</a></li>

                                @endauth
                            </ul>
                        </div>
                    </div>
                    @auth
                    <p>{{ Auth::User()->first_name }}</p>
                    @else
                    <p>User</p>
                    @endauth
                </div>
                @auth
                <a href="{{ route('wishlist.index') }}">
                    <div class="me-4">
                        <div class="wishlist_icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                                <path
                                    d="M19.4626 3.99415C16.7809 2.34923 14.4404 3.01211 13.0344 4.06801C12.4578 4.50096 12.1696 4.71743 12 4.71743C11.8304 4.71743 11.5422 4.50096 10.9656 4.06801C9.55962 3.01211 7.21909 2.34923 4.53744 3.99415C1.01807 6.15294 0.221721 13.2749 8.33953 19.2834C9.88572 20.4278 10.6588 21 12 21C13.3412 21 14.1143 20.4278 15.6605 19.2834C23.7783 13.2749 22.9819 6.15294 19.4626 3.99415Z"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                            </svg>
                            <div class="count wishlist_count" id="wishlist_count">{{ $count }}</div>
                        </div>
                        <p>Wishlist</p>
                    </div>
                </a>
                @else
                <a href="javascript:void(0)" onclick="showModal(event)">
                    <div class="me-4">
                        <div class="wishlist_icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                                <path
                                    d="M19.4626 3.99415C16.7809 2.34923 14.4404 3.01211 13.0344 4.06801C12.4578 4.50096 12.1696 4.71743 12 4.71743C11.8304 4.71743 11.5422 4.50096 10.9656 4.06801C9.55962 3.01211 7.21909 2.34923 4.53744 3.99415C1.01807 6.15294 0.221721 13.2749 8.33953 19.2834C9.88572 20.4278 10.6588 21 12 21C13.3412 21 14.1143 20.4278 15.6605 19.2834C23.7783 13.2749 22.9819 6.15294 19.4626 3.99415Z"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                            </svg>
                            <div class="count">0</div>
                        </div>
                        <p class="text-dark">Wishlist</p>
                    </div>
                </a>
                @endauth

                <a href="{{ route('cart.get-cart-details') }}">
                    <div class="">
                        <div class="bag_icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                                <path
                                    d="M3.06164 14.4413L3.42688 12.2985C3.85856 9.76583 4.0744 8.49951 4.92914 7.74975C5.78389 7 7.01171 7 9.46734 7H14.5327C16.9883 7 18.2161 7 19.0709 7.74975C19.9256 8.49951 20.1414 9.76583 20.5731 12.2985L20.9384 14.4413C21.5357 17.946 21.8344 19.6983 20.9147 20.8491C19.995 22 18.2959 22 14.8979 22H9.1021C5.70406 22 4.00504 22 3.08533 20.8491C2.16562 19.6983 2.4643 17.946 3.06164 14.4413Z"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                />
                                <path d="M7.5 9L7.71501 5.98983C7.87559 3.74176 9.7462 2 12 2C14.2538 2 16.1244 3.74176 16.285 5.98983L16.5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <div class="count_bag cart_count" id="cart_count">{{ $cartCount }}</div>
                        </div>
                        <p class="text-dark">Bag</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link nav-link_color" aria-current="page" href="{{ route('/') }}">HOME</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link_color" href="{{route ('about_us')}}">ABOUT US</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link_color" href="{{ route('category-list') }}">PRODUCTS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link_color" href="{{ route ('services')}}">SERVICES</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link_color" href="{{ route ('dealer_enq')}}">DEALER ENQUIRY</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link_color" href="{{ route('find_shop') }}">FIND SHOP</a>
            </li>
        </ul>
    </div>
</div>
<!--------------------------------- Web Header end--------------------------------------------------------- -->
<!--------------------------------- Mobile Header Start--------------------------------------------------------- -->
<div class="container-fluid d-lg-none d-block p-3 sticky-top shadow-lg bg-light" style="z-index: 999;">
    <div class="row align-items-center">
        <div class="col-3">
            <div>
                <svg class="mobile_menu" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="#000000" style="cursor: pointer;">
                    <path d="M4 5L20 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M4 12L20 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M4 19L20 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>
        <div class="col-6">
            <div class="text-center">
                <a href="{{ route('/') }}">
                    <img class="responsive_logo" src="{{ asset('images/oswal-logo.png') }}" alt="mobile_logo" width="40%" />
                </a>
            </div>
        </div>
        <div class="col-3">
            <div class="text-end">
                <svg class="mobile_search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" style="cursor: pointer;">
                    <path d="M17.5 17.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M20 11C20 6.02944 15.9706 2 11 2C6.02944 2 2 6.02944 2 11C2 15.9706 6.02944 20 11 20C15.9706 20 20 15.9706 20 11Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                </svg>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8 mx-auto">
            <div class="text-center">
                <div class="header_input-container mt-3">
                <form action="{{ route('search') }}" method="get">
                    <input class="input-simple" type="text" placeholder="Search"  name="query" />
                    <span class="search-icon position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
                            <path fill="#838D99" d="M13.22 14.63a8 8 0 1 1 1.41-1.41l4.29 4.29a1 1 0 1 1-1.41 1.41l-4.29-4.29zm-.66-2.07a6 6 0 1 0-8.49-8.49 6 6 0 0 0 8.49 8.49z"></path>
                        </svg>
                    </span>
                </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="mobile-menu">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="{{ route('/') }}">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route ('about_us')}}">ABOUT US</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('category-list') }}">PRODUCTS</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route ('services')}}">SERVICES</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route ('dealer_enq')}}">DEALER ENQUIRY</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('find_shop') }}">FIND SHOP</a></li>
                </ul>
				@auth
                <div class="d-flex">
                    <!-- <button class="btn btn-danger me-3">Sign Up</button> -->
                  <a href="{{ url('/user') }}">
				  <button class="btn btn-danger" >Logout</button>
				  </a>
                </div>
				@else
				<div class="d-flex">
                    <!-- <button class="btn btn-danger me-3">Sign Up</button> -->
                    <button class="btn btn-danger" onclick="showModal(event)">Login</button>
                </div>
				@endauth
            </div>
        </div>
    </div>
</div>
<!--------------------------------- Mobile Header End--------------------------------------------------------- -->

<!-- Basic Modal -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-labelledby="basicModalLabel" aria-hidden="true">
    <div class="modal-dialog sta_mode">
        <form method="POST" action="{{ route('set.location') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="basicModalLabel">Select State and City</h6>
                    <!-- Optional close button -->
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body">
                    <select name="state" id="typesstate" style="width: 100%;" onchange="getCity('{{ route('getcity') }}', 'city-container2')" required>
                        <option value="99999">Choose State</option>
                        @foreach (App\Models\State::all() as $state)
                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                        @endforeach
                    </select>
                    <select id="city-container2" name="city" class="form-control" required>
                        <option value="">----- Select City -----</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-default" data-bs-dismiss="modal" style="background:#4FD1C5; padding: 7px 17px; font-size: 15px;">Close</button> -->
                    <button type="submit" class="btn btn-primary" style="background: #4fd1c5; padding: 7px 17px; font-size: 15px;">Proceed</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- --------------------------------Bottom Bar----------------------------------------------------------------------- -->
<nav class="bottom_bar">
    <div class="nav-box d-lg-none">
        <ul class="nav-container">
            <li class="nav__item active">
                <a href="{{ url('/') }}" class="nav__item-link">
                    <div class="nav__item-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="#000000" fill="none">
                            <path d="M12 17H12.009" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M20 8.5V13.5C20 17.2712 20 19.1569 18.8284 20.3284C17.6569 21.5 15.7712 21.5 12 21.5C8.22876 21.5 6.34315 21.5 5.17157 20.3284C4 19.1569 4 17.2712 4 13.5V8.5" stroke="currentColor" stroke-width="1.5" />
                            <path d="M22 10.5L17.6569 6.33548C14.9902 3.77849 13.6569 2.5 12 2.5C10.3431 2.5 9.00981 3.77849 6.34315 6.33548L2 10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </div>
                    <span class="nav__item-text">Home</span>
                </a>
            </li>
            <li class="nav__item active">
                @auth
                <a href="{{ url('/user') }}"  class="nav__item-link">
                    <div class="nav__item-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="#000000" fill="none">
                            <path
                                d="M6.57757 15.4816C5.1628 16.324 1.45336 18.0441 3.71266 20.1966C4.81631 21.248 6.04549 22 7.59087 22H16.4091C17.9545 22 19.1837 21.248 20.2873 20.1966C22.5466 18.0441 18.8372 16.324 17.4224 15.4816C14.1048 13.5061 9.89519 13.5061 6.57757 15.4816Z"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path d="M16.5 6.5C16.5 8.98528 14.4853 11 12 11C9.51472 11 7.5 8.98528 7.5 6.5C7.5 4.01472 9.51472 2 12 2C14.4853 2 16.5 4.01472 16.5 6.5Z" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                    </div>
                    <span class="nav__item-text">Profile</span>
                </a>
                @else
                <a href="javascript:void(0)" onclick="showModal(event)" class="nav__item-link">
                    <div class="nav__item-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="#000000" fill="none">
                            <path
                                d="M6.57757 15.4816C5.1628 16.324 1.45336 18.0441 3.71266 20.1966C4.81631 21.248 6.04549 22 7.59087 22H16.4091C17.9545 22 19.1837 21.248 20.2873 20.1966C22.5466 18.0441 18.8372 16.324 17.4224 15.4816C14.1048 13.5061 9.89519 13.5061 6.57757 15.4816Z"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path d="M16.5 6.5C16.5 8.98528 14.4853 11 12 11C9.51472 11 7.5 8.98528 7.5 6.5C7.5 4.01472 9.51472 2 12 2C14.4853 2 16.5 4.01472 16.5 6.5Z" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                    </div>
                    <span class="nav__item-text">Profile</span>
                </a>
                @endauth
                
            </li>
            <li class="nav__item active">
                <a href="{{ route('wishlist.index') }}" class="nav__item-link">
                    <div class="nav__item-icon">
                        <div class="wishlist_icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                                <path
                                    d="M19.4626 3.99415C16.7809 2.34923 14.4404 3.01211 13.0344 4.06801C12.4578 4.50096 12.1696 4.71743 12 4.71743C11.8304 4.71743 11.5422 4.50096 10.9656 4.06801C9.55962 3.01211 7.21909 2.34923 4.53744 3.99415C1.01807 6.15294 0.221721 13.2749 8.33953 19.2834C9.88572 20.4278 10.6588 21 12 21C13.3412 21 14.1143 20.4278 15.6605 19.2834C23.7783 13.2749 22.9819 6.15294 19.4626 3.99415Z"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                            </svg>
                            <div class="count wishlist_count" id="wishlist_count">{{ $count }}</div>
                        </div>
                    </div>
                    <span class="nav__item-text">Wishlist</span>
                </a>
            </li>

            <li class="nav__item active">
                <a href="{{ route('cart.get-cart-details') }}" class="nav__item-link">
                    <div class="nav__item-icon">
                        <div class="bag_icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                                <path
                                    d="M3.06164 14.4413L3.42688 12.2985C3.85856 9.76583 4.0744 8.49951 4.92914 7.74975C5.78389 7 7.01171 7 9.46734 7H14.5327C16.9883 7 18.2161 7 19.0709 7.74975C19.9256 8.49951 20.1414 9.76583 20.5731 12.2985L20.9384 14.4413C21.5357 17.946 21.8344 19.6983 20.9147 20.8491C19.995 22 18.2959 22 14.8979 22H9.1021C5.70406 22 4.00504 22 3.08533 20.8491C2.16562 19.6983 2.4643 17.946 3.06164 14.4413Z"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                />
                                <path d="M7.5 9L7.71501 5.98983C7.87559 3.74176 9.7462 2 12 2C14.2538 2 16.1244 3.74176 16.285 5.98983L16.5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <div class="count_bag cart_count" id="cart_count">{{$cartCount}}</div>
                        </div>
                    </div>
                    <span class="nav__item-text">Cart</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- ----------------------------------------------end Bottom Bar------------------------------------------------ -->



<!-- Bottom bar for mobile -->
<!-- 
<div class="bottom-bar d-lg-none">
    <div class="bottom-bar-item">
        <a href="{{ url('/') }}"><i class="fa-solid fa-home"></i></a>
    </div>

    <div class="bottom-bar-item">

    <a href="product_category.html"><i class="fa-solid fa-list"></i><br>Categories</a>

    </div> 

    <div class="bottom-bar-item">
        <div class="bottom-bar-item">
            <div class="form" id="search2">
                <input type="text" class="input2" placeholder="Search Here" />
            </div>

             <button style="border: none; background: none;" class="search-btn2">

                <i style="font-size: 20px; color: red;" class="fa fa-search" id="naming" aria-hidden="true"></i>

            </button> 
            <input style="color: #d62837 !important;" type="search" class="search-field" placeholder="Search …" value="" name="s" title="Search for:" />
        </div>
    </div>

    @auth

    <div class="bottom-bar-item">
        <a class="" href="{{ route('wishlist.index') }}"><i class="fa-solid fa-heart"></i></a>
    </div>

    @else

    <div class="bottom-bar-item">
        <a href="javascript:void(0)" onclick="showModal(event)"><i class="fa-solid fa-heart"></i></a>
    </div>

    @endauth

    <div class="bottom-bar-item">
        <a href="{{ route('cart.get-cart-details') }}"><i class="fa-solid fa-shopping-cart"></i></a>
    </div>

    <div class="bottom-bar-item">
        <a href="{{ url('/user') }}"><i class="fa-solid fa-user"></i></a>
    </div>
</div> -->
@php
  $persistent =  DB::table('user_state_city')->where('persistent_id', request()->cookie('persistent_id'))->first()
@endphp

@if (!$persistent)
    
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('basicModal'), {
            backdrop: 'static', 
            keyboard: false     
        });
        myModal.show();
    });
</script>

@endif

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Toggle class on search icon click
        $(".mobile_search").click(function (event) {
            event.stopPropagation(); // Prevents the click from propagating to the window click event
            $(".header_input-container").toggleClass("active");
        });

        // Toggle class on window click
        $(window).click(function (event) {
            // Check if the click was outside the .header_input-container
            if (!$(event.target).closest(".header_input-container").length) {
                $(".header_input-container").removeClass("active");
            }
        });

        // Prevent clicks inside .header_input-container from closing it
        $(".header_input-container").click(function (event) {
            event.stopPropagation();
        });

        // Toggle class on mobile menu click
        $(".mobile_menu").click(function (event) {
            event.stopPropagation(); // Prevents the click from propagating to the window click event
            $(".mobile-menu").toggleClass("show");
        });

        // Toggle class on window click
        $(window).click(function (event) {
            // Check if the click was outside the .mobile-menu
            if (!$(event.target).closest(".mobile-menu, .mobile_menu").length) {
                $(".mobile-menu").removeClass("show");
            }
        });

        // Prevent clicks inside .mobile-menu from closing it
        $(".mobile-menu").click(function (event) {
            event.stopPropagation();
        });
    });

    const list = document.querySelectorAll(".nav__item");
    list.forEach((item) => {
        item.addEventListener("click", () => {
            list.forEach((item) => item.classList.remove("active"));
            item.classList.add("active");
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const dropdown = document.querySelector(".dropdown");
        const dropdownMenu = document.querySelector(".dropdown-menu");

        // Show the dropdown menu on mouse enter
        dropdown.addEventListener("mouseenter", function () {
            dropdownMenu.style.display = "block";
        });

        // Hide the dropdown menu on mouse leave
        dropdown.addEventListener("mouseleave", function () {
            dropdownMenu.style.display = "none";
        });
    });
</script>

