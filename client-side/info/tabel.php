<html>
<head>
		<link href="media/css/main/header.css" rel="stylesheet" type="text/css" />
    	<link href="media/css/main/mainpage.css" rel="stylesheet" type="text/css" />
    	<link href="media/css/main/tooltip.css" rel="stylesheet" type="text/css" />
    	<style type="text/css">
        #report_length label select,
        #report_deep_length label select{
        	width: 60px;
            font-size: 10px;
            padding: 0;
            height: 18px;
        }
        #report_length,#report_deep_length{
        	top: 3px;
        }
        
        </style>
</head>
<script>
var aJaxURL	  = "server-side/info/tabel.action.php";		    //server side folder url
var l_aJaxURL = "server-side/info/tabel/worker_job_time.php"; //list
var tName	  = "report";											//table name
var fName	  = "add-edit-form";								    //form name
var img_name  = "0.jpg";
var change_colum_main = "<'F'lip>";

$(document).ready(function () {       
	
});

function LoadTable(start, end, person_id, password){
	var start	    = $("#search_start").val();
	var end		    = $("#search_end").val();
	var person_id   = $("#user1").val();
	total = [3,4,5,6];
	/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
	GetDataTable3("report", l_aJaxURL, "get_list", 7, "start=" + start + "&end=" + end + "&person_id=" + person_id + "&password=" + password, 0, "", 1, "desc",total,change_colum_main);
}

$(document).on("click", "#save-dialog", function () {

	param = new Object();

	param.act           = "save_act";
	param.user          = $("#user").val();
	param.pwd           = $("#password").val();
	param.action        = $("#action").val();
	param.comment_start = $("#comment_start").val();
	param.comment_end   = $("#comment_end").val();
	
    $.ajax({
        url: aJaxURL,
	    data: param,
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
            	$("#come-in-form").dialog("close"); 
            	alert("ოპერაცია წარმატებით დასრულდა!"); 
            }
	    }
    }).done(function() {
    	if(param.action == 3){
    		$('#disable_all').css('display', 'block');
    		$('#disable_all').css('z-index', 999999);
    		$('#disable_all').css('background', 'rgba(54, 25, 25, .5)');
    		$('#back_relax').css('z-index', 9999999);
    		$('.come-in-form-class').css('z-index', 9999999);
    	}else{
    		$('#disable_all').css('z-index', 0);
    		$('#back_relax').css('z-index', 1);
    		$('#disable_all').css('background', 'none');
    	}
    });	
    
});


$(document).on("click", "#come_in", function () {
	
    $.ajax({
        url: aJaxURL,
	    data: "act=get_come_in_page&action=" + 1,
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
                $("#come-in-form").html(data.page);
                GetDialog("come-in-form", 450, "auto");
          		$("#action").val(1);
          		$(".come-in-form-class #ui-id-1").html('მოსვლა');
            }
	    }
    });	
});

$(document).on("click", "#go_home", function () {
	
    $.ajax({
        url: aJaxURL,
	    data: "act=get_come_in_page&action=" + 2,
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
                $("#come-in-form").html(data.page);
                GetDialog("come-in-form", 450, "auto");
          		$("#action").val(2);
          		$(".come-in-form-class #ui-id-1").html('გასვლა');
            }
	    }
    });	
});


$(document).on("click", "#relax", function () {
	
    $.ajax({
        url: aJaxURL,
	    data: "act=get_come_in_page&action=" + 3,
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
                $("#come-in-form").html(data.page);
                GetDialog("come-in-form", 450, "auto");
          		$("#action").val(3);
          		$(".come-in-form-class #ui-id-1").html('შესვენებაზე გასვლა');
          		$('#showTr').css('display','table-row');
            }
	    }
    });	
});


$(document).on("click", "#back_relax", function () {
	
    $.ajax({
        url: aJaxURL,
	    data: "act=get_come_in_page&action=" + 4,
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
                $("#come-in-form").html(data.page);
                GetDialog("come-in-form", 450, "auto");
          		$("#action").val(4);
          		$(".come-in-form-class #ui-id-1").html('შესვენებიდან მოსვლა');
          		$('#showTr1').css('display','table-row');
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
            	var buttons = {
        	        	"cancel": {
        		            text: "დახურვა",
        		            id: "cancel-dialog",
        		            click: function () {
        		        		$(this).dialog("close");
        		            }
        	        	}
        		    };
            	GetDialog("balance-form-deep",890, 'auto', buttons);
            	$("#balance-form-deep").html(data.page);
            	//var total = [1,2,3,4];
            	GetDataTable3('report_deep', l_aJaxURL, "get_list_deep", 8, "id=" + id, 0, "", 0, "asc",'',"<'F'lip>");
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

    			GetDateTimes("search_start");
    			GetDateTimes("search_end");
    			
     			$("#search_start").val(GetDateTime(2) + " 00:00");
     			$("#search_end").val(GetDateTime(2) + " 23:59");
    			
    			LoadTable();
    			var buttons = {
        	        	"cancel": {
        		            text: "დახურვა",
        		            id: "cancel-dialog",
        		            click: function () {
        		        		$(this).dialog("close");
        		            }
        	        	}
        		    };
                GetDialog("balance-form",850, 670, buttons);
            }
	    }
    });
	 
});

$(document).on("click", "#check", function () {
    LoadTable();
});

$(document).on("change", "#search_start", function () {
	LoadTable();
});

$(document).on("change", "#search_end", function () {
	LoadTable();
});


</script>
<body onselectstart='return false;'>
    <div id="ContentHolder">  
    <div class="content"> 
        <table class="tiles">
            <tbody>
                <tr>
                    <td style="padding: 30px 100px 50px 90px;">
                        <div  class="tile_large" id="come_in"  style="background: #A0C64B;" >
										<div class="tile_icon" style="margin-top: 10px;">
											<img src="media/images/w_come_in.png" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
										</div><p style="margin-top: 22px; margin-left: 80px">მოსვლა</p>
						</div>
                    </td>
                    
                    <td style="padding: 30px 100px 50px 50px;">
                        <div  class="tile_large" id="go_home" style="background: #A0C64B;" >
										<div class="tile_icon" style="margin-top: 10px;">
											<img src="media/images/w_go_home.png" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
										</div><p style="margin-top: 22px; margin-left: 80px">გასვლა</p>
						</div>
                    </td>
                    
                </tr>
                  <tr>
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
										</div><p style="margin-top: 22px; margin-left: 80px"> შესვენებიდან მოსვლა</p>
						</div>
                    </td>
                    
                </tr>
                <tr>
                   <td style="padding: 30px 100px 50px 280px;" colspan="2">
                        <div  class="tile_large" id="balance" style="background: #A0C64B;" >
										<div class="tile_icon" style="margin-top: 10px;">
											<img src="media/images/w_balance.png" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
										</div><p style="margin-top: 22px; margin-left: 80px">ბალანსი</p>
						</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
        
        
    <div id="come-in-form" class="form-dialog" title="ინფორმაცია">
    	<!-- aJax -->
	</div>
	
	<div id="balance-form" class="form-dialog" title="ბალანსი">
    	<!-- aJax -->
	</div>
	
	<div id="balance-form-deep" class="form-dialog" title="ბალანსი">
    	<!-- aJax -->
	</div>
	
</body>
</html>
