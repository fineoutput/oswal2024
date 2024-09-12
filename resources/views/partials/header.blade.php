<div class="container-fluid  sticky-top bg-light shadow-lg p-lg-0 p-2" style="z-index:999">

    <div class="asdas">

        <div class="col-lg-9 col-8" >

            <div class="row" style="align-items: center; justify-content: space-between;">

                <div class="col-lg-2 col-6 d-flex mobile_header">

                    <div class="menu-toggle">

                        <a href="#" class="menu-btn"><i class="fa-solid fa-bars"></i></a>

                    </div>
                    <a href="{{ route('/') }}">
                    <img class="mobile_logo img-responsive logo" src="{{ asset('images/oswal-logo.png') }}"
                        alt="" />
                        </a>
                </div>

                <div class="col-lg-10 d-flex  justify-content-center ">

                    <nav class="nav">

                        <ul class="nav-links"style="font-family: 'Inter', sans-serif; font-weight: 700;">

                            <!-- <li class="mobile_search_box">

                                <div class="d-flex">

                                    <input type="text" class="border-0" placeholder="Search Products..."
                                        aria-label="First name" />

                                    <span class="ps-1"><i class="fa-solid fa-magnifying-glass"></i></span>

                                </div>

                            </li> -->

                            <li><a href="{{ route('/') }}" style="font-family: 'Inter', sans-serif; font-weight: 600;">HOME</a></li>

                            <li><a href="{{route ('about_us')}}"style="font-family: 'Inter', sans-serif; font-weight: 600;">About Us</a></li>

                            <li><a href="{{ route('category-list') }}"style="font-family: 'Inter', sans-serif; font-weight: 600;">Products</a></li>

                            <li><a href="{{ route ('services')}}"style="font-family: 'Inter', sans-serif; font-weight: 600;"> Services</a></li>

                            <li><a href="{{ route ('dealer_enq')}}"style="font-family: 'Inter', sans-serif; font-weight: 600;">Dealer Enquiry</a></li>

                            <li><a href="{{ route('find_shop') }}"style="font-family: 'Inter', sans-serif; font-weight: 600;">Find Shop</a></li>

                          

                        </ul>

                    </nav>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-4">

            <div class="row" style="display: flex; justify-content:center;">

                <div class="col-lg-8 header_expo" style="width:100%">

                    <div style="margin-top: 2rem;"
                        class="d-flex flex-wrap justify-content-center align-items-center header_item_icon">
                        <button class="search-btn d-none d-lg-block me-3">
                            <!-- <i class="fa fa-search" aria-hidden="true"></i> -->
                            <svg class="header_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" color="#000000" fill="none">
    <path d="M17.5 17.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    <path d="M20 11C20 6.02944 15.9706 2 11 2C6.02944 2 2 6.02944 2 11C2 15.9706 6.02944 20 11 20C15.9706 20 20 15.9706 20 11Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
</svg>
                        </button>
                        @php
                            $count = 0;

                            if(Auth::check()){

                                $identifierColumn = 'user_id';

                                $identifierValue = Auth::user()->id;

                                $count = App\Models\Wishlist::where('user_id', Auth::user()->id)->count();

                            }else{

                                $identifierColumn = 'persistent_id';

                                $identifierValue  = sendPersistentId(request());
                                
                            }
                            $cartCount = App\Models\Cart::where($identifierColumn, $identifierValue)->count();

                        @endphp 

                        <a class="d-none d-lg-block me-3 bag_icon" href="{{ route('cart.get-cart-details') }}">

                            <!-- <i class="fa-solid fa-bag-shopping"></i> -->
                            <svg class="header_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" color="#000000" fill="none">
                                <path d="M3.06164 15.1933L3.42688 13.1219C3.85856 10.6736 4.0744 9.44952 4.92914 8.72476C5.78389 8 7.01171 8 9.46734 8H14.5327C16.9883 8 18.2161 8 19.0709 8.72476C19.9256 9.44952 20.1414 10.6736 20.5731 13.1219L20.9384 15.1933C21.5357 18.5811 21.8344 20.275 20.9147 21.3875C19.995 22.5 18.2959 22.5 14.8979 22.5H9.1021C5.70406 22.5 4.00504 22.5 3.08533 21.3875C2.16562 20.275 2.4643 18.5811 3.06164 15.1933Z" stroke="currentColor" stroke-width="1.5" />
                                <path d="M7.5 8L7.66782 5.98618C7.85558 3.73306 9.73907 2 12 2C14.2609 2 16.1444 3.73306 16.3322 5.98618L16.5 8" stroke="currentColor" stroke-width="1.5" />
                                <path d="M15 11C14.87 12.4131 13.5657 13.5 12 13.5C10.4343 13.5 9.13002 12.4131 9 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <!-- <span class="badge rounded-pill badge-notification bg-danger" id="cart_count">{{ $cartCount }}</span> -->
                            <span class="badge  badge-notification bg-danger bag_count" id="cart_count">{{ $cartCount }}</span>
                        </a>

                        <a class="d-none d-lg-block me-3 wishlist_icon" href="{{ route('wishlist.index') }}">
                            <!-- <i class="fa-solid fa-heart"></i> -->
                            <svg class="header_icon " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" color="#000000" fill="none">

                                <path d="M19.4626 3.99415C16.7809 2.34923 14.4404 3.01211 13.0344 4.06801C12.4578 4.50096 12.1696 4.71743 12 4.71743C11.8304 4.71743 11.5422 4.50096 10.9656 4.06801C9.55962 3.01211 7.21909 2.34923 4.53744 3.99415C1.01807 6.15294 0.221721 13.2749 8.33953 19.2834C9.88572 20.4278 10.6588 21 12 21C13.3412 21 14.1143 20.4278 15.6605 19.2834C23.7783 13.2749 22.9819 6.15294 19.4626 3.99415Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            
                            <span class="  @if($count > 0) wishlist_dot @endif" id="wishlist_count"></span>

                        </a>

                        <div class="dropdown">

                            <a href="#" class="dropdown-toggle" id="userDropdown">

                                <!-- <i class="fa-solid fa-user"></i> -->
                                <svg class="header_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" color="#000000" fill="none">
                                    <path d="M6.57757 15.4816C5.1628 16.324 1.45336 18.0441 3.71266 20.1966C4.81631 21.248 6.04549 22 7.59087 22H16.4091C17.9545 22 19.1837 21.248 20.2873 20.1966C22.5466 18.0441 18.8372 16.324 17.4224 15.4816C14.1048 13.5061 9.89519 13.5061 6.57757 15.4816Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16.5 6.5C16.5 8.98528 14.4853 11 12 11C9.51472 11 7.5 8.98528 7.5 6.5C7.5 4.01472 9.51472 2 12 2C14.4853 2 16.5 4.01472 16.5 6.5Z" stroke="currentColor" stroke-width="1.5" />
                                </svg>

                            </a>

                            <ul class="dropdown-menu" aria-labelledby="userDropdown">

                                @auth

                                    <li><a class="dropdown-item" href="{{ url('/user') }}">Profile</a></li>

                                    <li><a class="dropdown-item" href="{{ url('/user') }}">Account</a></li>

                                    <li><a class="dropdown-item" href="{{ url('/logout') }}">Logout</a></li>

                                @else

                                    <li onclick="showModal(event)"><a class="dropdown-item" href="#">Login</a></li>

                                @endauth


                            </ul>

                        </div>

                        <div class="form d-none d-lg-block" id="search">

                            <input type="text" class="input" placeholder="Search Here" />

                        </div>

                        <!-- <a><button class="order_btn btn btn-primary" data-bs-toggle="modal"data-bs-target="#basicModal">Order Now</button></a> -->

                        <!-- Basic Modal -->
                        <div class="modal fade" id="basicModal" tabindex="-1" aria-labelledby="basicModalLabel"

                            aria-hidden="true">

                            <div class="modal-dialog sta_mode">

                                <form method="POST" action="{{ route('set.location') }}">

                                    @csrf

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h6 class="modal-title" id="basicModalLabel">Select State and City</h6>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

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
                                            <button type="button" class="btn btn-default" data-bs-dismiss="modal" style="background:#4FD1C5; padding: 7px 17px; font-size: 15px;">Close</button>
                                            <button type="submit" class="btn btn-primary" style="background:#4FD1C5; padding: 7px 17px; font-size: 15px;">Proceed</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Bottom bar for mobile -->

<div class="bottom-bar d-lg-none">

    <div class="bottom-bar-item">

        <a href="{{ url('/') }}"><i class="fa-solid fa-home"></i></a>

    </div>

    <!-- <div class="bottom-bar-item">

    <a href="product_category.html"><i class="fa-solid fa-list"></i><br>Categories</a>

    </div> -->

    <div class="bottom-bar-item">

        <div class="bottom-bar-item">

            <div class="form" id="search2">

                <input type="text" class="input2" placeholder="Search Here" />

            </div>

            <button style="border: none; background: none;" class="search-btn2">

                <i style="font-size: 20px; color: red;" class="fa fa-search" id="naming" aria-hidden="true"></i>

            </button>

        </div>

    </div>

    <div class="bottom-bar-item">

        <a class="" href="{{ route('wishlist.index') }}"><i class="fa-solid fa-heart"></i></a>

    </div>

    <div class="bottom-bar-item">

        <a href="{{ route('cart.get-cart-details') }}"><i class="fa-solid fa-shopping-cart"></i></a>

    </div>

    <div class="bottom-bar-item">

        <a href="{{ url('/user') }}"><i class="fa-solid fa-user"></i></a>

    </div>

</div>
