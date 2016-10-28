<head>
<style type="text/css">
 
</style>
<script type="text/javascript">
    var aJaxURL           = "server-side/report/all_call.action.php";
    var aJaxURL_getmail	  = "includes/phpmailer/gmail.php";
    var aJusURL_mail      = "server-side/call/Email_sender.action.php";
    var aJaxURL_send_sms  = "includes/sendsms.php";
    var tName             = "table_";
    var dialog            = "add-edit-form";
    var colum_number      = 13;
    var main_act          = "get_list";
    var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl>";
     
    $(document).ready(function () {
    	GetButtons("add_button","delete_button");
    	GetDate('start_date');
    	GetDate('end_date');
    	LoadTable('index',colum_number,main_act,change_colum_main);
    	//SetEvents("add_button", "delete_button", "", tName+'index', dialog, aJaxURL);
    	$('#operator_id,#tab_id').chosen({ search_contains: true });
    	$('.callapp_filter_body').css('display','none');
    	

    	    $.session.clear(); 
    });

    function LoadTable(tbl,col_num,act,change_colum){
    	GetDataTable(tName+tbl,
    	    	aJaxURL,
    	    	act,
    	    	col_num,
    	    	"start_date="+$('#start_date').val()+
    	    	"&end_date="+$('#end_date').val()+
    	    	"&operator_id="+$('#operator_id').val()+
    	    	"&tab_id="+$('#tab_id').val()+
    	    	"&filter_1="+$('#filter_1:checked').val()+
    	    	"&filter_2="+$('#filter_2:checked').val()+
    	    	"&filter_3="+$('#filter_3:checked').val()+
    	    	"&filter_4="+$('#filter_4:checked').val()+
    	    	"&filter_5="+$('#filter_5:checked').val()+
    	    	"&filter_6="+$('#filter_6:checked').val()+
    	    	"&filter_7="+$('#filter_7:checked').val(),
    	    	0,
    	    	"",
    	    	1,
    	    	"asc",
    	    	'',
    	    	change_colum);
    	setTimeout(function(){
	    	$('.ColVis, .dataTable_buttons').css('display','none');
	    	}, 90);
    }

    function LoadTable1(tbl,col_num,act,change_colum,custom_param,URL){
    	GetDataTable(tName+tbl, URL, act, col_num, custom_param, 0, "", 2, "desc", '', change_colum);
    	setTimeout(function(){
	    	$('.ColVis, .dataTable_buttons').css('display','none');
	    	}, 50);
    	$('.display').css('width','100%');
    }
    
    function LoadDialog(fName){
    	var buttons = {
				"save": {
		            text: "Save",
		            id: "save-dialog"
		        },
	        	"cancel": {
		            text: "Close",
		            id: "cancel-dialog",
		            click: function () {
		            	$(this).dialog("close");
		            }
		        }
		    };
        GetDialog(fName, 300, "auto", buttons, 'left+43 top');
        LoadTable1('sms',5,'get_list',"<'F'lip>",'',aJaxURL);
        LoadTable1('mail',5,'get_list_mail',"<'F'lip>",'incomming_id='+$('#incomming_id').val(),aJaxURL);
        $("#client_checker,#add_sms,#add_mail,#show_all_scenario").button();
        GetDate2("date_input");
        GetDateTimes("task_end_date");
        GetDateTimes("task_start_date");
		GetDateTimes1("date_time_input");
		$('#back_quest,#next_quest').css('display','none');
		//$('.1').css('display','block');
		$('#next_quest').attr('next_id',$('.1').attr('id'));
		$('#next_quest, #back_quest').button();
		$('#back_quest').prop('disabled',true);

    }

    $(document).on("click", ".callapp_refresh", function () {
    	LoadTable('index',colum_number,main_act,change_colum_main);
    });
    
    $(document).on("change", "#incomming_cat_1", function () {
    	param 			= new Object();
		param.act		= "cat_2";
		param.cat_id    = $('#incomming_cat_1').val();
        $.ajax({
            url: aJaxURL,
            data: param,
            success: function(data) {
                $("#incomming_cat_1_1").html(data.page);
            }
        });
    });
    $(document).on("change", "#incomming_cat_1_1", function () {
    	param 			= new Object();
		param.act		= "cat_3";
		param.cat_id    = $('#incomming_cat_1_1').val();
        $.ajax({
            url: aJaxURL,
            data: param,
            success: function(data) {
                $("#incomming_cat_1_1_1").html(data.page);
            }
        });
    });

    $(document).on("change", "#operator_id", function () {
        if($(this).val() != 0){
            $.session.set("operator_id", "on");
        }else{
            $.session.remove('operator_id');
        }
    	my_filter();
    });
    $(document).on("change", "#tab_id", function () {
    	if($(this).val() != 0){
    	    $.session.set("tab_id", "on");
    	}else{
            $.session.remove('tab_id');
        }
    	my_filter();
    });
    $(document).on("click", "close", function () {
        var id = $(this).attr('cl');
        $.session.remove($(this).attr('cl'));
        $( "#"+id ).click();
        my_filter();
    });
    $(document).on("click", "#filter_1", function () {
    	if ($(this).is(':checked')) {
    	    $.session.set("filter_1", "on");
    	    LoadTable('index',colum_number,main_act,change_colum_main);
    	}else{
    		$.session.remove('filter_1');
    	}
    	my_filter();
    });
    $(document).on("click", "#filter_2", function () {
    	if ($(this).is(':checked')) {
    	    $.session.set("filter_2", "on");
    	}else{
    		$.session.remove('filter_2');
    	}
    	my_filter();
    });
    $(document).on("click", "#filter_3", function () {
    	if ($(this).is(':checked')) {
    	    $.session.set("filter_3", "on");
    	}else{
    		$.session.remove('filter_3');
    	}
    	my_filter();
    });
    $(document).on("click", "#filter_4", function () {
    	if ($(this).is(':checked')) {
    	    $.session.set("filter_4", "on");
    	}else{
    		$.session.remove('filter_4');
    	}
    	my_filter();
    });
    $(document).on("click", "#filter_5", function () {
    	if ($(this).is(':checked')) {
    	    $.session.set("filter_5", "on");
    	}else{
    		$.session.remove('filter_5');
    	}
    	my_filter();
    });
    $(document).on("click", "#filter_6", function () {
    	if ($(this).is(':checked')) {
    	    $.session.set("filter_6", "on");
    	}else{
    		$.session.remove('filter_6');
    	}
    	my_filter();
    });
    $(document).on("click", "#filter_7", function () {
    	if ($(this).is(':checked')) {
    	    $.session.set("filter_7", "on");
    	}else{
    		$.session.remove('filter_7');
    	}
    	my_filter();
    });


    $(document).on("change", "#end_date", function () {
    	LoadTable('index',colum_number,main_act,change_colum_main);
    });

    $(document).on("change", "#start_date", function () {
    	LoadTable('index',colum_number,main_act,change_colum_main);
    });

    function my_filter(){
    	var myhtml = '';
    	if($.session.get("operator_id")=='on'){
    		myhtml += '<span>Operator<close cl="operator_id">X</close></span>';
    	}else{
    		$('#operator_id option:eq(0)').prop('selected', true);
    	}
    	if($.session.get("tab_id")=='on'){
    		myhtml += '<span>Tab<close cl="tab_id">X</close></span>';
    	}else{
    		$('#tab_id option:eq(0)').prop('selected', true);
    	}
    	if($.session.get("filter_1")=='on'){
    		myhtml += '<span>Inc. Treated<close cl="filter_1">X</close></span>';
    	}
    	if($.session.get("filter_2")=='on'){
    		myhtml += '<span>Inc. Untreated<close cl="filter_2">X</close></span>';
    	}
    	if($.session.get("filter_3")=='on'){
    		myhtml += '<span>Inc. Unansswer<close cl="filter_3">X</close></span>';
    	}
    	if($.session.get("filter_4")=='on'){
    		myhtml += '<span>Out Ansswer<close cl="filter_4">X</close></span>';
    	}
    	if($.session.get("filter_5")=='on'){
    		myhtml += '<span>Out Unansswer<close cl="filter_5">X</close></span>';
    	}
    	if($.session.get("filter_6")=='on'){
    		myhtml += '<span>Inner Ansswer<close cl="filter_6">X</close></span>';
    	}
    	if($.session.get("filter_7")=='on'){
    		myhtml += '<span>Inner Unansswer<close cl="filter_7">X</close></span>';
    	}
    	
    	$('.callapp_tabs').html(myhtml);
    	LoadTable('index',colum_number,main_act,change_colum_main);
    	$('#operator_id, #tab_id').trigger("chosen:updated");
    }
    
    function show_right_side(id){
        $("#right_side fieldset").hide();
        $("#" + id).show();
        $(".add-edit-form-class").css("width", "1200");
        //$('#add-edit-form').dialog({ position: 'left top' });
        hide_right_side();
        var str = $("."+id).children('img').attr('src');
		str = str.substring(0, str.length - 4);
        $("."+id).children('img').attr('src',str+'_blue.png');
        $("."+id).children('div').css('color','#2681DC');
    }

    function hide_right_side(){
    	$("#side_menu").children('spam').children('div').css('color','#FFF');
        $(".info").children('img').attr('src','media/images/icons/info.png');
        $(".scenar").children('img').attr('src','media/images/icons/scenar.png');
        $(".task").children('img').attr('src','media/images/icons/task.png');
        $(".sms").children('img').attr('src','media/images/icons/sms.png');
        $(".mail").children('img').attr('src','media/images/icons/mail.png');
        $(".record").children('img').attr('src','media/images/icons/record.png');
        $(".file").children('img').attr('src','media/images/icons/file.png');
        $(".question").children('img').attr('src','media/images/icons/question.png');
        $("#record fieldset").show();
    }
    
    function show_main(id,my_this){
    	$("#client_main,#client_other").hide();
    	$("#" + id).show();
    	$(".client_main,.client_other").css('border','none');
    	$(".client_main,.client_other").css('padding','6px');
    	$(my_this).css('border','1px solid #ccc');
    	$(my_this).css('border-bottom','1px solid #F1F1F1');
    	$(my_this).css('padding','5px');
    }

    function client_status(id){
    	$("#pers,#iuri").hide();
    	$("#" + id).show();
    }
    
    $(document).on("click", ".hide_said_menu", function () {
    	$("#right_side fieldset").hide();    	
    	$(".add-edit-form-class").css("width", "300");
        //$('#add-edit-form').dialog({ position: 'top' });
        hide_right_side();
    });

    $(document).on("click", "#show_copy_prit_exel", function () {
        if($(this).attr('myvar') == 0){
            $('.ColVis,.dataTable_buttons').css('display','block');
            $(this).css('background','#2681DC');
            $(this).children('img').attr('src','media/images/icons/select_w.png');
            $(this).attr('myvar','1');
        }else{
        	$('.ColVis,.dataTable_buttons').css('display','none');
        	$(this).css('background','#E6F2F8');
            $(this).children('img').attr('src','media/images/icons/select.png');
            $(this).attr('myvar','0');
        }
    });    
    
    $(document).on("click", "#add_sms", function () {
    	param 			= new Object();
		param.act		= "send_sms";
        $.ajax({
            url: aJaxURL,
            data: param,
            success: function(data) {
                $("#add-edit-form-sms").html(data.page);
                $("#copy_phone,#sms_shablon,#send_sms").button();
            }
        });
    	var buttons = {
	        	"cancel": {
		            text: "Close",
		            id: "cancel-dialog",
		            click: function () {
		            	$(this).dialog("close");
		            }
		        }
		    };
        GetDialog("add-edit-form-sms", 360, "auto", buttons);
    });
    
    $(document).on("click", "#callapp_show_filter_button", function () {
        if($('.callapp_filter_body').attr('myvar') == 0){
        	$('.callapp_filter_body').css('display','block');
        	$('.callapp_filter_body').attr('myvar',1);
        }else{
        	$('.callapp_filter_body').css('display','none');
        	$('.callapp_filter_body').attr('myvar',0);
        }        
    });

    function listen(file){
        var url = location.origin + "/records/" + file;
        $("audio source").attr('src',url);
        $("audio").load();
    }
    $(document).on("click", "#upload_file", function () {
	    $('#file_name').click();
	});
    $(document).on("change", "#file_name", function () {
        var file_url  = $(this).val();
        var file_name = this.files[0].name;
        var file_size = this.files[0].size;
        var file_type = file_url.split('.').pop().toLowerCase();
        var path	  = "../../media/uploads/file/";

        if($.inArray(file_type, ['pdf','png','xls','xlsx','jpg','docx','doc','csv']) == -1){
            alert("Allowed only 'pdf', 'png', 'xls', 'xlsx', 'jpg', 'docx', 'doc', 'csv' type");
        }else if(file_size > '15728639'){
            alert("File size 15MB over");
        }else{
        	$.ajaxFileUpload({
		        url: "server-side/upload/file.action.php",
		        secureuri: false,
     			fileElementId: "file_name",
     			dataType: 'json',
			    data: {
					act: "file_upload",
					button_id: "file_name",
					table_name: 'incomming_call',
					file_name: Math.ceil(Math.random()*99999999999),
					file_name_original: file_name,
					file_type: file_type,
					file_size: file_size,
					path: path,
					table_id: $("#incomming_id").val(),

				},
		        success: function(data) {			        
			        if(typeof(data.error) != 'undefined'){
						if(data.error != ''){
							alert(data.error);
						}else{
							var tbody = '';
							for(i = 0;i <= data.page.length;i++){
								tbody += "<div id=\"first_div\">" + data.page[i].file_date + "</div>";
								tbody += "<div id=\"two_div\">" + data.page[i].name + "</div>";
								tbody += "<div id=\"tree_div\" onclick=\"download_file('" + data.page[i].rand_name + "')\">Download</div>";
								tbody += "<div id=\"for_div\" onclick=\"delete_file('" + data.page[i].id + "')\">-</div>";
								$("#paste_files").html(tbody);
							}							
						}						
					}					
			    }
		    });
        }
    });

    function download_file(file){
        var download_file	= "media/uploads/file/"+file;
    	var download_name 	= file;
    	SaveToDisk(download_file, download_name);
    }
    
    function delete_file(id){
    	$.ajax({
            url: "server-side/upload/file.action.php",
            data: "act=delete_file&file_id="+id+"&table_name=incomming_call",
            success: function(data) {
               
            	var tbody = '';
            	if(data.page.length == 0){
            		$("#paste_files").html('');
            	};
				for(i = 0;i <= data.page.length;i++){
					tbody += "<div id=\"first_div\">" + data.page[i].file_date + "</div>";
					tbody += "<div id=\"two_div\">" + data.page[i].name + "</div>";
					tbody += "<div id=\"tree_div\" onclick=\"download_file('" + data.page[i].rand_name + "')\">Download</div>";
					tbody += "<div id=\"for_div\" onclick=\"delete_file('" + data.page[i].id + "')\">-</div>";
					$("#paste_files").html(tbody);
				}	
            }
        });
    }

    function SaveToDisk(fileURL, fileName) {
    	var iframe = document.createElement("iframe"); 
        iframe.src = fileURL; 
        iframe.style.display = "none"; 
        document.body.appendChild(iframe);
        return false;
    }

    $(document).on("click", "#send_sms", function (fName) {
	    param 			= new Object();

	    param.sms_hidde_id		= sms_id;
    	param.phone			= $("#sms_phone").val();
    	param.text			= $("#sms_text").val();
    	param.sms_inc_increm_id	= $("#sms_inc_increm_id").val();
    	
    	 $.ajax({
		        url: aJaxURL_send_sms,
			    data: param,
		        success: function(data) {
                    $("#sms_text").val('');
                    alert('SMS has been successfully sent');
                    LoadTable1_1();
                    CloseDialog("sms_dialog");
			    }
		    });
 	    });
    
    $(document).on("click", ".open_dialog", function () {
    	var queoue = $($(this).siblings())[0];
    	queoue = $(queoue).text();
        $.ajax({
            url: aJaxURL,
            type: "POST",
            data: "act=get_edit_page&id=&open_number=" + $(this).text() + "&queue=" + queoue,
            dataType: "json",
            success: function (data) {
                if (typeof (data.error) != "undefined") {
                    if (data.error != "") {
                        alert(data.error);
                    } else {
                        $("#add-edit-form").html(data.page); 
                    	LoadDialog('add-edit-form');
                    }
                }
            }
        });        
    });
    
    $(document).on("click", "#show_flesh_panel", function () {
        //$('#flesh_panel').css('width','425px');
        $( "#flesh_panel" ).animate({
            width: "425px"
          }, 1000 );
        $('#show_flesh_panel').attr('src','media/images/icons/arrow_right.png');
        $('#show_flesh_panel').attr('id','show_flesh_panel_right');
        $('#flesh_panel_table_mini').css('display','none');
        $('#flesh_panel_table').css('display','block');
        $('#flesh_panel').css('z-index','99');
        $('#show_flesh_panel_right').attr('title','Panel smaller');
    });
    $(document).on("click", "#show_flesh_panel_right", function () {
        //$('#flesh_panel').css('width','150px');
        $( "#flesh_panel" ).animate({
            width: "150px"
          }, 300 );
        $('#show_flesh_panel_right').attr('src','media/images/icons/arrow_left.png');
        $('#show_flesh_panel_right').attr('id','show_flesh_panel');
        $('#flesh_panel_table').css('display','none');
        $('#flesh_panel_table_mini').css('display','block');
        $('#flesh_panel').css('z-index','99');
        $('#show_flesh_panel').attr('title','Larger panel');
    });

    $(document).on("click", "#save-dialog", function () {
		   
		param 				= new Object();
		param.act			= "save_incomming";
	    	
		param.id					= $("#hidden_id").val();
			
		// --------------------------------------------------
		var items          = {};
    	var checker        = {};
    	var inp_checker    = {};
    	var radio_checker  = {};
    	var date_checker   = {};
    	var date_date_checker = {};
    	var select ={};
    	
    	$('#add-edit-form .check_input:checked').each(function() {
	    	
    		key      = this.name;
    		value    = this.value;
    		ansver_val    = $(this).attr('ansver_val');
    		
    		checker[key] = checker[key] + "," + value;

    	});
    	
    	items.checker = checker;
    	
        $('#add-edit-form .inputtext').each(function() {
	    	
    		inp_key      = this.id;
    		inp_value    = this.value;
    		inp_q_id     = $(this).attr('q_id');
    		
    	    if(inp_value != ''){
    		 inp_checker[inp_key] = inp_checker[inp_key] + "," + inp_value;
    	    }
    	});
    	
    	items.input   = inp_checker;

        $('#dialog-form .radio_input:checked').each(function() {
	    	
    		radio_key      = this.name;
    		radio_value    = this.value;
    		ansver_val     = $(this).attr('ansver_val');
    		
    		radio_checker[radio_key] = checker[radio_key] + "," + radio_value;

    	});
    	
    	items.radio = radio_checker;

        $('#add-edit-form .date_input').each(function() {
	    	
        	date_key      = this.id;
    		date_value    = this.value;
    	    if(date_value != ''){
    	    	date_checker[date_key] = date_checker[date_key] + "," + date_value;
    	    }
    	});
    	
    	items.date   = date_checker;

        $('#add-edit-form .date_time_input').each(function() {	
	    	
        	date_time_key      = this.id;
        	date_time_value    = this.value;
    	    if(date_time_value != ''){
    	    	date_date_checker[date_time_key] = date_date_checker[date_time_key] + "," + date_time_value;
    	    }
    	});
    	
    	items.date_time   = date_date_checker;

        $('#add-edit-form .hand_select').each(function() {

	    	//alert($("option:selected",this).val());
        	select_key      = this.id;
        	select_value    = $("option:selected",this).val();
    		
        	select[select_key] = select[select_key] + "," + select_value;

    	});
    	
    	items.select_op   = select;

		//----------------------------------------------------
		
		// Incomming Vars
    	param.incomming_id          = $("#incomming_id").val();
		param.hidden_id				= $("#hidden_id").val();
		param.incomming_phone		= $("#incomming_phone").val();
		param.incomming_date        = $("#incomming_date").val();
		param.incomming_cat_1		= $("#incomming_cat_1").val();
		param.incomming_cat_1_1		= $("#incomming_cat_1_1").val();
		param.incomming_cat_1_1_1	= $("#incomming_cat_1_1_1").val();
		param.incomming_comment		= $("#incomming_comment").val();
		param.scenario_id           = $("#scenario_id").val();
		param.inc_status_id         = $("#inc_status_id").val();

		// Incomming Client Vars
		param.in_sorce_info_id = $("#in_sorce_info_id").val();
		param.in_service_center_id = $("#in_service_center_id").val();
		param.in_branch_id = $("#in_branch_id").val();
		param.in_district_id = $("#in_district_id").val();
		param.in_type_id = $("#in_type_id").val();
		param.cl_id = $("#cl_id").val();
		param.cl_name = $("#cl_name").val();
		param.cl_ab = $("#cl_ab").val();
		param.cl_ab_num = $("#cl_ab_num").val();
		param.cl_addres = $("#cl_addres").val();
		param.cl_phone = $("#cl_phone").val();

		param.task_type_id			= $("#task_type_id").val();
		param.task_start_date		= $("#task_start_date").val();
		param.task_end_date			= $("#task_end_date").val();
		param.task_departament_id	= $("#task_departament_id").val();
		param.task_recipient_id		= $("#task_recipient_id").val();
		param.task_priority_id		= $("#task_priority_id").val();
		param.task_controler_id		= $("#task_controler_id").val();
		param.task_status_id		= $("#task_status_id").val();
		param.task_description		= $("#task_description").val();
		param.task_note			    = $("#task_note").val();
		
		var link = GetAjaxData(param);		
	    	$.ajax({
		        url: aJaxURL,
			    data: link + "&checker=" + JSON.stringify(items.checker) + "&input=" + JSON.stringify(items.input)  + "&radio=" + JSON.stringify(items.radio) + "&date=" + JSON.stringify(items.date) + "&date_time=" + JSON.stringify(items.date_time) + "&select_op=" + JSON.stringify(items.select_op),
		        success: function(data) {       
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							LoadTable('index',colum_number,main_act,change_colum_main);
						    CloseDialog("add-edit-form");
						}
					}
		    	}
		   });
	});

	function play(str){
		var win = window.open('http://'+location.hostname+':8000/'+str, '_blank');
		if(win){
		    //Browser has allowed it to be opened
		    win.focus();
		}else{
		    //Broswer has blocked it
		    alert('Please allow popups for this site');
		}
	}

	$(document).on("dblclick", "#table_mail tbody tr", function () {
    	var nTds = $("td", this);
        var empty = $(nTds[0]).attr("class");
        
            var rID = $(nTds[0]).text();
            
            $.ajax({
                url: aJusURL_mail,
                type: "POST",
                data: "act=send_mail&call_type=inc&mail_id=" + rID + "&",
                dataType: "json",
                success: function (data) {
                    if (typeof (data.error) != "undefined") {
                        if (data.error != "") {
                            alert(data.error);
                        } else {
                            
                            if ($.isFunction(window.LoadDialog)) {
                                //execute it
                            	var buttons = {
                        	        	"cancel": {
                        		            text: "Close",
                        		            id: "cancel-dialog",
                        		            click: function () {
                        		            	$(this).dialog("close");
                        		            }
                        		        }
                        		    };
                                GetDialog("add-edit-form-mail", 640, "auto", buttons, 'center top');
                               
                                $("#add-edit-form-mail").html(data.page);
                                $("#email_shablob,#choose_button_mail,#send_email").button();
                                setTimeout(function(){ 
                        			new TINY.editor.edit('editor',{
                        				id:'input',
                        				width:"580px",
                        				height:"200px",
                        				cssclass:'te',
                        				controlclass:'tecontrol',
                        				dividerclass:'tedivider',
                        				controls:['bold','italic','underline','strikethrough','|','subscript','superscript','|',
                        				'orderedlist','unorderedlist','|','outdent','indent','|','leftalign',
                        				'centeralign','rightalign','blockjustify','|','unformat','|','undo','redo','n',
                        				'font','size','|','image','hr','link','unlink','|','print'],
                        				footer:true,
                        				fonts:['Verdana','Arial','Georgia','Trebuchet MS'],
                        				xhtml:true,
                        				bodyid:'editor',
                        				footerclass:'tefooter',
                        				resize:{cssclass:'resize'}
                        			}); }, 100);
                            }
                        }
                    }
                }
            });
        
    });
    
    $(document).on("click", "#email_shablob", function () {
    	param 			= new Object();
		param.act		= "send_mail_shablon";
        $.ajax({
            url: aJusURL_mail,
            data: param,
            success: function(data) {
                $("#add-edit-form-mail-shablon").html(data.page);                
            }
        });
    	var buttons = {
	        	"cancel": {
		            text: "Close",
		            id: "cancel-dialog",
		            click: function () {
		            	$(this).dialog("close");
		            }
		        }
		    };
        GetDialog("add-edit-form-mail-shablon", 415, "auto", buttons,'center top');
	});
    
    $(document).on("click", "#add_mail", function () {
    	param 			= new Object();
		param.act		= "send_mail";
		param.call_type	= 'inc';
		param.incomming_id	= $('#incomming_id').val();
        $.ajax({
            url: aJusURL_mail,
            data: param,
            success: function(data) {
                $("#add-edit-form-mail").html(data.page);
                $("#email_shablob,#choose_button_mail,#send_email").button();
                
            }
        });
    	var buttons = {
	        	"cancel": {
		            text: "Close",
		            id: "cancel-dialog",
		            click: function () {
		            	$(this).dialog("close");
		            }
		        }
		    };
        GetDialog("add-edit-form-mail", 640, "auto", buttons, 'center top');
        setTimeout(function(){ 
			new TINY.editor.edit('editor',{
				id:'input',
				width:"580px",
				height:"200px",
				cssclass:'te',
				controlclass:'tecontrol',
				dividerclass:'tedivider',
				controls:['bold','italic','underline','strikethrough','|','subscript','superscript','|',
				'orderedlist','unorderedlist','|','outdent','indent','|','leftalign',
				'centeralign','rightalign','blockjustify','|','unformat','|','undo','redo','n',
				'font','size','|','image','hr','link','unlink','|','print'],
				footer:true,
				fonts:['Verdana','Arial','Georgia','Trebuchet MS'],
				xhtml:true,
				bodyid:'editor',
				footerclass:'tefooter',
				resize:{cssclass:'resize'}
			}); }, 100);
    });

    function pase_body(id,head){
        $('#mail_text').val(head);
    	$("iframe").contents().find("body").html($('#'+id).html());
    	$('#add-edit-form-mail-shablon').dialog('close');
    }

    $(document).on("click", "#send_email", function () {
		  	param 			= new Object();

		  	param.source_id         = $("#source_id").val();
	    	param.address		    = $("#mail_address").val();
	    	param.cc_address		= $("#mail_address1").val();
	    	param.bcc_address		= $("#mail_address2").val();
	    	
	    	param.subject			= $("#mail_text").val();
	    	param.send_mail_id	    = $("#send_email_hidde").val();
			param.incomming_call_id	= $("#sms_inc_increm_id").val();
			param.body				= $("iframe").contents().find("body").html();
			
	    	$.ajax({
			        url: aJaxURL_getmail,
				    data: param,
				   
			        success: function(data) {
						if(data.status=='true'){
							alert('Message sent!');
							$("#mail_text").val('');
							$("iframe").contents().find("body").html('');
							$("#file_div_mail").html('');
							CloseDialog("add-edit-form-mail");
							LoadTable1('mail',5,'get_list_mail',"<'F'lip>",'incomming_id='+$('#incomming_id').val(),aJaxURL);
						}else{
							alert('Message not sent!');
						}
					}
			    });
			});
    $(document).on("click", "#choose_button_mail", function () {
	    $("#choose_mail_file").click();
	});

    $(document).on("change", "#choose_mail_file", function () {
        var file_url  = $(this).val();
        var file_name = this.files[0].name;
        var file_size = this.files[0].size;
        var file_type = file_url.split('.').pop().toLowerCase();
        var path	  = "../../media/uploads/file/";

        if($.inArray(file_type, ['pdf','png','xls','xlsx','jpg','docx','doc','csv']) == -1){
            alert("Allowed only 'pdf', 'png', 'xls', 'xlsx', 'jpg', 'docx', 'doc', 'csv' type");
        }else if(file_size > '15728639'){
            alert("File size 15MB over");
        }else{
        	$.ajaxFileUpload({
		        url: "server-side/upload/file.action.php",
		        secureuri: false,
     			fileElementId: "choose_mail_file",
     			dataType: 'json',
			    data: {
					act: "file_upload",
					button_id: "choose_mail_file",
					table_name: 'outgoing',
					file_name: Math.ceil(Math.random()*99999999999),
					file_name_original: file_name,
					file_type: file_type,
					file_size: file_size,
					path: path,
					table_id: $("#incomming_id").val(),

				},
		        success: function(data) {			        
			        if(typeof(data.error) != 'undefined'){
						if(data.error != ''){
							alert(data.error);
						}else{
							var tbody = '';
							for(i = 0;i <= data.page.length;i++){
								tbody += "<div id=\"first_div\">" + data.page[i].file_date + "</div>";
								tbody += "<div id=\"two_div\">" + data.page[i].name + "</div>";
								tbody += "<div id=\"tree_div\" onclick=\"download_file('" + data.page[i].rand_name + "','"+data.page[i].name+"')\">Download</div>";
								tbody += "<div id=\"for_div\" onclick=\"delete_file1('" + data.page[i].id + "')\">-</div>";
								$("#paste_files1").html(tbody);								
							}							
						}						
					}					
			    }
		    });
        }
    });
</script>
<style type="text/css">
.callapp_tabs{
	margin-top: 5px;
	margin-bottom: 5px;
	float: right;
	width: 100%;
	height: 43px;
}
.callapp_tabs span{
	color: #FFF;
    border-radius: 5px;
    padding: 5px;
	float: left;
	margin: 0 3px 0 3px;
	background: #2681DC;
	font-weight: bold;
	font-size: 11px;
    margin-bottom: 2px;
}

.callapp_tabs span close{
	cursor: pointer;
	margin-left: 5px;
}

.callapp_head{
	font-family: pvn;
	font-weight: bold;
	font-size: 20px;
	color: #2681DC;
}
.callapp_head_hr{
	border: 1px solid #2681DC;
}
.callapp_refresh{
    padding: 5px;
    border-radius:3px;
    color:#FFF;
    background: #9AAF24;
    float: right;
    font-size: 13px;
    cursor: pointer;
}
.callapp_filter_show{
	margin-bottom: 50px;
	float: right;
	width: 100%;
}
.callapp_filter_show button{
    margin-bottom: 10px;
	border: none;
    background-color: white;
	color: #2681DC;
	font-weight: bold;
	cursor: pointer;
}
.callapp_filter_body{
	width: 100%;
	height: 83px;
	padding: 5px;
	margin-bottom: 0px;
}
.callapp_filter_body span {
	float: left;
    margin-right: 10px;
	height: 22px;
}
.callapp_filter_body span label {
	color: #555;
    font-weight: bold;
	margin-left: 20px;
}
.callapp_filter_body_span_input {
	position: relative;
	top: -17px;
}
.callapp_filter_header{
	color: #2681DC;
	font-family: pvn;
	font-weight: bold;
}

.ColVis, .dataTable_buttons{
	z-index: 50;
}
#flesh_panel{
    height: 630px;
    width: 150px;
    position: absolute;
    top: 0;
    padding: 15px;
    right: 2px;
	z-index: 49;
	background: #FFF;
}
#table_sms_length{
	position: inherit;
    width: 0px;
	float: left;
}
#table_sms_length label select{
	width: 60px;
    font-size: 10px;
    padding: 0;
    height: 18px;
}
#table_sms_paginate{
	margin: 0;
}
#table_mail_length{
	position: inherit;
    width: 0px;
	float: left;
}
#table_mail_length label select{
	width: 60px;
    font-size: 10px;
    padding: 0;
    height: 18px;
}
#table_mail_paginate{
	margin: 0;
}
</style>
</head>

<body>
<div id="tabs">
<div class="callapp_head">Calls/ Record List<span class="callapp_refresh"><img alt="refresh" src="media/images/icons/refresh.png" height="14" width="14">   Update</span><hr class="callapp_head_hr"></div>
<div class="callapp_tabs">

</div>
<div class="callapp_filter_show">
<button id="callapp_show_filter_button">Filter v</button>
    <div class="callapp_filter_body" myvar="0">
    <div style="float: left; width: 292px;">
        <span>
        <label for="start_date" style="margin-left: 110px;">-From</label>
        <input class="callapp_filter_body_span_input" type="text" id="start_date" style="width: 85px;">
        </span>
        <span>
        <label for="end_date" style="margin-left: 110px;">-Up to</label>
        <input class="callapp_filter_body_span_input" type="text" id="end_date" style="width: 85px;">
        </span>
        <span style="margin-top: 15px;">
        <select id="operator_id" style="width: 285px;">
        <option value="0">All Operator</option>
        <?php
        include '../../includes/classes/core.php';
        
        $res = mysql_query("SELECT  `users`.`id`,
				                    `user_info`.`name`
                            FROM    `users`
                            JOIN    `user_info` ON `users`.`id` = `user_info`.`user_id`
                            WHERE   `actived` = 1");
        $data = '';
        while ($req = mysql_fetch_array($res)){
            $data .= '<option value="'.$req[0].'" >'.$req[1].'</option>';
        }
        echo $data;
        ?>
        </select>
        </span>
        <span style="margin-top: 15px;">
        <select id="tab_id" style="width: 285px;">
        <option value="0">All Call</option>
        <option value="1">Transferred out</option>
        <option value="2">In Process</option>
        <option value="3">Completed</option>
        </select>
        </span>
    </div>
    <div style="float: left; width: 170px; margin-left: 20px;">
        <span >
        <div class="callapp_filter_header"><img alt="inc" src="media/images/icons/inc_call.png" height="14" width="14">  შემომავალი ზარი</div>
        </span>
        <span style="margin-left: 15px">        
        <label for="filter_1">Treated</label>
        <input class="callapp_filter_body_span_input" id="filter_1" type="checkbox" value="1">
        </span>
        <span style="margin-left: 15px">
        <label for="filter_2" style="">Untreated</label>
        <input class="callapp_filter_body_span_input" id="filter_2" type="checkbox" value="2">
        </span>
        <span style="margin-left: 15px">
        <label for="filter_3">Unansswer</label>
        <input class="callapp_filter_body_span_input" id="filter_3" type="checkbox" value="3">
        </span>        
        </div>
    <div style="float: left; width: 170px;">
        <span >
        <div class="callapp_filter_header"><img alt="out" src="media/images/icons/out_call.png" height="14" width="14">  გამავალი ზარი</div>
        </span>
        <span style="margin-left: 15px">
        <label for="filter_4">Ansswer</label>
        <input class="callapp_filter_body_span_input" id="filter_4" type="checkbox" value="4">
        </span>
        <span style="margin-left: 15px">
        <label for="filter_5">Unansswer</label>
        <input class="callapp_filter_body_span_input" id="filter_5" type="checkbox" value="5">
        </span>
        
        
        </div>
    <div style="float: left; width: 145px;">
        <span>
        <div class="callapp_filter_header"><img alt="inner" src="media/images/icons/inner_call_1.png" height="14" width="14">  შიდა ზარი</div>
        </span>
        <span style="margin-left: 15px">        
        <label for="filter_6">Ansswer</label>
        <input class="callapp_filter_body_span_input" id="filter_6" type="checkbox" value="6">
        </span>
        <span style="margin-left: 15px">
        <label for="filter_7">Unansswer</label>
        <input class="callapp_filter_body_span_input" id="filter_7" type="checkbox" value="7">
        </span>
        
    </div>
</div>
<table id="table_right_menu">
<tr>
<td><img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
</td>
<td><img alt="log" src="media/images/icons/log.png" height="14" width="14">
</td>
<td id="show_copy_prit_exel" myvar="0"><img alt="link" src="media/images/icons/select.png" height="14" width="14">
</td>
</tr>
</table>

<table class="display" id="table_index" style="width: 100%;">
    <thead>
        <tr id="datatable_header">
            <th>ID</th>
            <th style="width: 46px;">№</th>
            <th style="width: 150px;">Date</th>
            <th style="width: 120px;">Recipient</th>
            <th style="width: 120px;">Source</th>
            <th style="width: 25%;">Operator</th>
            <th style="width: 25%;">Duration</th>            
            <th style="width: 25%;">Status</th>
            <th style="width: 25%;">Lisen</th>
        </tr>
    </thead>
    <thead>
       <tr class="search_header">
            <th class="colum_hidden">
        	   <input type="text" name="search_id" value="Filter" class="search_init" />
            </th>
            <th>
            	<input type="text" name="search_number" value="Filter" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_date" value="Filter" class="search_init" />
            </th>    
            <th>
                <input type="text" name="search_date" value="Filter" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_date" value="Filter" class="search_init" />
            </th>                         
            <th>
                <input type="text" name="search_category" value="Filter" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_category" value="Filter" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_phone" value="Filter" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_category" value="Filter" class="search_init" />
            </th>            
        </tr>
    </thead>
</table>
</div>

<!-- jQuery Dialog -->
<div  id="add-edit-form" class="form-dialog" title="All Call">
</div>
<!-- jQuery Dialog -->
<div  id="add-edit-form-sms" class="form-dialog" title="New SMS">
</div>
<!-- jQuery Dialog -->
<div  id="add-edit-form-mail" class="form-dialog" title="New E-mail">
</div>
<!-- jQuery Dialog -->
<div  id="add-edit-form-mail-shablon" class="form-dialog" title="E-mail Template">
</div>

</body>