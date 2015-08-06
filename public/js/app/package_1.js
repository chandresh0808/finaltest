$(document).ready(function() {
    /* Makes the selected package as active */
    $('.acp_selected_package').on('click', function() {
        $('.pricing-panel').removeClass('active');
        $('.pricing-panel').removeClass('acp-selected-pck');
        var current_object_id = $(this).attr('id');
        $("#" + current_object_id + " .pricing-panel").addClass('active');
        $("#" + current_object_id + " .pricing-panel").addClass('acp-selected-pck');
    });

    /* Adds the package */
    $('#button_package_add_to_cart').on('click', function() {
        var selected_package_div = $('.acp-selected-pck');
        var amount = selected_package_div.attr('data-amount');
        var phc_id = selected_package_div.attr('data-phc-id');
        var credits = selected_package_div.attr('data-credits');
        var pck_id = selected_package_div.attr('data-pck-id');

        if (!amount) {
            msg = 'Please select any package';
            showAndHideDiv('.alert-error', msg);
            return false;
        }

        data = {
            'amount': amount,
            'package_id': pck_id,
            'package_has_credit_id': phc_id,
            'credits': credits
        };

        ajaxAddPackageResponse = postDataAjax(add_package_url, data);

        ajaxAddPackageResponse.done(function(response) {

            if ('success' == response.status) {
                window.top.location.href = display_cart_url;
            } else {
                showAndHideDiv('.alert-error', response.message);
            }
        });

    });


    /* Delete item from cart */
    $('#delete-card-button').on('click', function() {
        
        var cart_id = $(this).attr('data-cart-id');
        var item_id = $(this).attr('data-item-id');
        
        $("<div title='Confirmation'></div>").html("Are you sure you want to delete an Item ?").dialog({
            resizable: false,
            modal: true,
            buttons: {
                "Yes": function() {

                    data = {
                        'cart_id': cart_id,
                        'item_id': item_id
                    };

                    ajaxDeleteCartItemResponse = postDataAjax(delete_cart_item_url, data);
                    $(this).dialog("close");
                    ajaxDeleteCartItemResponse.done(function(response) {

                        if ('success' == response.status) {                               
                            window.location.href = display_cart_url;   
                        } else {
                            showAndHideDiv('.alert-error', response.message);
                        }
                        
                    });
                },
                "No": function() {
                    $(this).dialog("close");
                }
            }
        });
    });
   
    /* closing popup */
    $(".ui-dialog-titlebar-close").livequery('click', function() {           
         $(this).dialog("close");
    });

    $('#button_proceed_to_checkout').on('click', function() {            
        window.location.href = checkout_url;   
    });


    $('#submit_confirm_order').on('click', function() {   
        clearErrorMessages('error');
        isTextFieldContainValue('full_name', 'Please enter full name');
        isTextFieldContainValue('phone_number', 'Please enter phone number');
        isTextFieldContainValue('email', 'Please enter email');
        isTextFieldContainValue('address_1', 'Please enter address');
        isTextFieldContainValue('city', 'Please enter city');
        isTextFieldContainValue('zip_code', 'Please enter zip code');
        isTextFieldContainValue('card_number', 'Please enter card number');
        isTextFieldContainValue('card_holder_name', 'Please enter card holder name');
        isTextFieldContainValue('cvv_number', 'Please enter cvv number');
        isZipCodeValid('zip_code','Please enter valid zipcode');
        
        
        if (getErrorMessageArray().length) {
            displayErrorMessages();
        } else {         
            $("#submit_confirm_order").submit();
            return true;
        }
        return false;
        
    });


     //if the letter is not digit then don't type anything
    $("#phone_number, #card_number, #cvv_number").keypress(function(e) {       
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });
  

});