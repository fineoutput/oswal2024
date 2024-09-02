@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

    <div class="wish_main_sect">

        <div class="container">

            <div class="row">

                <div class="col-lg-12 col-sm-12 col-md-12">

                    <div class="wish_title">

                        <h2 class="text-center">WISH LIST</h2>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="section">

        <div class="container">

            <div class="row">

                <div class="col-12">

                    <div class="table-responsive wishlist_table">

                        <table class="table">

                            <thead>

                                <tr>

                                    <th class="product-thumbnail">&nbsp;</th>

                                    <th class="product-name">Product</th>

                                    <th class="product-price">Price</th>

                                    <th class="product-stock-status">Stock Status</th>

                                    <th class="product-add-to-cart">Cart</th>

                                    <th class="product-remove">Remove</th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <td class="product-thumbnail">

                                        <a href="#"><img width="100px" src="images/mirch.jpg" alt="product1" /></a>

                                    </td>

                                    <td class="product-name" data-title="Product"><a href="#">Oswal Mirch powder</a>
                                    </td>

                                    <td class="product-price" data-title="Price">₹ 20</td>

                                    <td class="product-stock-status" data-title="Stock Status"><span
                                            class="badge rounded-pill bg-success">In Stock</span></td>


                                    <td class="product-add-to-cart">

                                        <button class="animated-button"><span> Add to Cart</span><span></span></button>

                                    </td>

                                    <td class="product-remove" data-title="Remove">

                                        <a href="#"><i class="fa-solid fa-x"></i></a>

                                    </td>

                                </tr>

                                <tr>

                                    <td class="product-thumbnail">

                                        <a href="#"><img width="100px" src="images/haldi.jpg" alt="product2" /></a>

                                    </td>

                                    <td class="product-name" data-title="Product">

                                        <a href="#">Oswal Haldi Powder</a>

                                    </td>

                                    <td class="product-price" data-title="Price">
                                        ₹ 25
                                    </td>

                                    <td class="product-stock-status" data-title="Stock Status">

                                        <span class="badge rounded-pill bg-success">In Stock</span>

                                    </td>

                                    <td class="product-add-to-cart">

                                        <button class="animated-button">
                                            <span> Add to Cart</span><span></span>
                                        </button>

                                    </td>

                                    <td class="product-remove" data-title="Remove">

                                        <a href="#"><i class="fa-solid fa-x"></i></a>

                                    </td>

                                </tr>

                                <tr>


                                    <td class="product-thumbnail">

                                        <a href="#"><img width="100px" src="images/dhaniya.jpg" alt="product3" /></a>

                                    </td>

                                    <td class="product-name" data-title="Product">
                                        <a href="#">Oswal Dhaiya Powder</a>
                                    </td>

                                    <td class="product-price" data-title="Price">₹ 25</td>

                                    <td class="product-stock-status" data-title="Stock Status">
                                        <span class="badge rounded-pill bg-success">In Stock</span>
                                    </td>

                                    <td class="product-add-to-cart">

                                        <button class="animated-button">
                                            <span> Add to Cart</span><span></span>
                                        </button>

                                    </td>

                                    <td class="product-remove" data-title="Remove">

                                        <a href="#"><i class="fa-solid fa-x"></i></a>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
