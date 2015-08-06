var dataArray = [], sortColumnArray = [], sortColumnObject = {},
        sortColumnOrder = 0, // Default Sort Column
        sortOrder = 'asc',
        table_id = "#list-extracts", // Table id used for mapping data table  

        collapseJsonDataObj = {};

//search input box option
display_search_input_box = false;

no_record_display_message = 'No records available';

//Disable sorting for given column
disableSortColumnIndex = [3];

dumy = [10, 20, 50, 100];

//columns for data mapping from db to front end
column_array = [
    [null, false],
    [null, false],
    [null, false],
    [null, false]
];

function createCustomRowFunction(aData, nRow) {
     nRow.setAttribute('id', "tr_id_" + aData['0'].id);
    column0 = aData[0].extract_name
    column1 = aData.firstName + " " + aData.lastName;
    column2 = format_date(aData[0].created_dt_tm);
    column3 = get_action_row_for_extracts(aData);

    $('td:eq(0)', nRow).html(column0);
    $('td:eq(1)', nRow).html(column1);
    $('td:eq(2)', nRow).html(column2);
    $('td:eq(3)', nRow).html(column3);
}

function get_action_row_for_extracts(aData) {    
    var deleteClass = ' text-replace sprite-delete mar-l20';
    var delete_extract_class = ' acp-extracts-delete';
    var arl = aData[0].analysis_request_list[0];
 
    if (!$.isEmptyObject(arl)) {      
        deleteClass = '  text-replace sprite-delete-disable mar-l20';
        delete_extract_class = '';
        expireDate = format_date(aData[0].analysis_request_list[0].file_expire_dt_tm);
        actions = '<span data-placement="top" data-toggle="tooltip"  data-original-title="Expire date time">Expires on '+expireDate+'</span>';
    } else {
        actions = '<a href="javascript:void(0)" data-extract-name='+aData[0].extract_name+' data-extract-id=' + aData[0].id + ' class="action-icon' + deleteClass + delete_extract_class + '" title="" data-placement="top" data-toggle="tooltip"  data-original-title=""></a>';
    }
    
    return actions;

}


$(document).ready(function() {

    /*Delete ar*/
    $('.acp-extracts-delete').livequery('click', function() {
        var extract_id = $(this).attr('data-extract-id');
        var extract_name = $(this).attr('data-extract-name');
        $("<div id='custom_dialog' title='Confirmation'></div>").html("Are you sure you want to delete <b>"+extract_name+"</b> extract ?").dialog({
            resizable: false,
            modal: true,
            buttons: {
                "Yes": function() {
                    data = {'extract_id': extract_id};

                    beforeSendFunc = function() {
                        $('.acp-ajax-loader').show();
                    };

                    completeFunc = function() {
                        $('.acp-ajax-loader').hide();
                    };

                    ajaxDeleteArResponse = postDataAjax(delete_extract_url, data, beforeSendFunc, completeFunc);
                    $(this).dialog("close");
                    ajaxDeleteArResponse.done(function(response) {
                        if ('success' == response.status) {
                            $("#tr_id_"+extract_id).remove();
                            showAndHideDiv('.alert-success', 'Extract deleted successfully');
                            
                                setTimeout(function() {
                                   location.reload();
                                }, 10000);
                                                       
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



}); //document ready 