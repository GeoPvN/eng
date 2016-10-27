<html>
<head>

<script type="text/javascript">
var aJaxURL	= "server-side/report/work_graphic.action.php";
var dey=2;
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
	$('.time').timepicker({
		hourMax: 23,
		hourMin: 0,
		hourGrid: 3,
		minuteGrid: 10

	});
	$("#save-dialog").click(function(){
		var param= new Object();
			param.act 			= "save_dialog";
			param.id 			= $("#id").val();
			param.week_day_id   = dey;
			param.start 		= $("#start").val();
			param.end			= $("#end").val();
		    time_count = $( ".time" ).size() / 2 - 1;

			if(param.start!="" && param.end!=""){
			 $.getJSON(aJaxURL, param, function(json) {
				LoadTable();
				$("#add-edit-form").dialog("close");
				for(l = 1;l <= time_count;l++){
					$.getJSON(
							aJaxURL,
							'act=break&id=' + $("#id").val() + '&start_b=' + $("#start"+l).val() + '&end_b=' + $("#end"+l).val(),
							function(json) {

		 			 });
				}
			 });
			}else{
				alert('მიუთითეთ კორექტული დრო');
			}

	});
	$("#testc").button()
	$("#hidden").focus();
};

function LoadTable(){
	GetDataTable("example",aJaxURL+'?dey='+dey,"get_list",3,"", 0, "", 1, "desc", "", "<'dataTable_buttons'T><'F'Cfipl>")
}
var count = 0;
$(document).on("click", "#testc", function () {
	count++;
	$('#test').html($('#test').html()+'<table class="dialog-form-table"><tr><td style="width: 200px;"><label for="">შესვენების დასაწყისი</label></td><td style="width: 200px;"><label for="">შესვენების დასასრული</label></td></tr><tr><td><input id="start'+count+'" 	class="idle time" type="text" value="" /></td><td><input id="end'+count+'"     class="idle time" type="text" value="" /></td></tr></table>');
	$('.time').timepicker({
		hourMax: 23,
		hourMin: 0,
		hourGrid: 3,
		minuteGrid: 10

	});
});
</script>

<style type="text/css">


.menun{
		cursor:pointer;
		padding: 6px 10px;
		width: 100px !important;
		display: block;
		margin: -3px;
}</style>
</head>
<body>
	<div id="dt_example" class="ex_highlight_row">
       	 <div id="container" style="width:90%">
			<table>
			<tr>
			<th style="; padding: 10px; margin: 10px">

            	<h2 align="center"style="">სამუშაო გრაფიკები</h2>

            	<div id="button_area" style="">
            	<button id="add">დამატება</button> 	<button id="dis">წაშლა</button>
            	</div>
            	<div id="get-info" style="float : left; margin-left: 30px; margin-top: 50px;"></div>
                <table class="display" id="example">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width:50%">მუშაობის დასაწყისი</th>
                            <th style="width:50%">სამუშაოს დასასრული</th>
                            <th style="width: 30px">#</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            	<input type="text" value="ფილტრი" class="search_init"/>
                            </th>
							<th>
                                <input type="text" value="ფილტრი" class="search_init"/>
                            </th>

                            <th>
                                <input type="text" value="ფილტრი" class="search_init"/>
                            </th>
                            <th>
                                <input type="checkbox" name="check-all" id="check-all">
                            </th>

                    </thead>
                </table>
			</th></tr>
			</table>
		</div>
  </div>
    <div  id="add-edit-form" class="form-dialog" title="შეარჩიეთ გრაფიკი">
	</div>
</body>
</html>
