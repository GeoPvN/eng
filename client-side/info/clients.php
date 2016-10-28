<head>
<script type="text/javascript">
    var aJaxURL           = "server-side/info/clients.action.php";
    var aJaxURL_object    = "server-side/info/project.action.php";
    var aJaxURL_client    = "server-side/info/sub_clients.action.php";
    var aJaxURL_sub_project    = "server-side/info/sub_project.action.php";
    var aJaxURL_template    = "server-side/info/template.action.php";
    var aJaxURL_template_actived    = "server-side/info/template_actived.action.php";
    var aJaxURL_send_sms  = "includes/sendsms.php";
    var tbName1			  = "tabs1";
    var tName             = "table_";
    var dialog            = "add-edit-form";
    var colum_number      = 6;
    var main_act          = "get_list";
    var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl><'gg't>";
    
   
    $(document).ready(function () {
        
    	GetButtons("add_button","delete_button");
    	LoadTable('index',colum_number,main_act,change_colum_main);
    	SetEvents("add_button", "delete_button", "check-all", tName+'index', dialog, aJaxURL);
    	
    });

    function LoadTable(tbl,col_num,act,change_colum){
        
        client_id	= $("#hidden_client_id").val();
        project_id	= $("#hidden_project_id").val();
        wday = $('#weak_id').val()

        GetDataTable(tName+tbl, aJaxURL, act, col_num, "client_id="+client_id+"&project_id="+project_id+"&wday="+wday+"&cp="+$('#client_personal').val(), 0, "", 1, "desc", '', change_colum);

    	setTimeout(function(){
    		$('.ColVis, .dataTable_buttons').css('display','none');
    	}, 90);
    }
    
    function LoadDialog(fName){
    	switch(fName){
			case "add-edit-form":
		    	var buttons = {
						"save": {
				            text: "შენახვა",
				            id: "save-dialog"
				        },
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        }
				    };
		        GetDialog(fName, 777, "auto", buttons, 'left+43 top');
		        GetTabs(tbName1);
		        
		  
		        $(document).on("tabsactivate", "#tabs1", function() {
		        	var tab = GetSelectedTab(tbName1);
		            
			        if(tab==1){
				       
			        	$("#right_side fieldset").hide();
			        	$(".add-edit-form-class").css("width", "777");
			            hide_right_side();
				    }
		        });
		        LoadTable('project',5,'get_list_project',"<'F'lip>");
		        LoadTable('client',6,'get_list_person',"<'F'lip>");
		        SetEvents("add_project", "delete_project", "check-all-project", tName+'project', "add-edit-form-project", aJaxURL_object);
		        SetEvents("add_client", "delete_client", "check-all-client", tName+'client', "add-edit-form-client", aJaxURL_client);
		        $("#choose_button, #upload_file, #client_check, #add_client, #delete_client, #add_project, #delete_project, #choose_buttondisabled").button(); 
		        
	       break;
		   case "add-edit-form-project":
		    	var buttons = {
						"save": {
				            text: "შენახვა",
				            id: "save-project"
				        },
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        }
				    };
		        GetDialog("add-edit-form-project", 401, "auto", buttons, 'left+43 top');
		        LoadTable('number',5,'get_list_number',"<'F'lip>");
		        SetEvents("add_number", "delete_number", "check-all-number", tName+'number', "add-edit-form-number", aJaxURL_sub_project);
		        $("#add_number, #delete_number, #download_exel_client, #download_exel, #choose_button, #delete_import, #choose_button1, #add_import, #delete_import_actived, #add_import_actived, #open_choseFile").button(); 
		        GetDateTimes('project_add_date');
		        $('#client_personal').chosen({ search_contains: true,width: "257px" });
		        $('#project_type').chosen({ search_contains: true });
		        $("#client_personal_chosen").css('top','-3px');
		        $('.import').click();
		        LoadTable('import_client',10,'get_list_import',"<'F'lip>");
		        SetEvents("add_import", "delete_import", "check-all-import", tName+'import_client', "add-edit-form-import", aJaxURL_template,'cp='+$('#client_personal').val(),tName+'import',10,'get_list_import',change_colum_main);

		        LoadTable('import_actived_client',10,'get_list_import_actived',"<'F'lip>");
		        SetEvents("add_import_actived", "delete_import_actived", "check-all-import-actived", tName+'import_actived_client', "add-edit-form-import-actived", aJaxURL_template_actived);
		        if($('#scenario_id').val() != 0){
		            $('#choose_button1,#add_import').css('display','inline-block');
		        }else{
		        	$('#choose_button1,#add_import').css('display','none');
		        }
		        if($('#project_type').val() == 2){
		        	$('.import').css('display','block');
		        	$('.actived').css('display','block');
		        	$('.phone').css('display','none');        	
		        }else{
		        	$('.import').css('display','none');
		        	$('.actived').css('display','none');
		        	$('.phone').css('display','block');
		        }
		        $( "#hide_said_menu_number" ).click();
		        $('.import').click();

		   break;
		   case "add-edit-form-import":
		    	var buttons = {
						"save": {
				            text: "შენახვა",
				            id: "save-template"
				        },
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        }
				    };
		        GetDialog("add-edit-form-import", 450, "auto", buttons, 'left+43 top');
		   break;
		   case "add-edit-form-import-actived":
		    	var buttons = {
						"save": {
				            text: "შენახვა",
				            id: "save-template-actived"
				        },
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        }
				    };
		        GetDialog("add-edit-form-import-actived", 350, "auto", buttons, 'left+43 top');
		        $('#note_actived,#scenario_id').chosen({ search_contains: true });
		        $('#add-edit-form-import-actived,.add-edit-form-import-actived-class').css('overflow', 'visible');
		   break;
		   case "add-edit-form-client":
		    	var buttons = {
						"save": {
				            text: "შენახვა",
				            id: "save-client_person"
				        },
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        }
				    };
		        GetDialog("add-edit-form-client", 350, "auto", buttons, 'left+43 top');
		   break;
		   case "add-edit-form-number":
		    	var buttons = {
						"save": {
				            text: "შენახვა",
				            id: "save-number"
				        },
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        }
				    };
		        GetDialog("add-edit-form-number", 370, "auto", buttons, 'left+43 top');
		   break;  
		}
    }

    function show_right_side(id){
        $("#right_side fieldset").hide();
        $("#" + id).show();
        $(".add-edit-form-class").css("width", "1235");
        hide_right_side();
        var str = $("."+id).children('img').attr('src');
		str = str.substring(0, str.length - 4);
        $("."+id).children('img').attr('src',str+'_blue.png');
        $("."+id).children('div').css('color','#2681DC');
        GetDate1('add_date');
    	GetDate1('contract_start_date');
    	GetDate1('contract_end_date');
    }
    
    function show_right_side1(id){
        $("#right_side_project fieldset").hide();
        $("#" + id).show();
        $(".add-edit-form-project-class").css("width", "1200");
        //$('#add-edit-form').dialog({ position: 'left top' });
        hide_right_side1();
        var str = $("."+id).children('img').attr('src');
		str = str.substring(0, str.length - 4);
        $("."+id).children('img').attr('src',str+'_blue.png');
        $("."+id).children('div').css('color','#2681DC');
    }
    
    function hide_right_side(){
    	$("#side_menu").children('spam').children('div').css('color','#FFF');
        $(".info").children('img').attr('src','media/images/icons/info.png');
        $(".task").children('img').attr('src','media/images/icons/task.png');
        $(".sms").children('img').attr('src','media/images/icons/sms.png');
        $(".mail").children('img').attr('src','media/images/icons/mail.png');
        $(".record").children('img').attr('src','media/images/icons/record.png');
        $(".file").children('img').attr('src','media/images/icons/file.png');
    }
    function hide_right_side1(){
    	$("#side_menu1").children('spam').children('div').css('color','#FFF');
        $(".phone").children('img').attr('src','media/images/icons/info.png');
        $(".holiday").children('img').attr('src','media/images/icons/holiday.png');
        $(".import").children('img').attr('src','media/images/icons/import.png');
        $(".actived").children('img').attr('src','media/images/icons/actived.png');
    }

    $(document).on("change", "#client_personal", function () {
        if($(this).val() == 1){
        	$('#table_import_client_div,#table_import_actived_client_div').css('display','block');
        	$('#table_import_div,#table_import_actived_div').css('display','none');
        	$('#download_exel_client').css('display','inline');
        	$('#download_exel').css('display','none');
            LoadTable('import_client',10,'get_list_import',"<'F'lip>");
            SetEvents("add_import", "delete_import", "check-all-import", tName+'import_client', "add-edit-form-import", aJaxURL_template,'cp='+$('#client_personal').val(),tName+'import',10,'get_list_import',change_colum_main);

            LoadTable('import_actived_client',10,'get_list_import_actived',"<'F'lip>");
	        SetEvents("add_import_actived", "delete_import_actived", "check-all-import-actived", tName+'import_actived_client', "add-edit-form-import-actived", aJaxURL_template_actived);
        }else{
            $('#table_import_client_div,#table_import_actived_client_div').css('display','none');
            $('#table_import_div,#table_import_actived_div').css('display','block');
            $('#download_exel_client').css('display','none');
        	$('#download_exel').css('display','inline');
            LoadTable('import',6,'get_list_import',"<'F'lip>");
            SetEvents("add_import", "delete_import", "check-all-import", tName+'import', "add-edit-form-import", aJaxURL_template,'cp='+$('#client_personal').val(),tName+'import',6,'get_list_import',change_colum_main);

            LoadTable('import_actived',7,'get_list_import_actived',"<'F'lip>");
	        SetEvents("add_import_actived", "delete_import_actived", "check-all-import-actived", tName+'import_actived', "add-edit-form-import-actived", aJaxURL_template_actived);
        }
    });
    
    function show_main(id,my_this){
    	$("#client_main,#client_other").hide();
    	$("#" + id).show();
    	$(".client_main,.client_other").css('border','none');
    	$(".client_main,.client_other").css('padding','6px');
    	$(my_this).css('border','1px solid #ccc');
    	$(my_this).css('border-bottom','1px solid #F9F9F9');
    	$(my_this).css('padding','5px');
    }

    function client_status(id){
    	$("#pers,#iuri").hide();
    	$("#" + id).show();
    }
    $(document).on("keyup", "#import_pid", function () {
    	if($(this).val().length >11){
    	    alert('პირადი ნომერი არ უნდა იყოს 11 ციფრზე მეტი!');
    	}
    });
    
    $(document).on("click", "#open_choseFile", function () {
    	var buttons = {
						
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        }
				    };
		        GetDialog("add-edit-form-choseFile", 227, "auto", buttons, 'left+43 top');
    });
    
    $(document).on("click", ".hide_said_menu", function () {
    	$("#right_side fieldset").hide();
    	$(".add-edit-form-class").css("width", "777");
        //$('#add-edit-form').dialog({ position: 'top' });
        hide_right_side();
    });
    $(document).on("click", "#hide_said_menu_number", function () {
    	$("#right_side_project fieldset").hide();
    	$(".add-edit-form-project-class").css("width", "401");
        //$('#add-edit-form').dialog({ position: 'top' });
        hide_right_side1();
    });

    $(document).on("click", "#show_copy_prit_exel", function () {
        
        if($(this).attr('myvar') == 0){
            $('.ColVis,.dataTable_buttons,#table_right_menu_content').css('display','block');
            $(this).css('background','#2681DC');
            $(this).children('img').attr('src','media/images/icons/select_w.png');
            $(this).attr('myvar','1');
        }else{
        	$('.ColVis,.dataTable_buttons,#table_right_menu_content').css('display','none');
        	$(this).css('background','#E6F2F8');
            $(this).children('img').attr('src','media/images/icons/select.png');
            $(this).attr('myvar','0');
        }
    });
    
    $(document).on("click", "#save-template", function () {
    	param = new Object();

        //Action
    	param.act	= "save-import";
    	param.import_id         = $("#import_id").val();
	    param.hidden_project_id	= $("#hidden_project_id").val();
	    param.project_hidden_id = $("#project_hidden_id").val();
	    param.note              = $("#note").val();
	    param.import_fname		= $("#import_fname").val();
	    param.import_lname		= $("#import_lname").val();
	    param.import_pid		= $("#import_pid").val();
	    param.import_date		= $("#import_date").val();
	    param.import_age		= $("#import_age").val();
	    param.import_sex	    = $("#import_sex").val();
	    param.import_phone1	    = $("#import_phone1").val();
	    param.import_phone2		= $("#import_phone2").val();
	    param.import_mail1		= $("#import_mail1").val();
	    param.import_mail2		= $("#import_mail2").val();
	    param.import_address1	= $("#import_address1").val();
	    param.import_address2	= $("#import_address2").val();
	    param.import_id_code    = $("#import_id_code").val();
	    param.import_client_name= $("#import_client_name").val();
	    param.import_activities = $("#import_activities").val();
	    param.import_note		= $("#import_note").val();
	    param.import_info1		= $("#import_info1").val();
	    param.import_info2		= $("#import_info2").val();
	    param.import_info3		= $("#import_info3").val();
	   
	   
	    $.ajax({
	        url: aJaxURL_template,
		    data: param,
	        success: function(data) {
				if(typeof(data.error) != "undefined"){
					if(data.error != ""){
						alert(data.error);
					}else{
						LoadTable('import',6,'get_list_import',"<'F'lip>");
						$("#add-edit-form-import").dialog("close");
					}
				}
		    }
	    });
		
    });

    $(document).on("click", "#save-template-actived", function () {
    	param = new Object();

        //Action
    	param.act	            = "save-import-actived";
	    param.hidden_project_id	= $("#hidden_project_id").val();
	    param.project_hidden_id = $("#project_hidden_id").val();
	    param.actived_number    = $("#actived_number").val();
	    param.scenario_id       = $("#scenario_id").val();
	    param.note              = $("#note_actived").val();
	   
	   
	    $.ajax({
	        url: aJaxURL_template_actived,
		    data: param,
	        success: function(data) {
				if(typeof(data.error) != "undefined"){
					if(data.error != ""){
						alert(data.error);
					}else{
						LoadTable('import_actived',7,'get_list_import_actived',"<'F'lip>");
						$("#add-edit-form-import-actived").dialog("close");
					}
				}
		    }
	    });
		
    });
    
   function listen(file){
        var url = location.origin + "/records/" + file
        $("audio source").attr('src',url)
    }
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
            alert("დაშვებულია მხოლოდ 'pdf', 'png', 'xls', 'xlsx', 'jpg', 'docx', 'doc', 'csv' გაფართოება");
        }else if(file_size > '15728639'){
            alert("ფაილის ზომა 15MB-ზე მეტია");
        }else{
        	$.ajaxFileUpload({
		        url: "server-side/upload/file.action.php",
		        secureuri: false,
     			fileElementId: "file_name",
     			dataType: 'json',
			    data: {
					act: "file_upload",
					button_id: "file_name",
					table_name: 'client_contract',
					file_name: Math.ceil(Math.random()*99999999999),
					file_name_original: file_name,
					file_type: file_type,
					file_size: file_size,
					path: path,
					table_id: $("#hidden_clientcontract_id").val(),

				},
		        success: function(data) {			        
			        if(typeof(data.error) != 'undefined'){
						if(data.error != ''){
							alert(data.error);
						}else{
							var tbody = '';
							for(i = 0;i <= data.page.length;i++){
								tbody += "<div style=\"border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 112px;float:left;height: 25px;\">" + data.page[i].file_date + "</div>";
								tbody += "<div style=\"border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 110px;float:left;height: 25px;\">" + data.page[i].name + "</div>";
								tbody += "<div style=\"border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;cursor: pointer;width: 110px;float:left;height: 25px;\" onclick=\"download_file('" + data.page[i].rand_name + "', '"+data.page[i].name+"')\">ჩამოტვირთვა</div>";
								
								tbody += "<div style=\"width: 45px;\" id=\"for_div\" onclick=\"delete_file('" + data.page[i].id + "')\">წაშლა</div>";
								
								$("#paste_files").html(tbody);
							}							
						}						
					}					
			    }
		    });
        }
    });
    //ufload image//
    $(document).on("click", "#choose_button", function () {
	    $("#choose_file").click();
	});
    $(document).on("click", "#choose_button1", function () {
	    $("#choose_file1").click();
	});
    
    $(document).on("change", "#scenario_id", function () {
        if($(this).val() != 0){
            $('#choose_button1,#add_import').css('display','inline-block');
        }else{
        	$('#choose_button1,#add_import').css('display','none');
        }
    });
    
    $(document).on("change", "#choose_file1", function () {

    	var file		= $(this).val();
	    var name		= uniqid();
	    var path		= "../../media/uploads/images/client/";

	    var ext = file.split('.').pop().toLowerCase();
        if($.inArray(ext, ['xls']) == -1) {
        	alert('This is not an allowed file type.');
            this.value = '';
        }else{
        	img_name = name + "." + ext;
        	$.ajaxFileUpload({
        		url: "server-side/upload/file.action.php",
    			secureuri: false,
    			fileElementId: "choose_file1",
    			dataType: 'json',
    			data:{
					act: "upload_file",
					path: path,
					file_name: name,
					project_other_id: $('#project_hidden_id').val(),
					project_id: $('#hidden_project_id').val(),
					cp: $('#client_personal').val(),
					note: $('#note').val(),
					type: ext
				},
				complete: function(data){
					alert('ფაილი აიტვირთა!');
					LoadTable('import',6,'get_list_import',"<'F'lip>");
					$('add-edit-form-choseFile').dialog('close');
				},

			});

        }

        
    });
    
    $(document).on("change", "#choose_file", function () {
        var file_url  = $(this).val();
        var file_name = this.files[0].name;
        var file_size = this.files[0].size;
        var file_type = file_url.split('.').pop().toLowerCase();
        var path	  = "../../media/uploads/file/";
		
        if($.inArray(file_type, ['pdf','png','xls','xlsx','jpg','docx','doc','csv']) == -1){
            alert("დაშვებულია მხოლოდ 'pdf', 'png', 'xls', 'xlsx', 'jpg', 'docx', 'doc', 'csv' გაფართოება");
        }else if(file_size > '25728639'){
            alert("ფაილის ზომა 25MB-ზე მეტია");
        }else{
        	$.ajaxFileUpload({
		        url: "server-side/upload/file.action.php",
		        secureuri: false,
     			fileElementId: "choose_file",
     			dataType: 'json',
			    data: {
					act: "file_upload",
					button_id: "choose_file",
					table_name: 'client',
					file_name: Math.ceil(Math.random()*99999999999),
					file_name_original: file_name,
					file_type: file_type,
					file_size: file_size,
					path: path,
					table_id: $("#hidden_client_id").val(),

				},
		        success: function(data) {	
					$("#upload_img").attr("src", "media/uploads/file/" + data.page[0].rand_name);
					$('#choose_button').attr('id','choose_buttondisabled');
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
    
    function download_file(file,fname){
        var download_file	= "media/uploads/file/"+file;
    	var download_name 	= fname;
    	SaveToDisk(download_file, download_name);
    }
    
    function delete_file(id){
    	$.ajax({
            url: "server-side/upload/file.action.php",
            data: "act=delete_file&file_id="+id+"&table_name=client_contract",
            success: function(data) {
               
            	var tbody = '';
            	if(data.page.length == 0){
            		$("#paste_files").html('');
            	};
				for(i = 0;i <= data.page.length;i++){
					tbody += "<div style=\"border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 112px;float:left;height: 25px;\">" + data.page[i].file_date + "</div>";
					tbody += "<div style=\"border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 110px;float:left;height: 25px;\">" + data.page[i].name + "</div>";
					tbody += "<div style=\"border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;cursor: pointer;width: 110px;float:left;height: 25px;\" onclick=\"download_file('" + data.page[i].rand_name + "','"+data.page[i].name+"')\">ჩამოტვირთვა</div>";
					tbody += "<div style=\"width: 45px;\" id=\"for_div\" onclick=\"delete_file('" + data.page[i].id + "')\">წაშლა</div>";
					
					$("#paste_files").html(tbody);
				}	
            }
        });
    }
    
    function SaveToDisk(fileURL, fileName) {
    	var iframe = document.createElement("iframe"); 
        iframe.src = fileURL; 
        iframe.style.display = "none"; 
        document.body.appendChild(iframe);
        return false;
    }

    // Add - Save
	$(document).on("click", "#save-dialog", function () {
		param = new Object();

        //Action
    	param.act	= "save_client";

	    param.id				= $("#hidden_id").val();

	    param.identity_code		= $("#identity_code").val();
	    param.client_name		= $("#client_name").val();
	    param.jurid_address		= $("#jurid_address").val();
	    param.fact_address		= $("#fact_address").val();

	    //კონტრაქტი//
	    param.contract_number		= $("#contract_number").val();
	    param.add_date				= $("#add_date").val();
	    param.contract_start_date	= $("#contract_start_date").val();
	    param.contract_end_date		= $("#contract_end_date").val();
	    param.contract_price		= $("#contract_price").val();
	    param.angarish_period		= $("#angarish_period").val();
	    param.angarish_period1		= $("#angarish_period1").val();

	    //დოკუმენტი//
	    param.invois			= $("input[id='invois']:checked").val();
	    param.migeba_chabareba	= $("input[id='migeba_chabareba']:checked").val();
	    param.angarishfaqtura	= $("input[id='angarishfaqtura']:checked").val();
	   
	   
	   if(param.identity_code == ""){
			alert("შეავსეთ საიდენტიპიკაციო კოდი!");
		}else if(param.client_name == ""){
			alert("შეავსეთ  დასახელება!");
		}else{
		    $.ajax({
		        url: aJaxURL,
			    data: param,
		        success: function(data) {
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							LoadTable('index',colum_number,main_act,change_colum_main);
							$("#add-edit-form").dialog("close");
						}
					}
			    }
		    });
		}

	});

	$(document).on("click", "#save-client_person", function () {
		param = new Object();

        //Action
    	param.act	= "save-client_person";

	    param.hidden_client_id	= $("#hidden_client_id").val();
	    param.hidden_id	= $("#person_hidden_id").val();
	    
	    param.person_name		= $("#person_name").val();
	    param.person_surname	= $("#person_surname").val();
	    param.person_posityon	= $("#person_posityon").val();
	    param.person_mobile		= $("#person_mobile").val();
		param.person_phone		= $("#person_phone").val();
	    param.person_comment	= $("#person_comment").val();
	    
	   
	   
	   if(param.person_name == ""){
			alert("შეავსეთ სახელი!");
		}else{
		    $.ajax({
		        url: aJaxURL_client,
			    data: param,
		        success: function(data) {
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							LoadTable('client',6,'get_list_person',"<'F'lip>");
							$("#add-edit-form-client").dialog("close");
						}
					}
			    }
		    });
		}

	});
	$(document).on("click", "#save-project", function () {
		param = new Object();

        //Action
    	param.act	= "save-project";

	    param.hidden_client_id	= $("#hidden_client_id").val();
	    param.project_hidden_id	= $("#project_hidden_id").val();
	    
	    param.project_name		= $("#project_name").val();
	    param.project_type		= $("#project_type").val();
	    param.project_add_date	= $("#project_add_date").val();
	    param.start_date_holi	= $("#start_date_holi").val();
	    param.end_date_holi	    = $("#end_date_holi").val();
	   
	   if(param.person_name == ""){
			alert("შეავსეთ სახელი!");
		}else{
		    $.ajax({
		        url: aJaxURL_object,
			    data: param,
		        success: function(data) {
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							
							 LoadTable('project',5,'get_list_project',"<'F'lip>");
							$("#add-edit-form-project").dialog("close");
						}
					}
			    }
		    });
		}

	});

	$(document).on("click", "#save-number", function () {
		param = new Object();

        //Action
    	param.act	= "save-number";

	    param.number_hidden_id	= $("#number_hidden_id").val();
	    
	    param.hidden_project_id	= $("#hidden_project_id").val();
	    
	    param.project_number	= $("#project_number").val();
	    param.project_queue		= $("#project_queue").val();
	    
	   
	    
	   
	   
	   if(param.person_name == ""){
			alert("შეავსეთ სახელი!");
		}else{
		    $.ajax({
		        url: aJaxURL_sub_project,
			    data: param,
		        success: function(data) {
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							LoadTable('number',5,'get_list_number',"<'F'lip>");
							$("#add-edit-form-number").dialog("close");
						}
					}
			    }
		    });
		}

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

	$(document).on("click", "#download_exel", function () {
		SaveToDisk('client-side/info/template.xls', 'template.xls');
    });

	$(document).on("click", "#download_exel_client", function () {
		SaveToDisk('client-side/info/template.xls', 'template.xls');
    });
    
	$(document).on("change", "#project_type", function () {
        if($(this).val() == 2){
        	$('.import').css('display','block');
        	$('.actived').css('display','block');
        	$('.phone').css('display','none');       	
        }else{
        	$('.import').css('display','none');
        	$('.actived').css('display','none');
        	$('.phone').css('display','block');
        }
        $( "#hide_said_menu_number" ).click();
	});
    
</script>
<style type="text/css">


.callapp_tabs span close{
	cursor: pointer;
	margin-left: 5px;
}

#table_project_length,
#table_number_length,
#table_import_length,
#table_import_client_length,
#table_import_actived_length,
#table_import_actived_client_length,
#table_client_length,
#table_holiday_length,
#table_break_length,
#table_week_length,
#table_lang_length,
#table_infosorce_length{
	position: inherit;
    width: 0px;
	float: left;
}
#table_project_length label select,
#table_number_length label select,
#table_import_length label select,
#table_import_client_length label select,
#table_import_actived_length label select,
#table_import_actived_client_length label select,
#table_client_length label select,
#table_holiday_length label select,
#table_break_length label select,
#table_week_length label select,
#table_lang_length label select,
#table_infosorce_length label select{
	width: 60px;
    font-size: 10px;
    padding: 0;
    height: 18px;
}


#table_client_paginate{
	margin-left: 45px;
}
#table_number_paginate,
#table_import_paginate,
#table_import_client_paginate,
#table_import_actived_paginate,
#table_import_actived_client_paginate,
#table_holiday_actived_paginate,
#table_break_actived_paginate{
	margin-left: -22px;
}

</style>
</head>

<body>
	<div id="tabs" style="width: 90%;">
		<div class="callapp_head">კლიენტები<hr class="callapp_head_hr"></div>
<div style="margin-bottom: 5px;">
<div id="instruqcia">ინსტრუქცია</div>
<table id="stepby">
<tr>
<td  onclick="location.href='index.php?pg=18';" >კითხვა/პასუხი >></td><td onclick="location.href='index.php?pg=17';" >სცენარის კატეგორია >></td><td onclick="location.href='index.php?pg=16';">სცენარი >></td><td onclick="location.href='index.php?pg=15';">რიგი >></td><td style="color: #FFF;background: #2681DC;" onclick="location.href='index.php?pg=14';">კლიენტები</td>
</tr>
</table>
</div>
		
		<div >
		
			<button id="add_button">დამატება</button>
			<button id="delete_button">წაშლა</button>
		  
			<table style="margin-top: 10px;" id="table_right_menu">
				<tr>
					<td>
						<img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
					</td>
					<td>
						<img alt="log" src="media/images/icons/log.png" height="14" width="14">
					</td>
					<td id="show_copy_prit_exel" myvar="0">
						<img alt="link" src="media/images/icons/select.png" height="14" width="14">
					</td>
				</tr>
			</table>
		
			<table class="display" id="table_index" style="width: 100%;">
			    <thead>
			        <tr id="datatable_header">
			            <th>ID</th>
			            <th style="width: 35px;">№</th>
			            <th style="width: 100%;">დასახელება</th>
			            <th style="width: 100%;">საიდენტიფიკაციო კოდი</th>
			          	<th style="width: 100%;">იურიდიული მისამართი</th>                            
			            <th style="width: 100%;">ფაქტიური მისამართი</th>
			            <th style="width: 30px; margin-top: 3px;" class="check">&nbsp;</th>
			        </tr>
			    </thead>
			    <thead>
			        <tr class="search_header">
			            <th class="colum_hidden">
			        	   <input type="text" name="search_id" value="ფილტრი" class="search_init" />
			            </th>
			            <th>
			            	<input type="text" name="search_number" value="ფილტრი" class="search_init" />
			            </th>
			            <th>
			                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
			            </th>    
			            <th>
			                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
			            </th>
			            <th>
			                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
			            </th>                         
			            <th>
			                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
			            </th>
			            <th style="border-right: 1px solid #E6E6E6 !important;">
			            	<div class="callapp_checkbox">
			                    <input type="checkbox" id="check-all" name="check-all" />
			                    <label for="check-all" style="margin-top: 3px;"></label>
			                </div>
			            </th>  
			        </tr>
			    </thead>
			</table>
		
	</div>

	<!-- jQuery Dialog -->
	<div  id="add-edit-form" class="form-dialog" title="კლიენტი">
	</div>
	
	<div  id="add-edit-form-client" class="form-dialog" title="საკონტაქტო პირი">
	</div>
	
	<div  id="add-edit-form-project" class="form-dialog" title="პროექტი">
	</div>
	
	<div  id="add-edit-form-number" class="form-dialog" title="ნომერი">
	</div>
	
	<div  id="add-edit-form-import" class="form-dialog" title="იმპორტი">
	</div>
	
	<div  id="add-edit-form-import-actived" class="form-dialog" title="აქტივაცია">
	</div>
	
	<div  id="add-edit-form-img" class="form-dialog" title="ფოტო">
	</div>
	
	<div  id="add-edit-form-choseFile" class="form-dialog" title="ფაილის არჩევა">
    	<div id="dialog-form">
    	    <fieldset style="width: 175px;">
    	       <legend>ძირითადი ინფორმაცია</legend>
            	<div><span style="display:inline-block;margin-bottom: 5px;">შენიშვნა</span>
                <input type="text" id="note" value="" style="margin-bottom: 5px;">
                </div>
                <input id="choose_file1" type="file" name="choose_file1" class="input" style="display: none;">
                <button id="choose_button1" >აირჩიეთ ფაილი</button>
            </fieldset>
        </div>
	</div>
	
	<div  id="add-edit-form-hour" class="form-dialog" title="წუთი">
	</div>
	<div  id="add-edit-form-weekADD" class="form-dialog" title="დამატება">
	</div>
	<div  id="add-edit-form-week" class="form-dialog" title="სამუშაო გრაფიკი">
	</div>
	<div  id="add-edit-form-lang" class="form-dialog" title="სასაუბრო ენა">
	</div>
	<div  id="add-edit-form-infosorce" class="form-dialog" title="ინფორმაციის წყარო">
	</div>

</body>