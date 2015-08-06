var url = window.location.href;
var rulebook_id = url.split('/').pop();
var rhjf_id;
var rhr_id = $('#default_rulebook_has_risk_id').val();
var prevRHRID  = rhr_id;
var prevRHJFID = '';
var prevJFHTID = '';
$(document).ready(function () {    
    defaultJobFunctionList();
    defaultTransactionList();
    jobFunctionDropdownInitialization();
    transactionDropdownInitialization();
    $("#"+rhr_id).addClass('open');
    ajaxCalls(rhr_id);
    
    /*job function search functionality*/
    $("#search-job-function").keyup(function() {        
        var search_key = $(this).val().toLowerCase();        
         $(".job-function-listing .cr-title").each(function() {            
            var match = $(this).text().toLowerCase();            
            $(this).closest('.job-function-listing')[ match.indexOf(search_key) !== -1 ? 'show' : 'hide' ]();
         });         
         
         if ($('.job-func-list').children(':visible').length == 0) {                        
            $(".job-func-list").append("<p class='no-job-function-results'>No results found</p>");            
         }
         
         if ($('.job-func-list').children(':visible').length > 1 && $('.job-func-list').children().hasClass('no-job-function-results')) {            
            $(".no-job-function-results").remove();
         }
    }); 

    /*transaction search functionality*/
    $("#search-transaction").keyup(function() {        
        var search_key = $(this).val().toLowerCase();        
         $(".transaction-listing .cr-title").each(function() {
            var match = $(this).text().toLowerCase();            
            $(this).closest('.transaction-listing')[ match.indexOf(search_key) !== -1 ? 'show' : 'hide' ]();
         });

         if($('.trans-list').children(':visible').length == 0) {
            $(".trans-list").append("<p class='no-transaction-results'>No results found</p>");
         }
         
         if($('.trans-list').children(':visible').length > 1 && $('.trans-list').children().hasClass('no-transaction-results')) {
            $(".no-transaction-results").remove();
         }
    });


    $( "#job_function_id" ).keyup(function() {
       if($("#job_function_id").val() != '' || $("#job_function_description").val() != ''){           
           $('#default_job_function_list').multiselect('disable');
       }else{           
           $('#default_job_function_list').multiselect('enable');
       }
    });

    $("#job_function_description").keyup(function() {
        if ($("#job_function_id").val() != '' || $("#job_function_description").val() != '') {            
            $('#default_job_function_list').multiselect('disable');
        } else {            
            $('#default_job_function_list').multiselect('enable');
        }
    });

    $("#transaction_id").keyup(function() {
        if ($("#transaction_id").val() != '' || $("#transaction_description").val() != '') {
            $('#default_transaction_list').multiselect('disable');
        } else {
            $('#default_transaction_list').multiselect('enable');
        }
    });

    $("#transaction_description").keyup(function() {
        if ($("#transaction_id").val() != '' || $("#transaction_description").val() != '') {
            $('#default_transaction_list').multiselect('disable');
        } else {
            $('#default_transaction_list').multiselect('enable');
        }
    });
    
    $('#add_risk_icon').livequery('click', function() {
        $("#risk_idErrorMsg").text('Risk ID');
        $("#risk_descriptionErrorMsg").text('Description');
        $("#risk_id").val('');
        $("#risk_description").val('');
        clearErrorMessages('error');
    });

    $('#add_job_function_icon').livequery('click', function() {        
        $("#jobFunctionModalLabel").text("ADD JOB FUNCTION FOR RISK ID "+$("#"+rhr_id).children('a').text());
        $('.multiselect-container li.active').removeClass('active');        
        $('.multiselect-container li').find('input[type=checkbox]').prop('checked', false);        
        $('.multiselect').attr('title', 'Select Job Functions');
        $('.multiselect-selected-text').text('Select Job Functions');        
        $('#default_job_function_list :selected').removeAttr('selected');
               
        $('#job_function_error_msg').hide();
        $('#job_function_idErrorMsg').text('Custom Function ID');
        $('#job_function_descriptionErrorMsg').text('Description');
        $("#job_function_id").val('');
        $("#job_function_description").val('');        
        clearErrorMessages('error');        
    });

    $('#add_transaction_icon').livequery('click', function() {        
        $("#transactionModalLabel").text("ADD TRANSACTION FOR JOB FUNCTION ID "+$("#job_function_id_"+rhjf_id).children('a').text());
        $('.multiselect-container li.active').removeClass('active');        
        $('.multiselect-container li').find('input[type=checkbox]').prop('checked', false);        
        $('.multiselect').attr('title', 'Select Transactions');
        $('.multiselect-selected-text').text('Select Transactions');
        $('#default_transaction_list :selected').removeAttr('selected');
               
        $('#transaction_error_msg').hide();
        $("#transaction_idErrorMsg").text('Custom Transaction ID');
        $("#transaction_descriptionErrorMsg").text('Description');
        $("#transaction_id").val('');
        $("#transaction_description").val('');
        clearErrorMessages('error');
    });
    
    $('.risk-list li').livequery('click', function() {
    	
       $('.job-func-list li').remove();
       $('.trans-list li').remove();
       
       if (prevRHRID != '') {
    	   $("#"+prevRHRID).removeClass('open');
       }
       if (prevRHJFID != '') {
    	   $("#"+prevRHJFID).removeClass('open');
       }
       if (prevJFHTID != '') {
    	   $("#"+prevJFHTID).removeClass('open');
       }       
       rhr_id = $(this).attr('id');        
       prevRHRID  = rhr_id;
       $("#"+prevRHRID).addClass('open');
       ajaxCalls(rhr_id);       
    });


    $(".cr-title").click(function() {
        $(this).parent('.cr-list li').toggleClass("open");
    });

    $('.job-func-list li').livequery('click', function() {
        $('.trans-list li').remove();
        if (prevRHJFID != '') {
     	   $("#"+prevRHJFID).removeClass('open');
        }
        if (prevJFHTID != '') {
     	   $("#"+prevJFHTID).removeClass('open');
        }
        
        var id;
        var j;
        var rhjf_id_string = $(this).attr('id');
        var rhjf_id_array = rhjf_id_string.split("_");
        rhjf_id = rhjf_id_array[3];
        prevRHJFID = "job_function_id_"+rhjf_id;
        $("#"+prevRHJFID).addClass('open');
        if ($("#"+prevRHJFID).hasClass('is-default')) {
            $("#add_transaction_icon").remove();
        } else {            
            if ($('#add_transaction_icon').length == 0) {
                $(".addTransaction").append('<a href="javascript:void(0)" class="text-replace sprite-add action-icon" data-toggle="tooltip" data-placement="left" title="Add Transactions" id="add_transaction_icon">Add</a>');
            }
        }
        data = {'riskHasJobFunction_id': rhjf_id, 'rulebook_id' : rulebook_id};
        ajaxTransactionListResponse = postDataAjax(on_select_transaction_url, data);
        ajaxTransactionListResponse.done(function(t_response) {            
            if (t_response.redirect){
                location.href = t_response.redirect;
            }
            if (t_response.length > 0) {
                /*for (j = 0; j < response.length; j++) {
                    id = response[j].id;
                    $("#transaction_id_" + id).show();
                }*/
            	$(".trans-list").empty();
            	for (j = 0; j < t_response.length; j++) {
                    t_id = t_response[j].id;
                    //$("#transaction_id_" + t_id).show();
                    if (t_response[j].is_default_transaction == 1) {
                        if ($("#job_function_id_"+rhjf_id).hasClass('is-default')) {
                            $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                        } else {
                            $(".trans-list").append("<li class='transaction-listing is-default' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='action-icon-sm text-replace sprite-sm-remove un-assign' data-toggle='tooltip' data-placement='top' title='Remove'>Remove</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                        }
                    } else {
                        $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><span data-toggle='modal' data-acp-type='Transaction' data-target='#edit-acp-data' class='pull-left acp-risk-edit'><a href='javascript:void(0)' class='text-replace sprite-edit action-icon-sm mar-r10' data-toggle='tooltip' data-placement='top' title='Edit'>Edit</a></span><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='text-replace sprite-delete-sm action-icon-sm un-assign' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                    }
                    
                    if (j == 0) {
                    	prevJFHTID = "transaction_id_"+t_response[j].id;
                    	$("#transaction_id_"+t_response[j].id).addClass('open');
                    }
                }
            }
        });
        displayEmptyIcons();
    });

    $('.trans-list li').livequery('click', function() {
    	if (prevJFHTID != '') {
      	   $("#"+prevJFHTID).removeClass('open');
        }
    	var id;
        var j;
        var jfht_id_string = $(this).attr('id');
        var jfht_id_array = jfht_id_string.split("_");
        jfht_id_array_id = jfht_id_array[2];
        prevJFHTID = "transaction_id_"+jfht_id_array_id;
        $("#"+prevJFHTID).addClass('open');        
    });

    $('#risk_submit').livequery('click', function() {
        
        clearErrorMessages('error');
        isTextFieldContainValue('risk_id', 'Please enter risk name');
        isTextFieldContainValue('risk_description', 'Please enter description');

        if (getErrorMessageArray().length) {
            displayErrorMessages();
        } else {
            var riskId = $("#risk_id").val();
            var riskDescription = $("#risk_description").val();
            
            if (/<[a-z][\s\S]*>/i.test(riskId)) {
                $('#risk_idErrorClass').addClass('error');                    
                $('#risk_idErrorMsg').html('Invalid RiskId');                
            }
            
            if (/<[a-z][\s\S]*>/i.test(riskDescription)) {
                $('#risk_descriptionErrorClass').addClass('error');                    
                $('#risk_descriptionErrorMsg').html('Invalid Description');                
            }
            
            if (/<[a-z][\s\S]*>/i.test(riskId) || /<[a-z][\s\S]*>/i.test(riskDescription)) {
               return false; 
            }
            
            data = {'risk_id': riskId, 'risk_description': riskDescription, 'rulebook_id': rulebook_id};
            ajaxAddRiskResponse = postDataAjax(add_risk_url, data);
            ajaxAddRiskResponse.done(function(response) {                            
                if (response.redirect){
                    location.href = response.redirect;
                }
                if ('success' == response.status) {                    
                    location.reload();
                } else {                    
                    $('#risk_idErrorClass').addClass('error');                    
                    $('#risk_idErrorMsg').html(response.riskName);
                }
            });
        }
    });


    $('#job_function_submit').livequery('click', function() {        
        clearErrorMessages('error');
        if ($('#default_job_function_list').prop("disabled")) {               
            isTextFieldContainValue('job_function_id', 'Please enter job function name');
            isTextFieldContainValue('job_function_description', 'Please enter description');
            var jobFunctionId = $("#job_function_id").val();
            var jobFunctionDescription = $("#job_function_description").val();
            
            if (/<[a-z][\s\S]*>/i.test(jobFunctionId)) {
                $('#job_function_idErrorClass').addClass('error');                    
                $('#job_function_idErrorMsg').html('Invalid JobFunctionId');                
            }
            
            if (/<[a-z][\s\S]*>/i.test(jobFunctionDescription)) {
                $('#job_function_descriptionErrorClass').addClass('error');                    
                $('#job_function_descriptionErrorMsg').html('Invalid Description');                
            }
            
            if (/<[a-z][\s\S]*>/i.test(jobFunctionId) || /<[a-z][\s\S]*>/i.test(jobFunctionDescription)) {
               return false; 
            }            
            
        } else {                                    
            var dropdownSelect = true;
            var jobFunctionList = document.getElementById("default_job_function_list");        
            var selectedList = {};
            for (var i = 0; i < jobFunctionList.length; i++) {
                if (jobFunctionList.options[i].selected){ 
                    selectedList[jobFunctionList.options[i].text] = jobFunctionList.options[i].value;                
                }
            }            
        }       
        
        if (jQuery.isEmptyObject(selectedList) && typeof jobFunctionId == 'undefined') {            
            isTextFieldContainValue('job_function_id', 'Please enter job function name');
            isTextFieldContainValue('job_function_description', 'Please enter description');
            $('#job_function_error_msg').show();
            $('#job_function_error_msg').html('Or Select default job function');            
        }        
        
        
        if (getErrorMessageArray().length) {
            displayErrorMessages();
        } else {
            if (typeof selectedList != 'undefined'){
                data = {'risk_id': rhr_id, 'selected_job_function_list': selectedList , 'rulebook_id': rulebook_id};
            } else {
                data = {'risk_id': rhr_id,'job_function_id': jobFunctionId, 'job_function_description': jobFunctionDescription, 'rulebook_id': rulebook_id};
            }
            
            ajaxAddJobFunctionResponse = postDataAjax(add_job_function_url, data);
            ajaxAddJobFunctionResponse.done(function(response) {
                if (response.redirect){
                    location.href = response.redirect;
                }
                if ('success' == response.status) {
                    //location.reload();
                   var i;
                       var j;   
                       var count;

                       jf_data = {'rulebookHasRisk_id': rhr_id, 'rulebook_id' : rulebook_id};
                       ajaxJobFunctionListResponse = postDataAjax(on_select_job_function_url, jf_data);
                       ajaxJobFunctionListResponse.done(function(jf_response) {        
                            if(jf_response.length > 0){      
                                    $(".job-func-list").empty();
                                for(i = 0; i < jf_response.length; i++){
                                    if (jf_response[i].is_default_job_function == 1) {
                                        $(".job-func-list").append("<li class='job-function-listing is-default' id='job_function_id_"+jf_response[i].id+"'><div class='pull-right'><a href='javascript:void(0)' riskHasJobFunction_id='"+jf_response[i].id+"' class='action-icon-sm text-replace sprite-sm-remove un-assign' data-toggle='tooltip' data-placement='top' title='Remove'>Remove</a></div><a href='javascript:void(0)' class='cr-title'>"+jf_response[i].sap_job_function_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+jf_response[i].description+"</p></li>")
                                    } else {
                                        $(".job-func-list").append("<li class='job-function-listing' id='job_function_id_"+jf_response[i].id+"'><div class='pull-right'><span data-toggle='modal' data-acp-type='Job function' data-target='#edit-acp-data' class='pull-left acp-risk-edit'><a href='javascript:void(0)' class='text-replace sprite-edit action-icon-sm mar-r10' data-toggle='tooltip' data-placement='top' title='Edit'>Edit</a></span><a href='javascript:void(0)' riskHasJobFunction_id='"+jf_response[i].id+"' class='text-replace sprite-delete-sm action-icon-sm un-assign' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</a></div><a href='javascript:void(0)' class='cr-title'>"+jf_response[i].sap_job_function_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+jf_response[i].description+"</p></li>")
                                    }
                                    jf_id = jf_response[i].id;
                                    if (i == 0) {                                        
                                        $("#job_function_id_"+jf_response[i].id).addClass('open');
                                        if ($("#job_function_id_"+jf_response[i].id).hasClass('is-default')) {
                                            $("#add_transaction_icon").remove();
                                        } else {            
                                            if ($('#add_transaction_icon').length == 0) {
                                                $(".addTransaction").append('<a href="javascript:void(0)" class="text-replace sprite-add action-icon" data-toggle="tooltip" data-placement="left" title="Add Transactions" id="add_transaction_icon">Add</a>');
                                            }
                                        }
                                        prevRHJFID = "job_function_id_"+jf_response[i].id;
                                        rhjf_id = jf_id;
                                        t_data = {'riskHasJobFunction_id': jf_id, 'rulebook_id' : rulebook_id};                        
                                        ajaxTransactionListResponse = postDataAjax(on_select_transaction_url, t_data);
                                        ajaxTransactionListResponse.done(function(t_response) {

                                            if (t_response.length > 0) {
                                                    $(".trans-list").empty();
                                                for (j = 0; j < t_response.length; j++) {
                                                    t_id = t_response[j].id;
                                                    //$("#transaction_id_" + t_id).show();
                                                    if (t_response[j].is_default_transaction == 1) {
                                                        if ($("#job_function_id_"+jf_id).hasClass('is-default')) {
                                                            $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                                        } else {
                                                            $(".trans-list").append("<li class='transaction-listing is-default' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='action-icon-sm text-replace sprite-sm-remove un-assign' data-toggle='tooltip' data-placement='top' title='Remove'>Remove</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                                        }
                                                    } else {
                                                        $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><span data-toggle='modal' data-acp-type='Transaction' data-target='#edit-acp-data' class='pull-left acp-risk-edit'><a href='javascript:void(0)' class='text-replace sprite-edit action-icon-sm mar-r10' data-toggle='tooltip' data-placement='top' title='Edit'>Edit</a></span><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='text-replace sprite-delete-sm action-icon-sm un-assign' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                                    }
                                                    if (j == 0) {
                                                        prevJFHTID = "transaction_id_"+t_response[j].id;
                                                        $("#transaction_id_"+t_response[j].id).addClass('open');
                                                    }
                                                }
                                            } else {
                                                $('.trans-list li').hide();
                                            }
                                        });
                                    }

                                }
                            }
                      });                        
                      $("#job_function_id").val('');
                      $("#job_function_description").val('');                      
                      $("#default_job_function_list").val('');
                      $("#job_function_error_msg").html('');
                      $("#add-job-function").modal('hide');                      
                } else {
                    
                    if ($('#default_job_function_list').prop("disabled")) {
                        $('#job_function_idErrorClass').addClass('error');                    
                        $('#job_function_idErrorMsg').html(response.jobFunctionName);
                    } else {                        
                        $('#job_function_error_msg').show();
                        $('#job_function_error_msg').html(response.jobFunctionName);
                    }                    
                }            
            });
        }
        displayEmptyIcons();        
        defaultJobFunctionList();
        $('#default_job_function_list').multiselect('rebuild');
    });
    $('#go_next').livequery('click', function() {  
    	var previousSelectedId = $("#previousSelectedId").val();
    	
    	var completed_rulebook_url = '';
    	if(typeof(previousSelectedId) === 'undefined'){ 
    		completed_rulebook_url = '/complete-rule-book';
    		location.href = completed_rulebook_url;
        }else{
        	
        	if (previousSelectedId == 1) {
        		completed_rulebook_url = '/complete-rule-book';
            } else {
            	completed_rulebook_url = '/complete-rule-book'+'/'+previousSelectedId;
            }
        	location.href = completed_rulebook_url;
        }
    });
    $('#transaction_submit').livequery('click', function() {        
        clearErrorMessages('error');
        if ($('#default_transaction_list').prop("disabled")) {
            isTextFieldContainValue('transaction_id', 'Please enter transaction name');
            isTextFieldContainValue('transaction_description', 'Please enter description');
            var transactionId = $("#transaction_id").val();
            var transactionDescription = $("#transaction_description").val();
            
            if (/<[a-z][\s\S]*>/i.test(transactionId)) {
                $('#transaction_idErrorClass').addClass('error');                    
                $('#transaction_idErrorMsg').html('Invalid TransactionId');                
            }
            
            if (/<[a-z][\s\S]*>/i.test(transactionDescription)) {
                $('#transaction_descriptionErrorClass').addClass('error');                    
                $('#transaction_descriptionErrorMsg').html('Invalid Description');                
            }
            
            if (/<[a-z][\s\S]*>/i.test(transactionId) || /<[a-z][\s\S]*>/i.test(transactionDescription)) {
               return false; 
            }

        } else {
            
            var transactionList = document.getElementById("default_transaction_list");        
            var selectedList = {};
            for (var i = 0; i < transactionList.length; i++) {
                if (transactionList.options[i].selected){ 
                    selectedList[transactionList.options[i].text] = transactionList.options[i].value;                
                }
            }

        }
        
        if (jQuery.isEmptyObject(selectedList) && typeof transactionId == 'undefined') {            
            isTextFieldContainValue('transaction_id', 'Please enter transaction name');
            isTextFieldContainValue('transaction_description', 'Please enter description');
            $('#transaction_error_msg').show();
            $('#transaction_error_msg').html('Or Select default transactions');            
        }
        
        if (getErrorMessageArray().length) {
            displayErrorMessages();
        } else {

            if (typeof selectedList != 'undefined') {
                data = {'job_function_id': rhjf_id, 'selected_transaction_list': selectedList, 'rulebook_id': rulebook_id};
            } else {
                data = {'job_function_id': rhjf_id, 'transaction_id': transactionId, 'transaction_description': transactionDescription, 'rulebook_id': rulebook_id};
            }

            ajaxAddTransactionResponse = postDataAjax(add_transaction_url, data);
            ajaxAddTransactionResponse.done(function(response) {
                if (response.redirect){
                    location.href = response.redirect;
                }
                if ('success' == response.status) {
                    //location.reload();

                     t_data = {'riskHasJobFunction_id': rhjf_id, 'rulebook_id' : rulebook_id};                        
                     ajaxTransactionListResponse = postDataAjax(on_select_transaction_url, t_data);
                     ajaxTransactionListResponse.done(function(t_response) {

                         if (t_response.length > 0) {
                            $(".trans-list").empty();
                             for (j = 0; j < t_response.length; j++) {
                                 t_id = t_response[j].id;
                                 //$("#transaction_id_" + t_id).show();
                                 if (t_response[j].is_default_transaction == 1) {
                                    if ($("#job_function_id_"+rhjf_id).hasClass('is-default')) {
                                        $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                    } else {
                                        $(".trans-list").append("<li class='transaction-listing is-default' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='action-icon-sm text-replace sprite-sm-remove un-assign' data-toggle='tooltip' data-placement='top' title='Remove'>Remove</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                    }
                                 } else {
                                    $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><span data-toggle='modal' data-acp-type='Transaction' data-target='#edit-acp-data' class='pull-left acp-risk-edit'><a href='javascript:void(0)' class='text-replace sprite-edit action-icon-sm mar-r10' data-toggle='tooltip' data-placement='top' title='Edit'>Edit</a></span><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='text-replace sprite-delete-sm action-icon-sm un-assign' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                 }
                                 
                                 if (j == 0) {
                                    prevJFHTID = "transaction_id_"+t_response[j].id;
                                    $("#transaction_id_"+t_response[j].id).addClass('open');
                                 }
                             }
                         }
                     });
                      $("#transaction_id").val('');
                      $("#transaction_description").val('');
                      $("#transaction_description").val('');
                      $("#default_transaction_list").val('');
                      $("#transaction_error_msg").html('');
                      $("#add-transaction").modal('hide');
                } else {                    
                    if ($('#default_transaction_list').prop("disabled")) {                        
                        $('#transaction_idErrorClass').addClass('error');                    
                        $('#transaction_idErrorMsg').html(response.transactionName);
                    } else {
                        $('#transaction_error_msg').show();
                        $('#transaction_error_msg').html(response.transactionName);
                    }  
                }
            });
        }
        displayEmptyIcons();
        defaultTransactionList();
        $('#default_transaction_list').multiselect('rebuild');
    });
    
    $('.un-assign').livequery('click', function() {        
        
        var confirmBoxComment = 'Do you want to delete?';
        if ($(this).hasClass('is-default')) {
            var confirmBoxComment = 'Do you want to remove?';
        } else {
            var confirmBoxComment = 'Do you want to delete?';
        }
    	var rulebookHasRisk_id = $(this).attr('rulebookHasRisk_id');
    	var prevRuleBookHasRiskId = $("#"+rulebookHasRisk_id).prev('li').attr('id');
    	var nextRuleBookHasRiskId = $("#"+rulebookHasRisk_id).next('li').attr('id');

    	var riskHasJobFunction_id = $(this).attr('riskHasJobFunction_id');
    	var prevJobFunctionId = $("#job_function_id_"+riskHasJobFunction_id).prev('li').attr('id');
    	var nextJobFunctionId = $("#job_function_id_"+riskHasJobFunction_id).next('li').attr('id');

    	var jobFunctionHasTransaction_id = $(this).attr('jobFunctionHasTransaction_id');
    	var prevjobFunctionHasTransactionId = $("#transaction_id_"+jobFunctionHasTransaction_id).prev('li').attr('id');
    	var nextjobFunctionHasTransactionId = $("#transaction_id_"+jobFunctionHasTransaction_id).next('li').attr('id');   	 
        

        if (typeof rulebookHasRisk_id  != 'undefined') {
            var url = delete_risk_url;            
            del_data = {'rulebookHasRisk_id' : rulebookHasRisk_id, 'rulebook_id' : rulebook_id};
            var id_name = rulebookHasRisk_id;
        } else if (typeof riskHasJobFunction_id  != 'undefined') {
            var url = delete_job_function_url;            
            var id_name = 'job_function_id_'+riskHasJobFunction_id;
            //var job_function_val = $("#"+id_name).children('a').text();                        
            if ($("#job_function_id_"+riskHasJobFunction_id).hasClass('is-default')) {
                confirmBoxComment = 'Do you want to remove?';
            }
            del_data = {'riskHasJobFunction_id' : riskHasJobFunction_id, 'rulebook_id' : rulebook_id};            
        } else if (typeof jobFunctionHasTransaction_id != 'undefined') {            
            var url = delete_transaction_url;
            var id_name = 'transaction_id_'+jobFunctionHasTransaction_id;
            //var transaction_val = $("#"+id_name).children('a').text();            
            if ($("#transaction_id_"+jobFunctionHasTransaction_id).hasClass('is-default')) {
                confirmBoxComment = 'Do you want to remove?';
            }
            del_data = {'jobFunctionHasTransaction_id' : jobFunctionHasTransaction_id, 'rulebook_id' : rulebook_id};            
        }        

        $("<div id='custom_dialog' title='Confirmation'></div>").html(confirmBoxComment).dialog({
            resizable: false,
            modal: true,
            buttons: {
                "Yes": function() {
                    var close = $(this);                                        
                    ajaxDeleteRiskResponse = postDataAjax(url, del_data);
                    ajaxDeleteRiskResponse.done(function(response) {                            
                        if (response.redirect){
                            location.href = response.redirect;
                        }
                        if ('success' == response.status) {
                            if (typeof rulebookHasRisk_id  != 'undefined') {
                                $('.job-func-list').empty();
                                $('.trans-list').empty();
                                $("#"+rulebookHasRisk_id).remove();
                            	if  (typeof prevRuleBookHasRiskId != 'undefined') {
                            	   var i;
                              	   var j;   
                              	   var count;
                              	   $("#"+prevRuleBookHasRiskId).addClass('open');
                                   rhr_id = prevRuleBookHasRiskId;
                              	   prevRHRID = prevRuleBookHasRiskId;                                   
                              	   jf_data = {'rulebookHasRisk_id': prevRuleBookHasRiskId, 'rulebook_id' : rulebook_id};
                              	   ajaxJobFunctionListResponse = postDataAjax(on_select_job_function_url, jf_data);
                              	   ajaxJobFunctionListResponse.done(function(jf_response) {        
                              	        if(jf_response.length > 0){      
                                            $(".job-func-list").empty();
                              	            for(i = 0; i < jf_response.length; i++){
                              	            	if (jf_response[i].is_default_job_function == 1) {
                                                    $(".job-func-list").append("<li class='job-function-listing is-default' id='job_function_id_"+jf_response[i].id+"'><div class='pull-right'><a href='javascript:void(0)' riskHasJobFunction_id='"+jf_response[i].id+"' class='action-icon-sm text-replace sprite-sm-remove un-assign' data-toggle='tooltip' data-placement='top' title='Remove'>Remove</a></div><a href='javascript:void(0)' class='cr-title'>"+jf_response[i].sap_job_function_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+jf_response[i].description+"</p></li>")
                                                } else {
                                                    $(".job-func-list").append("<li class='job-function-listing' id='job_function_id_"+jf_response[i].id+"'><div class='pull-right'><span data-toggle='modal' data-acp-type='Job function' data-target='#edit-acp-data' class='pull-left acp-risk-edit'><a href='javascript:void(0)' class='text-replace sprite-edit action-icon-sm mar-r10' data-toggle='tooltip' data-placement='top' title='Edit'>Edit</a></span><a href='javascript:void(0)' riskHasJobFunction_id='"+jf_response[i].id+"' class='text-replace sprite-delete-sm action-icon-sm un-assign' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</a></div><a href='javascript:void(0)' class='cr-title'>"+jf_response[i].sap_job_function_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+jf_response[i].description+"</p></li>")
                                                }
                              	            	
                              	                jf_id = jf_response[i].id;
                              	            	if (i == 0) {
                                                    $("#job_function_id_"+jf_response[i].id).addClass('open');
                                                    if ($("#job_function_id_"+jf_response[i].id).hasClass('is-default')) {
                                                        $("#add_transaction_icon").remove();
                                                    } else {            
                                                        if ($('#add_transaction_icon').length == 0) {
                                                            $(".addTransaction").append('<a href="javascript:void(0)" class="text-replace sprite-add action-icon" data-toggle="tooltip" data-placement="left" title="Add Transactions" id="add_transaction_icon">Add</a>');
                                                        }
                                                    }
                                                    prevRHJFID = "job_function_id_"+jf_response[i].id;
                                                    rhjf_id = jf_id;
                              	                    t_data = {'riskHasJobFunction_id': jf_id, 'rulebook_id' : rulebook_id};                        
                              	                    ajaxTransactionListResponse = postDataAjax(on_select_transaction_url, t_data);
                              	                    ajaxTransactionListResponse.done(function(t_response) {
                              	                    	
                              	                        if (t_response.length > 0) {
                                                            $(".trans-list").empty();
                              	                            for (j = 0; j < t_response.length; j++) {
                              	                                t_id = t_response[j].id;
                              	                                //$("#transaction_id_" + t_id).show();
                                                                if (t_response[j].is_default_transaction == 1) {
                                                                    if ($("#job_function_id_"+jf_id).hasClass('is-default')) {
                                                                        $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                                                    } else {
                                                                        $(".trans-list").append("<li class='transaction-listing is-default' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='action-icon-sm text-replace sprite-sm-remove un-assign' data-toggle='tooltip' data-placement='top' title='Remove'>Remove</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                                                    }
                                                                } else {
                                                                    $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><span data-toggle='modal' data-acp-type='Transaction' data-target='#edit-acp-data' class='pull-left acp-risk-edit'><a href='javascript:void(0)' class='text-replace sprite-edit action-icon-sm mar-r10' data-toggle='tooltip' data-placement='top' title='Edit'>Edit</a></span><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='text-replace sprite-delete-sm action-icon-sm un-assign' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                                                }
                              	                                if (j == 0) {
                                                                    prevJFHTID = "transaction_id_"+t_response[j].id;
                                                                    $("#transaction_id_"+t_response[j].id).addClass('open');
                              	                                }
                              	                            }
                              	                        }
                              	                    });
                              	            	}                              	                
                              	            }
                              	        }
                              	    });                                    
                            	} else if (typeof nextRuleBookHasRiskId != 'undefined') {
                            	   var i;
                               	   var j;   
                               	   var count;
                               	   $("#"+rulebookHasRisk_id).hide();
                               	   $("#"+nextRuleBookHasRiskId).addClass('open');
                               	   jf_data = {'rulebookHasRisk_id': nextRuleBookHasRiskId, 'rulebook_id' : rulebook_id};
                                   rhr_id = nextRuleBookHasRiskId;
                                   prevRHRID = nextRuleBookHasRiskId;
                               	   ajaxJobFunctionListResponse = postDataAjax(on_select_job_function_url, jf_data);
                               	   ajaxJobFunctionListResponse.done(function(jf_response) {        
                               	        if(jf_response.length > 0){      
                               	        	$(".job-func-list").empty();
                               	            for(i = 0; i < jf_response.length; i++){
                               	            	if (jf_response[i].is_default_job_function == 1) {
                                                    $(".job-func-list").append("<li class='job-function-listing is-default' id='job_function_id_"+jf_response[i].id+"'><div class='pull-right'><a href='javascript:void(0)' riskHasJobFunction_id='"+jf_response[i].id+"' class='action-icon-sm text-replace sprite-sm-remove un-assign' data-toggle='tooltip' data-placement='top' title='Remove'>Remove</a></div><a href='javascript:void(0)' class='cr-title'>"+jf_response[i].sap_job_function_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+jf_response[i].description+"</p></li>")
                                                } else {
                                                    $(".job-func-list").append("<li class='job-function-listing' id='job_function_id_"+jf_response[i].id+"'><div class='pull-right'><span data-toggle='modal' data-acp-type='Job function' data-target='#edit-acp-data' class='pull-left acp-risk-edit'><a href='javascript:void(0)' class='text-replace sprite-edit action-icon-sm mar-r10' data-toggle='tooltip' data-placement='top' title='Edit'>Edit</a></span><a href='javascript:void(0)' riskHasJobFunction_id='"+jf_response[i].id+"' class='text-replace sprite-delete-sm action-icon-sm un-assign' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</a></div><a href='javascript:void(0)' class='cr-title'>"+jf_response[i].sap_job_function_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+jf_response[i].description+"</p></li>")
                                                }
                               	                jf_id = jf_response[i].id;
                               	            	if (i == 0) {
                                                    $("#job_function_id_"+jf_response[i].id).addClass('open');
                                                    if ($("#job_function_id_"+jf_response[i].id).hasClass('is-default')) {
                                                        $("#add_transaction_icon").remove();
                                                    } else {            
                                                        if ($('#add_transaction_icon').length == 0) {
                                                            $(".addTransaction").append('<a href="javascript:void(0)" class="text-replace sprite-add action-icon" data-toggle="tooltip" data-placement="left" title="Add Transactions" id="add_transaction_icon">Add</a>');
                                                        }
                                                    }
                                                    prevRHJFID = "job_function_id_"+jf_response[i].id;
                                                    rhjf_id = jf_id;
                               	                    t_data = {'riskHasJobFunction_id': jf_id, 'rulebook_id' : rulebook_id};                        
                               	                    ajaxTransactionListResponse = postDataAjax(on_select_transaction_url, t_data);
                               	                    ajaxTransactionListResponse.done(function(t_response) {
                               	                    	
                               	                        if (t_response.length > 0) {
                                                            $(".trans-list").empty();
                               	                            for (j = 0; j < t_response.length; j++) {
                               	                                t_id = t_response[j].id;
                               	                                //$("#transaction_id_" + t_id).show();
                                                                if (t_response[j].is_default_transaction == 1) {
                                                                    if ($("#job_function_id_"+jf_id).hasClass('is-default')) {
                                                                        $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                                                    } else {
                                                                        $(".trans-list").append("<li class='transaction-listing is-default' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='action-icon-sm text-replace sprite-sm-remove un-assign' data-toggle='tooltip' data-placement='top' title='Remove'>Remove</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                                                    }                                                                    
                                                                } else {
                                                                    $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><span data-toggle='modal' data-acp-type='Transaction' data-target='#edit-acp-data' class='pull-left acp-risk-edit'><a href='javascript:void(0)' class='text-replace sprite-edit action-icon-sm mar-r10' data-toggle='tooltip' data-placement='top' title='Edit'>Edit</a></span><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='text-replace sprite-delete-sm action-icon-sm un-assign' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                                                }
                               	                                if (j == 0) {
                                                                    prevJFHTID = "transaction_id_"+t_response[j].id;
                                                                    $("#transaction_id_"+t_response[j].id).addClass('open');
                               	                                }
                               	                            }
                               	                        } 
                               	                    });
                               	            	}
                               	               
                               	            }
                               	        }
                               	    });
                            	} else { 
                            		$(".job-func-list").empty();
                            		$(".trans-list").empty();                                        
                            	}                            	
                            } else if (typeof riskHasJobFunction_id  != 'undefined') { 
                                $('.trans-list').empty();
                                $("#job_function_id_"+riskHasJobFunction_id).remove();
                                 
                                if (typeof prevJobFunctionId  != 'undefined') {
                                    $("#"+prevJobFunctionId).addClass('open');
                                    if ($("#"+prevJobFunctionId).hasClass('is-default')) {
                                        $("#add_transaction_icon").remove();
                                    } else {            
                                        if ($('#add_transaction_icon').length == 0) {
                                            $(".addTransaction").append('<a href="javascript:void(0)" class="text-replace sprite-add action-icon" data-toggle="tooltip" data-placement="left" title="Add Transactions" id="add_transaction_icon">Add</a>');
                                        }
                                    }                                    
                                    
                                    prevRHJFID = prevJobFunctionId;
                                	
                                    var temp = prevJobFunctionId.split("_");
                                    rhjf_id = temp[3];
                                    data = {'riskHasJobFunction_id': temp[3], 'rulebook_id' : rulebook_id};
                                    ajaxTransactionListResponse = postDataAjax(on_select_transaction_url, data);
                                    ajaxTransactionListResponse.done(function(t_response) {
                                        if (t_response.length > 0) {
                                           
                                            $(".trans-list").empty();
                                            for (j = 0; j < t_response.length; j++) {
                                                t_id = t_response[j].id;
                                                if (t_response[j].is_default_transaction == 1) {
                                                    if ($("#job_function_id_"+temp[3]).hasClass('is-default')) {
                                                        $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>");  
                                                    } else {
                                                        $(".trans-list").append("<li class='transaction-listing is-default' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='action-icon-sm text-replace sprite-sm-remove un-assign' data-toggle='tooltip' data-placement='top' title='Remove'>Remove</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>");  
                                                    }                                                    
                                                } else {
                                                    $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><span data-toggle='modal' data-acp-type='Transaction' data-target='#edit-acp-data' class='pull-left acp-risk-edit'><a href='javascript:void(0)' class='text-replace sprite-edit action-icon-sm mar-r10' data-toggle='tooltip' data-placement='top' title='Edit'>Edit</a></span><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='text-replace sprite-delete-sm action-icon-sm un-assign' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")  
                                                }
                                                if (j == 0) {
                                                	prevJFHTID = "transaction_id_"+t_response[j].id;
                                                	$("#transaction_id_"+t_response[j].id).addClass('open');
                                                }
                                            }
                                        } 
                                    });
                                } else if (typeof nextJobFunctionId != 'undefined') {
                                    $("#"+nextJobFunctionId).addClass('open');
                                    if ($("#"+nextJobFunctionId).hasClass('is-default')) {
                                        $("#add_transaction_icon").remove();
                                    } else {            
                                        if ($('#add_transaction_icon').length == 0) {
                                            $(".addTransaction").append('<a href="javascript:void(0)" class="text-replace sprite-add action-icon" data-toggle="tooltip" data-placement="left" title="Add Transactions" id="add_transaction_icon">Add</a>');
                                        }
                                    }                                    
                                    prevRHJFID =  nextJobFunctionId;

                                    var temp = nextJobFunctionId.split("_");
                                    rhjf_id = temp[3];
                                    data = {'riskHasJobFunction_id': temp[3], 'rulebook_id' : rulebook_id};
                                    ajaxTransactionListResponse = postDataAjax(on_select_transaction_url, data);
                                    ajaxTransactionListResponse.done(function(t_response) {
                                        if (t_response.length > 0) {
                                           
                                        	$(".trans-list").empty();
                                        	for (j = 0; j < t_response.length; j++) {
                                                t_id = t_response[j].id;
                                                if (t_response[j].is_default_transaction == 1) {
                                                    if ($("#job_function_id_"+temp[3]).hasClass('is-default')) {
                                                        $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                                    } else {
                                                        $(".trans-list").append("<li class='transaction-listing is-default' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='action-icon-sm text-replace sprite-sm-remove un-assign' data-toggle='tooltip' data-placement='top' title='Remove'>Remove</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                                    }
                                                } else {
                                                    $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><span data-toggle='modal' data-acp-type='Transaction' data-target='#edit-acp-data' class='pull-left acp-risk-edit'><a href='javascript:void(0)' class='text-replace sprite-edit action-icon-sm mar-r10' data-toggle='tooltip' data-placement='top' title='Edit'>Edit</a></span><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='text-replace sprite-delete-sm action-icon-sm un-assign' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                                }
                                                if (j == 0) {
                                                	prevJFHTID = "transaction_id_"+t_response[j].id;
                                                	$("#transaction_id_"+t_response[j].id).addClass('open');
                                                }
                                            }
                                        } 
                                    });
                                	
                                } else {
                                	$(".trans-list").empty();                                        
                                	//location.reload();
                                }                                
                            } else if (typeof jobFunctionHasTransaction_id != 'undefined') { 
                            	 $("#transaction_id_"+jobFunctionHasTransaction_id).remove();
                            	 if (typeof prevjobFunctionHasTransactionId  != 'undefined') {
                            		 prevJFHTID = prevjobFunctionHasTransactionId;
                            		 $("#"+prevjobFunctionHasTransactionId).addClass('open');
                            	 } else if (typeof nextjobFunctionHasTransactionId  != 'undefined') { 
                            		 prevJFHTID = nextjobFunctionHasTransactionId;
                            		 $("#transaction_id_"+jobFunctionHasTransaction_id).hide();
                            		 $("#"+nextjobFunctionHasTransactionId).addClass('open');
                            	 } else { 
                            		 $(".trans-list").empty();                                         
                            	 }                            	 
                            }                            
                            close.dialog("close");
                            displayEmptyIcons();
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
    displayEmptyIcons();    
});

function displayEmptyIcons(){    
    $('.no_job_functions').hide();
    $('.no_transactions').hide();
    $('.job-func-list').show();
    $('.trans-list').show();
    if($('.job-func-list li').length == 0) {        
        $('.job-func-list').hide();
        $('.no_job_functions').show();
    }
    if( $('.trans-list li').length == 0) {        
        $('.trans-list').hide();
        $('.no_transactions').show();
    }
}


function ajaxCalls(data){   
   var i;
   var j;   
   var count;
   jf_data = {'rulebookHasRisk_id': data, 'rulebook_id' : rulebook_id};
   ajaxJobFunctionListResponse = postDataAjax(on_select_job_function_url, jf_data);
   ajaxJobFunctionListResponse.done(function(jf_response) {        
        if (jf_response.redirect){
            location.href = jf_response.redirect;
        }
        if(jf_response.length > 0){      
            $(".job-func-list").empty();
            for(i = 0; i < jf_response.length; i++){                
            	if (jf_response[i].is_default_job_function == 1) {
                    $(".job-func-list").append("<li class='job-function-listing is-default' id='job_function_id_"+jf_response[i].id+"'><div class='pull-right'><a href='javascript:void(0)' riskHasJobFunction_id='"+jf_response[i].id+"' class='action-icon-sm text-replace sprite-sm-remove un-assign' data-toggle='tooltip' data-placement='top' title='Remove'>Remove</a></div><a href='javascript:void(0)' class='cr-title'>"+jf_response[i].sap_job_function_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+jf_response[i].description+"</p></li>");                    
                } else {                    
                    $(".job-func-list").append("<li class='job-function-listing' id='job_function_id_"+jf_response[i].id+"'><div class='pull-right'><span data-toggle='modal' data-acp-type='Job function' data-target='#edit-acp-data' class='pull-left acp-risk-edit'><a href='javascript:void(0)' class='text-replace sprite-edit action-icon-sm mar-r10' data-toggle='tooltip' data-placement='top' title='Edit'>Edit</a></span><a href='javascript:void(0)' riskHasJobFunction_id='"+jf_response[i].id+"' class='text-replace sprite-delete-sm action-icon-sm un-assign' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</a></div><a href='javascript:void(0)' class='cr-title'>"+jf_response[i].sap_job_function_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+jf_response[i].description+"</p></li>");
                    $('#add_transaction_icon').append();
                }
                jf_id = jf_response[i].id;
            	if (i == 0) {
                    if ($("#job_function_id_"+jf_response[i].id).hasClass('is-default')) {
                        $("#add_transaction_icon").remove();
                    } else {
                        if ($('#add_transaction_icon').length == 0) {
                            $(".addTransaction").append('<a href="javascript:void(0)" class="text-replace sprite-add action-icon" data-toggle="tooltip" data-placement="left" title="Add Transactions" id="add_transaction_icon">Add</a>');
                        }
                    }
                    $("#job_function_id_"+jf_response[i].id).addClass('open');
                    prevRHJFID = "job_function_id_"+jf_response[i].id;                        
                    
                    rhjf_id = jf_id;
                    t_data = {'riskHasJobFunction_id': jf_id, 'rulebook_id' : rulebook_id};                        
                    ajaxTransactionListResponse = postDataAjax(on_select_transaction_url, t_data);
                    ajaxTransactionListResponse.done(function(t_response) {
                    	
                        if (t_response.length > 0) {
                            $(".trans-list").empty();
                            for (j = 0; j < t_response.length; j++) {
                                t_id = t_response[j].id;
                                //$("#transaction_id_" + t_id).show();
                                if (t_response[j].is_default_transaction == 1) {
                                    if ($("#job_function_id_"+jf_id).hasClass('is-default')) {                                        
                                        $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                    } else {
                                        $(".trans-list").append("<li class='transaction-listing is-default' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='action-icon-sm text-replace sprite-sm-remove un-assign' data-toggle='tooltip' data-placement='top' title='Remove'>Remove</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                    }
                                } else {
                                    $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><span data-toggle='modal' data-acp-type='Transaction' data-target='#edit-acp-data' class='pull-left acp-risk-edit'><a href='javascript:void(0)' class='text-replace sprite-edit action-icon-sm mar-r10' data-toggle='tooltip' data-placement='top' title='Edit'>Edit</a></span><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='text-replace sprite-delete-sm action-icon-sm un-assign' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                }
                                if (j == 0) {
                                	prevJFHTID = "transaction_id_"+t_response[j].id;
                                	$("#transaction_id_"+t_response[j].id).addClass('open');
                                }
                            }
                        }
                    });
            	}
               
                //$("#job_function_id_" + jf_id).show();

                /*if (i == 0) {
                    rhjf_id = jf_id;
                    t_data = {'riskHasJobFunction_id': jf_id, 'rulebook_id' : rulebook_id};                        
                    ajaxTransactionListResponse = postDataAjax(on_select_transaction_url, t_data);
                    ajaxTransactionListResponse.done(function(t_response) {
                        if (t_response.length > 0) {
                            for (j = 0; j < t_response.length; j++) {
                                t_id = t_response[j].id;
                                $("#transaction_id_" + t_id).show();
                            }
                        }
                    });
                }*/
            }
        }
    });
    displayEmptyIcons();
}

function jobFunctionDropdownInitialization() {
   $('#default_job_function_list').multiselect({            
        nonSelectedText: 'Select Job Functions',
        maxHeight: 200,
        inheritClass: false,
        buttonClass: 'btn btn-csel',
        optionLabel: function(element) {
            return $(element).html() + ' (' + $(element).attr('description') + ') ' + $(element).attr('is_default');
        }
    });  
}

function transactionDropdownInitialization() {
    $('#default_transaction_list').multiselect({
        nonSelectedText: 'Select Transactions',
        maxHeight: 200,
        inheritClass: false,
        buttonClass: 'btn btn-csel',
        optionLabel: function(element) {
            return $(element).html() + ' (' + $(element).attr('description') + ') ' + $(element).attr('is_default');
        }
    }); 
}


function defaultJobFunctionList() {
    $("#default_job_function_list").empty();
    ajaxDefaultJobFunctionListResponse = postDataAjax(default_job_function_list_url);
    ajaxDefaultJobFunctionListResponse.done(function(response) {                
        for(i = 0;i < response.length; i++){            
            $("#default_job_function_list").append("<option is_default='"+response[i].is_default+"' description='"+response[i].description+"' value='"+response[i].sap_job_function_id+"'>"+response[i].sap_job_function_id+"</option>")
        }        
    });    
}

function defaultTransactionList() {
    $("#default_transaction_list").empty();
    ajaxDefaultTransactionListResponse = postDataAjax(default_transaction_list_url);
    ajaxDefaultTransactionListResponse.done(function(response) {                
        for(i = 0;i < response.length; i++){            
            $("#default_transaction_list").append("<option is_default='"+response[i].is_default+"' description='"+response[i].description+"' value='"+response[i].sap_transaction_id+"'>"+response[i].sap_transaction_id+"</option>")
        }        
    });    
}

/* Display edit model box */
$('.acp-risk-edit').livequery('click', function(e) {        
    var type = $(this).attr('data-acp-type');
    var risk_id = $(this).closest('li').attr('id');
    var sap_risk_id = $(this).closest('li').children('a').html();
    var desc = $(this).closest('li').children('p').html();  
    $('.form-control-anim').addClass('form-control-selected');
    $('.form-group').removeClass('error');

    if (type == 'Rulebook') {
        var sap_risk_id = $('#rulebook_name').text();
        var desc = $('#rulebook_description').text();
    } 
    
    $('.acp-label-id').html(type + ' Id');
    $('.acp-label-desc').html('Description');        
    $('#edit_acp_id_hidden').val(risk_id);
    $('#edit_acp_type_hidden').val(type);
    $('#edit-acp-data #myModalLabel').html('Edit ' + type);
    $('#edit_sap_id').val(sap_risk_id);
    $('#edit_sap_description').val(desc);

});

/* Edit model box */
$('#edit_acp_data_save').livequery('click', function(e) {    
    var type = $('#edit_acp_type_hidden').val();    
    clearErrorMessages('error');
    isTextFieldContainValue('edit_sap_id', 'Please enter '+type+' Id');
    isTextFieldContainValue('edit_sap_description', 'Please enter '+type+' description');
  
    if (getErrorMessageArray().length) {
        displayErrorMessages();
        return false;
    }
    
    var db_id = $('#edit_acp_id_hidden').val();
    var sap_id = $('#edit_sap_id').val();
    var sap_desc = $('#edit_sap_description').val();   
    var rule_book_id = $('#rule_book_id_hidden').val();   
    var edit_val = $("#"+db_id).children('a').text();
    
    var url;
    if ('Risk' == type) {
        url = edit_risk_url;
    } else if('Job function' == type) {
        url = edit_job_function_url;
    } else if('Transaction' == type) {
        url = edit_transaction_url;
    } else if('Rulebook' == type) {
        url = edit_rulebook_url;
    }
    
    data = {'db_id': db_id, 'sap_id': sap_id,'sap_desc': sap_desc, 'rule_book_id': rule_book_id, 'risk_id': rhr_id, 'job_function_id': rhjf_id,'edit_val' : edit_val};
    ajaxDeleteCartItemResponse = postDataAjax(url, data);
    ajaxDeleteCartItemResponse.done(function(response) {
        if (response.redirect){
            location.href = response.redirect;
        }
        //$('#edit-acp-data').modal('hide');
        if ('success' == response.status) {
            $('#edit-acp-data').modal('hide');
            if ('Rulebook' == type) {
                $("#rulebook_name").html(sap_id);
                $("#rulebook_description").html(sap_desc);
            } else if ('Job function' == type){                
               var i;
               var j;   
               var count;

               jf_data = {'rulebookHasRisk_id': rhr_id, 'rulebook_id' : rulebook_id};
               ajaxJobFunctionListResponse = postDataAjax(on_select_job_function_url, jf_data);
               ajaxJobFunctionListResponse.done(function(jf_response) {                            
                    if(jf_response.length > 0){      
                        $(".job-func-list").empty();
                        for(i = 0; i < jf_response.length; i++){
                            if (jf_response[i].is_default_job_function == 1) {
                                $(".job-func-list").append("<li class='job-function-listing is-default' id='job_function_id_"+jf_response[i].id+"'><div class='pull-right'><a href='javascript:void(0)' riskHasJobFunction_id='"+jf_response[i].id+"' class='action-icon-sm text-replace sprite-sm-remove un-assign' data-toggle='tooltip' data-placement='top' title='Remove'>Remove</a></div><a href='javascript:void(0)' class='cr-title'>"+jf_response[i].sap_job_function_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+jf_response[i].description+"</p></li>")
                            } else {
                                $(".job-func-list").append("<li class='job-function-listing' id='job_function_id_"+jf_response[i].id+"'><div class='pull-right'><span data-toggle='modal' data-acp-type='Job function' data-target='#edit-acp-data' class='pull-left acp-risk-edit'><a href='javascript:void(0)' class='text-replace sprite-edit action-icon-sm mar-r10' data-toggle='tooltip' data-placement='top' title='Edit'>Edit</a></span><a href='javascript:void(0)' riskHasJobFunction_id='"+jf_response[i].id+"' class='text-replace sprite-delete-sm action-icon-sm un-assign' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</a></div><a href='javascript:void(0)' class='cr-title'>"+jf_response[i].sap_job_function_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+jf_response[i].description+"</p></li>")
                            }
                            jf_id = jf_response[i].id;
                            if (i == 0) {                                        
                                $("#job_function_id_"+jf_response[i].id).addClass('open');
                                prevRHJFID = "job_function_id_"+jf_response[i].id;
                                rhjf_id = jf_id;
                                data = {'riskHasJobFunction_id': rhjf_id, 'rulebook_id' : rulebook_id};
                                ajaxTransactionListResponse = postDataAjax(on_select_transaction_url, data);
                                ajaxTransactionListResponse.done(function(t_response) {
                                    if (t_response.length > 0) {
                                        $(".trans-list").empty();
                                        for (j = 0; j < t_response.length; j++) {
                                            t_id = t_response[j].id;                                            
                                            if (t_response[j].is_default_transaction == 1) {
                                                if ($("#job_function_id_"+rhjf_id).hasClass('is-default')) {
                                                    $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                                } else {
                                                    $(".trans-list").append("<li class='transaction-listing is-default' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='action-icon-sm text-replace sprite-sm-remove un-assign' data-toggle='tooltip' data-placement='top' title='Remove'>Remove</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                                }
                                            } else {
                                                $(".trans-list").append("<li class='transaction-listing' id='transaction_id_"+t_response[j].id+"'><div class='pull-right'><span data-toggle='modal' data-acp-type='Transaction' data-target='#edit-acp-data' class='pull-left acp-risk-edit'><a href='javascript:void(0)' class='text-replace sprite-edit action-icon-sm mar-r10' data-toggle='tooltip' data-placement='top' title='Edit'>Edit</a></span><a href='javascript:void(0)' jobFunctionHasTransaction_id='"+t_response[j].id+"' class='text-replace sprite-delete-sm action-icon-sm un-assign' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</a></div><a href='javascript:void(0)' class='cr-title'>"+t_response[j].sap_transaction_id+"</a><p class='child-content font-size-small mar-b0 mar-t0'>"+t_response[j].description+"</p></li>")
                                            }

                                            if (j == 0) {
                                                prevJFHTID = "transaction_id_"+t_response[j].id;
                                                $("#transaction_id_"+t_response[j].id).addClass('open');
                                            }
                                        }                                        
                                    } else {
                                        $('.trans-list li').hide();
                                    }
                                });
                            }

                        }
                    }
              });
              defaultJobFunctionList();
              $('#default_job_function_list').multiselect('rebuild');
            } else {
                $("#"+db_id).children('a').html(sap_id);
                $("#"+db_id).children('p').html(sap_desc);
                defaultTransactionList();
                $('#default_transaction_list').multiselect('rebuild');                
            }
            $('#edit_sap_id').val('');
            $('#edit_sap_description').val('');
            showAndHideDiv('.alert-success', response.message);
        } else {
            
            if (typeof response.jobFunctionName != 'undefined') {
                $('#edit_sap_idErrorClass').addClass('error');                    
                $('#edit_sap_idErrorMsg').html(response.jobFunctionName);
            } else if (typeof response.transactionName != 'undefined') {                
                $('#edit_sap_idErrorClass').addClass('error');                    
                $('#edit_sap_idErrorMsg').html(response.transactionName);
            } else {
                $('#edit_sap_idErrorClass').addClass('error');                    
                $('#edit_sap_idErrorMsg').html(response.message);
            }
            //showAndHideDiv('.alert-error', response.message);
        }
    });
    displayEmptyIcons();
});
