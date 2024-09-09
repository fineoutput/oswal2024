<div class="container-fluid p-4">

    <div class="asdas">

        <div class="col-lg-8 col-8" style="display: flex; justify-content: space-between !important;">

            <div class="row" style="align-items: center; justify-content: space-between;">

                <div class="col-lg-2 col-6 d-flex mobile_header">

                    <div class="menu-toggle">

                        <a href="#" class="menu-btn"><i class="fa-solid fa-bars"></i></a>

                    </div>

                    <img class="mobile_logo img-responsive logo" src="{{ asset('images/oswal-logo.png') }}" alt="" />

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

                            <li><a href="{{ route('product-detail' ,['slug' => 'Oswal-Chana-Dal']) }}">Product Detail</a></li>

                            <li><a href="{{ route('wislist') }}">Wishlist</a></li>

                            <li><a href="">Cart</a></li>

                            <li><a href="{{ route('checkout') }}">Checkout</a></li>

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

                        <a class="d-none d-lg-block" href="cart.html"><i class="fa-solid fa-bag-shopping"></i><span class="badge rounded-pill badge-notification bg-danger">9</span></a>

                        <a class="d-none d-lg-block" href="wishlist_page.html"><i class="fa-solid fa-heart"></i></a>

                        <div class="dropdown">

                            <a href="#" class="dropdown-toggle" id="userDropdown">

                                <i class="fa-solid fa-user"></i>

                            </a>

                            <ul class="dropdown-menu" aria-labelledby="userDropdown">

                                <li><a class="dropdown-item" href="myorder_detail.html">Profile</a></li>

                                <li><a class="dropdown-item" href="myorder_detail.html">Account</a></li>

                                <li onclick="showModal(event)"><a class="dropdown-item" href="#">Login</a></li>

                            </ul>

                        </div>

                        <div class="form d-none d-lg-block" id="search">
    <input type="text" class="input" placeholder="Search Here" />
</div>

                        <a><button class="order_btn btn btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal">Order Now</button></a>

<!-- Basic Modal -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-labelledby="basicModalLabel" aria-hidden="true">
  <div class="modal-dialog sta_mode">
    <form class="">

      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="basicModalLabel">Select State and City</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <select name="state" id="state" style="width: 100%;" required>
                <option value="99999">Choose State</option>
                <option value="1">State 1</option>
                <option value="2">State 2</option>
                <option value="3">State 3</option>
                <option value="99999">Others</option>
            </select>

            <select name="city" id="city" style="width: 100%; margin-top: 15px;" required>
                <option value="99999">Choose State First</option>
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

        <a class="" href="wishlist_page.html"><i class="fa-solid fa-heart"></i></a>

    </div>

    <div class="bottom-bar-item">

        <a href="cart.html"><i class="fa-solid fa-shopping-cart"></i></a>

    </div>

    <div class="bottom-bar-item">

        <a href="myorder_detail.html"><i class="fa-solid fa-user"></i></a>

    </div>

</div>

