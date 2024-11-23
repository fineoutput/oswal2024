@extends('layouts.app') 

@section('title', $title ?? '') 

@section('content') 
<style>
    .no_products {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
</style>
{{-- @dd($productData); --}}
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

                            @forelse ($productData as $product)

                                <tr id="wishlist{{$product['product_id']}}">

                                    <form id="movetocart{{$product['product_id']}}">

                                        @csrf


                                        <input type="hidden" name="wishlist_id" value="{{ $product['wishlist_id'] }}" />

                                        <input type="hidden" name="type_price" value="{{ $product['type_price'] }}" />

                                        <input type="hidden" name="type_id" value="{{ $product['type_id'] }}" />
                                        
                                    </form>

                                    <td class="product-thumbnail">

                                        <a href="#"><img width="100px" src="{{ $product['image1'] }}" alt="product1" /></a>

                                    </td>

                                    <td class="product-name" data-title="Product"><a href="#">{{ $product['product_name'] }}</a></td>

                                    <td class="product-price" data-title="Price">{{formatPrice($product['type_price'])}}</td>

                                    <td class="product-stock-status" data-title="Stock Status"><span class="badge rounded-pill bg-success">In Stock</span></td>


                                    <td class="product-add-to-cart">

                                        <button class="animated-button" onclick="MoveTOCart('{{ $product['product_id'] }}')"><span> Add to Cart</span><span></span></button>

                                    </td>

                                    <td class="product-remove" onclick="RemoveToWishlist('{{ $product['product_id'] }}')" data-title="Remove">

                                        <a href="#"><i class="fa-solid fa-x"></i></a>

                                    </td>

                                </tr>

                            @empty
                            <tr>
                                <td colspan="6">
                                <div class="no_products">
                                No Product Found
                                <img src="{{(asset('images/no_products.webp'))}}" width="20%" alt="">
                                </div>
                                </td>
                            </tr>
                            
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection @push('scripts')

<script>
    function RemoveToWishlist(productId) {
        $.ajax({
            url: "{{ route('wishlist.destroy') }}",
            type: "GET",
            data: {
                product_id: productId,
            },
            success: function (response) {
                if(response.success){
                    $(`#wishlist${productId}`).remove();
                    $('#wishlist_count').text(response.count);
                    showNotification(response.message, 'success');
                }else{
                    showNotification(response.message, 'error');
                }
            },
            error: function (xhr) {
                console.error("An error occurred while removing from the wishlist.");
            },
        });
    }

    function MoveTOCart(productId) {
        // event.preventDefault();
        $.ajax({
            url: "{{ route('wishlist.move-to-cart') }}",
            type: 'POST',
            data: $(`#movetocart${productId}`).serialize(),
            success: function(response) {
                console.log(response);
            var wishlistCount = parseInt($('#wishlist_count').text(), 10); 
            var cartCount = parseInt($('#cart_count').text(), 10);
            if (isNaN(wishlistCount)) {
                wishlistCount = 0;
            }
            if (isNaN(cartCount)) {
                cartCount = 0; 
            }
            console.log('Wishlist Count:', wishlistCount);
            wishlistCount -= 1; 
            cartCount += 1; 

            $('.wishlist_count').text(''); 
            $('.cart_count').text('');      
            $('.wishlist_count').text(wishlistCount); 
            $('.cart_count').text(cartCount); 
            $(`#wishlist${productId}`).remove();
                showNotification(response.message, 'success');
            },
            error: function(xhr) {
                console.error('An error occurred while loading the category details and products.');
            }
        });

    }
</script>
@endpush
