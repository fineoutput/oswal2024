
<div class="container-fluid  sticky-top bg-light shadow-lg p-lg-0 p-2" style="z-index:999">

    <div class="asdas">

        <div class="col-lg-8 col-8" >

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

                            <li><a href="{{ route('/') }}" class="nav-link_color" style="font-family: 'Inter', sans-serif; font-weight: 600;">HOME</a></li>

                            <li><a href="{{route ('about_us')}}"class="nav-link_color" style="font-family: 'Inter', sans-serif; font-weight: 600;">About Us</a></li>

                            <li><a href="{{ route('category-list') }}"class="nav-link_color" style="font-family: 'Inter', sans-serif; font-weight: 600;">Products</a></li>

                            <li><a href="{{ route ('services')}}" class="nav-link_color" style="font-family: 'Inter', sans-serif; font-weight: 600;"> Services</a></li>

                            <li><a href="{{ route ('dealer_enq')}}" class="nav-link_color" style="font-family: 'Inter', sans-serif; font-weight: 600;">Dealer Enquiry</a></li>

                            <li><a href="{{ route('find_shop') }}" class="nav-link_color" style="font-family: 'Inter', sans-serif; font-weight: 600;">Find Shop</a></li>

                          

                        </ul>

                    </nav>

                </div>

            </div>

        </div>

        <div class="col-lg-4 col-4">

            <div class="row" style="display: flex; justify-content:start;">

                <div class="col-lg-8 header_expo" style="width:100%">

                    <div style="margin-top: 2rem;"
                        class="d-flex flex-wrap justify-content-lg-start justify-content-end align-items-center header_item_icon">
                     
                        <input class="input-inset me-3  d-none d-lg-block " type="text" placeholder="Search">
<!-- 
                      <button class="search-btn d-none d-lg-block me-3">
                         
                         <svg class="header_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="26" height="26" color="#000000" fill="none">
 <path d="M17.5 17.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
 <path d="M20 11C20 6.02944 15.9706 2 11 2C6.02944 2 2 6.02944 2 11C2 15.9706 6.02944 20 11 20C15.9706 20 20 15.9706 20 11Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
</svg>
                     </button> -->
                  
                        <!-- <input type="search" class="search-field d-none d-lg-block me-3" placeholder="Search …" value="" name="s" title="Search for:" /> -->
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
                        <div class="dropdown">

<a href="#" class="dropdown-toggle  icon-container" id="userDropdown" style="margin-right:2rem">

    <!-- <i class="fa-solid fa-user"></i> -->
    <svg class="header_icon nav-link_color first-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#000000" fill="none">
        <path d="M6.57757 15.4816C5.1628 16.324 1.45336 18.0441 3.71266 20.1966C4.81631 21.248 6.04549 22 7.59087 22H16.4091C17.9545 22 19.1837 21.248 20.2873 20.1966C22.5466 18.0441 18.8372 16.324 17.4224 15.4816C14.1048 13.5061 9.89519 13.5061 6.57757 15.4816Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M16.5 6.5C16.5 8.98528 14.4853 11 12 11C9.51472 11 7.5 8.98528 7.5 6.5C7.5 4.01472 9.51472 2 12 2C14.4853 2 16.5 4.01472 16.5 6.5Z" stroke="currentColor" stroke-width="1.5" />
    </svg>
    <svg class="header_icon nav-link_color second-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#000000" fill="none">
        <path d="M6.57757 15.4816C5.1628 16.324 1.45336 18.0441 3.71266 20.1966C4.81631 21.248 6.04549 22 7.59087 22H16.4091C17.9545 22 19.1837 21.248 20.2873 20.1966C22.5466 18.0441 18.8372 16.324 17.4224 15.4816C14.1048 13.5061 9.89519 13.5061 6.57757 15.4816Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M16.5 6.5C16.5 8.98528 14.4853 11 12 11C9.51472 11 7.5 8.98528 7.5 6.5C7.5 4.01472 9.51472 2 12 2C14.4853 2 16.5 4.01472 16.5 6.5Z" stroke="currentColor" stroke-width="1.5" />
    </svg>

</a>

<ul class="dropdown-menu" aria-labelledby="userDropdown">

    @auth

        <li><a class="dropdown-item" href="{{ url('/user') }}">dashBoard</a></li>

        {{-- <li><a class="dropdown-item" href="{{ url('/user') }}">Account</a></li> --}}

        <li><a class="dropdown-item" href="{{ url('/logout') }}">Logout</a></li>

    @else

        <li onclick="showModal(event)"><a class="dropdown-item" href="#">Login</a></li>
       

    @endauth


</ul>

</div>

                        <a class="d-none d-lg-block me-3 bag_icon nav-link_color icon-container" href="{{ route('cart.get-cart-details') }}">

                            <!-- <i class="fa-solid fa-bag-shopping"></i> -->
                            <svg class="header_icon first-icon " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#000000" fill="none">
    <path d="M3.06164 14.4413L3.42688 12.2985C3.85856 9.76583 4.0744 8.49951 4.92914 7.74975C5.78389 7 7.01171 7 9.46734 7H14.5327C16.9883 7 18.2161 7 19.0709 7.74975C19.9256 8.49951 20.1414 9.76583 20.5731 12.2985L20.9384 14.4413C21.5357 17.946 21.8344 19.6983 20.9147 20.8491C19.995 22 18.2959 22 14.8979 22H9.1021C5.70406 22 4.00504 22 3.08533 20.8491C2.16562 19.6983 2.4643 17.946 3.06164 14.4413Z" stroke="currentColor" stroke-width="1.5" />
    <path d="M7.5 9L7.71501 5.98983C7.87559 3.74176 9.7462 2 12 2C14.2538 2 16.1244 3.74176 16.285 5.98983L16.5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
</svg>
<svg class="header_icon second-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#000000" fill="none">
    <path d="M3.06164 14.4413L3.42688 12.2985C3.85856 9.76583 4.0744 8.49951 4.92914 7.74975C5.78389 7 7.01171 7 9.46734 7H14.5327C16.9883 7 18.2161 7 19.0709 7.74975C19.9256 8.49951 20.1414 9.76583 20.5731 12.2985L20.9384 14.4413C21.5357 17.946 21.8344 19.6983 20.9147 20.8491C19.995 22 18.2959 22 14.8979 22H9.1021C5.70406 22 4.00504 22 3.08533 20.8491C2.16562 19.6983 2.4643 17.946 3.06164 14.4413Z" stroke="currentColor" stroke-width="1.5" />
    <path d="M7.5 9L7.71501 5.98983C7.87559 3.74176 9.7462 2 12 2C14.2538 2 16.1244 3.74176 16.285 5.98983L16.5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
</svg>
                            <!-- <span class="badge rounded-pill badge-notification bg-danger" id="cart_count">{{ $cartCount }}</span> -->
                            <span class="badge  badge-notification bg-danger bag_count" id="cart_count">{{ $cartCount }}</span>
                        </a>

                        @auth
                        <a class="d-none d-lg-block wishlist_icon nav-link_color icon-container" href="{{ route('wishlist.index') }}">
                            <!-- <i class="fa-solid fa-heart"></i> -->
                            <svg class="header_icon first-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#000000" fill="none">

                                <path d="M19.4626 3.99415C16.7809 2.34923 14.4404 3.01211 13.0344 4.06801C12.4578 4.50096 12.1696 4.71743 12 4.71743C11.8304 4.71743 11.5422 4.50096 10.9656 4.06801C9.55962 3.01211 7.21909 2.34923 4.53744 3.99415C1.01807 6.15294 0.221721 13.2749 8.33953 19.2834C9.88572 20.4278 10.6588 21 12 21C13.3412 21 14.1143 20.4278 15.6605 19.2834C23.7783 13.2749 22.9819 6.15294 19.4626 3.99415Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <svg class="header_icon second-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#000000" fill="none">

<path d="M19.4626 3.99415C16.7809 2.34923 14.4404 3.01211 13.0344 4.06801C12.4578 4.50096 12.1696 4.71743 12 4.71743C11.8304 4.71743 11.5422 4.50096 10.9656 4.06801C9.55962 3.01211 7.21909 2.34923 4.53744 3.99415C1.01807 6.15294 0.221721 13.2749 8.33953 19.2834C9.88572 20.4278 10.6588 21 12 21C13.3412 21 14.1143 20.4278 15.6605 19.2834C23.7783 13.2749 22.9819 6.15294 19.4626 3.99415Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
</svg>
                            <span class="badge  badge-notification bg-danger bag_count" id="wishlist_count">{{ $count }}</span> 
                            <!-- <span class="  @if($count > 0) wishlist_dot @endif" id="wishlist_count"></span> -->

                        </a>
                
                    @else
                
                        <a class="d-none d-lg-block wishlist_icon " href="javascript:void(0)" onclick="showModal(event)">
                            <!-- <i class="fa-solid fa-heart"></i> -->
                            <svg class="header_icon " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#000000" fill="none">

                                <path d="M19.4626 3.99415C16.7809 2.34923 14.4404 3.01211 13.0344 4.06801C12.4578 4.50096 12.1696 4.71743 12 4.71743C11.8304 4.71743 11.5422 4.50096 10.9656 4.06801C9.55962 3.01211 7.21909 2.34923 4.53744 3.99415C1.01807 6.15294 0.221721 13.2749 8.33953 19.2834C9.88572 20.4278 10.6588 21 12 21C13.3412 21 14.1143 20.4278 15.6605 19.2834C23.7783 13.2749 22.9819 6.15294 19.4626 3.99415Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <span class="badge  badge-notification bg-danger bag_count" id="wishlist_count">{{ $count }}</span> 
                            <!-- <span class="  @if($count > 0) wishlist_dot @endif" id="wishlist_count"></span> -->

                        </a>
                
                    @endauth

                      

                      

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

            <!-- <button style="border: none; background: none;" class="search-btn2">

                <i style="font-size: 20px; color: red;" class="fa fa-search" id="naming" aria-hidden="true"></i>

            </button> -->
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

</div>
