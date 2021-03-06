<html>
<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/info/user.action.php";		//server side folder url
		var upJaxURL= "server-side/upload/file.action.php";				//server side folder url
		var tName	= "example";											//table name
		var fName	= "add-edit-form";										//form name
		var img_name		= "0.jpg";
		var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl>";

		$(document).ready(function () {
			LoadTable();

			/* Add Button ID, Delete Button ID */
			GetButtons("add_button", "delete_button");

			SetEvents("add_button", "delete_button", "check-all", tName, fName, aJaxURL);
		});

		function LoadTable(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list", 8, "", 0, "", 1, "asc", "", change_colum_main);
			setTimeout(function(){
		    	$('.ColVis, .dataTable_buttons').css('display','none');
		    }, 90);
		}

		function LoadDialog(){
			var id		= $("#pers_id").val();
			if(id != ""){
				$("#lname_fname").attr("disabled", "disabled");
			}

			GetButtons("choose_button");
			GetButtons("choose_buttondisabled");
			
			/* Dialog Form Selector Name, Buttons Array */
			GetDialog(fName, 450, "auto", "");

			if( $("#position").val() == 13 ){
					$("#passwordTR").removeClass('hidden');
			}
			$( "#accordion" ).accordion({
				active: false,
				collapsible: true,
				heightStyle: "content",
				activate: function(event, ui) {
					$("#is_user").val();
				}
			});
			$('#position,#dep_id,#service_center_id,#branch_id').chosen({ search_contains: true });
		}

	    // Add - Save
		$(document).on("click", "#save-dialog", function () {
			param = new Object();

            //Action
	    	param.act	= "save_pers";

		    param.id	= $("#pers_id").val();

		    param.n		= $("#name").val();
		    param.t		= $("#tin").val();
		    param.p		= $("#position").val();
		    param.dep_id= $("#dep_id").val();
		    param.a		= $("#address").val();
		    param.pas	= $("#password").val();
		    param.h_n	= $("#home_number").val();
		    param.m_n	= $("#mobile_number").val();
		    param.comm	= $("#comment").val();
		    param.service_center_id	= $("#service_center_id").val();
		    param.branch_id	= $("#branch_id").val();

		    param.user	= $("#user").val();
		    param.userp	= $("#user_password").val();
		    param.gp	= $("#group_permission").val();
		    param.ext	= $("#ext").val();
		    param.img 	= img_name;

			if(param.n == ""){
				alert("Fill in the name!");
			}else if(param.p == 0){
				alert("Fill Position!");
			}else if(param.user && !param.userp){
				alert("Fill Password")
			}else{
			    $.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {
						if(typeof(data.error) != "undefined"){
							if(data.error != ""){
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

	    $(document).on("click", "#choose_button", function () {
		    $("#choose_file").click();
		});

	    $(document).on("click", "#choose_buttondisabled", function () {
		    alert('If you want to upload a new image, delete the current picture!');
		});

	    
	    $(document).on("change", "#choose_file", function () {
	        var file_url  = $(this).val();
	        var file_name = this.files[0].name;
	        var file_size = this.files[0].size;
	        var file_type = file_url.split('.').pop().toLowerCase();
	        var path	  = "../../media/uploads/file/";

	        if($.inArray(file_type, ['png','jpg']) == -1){
	            alert("Allowed only 'png', 'jpg'  Extension");
	        }else if(file_size > '15728639'){
	            alert("File size over 15MB");
	        }else{
	            if($("#pers_id").val() == ''){
		            users_id = $("#is_user").val();
	            }else{
	            	users_id = $("#pers_id").val()
	            }
	        	$.ajaxFileUpload({
			        url: "server-side/upload/file.action.php",
			        secureuri: false,
	     			fileElementId: "choose_file",
	     			dataType: 'json',
				    data: {
						act: "file_upload",
						button_id: "choose_file",
						table_name: 'users',
						file_name: Math.ceil(Math.random()*99999999999),
						file_name_original: file_name,
						file_type: file_type,
						file_size: file_size,
						path: path,
						table_id: users_id,

					},
			        success: function(data) {			        
				        if(typeof(data.error) != 'undefined'){
							if(data.error != ''){
								alert(data.error);
							}else{
								$("#upload_img").attr('src','media/uploads/file/'+data.page[0].rand_name);
								$('#choose_button').attr('id','choose_buttondisabled');
								$("#delete_image").attr('image_id',data.page[0].id);
								$(".complate").attr('onclick','view_image('+ data.page[0].id + ')');
							}						
						}					
				    }
			    });
	        }
	    });

	    $(document).on("click", "#delete_image", function () {
		    $.ajax({
	            url: "server-side/upload/file.action.php",
	            data: "act=delete_file&file_id="+$(this).attr('image_id')+"&table_name=client",
	            success: function(data) {
	               $('#upload_img').attr('src','media/uploads/file/0.jpg');               
	               $("#choose_button").button();
	               $('#choose_buttondisabled').attr('id','choose_button')
	            }
	        });
		});

		function view_image(id){
			param = new Object();

	        //Action
	    	param.act	= "view_img";
	    	param.id    = id;
	    	
			$.ajax({
		        url: aJaxURL,
			    data: param,
		        success: function(data) {
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							var buttons = {
						        	"cancel": {
							            text: "დახურვა",
							            id: "cancel-dialog",
							            click: function () {
							            	$(this).dialog("close");
							            }
							        }
							    };
							GetDialog("add-edit-form-img", 401, "auto", buttons, 'center top');
							$("#add-edit-form-img").html(data.page);
						}
					}
			    }
		    });
		}


		
		$(document).on("change", "#branch_id", function () {
			$.ajax({
		        url: aJaxURL,
			    data: 'act=GetServiceCenter&branch_id='+$(this).val(),
		        success: function(data) {
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
						    $('#service_center_id').html(data.page);
						    $('#service_center_id').trigger("chosen:updated");
						}
					}
			    }
		    });
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
</head>

<body>
<div id="tabs" style="width: 90%">
<div class="callapp_head">Staff<hr class="callapp_head_hr"></div>
    
    <div style="margin-top: 15px;">
        <button id="add_button">New</button>
        <button id="delete_button">Delete</button>
    </div>
    
<div class="callapp_filter_show">
<table id="table_right_menu">
<tr>
<td><img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
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
            <th style="width: 50%">User name</th>
            <th style="width: 50%">Branch</th>
            <th style="width: 50%">Service center</th>
            <th style="width: 50%">extention</th>
            <th class="min">Tin</th>
            <th class="min">Position</th>
            <th class="aver">Address</th>
            <th class="check">#</th>
        </tr>
    </thead>
    <thead>
        <tr class="search_header">
            <th class="colum_hidden">
            	<input type="text" name="search_id" value="Filter" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_name" value="Filter" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_tin" value="Filter" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_tin" value="Filter" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_position" value="Filter" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_tin" value="Filter" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_position" value="Filter" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_address" value="ფილტრი" class="search_init" />
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
    <div id="add-edit-form" class="form-dialog" title="Staff">
    	<!-- aJax -->
	</div>
    <!-- jQuery Dialog -->
    <div id="image-form" class="form-dialog" title="Employee Photo">
    	<img id="view_img" src="media/uploads/images/worker/0.jpg">
	</div>
	 <!-- jQuery Dialog -->
    <div id="add-group-form" class="form-dialog" title="Group">
	</div>
	<div id="add-edit-form-img" class="form-dialog" title="Employee Photo">
	</div>
	
</body>
</html>