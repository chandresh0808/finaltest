var dataArray = [], sortColumnArray = [], sortColumnObject = {},
        sortColumnOrder = 1, // Default Sort Column
        sortOrder = 'desc',
        table_id = "#rulebook-list", // Table id used for mapping data table  

//search input box option
        display_search_input_box = true;

no_record_display_message = 'No records available';

//Disable sorting for given column
disableSortColumnIndex = [2];

//columns for data mapping from db to front end
column_array = [
    [null, false],
    [null, false],
    [null, false]
];
function wordwrap( str, width, brk, cut ) {
	 
    brk = brk || 'n';
    width = width || 75;
    cut = cut || false;
 
    if (!str) { return str; }
 
    var regex = '.{1,' +width+ '}(\s|$)' + (cut ? '|.{' +width+ '}|.+$' : '|\S+?(\s|$)');
 
    return str.match( RegExp(regex, 'g') ).join( brk );
 
}
function createCustomRowFunction(aData, nRow) {
    nRow.setAttribute('id', aData.id);
    nRow.setAttribute('class', 'clickaleRow');
    nRow.setAttribute('value', aData.description);
    nRow.setAttribute('lastExtractDt', format_date(aData.analysis_request_list.updated_dt_tm));
    nRow.setAttribute('style', 'cursor: pointer');
    var hiddenText = '<input type="hidden" id="namehidden_' + aData.id + '" value="' + aData.name + '">';
    var hiddenDescription = '<input type="hidden" id="descriptionhidden_' + aData.id + '" value="' + aData.description + '">';
    aData.name = wordwrap(aData.name, 40, '<br/>n', -1);
    column0 = aData.name + hiddenText + hiddenDescription;
    column1 = format_date(aData.updated_dt_tm);
    column2 = get_action_row_for_test_run(aData);

    $('td:eq(0)', nRow).html(column0);
    $('td:eq(1)', nRow).html(column1);
    $('td:eq(2)', nRow).html(column2);
}

function get_action_row_for_test_run(aData) {
    if (aData.name == 'Default Rulebook') {
        actions = '<a copy-rb-id=' + aData.id + ' class="action-icon text-replace sprite-copy" data-toggle="tooltip" data-placement="top" title="Copy">Copy</a>\n\
                   <a download-rb-id=' + aData.id + ' class="action-icon text-replace sprite-download mar-l20" data-toggle="tooltip" data-placement="top" title="Download">Download</a>';
    } else {
        actions = '<a copy-rb-id=' + aData.id + ' class="action-icon text-replace sprite-copy" data-toggle="tooltip" data-placement="top" title="Copy">Copy</a>\n\
                      <a download-rb-id=' + aData.id + ' class="action-icon text-replace sprite-download mar-l20" data-toggle="tooltip" data-placement="top" title="Download">Download</a>\n\
                      <a delete-rb-id=' + aData.id + ' class="action-icon text-replace sprite-delete mar-l20" data-toggle="tooltip" data-placement="top" title="Delete">Delete</a>';
    }

    return actions;

}

$(document).ready(function() {
    var rulebookDescription;
    var rulebookName;
    var rulebookLastUpdated;
    var lastExtract;
    var previousSelected;
    var selectedRulebookId;

    $('.clickaleRow').livequery('click', function() {        
        

        if (typeof previousSelected === 'undefined') {            
            previousSelected = $(this);            
        } else { 
            previousSelected.removeClass("current");
            previousSelected = $(this);
        }

        $(this).addClass("current");
        selectedRulebookId = $(this).attr('id');        
        
        /*if (selectedRulebookId == 1) {
           $("#rulebook_next").hide();
        } else {
           $("#rulebook_next").show();
        }*/

        rulebookDescription = $(this).attr('value');        
        $("#rulebook_description").html('<p>'+rulebookDescription+'<p>');
        rulebookName = $(this).closest('tr').children('td:first').text();
        $("#rulebook_name").text(rulebookName);
        rulebookLastUpdated = $(this).closest('tr').children('td:eq(1)').text();
        $("#rulebook_last_updated").text(rulebookLastUpdated);
        lastExtract = $(this).attr('lastExtractDt');
        if (lastExtract != ' - ') {
            $("#last_extracted").text(lastExtract);
        } else {
            $("#last_extracted").text('NA');
        }
    });

    $('.sprite-delete').livequery('click', function() {
        rulebookId = $(this).attr('delete-rb-id');
        $("<div id='custom_dialog' title='Confirmation'></div>").html("Are you sure you want to delete this rulebook ?").dialog({
            resizable: false,
            modal: true,
            buttons: {
                "Yes": function() {
                    data = {'rb_id': rulebookId};
                    ajaxDeleteRuleBookResponse = postDataAjax(delete_request_url, data);
                    $(this).dialog("close");
                    ajaxDeleteRuleBookResponse.done(function(response) {
                        if (response.redirect){
                            location.href = response.redirect;
                        }

                        if ('success' == response.status) {
                            $("#"+rulebookId).remove();                            
                            $('html, body').animate({
                                    scrollTop:$('.container').position().top
                            }, 2000);
                            
                            showAndHideDiv('.alert-success', 'Rulebook has been deleted successfully');
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

    $('.sprite-copy').livequery('click', function() {

        copyRulebookId = $(this).attr('copy-rb-id');
        var nameText = $("#namehidden_" + copyRulebookId).val();
        
        var descriptionText = $("#descriptionhidden_" + copyRulebookId).val();

        if (nameText != '') {
            nameText = 'Copy of ' + nameText;
        }
        
        if (descriptionText != '') {
        	descriptionText  = 'Copy of ' + descriptionText;
        }
        
        // $("#"+copyRulebookId).
        $("<div id='custom_dialog' title='Copy Rulebook'></div>").html("<script src='/js/anim-form.js'></script> <div class='clearfix'><span class='form-control-anim form-control-selected'><input id='rulebook_name_modal' class='form-control-field' type='text' name='Rulebook Name'><label class='form-control-label'><span class='form-control-label-content'>Rulebook Name</span></label></span></div><div class='clearfix'><span class='form-control-anim ta-sm-height form-control-selected'><textarea class='form-control-field' id='rulebook_description_modal' name='Description'></textarea><label class='form-control-label'><span class='form-control-label-content'>Description</span></label></span></div><div id='error_msg' style='display:none;color:red;'></div><div id='success_msg' style='display:none;color:green;'></div><div id='waiting_msg' style='display:none;' class='blink'>Please wait..we are copying your rulebook data</div>").dialog({
            resizable: false,
            modal: true,
            open: function (event, ui) {
            	$("#rulebook_name_modal").val(nameText);
            	$("#rulebook_description_modal").val(descriptionText);
            },
            buttons: {
                "submit": function() {
                    //$('.ui-dialog-buttonset').hide(); 
                    //$("#waiting_msg").show();
                    var rulebookNameModal = $("#rulebook_name_modal").val();
                    var rulebookDescriptionModal = $("#rulebook_description_modal").val();
                    if (rulebookNameModal != '') {
                        data = {'rb_id': copyRulebookId, 'rb_name': rulebookNameModal, 'rb_description': rulebookDescriptionModal};


                        ajaxCopyRuleBookResponse = postDataAjax(copy_request_url, data);

                        ajaxCopyRuleBookResponse.done(function(response) {
                            if (response.redirect){
                                location.href = response.redirect;
                            }

                            if ('success' == response.status) {
                                $("#error_msg").hide();
                                $("#success_msg").show();
                                $("#success_msg").html(response.message);
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            } else {
                                $("#success_msg").hide();
                                $("#error_msg").show();
                                $("#error_msg").html(response.message);
                            }
                        });
                    } else {
                        $("#error_msg").show();
                        $("#error_msg").html('Please enter rulebook name');
                    }

                },
                "Cancel": function() {
                    $(this).dialog("close");
                    location.reload();
                }
            }
        });
    });

    $('.sprite-download').livequery('click', function() {
        rulebookId = $(this).attr('download-rb-id');
        download_request_url = '/download-excel' + '/' + rulebookId;
        location.href = download_request_url;
    });

    /*Next button in rulebook list page*/
    $('#rulebook_next').livequery('click', function () {        
        if(typeof(selectedRulebookId) === 'undefined'){
            alert('Please select a rulebook');
        }else{
        	if (selectedRulebookId == 1) {
        		modify_rulebook_url = '/complete-rule-book';
            } else {
            	modify_rulebook_url = '/modify-rulebook'+'/'+selectedRulebookId;
            }
            
            location.href = modify_rulebook_url;
        }
    });
   
    $('#comfigure-rb').livequery('click', function () {
    	if(typeof(selectedRulebookId) === 'undefined'){
            alert('Please select a rulebook');
        }else{
        	if (selectedRulebookId == 1) {
        		modify_rulebook_url = '/complete-rule-book';
            } else {
            	modify_rulebook_url = '/modify-rulebook'+'/'+selectedRulebookId;
            }
            
            location.href = modify_rulebook_url;
        }
    });
    /* upload rulebook excel */
    $('#save_upload_excel').livequery('click', function() {
        $("#upload_excel_table tr").remove();
        $("#upload_success").hide();
        var file_rule_book = $('#file_rule_book_excel').val();

        if (!file_rule_book) {      
            //$("#upload_excel_table_div").show();
            //var ctr = '<tr><td class="text-error sprite-error">Please upload the file</td></tr>';
        	$("#upload_error").show();
            $("#upload_error").html("Please upload the file");
            //$("#upload_excel_table").append(ctr);
            return false;
        }

        $('#id_upload_excel').ajaxForm({
            beforeSend: function() {
                $(".acp-ajax-loader").show();
                //$("#upload_excel_table_div").hide();
                //$("#upload_excel_table tr").remove();
                $("#upload_error").hide();
                $("#upload_success").hide();
            },
            complete: function(xhr) {
                $('#file_rule_book_excel').val('');
                $(".acp-ajax-loader").hide();
                response = $.parseJSON(xhr.responseText);          
                $("#upload_excel_table_div").show();
                if (response.status == 'fail') {
                    var ctr; 
                    error_message_array = response.errorMessage;
                    for (var i in error_message_array) {
                        ctr += '<tr><td class="text-error"><span class="action-icon text-replace sprite-error mar-r10"></span>' + error_message_array[i] + '</td></tr>';
                    }
                    $("#upload_excel_table").append(ctr);
                } else if (response.status == 'success') {                    
                    //var ctr = '<tr><td class="text-success sprite-success">Excel file uploaded successfully</td></tr>';
                    //$("#upload_excel_table").append(ctr);       
                    $("#upload_success").show();
                    $("#upload_success").html("Excel file uploaded successfully");
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
        });

    });

});