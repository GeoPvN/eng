<head>
	<script type="text/javascript">
		var aJaxURL		= "server-side/report/out_call.action.php";		//server side folder url
		var upJaxURL	= "server-side/upload/file.action.php";		        //server side upload folder url	
		var tName		= "table_";											//table name											//tabs name
		var fName		= "add-edit-form";										//form name
		var colum_number = 9;
		var main_act    = "get_list";
		var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl>";
		var dialog            = "add-edit-form";
		var file_name = '';
		var rand_file = '';
		
		$(document).ready(function () {
			GetDate('date_start');
			GetDate('date_end');
			LoadTable('index',colum_number,main_act,change_colum_main);
		});


		function LoadTable(tbl,col_num,act,change_colum){
			
			GetDataTable(tName+tbl,aJaxURL,act,col_num,"date_start="+$('#date_start').val()+"&date_end="+$('#date_end').val(),0,"",1,"desc",'',change_colum);
			setTimeout(function(){
		    	$('.ColVis, .dataTable_buttons').css('display','none');
		    	}, 90);
		}
		
		function LoadTableLog(){
			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName+'index_log', aJaxURL, "get_list_log", 8, "", 0, "", 2, "desc", "", change_colum_main);
			setTimeout(function(){
		    	$('.ColVis, .dataTable_buttons').css('display','none');
		    	}, 90);
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
                    	tinymce.remove("#action_content");
                    	$(this).dialog("close");
                    }
                } 
            };
            
            GetDialog("add-edit-form", 1230, "auto", buttons,'left+43 top');
		}

	    $(document).on("click", "#show_log", function () {
	    	LoadTableLog();
	    	$("#table_index_wrapper,#table_index,#button_area").css('display','none');
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
	    	$("#table_index_wrapper,#button_area").css('display','block');
	    	$("#table_index").css('display','table');
	    	
	    	$("#show_log").css('background','#E6F2F8');
	        $("#show_log").children('img').attr('src','media/images/icons/log.png');

	        $(this).css('background','#2681DC');
	        $(this).children('img').attr('src','media/images/icons/table_w.png');
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

	    $(document).on("change", "#date_start,#date_end", function () {
	    	LoadTable('index',colum_number,main_act,change_colum_main);
	    });
    </script>
</head>

<body>

<div id="tabs">
<div class="callapp_head">Outgoing calls<hr class="callapp_head_hr"></div>
<div class="callapp_tabs">

</div>

<div>
<input type="text" id="date_start" style="width: 70px;">-From
<input type="text" id="date_end" style="width: 70px;margin-left: 5px;">-Up to
</div>

<table id="table_right_menu" style="top: 28px;">
<tr>
<td style="cursor: pointer;padding: 4px;border-right: 1px solid #A3D0E4;background:#2681DC;" id="show_table" myvar="0"><img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
</td>
<td style="cursor: pointer;padding: 4px;border-right: 1px solid #A3D0E4;" id="show_log" myvar="0"><img alt="log" src="media/images/icons/log.png" height="14" width="14">
</td>
<td style="cursor: pointer;padding: 4px;border-right: 1px solid #A3D0E4;" id="show_copy_prit_exel" myvar="0"><img alt="link" src="media/images/icons/select.png" height="14" width="14">
</td>
</tr>
</table>
<table class="display" id="table_index" style="width: 100%;">
    <thead>
		<tr id="datatable_header">
           <th>ID</th>
			<th style="width:10%;">#</th>
			<th style="width:17%; word-break:break-all;">Call Date</th>
			<th style="width:14%; word-break:break-all;">Extension</th>
			<th style="width:15%; word-break:break-all;">Agent</th>
			<th style="width:13%; word-break:break-all;">Phone</th>
			<th style="width:17%; word-break:break-all;">Waiting time</th>
			<th style="width:17%; word-break:break-all;">Duration</th>
			<th style="width:25%; word-break:break-all;">Listen</th>
		</tr>
	</thead>
	<thead>
		<tr class="search_header">
			<th class="colum_hidden">
    			<input type="text" name="search_id" value="Filter" class="search_init" style="width: 10px"/>
    		</th>
			<th>
				<input style="width:100%;" type="text" name="search_overhead" value="Filter" class="search_init" />
			</th>
			<th>
				<input style="width:100%;" type="text" name="search_overhead" value="Filter" class="search_init" />
			</th>
			<th>
				<input style="width:100%;" type="text" name="search_partner" value="Filter" class="search_init" />
			</th>
			<th>
				<input style="width:100%;" type="text" name="search_sum_cost" value="Filter" class="search_init" />
			</th>
			<th>
				<input style="width:100%;" type="text" name="search_partner" value="Filter" class="search_init" />
			</th>
			<th>
				<input style="width:100%;" type="text" name="search_partner" value="Filter" class="search_init" />
			</th>
			<th> 
				<input style="width:100%;" type="text" name="search_sum_cost" value="Filter" class="search_init" />
			</th>
			<th>
				<input style="width:100%;" type="text" name="search_partner" value="Filter" class="search_init" />
			</th>
	    </tr>
	</thead>
</table>      
		
</body>

