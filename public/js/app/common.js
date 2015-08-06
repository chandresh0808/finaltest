function postDataAjax(url, data, beforeSendFunc, completeFunc) {
    
    emptyFunc = function() { };
    beforeSendFunc = beforeSendFunc || emptyFunc;
    completeFunc = completeFunc || emptyFunc;
    
    var ajaxPost = $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: 'json',
        async: false,
        beforeSend: beforeSendFunc,
        complete:completeFunc
    });
    return ajaxPost;
}


function showAndHideDiv(element, message) {
    $(element).html(message);
    $(element).fadeIn(2000);
    setTimeout(function() {
        $(element).fadeOut(2000);
    }, 5000);
}
 

function wordwrap( str, width, brk, cut ) {
	 
    brk = brk || '\n';
    width = width || 75;
    cut = cut || false;
 
    if (!str) { return str; }
 
    var regex = '.{1,' +width+ '}(\s|$)' + (cut ? '|.{' +width+ '}|.+$' : '|\S+?(\s|$)');
 
    return str.match( RegExp(regex, 'g') ).join( brk );
 
}


/*
//Sticky Placeorder Block JS
var stickerCheckout = $("#sidebar");
var positionCheckout = stickerCheckout.position();	
var stickermax = $(document).outerHeight() - $("footer").outerHeight() - stickerCheckout.outerHeight() - 200; 

$(window).scroll(function() {
    var windowpos = $(window).scrollTop();		
    if (windowpos >= positionCheckout.top && windowpos < stickermax) {
        stickerCheckout.attr("style", "");  
        stickerCheckout.addClass("stick");  
    } else if (windowpos >= stickermax) {
        stickerCheckout.removeClass();  
        stickerCheckout.css({position: "absolute", top: stickermax + "px"});  
    } 
    else {
        stickerCheckout.removeClass(); 
    }
});
*/
 