<html>
<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/view/sl.action.php";		//server side folder url
		var tName	= "example";													//table name
		var fName	= "add-edit-form";												//form name
		var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl>";
		    	
		$(document).ready(function () {        	
			LoadTable();	
 						
			/* Add Button ID, Delete Button ID */			
			SetEvents("", "", "check-all", tName, fName, aJaxURL);
		});
        
		function LoadTable(){
			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list", 3, "", 0, "", 1, "desc", "", change_colum_main);
			setTimeout(function(){
    	    	$('.ColVis, .dataTable_buttons').css('display','none');
  	    	}, 90);
		}

	    function LoadTableLog(){
			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName+'_log', aJaxURL, "get_list_log", 8, "", 0, "", 1, "desc", "", change_colum_main);
			setTimeout(function(){
    	    	$('.ColVis, .dataTable_buttons').css('display','none');
  	    	}, 90);
		}
		
		function LoadDialog(){
			var id		= $("#department_id").val();
			
			/* Dialog Form Selector Name, Buttons Array */
			GetDialog(fName, 600, "auto", "");
		}
		
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
		    param 			= new Object();

		    param.act		="save_department";
	    	param.id		= $("#department_id").val();
	    	param.name		= $("#name").val();
	    	param.name1		= $("#name1").val();
	    	
			if(param.name == ""){
				alert("შეავსეთ Field!");
			}else {
			    $.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {			        
						if(typeof(data.error) != 'undefined'){
							if(data.error != ''){
								alert(data.error);
							}else{
								LoadTable();
				        		CloseDialog(fName);
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

	    $(document).on("click", "#show_log", function () {
	    	LoadTableLog();
	    	$("#example_wrapper,#example").css('display','none');
	    	$("#example_log_wrapper").css('display','block');
	    	$("#example_log").css('display','table');
	    	$('#add_button,#delete_button').attr('disabled','disabled');
	    	
	    	$(this).css('background','#2681DC');
            $(this).children('img').attr('src','media/images/icons/log_w.png');
    	
    		$("#show_table").css('background','#E6F2F8');
            $("#show_table").children('img').attr('src','media/images/icons/table.png');
	    	
	    });

	    $(document).on("click", "#show_table", function () {
	    	LoadTable();
	    	$("#example_log_wrapper,#example_log").css('display','none');
	    	$("#example_wrapper").css('display','block');
	    	$("#example").css('display','table');
	    	$('#add_button,#delete_button').removeAttr('disabled');
	    	
	    	$("#show_log").css('background','#E6F2F8');
            $("#show_log").children('img').attr('src','media/images/icons/log.png');

            $(this).css('background','#2681DC');
            $(this).children('img').attr('src','media/images/icons/table_w.png');
	    });
    </script>
    <style type="text/css">
        #table_right_menu{
            position: relative;
            float: right;
            width: 70px;
            top: 28px;
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
        #example_log tbody .dataTables_empty{
        	text-align: center !important;
        }
        #example_log tbody td:last-child{
        	text-align: left !important;
        }
    </style>
</head>

<body>
<div id="tabs">
<div class="callapp_head">SL<hr class="callapp_head_hr"></div>

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
    <table class="display" id="example">
        <thead>
            <tr id="datatable_header">
                <th>ID</th>
                <th style="width: 50%;">Seconds</th>
                <th style="width: 50%;">Percent</th>
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
                	<div class="callapp_checkbox">
                        <input type="checkbox" id="check-all" name="check-all" />
                        <label for="check-all"></label>
                    </div>
                </th>
            </tr>
        </thead>
    </table>
    
    <table class="display" id="example_log" style="display: none;">
        <thead>
            <tr id="datatable_header">
                <th>ID</th>
                <th style="width: 7%;">№</th>
                <th style="width: 15%;">Date</th>
                <th style="width: 15%;">Action</th>
                <th style="width: 15%;">Users</th>
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

    
    <!-- jQuery Dialog -->
    <div id="add-edit-form" class="form-dialog" title="SL">
    	<!-- aJax -->
	</div>
</body>
</html>


