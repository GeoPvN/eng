<?php 
$user_id = $_SESSION['USERID'];
?>
<html>
<head>
<style type="text/css">
<?php                    		
if($_SESSION['USERID'] == 5 || $_SESSION['USERID'] == 2 ){
   
 
}else{
     echo '.dataTable_buttons{
            display:none;
        }';
}
?>
#exel_tabel{
    width: 100%;
}

#exel_tabel th{
    border: 2px solid;
    font-size: 11px;
    font-weight: normal;
    text-align: center;
    padding: 3px;
    font-weight: bold;
}

#exel_tabel td{
    border: 2px solid;
    font-size: 11px;
    font-weight: normal;
    text-align: center;
    padding: 3px;
}

</style>
		<link href="media/css/main/header.css" rel="stylesheet" type="text/css" />
    	<link href="media/css/main/mainpage.css" rel="stylesheet" type="text/css" />
    	<link href="media/css/main/tooltip.css" rel="stylesheet" type="text/css" />
    	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
</head>
<script src="js/circular-countdown.js"></script>
<script>
var aJaxURL	  = "server-side/info/tabel.action.php";		    //server side folder url
var l_aJaxURL = "server-side/info/tabel/worker_job_time.php"; //list
var tName	  = "report";											//table name
var fName	  = "add-edit-form";								    //form name
var img_name  = "0.jpg";

$(document).ready(function () {  
	
	 $.ajax({
	        url: aJaxURL,
		    data: "act=check_status",
		    dataType: "json",
	        success: function(data) {
	            if (data.error != "") {
	                alert(data.error);
	            }else {
					if(data.check==1){
						$('#opper_status').html('სისტემიდან გასული');
    				}else{
    	            	$('#opper_status').html('სისტემაში შემოსული');
    				}
		    	}
	        }
	});

});

function LoadTable(start, end, person_id, password){
	var start	    = $("#search_start").val();
	var end		    = $("#search_end").val();
	var person_id   = $("#user1").val();
	total = [4,5,6,7];
	/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
	GetDataTable3(tName, l_aJaxURL, "get_list", 8, "start=" + start + "&end=" + end + "&person_id=" + person_id + "&password=" + password, 0, "", 1, "desc",total);
	$('.dataTable_buttons').css('display', 'block');
}

$(document).on("click", "#go_home", function () {
	var button = {
			"save": {
	            text: "შენახვა",
	            id: "save-dl",
	            click: function () {
	            	$.ajax({
	                    url: aJaxURL,
	            	    data: "act=break_checker&logout_actions=" + $("#logout_actions").val(),
	            	    dataType: "json",
	                    success: function(data) {
		                    if(data.checker == 1){
        	            	$.ajax({
        	                    url: aJaxURL,
        	            	    data: "act=save_act&action=" + 3 + "&logout_actions=" + $("#logout_actions").val() + "&logout_comment=" + $("#logout_comment").val(),
        	            	    dataType: "json",
        	                    success: function(data) {
        	                        if (data.error != "") {
        	                            alert(data.error);
        	                        }else {
        	                        	if(data.done==1){
        	                        		  location.reload();
        	                        	}
        	                        	if(data.status==2){
        	                        		
        	                        		$('#disable_all').css('display', 'block');
        	                        		$('#disable_all').css('z-index', 999999);
        	                        		$('#disable_all').css('background', 'rgba(54, 25, 25, .5)');
        	                        		$('#come_in').css('z-index', 9999999);
        	                        		$('.come-in-form-class').css('z-index', 9999999);
        	                        		$('#opper_status').html('შესვენებაზე გასული');
        
        	                        		$("#dis_timer").css('display','block');
        	                        		$(".timer").html('');
        	                        		$('.timer').circularCountDown({
        	                                    delayToFadeIn: 500,
        	                                    size: 400,
        	                                    fontColor: '#fff',
        	                                    fontSize: 24,
        	                                    colorCircle: 'white',
        	                                    background: 'red',
        	                                    reverseLoading: false,
        	                                    duration: {
        	                                        seconds: parseInt(data.timer)
        	                                    },
        	                                    beforeStart: function() {
        	                                        $('.launcher').hide();
        	                                    },
        	                                    end: function(countdown) {
        	                                        countdown.destroy();
        	                                        $('.launcher').show();
        	                                        
        	                                    }
        	                                });
        	                        		var button = {
        	                        				"cancel": {
        	                        		            text: "დახურვა",
        	                        		            id: "cancel-dialog",
        	                        		            click: function () {
        	                        		                $(this).dialog("close");
        	                        		            }
        	                        		        }
        	                        		};
        	                        		GetDialog("alert_dialog", 360, "auto", button);
        	                        		$('#alert_dialog').html('ოპერაცია წარმატებით განხორციელდა');
        	                        		$('.alert_dialog-class').css('z-index','9999999');
        	                        	}
        	                        }
        	            	    }
        	                });
      	            	  $("#lg_out").dialog("close");
		                    }else{
		                    	var button = {
                        				"cancel": {
                        		            text: "დახურვა",
                        		            id: "cancel-dialog",
                        		            click: function () {
                        		                $(this).dialog("close");
                        		            }
                        		        }
                        		};
                        		GetDialog("alert_dialog", 360, "auto", button);
                        		$('#alert_dialog').html('ამჟამად თქვენ ვერ შეძლებთ შესვენებაზე გასვლას, სცადეთ მოგვიანებით');
                        		$('.alert_dialog-class').css('z-index','9999999');
		                    }
	                    }
	                });
	            }
	        },
		      "cancel": {
		            text: "დახურვა",
		            id: "cancel-dialog",
		            click: function () {
		                $(this).dialog("close");
		            }
		        }
		    };
    GetDialog("lg_out", 360, "auto", button);

    $.ajax({
        url: aJaxURL,
	    data: "act=gdl",
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
            	$("#lg_out").html(data.page);
            }
	    }
    });
});


$(document).on("click", "#come_in", function () {
	
    $.ajax({
        url: aJaxURL,
	    data: "act=save_act&action=" + 4,
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
            	if(data.status==3){
            		var button = {
            				"cancel": {
            		            text: "დახურვა",
            		            id: "cancel-dialog",
            		            click: function () {
            		                $(this).dialog("close");
            		            }
            		        }
            		};
            		GetDialog("alert_dialog", 360, "auto", button);
            		$('#alert_dialog').html('ოპერაცია წარმატებით განხორციელდა');
            		$('.alert_dialog-class').css('z-index','9999999');
                	$('#disable_all').css('z-index', 0);
            		$('#come_in').css('z-index', 1);
            		$('#disable_all').css('background', 'none');
            		$('#opper_status').html('სისტემაში შემოსული');
            		$("#dis_timer").css('display','none');
            	}
            }
	    }
    });	
});

$(document).on("click", "#report tbody tr", function () {
	
	var id = $(this).children(0).html()
	
	$.ajax({
        url: aJaxURL,
	    data: "act=get_deep",
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
            	var button = {
          		      
            	        "cancel": {
            	            text: "დახურვა",
            	            id: "cancel-dialog",
            	            click: function () {
            	                $(this).dialog("close");
            	            }
            	        }
            	    };
            	GetDialog("balance-form-deep",890, 'auto', button);
            	$("#balance-form-deep").html(data.page);
            	//var total = [1,2,3,4];
            	GetDataTable('report_deep', l_aJaxURL, "get_list_deep", 8, "id=" + id, 0, "", 0, "asc");
            	$('.dataTable_buttons').css('display', 'block');
            }
	    }
    });
	
});

$(document).on("click", "#balance", function () {
	

    $.ajax({
        url: aJaxURL,
	    data: "act=get_balance",
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
                
                $("#balance-form").html(data.page);  
    			$("#check").button({
    	            icons: {
    	                primary: "ui-icon-circle-check"
    	            }
            	});

    			GetDate("search_start");
    			GetDate("search_end");
    			
     			$("#search_start").val(GetDateTime(2));
     			$("#search_end").val(GetDateTime(2));

     			$("#user1").chosen({width: "225px"});
    			
     			LoadTable();
     			
    			var button = {
          				      "cancel": {
            			            text: "დახურვა",
            			            id: "cancel-dialog",
            			            click: function () {
            			                $(this).dialog("close");
            			            }
            			        }
            			    };
                GetDialog("balance-form",850, "auto", button);
                $("#exel_button").button();
                
                $('.dataTable_buttons').css('display', 'block');
                
            }
	    }
    });
	 
});

$(document).on("click", "#exel_button", function () {


	var button = {
		      "cancel": {
		            text: "დახურვა",
		            id: "cancel-dialog",
		            click: function () {
		                $(this).dialog("close");
		            }
		        }
		    };
    GetDialog("balance-form-excel",1250, "auto", button);

	param = new Object();
	
	param.act	    = 'excel';
 	param.start	    = $("#search_start").val();
 	param.end       = $("#search_end").val();
 	param.group_id  = $("#user1").val();
	
  	$.ajax({
	        url: 'server-side/info/tabel_excel.action.php',
		    data: param,
	        success: function(data) {
	            $('#balance-form-excel').html(data);
		    }
	    });
  	
});



function SaveToDisk(fileURL, fileName) {
	var iframe = document.createElement("iframe"); 
    iframe.src = fileURL; 
    iframe.style.display = "none"; 
    document.body.appendChild(iframe);
    return false;
}

$(document).on("click", "#check", function () {
    LoadTable();
});

$(document).on("change", "#search_start", function () {
	LoadTable();
});

$(document).on("change", "#search_end", function () {
	LoadTable();
});

$(document).on("click", "#goexcel", function (e) {
	var table = 'exel_tabel';

	var tableToExcel = (function() {
          var uri = 'data:application/vnd.ms-excel;base64,'
            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table  border="1px">{table}</table></body></html>'
            , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
            , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
          return function(table, name) {
            if (!table.nodeType) table = document.getElementById(table)
            var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
            window.location.href = uri + base64(format(template, ctx))
          }
        })();
	tableToExcel(table, 'excel export');
});
</script>
<body onselectstart='return false;'>
<div style="margin-top: 15px;">
	<div style="margin-left: 298px; font-size: 17px; margin-top: -10px; color: #000;">სტატუსი:</div>
    <div id="opper_status" style="margin-left: 380px; margin-top: -15px; color: red; font-size: 15px;">
    </div>
    <div id="ContentHolder">  
        <div class="content"> 
            <table class="tiles" style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="padding: 30px 100px 50px 90px;">
                            <div  class="tile_large" id="come_in"  style="background: #A0C64B;" >
            					<div class="tile_icon" style="margin-top: 10px;">
            					<img src="media/images/w_come_in.png" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
        					</div>
        					<p style="margin-top: 22px; margin-left: 80px">მოსვლა</p>
            				</div>
                        </td>
                        <td style="padding: 30px 100px 50px 50px;">
                            <div  class="tile_large" id="go_home" style="background: #A0C64B;" >
            					<div class="tile_icon" style="margin-top: 10px;">
            					<img src="media/images/w_go_home.png" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
        					</div>
        					<p style="margin-top: 22px; margin-left: 80px">გასვლა</p>
            				</div>
                        </td>
                    </tr>
                    <!-- tr>
                        <td style="padding: 30px 100px 50px 90px;">
                            <div  class="tile_large" id="relax" style="background: #A0C64B;" >
        					<div class="tile_icon" style="margin-top: 10px;">
        					<img src="media/images/w_relax.png" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
        					</div><p style="margin-top: 22px; margin-left: 80px">შესვენებაზე გასვლა</p>
            				</div>
                        </td>
                        <td style="padding: 30px 100px 50px 50px;">
                            <div  class="tile_large" id="back_relax" style="background: #A0C64B;" >
            					<div class="tile_icon" style="margin-top: 10px;">
            					<img src="media/images/w_back.png" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
        					</div>
        					<p style="margin-top: 22px; margin-left: 80px"> შესვენებიდან მოსვლა</p>
            				</div>
                        </td>
                    </tr -->
                    <tr>
                       <td style="padding: 30px 100px 50px 280px;" colspan="2">
                            <div  class="tile_large" id="balance" style="background: #A0C64B;" >
            					<div class="tile_icon" style="margin-top: 10px;">
            					<img src="media/images/w_balance.png" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
        					</div>
        					<p style="margin-top: 22px; margin-left: 80px">ბალანსი</p>
            				</div>
                       </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
        
    <div id="timer_html" class="form-dialog" title="ტაიმერი">
    	<!-- aJax -->
    	<div style="text-align: center;font-size: 15px;margin-top: 12px;">თქვენ დარჩენილი გაქვთ <span id="timer_value" style="font-size: 18px;font-weight: bold;">0</span> წამი</div>
	</div>
	
	<div id="lg_out" class="form-dialog" title="ქმედება">
    	<!-- aJax -->
	</div>
	
	<div id="balance-form" class="form-dialog" title="ბალანსი">
    	<!-- aJax -->
	</div>
	
	<div id="balance-form-deep" class="form-dialog" title="ბალანსი">
    	<!-- aJax -->
	</div>
	
	<div id="balance-form-excel" class="form-dialog" title="ექსელში გატანა">

	</div>
	
	<div id="alert_dialog" class="form-dialog" title="შეტყობინება">

	</div>
	
	<div id="dis_timer" style="position: absolute; top: 35%; left: 35%;z-index: 999999;">
    <div class="timer"></div>
    </div>
</body>
</html>
