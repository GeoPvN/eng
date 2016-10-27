<html>
<head>
<style type="text/css">
#in_form,.in_form-class{
	overflow: visible;
}
.green
	{
	background-color: rgba(255, 0, 29, 0.51) !important;
	color: #0D233A !important;
	font-size: 14px !important;
		
	}
	.ui-dropdownchecklist-selector{
		background: #FFF;
		padding: 3px 0;
	}
	.ui-dropdownchecklist-text{
		display: inline;
	}
	.ui-dropdownchecklist-item{
		background: #FFF !important;
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
#ddcl-info_category-ddw{
    z-index: 51 !important;
	width: 327px !important;
}
</style>
<script type="text/javascript" src="js/ui.dropdownchecklist.js"></script>
<script type="text/javascript">
var aJaxURL	= "server-side/report/all_report.action.php";		//server side folder url
var tName   = "report";
var start	= $("#search_start").val();
var end		= $("#search_end").val();
var colum   = "<'dataTable_buttons'T><'F'Cfipl>";
var dialog  = "add-edit-form";
$(document).ready(function() {
	GetDate("search_start");
	GetDate("search_end");
	$("#source_info,#service_center,#in_district,#in_type,#info_category,#info_category1,#info_category2").dropdownchecklist({ firstItemChecksAll: true, explicitClose: 'დახურვა',icon: {}, width: 150, onComplete : drawFirstLevel});
	$("#branch").dropdownchecklist({ firstItemChecksAll: true, explicitClose: 'დახურვა',icon: {}, width: 150, onComplete : filter_sc});
	
	drawFirstLevel();
});

$(document).on("change", "#search_start", function () 	{drawFirstLevel();});
$(document).on("change", "#search_end"  , function () 	{drawFirstLevel();});

function drawFirstLevel(){
	GetDataTable(tName, aJaxURL, 'get_list', 12,"start="+$("#search_start").val()+"&end="+$("#search_end").val()+"&source_info="+$("#source_info").val()+"&service_center="+$("#service_center").val()+"&in_district="+$("#in_district").val()+"&in_type="+$("#in_type").val()+"&branch="+$("#branch").val()+"&info_category="+$("#info_category").val()+"&info_category1="+$("#info_category1").val()+"&info_category2="+$("#info_category2").val(), 0, "", 2, "desc", '', colum);
	setTimeout(function(){
    	$('.ColVis, .dataTable_buttons').css('display','none');
    	}, 120);
	$('.display').css('width','100%');
	//SetEvents("", "", "", tName, dialog, "server-side/call/incomming.action.php");
}

function LoadDialog(fName){
	var buttons = {
			
        	"cancel": {
	            text: "დახურვა",
	            id: "cancel-dialog",
	            click: function () {
	            	$('#'+fName).dialog("close");
	            }
	        }
	    };
    GetDialog(fName, 1200, "auto", buttons, 'left+43 top');
    LoadTable1('sms',5,'get_list',"<'F'lip>",'',"server-side/call/incomming.action.php");
    LoadTable1('mail',5,'get_list_mail',"<'F'lip>",'incomming_id='+$('#incomming_id').val(),"server-side/call/incomming.action.php");
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
	$('#task_departament_id,#task_recipient_id,#task_status_id,#source_info_id,#service_center_id,#in_district_id,#branch_id,#in_type_id,#incomming_cat_1,#incomming_cat_1_1,#incomming_cat_1_1_1,#inc_status_id').chosen({ search_contains: true });
	$('#task_departament_id_chosen,#task_recipient_id_chosen,#task_status_id_chosen').css('width','240px');
	$('#inc_status_id_chosen').css('position','fixed');
    $('#inc_status_id_chosen').css('top','445px');
} 

function LoadTable1(tbl,col_num,act,change_colum,custom_param,URL){
	GetDataTable('table_'+tbl, URL, act, col_num, custom_param, 0, "", 2, "desc", '', change_colum);
	setTimeout(function(){
    	$('.ColVis, .dataTable_buttons').css('display','none');
    	}, 50);
	$('.display').css('width','100%');
}

$(document).on("click", "#show_copy_prit_exel", function () {
    
    if($(this).attr('myvar') == 0){
        $('.ColVis,.dataTable_buttons,#table_right_menu_content').css('display','block');
        $(this).css('background','#2681DC');
        $(this).children('img').attr('src','media/images/icons/select_w.png');
        $(this).attr('myvar','1');
    }else{
    	$('.ColVis,.dataTable_buttons,#table_right_menu_content').css('display','none');
    	$(this).css('background','#E6F2F8');
        $(this).children('img').attr('src','media/images/icons/select.png');
        $(this).attr('myvar','0');
    }
});

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
$(document).on("click", ".hide_said_menu", function () {
	$("#right_side fieldset").hide();    	
	$(".add-edit-form-class").css("width", "300");
    //$('#add-edit-form').dialog({ position: 'top' });
    hide_right_side();
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

function filter_sc(){
	var branches1  = $("#branch").val();

    $.ajax({
        url: 'server-side/report/check_sc.action.php',
        data: 'act=check_sc&ids='+branches1,
        success: function(data) {
        	$("#ddcl-service_center").remove();
            $("#service_center").html(data.test);
            $("#service_center").dropdownchecklist({ firstItemChecksAll: true, explicitClose: 'დახურვა',icon: {}, width: 150, zIndex: 111, onComplete : drawFirstLevel});
            drawFirstLevel();
        }
    });
}

</script>
</head>
<body>
	<div id="tabs" style="width: 90%; height: 1600px;">
		<div class="callapp_head">ყველა რეპორტი<hr class="callapp_head_hr"></div>
         
       <div id="button_area" style="margin: 3% 0 0 0;float: none;">
         <div class="left" style="width: 175px;">
           <input type="text" name="search_start" id="search_start" style="height: 13px;"  class="inpt right"/>
             </div>
            	<label for="search_start" class="left" style="margin:5px 0 0 3px">-დან</label>
             <div class="left" style="width: 185px;">
	            <input type="text" name="search_end" id="search_end" style="height: 13px;" class="inpt right" />
             </div>
            	<label for="search_end" class="left" style="margin:5px 0 0 3px">–მდე</label>
            </div>
            <div style="padding-left: 6px;">
                <div style="float: left;">
                <div>მეთოდი</div>
             	<select id="source_info" multiple="multiple">
    	            <option value="'all'" selected="selected">(ყველა)</option>
    	            <?php 
                   	 include '../../includes/classes/core.php';
                   	        $res = mysql_query("SELECT 	`id`,
                                        				`name`
                                                FROM `source_info`
                                                WHERE actived = 1");
                   	        while ($req = mysql_fetch_array($res)){
                   	            echo "<option value=\"$req[0]\">$req[1]</option>";
                   	        }
                   	 ?>
	            </select>
	            </div>
	            <div style="float: left; margin-left: 5px;">
	            <div>მომსახურების ცენტრი</div>
             	<select id="service_center" multiple="multiple">
    	            <option value="'all'" selected="selected">(ყველა)</option>
    	            <?php 
                   	        $res = mysql_query("SELECT 	`id`,
                                        				`name`
                                                FROM `service_center`
                                                WHERE actived = 1 AND parent_id = 0");
                   	        while ($req = mysql_fetch_array($res)){
                   	            echo "<option value=\"$req[0]\">$req[1]</option>";
                   	        }
                   	 ?>
	            </select>
	            </div>
	            <div style="float: left; margin-left: 5px;">
	            <div>უბანი</div>
             	<select id="in_district" multiple="multiple">
    	            <option value="'all'" selected="selected">(ყველა)</option>
    	            <?php 
                   	        $res = mysql_query("SELECT 	`id`,
                                        				`name`
                                                FROM `service_center`
                                                WHERE actived = 1 AND parent_id != 0");
                   	        while ($req = mysql_fetch_array($res)){
                   	            echo "<option value=\"$req[0]\">$req[1]</option>";
                   	        }
                   	 ?>
	            </select>
	            </div>
	            <div style="float: left; margin-left: 5px;">
	            <div>ტიპი</div>
             	<select id="in_type" multiple="multiple">
    	            <option value="'all'" selected="selected">(ყველა)</option>
    	            <?php 
                   	        $res = mysql_query("SELECT 	`id`,
                                        				`name`
                                                FROM `in_type`
                                                WHERE actived = 1");
                   	        while ($req = mysql_fetch_array($res)){
                   	            echo "<option value=\"$req[0]\">$req[1]</option>";
                   	        }
                   	 ?>
	            </select>
	            </div>
	            <div style="float: left; margin-left: 5px;">
	            <div>ფილიალი</div>
             	<select id="branch" multiple="multiple">
    	            <option value="'all'" selected="selected">(ყველა)</option>
    	            <?php 
                   	        $res = mysql_query("SELECT 	`id`,
                                        				`name`
                                                FROM `branch`
                                                WHERE actived = 1");
                   	        while ($req = mysql_fetch_array($res)){
                   	            echo "<option value=\"$req[0]\">$req[1]</option>";
                   	        }
                   	 ?>
	            </select>
	            </div>
	            <div style="float: left; margin-left: 5px; margin-right: 7px;">
	            <div>ზარის კატეგორია</div>
             	<select id="info_category" multiple="multiple">
    	            <option value="'all'" selected="selected">(ყველა)</option> 
    	            <?php 
                   	        $res = mysql_query("SELECT 	`id`,
                                        				`name`
                                                FROM `info_category`
                                                WHERE `actived` = 1 AND `parent_id` = 0");
                   	        while ($req = mysql_fetch_array($res)){
                   	            echo "<option value=\"$req[0]\">$req[1]</option>";
                   	        }
                   	 ?>
	            </select>
	            </div>
	            <div style="float: left;">
	            <div>ზარის ქვე-კატეგორია 1</div>
             	<select id="info_category1" multiple="multiple">
    	            <option value="'all'" selected="selected">(ყველა)</option>
    	            <?php 
                   	        $res = mysql_query("SELECT 	`id`,
                                        				`name`
                                                FROM `info_category`
                                                WHERE `actived` = 1 AND `parent_id` != 0 AND parent_id IN(11,12,13,47,53,57,60,61,62,63,64,65)");
                   	        while ($req = mysql_fetch_array($res)){
                   	            echo "<option value=\"$req[0]\">$req[1]</option>";
                   	        }
                   	 ?>
	            </select>
	            </div>
	            <div style="float: left; margin-left: 5px;">
	            <div>ზარის ქვე-კატეგორია 2</div>
             	<select id="info_category2" multiple="multiple">
    	            <option value="'all'" selected="selected">(ყველა)</option>
    	            <?php 
                   	        $res = mysql_query("SELECT 	`id`,
                                        				`name`
                                                FROM `info_category`
                                                WHERE `actived` = 1 AND `parent_id` != 0 AND parent_id NOT IN(11,12,13,47,53,57,60,61,62,63,64,65) GROUP BY `name`");
                   	        while ($req = mysql_fetch_array($res)){
                   	            echo "<option value=\"'$req[1]'\">$req[1]</option>";
                   	        }
                   	 ?>
	            </select>
	            </div>
	        </div>
		<br><br>
<table style="margin-top: 25px;" id="table_right_menu">
				<tr>
					<td>
						<img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
					</td>
					<td>
						<img alt="log" src="media/images/icons/log.png" height="14" width="14">
					</td>
					<td id="show_copy_prit_exel" myvar="0">
						<img alt="link" src="media/images/icons/select.png" height="14" width="14">
					</td>
				</tr>
			</table>
		 <table class="display" id="report" style="width: 100%">
                <thead>
                    <tr id="datatable_header">
                        <th>ID</th>
                        <th style="width: 46px;">№</th>
                        <th style="width: 120px;">თარიღი</th>
                        <th style="width: 120px;">ტელეფონი</th>
                        <th style="width: 25%;">აბონენტი</th>
                        <th style="width: 25%;">აბონენტის ნომერი</th>
                        <th style="width: 25%;">მ/ცენტრი</th>
                        <th style="width: 25%;">კატეგორია</th>
                        <th style="width: 25%;">რეაგირება</th>
                    </tr>
                </thead>
                <thead>
                    <tr class="search_header">
                        <th class="colum_hidden">
                    	   <input type="text" name="search_id" value="ფილტრი" class="search_init" />
                        </th>
                        <th>
                        	<input type="text" name="search_number" value="ფილტრი" class="search_init" />
                        </th>
                        <th>
                            <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                        </th>
                        <th>
                            <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                        </th>                         
                        <th>
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                        </th>            
                        <th>
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                        </th>
                        <th>
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                        </th>
                        <th>
                            <input type="text" name="search_phone" value="ფილტრი" class="search_init" />
                        </th>
                        <th>
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                        </th>            
                    </tr>
                </thead>
            </table>
<!-- jQuery Dialog -->
<div  id="add-edit-form" class="form-dialog" title="შემომავალი ზარი">
</div>
</body>
</html>

