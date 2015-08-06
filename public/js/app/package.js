$(document).ready(function() {
    
    stickyPlaceholder();
    
    /* Adds the package */
    $('.acp_selected_package').on('click', function() {
        //var selected_package_div = $('.acp-selected-pck');
        var amount = $(this).attr('data-amount');
        var phc_id = $(this).attr('data-phc-id');
        var credits = $(this).attr('data-credits');
        var pck_id = $(this).attr('data-pck-id');

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
        
        $("<div id='custom_dialog' title='Confirmation'></div>").html("Are you sure you want to delete an Item ?").dialog({
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
   

    $('#button_proceed_to_checkout').on('click', function() {            
        window.location.href = checkout_url;   
    });


    $('#submit_confirm_order').on('click', function() {
        if ($('#phone_number').length > 0) {
            if (!$('#phone_number').parent('span').hasClass('form-control-selected')) {
                $('#phone_number').attr('placeholder', '');
            } else {
                $('#phone_number').attr('placeholder', 'xxx-xxx-xxxx');
            }
        }
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
        isValidEmail('email', 'Please enter valid email');
        
        if (getErrorMessageArray().length) {
            displayErrorMessages();
        } else {         
            $("#submit_confirm_order").submit();
            return true;
        }
        return false;
        
    });

});


//Sticky Placeorder Block JS
function stickyPlaceholder() {         
    var stickerCheckout = $("#sidebar");        
    var positionCheckout = stickerCheckout.position();	    
    if (typeof positionCheckout != 'undefined') {
        var stickermax = $(document).outerHeight() - $("footer").outerHeight() - stickerCheckout.outerHeight() - 200; 

        var scrollEndPos = $(window).scrollTop();        
        if (scrollEndPos >= positionCheckout.top && scrollEndPos < stickermax) {
            stickerCheckout.attr("style", "");  
            stickerCheckout.addClass("stick");  
        } else if (scrollEndPos >= stickermax) {
            stickerCheckout.removeClass();  
            stickerCheckout.css({position: "absolute", top: stickermax + "px"});  
        }     

        $(window).scroll(function() {                         
            var windowpos = $(window).scrollTop();		
            if (windowpos >= positionCheckout.top && windowpos < stickermax) {                
                stickerCheckout.attr("style", "");  
                stickerCheckout.addClass("stick");  
            } else if (windowpos >= stickermax) {
                stickerCheckout.removeClass();  
                stickerCheckout.css({position: "absolute", top: stickermax + "px"});  
            }             
        });
    }
}