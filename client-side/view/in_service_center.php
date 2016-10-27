<html>
<head>
	<script type="text/javascript">
		var aJaxURL	          = "server-side/view/in_service_center.action.php";
		var tName             = "example";
	    var dialog            = "add-edit-form";
	    var colum_number      = 5;
	    var main_act          = "get_list";
	    var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl>";
 		    	
		$(document).ready(function () {        	
			GetButtons("add_button","delete_button");
	    	LoadTable('index',colum_number,main_act,change_colum_main);
	    	SetEvents("add_button", "delete_button", "check-all", tName, dialog, aJaxURL);
		});
        
		function LoadTable(tbl,col_num,act,change_colum){
	    	GetDataTable(tName, aJaxURL, act, col_num, "", 0, "", 1, "asc", '', change_colum);
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
		
		function LoadDialog(fName){
			GetDialog(fName, 500, "auto", '', 'center top');
			$('#parent_id,#client_id,#branch_id').chosen({ search_contains: true });
			$('#add-edit-form, .add-edit-form-class').css('overflow','visible');
			if($('#ubani').is(':checked')){
	    		$("#br_show").css('display','none');
	    	}else{
	    		$("#br_show").css('display','table-row');
	    	}
		}
		
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
		    param 			= new Object();

		    param.act		="save_category";
	    	param.id		= $("#cat_id").val();
	    	param.cat		= $("#category").val();
	    	param.parent_id	= $("#parent_id").val();
	    	param.branch_id = $("#branch_id").val();
	    	if($('#ubani').is(':checked')){
	    		param.ubani	= 1;
	    	}else{
	    		param.ubani	= 0;
	    	}
			
			if(param.cat == ""){
				alert("შეავსეთ პროდუქტის კატეგორია!");
			}else {
			    $.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {			        
						if(typeof(data.error) != 'undefined'){
							if(data.error != ''){
								alert(data.error);
							}else{
								LoadTable('index',colum_number,main_act,change_colum_main);
				        		CloseDialog(dialog);
							}
						}
				    }
			    });
			}
		});

	    $(document).on("click", "#ubani", function () {

		    if($('#ubani').is(':checked')){
	    		$("#br_show").css('display','none');
	    	}else{
	    		$("#br_show").css('display','table-row');
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
	    	LoadTable('index',colum_number,main_act,change_colum_main);
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
    </style>
</head>

<body>
<div id="tabs">
<div class="callapp_head">მომსახურების ცენტრი  / უბანი<hr class="callapp_head_hr"></div>
<div id="button_area">
	<button id="add_button">დამატება</button>
	<button id="delete_button">წაშლა</button>
</div>
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
<table  class="display" id="example">
    <thead>
        <tr id="datatable_header">
            <th>ID</th>
            <th style="width: 5%;">№</th>
            <th style="width: 40%;">მომსახურების ცენტრი</th>
            <th style="width: 40%;">უბანი</th>
            <th style="width: 20%;">ფილიალი</th>
            <th class="check">#</th>
        </tr>
    </thead>
    <thead>
        <tr class="search_header">
            <th class="colum_hidden">
            	<input type="text" name="search_id" value="ფილტრი" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_sub_category" value="ფილტრი" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_sub_category" value="ფილტრი" class="search_init" />
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
                <th style="width: 15%;">ქმედების თარიღი</th>
                <th style="width: 15%;">მომხმარებელი</th>
                <th style="width: 15%;">ქმედება</th>
                <th style="width: 15%;">ველი</th>
                <th style="width: 15%;">ძველი მნიშვნელობა</th>
                <th style="width: 20%;">ახალი მნიშვნელობა</th>
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
                <th>
                    <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                </th>
                <th>
                    <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                </th>
            </tr>
        </thead>
    </table>
    
    <!-- jQuery Dialog -->
    <div id="add-edit-form" class="form-dialog" title="მომსახურების ცენტრი / ფილიალი / უბანი">
    	<!-- aJax -->
	</div>
</body>
</html>
