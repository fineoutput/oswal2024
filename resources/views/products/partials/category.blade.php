<div class="category_product_sect">

    <div class="inner_category_product_sect">

        <div class="category mobile_category">

            @foreach ($categorys as $category)
                
                <div class="product_lien mobile_lien"  onclick="renderproductview('{{ route('getproducts', ['slug' => $category->url]) }}')">

                    <img src="{{asset($category->image)}}" alt="" />

                    <p>{{ $category->name }}</p>

                </div>

            @endforeach

        </div>

    </div>

</div>