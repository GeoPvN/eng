<html>
<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/info/work_shift.action.php";		//server side folder url
		var tName	= "example";													//table name
		var fName	= "add-edit-form";												//form name
		var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl>";
		    	
		$(document).ready(function () {        	
			LoadTable();	
 						
			/* Add Button ID, Delete Button ID */
			GetButtons("add_button", "delete_button");			
			SetEvents("add_button", "delete_button", "check-all", tName, fName, aJaxURL);
		});
        
		function LoadTable(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list", 10, "", 0, "", 1, "desc", "", change_colum_main);
			setTimeout(function(){
    	    	$('.ColVis, .dataTable_buttons').css('display','none');
  	    	}, 90);
		}
		
		function LoadDialog(){
			var id		= $("#lang_id").val();
			/* Dialog Form Selector Name, Buttons Array */
			GetDialog(fName, 410, "auto", "");
			$('#start_date,#end_date').timepicker({
	        	hourMax: 23,
	    		hourMin: 00,
	    		minuteMax: 55,
	    		minuteMin: 00,
	    		stepMinute: 15,
	    		minuteGrid: 15,
	    		hourGrid: 3,
	        	dateFormat: '',
	            timeFormat: 'HH:mm'
	    	});
			$('#timeout,#start_timeout,#end_timeout').timepicker({
	        	hourMax: 24,
	    		hourMin: 00,
	    		minuteMax: 59,
	    		minuteMin: 00,
	    		stepMinute: 1,
	    		minuteGrid: 15,
	    		hourGrid: 5,
	        	dateFormat: '',
	            timeFormat: 'HH:mm'
	    	});
			$( "#lang_id" ).focus();
		}
		
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
		    param 			= new Object();

		    param.act		    = "save_lang";
	    	param.id		    = $("#lang_id").val();
	    	param.start_date	= $("#start_date").val();
	    	param.end_date		= $("#end_date").val();
	    	param.name		    = $("#name").val();
	    	param.color		    = $("#color").val();
	    	param.type		    = $("#type").val();
	    	param.project_id	= $("#project_id").val();
	    	param.comment	    = $("#comment").val();
	    	param.pay	        = $("#pay").val();
	    	param.timeout       = $("#timeout").val();
	    	param.start_timeout = $("#start_timeout").val();
	    	param.end_timeout   = $("#end_timeout").val();
	    	
			if(param.name == "" || param.color == '' || param.type == 0 || param.pay == 0 || param.project_id == 0){
				alert("შეავსეთ ველი!");
			}else{
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
	   
    </script>
    <style type="text/css">
        #table_right_menu{
            top: 42px;
        }        
        
    </style>
</head>

<body>
<div id="tabs" style="width: 95.5%;">
<div class="callapp_head">ცვლები<hr class="callapp_head_hr"></div>


<div id="button_area">
	<button id="add_button">დამატება</button>
	<button id="delete_button">წაშლა</button>
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
    <table class="display" id="example">
        <thead>
            <tr id="datatable_header">
                <th>ID</th>
                <th style="width: 13%;">დასახელება</th>
                <th style="width: 8%;">დასაწყისი</th>
                <th style="width: 8%;">დასასრული</th>
                <th style="width: 8%;">შესვენება</th>
                <th style="width: 14%;">სამუშაო ტიპი</th>
                <th style="width: 12%;">ანაზღაურებადი/არა ანაზღაურებადი</th>
                <th style="width: 14%;">კომენტარი</th>
                <th style="width: 14%;">პროექტი</th>
                <th style="width: 5%;">ფერი</th>
            	<th class="check">#</th>
            </tr>
        </thead>
        <thead>
            <tr class="search_header">
                <th class="colum_hidden">
                    <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                </th>
                <th>
                    <input style="width: 95%;" type="text" name="search_category" value="ფილტრი" class="search_init" />
                </th>
                <th>
                    <input style="width: 95%;" type="text" name="search_category" value="ფილტრი" class="search_init" />
                </th>
                <th>
                    <input style="width: 95%;" type="text" name="search_category" value="ფილტრი" class="search_init" />
                </th>
                <th>
                    <input style="width: 95%;" type="text" name="search_category" value="ფილტრი" class="search_init" />
                </th>
                <th>
                    <input style="width: 95%;" type="text" name="search_category" value="ფილტრი" class="search_init" />
                </th>
                <th>
                    <input style="width: 95%;" type="text" name="search_category" value="ფილტრი" class="search_init" />
                </th>
                <th>
                    <input style="width: 95%;" type="text" name="search_category" value="ფილტრი" class="search_init" />
                </th>
                <th>
                    <input style="width: 95%;" type="text" name="search_category" value="ფილტრი" class="search_init" />
                </th>
                <th>
                    <input style="width: 95%;" type="text" name="search_category" value="ფილტრი" class="search_init" />
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
    <div id="add-edit-form" class="form-dialog" title="ცვლები">
    	<!-- aJax -->
	</div>
</body>
</html>