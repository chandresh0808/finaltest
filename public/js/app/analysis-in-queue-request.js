var dataArray = [], sortColumnArray = [], sortColumnObject = {},
sortColumnOrder = 1, // Default Sort Column
sortOrder = 'desc',
table_id = "#list-analysis-in-queue" // Table id used for mapping data table  

//search input box option
display_search_input_box = false;

no_record_display_message = 'No records available';

//Disable sorting for given column
disableSortColumnIndex = [];

//columns for data mapping from db to front end
column_array = [
    [null, false],
    [null, false]
];

function createCustomRowFunction(aData, nRow) {    
    column0 = aData['0'].analysis_request_name;
    column1 = format_date_time(aData['0'].created_dt_tm);        
    
    $('td:eq(0)', nRow).html(column0);
    $('td:eq(1)', nRow).html(column1);    
}

$(document).ready(function() {
   $('#complete-analysis-tab').livequery('click', function() {        
        location.href = '/analysis-reports';
    }); 
});