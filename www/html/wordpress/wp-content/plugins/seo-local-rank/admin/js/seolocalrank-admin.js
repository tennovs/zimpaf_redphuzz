/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Global Library of Theme colors for Javascript plug and play use  
var bgPrimary = '#4a89dc',
   bgPrimaryL = '#5d9cec',
   bgPrimaryLr = '#83aee7',
   bgPrimaryD = '#2e76d6',
   bgPrimaryDr = '#2567bd',
   bgSuccess = '#70ca63',
   bgSuccessL = '#87d37c',
   bgSuccessLr = '#9edc95',
   bgSuccessD = '#58c249',
   bgSuccessDr = '#49ae3b',
   bgInfo = '#3bafda',
   bgInfoL = '#4fc1e9',
   bgInfoLr = '#74c6e5',
   bgInfoD = '#27a0cc',
   bgInfoDr = '#2189b0',
   bgWarning = '#f6bb42',
   bgWarningL = '#ffce54',
   bgWarningLr = '#f9d283',
   bgWarningD = '#f4af22',
   bgWarningDr = '#d9950a',
   bgDanger = '#e9573f',
   bgDangerL = '#fc6e51',
   bgDangerLr = '#f08c7c',
   bgDangerD = '#e63c21',
   bgDangerDr = '#cd3117',
   bgAlert = '#967adc',
   bgAlertL = '#ac92ec',
   bgAlertLr = '#c0b0ea',
   bgAlertD = '#815fd5',
   bgAlertDr = '#6c44ce',
   bgSystem = '#37bc9b',
   bgSystemL = '#48cfad',
   bgSystemLr = '#65d2b7',
   bgSystemD = '#2fa285',
   bgSystemDr = '#288770',
   bgLight = '#f3f6f7',
   bgLightL = '#fdfefe',
   bgLightLr = '#ffffff',
   bgLightD = '#e9eef0',
   bgLightDr = '#dfe6e9',
   bgDark = '#3b3f4f',
   bgDarkL = '#424759',
   bgDarkLr = '#51566c',
   bgDarkD = '#2c2f3c',
   bgDarkDr = '#1e2028',
   bgBlack = '#283946',
   bgBlackL = '#2e4251',
   bgBlackLr = '#354a5b',
   bgBlackD = '#1c2730',
   bgBlackDr = '#0f161b';
   
var available_keywords;   



jQuery(document).ready( function(){
    
    
    /*
     *  format kw table
     */
   var slr_tooltip = 
    Popper.createPopper(jQuery(".slr-tooltip"), {
        placement: 'top',
    });

    jQuery('#kw-table-container table').dataTable({
        'paging': false,
        'info': false,
        'processing': true,
        'order': [[3, 'asc']],
        "columnDefs": [
            { "orderable": false, "targets": 6 }
        ],
       
    });
    
    jQuery('.slr_keyword_row').click(function() {
       var tk_id = jQuery(this).attr("id");
       jQuery(".selected").removeClass("selected");
       jQuery(this).addClass("selected");
       
      
       jQuery("#modal-kw-stats").modal("show");
       jQuery("#stats-loader").css("display","block");
       jQuery("#stats-data").css("opacity","0");
       
       var row = this;
       
       setTimeout(function(){
      
            showKeywordStats(row); 
        
        }, 500);
    });
    
   /*
    *  change state of keyword  
    */
    jQuery('.keyword_state').change(function() {
        var keywordId=jQuery(this).parent().parent().attr("id");
        if(keywordId > 0)
        {
            if(jQuery(this).is(":checked")) {
                activateKeyword(keywordId);
            }
            else
            {
                pauseKeyword(keywordId);
            }
        }
       
    });  
    
    
    
    /*
    *  delete keyword click 
    */
     jQuery('.slr_keyword_row').find('.tk-delete-button').click(function(e){
         
        e.stopPropagation(); 
        var row = jQuery(this).parent().parent();
        var keywordToDelete = jQuery(this).parent().parent().attr("id");
        jQuery.confirm({
            title: ajax_var.delete_keyword_title,
            content: ajax_var.delete_keyword_sure,
            buttons: {
                
                cancel: {
                    text: ajax_var.cancel,
                    action: function(){
                        
                    }
                    
                },
                confirm: {
                    text: ajax_var.confirm,
                    btnClass: 'btn-blue',
                    keys: ['enter', 'shift'],
                    action: function(){
                         
        
                        if(keywordToDelete > 0)
                        {
                            //console.log(keywordToDelete);
                            deleteKeyword(row, keywordToDelete);
                        }
                    }
                }
            }
        });
        
       
    });
    
    /*
    *  update keyword click 
    */
   /* jQuery("#update-keyword-button").on('click', function() {
        keywordToUpdate = jQuery(".slr_keyword_row.selected").attr("id");
        
        if(keywordToUpdate > 0)
        {
            updateKeyword(this, keywordToUpdate);
        }
    });*/
    
    jQuery('.slr_keyword_row').find('.tk-update-button').click(function(e){
       
       e.stopPropagation(); 
       keywordToUpdate = jQuery(this).parent().parent().attr("id");
       
        updateKeyword(this, keywordToUpdate);  
       
    });
    
    /*
    *  activate keyword click 
    */
   
   jQuery('.slr_keyword_row').find('.tk-activate-button').click(function(e){
       
       e.stopPropagation(); 
       var keywordId = jQuery(this).parent().parent().attr("id");
       var row = jQuery(this).parent().parent();
       

        
        activateKeyword(this, keywordId);
       
    });
   
   /*
    jQuery("#activate-tracking-button").on('click', function() {
        
        keywordToActivate = jQuery(".slr_keyword_row.selected").attr("id");
        
        if(keywordToActivate > 0)
        {
            activateKeyword(keywordToActivate);
        }
    });*/
   
    
    /*
     *  Select country in addKeyword
     */
    
    /*jQuery('.select_country').change(function(){
       var country = jQuery(this).val();
       if(country > 0)
       {
           jQuery(".select2-province").removeAttr("disabled");
           jQuery(".choose_country_error").css("display","none");
           jQuery(".select2-selection--multiple").css('background','#FFFFFF');
       }
       else
       {
           jQuery(".select2-province").attr("disabled","");
           jQuery(".choose_country_error").css("display","");
           jQuery(".select2-selection--multiple").css('background','#CCCCCC');
           
       }
       
    });*/
    
    /*
     * 
     * Select period of plans
     */
    
    jQuery('#show-monthly').click(function(){
        jQuery('#show-monthly').addClass("nav-tab-active slr-active");
        jQuery('#show-yearly').removeClass("nav-tab-active slr-active");
        jQuery('.slr-period-time-1').css("display","block");
        jQuery('.slr-period-time-12').css("display","none");
    });
    
    jQuery('#show-yearly').click(function(){
        jQuery('#show-monthly').removeClass("nav-tab-active slr-active");
        jQuery('#show-yearly').addClass("nav-tab-active slr-active");
        jQuery('.slr-period-time-1').css("display","none");
        jQuery('.slr-period-time-12').css("display","block");
    });
    
    /*
     *  Delete domain
     * 
     */
    
    jQuery('.domain_row').find('.submitdelete').click(function(){
        var project_domain_id = jQuery(this).parent().parent().attr("project_domain_id");
        if(project_domain_id > 0)
        {
            console.log(project_domain_id);
            deleteDomain(project_domain_id);
        }
    });
    
   
    
    jQuery('#tk-days').change(function() {
       var row = jQuery(".selected");
       var tk_id = jQuery(row).attr("id");
       
        jQuery("#stats-loader").css("display","block");
       jQuery("#stats-data").css("opacity","0");
       
       showKeywordStats(jQuery(row));
    });
    
    
    
   /********
    * 
    * @returns {Boolean}
    */
   jQuery("#delete-keyword-button").click(function(){
        jQuery.confirm({
            title: ajax_var.delete_keyword_title,
            content: ajax_var.delete_keyword_sure,
            buttons: {
                
                cancel: {
                    text: ajax_var.cancel,
                    action: function(){
                        
                    }
                    
                },
                confirm: {
                    text: ajax_var.confirm,
                    btnClass: 'btn-blue',
                    keys: ['enter', 'shift'],
                    action: function(){
                         keywordToDelete = jQuery(".slr_keyword_row.selected").attr("id");
        
                        if(keywordToDelete > 0)
                        {
                            deleteKeyword(jQuery(".slr_keyword_row.selected"), keywordToDelete);
                        }
                    }
                }
            }
        });
   });
   
   jQuery(".slr-subscribe-button").click(function(){
       var plan_period_id = jQuery(this).attr("period_id");
       getSaleId(plan_period_id);
   });
   
   jQuery('#desktop-screen-button').click(function(){
        if(!jQuery(this).hasClass("selected"))
        {
            jQuery(this).addClass("selected");
            jQuery('#mobile-screen-button').removeClass("selected");
            jQuery('#screen_type').val(1);
        }
    });
    
    jQuery('#mobile-screen-button').click(function(){
        if(!jQuery(this).hasClass("selected"))
        {
            jQuery(this).addClass("selected");
            jQuery('#desktop-screen-button').removeClass("selected");
            jQuery('#screen_type').val(2);
        }
    });
   
    
    
});

/*
 * 
 * send email to get API key
 */

function startSlr()
{
    jQuery('.slr-alert').css('display','none');
    jQuery('.email_error').css('visibility','hidden');
    jQuery('.terms_error').css('visibility','hidden');
    jQuery('.email_success').css('display', 'none');
    var email = jQuery("#email").val();
    
    
    if(!validateEmail(email))
    {
        jQuery('.email_error').css('visibility','');
        return false;
    }
    
    if(!jQuery("#terms").prop('checked'))
    {
        jQuery('.terms_error').css('visibility','');
        return false;
    }
    
    jQuery.ajax(
    {
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: ajax_var.url,
        data: 
        { 
            action: "slr_start", 
            email : email,
            _ajax_nonce: ajax_var.nonce, 

        },
        beforeSend:function()
        {
            jQuery('.loader').css('display','');
            jQuery('#get-api-key-form').css('display','none');
        },
        success:function(response)
        {
            console.log(response);
            try{
                if(response.ok) {
                    jQuery('.loader').css('display','none');
                    jQuery('#get-api-key-form').css('display','');
                    jQuery('#send-api-key').css('display','');
                    jQuery('.email_success').html(response.message);
                    jQuery('.email_success').css('display', '');


                }
                else {

                    jQuery('.email_error').css('visibility','');
                    jQuery('.email_error').html(response.error);
                    jQuery('.loader').css('display','none');
                    jQuery('#get-api-key-form').css('display','');
                }
            }
            catch(e){
                jQuery('.loader').css('display','none');
                jQuery('#get-api-key-form').css('display','');
                alert(ajax_var.general_error);
            }
        },
        error:function(resultado)
        {
            jQuery('.loader').css('display','none');
            jQuery('#get-api-key-form').css('display','');
            alert(ajax_var.general_error);
        },
        timeout:250000
    });
    
}

function cleanWpAlerts()
{
    jQuery('#wpbody-content').children().each(function () {
        if(jQuery(this).attr("id") === undefined || (!jQuery(this).attr("id").includes("slr") && !jQuery(this).attr("id").includes("mySidenav") && !jQuery(this).attr("id").includes("modal-kw-stats"))){
            jQuery(this).remove();
        }
    });
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}


/*
*  Activate automatic tracking  
*/

function activateKeyword(button, keywordId)
{
    jQuery.ajax({
        type : "post",
        url : ajax_var.url, 
        data : {
           action: "activate_keyword", 
           tracking_keyword_id : keywordId,
           _ajax_nonce: ajax_var.nonce, 
        },
        error: function(response){
           
           jQuery("#" + keywordId).find(".keyword_state").prop("checked", false);
        },
        success: function(response) {
            if(response.ok)
            {
                jQuery("#" + keywordId).attr("paused","0");
                jQuery("#" + keywordId).find(".pause-icon").remove();
              
                jQuery("#" + keywordId).attr("paused","0");
                jQuery("#" + keywordId).find(".pause-icon").remove();
                jQuery(button).remove();
              
                
            }
            else
            {
                jQuery.alert({
                    title: 'Error',
                    content: response.error,
                });
            }
        }
    });
}

/*
*  Pause automatic tracking  
*/

function pauseKeyword(keywordId)
{
    jQuery.ajax({
        
        type : "post",
        url : ajax_var.url, 
        data : {
           action: "pause_keyword", 
           tracking_keyword_id : keywordId,
           _ajax_nonce: ajax_var.nonce, 
        },
        error: function(response){
           
           jQuery("#" + keywordId).find(".keyword_state").prop("checked", true);
        },
        success: function(response) {
           if(!response.ok)
           {
               jQuery("#" + keywordId).find(".keyword_state").prop("checked", true);
               alert(response.error);
           }
           
        }
    });
}

/*
*  Delete keyword  
*/

function deleteKeyword(rowToDelete, keywordId)
{
    
   
    //available_keywords++;
    //$("#available_keywords_left").html(available_keywords);
    
    
    jQuery.ajax({
        beforeSend: function (qXHR, settings) {
            jQuery(document.body).css({'cursor' : 'wait'});
        },
        complete: function () {
            jQuery(document.body).css({'cursor' : 'default'});
         },
        type : "post",
        url : ajax_var.url,
        _ajax_nonce: ajax_var.nonce, 
        data : {
           action: "delete_keyword", 
           tracking_keyword_id : keywordId
        },
        error: function(response){
           
           alert(ajax_var.general_error);
        },
        success: function(response) {
           if(response.ok)
           {
               

                jQuery(rowToDelete).remove();
           }
           else
           {
               jQuery.alert({
                    title: 'Error',
                    content: response.error,
                });
               
           }
        }
    });
}

/*
 * add keyword
 */

function initSelectProvince()
{
    available_keywords = jQuery(".select2-province").data('availablekeywords');
    jQuery(".select2-province").select2({
        placeholder: jQuery(".select2-province").data('placeholder'),
        minimumInputLength: 3,
        maximumSelectionLength: available_keywords,
        templateResult: function(item) {
            if (! item.id) {
               return item.text;
            }

            var $item = jQuery('<div>' + item.name + '<br><em>' + item.google_search_location + '</em></div>');

            return $item;
        },
        ajax: {
            url: ajax_var.url,
            type: 'POST',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    
                    description: params.term,
                    action: "search_location", 
                    _ajax_nonce: ajax_var.nonce, 
                };
            },
            processResults: function (response) {
                var data = jQuery.map(response.data, function (obj) {
                    obj.text = obj.name;

                    return obj;
                });

                return {
                  results: data
                };
            }
        }
    });
    jQuery('.select2-province').on('select2:select', function () {
        available_keywords--;
        jQuery("#available_keywords_left").html(available_keywords);

        if (available_keywords === 0) {
            jQuery('.available_keywords_error').css('display', '');
        }
    });
    jQuery('.select2-province').on('select2:unselect', function () {
        available_keywords++;
        jQuery("#available_keywords_left").html(available_keywords);
        if (available_keywords > 0) {
            jQuery('.available_keywords_error').css('display', 'none');
        }
    });
}

function sendAddKeywordForm()
{
    jQuery('.keyword_error').css('display','none');
    jQuery('.cities_error').css('display','none');
    
    var projectDomainId = jQuery('#project-domain-id').val();
    var keyword = jQuery("#keyword").val();
    var cities = jQuery(".select2-province").val();
    var screen_type = jQuery('#screen_type').val();
    
    if(keyword == '')
    {
        jQuery('.keyword_error').css('display','');
        return false;
    }
    
    if(cities == null)
    {
        jQuery('.cities_error').css('display','');
        return false;
    }

    
    if(projectDomainId > 0)
    {
        
        jQuery.ajax(
        {
            async:true,
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            url: ajax_var.url,
            data: 
            { 
                action: "send_keyword", 
                project_domain_id : projectDomainId,
                keyword : keyword,
                cities : cities,
                screen_type : screen_type,
                _ajax_nonce: ajax_var.nonce, 
            },
            beforeSend:function()
            {
                jQuery('#loader-box').css('display','block');
                jQuery('#add-keyword-form-box').css('display','none');
            },
            success:function(response)
            {
                //jQuery("body").css("cursor","default");
                if(response.ok) {
                    location.reload();
                    //kw_list_url = jQuery('.nav-tab-wrapper').find('#kw_list').attr("href");
                    //location.href = kw_list_url;
                    
                }
                else {
                    jQuery.alert(response.error);
                    jQuery('#loader-box').css('display','none');
                    jQuery('#add-keyword-form-box').css('display','block');
                }
            },
            error:function(resultado)
            {
                $("body").css("cursor","default"); 
                jQuery.alert(ajax_var.general_error);
               
            },
            timeout:250000
        });
    }
    else
    {
        alert(ajax_var.general_error);
    }
    return false;
}

/*
*  update keyword  
*/

function updateKeyword(button, keywordId)
{
    var currentValue = jQuery('#' +  keywordId).find('.rank').html();
    var bestRank = jQuery('#' +  keywordId).find('.best-rank').html();
    var rankChange = jQuery('#' +  keywordId).find('.rank-change').html();
    var lastSearch = jQuery('#' +  keywordId).find('.last_search').html();
    
    
    jQuery('#' +  keywordId).find('.rank').html('<img class="position_loader" src="' + ajax_var.loader + '"/>');
    jQuery('#' +  keywordId).find('.rank-change').html('<img class="position_loader" src="' + ajax_var.loader + '"/>');
    jQuery('#' +  keywordId).find('.best-rank').html('<img class="position_loader" src="' + ajax_var.loader + '"/>');
    jQuery('#' +  keywordId).find('.last_search').html('<img class="position_loader" src="' + ajax_var.loader + '"/>');

   
    jQuery.ajax({
        beforeSend: function (qXHR, settings) {
            jQuery('#' +  keywordId).find('.rank_position').html('<img class="position_loader" src="' + ajax_var.loader + '"/>');
            //jQuery(button).css("display", "none");
        },
        
        type : "post",
        url : ajax_var.url, 
        data : {
           action: "update_keyword", 
           tracking_keyword_id : keywordId,
           _ajax_nonce: ajax_var.nonce, 
        },
        error: function(response){
           
            jQuery('#' +  keywordId).find('.rank').html(currentValue);
            jQuery('#' +  keywordId).find('.rank-change').html(rankChange);
            jQuery('#' +  keywordId).find('.best-rank').html(bestRank);
            jQuery('#' +  keywordId).find('.last_search').html(lastSearch);
        },
        success: function(response) {
           if(response.ok)
           {
               setTimeout(function(){
                    getKeywordUpdatedData(keywordId);
                 }, 7000);
               
                /*var rank = response.tracking_keyword.rank;
                if(rank == 0)
                {
                    rank = '+100';
                }
                
                
                jQuery('#' +  keywordId).find('.rank').html(rank);
                jQuery('#' +  keywordId).find('.best-rank').html(response.tracking_keyword.best_rank);
                //jQuery('#' +  keywordId).find('.rank-change').html(rankChange);
                
                jQuery('#' +  keywordId).find('.last_search').html(response.tracking_keyword.last_search);
                jQuery('#' +  keywordId).find('.last_search').css("font-weight","bold");
                jQuery('#' +  keywordId).find('.last_search').css("color","#4a89dc");
                
               
                var change_icon;
                var change = "";
                var change_color = "";
                if (response.tracking_keyword.better_rank == 0)
                {
                    
                    change = '<span><i class="fa fa-circle"></i></span>';
                }
                else {
                    if (response.tracking_keyword.better_rank == 1) {
                        change_icon = 'chevron-up';
                        change_color = 'slr-bg-success';
                    }
                    else {
                        change_icon = 'chevron-down';
                        change_color = 'slr-bg-danger';
                    }

                    change = '<span class="' + change_color + '"><i class="fa fa-' + change_icon + '"></i> ' + response.tracking_keyword.change + '</span>';
                }
                jQuery('#' +  keywordId).find('.rank-change').html(change);
                
               /* if(jQuery('#' +  keywordId).hasClass("selected"))
                {
                    showKeywordStats(jQuery('#' +  keywordId));
                }*/
                
           }
           else
           {
                alert(response.error);
                jQuery('#' +  keywordId).find('.rank').html(currentValue);
                jQuery('#' +  keywordId).find('.rank-change').html(rankChange);
                jQuery('#' +  keywordId).find('.best-rank').html(bestRank);
                jQuery('#' +  keywordId).find('.last_search').html(lastSearch);
           }
        }
    });
}

function getKeywordUpdatedData(tracking_keyword_id)
{
     jQuery.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: ajax_var.url,
        data: { 
           action: "get_update_keyword_data", 
           tracking_keyword_id : tracking_keyword_id,
           _ajax_nonce: ajax_var.nonce, 
        },
        beforeSend: function() {
             
           
        },
        success: function(response) {
            if (response.ok && response.tracking_keyword) {
                 
                var row = jQuery("#" + tracking_keyword_id);
                
                row.find(".last_search").html(ajax_var.today);
                row.find(".last_search").css("color", "#e9573f");
                row.find(".last_search").css("font-weight", "bold");
                
                var rank = response.tracking_keyword.rank;
                if(rank == 0)
                {
                    rank = '+100';
                }
                
                
                jQuery('#' +  tracking_keyword_id).find('.rank').html(rank);
                jQuery('#' +  tracking_keyword_id).find('.best-rank').html(response.tracking_keyword.best_rank);
            
            
                if(response.tracking_keyword.rank > 0)
                {
                    if(response.tracking_keyword.rank < 4)
                    {
                        row.find(".rank").html('<span class="slr-success"><strong>' + response.tracking_keyword.rank + '</strong></span>');
                    }
                    else if(response.tracking_keyword.rank < 11)
                    {
                        row.find(".rank").html('<span class="slr-warning"><strong>' + response.tracking_keyword.rank + '</strong></span>');
                    }
                    else
                    {
                        row.find(".rank").html('<span class="slr-danger"><strong>' + response.tracking_keyword.rank + '</strong></span>');                        
                    }
                }
                else
                {
                     row.find(".rank").html('<span class="slr-dark"><strong>+101</strong></span>');
                }
                if(response.tracking_keyword.cannibalitazion && row.find(".tk-keyword").has(".fa-bug").length == 0)
                {
                    row.find(".tk-keyword").append('<i class="fa fa-bug" ></i>');
                }
                else if(!response.tracking_keyword.cannibalitazion && row.find(".tk-keyword").has(".fa-bug").length > 0)
                {
                    row.find(".tk-keyword").find(".fa-bug").remove();
                }
                
                var change_icon;
                var change = "";
                var change_color = "";
                
               
                var rank = response.tracking_keyword.rank;
                var previous_rank = response.tracking_keyword.previous_rank;
                if(rank == 0)
                {
                    rank = 100;
                }
                if(previous_rank == 0)
                {
                    previous_rank = 100;
                }
                if(rank < previous_rank)
                {
                    row.find(".rank-change").html('<span class="slr-bg-success"><i class="fa fa-chevron-up"></i> ' + (previous_rank - rank) + '</span>');
                }
                else if(rank > previous_rank)
                {
                    row.find(".rank-change").html('<span class="slr-bg-danger"><i class="fa fa-chevron-down"></i> ' + (rank - previous_rank) + '</span>');
                }
                else
                {
                    row.find(".rank-change").html('<span><i class="fa fa-circle"></i></span>');
                }
            
            
            
            }
            else
            {
                
                setTimeout(function(){
                    getKeywordUpdatedData(tracking_keyword_id);
                 }, 7000);
            }
        },
        error: function(response){
           
            setTimeout(function(){
                    getKeywordUpdatedData(tracking_keyword_id);
                 }, 7000);
        },
    });
}

/**************
 * 
 *CHARTS
 * 
 */

function getChartSeries(data)
{
   var series = [];
   jQuery.each(data, function( index, value ) {
        if(parseInt(value.rank) > 0)
        {
            series.push(parseInt(value.rank));
        }else{
            series.push(101);
        }
    });
    return series.reverse();
}

function getChartIntervals(data)
{
    var intervals = [];
    jQuery.each(data, function( index, value ) {
        intervals.push(value.formatted_date2);
        /*if(value.days_ago > 0)
        {
            //intervals.push(value.formatted_date);
            intervals.push(value.days_ago + "d");
        }
        else
            intervals.push(ajax_var.today);*/
     });
    return intervals.reverse();
}

var highColors = [bgPrimary, bgWarning, bgInfo, bgAlert,
            bgDanger, bgSuccess, bgSystem, bgDark
        ];

// Color Library we used to grab a random color
var sparkColors = {
    "primary": [bgPrimary, bgPrimaryLr, bgPrimaryDr],
    "info": [bgInfo, bgInfoLr, bgInfoDr],
    "warning": [bgWarning, bgWarningLr, bgWarningDr],
    "success": [bgSuccess, bgSuccessLr, bgSuccessDr],
    "alert": [bgAlert, bgAlertLr, bgAlertDr]
};
        


function showChartsEChart(series, intervals, name)
{
    var myChart = echarts.init(document.getElementById('chart'));
    
    var option = {
            color: highColors,
            title: {
                text: ''
            },
            tooltip : {
                trigger: 'axis'
            },
            legend: {
                data:['Sales']
            },
            xAxis: {
                data: intervals
            },
            yAxis: {
                inverse: true,
                min: 1,
                max: 120,
            },
            series: [{
                name: name,
                data: series,
                type: 'line',
                
                
            }],
         toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
    },
            
           
        };

        // use configuration item and data specified to show chart
        myChart.setOption(option);
                 

    
}


function showTrends(data)
{
    
    var months = [];
    var values = [];
    
    
    jQuery.each(data, function( index, value ) {
        
        months.push(index);
        values.push(value);
       
    });

    months.reverse();
    values.reverse();
    
    var myChart = echarts.init(document.getElementById("google-trends"));
    var app = {};
    option = null;
    option = {
        xAxis: {
            type: 'category',
            data: months
        },
        yAxis: {
            type: 'value',
            show: false
        },
        series: [{
            data: values,
            type: 'bar',
        }],
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                label: {
                    backgroundColor: '#283b56'
                }
            }
        },
        
        color: "#4a89dc",
        backgroundColor: '#f9f9f9',
        height: '120px,'
    };
    
   
    myChart.setOption(option, true);
    
}



/*******************************************
 *  Add domain
 */

function isValidDomain(domain)
{
    if(domain.includes("http://")) {
        return false;
    }
    else if(domain.includes("https://")) {
        
        return false;
    }
    return true;
}

function sendAddDomainForm()
{
    jQuery('.domain_error').css('display','none');
    
    var domain = jQuery("#domain").val();
    
    if(domain == '')
    {
        jQuery('.domain_error').css('display','');
        return false;
    }
    
    if(isValidDomain(domain))
    {
    
        jQuery.ajax(
        {
            async:true,
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            url: ajax_var.url,
            data: 
            { 
                action: "send_domain", 
                name : domain,
                _ajax_nonce: ajax_var.nonce, 
                
            },
            beforeSend:function()
            {
                jQuery('#loader-box').css('display','block');
                jQuery('#add-domain-form-box').css('display','none');
            },
            success:function(response)
            {
                jQuery("body").css("cursor","default");
                if(response.ok) {
                    //location.reload();
                    domains_list_url = jQuery('.nav-tab-wrapper').find('#domains_list').attr("href");
                    location.href = domains_list_url;

                }
                else {
                    alert(response.error);
                    jQuery('#loader-box').css('display','none');
                    jQuery('#add-domain-form-box').css('display','block');
                }
            },
            error:function(resultado)
            {
                $("body").css("cursor","default");
                alert(ajax_var.general_error);
            },
            timeout:250000
        });
    }
    else
    {
        jQuery('.domain_error').css('display','');
    }
   
    return false;
}

/*
*  Delete domain  
*/

function deleteDomain(project_domain_id)
{
    
    
    if(!confirm(ajax_var.delete_domain_sure)){
        return false;
    }
    
    jQuery.ajax({
        beforeSend: function (qXHR, settings) {
            jQuery(document.body).css({'cursor' : 'wait'});
        },
        complete: function () {
            jQuery(document.body).css({'cursor' : 'default'});
         },
        type : "post",
        url : ajax_var.url, 
        data : {
           action: "delete_domain", 
           project_domain_id : project_domain_id,
           _ajax_nonce: ajax_var.nonce, 
        },
        error: function(response){
           
           alert(ajax_var.general_error);
        },
        success: function(response) {
           if(response.ok)
           {
               jQuery("#" + project_domain_id).remove();
           }
           else
           {
               alert(response.error);
           }
        }
    });
}

/*
 * 
 * Contact form
 */

function sendContactForm()
{
    jQuery('.subject_error').css('display','none');
    jQuery('.message_error').css('display','none');
    
    var subject = jQuery("#subject").val();
    var message = jQuery("#message").val();
    
    if(subject === '')
    {
        jQuery('.subject_error').css('display','');
        return false;
    }
    
    if(message === '')
    {
        jQuery('.message_error').css('display','');
        return false;
    }
    
    
    jQuery.ajax(
    {
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: ajax_var.url,
        data: 
        { 
            action: "slr_contact", 
            subject : subject,
            message : message,
            _ajax_nonce: ajax_var.nonce, 

        },
        beforeSend:function()
        {
            jQuery('#loader-box').css('display','block');
            jQuery('#contact-form-box').css('display','none');
        },
        success:function(response)
        {
            jQuery("body").css("cursor","default");
            if(response.ok) {
                
                jQuery('#loader-box').css('display','none');
                jQuery('#contact-form-box').css('display','none');
                jQuery('#contact-send-success-box').css('display','block');
                

            }
            else {
                alert(response.error);
                jQuery('#loader-box').css('display','none');
                jQuery('#contact-form-box').css('display','block');
            }
        },
        error:function(resultado)
        {
            $("body").css("cursor","default");
            alert(ajax_var.general_error);
        },
        timeout:250000
    });
   
    
   
    return false;
}

/*
 * 
 * Get Sale id
 */

function getSaleId(plan_period_id)
{
    
    //console.log(plan_period_id);
    location.href = jQuery("#" + plan_period_id).find(".slr-subscribe-button").attr("go-to");
    
    /* jQuery.ajax(
    {
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: ajax_var.url,
        data: 
        { 
            action: "slr_get_sale_id", 
            plan_period_id: plan_period_id,
            _ajax_nonce: ajax_var.nonce, 
           

        },
        beforeSend:function()
        {
            
            jQuery("body").css("cursor","wait");
        },
        success:function(response)
        {
            jQuery("body").css("cursor","default");
            if(response.ok) {
                
                //redirect to pay in seolocalrank.com
                console.log(jQuery("#" + plan_period_id).find(".slr-subscribe-button").attr("go-to"));
                location.href = jQuery("#" + plan_period_id).find(".slr-subscribe-button").attr("go-to");

            }
            else {
                alert(response.error);
                
            }
        },
        error:function(resultado)
        {
            $("body").css("cursor","default");
            alert(ajax_var.general_error);
        },
        timeout:250000
    });*/
   
    
   
    return false;
}
function updateGoogleDataTable(search_stats)
{
     var html = "";
     html += '<tr>';
     if(search_stats)
     {
        html += '<td class="slr-text-center">' + search_stats.volume + '</td>';
        html += '<td class="slr-text-center">' + search_stats.cpc + '€</td>';
        html += '<td class="slr-text-center">' + (search_stats.competition * 100) + '%</td>';
        html += '<td class="slr-text-center">' + search_stats.estimated_visits + '</td>';
        html += '</tr>';
        
        if(search_stats.trend)
        {
            showTrends(search_stats.trend);
        }
    }
    else
    {
        html += '<td class="slr-text-center"><i class="fas fa-lock"></i></td>';
        html += '<td class="slr-text-center"><i class="fas fa-lock"></i></td>';
        html += '<td class="slr-text-center"><i class="fas fa-lock"></i></td>';
        html += '<td class="slr-text-center"><i class="fas fa-lock"></i></td>';
        html += '</tr>';
    }
    
    jQuery("#google_data_table").html(html);
}

function updateIndexedUrlTable(data, tk_id, keyword)
{
    if(data.length > 0)
    {
        var last = data[0];
        var html = "";
        html += '<tr>';
        if(last.rank > 0)
        {
            html += '<td><a href="' + last.url + '" target="_blank">' + last.url + '</a></td>';
            html += '<td class="slr-text-center">' + last.rank + '</td>';
            
        }
        else
        {
            html += '<td>N/A</td>';
            html += '<td class="slr-text-center">101+</td>';
           
        }
        
        html += '</tr>';
        
        if (last.cannibalization) {
            last.cannibalization.forEach(function(item) {
                if (item.hide) {
                    html += '<tr class="slr-blur" onclick="return goToPricingFromCannibalization()">';
                    html += '<td>' + item.url + '</td>';
                    html += '<td class="slr-text-center">' + item.position + '</td>';
                   
                }
                else {
                    html += '<tr>';
                    html += '<td><a href="' + item.url + '" target="_blank">' + item.url + '</a></td>';
                    html += '<td class="text-center">' + item.position + '</td>';
                    html += '</tr>';
                }
            });
        }
        
        jQuery("#indexed_url_table").html(html);
    }
    else
    {
        jQuery("#indexed_url_table").html("");   
    }
}

function updateMainCompetitorsTable(data, tk_id, keyword)
{
    if(data.length > 0)
    {
        
        var html = ""; 
        jQuery.each( data, function( key, value ) {
            
            if(value.my_domain)
            {
               html += '<tr style="background-color:#f2f2f2; font-weight: bold;">'; 
            }
            else
            {
                html += '<tr>';
            }
            
            html += '<td class="text-center">' + value.position + '</td>';
            html += '<td><a href="' + value.url + '" target="_blank"><span class="truncate">' + value.url + '</span>';
            if(value.cannibalization)
            {
                html += '<i class="fa fa-bug" style="color:red;margin-left:5px;"></i>';
            }
            html +='</a></td>';
            
            /*if(value.url_analysis != null)
            {
                html += '<td class="text-center hidden-xs hidden-sm">' + getScoreHtml(value.url_analysis.friendly_score, value.url_analysis.redirection) + '</td>';
                
            }
            else
            {
               html += '<td class="text-center hidden-xs hidden-sm"> <button type="button" class="btn btn-primary btn-xs btn-block" onclick="getSeoAnalysis(this)" style="padding: 2px;" analyze-url="'+value.url+'" analyze-tk="' + tk_id + '" analyze-kw="' + keyword + '">' + lang.dictionary.seo_analyze + '</button> </td>'; 
            }
            
            if(value.url_pagespeed_analysis != null)
            {
                html += '<td class="text-center hidden-xs hidden-sm">' + getScoreHtml(value.url_pagespeed_analysis.mobile_score, value.url_pagespeed_analysis.redirection) + '</td>';
            }
            else
            {
               html += '<td class="text-center hidden-xs hidden-sm"> <button type="button" class="btn btn-primary btn-xs btn-block" onclick="getPagespeedAnalysis(this)" style="padding: 2px;" analyze-url="'+value.url+'">' + lang.dictionary.seo_analyze + '</button> </td>'; 
            }*/
            
            html += '</tr>';
        });   
           
 
        jQuery("#main_competitors_table").html(html);
    }
    else
    {
        jQuery("#main_competitors_table").html("");
        
    }
}

function showKeywordStats(keyword_row)
{
    var tk_id = jQuery(keyword_row).attr("id");

    var domainId = jQuery(keyword_row).attr('domain-id');
    var keywordProvinceId = jQuery(keyword_row).attr('keyword-province-id');
    var keyword =  jQuery(keyword_row).attr('keyword');
    var position = jQuery(keyword_row).find(".rank").html();
    var tk_id = jQuery(keyword_row).attr('id');
    var tk_days = jQuery("#tk-days").val();
    var paused = jQuery(keyword_row).attr("paused");
    
    jQuery("#tk-keyword").html(jQuery(keyword_row).find(".tk-keyword").html() + " (" + jQuery(keyword_row).find(".tk-province").html() + ")");
    jQuery("#tk-keyword-position").html(position); 
    
    if(paused == 1)
    {
        jQuery("#paused-advise").css("display","block"); 
        jQuery("#activate-tracking-button").css("visibility","visible");
    }
    else
    {
        jQuery("#paused-advise").css("display","none"); 
        jQuery("#activate-tracking-button").css("visibility","hidden");
    }
    
    if(domainId > 0 && keywordProvinceId > 0)
    {
         getKeywordHistory(domainId, keywordProvinceId, keyword, tk_id, tk_days);
    }
}

function getKeywordHistory(domainId, keywordProvinceId, keyword, tk_id, tk_days)
{
    last = true;
    if(tk_days > 0)
    {
        last = false;
    }
    jQuery.ajax(
    {
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: ajax_var.url,
        data: 
        { 
            action: "slr_kw_history", 
            domain_id : domainId,
            keyword_province_id : keywordProvinceId,
            tracking_keyword_id : tk_id,
            last: last,
            get_scores: true,
            days: tk_days,
            _ajax_nonce: ajax_var.nonce, 

        },
      
        beforeSend:function()
        {
            
        },
        success:function(response)
        {
            
            if(response.ok)
            {
       
                var series = getChartSeries(response.data);
                var intervals = getChartIntervals(response.data);
                
                showChartsEChart(series, intervals, keyword);
               
                updateGoogleDataTable(response.search_stats);
                //updateIndexedUrlTable(response.data, tk_id, keyword);
                updateMainCompetitorsTable(response.top_10, tk_id, keyword);
                
                
                
                jQuery("#stats-loader").css("display","none");
                jQuery("#stats-data").css("opacity","1");
                
                
            }
            else
            {
                
            }
            
            
        },
        error:function(resultado)
        {
            
            
        },
        timeout:250000,
    });
    return false;
}