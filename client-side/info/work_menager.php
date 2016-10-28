
<html>
<head>

<script type="text/javascript">
var aJaxURL	= "server-side/info/work_menager.action.php";

$(document).ready(function(){
	$('#year_month').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm',
        onClose: function(dateText, inst) {
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
    });
    
	param 			= new Object();
	param.act		= "get_project";
    $.ajax({
        url: aJaxURL,
        data: param,
        success: function(data) {
            $("#project_id").html(data.project);
            $('#project_id').chosen({ search_contains: true });
            param 			= new Object();
        	param.act		= "get_cycle_start_date";
        	param.year_month	= $("#year_month").val();
            $.ajax({
                url: aJaxURL,
                data: param,
                success: function(data) {
                    $("#cycle_start_date").html(data.cycle_start_date);
                    $('#cycle_start_date').chosen({ search_contains: true });
                    $('#cycle_start_date_chosen').css('width','190px');
                    $('#cycle_start_date_chosen').css('z-index','999');
                    $("#cycle_start_date").trigger("chosen:updated");
                    $('#start_date').css('overflow','inherit');
                }
            });
        }
    });
});

function deletecycle(rigi,start,end,project_id){
	var user_id  = $('#user_id_'+rigi).val();
	var cycle_id = $('#'+rigi).val();
	param 			  = new Object();
	param.act		  = "delete_cycle";
	param.user_id	  = user_id;
	param.cycle_id	  = cycle_id;
	param.project_id  = project_id;
	param.start		  = start;
	param.end		  = end;
	param.rigi        = 'rigi'+rigi;
    $.ajax({
        url: aJaxURL,
        data: param,
        success: function(data) {
        	load_table();
        }
    });
}

function load_table(){
	param 			    = new Object();
	param.act		    = "get_table";
	param.project_id	= $("#project_id").val();
	param.year_month	    = $("#year_month").val();
	param.add_new_line	    = $("#add_new_line").attr('value');
    $.ajax({
        url: aJaxURL,
        data: param,
        success: function(data) {
            $("#time_line").html(data.table);
            $("#test").html(data.ttt);
            var total_hour = 0;
            var day_hour = 0;
            for(i = 1;i <= data.num;i++){
                for(g = 1;g <= data.day;g++){
                    total_hour += parseInt($("td[vertikal='"+i+"'][horizontal='"+g+"']").attr('hour'));
                }
                //alert(total_hour);
                $(".total_"+i).html(toHHMMSS(total_hour));
                total_hour = 0;
            }
            for(i = 1;i <= data.day;i++){
                for(g = 1;g <= data.num;g++){
                	day_hour += parseFloat($("td[vertikal='"+g+"'][horizontal='"+i+"']").attr('hour'));
                }
                //alert(day_hour);
                $(".qveda_dge_"+i).html(toHHMMSS(day_hour));
                tes1 = diff($(".qveda_dge_geg_"+i).html(),$(".qveda_dge_"+i).html());

                
                $(".qveda_dge_sx_"+i).html(tes1);
                if(parseInt($(".qveda_dge_sx_"+i).html()) > 0){
                	$(".qveda_dge_sx_"+i).css('background','green');
                }else{
                	$(".qveda_dge_sx_"+i).css('background','red');
                }
                day_hour = 0;
            }
            $('select[user_num]').chosen({ search_contains: true });
            param 			= new Object();
        	param.act		= "get_cycle_start_date";
        	param.year_month	= $("#year_month").val();
            $.ajax({
                url: aJaxURL,
                data: param,
                success: function(data) {
                    $("#cycle_start_date").html(data.cycle_start_date);
                    $("#cycle_start_date").trigger("chosen:updated");
                }
            });
            $('#ParentContainer').scroll(function() { 
			    $('#FixedDiv').css('margin-left', 310+$(this).scrollLeft());
			});
        }
    });
}

function toHHMMSS(time) {
    var sec_num = parseInt(time, 10); // don't forget the second param
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    return hours+':'+minutes;
    //+':'+seconds;
}

function diff(start, end) {
    start = start.split(":");
    end = end.split(":");
    var startDate = new Date(0, 0, 0, start[0], start[1], 0);
    var endDate = new Date(0, 0, 0, end[0], end[1], 0);
    var diff = endDate.getTime() - startDate.getTime();
    var hours = Math.floor(diff / 1000 / 60 / 60);
    diff -= hours * 1000 * 60 * 60;
    var minutes = Math.floor(diff / 1000 / 60);

    if(hours < 0){
    	return (Math.abs(hours) < 9 ? "-0" : "-") + Math.abs(hours) + ":" + (minutes < 9 ? "0" : "") + minutes;
    }else{
    	return (hours < 9 ? "0" : "") + hours + ":" + (minutes < 9 ? "0" : "") + minutes;
    }
    
}

$(document).on("change", "#project_id", function () {
	load_table();
});

$(document).on("click", "#add_new_line", function () {
	var next_val = parseInt($(this).attr('value')) + 1;
	$(this).attr('value',next_val)
	load_table();
});

$(document).on("click", "#del_new_line", function () {
	var now_val = parseInt($('#add_new_line').attr('value'));
	if(now_val > 0){
		var next_val = now_val - 1;
		$('#add_new_line').attr('value',next_val)
		load_table();
	}
});

$(document).on("change", "#year_month", function () {
	load_table();
});

function opendialog(work_shift,color,date,rigi_num,work_real_id){
	if(color != 'red'){
		var buttons = {
				"save": {
		            text: "შენახვა",
		            id: "save-dialog",
		            click: function () {
		            	param 			    = new Object();
		            	param.act		    = "add_update_shift";
		            	param.project_id	= $("#project_id").val();
		            	param.work_shift	= work_shift;
		            	param.shift_id      = $("#shift_id").val();
		            	param.rigi_num      = rigi_num;
		            	param.date          = date;
		                $.ajax({
		                    url: aJaxURL,
		                    data: param,
		                    success: function(data) {
		                        $("#add-edit-form").dialog("close");
		                        load_table();
		                    }
		                });
		            }
		        },
	        	"cancel": {
		            text: "დახურვა",
		            id: "cancel-dialog",
		            click: function () {
		            	$(this).dialog("close");
		            }
		        }
		    };
        GetDialog("add-edit-form", 735, "auto", buttons, 'center top');
        param 			    = new Object();
    	param.act		    = "get_shift";
    	param.project_id	= $("#project_id").val();
    	param.work_shift	= work_shift;
        $.ajax({
            url: aJaxURL,
            data: param,
            success: function(data) {
                $("#shift_id").html(data.shift);
            }
        });
        GetDataTable('table_hist', aJaxURL, "get_list_hist", 6, "work_real_id="+work_real_id, 0, "", 1, "desc", "", "<'dataTable_buttons'T><'F'Cfipl>");
	}
}

$(document).on("change", ".cycle", function () {
	if($("#user_id_"+$(this).attr('id')).val()==0){
		alert('miutitet operatori');
	}else{
		var user_id  = $("#user_id_"+$(this).attr('id')).val();
		var cycle_id = $(this).val();
		var user_num = $(this).attr('user_num');
		var tarigi   = $(this).attr('tarigi');
		var str      = $("option:selected",this).attr('shift');
		var res      = str.split(",");
		var total    = $(res).size();
		var m        = 0;
		var insert   = '';
		$("#cheker").attr('rigi','rigi'+$(this).attr('id'));
		$("#cheker").attr('user',$("#user_id_"+$(this).attr('id')).val());
		
		var buttons = {
				"save": {
		            text: "შენახვა",
		            id: "save-dialog",
		            click: function () {
			            m = (parseInt($("#cvlis_nomeri").val())-1);

		            	$( "td[rigi_num='"+user_num+"'][holy='none']" ).each(function( index ) {
		        			if(parseInt($(this).attr('horizontal')) > (parseInt($("#cycle_start_date").val())-1)){
		        		    insert += "('"+user_id+"', '"+$(this).attr('tarigi')+"', '"+res[m]+"', '"+user_num+"', '"+$('#project_id').val()+"', '"+cycle_id+"', '"+<?php echo $_SESSION['USERID'];?>+"'),";
		        		    if(m == (total-1)){
		        		        m = 0;
		        		    }else{
		                        m++;
		        		    }
		        			}
		        		});

		        		param 			= new Object();
		        		param.act		= "add_real";
		        		param.insert	= insert;

		        	    $.ajax({
		        	        url: aJaxURL,
		        	        data: param,
		        	        success: function(data) {
		        	        	load_table();
		        	        	$("#start_date").dialog("close");
		        	        }
		        	    });
		            	
		            }
		        },
	        	"cancel": {
		            text: "დახურვა",
		            id: "cancel-dialog",
		            click: function () {
		            	$(this).dialog("close");
		            }
		        }
		    };
		GetDialog("start_date", 250, "auto", buttons, 'center top');
		
	}
});

function openhour(date,date1,id){
	if(id == undefined){
		id = 1;
	}
	param 			    = new Object();
	param.act		    = "get_24_hour";
	param.project_id	= $("#project_id").val();
	param.date	        = date;
	param.date1         = $("td[tarigi1='"+date+"']").attr('tarigi_back');
	param.new_viwe      = id;
	
    $.ajax({
        url: aJaxURL,
        data: param,
        success: function(data) {
        	
            var buttons = {
                	"cancel": {
                        text: "დახურვა",
                        id: "cancel-dialog",
                        click: function () {
                        	$(this).dialog("close");
                        	load_table();
                        }
                    }
            };
            GetDialog("wfm_hour", 1130, "auto", buttons, 'center top');
            $('#wfm_hour').html(data.page);
            $('#select_viwe').chosen({ search_contains: true });
        }
    });
}

$(document).on("change", "#select_viwe", function () {
	openhour($('#load_date').val(),'',$(this).val());
	
});
function LoadTable(tbl,col_num,act,change_colum,custom_param,URL){
	GetDataTable('table_'+tbl, URL, act, col_num, custom_param, 0, "", 1, "asc", '', change_colum);

	$('.display').css('width','100%');
}

$(document).on("change", "#work_activities_id", function () {
	param 			         = new Object();
	param.act		         = "get_work_activities_detail";
	param.work_activities_id = $(this).val();

    $.ajax({
        url: aJaxURL,
        data: param,
        success: function(data) {
            $('#work_activities_detail_id').html(data.selector);
        }
    });
});

$(document).on("change", "#work_activities_detail_id", function () {
	param 			         = new Object();
	param.act		         = "paste_date";
	param.work_activities_detail_id = $(this).val();

    $.ajax({
        url: aJaxURL,
        data: param,
        success: function(data) {
            $('#start_break').val(data.paste.start);
            $('#end_break').val(data.paste.end);
        }
    });
});

$(document).on("click", ".user_break", function () {
    var user_name = $(this).html()
	var buttons = {
        	"cancel": {
	            text: "დახურვა",
	            id: "cancel-dialog",
	            click: function () {
	            	openhour($("#load_date").val());
	            	$(this).dialog("close");
	            }
	        }
	    };
	GetDialog("add_break", 690, "auto", buttons, 'center top');

	param 			         = new Object();
	param.act		         = "get_user_break";
	param.work_real_id	     = $(this).attr('work_real_id');
	param.work_real_break_id = $(this).attr('work_real_break_id');

    $.ajax({
        url: aJaxURL,
        data: param,
        success: function(data) {
            $("#add_break").html(data.break);
            $("#add_break legend").html(user_name);
            GetButtons("add_button_user", "delete_button_user");
            LoadTable('index',4,'get_index',"<'F'lip>",'work_real_id='+$('#r_id').val(),aJaxURL);
        	SetEvents("add_button_user", "delete_button_user", "check-all-index", 'table_index', 'add-edit-form-user', aJaxURL);
        }
    });

});

function LoadDialog(fname){
	var buttons = {
			"save": {
	            text: "შენახვა",
	            id: "save-dialog",
	            click: function () {
	            	param 		      = new Object();
	            	param.act	      = "add_user_break";
	            	param.r_id	      = $("#r_id").val();
	            	param.b_id        = $("#b_id").val();
	            	param.start_break = $("#start_break").val();
	            	param.end_break   = $("#end_break").val();
	            	param.work_activities_id = $("#work_activities_id").val();

	                $.ajax({
	                    url: aJaxURL,
	                    data: param,
	                    success: function(data) {
		                    if(data.error!=1){
		                    	LoadTable('index',4,'get_index',"<'F'lip>",'work_real_id='+$('#r_id').val(),aJaxURL);
		                    	$("#"+fname).dialog("close");
		                    }else{
		                        alert('შუალედი ცდება მიმდინარე სამუშაო გრაფიკსი, მიუთითეთ კორექტული შუალედი!');
		                    }
	                    }
	                });
	            }
	        },
        	"cancel": {
	            text: "დახურვა",
	            id: "cancel-dialog",
	            click: function () {
	            	//openhour($("#load_date").val());
	            	$(this).dialog("close");
	            }
	        }
	    };
	GetDialog(fname, 230, "auto", buttons, 'center top');
	$('#start_break, #end_break').timepicker({
    	hourMax: 23,
		hourMin: 00,
		minuteMax: 59,
		minuteMin: 00,
		stepMinute: 5,
		minuteGrid: 15,
		hourGrid: 3,
    	dateFormat: '',
        timeFormat: 'HH:mm'
    });
}
$(document).on("click", "#delete_button_user", function () {
	LoadTable('index',4,'get_index',"<'F'lip>",'work_real_id='+$('#r_id').val(),aJaxURL);
});
$(document).on("change", "#cheker", function () {
	param 		      = new Object();
	param.act	      = "checker";
	param.project_id  = $("#project_id").val();
	param.rigi        = $(this).attr('rigi');
	param.user        = $(this).attr('user');

    $.ajax({
        url: aJaxURL,
        data: param,
        success: function(data) {
            if(data.error!=1){
            	$("#cvlis_nomeri").val(data.num);
            	if($('#cvlis_nomeri').prop('disabled') == true){
         		   $('#cvlis_nomeri').prop('disabled', false);
          		   $("#cvlis_nomeri").val(1);
            	}else{
            		$('#cvlis_nomeri').prop('disabled', true);
            	}
            }else{
                alert('შუალედი ცდება მიმდინარე სამუშაო გრაფიკსი, მიუთითეთ კორექტული შუალედი!')
            }
        }
    });
});
</script>

<style type="text/css">
::-webkit-scrollbar {
    width: 12px;
	height: 15px;
}  
::-webkit-scrollbar-track {
    background-color: #CBD9E6;
    border-left: 1px solid #ccc;
}
::-webkit-scrollbar-thumb {
    background-color: #2681DC;
	border-radius: 12px;
}
::-webkit-scrollbar-thumb:hover {  
    background-color: #aaa;  
}  
#time_line td,#time_line1 td{   
   border:solid 1px #A3D0E4;
}
#pirveli td, #meore td, #mesame td,#qveda_meore td,#qveda_pirveli td,#qveda_meore1 td,#qveda_pirveli1 td{
	padding: 2px;
}
#pirveli, #meore, #mesame,#qveda_meore,#qveda_pirveli,#qveda_meore1,#qveda_pirveli1{
	width: 100%;
}
#work_table td, #work_table th {
    border: 1px solid;
    font-size: 11px;
    font-weight: normal;
    text-align: center;
}
#table_index_length
{
	position: inherit;
    width: 0px;
	float: left;
}
#table_index_length label select{
	width: 60px;
    font-size: 10px;
    padding: 0;
    height: 18px;
}
#table_index_paginate{
	margin: 0;
}
.chosen-single span, .chosen-drop ul li{
	font-size: 11px;
}
#time_line td {
	font-size: 12px;
}
td {position: relative;}
.comment1:after{
	content: "";
    position: absolute;
    left: calc(100% - 0.5em);
    top: 0;
    border-left: 0.5em solid transparent;
    border-top: 0.9em solid #000;
}
</style>
</head>
<body>
<div style="width: 1200px;margin: auto;margin-top: 50px;">

    <div id="container" style="width:100%;margin-bottom: 70px;">
    
        <select style="width: 210px;padding: 2px;border: solid 1px #85b1de;margin-right: 15px;"  id="project_id"></select>
        <!-- select style="width: 120px;padding: 2px;border: solid 1px #85b1de;margin-right: 15px;"  id="week_num">
        <option value="0">1 (28 დღე)</option>
        <option value="28">2 (28 დღე)</option>
        <option value="56">3 (28 დღე)</option>
        <option value="84">4 (28 დღე)</option>
        <option value="112">5 (28 დღე)</option>
        <option value="140">6 (28 დღე)</option>
        <option value="168">7 (28 დღე)</option>
        <option value="196">8 (28 დღე)</option>
        <option value="224">9 (28 დღე)</option>
        <option value="252">10 (28 დღე)</option>
        <option value="280">11 (28 დღე)</option>
        <option value="308">12 (28 დღე)</option>
        <option value="336">13 (28 დღე)</option>
        <option value="364">14 (28 დღე)</option>
        </select-->
        <input style="width: 60px;display: inline-block; height: 13px; position: relative;" id="year_month" value="<?php echo date('Y-m')?>" class="date1 inpt" placeholder="თარიღი"/>
        <br/>
        <div id="time_line" style="margin-top: 20px;"><div style="color: #2681DC;text-align: center; font-size: 14px; font-weight: bold;">აირჩიეთ პროექტი და თარიღი!</div></div>

    </div>
<div id="test"></div>
<div id="add-edit-form" class="form-dialog" title="ცვლის დამატება">
<div id="dialog-form">
    <fieldset>
        <legend>ცვლა</legend>
        <select style="width: 190px;margin-bottom: 25px;" id="shift_id"></select>
        <table class="display" id="table_hist">
        <thead>
            <tr id="datatable_header">
                <th>ID</th>
                <th style="width: 50%;">ცვლილების თარიღი</th>
                <th style="width: 50%;">ცვლილების სტატუსი</th>
                <th style="width: 50%;">ცვლილების ავტორი</th>
                <th style="width: 50%;">ცვლილებამდე</th>
                <th style="width: 50%;">ცვლილების შემდეგ</th>
            </tr>
        </thead>
        <thead>
            <tr class="search_header">
                <th class="colum_hidden">
                    <input type="text" name="search_category" value="ფილტრი" class="search_init" />
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
                    <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                </th>
                <th>
                    <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                </th>
            </tr>
        </thead>
    </table>
    </fieldset>
</div>
</div>
<div id="start_date" class="form-dialog" title="ციკლის დაწყების თარიღი">
<div id="dialog-form">
    <fieldset style="height: 80px;">
        <legend>ციკლის დაწყების თარიღი</legend>
        <select style="width: 190px;" id="cycle_start_date"></select>
        <input type="number" id="cvlis_nomeri" min="1" max="22" style="margin-top: 15px;float: left;" value="1">
        <label style="float: left;margin-left: 70px;margin-top: -25px;width: 105px;" for="cheker">გააგრძელე ციკლი წინა თვიდან</label><input type="checkbox" id="cheker" style="margin-top: -28px; margin-left: 180px;float:left;">
    </fieldset>
</div>
</div>
<div id="wfm_hour" class="form-dialog" title="საათების მიხედვით">
</div>
<div id="add_break" class="form-dialog" title="შესვენების დამატება">
</div>
<div id="add-edit-form-user" class="form-dialog" title="შესვენების დამატება">
</div>
</body>
</html>
