<head>
<style type="text/css">
.re{
	height:22px;width:20px;
	background: #F44336;
}
.gr{
	height:22px;width:20px;
	background: #4CAE50;
}
.ye{
	height:22px;width:20px;
	background: #ECAF00;
}
#box-table-b
{
	font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;
	text-align: center;
	border-collapse: collapse;
	border-top: 7px solid #70A9D2;
	border-bottom: 7px solid #70A9D2;
	
}
#box-table-b1
{
	font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;
	text-align: center;
	border-collapse: collapse;
	border-top: 7px solid #70A9D2;
	border-bottom: 7px solid #70A9D2;
	width: 300px;
}
#box-table-b th
{
	font-size: 13px;
	font-weight: normal;
	padding: 8px;
	background: #E8F3FC;;
	border-right: 1px solid #9baff1;
	border-left: 1px solid #9baff1;
	color: #4496D5;
}
#box-table-b1 th
{
	font-size: 13px;
	font-weight: normal;
	padding: 8px;
	background: #E8F3FC;;
	border-right: 1px solid #9baff1;
	border-left: 1px solid #9baff1;
	color: #4496D5;
}
#box-table-b td
{
	padding: 8px;
	background: #e8edff; 
	border-right: 1px solid #aabcfe;
	border-left: 1px solid #aabcfe;
	color: #669;
}
#box-table-b1 td
{
	padding: 8px;
	background: #e8edff; 
	border-right: 1px solid #aabcfe;
	border-left: 1px solid #aabcfe;
	color: #669;
}
.download_shablon {
	background-color:#4997ab;
	border-radius:2px;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:arial;
	font-size:14px;
	border:0px;
	text-decoration:none;
	text-overflow: ellipsis;
}
</style>
<script type="text/javascript">
    var aJaxURL           = "server-side/call/incomming.action.php";
    var aJaxURL_getmail	  = "includes/phpmailer/smtp.php";
    var aJusURL_mail      = "server-side/call/Email_sender.action.php";
    var aJaxURL_send_sms  = "includes/sendsms.php";
    var aJaxURL_service   = "server-side/call/service.action.php";
    var tName             = "table_";
    var dialog            = "add-edit-form";
    var colum_number      = 11;
    var main_act          = "get_list";
    var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl>";
    var sms_id            = 0;
     
    $(document).ready(function () {
    	GetButtons("add_button","delete_button");
    	GetDate('start_date');
    	GetDate('end_date');
    	LoadTable('index',colum_number,main_act,change_colum_main);
    	SetEvents("add_button", "delete_button", "", tName+'index', dialog, aJaxURL);
    	$('#operator_id,#tab_id,#user_info_id').chosen({ search_contains: true });
    	$('.callapp_filter_body').css('display','block');
    	$("#go_exel").button();

    	    $.session.clear(); 
    	    $.session.set("checker_st", "1");
    	    runAjax();

    	    <?php if($_SESSION['USERGR'] == 6 && $_SESSION['EXTENSION'] == 0){echo "$('#flesh_panel').css('display','none');";}else{if($_SESSION['USERGR'] == 6){echo "$('#add_button').css('display','none');";}}?>
    });
    
    function LoadTableLog(){
		
		/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
		GetDataTable(tName+'index_log', aJaxURL, "get_list_log", 8, "start_date="+$('#start_date').val()+'&end_date='+$('#end_date').val(), 0, "", 2, "desc", "", change_colum_main);
		setTimeout(function(){
	    	$('.ColVis, .dataTable_buttons').css('display','none');
	    	}, 90);
	}

    function LoadTable(tbl,col_num,act,change_colum){
    	param 				= new Object();
    	param.start_date=$('#start_date').val();
    	param.end_date=$('#end_date').val();
    	param.operator_id=$('#operator_id').val();
    	param.tab_id=$('#tab_id').val();
    	param.user_info_id=$('#user_info_id').val();
    	param.filter_1=$('#filter_1:checked').val();
    	param.filter_2=$('#filter_2:checked').val();
    	param.filter_3=$('#filter_3:checked').val();
    	param.filter_4=$('#filter_4:checked').val();
    	param.filter_5=$('#filter_5:checked').val();
    	param.filter_6=$('#filter_6:checked').val();
    	param.filter_7=$('#filter_7').val();
    	GetDataTableSD(tName+tbl,
    	    	aJaxURL,
    	    	act,
    	    	col_num,
    	    	param,
    	    	0,
    	    	"",
    	    	1,
    	    	"desc",
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
	    	}, 90);
    	$('.display').css('width','100%');
    }

    $(document).on("click", ".ui-icon-closethick", function () {
		tinymce.remove("#answer");
	});
    
    function LoadDialog(fName){
        if(fName == 'add-edit-form'){
    	var buttons = {
				"save": {
		            text: "Save",
		            id: "save-dialog"
		        },
	        	"cancel": {
		            text: "Close",
		            id: "cancel-dialog",
		            click: function () {
		            	var buttons2 = {
		        				"save": {
		        		            text: "Yes",
		        		            id: "save-dialog",
		        		            click: function () {
		        		            	$('#yesnoclose').dialog("close");
		        		            }
		    			        },"cancel": {
		        		            text: "No",
		        		            id: "no-cc",
		        		            click: function () {
		        		            	$('#'+fName).dialog("close");
		        		            	$('#yesnoclose').dialog("close");
		        		            }
		    			        }
		    			        }
		            	GetDialog("yesnoclose","300","auto",buttons2);
		            }
		        }
		    };
    	GetDialog(fName, 1200, "auto", buttons, 'left+43 top');
    	LoadTable1('history',7,'get_list_history',"<'F'lip>",'',aJaxURL);
        LoadTable1('sms',5,'get_list_sms',"<'F'lip>",'incomming_id='+$('#incomming_id').val(),aJaxURL);
        LoadTable1('mail',5,'get_list_mail',"<'F'lip>",'incomming_id='+$('#incomming_id').val(),aJaxURL);
        LoadTable1('question',3,'get_list_quest',"<'F'lip>",'',aJaxURL);
        SetEvents("", "", "", tName+'history', 'add-edit-form-task', "server-side/call/outgoing/outgoing_task.action.php?im_inc=1");
        //SetEvents("", "", "", 'table_question', 'add-edit-form-quest', "server-side/view/queries.action.php");
        que('table_question', 'add-edit-form-quest', "server-side/view/queries.action.php");
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
		if($("#incomming_phone").val()==''){

		}else{
			$("#source_info_id option[value='1']").attr('selected','selected');
		}
		<?php if($_SESSION['USERGR'] == 6 && $_SESSION['EXTENSION'] == 0){echo "$('#source_info_id option[value=\'1\']').remove();";}else{if($_SESSION['USERGR'] == 6){echo "$('#source_info_id option[value=\'2\'],#source_info_id option[value=\'4\']').remove();";}}?>
		$('#task_departament_id,#task_recipient_id,#task_status_id,#source_info_id,#service_center_id,#in_district_id,#branch_id,#in_type_id,#incomming_cat_1,#incomming_cat_1_1,#incomming_cat_1_1_1,#inc_status_id').chosen({ search_contains: true });
		$('#task_departament_id_chosen,#task_recipient_id_chosen,#task_status_id_chosen').css('width','240px');

        }else if(fName == 'add-edit-form-task'){
	    	var buttons = {
		        	"cancel": {
			            text: "Close",
			            id: "cancel-dialog",
			            click: function () {
			            	$('#'+fName).dialog("close");
			            }
			        }
			    };
	    	GetDialog(fName, 535, "auto", buttons, 'left+43 top');
	    }else if(fName == 'add-edit-form-quest'){
    	   var buttons = {
				
	        	"cancel": {
		            text: "Close",
		            id: "cancel-dialog",
		            click: function () {
		            	tinymce.remove("#answer");
		            	$('#'+fName).dialog("close");
		            }
		        }
		    };
	    	GetDialog(fName, 1270, "auto", buttons, 'left+43 top');
	    	tinymce.init({selector:'#answer'});
	    }
        $('.ui-widget-overlay').css('z-index',99);
    } 

    $(document).on("click", ".scenar", function () {
		///log
    	param 				= new Object();
    	param.act		    = 'menu_clicks';
		param.menu_name		= 'billing';
		param.row_id    	= $('#hidden_id').val();
		
        $.ajax({
            url: aJaxURL,
            data: param,
            success: function(data) {
            }
        });

		//log
        
    	if(location.hostname == '37.143.152.21'){
    		linkurl = '37.143.152.21';
    	}else{
    		linkurl = '192.168.0.33';
    	}
    	
    	Site = 'http://'+linkurl+':8181/client-side/call/biling.php?service_center_id='+$("#service_center_id").val();


    	goVisitSite(Site)
    	
    	//$("#scenar").html('<legend>Billing</legend><iframe src="http://'+linkurl+':8181/client-side/call/biling.php?service_center_id='+$("#service_center_id").val()+'" style="width: 875px; height:600px;"></iframe>');
    	//setTimeout(function(){ window.stop(); }, 1500);
    });

    $(document).on("click", ".box", function () {
    	///log
    	param 				= new Object();
    	param.act		    = 'menu_clicks';
		param.menu_name		= 'cancelary';
		param.row_id    	= $('#hidden_id').val();
		
        $.ajax({
            url: aJaxURL,
            data: param,
            success: function(data) {
            }
        });

		//log
        Site = 'http://192.168.0.167:8080/zem/home';
        goVisitSite(Site)
    	//$("#box").html('<legend>Chancellery</legend><span class="hide_said_menu">x</span><iframe src="http://192.168.0.167:8080/zem/home" style="width: 875px; height:600px;"></iframe>');
    	//setTimeout(function(){ window.stop(); }, 1000);
    });

    function goVisitSite(Site)
	{
	NewWindow1 = window.open(Site,
	"viewwin",

	"toolbar=0,menubar=0,width=1300,height=650,top=0,le ft=0,scrollbars=1,resizable=yes");
	}
	
    $(document).on("click", ".ui-icon-closethick", function () {
		tinymce.remove("#answer");
	});
    
    $(document).on("click", ".callapp_refresh", function () {
    	//LoadTable('index',colum_number,main_act,change_colum_main);
    	$( "#show_table" ).click();
    });

    $(document).on("click", "#copy_phone", function () {
        $('#sms_phone').val($('#incomming_phone').val());
    });

    $(document).on("click", "#go", function () {
    	LoadTable1('history',7,'get_list_history',"<'F'lip>",'start_check='+$("#start_check").val()+'&end_check='+$('#end_check').val()+'&check_ab='+$('#check_ab').val()+'&cl_ab_num='+$('#cl_ab_num').val()+'&task_status_ck='+$('#task_status_ck').val(),aJaxURL);
    	$.ajax({
            url: aJaxURL_service,
            data: 'ab_num='+$("#cl_ab_num").val(),
            success: function(data) {
                if($("#cl_ab_num").val() == data.custNumber){
                	$("#cl_addres").val('')
                    $("#cl_debt").val('');
                    $("#cl_ab").val('');
                    $("#cl_addres").val(data.address)
                    $("#cl_debt").val(data.balance);
                    $("#cl_ab").val(data.custName);
                }else{
                	$("#cl_addres").val('')
                    $("#cl_debt").val('');
                    $("#cl_ab").val('');
                    alert('Subscriber Number Not Found!');
                }
            }
        });
    });

    ///////////////looooooog
    
    $(document).on("keyup", "#cl_ab_num, #cl_ab", function () {
        
    	param 				= new Object();
    	param.act		= 'log';
		param.cl_ab_num_value		= $('#cl_ab_num').val();
		param.cl_ab_value    		= $('#cl_ab').val();
		param.row_id    	= $('#hidden_id').val();
		
		
        $.ajax({
            url: aJaxURL,
            data: param,
            success: function(data) {
                console.log(param.row_id);
            }
        });
    });

    $(document).on("change", "#cl_ab_num, #cl_ab", function () {
            
        	param 				= new Object();
        	param.act		= 'log';
    		param.cl_ab_num_value		= $('#cl_ab_num').val();
    		param.cl_ab_value    		= $('#cl_ab').val();
    		param.row_id    	= $('#hidden_id').val();
    		
    		
            $.ajax({
                url: aJaxURL,
                data: param,
                success: function(data) {
                    console.log(param.row_id);
                }
            });
    });

    //////////////
    
    $(document).on("change", "#incomming_cat_1", function () {
    	param 			= new Object();
		param.act		= "cat_2";
		param.cat_id    = $('#incomming_cat_1').val();
        $.ajax({
            url: aJaxURL,
            data: param,
            success: function(data) {
                $("#incomming_cat_1_1").html(data.page);
                $('#incomming_cat_1_1').trigger("chosen:updated");
                if($('#incomming_cat_1_1 option:selected').val()==999){
                	param 			= new Object();
            		param.act		= "cat_3";
            		param.cat_id    = $('#incomming_cat_1_1').val();
                    $.ajax({
                        url: aJaxURL,
                        data: param,
                        success: function(data) {
                            $("#incomming_cat_1_1_1").html(data.page);
                            $('#incomming_cat_1_1_1').trigger("chosen:updated");
                        }
                    });
                }
            }
        });
    });
    $(document).on("change", "#task_departament_id", function () {
    	param 			          = new Object();
		param.act		          = "task_dep";
		param.task_departament_id = $('#task_departament_id').val();
        $.ajax({
            url: aJaxURL,
            data: param,
            success: function(data) {
                $("#task_recipient_id").html(data.get_dep_user);
                $('#task_recipient_id').trigger("chosen:updated");
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
                $('#incomming_cat_1_1_1').trigger("chosen:updated");
            }
        });
    });
    $(document).on("change", "#service_center_id", function () {
    	param 			= new Object();
		param.act		= "sc";
		param.cat_id    = $('#service_center_id').val();
        $.ajax({
            url: aJaxURL,
            data: param,
            success: function(data) {
                $("#branch_id").html(data.page);
                $("#in_district_id").html(data.page_l);
                $('#branch_id,#in_district_id').trigger("chosen:updated");
            }
        });
    });

    $(document).on("click", "#next_quest", function () {
    	$('#back_quest').attr('back_id',$(".quest_body:visible").attr("id"));
        var input_radio    = '';
        var input_checkbox = '';
        var input          = '';
        var select         = '';
        input_radio = $('#' + $(this).attr('next_id') + ' .radio_input:checked').attr('next_quest');
        input_checkbox = $('#' + $(this).attr('next_id') + ' .check_input:checked').attr('next_quest');
        input = $('#' + $(this).attr('next_id') + ' input[type="text"]').attr('next_quest');
        select = $('#' + $(this).attr('next_id') + ' .hand_select').attr('next_quest');
        if(input_radio == undefined){
            
        }else{
        	$('.quest_body').css('display','none');
        	$('#'+input_radio).css('display','block');
        	$('#next_quest').attr('next_id',input_radio);
        	$('#back_quest').prop('disabled',false);
        	if(input_radio == 0){
        		$('.last_quest').css('display','block');
        		$('#next_quest').prop('disabled',true);
        	}
        }
        if(input == undefined){
        	
        }else{
            if(input==0){
            	$('#next_quest').prop('disabled',true);
            	$('.quest_body').css('display','none');
            	$('.last_quest').css('display','block');
            }else{
            $('.quest_body').css('display','none');
        	$('#'+input).css('display','block');
        	$('#next_quest').attr('next_id',input);
            }
        }
        
        if(input_checkbox == undefined){
            
        }else{
        	$('.quest_body').css('display','none');
        	$('#'+input_checkbox).css('display','block');
        	$('#next_quest').attr('next_id',input_checkbox);
        }
        if(select == undefined){
            
        }else{
        	$('.quest_body').css('display','none');
        	$('#'+select).css('display','block');
        	$('#next_quest').attr('next_id',select);
        }
    });

    $(document).on("click", "#back_quest", function () {
    	$('#next_quest').prop('disabled',false);
    	$('#next_quest').attr('next_id',$(".quest_body:visible").attr("id"));
    	
    	var str = $(".quest_body:visible").attr("class");
    	if(str == undefined){
    		var res = parseInt($(this).attr('back_id')) + 1;
    	}else{
    		var res = str.replace("quest_body ", "");
    	}
    	back_id = (res-1);
    	if(back_id<1){
    		back_id = 1;
    		$('#back_quest').prop('disabled',true);
    	}
    	$('.quest_body,.last_quest').css('display','none');
    	$('.'+back_id).css('display','block');
    });

    $(document).on("change", "#operator_id,#user_info_id", function () {
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
    $(document).on("click", "#dzebna", function () {
    	if ($('#filter_7').val() != '') {
    	    $.session.set("filter_7", "on");
    	}else{
    		$.session.remove('filter_7');
    	}
    	my_filter();
    });



    if(new Date($("#task_start_date").val()) <= new Date($("#task_end_date").val()))
    {
    alert(123)
    }

    $(document).on("change", "#task_start_date", function () {
        if($("#task_start_date").val() != '' && $("#task_end_date").val() != ''){
        	if(new Date($("#task_start_date").val()) > new Date($("#task_end_date").val()))
            {
        	    alert('Enter the correct time!');
            }
        }
    });

    $(document).on("change", "#task_end_date", function () {
    	if($("#task_start_date").val() != '' && $("#task_end_date").val() != ''){
        	if(new Date($("#task_start_date").val()) > new Date($("#task_end_date").val()))
            {
        		alert('Enter the correct time!');
            }
        }
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
    		myhtml += '<span>Inc. Unanswered<close cl="filter_3">X</close></span>';
    	}
    	if($.session.get("filter_4")=='on'){
    		myhtml += '<span>Meeting<close cl="filter_4">X</close></span>';
    	}
    	if($.session.get("filter_5")=='on'){
    		myhtml += '<span>Internet<close cl="filter_5">X</close></span>';
    	}
    	if($.session.get("filter_6")=='on'){
    		myhtml += '<span>Phone<close cl="filter_6">X</close></span>';
    	}
    	if($.session.get("filter_7")=='on'){
    		myhtml += '<span>Applicant<close cl="filter_7">X</close></span>';
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
        $(".box").children('img').attr('src','media/images/icons/box.png');
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
        GetDialog("add-edit-form-sms", 360, "auto", buttons, 'center top');
    });
    
    $(document).on("click", "#callapp_show_filter_button", function () {
        if($('.callapp_filter_body').attr('myvar') == 0){
        	$('.callapp_filter_body').css('display','block');
        	$('.callapp_filter_body').attr('myvar',1);
        	$('#add_button').css('top','285px');
        	$('#go_exel').css('top','264px');
        	$("#shh").css('background-position','0 0px');      	
        }else{
        	$('.callapp_filter_body').css('display','none');
        	$('.callapp_filter_body').attr('myvar',0);
        	$('#add_button').css('top','172px');
        	$('#go_exel').css('top','171px');
        	$("#shh").css('background-position','0 9px');  
        }        
    });

    function listen(file){
        var url = 'http://'+location.hostname + ":8000/" + file;
        $("#record audio source").attr('src',url);
        $("#record audio").load();
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
            alert("Allowed only 'pdf', 'png', 'xls', 'xlsx', 'jpg', 'docx', 'doc', 'csv' Extension");
        }else if(file_size > '15728639'){
            alert("File size over 15MB");
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
//         // for non-IE
//         if (!window.ActiveXObject) {
//             var save = document.createElement('a');
//             save.href = fileURL;
//             save.target = '_blank';
//             save.download = fileName || 'unknown';

//             var event = document.createEvent('Event');
//             event.initEvent('click', true, true);
//             save.dispatchEvent(event);
//             (window.URL || window.webkitURL).revokeObjectURL(save.href);
//         }
// 	     // for IE
//         else if ( !! window.ActiveXObject && document.execCommand)     {
//             var _window = window.open(fileURL, "_blank");
//             _window.document.close();
//             _window.document.execCommand('SaveAs', true, fileName || fileURL)
//             _window.close();
//         }
    	var iframe = document.createElement("iframe"); 
        iframe.src = fileURL; 
        iframe.style.display = "none"; 
        document.body.appendChild(iframe);
        return false;
    }

    function LoadDialog_shablon(){
		var button = {
           		"save": {
           			text: "Update",
           			id: "shablon_dialog",
           			click: function () {
           			}
           		}
			};

		/* Dialog Form Selector Name, Buttons Array */
		GetDialogCalls('smsShablon', 330, "auto", button, 'center top');
		
    }
    
    $(document).on("click", "#sms_shablon", function () {
		 LoadDialog_shablon();
		 $('#shablon_dialog').click();
	});

    $(document).on("click", "#shablon_dialog", function () {
	 	param 			= new Object();
	 	param.act		= "get_shablon";

    	$.ajax({
	        url: aJaxURL,
		    data: param,
	        success: function(data) {
				if(typeof(data.error) != 'undefined'){
					if(data.error != ''){
						alert(data.error);
					}else{
						$("#smsShablon").html(data.shablon);
						$( ".insert_shablon" ).button({
						});
					}
				}
		    }
	    });

    });
    $(document).on("click", ".open_dialog", function () {

    	var number 		= $(this).attr("number");
    	var extention 	= $(this).attr("extention");
    	//queoue 			= $(queoue).text();
    	
    	param 			= new Object();
	 	param.act		= "check_user";
	 	param.extention	= extention;

	 	
    	$.ajax({
	        url: aJaxURL,
		    data: param,
	        success: function(data) {
				if(typeof(data.error) != 'undefined'){
					if(data.error != ''){
						alert(data.error);
					}else{
						if(data.check == 1 && number != ""){
							$.ajax({
					            url: aJaxURL,
					            type: "POST",
					            data: "act=get_edit_page&id=&open_number=" + number,
					            dataType: "json",
					            success: function (data) {
					                if (typeof (data.error) != "undefined") {
					                    if (data.error != "") {
					                        alert(data.error);
					                    } else {
					                    	$("#add-edit-form").html('');
					                        $("#add-edit-form").html(data.page); 
					                    	LoadDialog('add-edit-form');
					                    }
					                }
					            }
					        });
					    }
					}
				}
		    }
	    });
	});
//     $(document).on("click", ".open_dialog", function () {
//     	var queoue = $($(this).siblings())[0];
//     	queoue = $(queoue).text();
//     	if($(this).text() !=''){
//         $.ajax({
//             url: aJaxURL,
//             type: "POST",
//             data: "act=get_edit_page&id=&open_number=" + $(this).text() + "&queue=" + queoue,
//             dataType: "json",
//             success: function (data) {
//                 if (typeof (data.error) != "undefined") {
//                     if (data.error != "") {
//                         alert(data.error);
//                     } else {
//                     	$("#add-edit-form").html('');
//                         $("#add-edit-form").html(data.page); 
//                     	LoadDialog('add-edit-form');
//                     }
//                 }
//             }
//         });
//     	}
//     });
    
    $(document).on("click", "#show_all_scenario", function () {
        if($(this).attr('who') == 0){            
        //$('#scenar').css('overflow-y','scroll');
        $('.quest_body').css('display','block');
        $('#next_quest').prop('disabled', true);
        $(this).attr('who',1);
        $('#show_all_scenario span').text('სცენარის მიხედვით');
        $('#back_quest,#next_quest').css('display','none');
        $('.quest_body').attr('style','height: 130px;border: 1px solid #CCCCCC;padding: 0 10px;float: left;margin-right: 5px;width: 260px;margin-top: 5px;');
        $('.myhr,.last_quest').css('display','none');
        }else{
        	$('#next_quest').attr('next_id',$('.1').attr('id'));
        	$('.quest_body').attr('style','');
        	//$('#scenar').css('overflow-y','visible');
            $('.quest_body').css('display','none');
            $('.1').css('display','block');
            $('#next_quest').prop('disabled', false);
            $(this).attr('who',0);
            $('#show_all_scenario span').text('Allს ჩვენება');
            $('#back_quest,#next_quest').css('display','block');
            $('.myhr').css('display','block');
        }
    });
    
    $(document).on("click", "#show_flesh_panel", function () {
    	$("#check_state").val(0);
        $( "#flesh_panel" ).animate({
            width: "550px"
          }, 1400 );
        $('#show_flesh_panel').attr('src','media/images/icons/arrow_right.png');
        $('#show_flesh_panel').attr('id','show_flesh_panel_right');
        $('#flesh_panel_table_mini').css('display','none');
        $('#flesh_panel_table').css('display','block');
        $('#flesh_panel').css('z-index','99');
        $('#show_flesh_panel_right').attr('title','პანელის დაპატარევება');
        $.session.set("checker_st", "2");
    });
    $(document).on("click", "#show_flesh_panel_right", function () {
    	$("#check_state").val(1);
        //$('#flesh_panel').css('width','150px');
        $( "#flesh_panel" ).animate({
            width: "150px"
          }, 800 );
        $('#show_flesh_panel_right').attr('src','media/images/icons/arrow_left.png');
        $('#show_flesh_panel_right').attr('id','show_flesh_panel');
        $('#flesh_panel_table_mini').css('display','block');
        $('#flesh_panel').css('z-index','99');
        $('#show_flesh_panel').attr('title','პანელის გადიდება');
        $.session.set("checker_st", "1");
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
		param.source_info_id = $("#source_info_id").val();
		param.service_center_id = $("#service_center_id").val();
		param.branch_id = $("#branch_id").val();
		param.in_district_id = $("#in_district_id").val();
		param.in_type_id = $("#in_type_id").val();
		param.cl_id = $("#cl_id").val();
		param.cl_name = $("#cl_name").val();
		param.cl_ab = $("#cl_ab").val();
		param.cl_ab_num = $("#cl_ab_num").val();
		param.cl_addres = $("#cl_addres").val();
		param.cl_phone = $("#cl_phone").val();
		param.cl_debt = $("#cl_debt").val();
		
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
		if ($('#task_send').is(':checked')) {
			  param.task_send			    = 1;
		}else{
			  param.task_send			    = 0;
		}
		
		var link = GetAjaxData(param);
		    ab_num = $('#cl_ab_num').val().length;
			if($('#incomming_cat_1').val() == 0 || $('#incomming_cat_1_1').val() == 0 || $('#incomming_cat_1_1_1').val() == 0 || $('#source_info_id').val() == 0 || $('#in_type_id').val() == 0 || $('#inc_status_id').val() == 0 || $('#service_center_id').val() == 0 || ($('#cl_ab_num').val() != '' && ab_num < 10)){
				$("#service_center_id_chosen,#source_info_id_chosen,#incomming_cat_1_chosen,#incomming_cat_1_1_chosen,#incomming_cat_1_1_1_chosen,#in_type_id_chosen,#inc_status_id_chosen").css('border','0');
				if($('#cl_ab_num').val() != '' && ab_num < 10){
					alert('Customersს ნომერი შედგება ზუსტად 10 ციფრისგან!')
				}
				if($('#inc_status_id').val() == 0){
					$("#inc_status_id_chosen").css('border','1px solid red');
				}
				if($('#service_center_id').val() == 0){
					$("#service_center_id_chosen").css('border','1px solid red');
				}
				if($('#incomming_cat_1').val() == 0){
				    $("#incomming_cat_1_chosen").css('border','1px solid red');
				}
				if($('#incomming_cat_1_1').val() == 0){
				    $("#incomming_cat_1_1_chosen").css('border','1px solid red');
				}
				if($('#incomming_cat_1_1_1').val() == 0){
				    $("#incomming_cat_1_1_1_chosen").css('border','1px solid red');
				}
				if($('#source_info_id').val() == 0){
				    $("#source_info_id_chosen").css('border','1px solid red');
				}
				if($('#in_type_id').val() == 0){
				    $("#in_type_id_chosen").css('border','1px solid red');
				}
			}else{
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
		   }
	});
    function runAjax() {   	
        $.ajax({
        	async: false,
        	dataType: "html",
	        url: 'AsteriskManager/liveState.php',
		    data: 'sesvar=hideloggedoff&value=true&stst=1&checkState='+$("#check_state").val(),
	        success: function(data) {
				$("#flesh_panel_table").html(data);	
			}
        }).done(function(data) { 
            setTimeout(runAjax, 1000);
        });        
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
							alert('შეტყობინება წარმატებით გაიგზავნა!');
							$("#mail_text").val('');
							$("iframe").contents().find("body").html('');
							$("#file_div_mail").html('');
							CloseDialog("add-edit-form-mail");
							LoadTable1('mail',5,'get_list_mail',"<'F'lip>",'incomming_id='+$('#incomming_id').val(),aJaxURL);
						}else{
							alert('შეტყობინება არ გაიგზავნა!');
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
            alert("დაშვებულია მხოლოდ 'pdf', 'png', 'xls', 'xlsx', 'jpg', 'docx', 'doc', 'csv' გაფართოება");
        }else if(file_size > '15728639'){
            alert("ფაილის ზომა 15MB-ზე მეტია");
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
								tbody += "<div id=\"tree_div\" onclick=\"download_file('" + data.page[i].rand_name + "','"+data.page[i].name+"')\">ჩამოტვირთვა</div>";
								tbody += "<div id=\"for_div\" onclick=\"delete_file1('" + data.page[i].id + "')\">-</div>";
								$("#paste_files1").html(tbody);								
							}							
						}						
					}					
			    }
		    });
        }
    });

    $(document).on("keyup  paste", "#sms_text", function () {
      	 var sms_text = $('#sms_text').val(); 
      	  isValid(sms_text);
      	$('#simbol_caunt').val((sms_text.length)+'/150');
    });

    function isValid(str){
	     var check = false;
	     for(var i=0;i<str.length;i++){
	         if(str.charCodeAt(i)>127){
	        	 check = true;
	          }
	     }
	     if(check){
	    	 var string = $('#sms_text').val();
	    	 var replaced = string.replace(/[^\x00-\x7F]/g, "");
	    	 $('#sms_text').val(replaced);
	    	 alert('Noსწორი სიმბოლო');
		 }   
	 }

    // sms sender ^_^
    $(document).on("click", "#send_sms", function (fName) {
    	
	    param 			= new Object();

	    param.sms_hidde_id	= sms_id;
    	param.phone			= $("#sms_phone").val();
    	param.text			= $("#sms_text").val();
    	param.sms_inc_increm_id	= $("#hidden_id").val();
    	
    	 $.ajax({
		        url: aJaxURL_send_sms,
			    data: param,
		        success: function(data) {
					$("#sms_text").val('');
					alert('SMS წარმატებით გაიგზავნა');
					LoadTable1('sms',5,'get_list_sms',"<'F'lip>",'incomming_id='+$('#incomming_id').val(),aJaxURL);
					CloseDialog("sms_dialog");
			    }
		    });
		});

    $(document).on("click", ".download_shablon", function () {
    	var test = $(this).attr("number");
    	sms_id   = $(this).attr("sms_id");
    	
    	console.log(test);
    	if(test != ""){
    		$('#sms_text').val(test);
    		$('#simbol_caunt').val((test.length)+'/150');
	    }
    	CloseDialog("smsShablon");
    });

    $(document).on("click", "#show_log", function () {
    	LoadTableLog();
    	$("#table_index_wrapper,#table_index,#add_button").css('display','none');
    	$("#table_index_log_wrapper").css('display','block');
    	$("#table_index_log").css('display','table');
    	
    	$(this).css('background','#2681DC');
        $(this).children('img').attr('src','media/images/icons/log_w.png');
	
		$("#show_table").css('background','#E6F2F8');
        $("#show_table").children('img').attr('src','media/images/icons/table.png');
    	
    });

    $(document).on("click", "#show_table", function () {
    	LoadTable('index',colum_number,main_act,change_colum_main);
    	$("#table_index_log_wrapper,#table_index_log").css('display','none');
    	$("#table_index_wrapper,#add_button").css('display','block');
    	$("#table_index").css('display','table');
    	
    	$("#show_log").css('background','#E6F2F8');
        $("#show_log").children('img').attr('src','media/images/icons/log.png');

        $(this).css('background','#2681DC');
        $(this).children('img').attr('src','media/images/icons/table_w.png');
    });

    $(document).on("click", "#go_exel", function () {
    	param 				= new Object();
    	param.start_date=$('#start_date').val();
    	param.end_date=$('#end_date').val();
    	param.operator_id=$('#operator_id').val();
    	param.tab_id=$('#tab_id').val();
    	param.filter_1=$('#filter_1:checked').val();
    	param.filter_2=$('#filter_2:checked').val();
    	param.filter_3=$('#filter_3:checked').val();
    	param.filter_4=$('#filter_4:checked').val();
    	param.filter_5=$('#filter_5:checked').val();
    	param.filter_6=$('#filter_6:checked').val();
    	param.filter_7=$('#filter_7:checked').val();
        $.ajax({
            url: 'server-side/call/export.php',
    	    data: param,
    	    success: function(data) {
            	SaveToDisk('server-side/call/excel.xls', 'excel.xls');
    	    }
        });
    });
    
    function SaveToDisk(fileURL, fileName) {
//         // for non-IE
//         if (!window.ActiveXObject) {
//             var save = document.createElement('a');
//             save.href = fileURL;
//             save.target = '_blank';
//             save.download = fileName || 'unknown';

//             var event = document.createEvent('Event');
//             event.initEvent('click', true, true);
//             save.dispatchEvent(event);
//             (window.URL || window.webkitURL).revokeObjectURL(save.href);
//         }
// 	     // for IE
//         else if ( !! window.ActiveXObject && document.execCommand)     {
//             var _window = window.open(fileURL, "_blank");
//             _window.document.close();
//             _window.document.execCommand('SaveAs', true, fileName || fileURL)
//             _window.close();
//         }
    	var iframe = document.createElement("iframe"); 
        iframe.src = fileURL; 
        iframe.style.display = "none"; 
        document.body.appendChild(iframe);
        return false;
    } 
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
#table_mail_length,
#table_question_length,
#table_history_length{
	position: inherit;
    width: 0px;
	float: left;
}
#table_mail_length label select,
#table_question_length label select,
#table_history_length label select{
	width: 60px;
    font-size: 10px;
    padding: 0;
    height: 18px;
}
#table_mail_paginate,
#table_question_paginate{
	margin: 0;
}
#table_index_wrapper #table_index_filter{
    width: 54%;
    margin-left: 95px;
	margin-top: 9px;
}
.callapp_filter_show button{
	margin-bottom: 0;
}
#table_right_menu{
	top: 60px;
}
#table_index tbody td:last-child {
    padding: 0;
}
#table_index thead th:last-child .DataTables_sort_wrapper{
    display: none;
}
</style>
</head>

<body>
<div id="tabs">
<div class="callapp_head">Incoming Communication <span class="callapp_refresh"><img alt="refresh" src="media/images/icons/refresh.png" height="14" width="14">   Update</span><hr class="callapp_head_hr"></div>
<div class="callapp_tabs">

</div>
<div class="callapp_filter_show">
<button id="callapp_show_filter_button">Filter <div id="shh" style="background: url('media/images/icons/show.png'); width: 24px; height: 9px;background-position: 0 0px;margin-top: 5px;float: right;"></div></button>
    <div class="callapp_filter_body" myvar="1">
    <div style="float: left; width: 310px;">
        <span>
        <label for="start_date" style="margin-left: 110px;">- From</label>
        <input class="callapp_filter_body_span_input" type="text" id="start_date" style="width: 100px;">
        </span>
        <span>
        <label for="end_date" style="margin-left: 110px;">- Up to</label>
        <input class="callapp_filter_body_span_input" type="text" id="end_date" style="width: 100px;">
        </span>
        <span style="margin-top: 15px;">
        <select id="operator_id" style="width: 285px;">
        <?php
        include '../../includes/classes/core.php';
        $user_gr = $_SESSION['USERGR'];
        $user_id = $_SESSION['USERID'];
        $data    = '';
        if($user_gr == 1 || $user_gr == 2){
            $check_user = "";
            $select = "";
            $data .= '<option value="0">All Operator</option>';
        }else{
            $check_user = "AND `users`.`id` = $user_id";
            $select = 'selected="selected"';
        }
        $res = mysql_query("SELECT  `users`.`id`,
				                    `user_info`.`name`
                            FROM    `users`
                            JOIN    `user_info` ON `users`.`id` = `user_info`.`user_id`
                            WHERE   `actived` = 1 $check_user");
        
        while ($req = mysql_fetch_array($res)){
            $data .= '<option value="'.$req[0].'" '.$select.'>'.$req[1].'</option>';
        }
        echo $data;
        ?>
        </select>
        </span>
        <span style="margin-top: 15px;">
        <select id="tab_id" style="width: 285px;">
        <option value="0">All Call</option>
        <option value="1">Transferred Out</option>
        <option value="2">Clarifying the process of</option>
        <option value="3">Completed</option>
        </select>
        </span>
        </span>
        <span style="margin-top: 15px;">
        <select id="user_info_id" style="width: 285px;">
        <option value="1">My Appeals</option>
        <option value="2">My Branch</option>
        <option value="3">Services Center</option>
        <option value="4">All</option>
        </select>
        </span>
    </div>
    <div style="float: left; width: 170px; margin-left: 20px;">
        <span >
        <div class="callapp_filter_header"><img alt="inc" src="media/images/icons/inc_call.png" height="14" width="14">  Incoming Calls</div>
        </span>
        <span style="margin-left: 15px">        
        <label for="filter_1">Treated</label>
        <div class="callapp_checkbox" style="margin-top: -16px;margin-left: -15px;">
          <input class="callapp_filter_body_span_input" id="filter_1" type="checkbox" value="1"/>
          <label for="filter_1" style="background: #4CAE50;"></label>
        </div>
        </span>
        <span style="margin-left: 15px">
        <label for="filter_2" style="">Untreated</label>
        <div class="callapp_checkbox" style="margin-top: -16px;margin-left: -15px;">
          <input class="callapp_filter_body_span_input" id="filter_2" type="checkbox" value="2"/>
          <label for="filter_2" style="background: #ECAF00;"></label>
        </div>
        </span>
        <span style="margin-left: 15px">
        <label for="filter_3">Unanswered</label>
        <div class="callapp_checkbox" style="margin-top: -16px;margin-left: -15px;">
          <input class="callapp_filter_body_span_input" id="filter_3" type="checkbox" value="3"/>
          <label for="filter_3" style="background: #F44336;"></label>
        </div>
        </span>        
        </div>
    <div style="float: left; width: 170px;">
        <span >
        <div class="callapp_filter_header"><img alt="out" src="media/images/icons/scenar_blue.png" height="14" width="14">  Method</div>
        </span>
        <span style="margin-left: 15px">
        <label for="filter_4" style="width: 60px;">Meeting</label>
        <input class="callapp_filter_body_span_input" id="filter_4" type="checkbox" value="4">
        </span>
        <span style="margin-left: 15px">
        <label for="filter_5" style="width: 60px;">Internet</label>
        <input class="callapp_filter_body_span_input" id="filter_5" type="checkbox" value="5">
        </span>
        <span style="margin-left: 15px">
        <label for="filter_6" style="width: 60px;">Phone</label>
        <input class="callapp_filter_body_span_input" id="filter_6" type="checkbox" value="6">
        </span>
        </div>
    <div style="float: left; width: 145px;">
        <span>
        <div class="callapp_filter_header"><img alt="inner" src="media/images/icons/info_blue.png" height="14" width="14">  Applicant</div>
        </span>
        <span style="margin-left: 15px">        
        <label for="filter_7">Answered</label>
        <input class="callapp_filter_body_span_input" id="filter_7" type="text">
        <button style="border: 1px solid;cursor: pointer;" id="dzebna">Search</button>
        </span>
       
        
    </div>
    <div style="float: left; width: 145px; display: none;">
        <span>
        <div class="callapp_filter_header"><img alt="inner" src="media/images/icons/inner_call_1.png" height="14" width="14">  Internal call</div>
        </span>
        <span style="margin-left: 15px">        
        <label for="filter_8">Answered</label>
        <input class="callapp_filter_body_span_input" id="filter_8" type="checkbox" value="8">
        </span>
        <span style="margin-left: 15px">
        <label for="filter_9">Unanswered</label>
        <input class="callapp_filter_body_span_input" id="filter_9" type="checkbox" value="9">
        </span>
        
    </div>
</div>
<button id="add_button" style="font-size: 11px;border: 1px solid #A3D0E4;background: #E6F2F8;position: absolute;top: 285px;z-index: 1;left: 105px;">Add</button>
<table style="position: absolute;top: 1px;width: 222px;left: 68%;">
<tr>
<td><button id="go_exel" style="font-size: 11px;border: 1px solid #A3D0E4;background: #E6F2F8;position: absolute;top: 285px;z-index: 1;">Excele Import</button></td>
</tr>
</table>

<table id="table_right_menu">
<tr>
<td style="cursor: pointer;padding: 4px;border-right: 1px solid #A3D0E4;background:#2681DC;" id="show_table" myvar="0"><img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
</td>
<td style="cursor: pointer;padding: 4px;border-right: 1px solid #A3D0E4;" id="show_log" myvar="0"><img alt="log" src="media/images/icons/log.png" height="14" width="14">
</td>
<td style="cursor: pointer;padding: 4px;border-right: 1px solid #A3D0E4;" id="show_copy_prit_exel" myvar="0"><img alt="link" src="media/images/icons/select.png" height="14" width="14">
</td>
</tr>
</table>
<div class="clear"></div>
<table class="display" id="table_index" style="width: 100%;">
    <thead>
        <tr id="datatable_header">
            <th>ID</th>
            <th style="width: 46px;">№</th>
            <th style="width: 120px;">Date</th>
            <th style="width: 120px;">Phone</th>
            <th style="width: 25%;">Customers</th>
            <th style="width: 25%;">Customers Phone</th>
            <th style="width: 25%;">Service Center</th>
            <th style="width: 25%;">Category</th>
            <th style="width: 25%;">Responses</th>
            <th style="width: 25%;">Listen</th>
            <th style="width: 5px;">&nbsp;</th>
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
                <input type="text" name="search_category" value="Filter" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_phone" value="Filter" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_category" value="Filter" class="search_init" />
            </th>  
            <th>
            </th>           
        </tr>
    </thead>
</table>

    <table class="display" id="table_index_log" style="display: none;">
        <thead>
            <tr id="datatable_header">
                <th>ID</th>
                <th style="width: 7%;">№</th>
                <th style="width: 15%;">Actions Date</th>
                <th style="width: 15%;">Actions</th>
                <th style="width: 15%;">Consumer</th>
                <th style="width: 15%;">Field</th>
                <th style="width: 15%;">Old Value</th>
                <th style="width: 20%;">New Value</th>
            </tr>
        </thead>
        <thead>
            <tr class="search_header">
                <th class="colum_hidden">
                    <input type="text" name="search_category" value="Filter" class="search_init" />
                </th>
                <th>
                    <input type="text" name="search_category" value="Filter" class="search_init" />
                </th>
                <th>
                    <input type="text" name="search_category" value="Filter" class="search_init" />
                </th>
                <th>
                    <input type="text" name="search_category" value="Filter" class="search_init" />
                </th>
                <th>
                    <input type="text" name="search_category" value="Filter" class="search_init" />
                </th>
                <th>
                    <input type="text" name="search_category" value="Filter" class="search_init" />
                </th>
                <th>
                    <input type="text" name="search_category" value="Filter" class="search_init" />
                </th>
                <th>
                    <input type="text" name="search_category" value="Filter" class="search_init" />
                </th>
            </tr>
        </thead>
    </table>
</div>
<style>
#flesh_panel_table, #flesh_panel_table_mini{
	box-shadow: 0px 0px 7px #888888;
}

#flesh_panel_table td, #flesh_panel_table_mini td {
	height: 25px;	
	vertical-align: middle;
	text-align: left;
	padding: 0 5px;
	background: #FFF;
	
}
.tb_head td{
	border-right: 1px solid #E6E6E6;	
}
#show_flesh_panel,#show_flesh_panel_right{
    float: left;
	cursor: pointer;
}
.td_center{
    text-align: center !important;
}
</style>
<div id="flesh_panel">
    <div class="callapp_head" style="text-align: right;"><img id="show_flesh_panel" title="Larger panel" alt="arrow" src="media/images/icons/arrow_left.png" height="18" width="18">Call Centre<hr class="callapp_head_hr"></div>
    <table id="flesh_panel_table" style="margin-bottom: 60px;"></table>
</div>

	<input id="check_state" style="display: none" type="text" name="search_category" value="1" class="search_init" />

<!-- jQuery Dialog -->
<div  id="add-edit-form" class="form-dialog" title="Incoming Communication">
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

<div id="smsShablon" title="SMS Template">
</div>
<!-- jQuery Dialog -->
<div  id="add-edit-form-task" class="form-dialog" title="Task">
</div>
<!-- jQuery Dialog -->
<div  id="add-edit-form-quest" class="form-dialog" title="Question">
</div>
	
</body>