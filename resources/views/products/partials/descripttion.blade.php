<div class="details-product-content">

    <div class="prdd_name">

        <h2 class="details-product-title">{{ $product->name }}</h2>

        <div class="wishlist_icons">

            <a href="#"><i class="fa-regular fa-heart hollow_icon" style="color: #cdd5e5; font-size: 40px;"></i></a>

            <a href="#"><i class="fa-solid fa-heart colored_icon" style="color: #f20232; display: none; font-size: 40px;"></i></a>

        </div>

    </div>

    <a href="#" class="details-product-link">visit oswal store</a>

    <div class="details-product-rating">

        {!! renderStarRating(2) !!}
        
    </div>
    

    <div class="details-product-price">

        <p class="details-last-price">Market Price: <span>₹50</span></p>

        <p class="details-new-price">Selling Price: <span>₹40</span></p>

    </div>

    <div class="details-product-detail">
        <h2>about this item:</h2>
        {{-- <button class="accordion-toggle">Show More</button> --}}
        <div class="collapsible-content">
            <p>{{ $product->long_desc }}</p>
            {{-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur, perferendis eius.
                Dignissimos, labore suscipit. Unde.</p> --}}
        </div>
        <ul>
            {{-- <li>Type: <span>Cleaner</span></li> --}}
            <li>Available: <span>in stock</span></li>
            <li>Category: <span> {{ $product->category->name }}</span></li>
            {{-- <li>Shipping Area: <span>All over the world</span></li> --}}
            <li>Shipping Fee: <span>Free</span></li>
        </ul>
    </div>
    <select name="quality" id="qty_select" style="width: 30%; border: 1px solid #d1caca;">
        <option value="type">250gm</option>
        <option value="1kg">1kg</option>
        <option value="250gm">500gm</option>
    </select>

    <div class="details-purchase-info">
        <div class="set_insider">
            <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2 ripple"
                onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                <i class="fas fa-minus"></i>
            </button>

            <input style="border: 1px solid #d8172863 !important;" id="form1" min="0" name="quantity"
                value="1" type="number" class="form-control form-control-sm carts_puts" />

            <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2 ripple_set"
                onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                <i class="fas fa-plus"></i>
            </button>
        </div>
        <!-- <input type="number" min="0" value="1"> -->

        <button type="button" class="details-btn">Add to Cart <i class="fas fa-shopping-cart"></i></button>
        <!-- <button type="button" class="details-btn"><i class="fa-solid fa-heart colored_icon"
                        style="color: #f20232; display:none;"></i>Add to Wishlist </button> -->
    </div>

    <div class="details-social-links">
        <p class="m-0">Share At:</p>
        <a href="#">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="#">
            <i class="fa-brands fa-x-twitter"></i>
        </a>
        <a href="#">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="#">
            <i class="fab fa-whatsapp"></i>
        </a>
        <a href="#">
            <i class="fab fa-pinterest"></i>
        </a>
    </div>
</div>
