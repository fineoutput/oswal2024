<style>
/* Default hover styles */
.product_lien:hover {
    background-color: #c92323;
    color: #fff;
    cursor: pointer;
    border-radius: 10px;
}

/* Style when the element is clicked */
.product_lien.clicked {
    background-color: #c92323;
    color: #fff;
    border-radius: 10px;
}

</style>
<div class="category_product_sect">

    <div class="inner_category_product_sect">

        <div class="category mobile_category">

            {{-- @foreach ($categorys as $category)
                
                <div class="product_lien mobile_lien"  onclick="renderproductview('{{ route('getproducts', ['slug' => $category->url, 'type' => 'category']) }}')">

                    <img src="{{asset($category->image)}}" alt="" />

                    <p>{{ $category->name }}</p>

                </div>

            @endforeach --}}

            @foreach ($categorys as $category)
    <div class="product_lien mobile_lien" 
         onclick="renderproductview('{{ route('getproducts', ['slug' => $category->url, 'type' => 'category']) }}', '{{ $category->id }}')">
        <img src="{{asset($category->image)}}" alt="" />
        <p>{{ $category->name }}</p>
    </div>
@endforeach

        </div>

    </div>

</div>
<script>
   document.addEventListener('DOMContentLoaded', function() {
    // Select all elements with the class 'product_lien'
    const productLinks = document.querySelectorAll('.product_lien');
    
    if (productLinks.length > 0) {
        // Add 'clicked' class to the first element
        productLinks[0].classList.add('clicked');
        
        // Simulate a click on the first element to load the first product view
        productLinks[0].click();
    }

    // Add click event listener to each element
    productLinks.forEach(function(element) {
        element.addEventListener('click', function() {
            // Remove 'clicked' class from all elements
            productLinks.forEach(function(el) {
                el.classList.remove('clicked');
            });
            // Add 'clicked' class to the clicked element
            this.classList.add('clicked');
        });
    });
});


</script>