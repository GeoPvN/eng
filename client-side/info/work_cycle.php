<html>
<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/info/work_cycle.action.php";		//server side folder url
		var tName	= "table_";													//table name
		var fName	= "add-edit-form";												//form name
		var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl>";
		    	
		$(document).ready(function () {        	
			LoadTable(1,5,'get_list',change_colum_main,'',1);	
 						
			/* Add Button ID, Delete Button ID */
			GetButtons("add_button", "delete_button");	
			GetButtons("add_button2");			
			SetEvents("add_button", "delete_button", "check-all", 'table_1', fName, aJaxURL,'',1,5,'get_list',change_colum_main,'',1);
		});
        
		function LoadTable(tbl,col_num,act,change_colum,lenght,other_act){

			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName+tbl, aJaxURL, act, col_num, "", 0, "", 1, "desc", "", change_colum);
			setTimeout(function(){
    	    	$('.ColVis, .dataTable_buttons').css('display','none');
  	    	}, 90);
		}
		
		function LoadDialog(fname){
			if(fname == 'add-edit-form'){
			var id		= $("#cycle_id").val();
			/* Dialog Form Selector Name, Buttons Array */
			var buttons = {
					"save": {
			            text: "Save",
			            id: "cycle_save",
			            click: function () {
			            	param 			    = new Object();

			    		    param.act		    = "update_cycle";
			    		    param.cycle_id      = $("#add-edit-form #cycle_id").val();
			    	    	param.name		    = $("#add-edit-form #name").val();
			    	    	param.project_id    = $("#add-edit-form #project_id").val();
			    	    	param.num           = $("#add-edit-form #num").val();
			    	    	
		    			    $.ajax({
		    			        url: aJaxURL,
		    				    data: param,
		    			        success: function(data) {			        
		    						if(typeof(data.error) != 'undefined'){
		    							if(data.error != ''){
		    								alert(data.error);
		    							}else{
		    								LoadTable(1,5,'get_list',change_colum_main,'',1);
		    								
		    							}
		    						}
		    				    }
		    			    });
			    	    	$(this).dialog("close");
			            }
			        },"cancel": {
			            text: "Close",
			            id: "cancel-dialog",
			            click: function () {
			            	$(this).dialog("close");
			            }
			        }
			    };
			GetDialog(fName, 810, "auto", buttons);
			GetButtons("add_button1", "delete_button1");	
			GetDataTable('table_2', aJaxURL, "get_list1&id="+id, 9, "", 0, "", 1, "asc", "", "");
			//SetEvents("add_button1", "delete_button1", "check-all1", 'example1', 'add-edit-form2', aJaxURL,'','example1',8,'get_list1&id='+id,'','',1);
			MyEvent(aJaxURL, "add_button1", "delete_button1", "check-all1", '2', 'save_detail', 'cancel-dialog', '225', '', 'get_add_page_detail&next_project='+$('#next_project').val(), 'delete_detail', 'get_edit_page_detail', '2', 8, 'get_list1&id='+id, '', 0, 'id='+id,'2','','');
			}
		}

		$(document).on("click", "#add_button2", function () {
			var buttons = {
					"save": {
			            text: "Save",
			            id: "save_dialog",
			            click: function () {
			            	param 			    = new Object();

			    		    param.act		    = "save_cycle";
			    	    	param.name		    = $("#add-edit-form1 #name").val();
			    	    	param.project_id    = $("#add-edit-form1 #project_id").val()
			    	    	
			    			if(param.name == ""){
			    				alert("Fill in the fields!");
			    			}else {
			    			    $.ajax({
			    			        url: aJaxURL,
			    				    data: param,
			    			        success: function(data) {			        
			    						if(typeof(data.error) != 'undefined'){
			    							if(data.error != ''){
			    								alert(data.error);
			    							}else{
			    								LoadTable(1,5,'get_list',change_colum_main,'',1);
			    								$('#add-edit-form').html('');
			    				            	$(this).dialog("close");
			    				            	$("#add_button").click();
			    							}
			    						}
			    				    }
			    			    });
			    			}
			    			
			            }
			        },"cancel": {
			            text: "Close",
			            id: "cancel-dialog",
			            click: function () {
			            	$(this).dialog("close");
			            }
			        }
			    };
			GetDialog("add-edit-form1", 240, "auto", buttons);
			param 			    = new Object();

		    param.act		    = "get_project";
	    	
		    $.ajax({
		        url: aJaxURL,
			    data: param,
		        success: function(data) {			        
					if(typeof(data.error) != 'undefined'){
						if(data.error != ''){
							alert(data.error);
						}else{
							$("#add-edit-form1 #project_id").html(data.page);
						}
					}
			    }
		    });
			
			$("#add-edit-form1 #name").val('');
		});
		
	    // Add - Save
	    $(document).on("click", "#save_detail", function () {
		    param 			    = new Object();

		    param.act		    = "save_detail";
	    	param.detail_id		= $("#detail_id").val();
	    	param.project_id	= $("#project_id").val();
	    	param.cycle_id	    = $("#cycle_id").val();
	    	param.work_shift_id	= $("#work_shift_id").val();
	    	param.num           = $("#num").val();
	    	
			if(param.name == ""){
				alert("Fill in the fields!");
			}else {
			    $.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {			        
						if(typeof(data.error) != 'undefined'){
							if(data.error != ''){
								alert(data.error);
							}else{
								GetDataTable('table_2', aJaxURL, "get_list1&id="+$("#cycle_id").val(), 9, "", 0, "", 1, "asc", "", "");
								$("#add-edit-form2").dialog("close");
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
	        	$(this).css('background','#E6F2F8');
	            $(this).children('img').attr('src','media/images/icons/select.png');
	            $(this).attr('myvar','0');
	        }
	    });
	   
    </script>
    <style type="text/css">
        #table_right_menu{
            top: 42px;
        }        
        
    </style>
</head>

<body>
<div id="tabs" style="width: 100%;">
<div class="callapp_head">Working Cycle<hr class="callapp_head_hr"></div>
<div id="button_area">
	<button id="add_button" style="display: none;">New</button>
	<button id="add_button2">New</button>
	<button id="delete_button">Delete</button>
</div>
<table id="table_right_menu">
<tr>
<td ><img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
</td>
<td><img alt="log" src="media/images/icons/log.png" height="14" width="14">
</td>
<td id="show_copy_prit_exel" myvar="0"><img alt="link" src="media/images/icons/select.png" height="14" width="14">
</td>
</tr>
</table>
    <table class="display" id="table_1">
        <thead>
            <tr id="datatable_header">
                <th>ID</th>
                <th style="width: 5%;">#</th>
                <th style="width: 30%;">Cycle Name</th>
                <th style="width: 45%;">Shifts</th>
                <th style="width: 20%;">Project</th>
            	<th class="check">#</th>
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
                	<div class="callapp_checkbox">
                        <input type="checkbox" id="check-all" name="check-all" />
                        <label for="check-all"></label>
                    </div>
                </th>
            </tr>
        </thead>
    </table>

    
    <!-- jQuery Dialog -->
    <div id="add-edit-form" class="form-dialog" title="Cycle">
    	<!-- aJax -->
	</div>
	<div id="add-edit-form2" class="form-dialog2" title="Shifts">
    	<!-- aJax -->
	</div>
	<!-- jQuery Dialog -->
    <div id="add-edit-form1" class="form-dialog" title="Working Cycle Name"> 
    <div id="dialog-form">
    	    <fieldset>
    	    	<legend>Basic Information</legend>
    	    	<table class="dialog-form-table">
    	    	    <tr>
    			        <td style="width: 190px;"><label for="name">Project</label></td>
    				</tr>
    			    <tr>
                        <td>
    						<select id="project_id" style="width: 174px;"></select>
    					</td>
    				</tr>
    			    <tr>
    			        <td style="width: 190px;"><label for="name">Name</label></td>
    				</tr>
    			    <tr>
                        <td>
    						<input type="text" id="name" value="" />
    					</td>
    				</tr>
    			</table>
            </fieldset>
        </div>
	</div>
</body>
</html>


