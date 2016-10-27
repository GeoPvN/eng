<html>
<head>
	<script type="text/javascript">
		var aJaxURL	          = "server-side/view/handbook.action.php";
		var tName	          = "table_";
		var fName	          = "add-edit-form";
		var colum_number      = 2;
		var main_act          = "get_list";
		var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl>";
		var lenght = [[10, 30, 50, -1], [10, 30, 50, "ყველა"]];
		    	
		$(document).ready(function () {        	
			LoadTable('index',colum_number,main_act,change_colum_main,'');	
 						
			/* Add Button ID, Delete Button ID */
			GetButtons("add_button", "delete_button");			
			SetEvents("add_button", "delete_button", "check-all", tName+'index', fName, aJaxURL);
		});

		function LoadTable(tbl,col_num,act,change_colum,custom_param){
	    	GetDataTable(tName+tbl, aJaxURL, act, col_num, custom_param, 0, "", 1, "asc", '', change_colum);
	    	setTimeout(function(){
    	    	$('.ColVis, .dataTable_buttons').css('display','none');
  	    	}, 90);
	    }

		function LoadDialog(){
			var id		= $("#id").val();
			
			GetDialog(fName, 600, "auto", "","top top");
			LoadTable('detail',colum_number,'get_list_detail',"<'F'lip>",'id='+id);
			MyEvent(   aJaxURL,  'add_button_detail', 'delete_button_detail', 'check-all-de', '-detail', 'save-detail', 'cancel-dialog',      480,       'top top',  'get_add_page_detail', 'disable_detail', 'get_edit_page_detail',  'detail',   2,        'get_list_detail', "<'F'lip>",    '',  'id='+id,        '','id_original='+id,'id_original='+id);
			
		}
		
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
		    param 			= new Object();

		    param.act		="save";
	    	param.id		= $("#id").val();
	    	param.name		= $("#name").val();
	    	param.new_str	= $("#id").attr('new');
	    	
			if(param.name == ""){
				alert("შეავსეთ ველი!");
			}else {
			    $.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {			        
						if(typeof(data.error) != 'undefined'){
							if(data.error != ''){
								alert(data.error);
							}else{
								LoadTable('index',colum_number,main_act,change_colum_main,'');
				        		CloseDialog(fName);
							}
						}
				    }
			    });
			}
		});
		 // Add - Save
	    $(document).on("click", "#save-detail", function () {
		    param 			= new Object();

		    param.act		  = "save_detail";
		    param.id_detail   = $("#id_detail").val();
	    	param.id_original = $("#id_original").val();
	    	param.value		  = $("#value").val();
	    	
			if(param.name == ""){
				alert("შეავსეთ ველი!");
			}else {
			    $.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {			        
						if(typeof(data.error) != 'undefined'){
							if(data.error != ''){
								alert(data.error);
							}else{
								var id		= $("#id").val();
								LoadTable('detail',colum_number,'get_list_detail',"<'F'lip>",'id='+id);
				        		CloseDialog('add-edit-form-detail');
							}
						}
				    }
			    });
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
	        	$(this).css('background','#FAFAFA');
	            $(this).children('img').attr('src','media/images/icons/select.png');
	            $(this).attr('myvar','0');
	        }
	    });
	   
    </script>
    <style type="text/css">
        #table_right_menu{
            position: relative;
            float: right;
            width: 70px;
            top: 42px;
        	z-index: 99;
        	border: 1px solid #E6E6E6;
        	padding: 4px;
        }
        
        .ColVis, .dataTable_buttons{
        	z-index: 100;
        }
        .callapp_head{
        	font-family: pvn;
        	font-weight: bold;
        	font-size: 20px;
        	color: #2681DC;
        }
        #table_detail_length{
        	position: inherit;
            width: 0px;
        	float: left;
        }
        #table_detail_length label select{
        	width: 60px;
            font-size: 10px;
            padding: 0;
            height: 18px;
        }
        #table_detail_paginate{
        	margin: 0;
        }
    </style>
</head>

<body>
<div id="tabs">
<div class="callapp_head">სცენარის ცნობარი<hr class="callapp_head_hr"></div>
<div id="button_area">
	<button id="add_button">დამატება</button>
	<button id="delete_button">წაშლა</button>
</div>
<table id="table_right_menu">
<tr>
<td style="cursor: pointer;padding: 4px;border-right: 1px solid #E6E6E6;background:#2681DC;"><img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
</td>
<td style="cursor: pointer;padding: 4px;border-right: 1px solid #E6E6E6;"><img alt="log" src="media/images/icons/log.png" height="14" width="14">
</td>
<td style="cursor: pointer;padding: 4px;" id="show_copy_prit_exel" myvar="0"><img alt="link" src="media/images/icons/select.png" height="14" width="14">
</td>
</tr>
</table>
    <table class="display" id="table_index">
        <thead>
            <tr id="datatable_header">
                <th>ID</th>
                <th style="width: 100%;">სახელი</th>
            	<th class="check">#</th>
            </tr>
        </thead>
        <thead>
            <tr class="search_header">
                <th class="colum_hidden">
                <th>
                    <input type="text" name="search_category" value="ფილტრი" class="search_init" />
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

    
    <!-- jQuery Dialog -->
    <div id="add-edit-form" class="form-dialog" title="სცენარის ცნობარი">
    	<!-- aJax -->
	</div>
	<div id="add-edit-form-detail" class="form-dialog" title="სცენარის ცნობარის პარამეტრები">
    	<!-- aJax -->
	</div>
</body>
</html>