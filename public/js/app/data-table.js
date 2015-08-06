var dataTableObject;
urlArray = $.parseJSON($('#url-array').val());
url = urlArray.data_url;
column_name = '';
//Generate column for mapping data from db to front end
for (var i in column_array) {
    var columnObject = {};
    columnObject.mDataProp = column_array[i][0];
    if (column_array[i][1]) {
        columnObject.mRender = function (data, type, full) {
            return customRenderFunction(data);
        };
    }
    dataArray.push(columnObject);
    delete columnObject;
}
options = {
    "stateSave": false,
    "aoColumns": dataArray,
    "bDestroy": false,
    "sAjaxSource": url,
    "bServerSide": true,
    "bFilter": display_search_input_box,
    "order": [
        [sortColumnOrder, sortOrder]
    ],
    "aLengthMenu": [10, 20, 50, 100],
    "sPagination": true,
    "oLanguage": {
        "oPaginate": {
            "sFirst": "« First",
            "sLast": "Last »",
            "sNext": "»",
            "sPrevious": "«"},
        "sEmptyTable": no_record_display_message,
        "sLengthMenu": "Show: _MENU_ entries"


    },
    "fnCreatedRow": function (nRow, aData, iDataIndex) {    
        createCustomRowFunction(aData, nRow);
    },
    "fnDrawCallback": function(oSettings) {
    	
        if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
            $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
            $(oSettings.nTableWrapper).find('.dataTables_info').hide();
        } else { 
        	$(oSettings.nTableWrapper).find('.dataTables_paginate').show();
        	$(oSettings.nTableWrapper).find('.dataTables_info').show();
        }
    },
    aoColumnDefs: [
        {
            bSortable: false,
            aTargets: disableSortColumnIndex
        }
    ],
    sPaginationType: "full_numbers",
    "sDom": '<"H"lfrp>t<"F"ip>',

};


$(document).ready(function () {

    //To avoid warning popups
    $.fn.dataTableExt.sErrMode = 'throw';
    /* call data table plugin*/
    dataTableObject = $(table_id).dataTable(options);

});



function format_date(date,separator) {

    if (date == null) {
        return " - ";
    }
    
    if (!separator) {
        separator = '/';
    }
    
    var datePart = date.match(/\d+/g),
            year = datePart[0],
            month = datePart[1], day = datePart[2];
    return month + separator + day + separator + year;
}



function format_date_time(date,separator) {

    if (date == null) {
        return " - ";
    }
    
    if (!separator) {
        separator = '/';
    }
    
    var datePart = date.match(/\d+/g),
            year = datePart[0],
            month = datePart[1], day = datePart[2];
    
    return month + separator + day + separator + year + " " + datePart[3] + ":" + datePart[4] + ":" + datePart[5];
}



