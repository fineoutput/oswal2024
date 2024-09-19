$(document).ready(function() {
    
    if ($.fn.select2) {
        
        $('.select2').select2({
            
            placeholder: 'Select an option',
            
            allowClear: true
            
        });
        
    } else {
        
        console.error('Select2 is not loaded properly.');
        
    }

    $('#walletModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); 
        var userId = button.data('id');
        var userName = button.data('name');
        var walletAmount = button.data('wallet');
        
        var modal = $(this);
        modal.find('.modal-title').text('Edit Wallet Amount for ' + userName);
        modal.find('#wallet_amount').val(walletAmount);
        modal.find('#user_id').val(userId);
        // modal.find('#walletForm').attr('action', '/user/' + userId + '/update-wallet');
    });

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

        $('#product-container').empty(); 

    }
}

function getType(url , pid , cid) {

    let selectedId = $(`#${pid}`).val();

    if (selectedId !== '') {

        $.ajax({

            url: url,

            type: "get",

            data: {

                product_id: selectedId,

            },

            success: function(response){


                $(`#${cid}`).html();

                let $html = '<option value="">----- Select Type ----- </option>'; 

                $.each(response, function(key, value){
                    
                    $html += `<option value="${value.id}">${value.type_name}</option>`;

                });

                $(`#${cid}`).html($html);

            },

            error: function(xhr){

                console.log(xhr.responseText); 

            }

        });

    } else {

        $(`#${cid}`).empty(); 

    }
}

function calculateTotalWeight() {
    
    const quantity = $('#quantity').val();
    const weight = $('#type').val();

    const totalWeight = (quantity && weight) ? (quantity * weight) : 0;

    $('#totalweight').val(totalWeight);

}