<html>
<head>

<script type="text/javascript">
var aJaxURL	 = "server-side/info/work_graphic.action.php";
var aJaxURL1 = "server-side/info/work_graphic_breack.action.php";
var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl>";
var dey=2;
$(document).ready(function(){

	GetButtons("add", "dis");
	LoadTable();
	
	$(".menun").click(function(){
		dey=this.id;
		LoadTable();
		title=$(this).html();
		$("#add-edit-form").attr('title', title);
		$("#wek_h").html("'"+title+"'");
    });
    SetEvents("add", "dis", "check-all", "example", "add-edit-form", aJaxURL);
});

function LoadTable(){
	GetDataTable("example", aJaxURL, "get_list", 4, '', 0, "", 1, "desc","",change_colum_main);
	setTimeout(function(){
    	$('.ColVis, .dataTable_buttons').css('display','none');
    	}, 90);
}
function LoadTable_breack(){
	GetDataTable("example-brack", aJaxURL1, "get_list", 4, '&w_id=' + $("#id").val(), 0, "", 1, "desc","","<'F'lip>");
}

function LoadDialog(fname){
	switch(fname){
    	case "add-edit-form":
        	
    		GetDialog(fname,450,'auto');
    		
			$('.time').timepicker({
    			hourMax: 23,
    			hourMin: 0,
    			hourGrid: 3,
    			minuteGrid: 10

    		});
    		
    		break;
    	case "add-edit-form1":
    		var buttons = {
    				"save": {
    		            text: "შენახვა",
    		            id: "save-breack",
    		            click: function () {
    		            	if($("#start_breack").val()!="" && $("#end_breack").val()!=""){
        		            	$.getJSON(aJaxURL1,'act=break&w_id=' + $("#id").val() + '&id='+ $("#w_g_break").val() + '&start_b=' + $("#start_breack").val() + '&end_b=' + $("#end_breack").val(),
        						function(json) {});
        		            	$(this).dialog("close");
        		            	LoadTable_breack();
    		            	}else{
    		        			alert('მიუთითეთ კორექტული დრო');
    		        		}
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
    		GetDialog('add-edit-form1', 450, "auto", buttons);
    		
    		$('.time').timepicker({
    			hourMax: 23,
    			hourMin: 0,
    			hourGrid: 3,
    			minuteGrid: 10

    		});
    		
    		break;
	}
	LoadTable_breack();
	GetButtons("add_button_brack", "delete_button_brack");
	SetEvents("add_button_brack", "delete_button_brack", "check-all-b", "example-brack", "add-edit-form1", aJaxURL1, '','example-brack');
};

$(document).on("click", "#save-dialog", function () {

	var param= new Object();
	
	param.act 			= "save_dialog";
	param.id 			= $("#id").val();
	param.week_day_id   = dey;
	param.start 		= $("#start").val();
	param.end			= $("#end").val();
   
	if(param.start!="" && param.end!=""){
		
		var js = $.getJSON(aJaxURL, param, function(json) {
			
		});
		
		js.complete(function() {
			LoadTable();
			$("#add-edit-form").dialog("close");
		});
		
	}else{
		
		alert('მიუთითეთ კორექტული დრო');
		
	}

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
</script>

<style type="text/css">


.menun{
		cursor:pointer;
		padding: 6px 10px;
		width: 100px !important;
		display: block;
		margin: -3px;
}
#example-brack_length label select{
	width: 60px;
    font-size: 10px;
    padding: 0;
    height: 18px;
}
#example-brack_length{
	top: 3px;
}
</style>
</head>
<body>
<div id="tabs">
<div class="callapp_head">სამუშაო გრაფიკები<hr class="callapp_head_hr"></div>
<div class="callapp_tabs">
</div>

<div id="button_area" style="">
<button id="add">დამატება</button> 	<button id="dis">წაშლა</button>
</div>
         
<table id="table_right_menu" style="top:40px;">
<tr>
<td style="cursor: pointer;padding: 4px;border-right: 1px solid #A3D0E4;background:#2681DC;" id="show_table" myvar="0"><img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
</td>
<td style="cursor: pointer;padding: 4px;border-right: 1px solid #A3D0E4;" id="show_log" myvar="0"><img alt="log" src="media/images/icons/log.png" height="14" width="14">
</td>
<td style="cursor: pointer;padding: 4px;border-right: 1px solid #A3D0E4;" id="show_copy_prit_exel" myvar="0"><img alt="link" src="media/images/icons/select.png" height="14" width="14">
</td>
</tr>
</table>

<table class="display" id="example" style="width: 100%;">
    <thead>
        <tr id="datatable_header">
            <th>ID</th>
            <th style="width:50%">მუშაობის დასაწყისი</th>
            <th style="width:50%">სამუშაოს დასასრული</th>
            <th style="width: 30px">#</th>
        </tr>
    </thead>
    <thead>
        <tr class="search_header">
            <th class="colum_hidden">
            	<input type="text" name="search_id" value="ფილტრი" class="search_init" style=""/>
            </th>
			<th>
                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100px;"/>
            </th>

            <th>
                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100px;"/>
            </th>
            <th>
                <div class="callapp_checkbox">
                    <input type="checkbox" id="check-all" name="check-all" />
                    <label for="check-all"></label>
                </div>
            </th>      
        </tr>
    </thead>
</table>


    <div  id="add-edit-form" class="form-dialog" title="შეარჩიეთ გრაფიკი">
	</div>
	<div  id="add-edit-form1" class="form-dialog" title="შეარჩიეთ შესვენება">
	</div>
</body>
</html>
