<div class="row" id="product-list-container">

    @foreach ($products as $product)
        <div class="col-lg-4 this_sectio d-none d-lg-block">

            <div class="product_category_product_part" style="position: relative;">

                <div class="product_part_upper">

                    <div class="card_upper_img" style="width: 250px;height: 250px;">

                        <img src="{{ asset($product->img2) }}" alt="Primary Image" class="first-image"
                            style="width: 100%; height: 100%;" />

                        <img src="{{ asset($product->img1) }}" alt="Primary Image" class="secound-image"
                            style="width: 100%; height: 100%;" />

                    </div>

                    <svg class="savage" width="29" height="28" viewBox="0 0 29 28" fill="none"
                        xmlns="http://www.w3.org/2000/svg">

                        <path
                            d="M28.9499 0C28.3999 0 27.9361 1.44696 27.9361 2.60412V27.9718L24.5708 25.9718L21.2055 27.9718L17.8402 25.9718L14.4749 27.9718L11.1096 25.9718L7.74436 27.9718L4.37907 25.9718L1.01378 27.9718V2.6037C1.01378 1.44655 0.549931 0 0 0H28.9499Z"
                            fill="#c92323"></path>

                        <text x="50%" y="50%" font-size="6" text-anchor="middle" alignment-baseline="central"
                            fill="#ffffff" dy=".3em">20%
                            off</text>

                    </svg>

                </div>

                <div class="product_part_lower">

                    <div class="upper_txt det_txt">

                        <h4>{{ $product->name }}</h4>

                        <div class="rates">

                            <del>

                                <p class="prev_rate">₹20</p>

                            </del>

                            <p>₹15</p>

                        </div>

                    </div>

                    <div class="upper_common d-flex">

                        <div class="upper_txt_input">

                            <select name="quality" id="qty_select">

                                <option value="type">Type</option>

                                <option value="1kg">1kg</option>

                                <option value="250gm">250gm</option>

                            </select>

                        </div>

                        <div class="upper_txt_qty det_txt_qnt">

                            <div class="quant" id="quantity-section" style="display: none;">

                                <div class="input-group det_input_grp" style="display: flex; align-items: center;">

                                    <button type="button" class="btn btn-outline-secondary btn-decrement"
                                        style="margin-right: 5px;">-</button>

                                    <input class="qv-quantity form-control quantity-input" type="number"
                                        name="quantity" min="1" value="1" size="1" step="1"
                                        style="width: 60px; text-align: center;" />

                                    <button type="button" class="btn btn-outline-secondary btn-increment"
                                        style="margin-left: 5px;">+</button>

                                </div>

                            </div>

                            <div class="add_to_cart_button" id="add-to-cart-section">

                                <a href="#">

                                    <button>

                                        <span>Add</span>

                                    </button>

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    @endforeach

    @foreach ($products as $product)

        <div class="col-lg-6 col-6 this_sectio d-lg-none" style="padding: 0.2rem;">

            <div class="product_category_product_part"
                style="position: relative; border: 1px solid #b6afaf; border-radius: 10px;">

                <div class="product_part_upper">

                    <div class="card_upper_img">

                        <img src="{{ asset($product->img2) }}" alt="Primary Image" class="second-image"
                            style="width: 100%; height: 114px;" />

                    </div>

                    <svg class="savage" width="29" height="28" viewBox="0 0 29 28" fill="none"
                        xmlns="http://www.w3.org/2000/svg">

                        <path
                            d="M28.9499 0C28.3999 0 27.9361 1.44696 27.9361 2.60412V27.9718L24.5708 25.9718L21.2055 27.9718L17.8402 25.9718L14.4749 27.9718L11.1096 25.9718L7.74436 27.9718L4.37907 25.9718L1.01378 27.9718V2.6037C1.01378 1.44655 0.549931 0 0 0H28.9499Z"
                            fill="#c92323"></path>

                        <text x="50%" y="50%" font-size="6" text-anchor="middle" alignment-baseline="central"
                            fill="#ffffff" dy=".3em">20%

                            off</text>

                    </svg>

                </div>

                <div class="product_part_lower mobile_part_lower">

                    <div class="upper_txt det_txt mobile_det">

                        <h4>{{ $product->name }}</h4>

                    </div>

                    <div class="mobile_common">

                        <div class="d-flex flex-wrap" style="font-size: 0.8rem; gap: 5px;">

                            <del style="color: red;">25</del>

                            <p>₹15</p>

                        </div>

                    </div>

                    <div class="upper_txt_qty det_txt_qnt mobile_input_btn">

                        <div class="upper_txt_input mobile_input">

                            <select name="quality" id="qty_select">

                                <option value="type">Type</option>

                                <option value="1kg">1kg</option>

                                <option value="250gm">250gm</option>

                            </select>

                        </div>

                        <div class="button-container addButton">

                            <span class="buttonText">Add</span>

                            <div class="controlButtons hidden">

                                <div class="increment-decrement">

                                    <button class="btn-decrease">-</button>

                                    <span class="number-display">1</span>

                                    <button class="btn-increase">+</button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    @endforeach

    <div class="pagination-links" style="display: flex;justify-content: center;">
        {{ $products->links('vendor.pagination.bootstrap-4') }}
    </div>

</div>
