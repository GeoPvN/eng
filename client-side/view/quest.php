<html>
<head>
<style type="text/css">
.colum_hidden{
	display: none;
}
</style>
	<script type="text/javascript">
		var aJaxURL	= "server-side/view/quest.action.php";		//server side folder url
		var seoyURL	= "server-side/seoy/seoy.action.php";		//server side folder url
		var tName	= "table_";								//table name
		var fName	= "add-edit-form";							//form name
		var change_colum_main = "<'dataTable_buttons'T><'F'fipl>";
		var lenght = [[10, 30, 50, -1], [10, 30, 50, "ყველა"]];
 		    	
		$(document).ready(function () {
			LoadTable('index',3,'get_list',change_colum_main,'',lenght);
			MyEvent(   aJaxURL,  'add_button', 'delete_button', 'check-all', '', 'save-dialog', 'cancel-dialog',      735,       'center top',  'get_add_page', 'disable', 'get_edit_page',  'index',   3,        'get_list', change_colum_main,      '',        'hidden_id='+$('#hidden_id').val(),1,'','');
			
		});

        
		function LoadTable(tbl,col_num,act,change_colum,other_act){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			
			GetDataTable(tName+tbl, aJaxURL, act, col_num, other_act, 0, '', 0, "asc", '', change_colum);
			setTimeout(function(){
    	    	$('.ColVis, .dataTable_buttons').css('display','none');    	    	
  	    	}, 90); 		
		}

		function GetTable() {
			var questId = ''
				if($("#quest_id").val() == ''){
					questId = $("#delete_id").val();
				}else{
					questId = $("#quest_id").val();
				}
			LoadTable('quest',4,'get_list_detail',"<'F'lip>",'id='+$("#quest_id").val(),lenght);			
			MyEvent(   aJaxURL,  'add_button_detail', 'delete_button_detail', 'check-all-de', '-answer', 'save-answer', 'cancel-dialog',      480,       'center top',  'get_add_page', 'disable_detail', 'get_edit_page',  'quest',   4,        'get_list_detail', "<'F'lip>",      '',        'id='+questId,'','dialog_check=1&add_id='+$("#quest_id").val(),'dialog_check=1&quest_detail_id='+$("#quest_id").val());

	    }
		
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
	    	param 			= new Object();

		    param.act		       = "save_quest";
	    	param.id	           = $("#quest_id").val();
	    	param.quest_detail_id  = $("#quest_detail_id").val();
	    	param.name		       = $("#name").val();
	    	param.note             = $("#note").val();
	    	
			if(param.name == ""){
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
								LoadTable('index',3,'get_list',change_colum_main,'',lenght);
				        		CloseDialog(fName);
							}
						}
				    }
			    });
			}	    					
		});
	    $(document).on("change", "#quest_type_id", function () {
		    if($(this).val()==7){
		        $('#show_handbook').css('display','table-row');
		        $('#show_answer').css('display','none');
		    }else{
		    	$('#show_answer').css('display','table-row');
		    	$('#show_handbook').css('display','none');
		    }
	    });

	    $(document).on("click", "#save-answer", function () {	
	    	param 			= new Object();

		    param.act		        = "save_answer";
		    param.add_id	        = $("#add-edit-form-answer #add_id").val();
		    param.id	            = $("#add-edit-form-answer #quest_id").val();
	    	param.quest_detail_id   = $("#add-edit-form-answer #quest_detail_id").val();
	    	param.quest_type_id	    = $("#add-edit-form-answer #quest_type_id").val();
	    	if($("#add-edit-form-answer #answer").val() == ''){
	    		  param.answer		    = $("#add-edit-form-answer #handbook").val();
	    	}else{
	    		  param.answer		    = $("#add-edit-form-answer #answer").val();
	    	}
	    	param.hidden_product_id	= $("#add-edit-form-answer #hidden_product_id").val();
	        var ar_daamato = 0;
	    	$('#table_quest td:nth-child(4)').each(function(){
	    		if($(this).text() == 'რადიო-ბოქსი' && $("#quest_type_id option:selected").text() == 'ჩეკ-ბოქსი'){
	    			ar_daamato = 1;
	    		}
	    		
	    	    if($(this).text() == 'ჩეკ-ბოქსი' && $("#quest_type_id option:selected").text() == 'რადიო-ბოქსი'){	    	        
	    	       ar_daamato = 1;
	    	    }
	    	});
	    	
			if(param.name == ""){
				alert("შეავსეთ ველი!");
			}else{
				if(ar_daamato == 0){
			    $.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {			        
						if(typeof(data.error) != 'undefined'){
							if(data.error != '' && !$.isNumeric(data.error)){
								alert(data.error);
							}else{
								if($("#quest_id").val() == ''){
								    load_id = data.error;
								}else{
									load_id = $("#quest_id").val();
								}
								LoadTable('quest',4,'get_list_detail',"<'F'lip>",'id='+load_id,lenght);
								CloseDialog("add-edit-form-answer");
							}
						}
				    }
			    });
				}else{
					alert('ჩეკ-ბოქის და რადიო-ბოქსის ერთად დამატება აკრძალულია!');
				} 
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
#table_quest_length{
	position: inherit;
    width: 0px;
	float: left;
}
#table_quest_length label select{
	width: 60px;
    font-size: 10px;
    padding: 0;
    height: 18px;
}

    </style>
</head>

<body>
<div id="tabs">
<div class="callapp_head">კითხვა/პასუხი<hr class="callapp_head_hr"></div>
<div style="margin-bottom: 5px;">
<div id="instruqcia">ინსტრუქცია</div>
<table id="stepby">
<tr>
<td style="color: #FFF;background: #2681DC;" onclick="location.href='index.php?pg=18';" >კითხვა/პასუხი >></td><td onclick="location.href='index.php?pg=17';" >სცენარის კატეგორია >></td><td onclick="location.href='index.php?pg=16';">სცენარი >></td><td onclick="location.href='index.php?pg=15';">რიგი >></td><td onclick="location.href='index.php?pg=14';">კლიენტები</td>
</tr>
</table>
</div>
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
    <thead >
        <tr id="datatable_header">
            <th>ID</th>
            <th style="width: 100%;">დასახელება</th>
            <th style="width: 100%;">მინიშნება</th>
            <th class="check" style="width: 20px;">&nbsp;</th>
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
            	<div class="callapp_checkbox">
                    <input type="checkbox" id="check-all" name="check-all" />
                    <label for="check-all"></label>
                </div>
            </th>
        </tr>
    </thead>
</table>
        
    
    <!-- jQuery Dialog -->
    <div id="add-edit-form" class="form-dialog" title="კითხვა">
    	<!-- aJax -->
	</div>
	
	<div id="add-edit-form-answer" class="form-dialog" title="პასუხი">
    	<!-- aJax -->    	
	</div>
</body>
</html>