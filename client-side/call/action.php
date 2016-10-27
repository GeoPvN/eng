<head>
	<script type="text/javascript">
		var aJaxURL		= "server-side/call/action/action.action.php";		//server side folder url
		var upJaxURL	= "server-side/upload/file.action.php";		        //server side upload folder url	
		var tName		= "table_";											//table name											//tabs name
		var fName		= "add-edit-form";										//form name
		var colum_number = 7;
		var main_act    = "get_list";
		var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl>";
		var dialog            = "add-edit-form";
		var file_name = '';
		var rand_file = '';
		
		$(document).ready(function () {     
			LoadTable('index',colum_number,main_act,change_colum_main);
	    	SetEvents("add_button", "delete_button", "check-all", tName+'index', dialog, aJaxURL);
			GetButtons("add_button","delete_button");
			$('#status').chosen({ search_contains: true });
		});


		function LoadTable(tbl,col_num,act,change_colum){
			
			GetDataTable(tName+tbl,aJaxURL,act,col_num,"status=1",0,"",1,"desc",'',change_colum);
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

		$(document).on("click", ".ui-icon-closethick", function () {
    		tinymce.remove("#action_content");
    	});
    	
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
            GetDateTimes("start_date");
            GetDateTimes("end_date");
            tinymce.init({selector:'#action_content'});
    
    		$("#choose_button").button({
                
    	    });
		}

	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
	    	$('#save-dialog').attr('disabled','disabled');
	    	var ed = tinyMCE.get('action_content').getContent();
			param 				= new Object();
			param.act				= "save_action";
					
			param.id				= $("#actionn_id").val();
			param.action_name		= $("#action_name").val();
			param.start_date		= $("#start_date").val();
			param.end_date			= $("#end_date").val();
			param.action_content	= $("#action_content").val();


					
			param.task_type_id			= $("#task_type_id").val();
			param.persons_id			= $("#persons_id").val();
			param.comment				= $("#comment").val();
			param.task_department_id	= $("#task_department_id").val();
			param.action_content		= ed;
	 
		    $.ajax({
		        url: aJaxURL,
			    data: param,
		        success: function(data) {       
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							LoadTable('index',colum_number,main_act,change_colum_main);
							tinymce.remove("#action_content");
							CloseDialog("add-edit-form");
						}
					}
			    }
		    });
		});

	    $(document).on("change", "#status", function () {
	    	GetDataTable('table_index',aJaxURL,"get_list",colum_number,"status="+$(this).val(),0,"",1,"desc",'',change_colum_main);
	    	setTimeout(function(){
		    	$('.ColVis, .dataTable_buttons').css('display','none');
		    	}, 90);
	    });

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



	    $(document).on("click", "#upload_file", function () {
		    $('#file_name').click();
		});
	    $(document).on("change", "#file_name", function () {
	        var file_url  = $(this).val();
	        var file_name = this.files[0].name;
	        var file_size = this.files[0].size;
	        var file_type = file_url.split('.').pop().toLowerCase();
	        var path	  = "../../media/uploads/file/";

	        if($.inArray(file_type, ['pdf','png','xls','xlsx','jpg','docx','doc','csv']) == -1){
	            alert("Allowed only 'pdf', 'png', 'xls', 'xlsx', 'jpg', 'docx', 'doc', 'csv' expansion");
	        }else if(file_size > '15728639'){
	            alert("File size 15MB-More");
	        }else{
	        	$.ajaxFileUpload({
			        url: "server-side/upload/file.action.php",
			        secureuri: false,
	     			fileElementId: "file_name",
	     			dataType: 'json',
				    data: {
						act: "file_upload",
						button_id: "file_name",
						table_name: 'action',
						file_name: Math.ceil(Math.random()*99999999999),
						file_name_original: file_name,
						file_type: file_type,
						file_size: file_size,
						path: path,
						table_id: $("#act_id").val(),

					},
			        success: function(data) {			        
				        if(typeof(data.error) != 'undefined'){
							if(data.error != ''){
								alert(data.error);
							}else{
								var tbody = '';
								for(i = 0;i <= data.page.length;i++){
									tbody += "<div id=\"first_div\">" + data.page[i].file_date + "</div>";
									tbody += "<div id=\"two_div\">" + data.page[i].name + "</div>";
									tbody += "<div id=\"tree_div\" onclick=\"download_file('" + data.page[i].rand_name + "')\">Download</div>";
									tbody += "<div id=\"for_div\" onclick=\"delete_file('" + data.page[i].id + "')\">-</div>";
									$("#paste_files").html(tbody);
								}							
							}						
						}					
				    }
			    });
	        }
	    });

	    function download_file(file){
	        var download_file	= "media/uploads/file/"+file;
	    	var download_name 	= file;
	    	SaveToDisk(download_file, download_name);
	    }
	    
	    function delete_file(id){
	    	$.ajax({
	            url: "server-side/upload/file.action.php",
	            data: "act=delete_file&file_id="+id+"&table_name=incomming_call",
	            success: function(data) {
	               
	            	var tbody = '';
	            	if(data.page.length == 0){
	            		$("#paste_files").html('');
	            	};
					for(i = 0;i <= data.page.length;i++){
						tbody += "<div id=\"first_div\">" + data.page[i].file_date + "</div>";
						tbody += "<div id=\"two_div\">" + data.page[i].name + "</div>";
						tbody += "<div id=\"tree_div\" onclick=\"download_file('" + data.page[i].rand_name + "')\">Download</div>";
						tbody += "<div id=\"for_div\" onclick=\"delete_file('" + data.page[i].id + "')\">-</div>";
						$("#paste_files").html(tbody);
					}	
	            }
	        });
	    }

	    function SaveToDisk(fileURL, fileName) {
// 	        // for non-IE
// 	        if (!window.ActiveXObject) {
// 	            var save = document.createElement('a');
// 	            save.href = fileURL;
// 	            save.target = '_blank';
// 	            save.download = fileName || 'unknown';

// 	            var event = document.createEvent('Event');
// 	            event.initEvent('click', true, true);
// 	            save.dispatchEvent(event);
// 	            (window.URL || window.webkitURL).revokeObjectURL(save.href);
// 	        }
// 		     // for IE
// 	        else if ( !! window.ActiveXObject && document.execCommand)     {
// 	            var _window = window.open(fileURL, "_blank");
// 	            _window.document.close();
// 	            _window.document.execCommand('SaveAs', true, fileName || fileURL)
// 	            _window.close();
// 	        }
	    	var iframe = document.createElement("iframe"); 
	        iframe.src = fileURL; 
	        iframe.style.display = "none"; 
	        document.body.appendChild(iframe);
	        return false;
	    }
    </script>
</head>

<body>

<div id="tabs">
<div class="callapp_head">News<hr class="callapp_head_hr"></div>
<div class="callapp_tabs">

</div>

<div id="button_area">
	<button id="add_button">Add</button>
	<button id="delete_button">Delete</button>
	<select id="status" style="width: 150px;">
	   <option value="1">Current</option>
	   <option value="2">Archive</option>
	</select>
</div>
<table id="table_right_menu" style="top: 40px;">
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
			<th style="width:3%;">#</th>
			<th style="width:15%; word-break:break-all;">Begin</th>
			<th style="width:15%; word-break:break-all;">End</th>
			<th style="width:20%; word-break:break-all;">Title</th>
			<th style="width:30%; word-break:break-all;">Contents</th>
			<th style="width:15%; word-break:break-all;">Author</th>
			<th style="width:3%; word-break:break-all;">#</th>
		</tr>
	</thead>
	<thead>
		<tr class="search_header">
			<th class="colum_hidden">
    			<input type="text" name="search_id" value="Filter" class="search_init" style="width: 10px"/>
    		</th>
			<th>
				<input style="width:20px;" type="text" name="search_overhead" value="" class="search_init" />
			</th>
			<th>
				<input style="width:100px;" type="text" name="search_partner" value="Filter" class="search_init" />
			</th>
			<th>
				<input style="width:100px;" type="text" name="search_sum_cost" value="Filter" class="search_init" />
			</th>
			<th>
				<input style="width:100px;" type="text" name="search_partner" value="Filter" class="search_init" />
			</th>
			<th>
				<input style="width:100px;" type="text" name="search_op_date" value="Filter" class="search_init" />
			</th>
			<th>
				<input style="width:100px;" type="text" name="search_op_date" value="Filter" class="search_init" />
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
<div id="add-edit-form" class="form-dialog" title="Action">
<!-- aJax -->
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form1" class="form-dialog" title="Outgoing Call">
<!-- aJax -->
</div>
<!-- jQuery Dialog -->
<div id="add-edit-form3" class="form-dialog" title="Department">
<!-- aJax -->
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form2" class="form-dialog" title="Stocks Products">
<!-- aJax -->
</div>

<div id="add-responsible-person" class="form-dialog" title="Responsible Person">
<!-- aJax -->
</div>
</body>

