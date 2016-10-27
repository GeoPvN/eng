<?php

require_once("AsteriskManager/config.php");
//include("AsteriskManager/sesvars.php");

?>

<head>
	<script type="text/javascript">					
		      
		$(document).ready(function () {
			$.ajax({
            	dataType: "JSON",
		        url: 'server-side/call/flash_panel.action.php',
			    data: 'act=1',
		        success: function(data) {
					$("#queue").html(data.queue);
					$("#department").html(data.department);
					$("#ext").html(data.ext);
					$("#user").html(data.user);
					$("#state").html(data.state);
			    }
            }).done(function(data) {
	       runAjax();
	       $('#queue, #department, #ext, #user, #state').chosen({ search_contains: true });
            });
	   });

		function runAjax() {
            $.ajax({
            	async: false,
            	dataType: "html",
		        url: 'AsteriskManager/liveState_FP.php',
			    data: 'sesvar=hideloggedoff&value=true',
			    beforeSend: false,
	            complete: false,
		        success: function(data) {
					$("#jq").html(data);
					if($("#queue").val() != 0){
						$("tbody tr").css('display','none');
					    $("tbody tr[queue="+$("#queue").val()+"]").css('display','');
					}
					if($("#department").val() != 0){
						$("tbody tr").css('display','none');
					    $("tbody tr[dep='"+$("#department").val()+"']").css('display','');
					}
					if($("#ext").val() != 0){
						$("tbody tr").css('display','none');
					    $("tbody tr[ext='"+$("#ext").val()+"']").css('display','');
					}
					if($("#user").val() != 0){
						$("tbody tr").css('display','none');
					    $("tbody tr[user='"+$("#user").val()+"']").css('display','');
					}
					if($("#state").val() != 0){
						$("tbody tr").css('display','none');
					    $("tbody tr[state='"+$("#state").val()+"']").css('display','');
					}
					if($("#user").val() == 0 && $("#ext").val() == 0 && $("#department").val() == 0 && $("#queue").val() == 0 && $("#state").val() == 0){
						$("tbody tr").css('display','');
					}
			    }
            }).done(function(data) {
                setTimeout(runAjax, 1000);
            });
		}

		
    </script>
</head>

<style type='text/css'>
#my_div{
    margin-top: 30px;
}
#my_selector{
	width: 97%;
    margin: auto;
	background: #fff;
	border-radius: 5px;
    border: 1px solid #BABDBF;
}
#flesh_table,#tb{
    width: 98%;
    margin: auto;
}
#flesh_table thead tr th,#tb thead tr th{
	text-align: left;
	padding: 10px;
}
#flesh_table tbody tr td,#tb tbody tr td{
	height: 70px;
	padding-left: 10px;
	text-align: left;
	vertical-align: middle;	
}
#flesh_table tbody tr,#tb tbody tr{
	border-top: 1px solid #E5E5E5;
}
#flesh_table tbody tr:last-child,#tb tbody tr:last-child{
	border-bottom: 1px solid #E5E5E5;
}
#filter{
	padding: 10px 10px 20px 10px;
	height: 43px;
}
#filter span {
	float: left;
	margin-right: 38px;
}
.chosen-container {
	margin-top: 6px;
}
#jq{
	margin-top: 20px;
}
</style>

<body>	
<div id="tabs">
<div class="callapp_head">Flesh Panel<hr class="callapp_head_hr"></div>	
    <div id="my_div">
        <div id="my_selector">
            <div id="filter">
                <span>
                <label>Queue</label>
                <select id="queue" style="width: 165px"></select>
                </span>
                
                <span>
                <label>Department</label>
                <select id="department" style="width: 165px"></select>
                </span>
                
                <span>
                <label>Extension</label>
                <select id="ext" style="width: 165px"></select>
                </span>
                
                <span>
                <label>Worker</label>
                <select id="user" style="width: 165px"></select>
                </span>
                
                <span style="  margin-right: 0px;">
                <label>Status</label>
                <select id="state" style="width: 165px"></select>
                </span>
            </div>
        </div>
    </div>
    <div id="my_div"> 
        <div id="my_selector">
            
            <div id="jq">
            </div>
        </div> 
    </div>
</body>