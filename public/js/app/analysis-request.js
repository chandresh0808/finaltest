var dataArray = [], sortColumnArray = [], sortColumnObject = {},
        sortColumnOrder = 0, // Default Sort Column
        sortOrder = 'asc',
        table_id = "#list-analysis-report", // Table id used for mapping data table  

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
    column_icon = get_default_image(aData['0']);
    cDate = format_date(aData['0'].file_created_dt_tm, '_');

    column0 = aData.extractName + '_' + aData.name + '_' + aData['0'].analysis_request_name;
    if (cDate != ' - ') {
        column0 += '_' + cDate
    }
    column0 = column_icon + wordwrap(column0, 75, '<br/>', -1);
    column1 = format_date(aData['0'].file_created_dt_tm);
    column2 = format_date(aData['0'].file_expire_dt_tm);
    expireResult = compareExpireDate(aData['0']);
    column3 = get_action_row_for_ar(aData['0'], expireResult);

    if (expireResult == true) {
        $('td:eq(2)', nRow).addClass("text-error");
    }

    $('td:eq(0)', nRow).html(column0);
    $('td:eq(1)', nRow).html(column1);
    $('td:eq(2)', nRow).html(column2);
    $('td:eq(3)', nRow).html(column3);
}

function get_analysis_count(aData) {
    analysisCount = ' - ';
    if (!$.isEmptyObject(aData.analysis_request_report_file)) {
        analysisCount = aData.analysis_request_report_file.length;
    }
    return analysisCount;
}

function get_expire_date(aData) {
    expire_date = ' - ';
    if (!$.isEmptyObject(aData.analysis_request_report_file)) {
        expire_date = format_date(aData.analysis_request_report_file[0].expire_date);
    }
    return expire_date;
}

function get_created_date(aData) {
    expire_date = ' - ';

    expire_date = format_date(aData.file_created_dt_tm)

    return expire_date;
}

function generate_collapse_image(aData) {
    row = '<a href="#" id=' + aData.id + ' class="acpExpandCollapse" >arrow</a>';
    return row;
}

function get_default_image(aData) {
    jsonData = 0;
    arrowClass = '';
    if (aData.analysis_request_report_file.length) {
        jsonData = JSON.stringify(aData.analysis_request_report_file);
        //$('#analysis_request_'+aData.id).data('ar_data',jsonData);   
        arrowClass = 'arrow ';
    }
    imgTag = '<a id=analysis_request_' + aData.id + ' data-json-data=' + jsonData + ' class="' + arrowClass + 'collapsed acpExpandCollapse" ><span class="icon-bar"></span><span class="icon-bar"></span></a>';
    return imgTag;
}


function get_action_row_for_ar(aData, expireResult) {
    var timerClass = ' sprite-ext-time-disabled';
    var disableCursor = "style='cursor:default'";
    if (expireResult == true) {
        disableCursor = '';
        timerClass = ' sprite-ext-time acp-dynamic-extend-expire';
    }
    var download_ar_class = '';
    var delete_ar_class = '';
    if (aData.analysis_request_report_file.length) {
        download_ar_class = 'acp-dynamic-download';
        delete_ar_class = 'acp-delete-ar';
    }
    er = aData.file_expire_dt_tm;
    actions = '<a ' + disableCursor + ' data-ar-e-date=' + er + ' data-ar-id=' + aData.id + ' class="action-icon text-replace' + timerClass + '" title="" data-placement="top" data-toggle="tooltip"  data-original-title="Extend Time">Extend Time</a>\n\
            <a data-ar-id=' + aData.id + ' class="' + download_ar_class + ' action-icon text-replace sprite-download mar-l20" title="" data-placement="top" data-toggle="tooltip" data-original-title="Download">Download</a>\n\
            <a data-ar-id=' + aData.id + ' class="acp-delete-ar action-icon text-replace sprite-delete mar-l20" title="" data-placement="top" data-toggle="tooltip" data-original-title="Delete">Delete</a>';
    return actions;

}

function compareExpireDate(aData) {

    if (aData.file_expire_dt_tm) {
        cd = aData.file_expire_dt_tm;
        var dPart = cd.match(/\d+/g);
        var ed = new Date(dPart[0], dPart[1] - 1, dPart[2], dPart[3], dPart[4], dPart[5]);
        var d = new Date();
        d.setDate(d.getDate() + parseInt(expireDateValue));
        if (d >= ed) {
            return true;
        }
    }
    return false;
}

$(document).ready(function() {

    $('.acpExpandCollapse').livequery('click', function() {
        tr_id = $(this).attr('id');
        jsonData = $(this).attr('data-json-data');
        if (jsonData == 0) {
            return false;
        }
        var tr = $(this).closest('tr');
        if (tr.hasClass('acp-extended')) {
            tr.next(".acp-dynamic-row").remove();
            tr.removeClass('acp-extended');
            $('#' + tr_id).addClass('collapsed');
        } else {
            $('#' + tr_id).removeClass('collapsed');
            collapseRow = generateCollapseRow(jsonData);
            tr.after(collapseRow);
            tr.addClass('acp-extended');
        }
    });

    function generateCollapseRow(jsonData) {
        response = JSON.parse(jsonData);
        liRow = '';
        $.each(response, function(index, item) {
            sN = index + 1;
            var file_name = item.file_name.split(".");
            liRow += '<li>' + sN + '. ' + file_name[0] + '</li>';
        });
        collapseRow = '<tr class="acp-dynamic-row custom-panel" ><td colspan="5"><ul class="pad-l30 pad-t10 pad-b10">' + liRow + '</ul></td></tr>';
        return collapseRow;
    }

    /*expand ar */
    $('.acp-dynamic-extend-expire').livequery('click', function() {
        var co = $(this);
        var prtd = $(this).closest('td').prev('td');
        var ar_id = $(this).attr('data-ar-id');
        var enhance_ev = enhanceExpireDateValue;
        var ed = $(this).attr('data-ar-e-date');
        var aed = new Date(ed);
        aed.setDate(aed.getDate() + parseInt(enhance_ev));
        var dd = (aed.getMonth() + 1);
        var someFormattedDate = ('0' + dd).substr(-2, 2) + '/' + ('0' + aed.getDate()).substr(-2, 2) + '/' + aed.getFullYear();

        $("<div id='custom_dialog' title='Confirmation'></div>").html("Please confirm extending the report expiration by " + enhanceExpireDateValue + " days").dialog({
            resizable: false,
            modal: true,
            buttons: {
                "Yes": function() {
                    data = {'ar_id': ar_id, 'enhance_ev': enhance_ev};
                    ajaxDeleteCartItemResponse = postDataAjax(extend_analysis_request_url, data);
                    $(this).dialog("close");
                    ajaxDeleteCartItemResponse.done(function(response) {
                        if ('success' == response.status) {
                            prtd.text(someFormattedDate);
                            prtd.removeClass('text-error');
                            co.addClass('sprite-ext-time-disabled');
                            co.removeClass('acp-dynamic-extend-expire');
                            co.removeClass('sprite-ext-time');                            
                            showAndHideDiv('.alert-success', 'Expire date extended successfully');
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

    /*Download ar*/
    $('.acp-dynamic-download').livequery('click', function() {

        var ar_id = $(this).attr('data-ar-id');
        data = {'ar_id': ar_id};

        beforeSendFunc = function() {
            $('.acp-ajax-loader').show();
        };

        completeFunc = function() {
            $('.acp-ajax-loader').hide();
        };

        ajaxDeleteResponse = postDataAjax(download_analysis_request_url, data, beforeSendFunc, completeFunc);
        ajaxDeleteResponse.done(function(response) {
            if ('success' == response.status) {
                window.location = '\download-ar-excel';
            } else {
                showAndHideDiv('.alert-error', response.message);
            }
        });
    });

    /*Delete ar*/
    $('.acp-delete-ar').livequery('click', function() {
        var ar_id = $(this).attr('data-ar-id');
        $("<div id='custom_dialog' title='Confirmation'></div>").html("Are you sure you want to delete this analysis?").dialog({
            resizable: false,
            modal: true,
            buttons: {
                "Yes": function() {
                    data = {'ar_id': ar_id};

                    beforeSendFunc = function() {
                        $('.acp-ajax-loader').show();
                    };

                    completeFunc = function() {
                        $('.acp-ajax-loader').hide();
                    };

                    ajaxDeleteArResponse = postDataAjax(delete_analysis_request_url, data, beforeSendFunc, completeFunc);
                    $(this).dialog("close");
                    ajaxDeleteArResponse.done(function(response) {
                        if ('success' == response.status) {
                            $("#tr_id_" + ar_id).remove();
                            showAndHideDiv('.alert-success', 'Analysis deleted successfully');
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


    /* Validation for request analysis support */

    $('#submit_request_support').livequery('click', function() {
        clearErrorMessages('error');
        isTextFieldContainValue('email', 'Please enter email');
        isSelectBoxHasZero('category', 'Please select category');
        //isSelectBoxHasZero('analysis_name', 'Please select analysis name');
        isTextFieldContainValue('analysis_request_details', 'Please enter request details');
        if (getErrorMessageArray().length) {
            displayErrorMessages();
        } else {
            $("#submit_request_support").submit();
            return true;
        }
        return false;
    });


    $('#submit_analysis_request').livequery('click', function() {        
        clearErrorMessages('error');
        isTextFieldContainValue('analysis_name', 'Please enter analysis name');
        //isTextFieldContainValue('analysis_details', 'Please enter analysis details');
        isSelectBoxHasZero('extracts', 'Please select extract');
        isSelectBoxHasZero('rule_book', 'Please select rulebook');

        if (getErrorMessageArray().length) {
            displayErrorMessages();
            return false;
        } else {
            if ($('#credit_points').text() == 0) {                
                $("<div id='custom_dialog' title='Confirmation'></div>").html("Credits are not available for a full audit analysis. This request will produce a summary audit analysis. Press Continue to confirm the request. Press Cancel to cancel this request.").dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Continue": function() {                            
                            $("#analysis-request").submit();                            
                            return true;                            
                        },
                        "Cancel": function() {
                            $(this).dialog("close");
                        }
                    }
                });
            } else {
                  $("#analysis-request").submit();
                  return true;          
            }
        }
        return false;
    });
    
    $('#analysis-in-queue-tab').livequery('click', function() {        
        location.href = '/analysis-in-queue-reports';
    });   
    
    
}); //document ready 

function successAlert(message){
    $("<div id='custom_dialog' title='Confirmation'></div>").html("Analysis request has been created successfully").dialog({
        resizable: false,
        modal: true,
        buttons: {                
            "OK": function() {
                $(this).dialog("close");
            }
        }
    });
}