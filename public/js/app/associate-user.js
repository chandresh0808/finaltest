var dataArray = [], sortColumnArray = [], sortColumnObject = {},
sortColumnOrder = 1, // Default Sort Column
sortOrder = 'desc',
table_id = "#associate-user", // Table id used for mapping data table  

//search input box option
display_search_input_box = false;

no_record_display_message = 'No records available';

//Disable sorting for given column
disableSortColumnIndex = [3];

//columns for data mapping from db to front end
column_array = [
    [null, false],
    [null, false],
    [null, false],
    [null, false]
];

function createCustomRowFunction(aData, nRow) {
    nRow.setAttribute('id', aData.id);
    column0 = aData.first_name;
    column1 = aData.last_name;
    column2 = aData.username;
    column3 = get_action_row_for_test_run(aData);
    
    $('td:eq(0)', nRow).html(column0);
    $('td:eq(1)', nRow).html(column1);
    $('td:eq(2)', nRow).html(column2);
    $('td:eq(3)', nRow).html(column3);
}

function get_action_row_for_test_run(aData) {
    actions = '<a delete-associate-user-name=' + aData.first_name + ' delete-associate-user-id=' + aData.id + ' class="action-icon text-replace sprite-delete mar-l20" title="Delete" data-placement="top" data-toggle="tooltip" data-original-title="Delete">Delete</a>';
    return actions;
}



$(document).ready(function () {
    $('#add-associate-user-submit').livequery('click', function() {        
        
        clearErrorMessages('error');
        isTextFieldContainValue('associate_first_name', 'Please enter first name');
        isTextFieldContainValue('associate_last_name', 'Please enter last name');
        isTextFieldContainValue('associate_email', 'Please enter email');        

        if (getErrorMessageArray().length) {
            displayErrorMessages();
        } else {
            var associateFirstName = $("#associate_first_name").val();
            var associateLastName = $("#associate_last_name").val();
            var associateEmail = $("#associate_email").val();
            data = {'associate_first_Name': associateFirstName, 'associate_last_name': associateLastName, 'associate_email': associateEmail};
            ajaxAddAssociateResponse = postDataAjax(add_associate_url, data);
            ajaxAddAssociateResponse.done(function(response) {
                if (response.redirect){
                    location.href = response.redirect;
                }
                if ('success' == response.status) {
                    $("#add-associate-user").modal('hide');
                    showAndHideDiv('.alert-success', response.message);
                    $('#associate_first_name').val('');
                    $('#associate_last_name').val('');
                    $('#associate_email').val('');
                } else {
                    $('#associate_emailErrorClass').addClass('error');                    
                    $('#associate_emailErrorMsg').html(response.error_message['email']);                    
                }
            });            
        }                
    });
    
    $('.sprite-delete').livequery('click', function() {        
        var associateUserId = $(this).attr('delete-associate-user-id');
        var firstName = $(this).attr('delete-associate-user-name');
        $("<div id='custom_dialog' title='Confirmation'></div>").html("Do you want to delete this user?").dialog({
            resizable: false,
            modal: true,
            buttons: {
                "Yes": function() {
                    data = {'delete-associate-user-id': associateUserId};
                    ajaxDeleteAssociateUserResponse = postDataAjax(delete_associate_url, data);
                    $(this).dialog("close");
                    ajaxDeleteAssociateUserResponse.done(function(response) {
                        if (response.redirect){
                            location.href = response.redirect;
                        }
                        if ('success' == response.status) {
                            $('#'+associateUserId).hide();
                            showAndHideDiv('.alert-success', firstName+' deleted successfully');
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
    
    
});