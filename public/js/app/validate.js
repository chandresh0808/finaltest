var errorMessageArray = new Array();
var counter = 0;

function clearErrorMessages(className) {
    counter = 0;
    $("div").removeClass(className);
    errorMessageArray = new Array();
}

function unSetErrorMessageArray() {
    errorMessageArray = new Array();
    counter = 0;
}

function clearErrorMessage(id) {
    $("#" + id + "ErrorMsg").html("");
    for (var i = 0; i < errorMessageArray.length; i++) {
        var errorArray = errorMessageArray[i].split("||");
        if (errorArray[0] === id) {
            errorMessageArray.splice(i, 1);
            counter = counter - 1;
        }
    }
}

function displayErrorMessages() {


    for (var i = 0; i < errorMessageArray.length; i++) {
        var errorArray = errorMessageArray[i].split("||");

        /*TODO: Its temp hack, need to remove */
        if ('recaptcha_response_field' == errorArray[0]) {
            $("#recaptcha_response_field").val(errorArray[1]);
            $("#recaptcha_response_field").addClass('error');
        } else {
            $("#" + errorArray[0] + "ErrorMsg").html(errorArray[1]);
            $("#" + errorArray[0] + "ErrorClass").addClass('error');
        }



    }
    return errorMessageArray;
}

/*function used to tell user about error occured and move where error found */
function goToFirstErrorFound() {
    if (errorMessageArray.length) {
        var errorArray = errorMessageArray[0].split("||");
        destination = $("#" + errorArray[0]).offset().top - 25;
        $("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 1100);
        $("#" + errorArray[0]).focus();
        return false;
    }
}
/* Function to trim value*/
function trimValue(id) {
    $("#" + id).val($.trim($("#" + id).val()));
    return $("#" + id).val();
}
/* Function get all error messages*/
function getErrorMessageArray() {
    return errorMessageArray;
}
/* check is text field empty */
function isTextFieldContainValue(id, errorText) {
    var givenValue = trimValue(id);

    if (undefined == givenValue) {
        return false;
    }

    if ('' === givenValue) {
        if (typeof(errorText) !== 'undefined' && errorText !== null) {
            errorMessageArray[counter++] = id + "||" + errorText;
        } else {
            errorMessageArray[counter++] = id + "||" + "Please enter " + id + ".";
        }

        return false;
    }

    return true;
}

/* check if Date is valid */
function isValidDate(id) {
    errorMessageArray[counter++] = id + "||" + "Please select reviewer due date.";
    return true;

}
/* check is dropdown empty */
function isDropdownContainValue(id, errorText) {
    if ('' === $("#" + id).val() || null === $("#" + id).val()) {
        if (typeof(errorText) !== 'undefined' && errorText !== null) {
            errorMessageArray[counter++] = id + "||" + errorText;
        } else {
            errorMessageArray[counter++] = id + "||" + "Please enter " + id + ".";
        }
        return false;
    }
    return true;
}

function isValidUserName(id, errorText) {
    var val = trimValue(id);
    var valWithoutSpaces = val.replace(/\s+/g, '');

    var val_length = val.length;

    if (val_length < 6 || val_length > 30) {
        errorText = 'Username must be between 6-30 characters';
        errorMessageArray[counter++] = id + "||" + errorText;
        return false;
    }

    if (valWithoutSpaces == val) {
        return true;
    } else {
        errorText = 'Username must not contain spaces';
        errorMessageArray[counter++] = id + "||" + errorText;
        return false;
    }



    if ('' === val) {
        if (typeof(errorText) !== 'undefined' && errorText !== null) {
            errorMessageArray[counter++] = id + "||" + errorText;
        } else {
            errorMessageArray[counter++] = id + "||" + "Please enter " + id + ".";
        }

        return false;
    }

    return true;
}

/* check  is text field matches given text field */
function isTextFieldMatchGivenValue(id, givenValue, errorText) {
    var newValue = trimValue(id);
    if (givenValue != newValue) {
        errorMessageArray[counter++] = id + "||" + errorText;
        return false;
    }
    return true;
}

function isValidPassword(id, errorText) {
    if (typeof(errorText) !== 'undefined' && errorText !== null) {
        errText = errorText;
    } else {
        errText = "Invalid password or empty.";
    }
    var passwordRegex = /^[\#\s]*$/;
    if (passwordRegex.test(($("#" + id).val()))) {
        errorMessageArray[counter++] = id + "||" + errText;
        return false;
    }
    if ($("#" + id).val().length < 6) {
        errorMessageArray[counter++] = id + "||" + "Password should contain at least 6 characters.";
        return false;
    }
    if ($("#" + id).val().length > 20) {
        errorMessageArray[counter++] = id + "||" + "Password should not exceed 20 characters.";
        return false;
    }
    return true;
}

/* check is email valid */
function isValidEmail(id, errorText) {
    var value = trimValue(id);
    var regExp = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!regExp.test(value)) {
        if (typeof(errorText) !== 'undefined' && errorText !== null) {
            errorMessageArray[counter++] = id + "||" + errorText;
        } else {
            errorMessageArray[counter++] = id + "||" + "Please enter valid email.";
        }
        return false;
    }
    return true;
}
/* check text field is valid title */
function isValidTitle(id, errorText) {
    var value = trimValue(id);
    var regExp = /^[0-9a-zA-Z\_\ \r\n\.\-\'\!\/\&\*\#\@\=\(\)\%\^\,\+\-\:\;\>\<\"\~\`\[\]\{\}\$\\\?"]+$/;
    if (!regExp.test(value)) {
        if (typeof(errorText) !== 'undefined' && errorText !== null) {
            errorMessageArray[counter++] = id + "||" + errorText;
        } else {
            errorMessageArray[counter++] = id + "||" + "Please enter valid title.";
        }
        return false;
    }
    return true;
}
/* check text field is valid title */
function isValidDescription(id, errorText) {
    var value = trimValue(id);
    var regExp = /^[0-9a-zA-Z\_\ \r\n\.\-\'\!\/\&\*\#\@\=\(\)\%\^\,\+\-\:\;\>\<\"\~\`\[\]\{\}\$\\\?"]+$/;
    if (!regExp.test(value)) {
        if (typeof(errorText) !== 'undefined' && errorText !== null) {
            errorMessageArray[counter++] = id + "||" + errorText;
        } else {
            errorMessageArray[counter++] = id + "||" + "Please enter valid description.";
        }
        return false;
    }
    return true;
}

function isValidDate(id) {
    errorMessageArray[counter++] = id + "||" + "Please select reviewer due date.";
    return true;

}

function isValidUrl(id, errorText) {
    var value = trimValue(id);
    value = checkAndPreAppendHttpForUrl(value);
    var regExp = /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
    if (!regExp.test(value)) {
        if (typeof(errorText) !== 'undefined' && errorText !== null) {
            errorMessageArray[counter++] = id + "||" + errorText;
        } else {
            errorMessageArray[counter++] = id + "||" + "Please enter valid url.";
        }
        return false;
    }
    assignValue(id, value);
    return true;
}

function isValidLength(id, charLength, errorText) {
    var value = trimValue(id);

    if (value.length > charLength) {
        if (typeof(errorText) !== 'undefined' && errorText !== null) {
            errorMessageArray[counter++] = id + "||" + errorText;
        } else {
            errorMessageArray[counter++] = id + "||" + "It should not exceed " + charLength + " characters.";
        }
        return false;
    }
    return true;
}

function isValidAssignee(id, errorText) {
    var value = trimValue(id);
    var regExp = /^[0-9\,]{1,}$/;

    if (!regExp.test(value)) {
        if (typeof(errorText) !== 'undefined' && errorText !== null) {
            errorMessageArray[counter++] = id + "||" + errorText;
        } else {
            errorMessageArray[counter++] = id + "||" + "Assignee error.";
        }
        return false;
    }
    return true;
}

/* check is text field empty */
function isAssigneeContainValue(id, errorText) {
    var givenValue = trimValue(id);
    if ('' === givenValue) {
        if (typeof(errorText) !== 'undefined' && errorText !== null) {
            errorMessageArray[counter++] = id + "||" + errorText;
        } else {
            errorMessageArray[counter++] = id + "||" + "Please add " + id + ".";
        }
        return false;
    }
    return true;
}

/* check is text field empty */
function isCheckboxContainValue(id, errorText) {
    if (document.getElementById(id).checked == false) {
        if (typeof(errorText) !== 'undefined' && errorText !== null) {
            errorMessageArray[counter++] = id + "||" + errorText;
        } else {
            errorMessageArray[counter++] = id + "||" + "Please enter " + id + ".";
        }
        return false;
    }
    return true;

}


/* assign value to given field*/
function assignValue(id, value) {
    $("#" + id).val(value);
}

/* check is text field empty */
function isZipCodeValid(id, errorText) {
    var givenValue = trimValue(id);

    if (givenValue) {
        errorFlag = 1;
        if (undefined == givenValue) {
            return false;
        }
        if ((5 == givenValue.length) || (9 == givenValue.length)) {
            errorFlag = 0;
        }

        if (1 == errorFlag) {
            if (typeof(errorText) !== 'undefined' && errorText !== null) {
                errorMessageArray[counter++] = id + "||" + errorText;
            } else {
                errorMessageArray[counter++] = id + "||" + "Please enter " + id + ".";
            }
            return false;
        }
    }



    return true;
}

function isSelectBoxHasZero(id, errorText) {
    var givenValue = trimValue(id);
    if (0 == givenValue || '' === givenValue) {
        if (typeof(errorText) !== 'undefined' && errorText !== null) {
            errorMessageArray[counter++] = id + "||" + errorText;
        }

        return false;
    }

    return true;

}

//if the letter is not digit then don't type anything
$("#phone_number, #card_number, #cvv_number, #zip_code, #text-add-custom-credits, #text-expire-custom-credits").keypress(function(e) {       
   if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
       return false;
   }
});