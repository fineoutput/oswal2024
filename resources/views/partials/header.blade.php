<div class="container-fluid p-4">

    <div class="asdas">

        <div class="col-lg-8 col-8" style="display: flex; justify-content: space-between !important;">

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

                <div class="col-lg-10">

                    <nav class="nav">

                        <ul class="nav-links">

                            <li class="mobile_search_box">

                                <div class="d-flex">

                                    <input type="text" class="border-0" placeholder="Search Products..."
                                        aria-label="First name" />

                                    <span class="ps-1"><i class="fa-solid fa-magnifying-glass"></i></span>

                                </div>

                            </li>

                            <li><a href="{{ route('/') }}">HOME</a></li>

                            <li><a href="{{ route('category-list') }}">Product Category</a></li>

                            <li><a href="{{ route('product-detail', ['slug' => 'Oswal-Chana-Dal']) }}">Product Detail</a></li>

                            <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>

                            <li><a href="{{ route('cart.get-cart-details') }}">Cart</a></li>

                            <li><a href="javascript:void(0)">My Orders</a></li>

                        </ul>

                    </nav>

                </div>

            </div>

        </div>

        <div class="col-lg-4 col-4">

            <div class="row" style="display: flex; justify-content: end;">

                <div class="col-lg-8 header_expo">

                    <div style="margin-top: 2rem;"
                        class="d-flex flex-wrap justify-content-end align-items-center header_item_icon">

                        <button class="search-btn d-none d-lg-block">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                        @php
                            if(Auth::check()){

                                $identifierColumn = 'user_id';

                                $identifierValue = Auth::user()->id;

                            }else{

                                $identifierColumn = 'persistent_id';

                                $identifierValue  = sendPersistentId(request());
                                
                            }
                            $cartCount = App\Models\Cart::where($identifierColumn, $identifierValue)->count();
                        @endphp 

                        <a class="d-none d-lg-block" href="{{ route('cart.get-cart-details') }}">
                            <i class="fa-solid fa-bag-shopping"></i>
                            <p class="badge rounded-pill badge-notification bg-danger" id="cart_count">{{ $cartCount }}</p>
                        </a>

                        <a class="d-none d-lg-block" href="{{ route('wishlist.index') }}"><i
                                class="fa-solid fa-heart"></i></a>

                        <div class="dropdown">

                            <a href="#" class="dropdown-toggle" id="userDropdown">

                                <i class="fa-solid fa-user"></i>

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

                        <a><button class="order_btn btn btn-primary" data-bs-toggle="modal"data-bs-target="#basicModal">Order Now</button></a>

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

        <a href="index.html"><i class="fa-solid fa-home"></i></a>

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
