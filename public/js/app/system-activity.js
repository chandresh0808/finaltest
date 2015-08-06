var dataArray = [], sortColumnArray = [], sortColumnObject = {},
        sortColumnOrder = 2, // Default Sort Column
        sortOrder = 'desc',
        table_id = "#list-system-activity", // Table id used for mapping data table  

        collapseJsonDataObj = {};

//search input box option
display_search_input_box = true;

no_record_display_message = 'No records available';

//Disable sorting for given column
disableSortColumnIndex = [3];

dumy = [10, 25, 50, 100];

//columns for data mapping from db to front end
column_array = [
    [null, false],
    [null, false],
    [null, false],
    [null, false]
];

function createCustomRowFunction(aData, nRow) {
     
    column0 = aData.type; 
    column1 = aData.firstName + " " + aData.lastName;
    if (aData.firstName == null) {
        column1 = 'System'
    }

    column2 = format_date_time(aData.createdDtTm);
    column3 = wordwrap(aData.comment, 45, '<br/>', -1);
    
    $('td:eq(0)', nRow).html(column0);
    $('td:eq(1)', nRow).html(column1);
    $('td:eq(2)', nRow).html(column2);
    $('td:eq(3)', nRow).html(column3);
}


