<div class="details-product-imgs">
   
    <div class="details-img-display">
        
        <div class="details-img-showcase" style=" height: 500px; ">
            
            @foreach ($images as $image)
                
               <img src="{{ asset($image['img']) }}" alt="shoe image" />

            @endforeach
               
        </div>
        
    </div>
    
    <div class="details-img-select" style="height: 200px;" >

        @foreach ($images as $key => $image)

            <div class="details-img-item">
                
                <a href="#" data-id="{{ 1+$key }}">
                    
                    <img src="{{ asset($image['img']) }}" alt="shoe image" />
                    
                </a>
                
            </div>

        @endforeach
        
    </div>
    
</div>