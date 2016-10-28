<html>
<head>

<script type="text/javascript">
var aJaxURL	= "server-side/info/work_graphic.action.php";
var dey=2;
var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl>";
$(document).ready(function(){

	GetButtons("add", "dis");
	LoadTable();


	$(".menun").click(function(){
		dey=this.id;
		LoadTable();
		title=$(this).html();
		$("#add-edit-form").attr('title', title);
		$("#wek_h").html("'"+title+"'");


});
	SetEvents("add", "dis", "check-all", "example", "add-edit-form", aJaxURL);
	});
function LoadDialog(f){
	GetDialog(f,450,'auto');
	$("#save-dialog").click(function(){
		var param= new Object();
			param.act 			= "save_dialog";
			param.id 			= $("#id").val();
			param.wday          = $("#wday").val();
			param.project_id    = $("#project_id").val();
			param.start 		= $("#start").val();
			param.end			= $("#end").val();
			// break time
			param.breakStart1 	= $(".breakStart1").val();
			param.breakEnd1		= $(".breakEnd1").val();
			param.my_id1        = $('.breakStart1').attr('my_id1');
			param.breakStart2 	= $(".breakStart2").val();
			param.breakEnd2		= $(".breakEnd2").val();
			param.my_id2        = $('.breakStart2').attr('my_id2');
			param.breakStart3 	= $(".breakStart3").val();
			param.breakEnd3		= $(".breakEnd3").val();
			param.my_id3        = $('.breakStart3').attr('my_id3');
			param.breakStart4 	= $(".breakStart4").val();
			param.breakEnd4		= $(".breakEnd4").val();
			param.my_id4        = $('.breakStart4').attr('my_id4');
			param.breakStart5 	= $(".breakStart5").val();
			param.breakEnd5		= $(".breakEnd5").val();
			param.my_id5        = $('.breakStart5').attr('my_id5');
			param.breakStart6 	= $(".breakStart6").val();
			param.breakEnd6		= $(".breakEnd6").val();
			param.my_id6        = $('.breakStart6').attr('my_id6');
			
			if(param.start!="" && param.end!=""){
			$.getJSON(aJaxURL, param, function(json) {
				LoadTable();
				$("#add-edit-form").dialog("close");
		});} else alert('მიუთითეთ კორექტული დრო');


	});
	$("#hidden").focus();
};
function LoadTable(){
	GetDataTable("example",aJaxURL+'?dey='+dey,"get_list",4,'',0, "", 1, "asc", "", change_colum_main);
	setTimeout(function(){
    	$('.ColVis, .dataTable_buttons').css('display','none');
    }, 90);
}

$(document).on("change", "#wday", function () {
if($("#project_id").val() == 0){
	alert('აირჩიეთ პროექტი!');
}else{
	$.ajax({
        url: aJaxURL,
	    data: 'act=get_wk&project_id='+$('#project_id').val()+ '&wday=' + $('#wday').val(),
        success: function(data) {
			if(typeof(data.error) != "undefined"){
				if(data.error != ""){
					alert(data.error);
				}else{
					$('#pasteTable').html('')
					$("#script").html(data.start[0]+data.end[1]+data.end[2]+data.end[3]+data.end[4]+data.end[5]+data.end[6]);
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
	top: 56px;
}
.menun{
		cursor:pointer;
		padding: 6px 10px;
		width: 100px !important;
		display: block;
		margin: -3px;
}</style>
</head>
<body>
<div id="script" style="display:none;"></div>
<div id="tabs" style="width: 90%">
<div class="callapp_head">სამუშაო გრაფიკები<hr class="callapp_head_hr"></div>

<div id="button_area" style="margin-top: 15px;">
<button id="add">დამატება</button>
<button id="dis">წაშლა</button>
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
            <th style="width:50%">მუშაობის დასაწყისი</th>
            <th style="width:50%">სამუშაოს დასასრული</th>
            <th style="width: 35px">#</th>
        </tr>
    </thead>
    <thead>
        <tr class="search_header">
            <th class="colum_hidden">
            	<input type="text" name="search_id" value="ფილტრი" class="search_init" style=""/>
            </th>
			<th>
                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
            </th>

            <th>
                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
            </th>
            <th>
                <div class="callapp_checkbox">
                    <input type="checkbox" id="check-all" name="check-all" />
                    <label for="check-all"></label>
                </div>
            </th>

    </thead>
</table>
</div>

<div  id="add-edit-form" class="form-dialog" title="შეარჩიეთ გრაფიკი">
</div>
</body>
</html>
