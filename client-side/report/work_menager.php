<?php 
$user		= $_SESSION['USERID'];
if($user != 1){
    $show = "display:none;";
}else{
    $click = 'GetDialog1(f,500,200,buttons);';
}
?>
<html>
<head>

<script type="text/javascript">
var aJaxURL	= "server-side/report/work_menager.action.php";
var dey=1;
var tbName = "tabs";
$(document).ready(function(){
	$('.date1').datepicker({dateFormat: "yy-mm-dd"});
	SetEvents("change", "", "", "example", "add-edit-form", aJaxURL);
	GetButtons("change", "");
  	$(document).on("change", ".date1", function () 	{LoadTable();	});
  	LoadTable();
  });
function LoadTable(){
	var param= new Object();
	param.act 			= "get_list1";
	param.start 	    = $('#start').val();
	param.end 			= $('#end').val();
  $.getJSON(aJaxURL, param, function(json) {
		$("#time_line").html(json.aaData);
});
}
function LoadDialog(f,buttons,wo){
	
	$('.date').datepicker({	dateFormat: "yy-mm-dd"});
	<?php echo $click; ?>

	$("#save-dialog").click(function(){
		var param= new Object();
	        param.id            = wo;
			param.act 			= "save_dialog";
			param.graphic_time 	= $('#graphic_time').val();
			param.graphic_time_id 	= $('#graphic_time option:selected').attr('original');
			param.user 			= $('#user').val();
			param.date           = $("#date").val();
			$.getJSON(aJaxURL, param, function(data) {
                if (typeof (data.error) != "undefined") {
                    if (data.error != "") {
                        alert(data.error);
                    } else {
        				LoadTable();
        				$("#add-edit-form").dialog("close");
                    }
                }
		});
	});
};
function change_worc(wo) {
	
	var buttons = {
			"delete": {
	            text: "წაშლა",
	            id: "disable",
	            click: function () {		            
	        		var param= new Object();
	    			param.act 			= "disable";
	    			param.graphic_id    = wo;	 
	    			$.getJSON(aJaxURL, param, function(json) {
	    				LoadTable();
	    				$("#add-edit-form").dialog("close");
	    		});
	            	
	            }  
	        },
			"save": {
	            text: "შენახვა",
	            id: "save-dialog",
	            click: function () {}    
	        }, 
        	"cancel": {
	            text: "დახურვა",
	            id: "cancel-dialog",
	            click: function () {
	            	$(this).dialog("close");
	            }
	        } 
	    };
	
	var param= new Object();
	param.act="get_add_page";
	param.id= wo;
	$.getJSON(aJaxURL, param, function(json) {
		
		LoadTable();
		$("#add-edit-form").html(json.page);
		$("#user").attr('disabled', 'disabled');
		$('.date').datepicker({	dateFormat: "yy-mm-dd"});
});
	LoadDialog("add-edit-form",buttons,wo);

	
} 
</script>

<style type="text/css">
::-webkit-scrollbar {  
    width: 12px;  
	height: 15px;
}  
::-webkit-scrollbar-track {  
    background-color: #CBD9E6;  
    border-left: 1px solid #ccc;  
}  
::-webkit-scrollbar-thumb {  
    background-color: #E8F1B8;  
	border-radius: 12px;
}  
::-webkit-scrollbar-thumb:hover {  
    background-color: #aaa;  
}  
#time_line td{   
   border:solid 1px #7EB442;
}
#disable_all{
	height: 1px !important;
	width: 1px !important;
}
</style>
</head>
<body>
<div id="tabs" style="width: 98%; margin: 0 auto; min-height: 768px; margin-top: 25px;">
    <div id="dt_example" class="ex_highlight_row">
          <div id="container" style="width:100%">
		 <h2 align="center"style="">სამუშაო გრაფიკები</h2>
		 <br>
		  
		 <input style="top: -12px; display: inline-block; position: relative;" id="start" value="<?php echo date('Y-m-d');?> " class="date1 inpt" placeholder="დასაწყისი"/> -დან
		 <input style="top: -12px; display: inline-block; position: relative;" id='end'   value="<?php echo date('Y-m-d', strtotime("+3 day"));?>  " class="date1 inpt " placeholder="დასარული"/> -მდე
		 <button id="change" style="top: -12px; display: inline-block; position: relative;left: 150px; <?php echo $show; ?>">დამატება</button>
		  <br/>
			<div id="time_line" ></div>
   		</div>
  </div>
    <div  id="add-edit-form" class="form-dialog" title="თავისუფალი გრაფიკები">
  </div>
  </div>
<!-- </div> -->
</body>
</html>
