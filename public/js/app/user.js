$(document).ready(function() {
    if ($('#phone_number').length > 0) {
        $('#phone_number').mask('000-000-0000');
        if (!$('#phone_number').parent('span').hasClass('form-control-selected')) {
            $('#phone_number').attr('placeholder', '');
        } else {
            $('#phone_number').attr('placeholder', 'xxx-xxx-xxxx');
        }
    }    
    
    /* sign up form submit action */
    $('#submit_sign_up').click(function() {

        ///  $("#sign_up").submit(); return true;

        clearErrorMessages('error');
        isTextFieldContainValue('first_name', 'Please enter first name');
        isTextFieldContainValue('last_name', 'Please enter last name');
        isTextFieldContainValue('email', 'Please enter email');
        isTextFieldContainValue('password', 'Please enter password');
        isTextFieldContainValue('confirm_password', 'Please enter confirm password');
        isTextFieldContainValue('recaptcha_response_field', 'Please enter captcha');
        isCheckboxContainValue('terms_condition', 'Please select terms & conditions');

        if (getErrorMessageArray().length) {
            displayErrorMessages();
        } else {
            $("#sign_up").submit();
            return true;
        }
        return false;
    });

    /* sign in form submit action */
    $("#submit_login").click(function() {
        clearErrorMessages('error');

        isTextFieldContainValue('email', 'Please enter email address');
        isTextFieldContainValue('password', 'Please enter password');

        if (getErrorMessageArray().length) {
            displayErrorMessages();
            return false;
        } else {
            document.getElementById("sign_in").submit();
             return true;
        }
    });

    /* sign in and sign up form: Remove error message and class */
    $(".form-group, .col-sm-6").on('click', function() {   
        var current_object = $(this);
        var current_object_id = current_object.attr('id');       
        var text = $('#' + current_object_id + ' .form-control-label-content').attr('data-text');
        var text_span = $('#' + current_object_id + ' .form-control-label-content');
        current_object.removeClass('error');
        text_span.html(text);
    });

    /* term and conditions error message hide */
    $("#terms_condition").on('click', function() {
        if ($("#terms_condition").is(":checked") == true) {
            $("#terms_conditionErrorMsg").html('');
        }
    });

    /* Captcha message hide */
    $("#recaptcha_response_field").livequery('click', function() {
        if ($(this).hasClass('error')) {
            $(this).removeClass('error');
            $("#recaptcha_response_field").val('');
            $('#recaptcha_response_fieldErrorMsg').html(''); 
        }               
        var captcha_text = $(this).val();

        if ("Invalid captcha" == captcha_text || "Please enter captcha" == captcha_text) {
            $(this).val('');
        }

    });

    /* Fading in and out password criteria in user sign-up form */
    $("#passwordErrorClass, #new_passwordErrorClass").focusin(function() {
        $('#passwordCriteria').fadeIn(2000);
    });

    $("#confirm_passwordErrorClass, #confirm_new_passwordErrorClass").focusout(function() {
        $('#passwordCriteria').fadeOut(2000);
    });

    /* user forget password */
    $("#button_forgot_password").click(function() {
        clearErrorMessages('error');

        isTextFieldContainValue('email', 'Please enter email address');

        if (getErrorMessageArray().length) {
            displayErrorMessages();
            return false;
        } else {
            $("#button_forgot_password").submit();
        }
    });


    /* reset password */
    $("#reset_password_button").click(function() {
        clearErrorMessages('error');

        isTextFieldContainValue('new_password', 'Please enter password');
        isTextFieldContainValue('confirm_new_password', 'Please enter confirm password');

        if (getErrorMessageArray().length) {
            displayErrorMessages();
            return false;
        } else {
            $("#reset_password_button").submit();
             return true;
        }
    });


        
    $('#submit_user_account').click(function() {
     
        clearErrorMessages('error');
        isTextFieldContainValue('first_name', 'Please enter first name');
        isTextFieldContainValue('last_name', 'Please enter last name');
        isTextFieldContainValue('email', 'Please enter email');
        isZipCodeValid('zip_code','Please enter valid zipcode');

        if (getErrorMessageArray().length) {
            displayErrorMessages();
        } else {
            $("#submit_user_account").submit();
            return true;
        }
        return false;
    });


});

$(document).click(function() {
    if ($('#phone_number').length > 0) {
        if (!$('#phone_number').parent('span').hasClass('form-control-selected')) {
            $('#phone_number').attr('placeholder', '');
        } else {
            $('#phone_number').attr('placeholder', 'xxx-xxx-xxxx');
        }
    }
});