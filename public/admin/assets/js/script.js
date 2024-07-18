$(document).ready(function() {
    
    if ($.fn.select2) {
        
        $('.select2').select2({
            
            placeholder: 'Select an option',
            
            allowClear: true
            
        });
        
    } else {
        
        console.error('Select2 is not loaded properly.');
        
    }
    
});

function calculatePrices ($mrp, $gst_percentage, $gst_percentage_price, $selling_price) {

    let total_price = parseFloat($("#"+$mrp).val());

    let gst_percentage = parseFloat($('#'+$gst_percentage).val());

    if (isNaN(total_price) || isNaN(gst_percentage)) {
        return; 
    }

    const gst_price = (total_price * gst_percentage) / 100;

    const total_gst_price = (total_price + gst_price).toFixed(2);

    $('#'+$gst_percentage_price).val(gst_price.toFixed(2));

    $('#'+$selling_price).val(total_gst_price);
    
}

function getCity(url) {

    let selectedId = $('#state').val();

    if (selectedId !== '') {

        $.ajax({

            url: url,

            type: "get",

            data: {

                state_id: selectedId,

            },

            success: function(response){


                $('#city-container').html();

                let $html = '<option value="">----- Select City ----- </option>'; 

                $.each(response, function(key, value){
                    
                    $html += `<option value="${value.id}">${value.city_name}</option>`;

                });

                $('#city-container').html($html);

            },

            error: function(xhr){

                console.log(xhr.responseText); 

            }

        });

    } else {

        $('#city-dropdown').empty(); 

    }
}

function getProduct(url) {

    let selectedId = $('#category_id').val();

    if (selectedId !== '') {

        $.ajax({

            url: url,

            type: "get",

            data: {

                category_id: selectedId,

            },

            success: function(response){


                $('#product-container').html();

                let $html = '<option value="">----- Select Product ----- </option>'; 

                $.each(response, function(key, value){
                    
                    $html += `<option value="${value.id}">${value.name}</option>`;

                });

                $('#product-container').html($html);

            },

            error: function(xhr){

                console.log(xhr.responseText); 

            }

        });

    } else {

        $('#city-dropdown').empty(); 

    }
}