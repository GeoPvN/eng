<html>
<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/view/email.action.php"; //server side folder url
		var upJaxURL	= "server-side/upload/file.action.php";
		var file_name = '';
		var rand_file = '';
		var tName             = "table_";
	    var dialog            = "add-edit-form";
	    var fName             = "add-edit-form";
	    var colum_number      = 2;
	    var main_act          = "get_list";
	    var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl>";
	     
		    	
		$(document).ready(function () {        	
			LoadTable('index',colum_number,main_act,change_colum_main,'',aJaxURL);
						
			/* Add Button ID, Delete Button ID */
			GetButtons("add_button", "delete_button");			
			SetEvents("add_button", "delete_button", "check-all", tName+'index', dialog, aJaxURL,'','index',colum_number,main_act,change_colum_main,'',aJaxURL);
		});
        
		function LoadTable(tbl,col_num,act,change_colum,custom_param,URL){
	    	GetDataTable(tName+tbl, URL, act, col_num, custom_param, 0, "", 1, "asc", '', change_colum);
	    	setTimeout(function(){
		    	$('.ColVis, .dataTable_buttons').css('display','none');
		    	}, 50);
	    	$('.display').css('width','100%');
	    }
		
		function LoadDialog(){
			var id		= $("#source_id").val();
			
			/* Dialog Form Selector Name, Buttons Array */
			GetDialog(fName, "529", "auto", "");
			
			$("#choose_button").button();
			setTimeout(function(){ 
			new TINY.editor.edit('editor',{
				id:'input',
				width:"500px",
				height:"100%",
				cssclass:'te',
				controlclass:'tecontrol',
				dividerclass:'tedivider',
				controls:['bold','italic','underline','strikethrough','|','subscript','superscript','|',
				'orderedlist','unorderedlist','|','outdent','indent','|','leftalign',
				'centeralign','rightalign','blockjustify','|','unformat','|','undo','redo','n',
				'font','size','|','image','hr','link','unlink','|','print'],
				footer:true,
				fonts:['Verdana','Arial','Georgia','Trebuchet MS'],
				xhtml:true,
				bodyid:'editor',
				footerclass:'tefooter',
				resize:{cssclass:'resize'}
			}); }, 100);
			
		}
			
		$(document).on("click", "#download", function () {
	    	var download_file	= $(this).val();
	    	var download_name 	= $('#download_name').val();
	    	SaveToDisk(download_file, download_name);
	    });

	    function SaveToDisk(fileURL, fileName) {
	        // for non-IE
	        if (!window.ActiveXObject) {
	            var save = document.createElement('a');
	            save.href = fileURL;
	            save.target = '_blank';
	            save.download = fileName || 'unknown';

	            var event = document.createEvent('Event');
	            event.initEvent('click', true, true);
	            save.dispatchEvent(event);
	            (window.URL || window.webkitURL).revokeObjectURL(save.href);
	        }
		     // for IE
	        else if ( !! window.ActiveXObject && document.execCommand)     {
	            var _window = window.open(fileURL, "_blank");
	            _window.document.close();
	            _window.document.execCommand('SaveAs', true, fileName || fileURL)
	            _window.close();
	        }
	    } 
	    
	    $(document).on("click", "#delete", function () {
	    	var delete_id	= $("#delete").val();
	    	var r = confirm("გსურთ წაშალოთ?");
	    	if (r == true) {
	    		$.ajax({
			        url: aJaxURL,
				    data: {
						act: "delete_file",
						delete_id: delete_id,
						edit_id: $("#mail_hidde_id").val(),
					},
			        success: function(data) {
				        $("#file_div").html(data.page);
				    }
	    		
			    });
	    	}	
		});
		
	    $(document).on("click", "#choose_button", function () {
		    $("#choose_file").click();
		});
		
	    $(document).on("change", "#choose_file", function () {
	    	var file		= $(this).val();	    
	    	var files 		= this.files[0];
		    var name		= uniqid();
		    var path		= "../../media/uploads/file/";
		    
		    var ext = file.split('.').pop().toLowerCase();
	        if($.inArray(ext, ['pdf','png','xls','xlsx','jpg','docx']) == -1) { //echeck file type
	        	alert('This is not an allowed file type.');
                this.value = '';
	        }else{
	        	file_name = files.name;
	        	rand_file = name + "." + ext;
	        	$.ajaxFileUpload({
	    			url: upJaxURL,
	    			secureuri: false,
	    			fileElementId: "choose_file",
	    			dataType: 'json',
	    			data:{
						act: "upload_file",
						path: path,
						file_name: name,
						type: ext
					},
	    			success: function (data, status){
	    				if(typeof(data.error) != 'undefined'){
    						if(data.error != ''){
    							alert(data.error);
    						}
    					}
    							
	    				$.ajax({
					        url: aJaxURL,
						    data: {
								act: "up_now",
								rand_file: rand_file,
					    		file_name: file_name,
								edit_id: $("#mail_hidde_id").val(),

							},
					        success: function(data) {
						        $("#file_div").html(data.page);
						    }
					    });	   					    				
    				},
    				error: function (data, status, e)
    				{
    					alert(e);
    				}    				
    			});
	        }
		});
		 function isValid(str){
		     var check = false;
		     for(var i=0;i<str.length;i++){
		         if(str.charCodeAt(i)>127){
		        	 check = true;
		          }
		     }
		     if(check){
		    	 var string = $('#content').val();
		    	 var replaced = string.replace(/[^\x00-\x7F]/g, "");
		    	 $('#content').val(replaced);
		    	 alert('არასწორი სიმბოლო');
			 }   
		 }
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
		    param 			= new Object();

		    param.act		="save_source";
	    	param.id		= $("#source_id").val();
	    	param.SUBJECT	= $("#SUBJECT").val();
	    	param.content	= $("iframe").contents().find("body").html();
	    	
	    	
			if(param.SUBJECT == ""){
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
								LoadTable('index',colum_number,main_act,change_colum_main,'',aJaxURL);
				        		CloseDialog(fName);
							}
						}
				    }
			    });
			}
		});
	    $(document).on("keyup  paste", "#content", function () {
	    	 var sms_text = $('#content').val(); 
	    	  isValid(sms_text);
	    	$('#simbol_caunt').val((sms_text.length)+'/150');
	    });
	   
    </script>
    <link rel="stylesheet" href="media/css/tinyeditor.css" />

<script type="text/javascript" src="js/tinyeditor.js"></script>

</head>

<body>
<div id="tabs">
<div class="callapp_head">ელ-ფოსტის შაბლონი<hr class="callapp_head_hr"></div>

<div class="callapp_filter_show">
            	<div id="button_area">
        			<button id="add_button">დამატება</button>
        			<button id="delete_button">წაშლა</button>
        		</div>
                <table class="display" id="table_index">
                    <thead >
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 100%;">სახელი</th>
                            <th class="check" style="width: 30px;">&nbsp;</th>
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
                            	<div class="callapp_checkbox">
                                    <input type="checkbox" id="check-all" name="check-all" />
                                    <label for="check-all"></label>
                                </div>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
    
    <!-- jQuery Dialog -->
    <div id="add-edit-form" class="form-dialog" title="ახალი  E-mail">
    	<!-- aJax -->
	</div>
</body>
</html>






