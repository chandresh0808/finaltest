var dataArray = [], sortColumnArray = [], sortColumnObject = {},
        sortColumnOrder = 2, // Default Sort Column
        sortOrder = 'desc',
        table_id = "#manage-user", // Table id used for mapping data table  

//search input box option
        display_search_input_box = true;

no_record_display_message = 'No records available';

//Disable sorting for given column
disableSortColumnIndex = [3];

//columns for data mapping from db to front end
column_array = [
    [null, false],
    [null, false],
    [null, false],
    [null, false],
    [null, false],
    [null, false]
];


function createCustomRowFunction(aData, nRow) {    
    nRow.setAttribute('id', aData.id);
    aData.name = wordwrap(aData.name, 20, '<br/>', -1);
    aData.email = wordwrap(aData.email, 30, '<br/>', -1);
    var hiddenText = '<input type="hidden" id="namehidden_' + aData.id + '" value="' + aData.name + '">';

    column0 = aData.name + hiddenText;
    column1 = aData.email;
    column2 = aData.available_credit;
    column3 = aData.last_login;
    column4 = '<span id="status_' + aData.id + '">' + aData.status + '</span>';
    column5 = get_action_row_for_user(aData);
    
    $('td:eq(0)', nRow).html(column0);
    $('td:eq(1)', nRow).html(column1);    
    $('td:eq(2)', nRow).html(column2);
    $('td:eq(2)', nRow).addClass('txtr');
    $('td:eq(3)', nRow).html(column3);    
    $('td:eq(4)', nRow).html(column4);    
    $('td:eq(5)', nRow).html(column5);
}

function wordwrap( str, width, brk, cut ) {
	 
    brk = brk || 'n';
    width = width || 75;
    cut = cut || false;
 
    if (!str) { return str; }
 
    var regex = '.{1,' +width+ '}(\s|$)' + (cut ? '|.{' +width+ '}|.+$' : '|\S+?(\s|$)');
 
    return str.match( RegExp(regex, 'g') ).join( brk );
 
}
function get_action_row_for_user(aData) {    
    if (aData.status == 'Blocked') {
        actions = '<a href="/admin/view-user/'+aData.id+'" class="action-icon text-replace sprite-view" data-toggle="tooltip" data-placement="top" title="View user">View</a>\n\
                   <a href="/admin/edit-user/'+aData.id+'" class="action-icon text-replace sprite-edit-m mar-l20" data-toggle="tooltip" data-placement="top" title="Edit user" id="edit_'+aData.id+'">Edit</a>\n\
                   <a state=' + aData.state + ' admin-block-user-id=' + aData.id + ' class="action-icon mar-l20 text-replace sprite-unblock unblock" data-toggle="tooltip" data-placement="top" title="Unblock user" id="block_'+aData.id+'">Unblock</a>\n\
                   <a admin-delete-user-id=' + aData.id + ' class="action-icon text-replace sprite-delete mar-l20" title="Delete" data-placement="top" data-toggle="tooltip" data-original-title="Delete user" id="delete_'+aData.id+'">Delete</a>';    
    } else {
        actions = '<a href="/admin/view-user/'+aData.id+'" class="action-icon text-replace sprite-view" data-toggle="tooltip" data-placement="top" title="View user">View</a>\n\
                   <a href="/admin/edit-user/'+aData.id+'" class="action-icon text-replace sprite-edit-m mar-l20" data-toggle="tooltip" data-placement="top" title="Edit user" id="edit_'+aData.id+'">Edit</a>\n\
                   <a state=' + aData.state + ' admin-block-user-id=' + aData.id + ' class="action-icon mar-l20 text-replace sprite-block block" data-toggle="tooltip" data-placement="top" title="Block user" id="block_'+aData.id+'">Block</a>\n\
                   <a admin-delete-user-id=' + aData.id + ' class="action-icon text-replace sprite-delete mar-l20" title="Delete" data-placement="top" data-toggle="tooltip" data-original-title="Delete user" id="delete_'+aData.id+'">Delete</a>';
    }
    return actions;
}

$(document).ready(function() {
    $('.sprite-delete').livequery('click', function() {
        deleteAdminUserId = $(this).attr('admin-delete-user-id');
        var username = $('#namehidden_'+deleteAdminUserId).val();                
        $("<div id='custom_dialog' title='Confirmation'></div>").html("Do you want to delete this user?").dialog({
            resizable: false,
            modal: true,
            buttons: {
                "Yes": function() {
                    data = {'admin-delete-user-id': deleteAdminUserId};
                    ajaxDeleteUserResponse = postDataAjax(admin_user_delete_url, data);
                    $(this).dialog("close");
                    ajaxDeleteUserResponse.done(function(response) {
                        if (response.redirect){
                            location.href = response.redirect;
                        }
                        if ('success' == response.status) {
                            $('#'+deleteAdminUserId).remove();                            
                            showAndHideDiv('.alert-success', username+' deleted successfully');                            
                             setTimeout(function() {
                                  location.reload();
                             }, 5000);
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


    
    $('.block').livequery('click', function() {        
        blockAdminUserId = $(this).attr('admin-block-user-id');        
        var username = $('#namehidden_'+blockAdminUserId).val();
        $("<div id='custom_dialog' title='Confirmation'></div>").html("Do you want to block this user?").dialog({

            resizable: false,
            modal: true,
            buttons: {
                "Yes": function() {
                    data = {'admin-block-user-id': blockAdminUserId};
                    ajaxBlockUserResponse = postDataAjax(admin_user_block_url, data);
                    $(this).dialog("close");
                    ajaxBlockUserResponse.done(function(response) {                        
                        if (response.redirect){
                            location.href = response.redirect;
                        }
                        if ('success' == response.status) {

                            $('#block_'+blockAdminUserId).removeClass('sprite-block');
                            $('#block_'+blockAdminUserId).removeClass('block');
                            $('#block_'+blockAdminUserId).addClass('sprite-unblock');
                            $('#block_'+blockAdminUserId).addClass('unblock');                            
                            $('#block_'+blockAdminUserId).html('Unblock');
                            $('#block_'+blockAdminUserId).attr('title', 'Unblock user');
                            $('#status_'+blockAdminUserId).html('Blocked');
                            
                            if (response.associateUserIdArray != null) {
                                var i;
                                for(i = 0; i < response.associateUserIdArray.length; i++){
                                    $('#block_'+response.associateUserIdArray[i]).removeClass('sprite-block');
                                    $('#block_'+response.associateUserIdArray[i]).removeClass('block');
                                    $('#block_'+response.associateUserIdArray[i]).addClass('sprite-unblock');
                                    $('#block_'+response.associateUserIdArray[i]).addClass('unblock');                            
                                    $('#block_'+response.associateUserIdArray[i]).html('Unblock');
                                    $('#block_'+response.associateUserIdArray[i]).attr('title', 'Unblock user');
                                    $('#status_'+response.associateUserIdArray[i]).html('Blocked');
                                }
                            }
                            showAndHideDiv('.alert-success', username+' blocked successfully');
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

    $('.unblock').livequery('click', function() {
        unblockAdminUserId = $(this).attr('admin-block-user-id');        
        var username = $('#namehidden_'+unblockAdminUserId).val();
        var state = $(this).attr('state');
        $("<div id='custom_dialog' title='Confirmation'></div>").html("Do you want to unblock this user?").dialog({
            resizable: false,
            modal: true,
            buttons: {
                "Yes": function() {
                    data = {'admin-unblock-user-id': unblockAdminUserId};
                    ajaxUnBlockUserResponse = postDataAjax(admin_user_unblock_url, data);
                    $(this).dialog("close");
                    ajaxUnBlockUserResponse.done(function(response) {
                        if (response.redirect){
                            location.href = response.redirect;
                        }
                        if ('success' == response.status) {

                            $('#block_'+unblockAdminUserId).removeClass('sprite-unblock');
                            $('#block_'+unblockAdminUserId).removeClass('unblock');                            
                            $('#block_'+unblockAdminUserId).addClass('sprite-block');
                            $('#block_'+unblockAdminUserId).addClass('block');                            
                            $('#block_'+unblockAdminUserId).attr('title', 'Block user');                            
                            $('#block_'+unblockAdminUserId).html('Block');                            
                            
                            if (state == 1) {
                                $('#status_' + unblockAdminUserId).html('Active');
                            } else {
                                $('#status_' + unblockAdminUserId).html('Inactive');
                            }
                            
                            if (response.associateUserIdArray != null) {
                                var i;
                                for(i = 0; i < response.associateUserIdArray.length; i++){
                                    $('#block_'+response.associateUserIdArray[i]).removeClass('sprite-unblock');
                                    $('#block_'+response.associateUserIdArray[i]).removeClass('unblock');                            
                                    $('#block_'+response.associateUserIdArray[i]).addClass('sprite-block');
                                    $('#block_'+response.associateUserIdArray[i]).addClass('block');                            
                                    $('#block_'+response.associateUserIdArray[i]).attr('title', 'Block user');                            
                                    $('#block_'+response.associateUserIdArray[i]).html('Block');
                                    $('#status_' + response.associateUserIdArray[i]).html('Active');
                                }
                            }
                            showAndHideDiv('.alert-success', username+' unblocked successfully');
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


    $('.admin-reset-password').livequery('click', function() {
        userId = $(this).attr('attr-user-id');
        data = {'user_id': userId};
        ajaxResponse = postDataAjax(admin_reset_password, data);
        ajaxResponse.done(function(response) {
            if ('success' == response.status) {
                showAndHideDiv('.alert-success', response.message);
            } else {
                showAndHideDiv('.alert-error', response.message);
            }
        });
    });


    $('#button-add-custom-credits').livequery('click', function() {        
        var cc = $("#text-add-custom-credits").val();
         var expireDate = $("#cal_credits_expire_date").val();

        if (cc == 0 || cc == '' || $("#text-add-custom-credits").hasClass('error')) {  
            $("#text-add-custom-credits").val('Please enter valid number between 1 to 999');
            $("#text-add-custom-credits").addClass('error');        
           //showAndHideDiv('.alert-error', 'Please enter valid number between 1 to 999');
           //return false;
        }

        if (expireDate == '' || $("#cal_credits_expire_date").hasClass('error')) {           
            $("#cal_credits_expire_date").val('Please select expire date');
            $("#cal_credits_expire_date").addClass('error');                    
           //showAndHideDiv('.alert-error', 'Please select expire date');
           //return false;
        }        
        
        if (cc == 0 || cc == '' || expireDate == '' || $("#text-add-custom-credits").hasClass('error') || $("#cal_credits_expire_date").hasClass('error')) {
            return false;
        }

        var user_id = $("#hidden-user-id").val();
        var rp = $("#p-remaining-points").html();
        var tp = +cc + +rp;
        
        if (+tp>999) {
           showAndHideDiv('#div_add_credit_error', 'Your total credit points are exceeding 999');
           return false;
        }
       
        
        data = {'user_id': user_id, 'custom_credit': cc, 'expire_date': expireDate};
        ajaxResponse = postDataAjax(admin_add_custom_package, data);
        ajaxResponse.done(function(response) {
            $("#text-add-custom-credits").val('');
            $("#cal_credits_expire_date").val('');
            $("#detailsmodal").modal('hide');
            if ('success' == response.status) {
                $("#p-remaining-points").html(tp);
                $("#span-add-remain-points").html(tp);
                $("#span-edit-remain-points").html(tp);
                showAndHideDiv('#span_alert_success', response.message);
            } else {
                showAndHideDiv('#span_alert_error', response.message);
            }
        });
    });

    $('.btn-link, .close').livequery('click', function() {
        $("#text-add-custom-credits").val('');
        $("#text-expire-custom-credits").val('');
        $("#cal_credits_expire_date").val('');
        $("#text-add-custom-credits").removeClass('error');
        $("#text-expire-custom-credits").removeClass('error');
        $("#cal_credits_expire_date").removeClass('error');
    });
    
    $('#button-expire-custom-credits').livequery('click', function() {
        
        var cc = $("#text-expire-custom-credits").val();
        var rp = $("#p-remaining-points").html();    
        var user_id = $("#hidden-user-id").val();    
        
        
        if (cc == 0 || $("#text-expire-custom-credits").hasClass('error')) {            
            $("#text-expire-custom-credits").val('Please enter valid number');
            $("#text-expire-custom-credits").addClass('error');            
           //showAndHideDiv('.alert-error', 'Please enter valid number');
           //return false;
        }

        if (rp == 0) {            
            //$("#p-remaining-points").val('Sorry you dont have credit points');
            //$("#p-remaining-points").addClass('error');            
           showAndHideDiv('#div_ajust_credit_error', 'Sorry you dont have credit points');         
        }
        
        if (cc == 0 || rp == 0 || $("#text-expire-custom-credits").hasClass('error')) {
            return false;
        }

        if (cc == 0 || cc == '' || +cc > +rp) {  
           showAndHideDiv('#div_ajust_credit_error', 'Please enter valid number between 1 to ' + rp);
           return false;
        }
       
        var tp = +rp - +cc;
      
        data = {'user_id': user_id, 'custom_credit': cc, 'remaining_points': rp};
        
        ajaxResponse = postDataAjax(admin_expire_custom_package, data);
        ajaxResponse.done(function(response) {
            $("#text-expire-custom-credits").val('');
            $("#detailsmodal").modal('hide');
            if ('success' == response.status) {
                $("#span-add-remain-points").html(tp);
                $("#span-edit-remain-points").html(tp); 
                 $("#p-remaining-points").html(tp);
                showAndHideDiv('#span_alert_success', response.message);
            } else {
                showAndHideDiv('#span_alert_error', response.message);
            }
        });
    });

        
    $("#cal_credits_expire_date").datepicker({
        dateFormat: "mm/dd/yy",
        showOn: "button",
        buttonImage: "/img/calendar.png",
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        minDate: 0 

    }).keyup(function (e) {
        if (e.keyCode == 8 || e.keyCode == 46) {
            $.datepicker._clearDate(this);
        }
    });
    
     $('#text-add-custom-credits').livequery('click', function() {
         $(this).removeClass('error');
         var val = $(this).val();
          if(val == 'Please enter valid number between 1 to 999'){
              $(this).val('');
          }
         
     });
     
    $('#cal_credits_expire_date').on('click change', function() {
         $(this).removeClass('error');
         var val = $(this).val();
          if(val == 'Please select expire date'){
              $(this).val('');
          }
         
     });
     
    $('#text-expire-custom-credits').livequery('click', function() {
         $(this).removeClass('error');
         var val = $(this).val();
          if(val == 'Please enter valid number'){
              $(this).val('');
          }
         
     });
     
     
    $('#detailsModalHided').livequery('click', function() {
         showAndHideDiv('#span_alert_error', 'User is blocked/Inactive');         
     });
     

     $('#detailsModalPopup').livequery('click', function() {
         clearErrorMessages('error');
         $("#text-add-custom-credits").removeClass('error');
         $("#cal_credits_expire_date").removeClass('error');
         $("#text-expire-custom-credits").removeClass('error');
         $("#text-add-custom-credits").val('');
         $("#cal_credits_expire_date").val('');
         $("#text-expire-custom-credits").val('');
     });
    
});