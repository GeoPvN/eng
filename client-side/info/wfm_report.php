<?php include '../includes/classes/core.php'; ?>
<html>
<head>
<script type="text/javascript">
var aJaxURL	= "server-side/info/wfm_report.action.php";

$(document).ready(function(){
	$('#year_month').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
        
    });
    
	param 			= new Object();
	param.act		= "get_project";
    $.ajax({
        url: aJaxURL,
        data: param,
        success: function(data) {
            $("#project_id").html(data.project);
            $('#project_id').chosen({ search_contains: true });
            param 			= new Object();
        	param.act		= "get_cycle_start_date";
        	param.year_month	= $("#year_month").val();
            $.ajax({
                url: aJaxURL,
                data: param,
                success: function(data) {
                    $("#cycle_start_date").html(data.cycle_start_date);
                }
            });
        }
    });
});

function load_table(){
	param 			    = new Object();
	param.act		    = "get_24_hour";
	param.project_id	= $("#project_id").val();
	param.year_month	    = $("#year_month").val();
    $.ajax({
        url: aJaxURL,
        data: param,
        success: function(data) {
            $("#time_line").html(data.page);
            $("#test").html(data.tutuci);
            for(i=1;i <=144;i++){
                $("td[zeda='"+i+"'] span").html($("td[table_zeda='"+i+"'][count_green='green']").size());
            }
            for(i=1;i <=144;i++){
                $("td[shua='"+i+"'] span").html($("td[table_zeda='"+i+"'][style='height: 6px; background: green;']").size());
            }
            for(i=1;i <=144;i++){
                $("td[qveda='"+i+"'] span").html(((parseInt($("td[zeda='"+i+"'] span").html())-parseInt($("td[shua='"+i+"'] span").html()))));
            }
            $( data.sl ).each(function( index ) {
          	  console.log( data.sl[index].prc );
          	  if(data.sl[index].timeM >= '00' && data.sl[index].timeM <= '05'){
              	  $("td[shuaa][clock='"+data.sl[index].timeH+"00'] span").html(data.sl[index].prc);
          	  }else if(data.sl[index].timeM >= '05' && data.sl[index].timeM <= '10'){
              	  $("td[shuaa][clock='"+data.sl[index].timeH+"05'] span").html(data.sl[index].prc);
          	  }else if(data.sl[index].timeM >= '10' && data.sl[index].timeM <= '15'){
              	  $("td[shuaa][clock='"+data.sl[index].timeH+"10'] span").html(data.sl[index].prc);
          	  }else if(data.sl[index].timeM >= '15' && data.sl[index].timeM <= '20'){
              	  $("td[shuaa][clock='"+data.sl[index].timeH+"15'] span").html(data.sl[index].prc);
          	  }else if(data.sl[index].timeM >= '20' && data.sl[index].timeM <= '25'){
              	  $("td[shuaa][clock='"+data.sl[index].timeH+"20'] span").html(data.sl[index].prc);
          	  }else if(data.sl[index].timeM >= '25' && data.sl[index].timeM <= '30'){
              	  $("td[shuaa][clock='"+data.sl[index].timeH+"25'] span").html(data.sl[index].prc);
          	  }else if(data.sl[index].timeM >= '30' && data.sl[index].timeM <= '35'){
              	  $("td[shuaa][clock='"+data.sl[index].timeH+"30'] span").html(data.sl[index].prc);
          	  }else if(data.sl[index].timeM >= '35' && data.sl[index].timeM <= '40'){
              	  $("td[shuaa][clock='"+data.sl[index].timeH+"35'] span").html(data.sl[index].prc);
          	  }else if(data.sl[index].timeM >= '40' && data.sl[index].timeM <= '45'){
              	  $("td[shuaa][clock='"+data.sl[index].timeH+"40'] span").html(data.sl[index].prc);
          	  }else if(data.sl[index].timeM >= '45' && data.sl[index].timeM <= '50'){
              	  $("td[shuaa][clock='"+data.sl[index].timeH+"45'] span").html(data.sl[index].prc);
          	  }else if(data.sl[index].timeM >= '50' && data.sl[index].timeM <= '55'){
              	  $("td[shuaa][clock='"+data.sl[index].timeH+"50'] span").html(data.sl[index].prc);
          	  }else if(data.sl[index].timeM >= '55' && data.sl[index].timeM <= '59'){
              	  $("td[shuaa][clock='"+data.sl[index].timeH+"55'] span").html(data.sl[index].prc);
          	  }
          	  
          	});
            
        }
    });
}

$(document).on("change", "#project_id", function () {
	load_table();
});

$(document).on("change", "#year_month", function () {
	load_table();
});



$(document).on("click", ".user_break", function () {

	$("#work_table td").css('border','1px solid');
	
	$("td[rigi='"+$(this).attr('rigi')+"'][count_green]").css('border-top','2px groove #FF00AE');
	$("td[rigi='"+$(this).attr('rigi')+"'][count_red]").css('border-bottom','2px groove #FF00AE');
	$(this).css('border-top','2px groove #FF00AE');
	$(this).css('border-bottom','2px groove #FF00AE');
});

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
    background-color: #2681DC;
	border-radius: 12px;
}
::-webkit-scrollbar-thumb:hover {  
    background-color: #aaa;  
}  
#time_line td,#time_line1 td{   
   border:solid 1px #A3D0E4;
}
#pirveli td, #meore td, #mesame td,#qveda_meore td,#qveda_pirveli td,#qveda_meore1 td,#qveda_pirveli1 td{
	padding: 2px;
}
#pirveli, #meore, #mesame,#qveda_meore,#qveda_pirveli,#qveda_meore1,#qveda_pirveli1{
	width: 100%;
}
#work_table td, #work_table th {
    border: 1px solid;
    font-size: 11px;
    font-weight: normal;
    text-align: center;
}
#table_index_length
{
	position: inherit;
    width: 0px;
	float: left;
}
#table_index_length label select{
	width: 60px;
    font-size: 10px;
    padding: 0;
    height: 18px;
}
#table_index_paginate{
	margin: 0;
}
</style>
</head>
<body>
<div id="tabs" style="width: 98%">
    <div class="callapp_head">WFM<hr class="callapp_head_hr"></div>

    <div id="container" style="width:100%;margin-bottom: 70px;">
    
        <select style="width: 210px;padding: 2px;border: solid 1px #85b1de;margin-right: 15px;"  id="project_id"></select>
        <input style="width: 75px;display: inline-block; height: 13px; position: relative;" id="year_month" value="<?php echo date('Y-m-d')?>" class="date1 inpt" placeholder="თარიღი"/>
        <br/>
        <div id="time_line" style="margin-top: 20px;"><div style="color: #2681DC;text-align: center; font-size: 14px; font-weight: bold;">აირჩიეთ პროექტი და თარიღი!</div></div>

    </div>
<div id="test"></div>
<div id="add-edit-form" class="form-dialog" title="ცვლის დამატება">
<div id="dialog-form">
    <fieldset>
        <legend>ცვლა</legend>
        <select style="width: 160px;" id="shift_id"></select>
    </fieldset>
</div>
</div>
<div id="start_date" class="form-dialog" title="ციკლის დაწყების თარიღი">
<div id="dialog-form">
    <fieldset>
        <legend>ციკლის დაწყების თარიღი</legend>
        <select style="width: 190px;" id="cycle_start_date"></select>
    </fieldset>
</div>
</div>
<div id="wfm_hour" class="form-dialog" title="საათების მიხედვით">
</div>
<div id="add_break" class="form-dialog" title="შესვენების დამატება">
</div>
<div id="add-edit-form-user" class="form-dialog" title="შესვენების დამატება">
</div>
</body>
</html>
