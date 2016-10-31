<html>
<head>
<style type="text/css">
#in_form,.in_form-class{
	overflow: visible;
}
.green
	{
	background-color: rgba(255, 0, 29, 0.51) !important;
	color: #0D233A !important;
	font-size: 14px !important;
		
	}
</style>
<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>
<script type="text/javascript">

var aJaxURL	= "server-side/report/company_report.action.php";

$(document).ready(function() {
	GetDate("search_start");
	GetDate("search_end");

	var start	= $("#search_start").val();
	var end		= $("#search_end").val();
	
	runajax(start, end);
	
});

function runajax(start, end){
	
	 $("#total_quantity").html('Loading ...');
	 $.ajax({
        url: aJaxURL,
        type: "POST",
        data: 'act=get_chart&start='+start+'&end='+end,
        dataType: "json",
        success: function (data) {
        	drawFirstLevel(data);
        	$("#total_quantity").html(data.totalSum);
        	LoadTable('report',6,'get_list',"<'dataTable_buttons'T><'F'Cfipl>");
        }
     });
}

function LoadTable(tbl,col_num,act,change_colum){
	GetDataTable(tbl,aJaxURL,act,col_num,"start="+$("#search_start").val()+"&end="+$("#search_end").val(),0,"",1,"asc",'',change_colum);
	setTimeout(function(){
	    $('.ColVis, .dataTable_buttons').css('display','none');
    }, 120);
}

$(document).on("change", "#search_start", function () {runajax($("#search_start").val(), $("#search_end").val());});
$(document).on("change", "#search_end"  , function () {runajax($("#search_start").val(), $("#search_end").val());});

function drawFirstLevel(data){
            
	    $('#chart_container').highcharts({
		    
	        title: {
	            text: 'The company received the appeals'
	        },
	        
	        xAxis: {
	            categories: data.dataBranch
	        },
	        
	        labels: {
	            items: [{
	                html: 'Total by method',
	                style: {
	                    left: '-25px',
	                    top: '-20px',
	                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
	                }
	            }]
	        },
	        
	        series: data.dataSource
 
	    });
}

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

</script>
</head>
<body>
	<div id="tabs" style="width: 90%; height: 850px;">
		<div class="callapp_head">The company received the appeals<hr class="callapp_head_hr"></div>
         <div id="button_area" style="margin: 3% 0 0 0">
		</div>
       <div id="button_area" style="margin: 3% 0 0 0">
         <div class="left" style="width: 175px;">
           <input type="text" name="search_start" id="search_start"  class="inpt right"/>
             </div>
            	<label for="search_start" class="left" style="margin:5px 0 0 3px">-From</label>
             <div class="left" style="width: 185px;">
	            <input type="text" name="search_end" id="search_end"  class="inpt right" />
             </div>
            	<label for="search_end" class="left" style="margin:5px 0 0 3px">–Up to</label>
           <label class="left" style="margin:5px 0 0 40px">Total number of calls: </label> <label id="total_quantity" class="left" style="margin:5px 0 0 2px; font-weight: bold;"></label>
       <br /><br /><br />
            </div>
		<div id="chart_container" style="width: 100%; height: 480px; margin-top:-30px;"></div>
		<input type="text" id="hidden_name" value="" style="display: none;" />
		<br><br><br><br><br><br>
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
		 <table class="display" id="report" style="width: 100%">
                <thead>
                    <tr id="datatable_header">
                        <th>ID</th>
                        <th style="width:70%">Branch</th>
                        <th style="width:15%">Phone</th>
                        <th style="width:15%">Meeting</th>
                        <th style="width:15%">Application</th>
                        <th style="width:15%">Internet</th>
                    </tr>
                </thead>
                <thead>
                    <tr class="search_header">
                        <th class="colum_hidden">
                        	<input type="text" name="search_id" value="Filter" class="search_init" />
                        </th>
                        <th>
                        	<input type="text" name="search_number" value="Filter" class="search_init">
                        </th>
                        <th>
                        	<input type="text" name="search_number" value="Filter" class="search_init">
                        </th>
                        <th>
                        	<input type="text" name="search_number" value="Filter" class="search_init">
                        </th>
                        <th>
                        	<input type="text" name="search_object" value="Filter" class="search_init">
                        </th>
                        <th>
                            <input type="text" name="search_date" value="Filter" class="search_init" />
                        </th>

                    </tr>
                </thead>
            </table>

</body>
</html>

