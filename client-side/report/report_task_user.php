<html>
<head>
<style type="text/css">
.download {

	background:linear-gradient(to bottom, #599bb3 5%, #408c99 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#599bb3', endColorstr='#408c99',GradientType=0);
	background-color:#599bb3;
	-moz-border-radius:8px;
	-webkit-border-radius:8px;
	border-radius:8px;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:arial;
	font-size:14px;

	text-decoration:none;
	text-shadow:0px 1px 0px #3d768a;
}
#add-edit-form, .idle{'disabled', true};
</style>
<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>
<script type="text/javascript">
var title 	= '0';
var i 		= 0;
var done 	= ['','','','','','',''];
var aJaxURL	= "server-side/report/report_task_user.action.php";		//server side folder url
var tName   = "report";
var start	= $("#search_start").val();
var end		= $("#search_end").val();
$(document).ready(function() {
	$(document).on("change", "#search_start", function () 	{drawFirstLevel();});
	$(document).on("change", "#search_end"  , function () 	{drawFirstLevel();});
	$(document).on("change", "#persons_id"  , function () 	{drawFirstLevel();});
	GetDate("search_start");
	GetDate("search_end");
	drawFirstLevel();
	$("#back").button({ disabled: true });
	$("#back").button({ icons: { primary: "ui-icon-arrowthick-1-w" }});
    $('#back').click(function(){
	    i--;
	    drawFirstLevel();
    	if(i==0)$("#back").button({ disabled: true });
 });  });

function drawFirstLevel(){
		 var options = {
	                chart: {
	                    renderTo: 'chart_container',
	                    plotBackgroundColor: null,
	                    plotBorderWidth: null,
	                    plotShadow: false
	                },
	                title: {
	                    text: title
	                },
	                tooltip: {
	                    formatter: function() {
	                        return '<b>'+ this.point.name +': '+this.point.y+' დავალება :  '+this.percentage.toFixed(2) +' %</b>';

	                    }
	                },
	                plotOptions: {
	                	pie: {
	                        allowPointSelect: true,
	                        cursor: 'pointer',
	                        dataLabels: {
	                            enabled: true,
	                            color: '#000000',
	                            connectorColor: '#000000',
	                            formatter: function() {
	                            	return '<b>'+ this.point.name +': '+this.point.y+' დავალება :  '+this.percentage.toFixed(2) +' %</b>';
	                            }
	                        },
	                        point: {
	                            events: {
	                                click: function() {
		                               $("#back").button({ disabled: false });
		                        		done[i]=this.name;
		                        		if(i==1) i=0;
		                        		else i++;
		                        		drawFirstLevel();

	                                }
	                            }
	                        }
	                    }
	                },
	                series: [{
	                    type: 'pie',
	                    name: 'კატეგორიები',
	                    data: []
	                }]
	            }
		var start	= $("#search_start").val();
		var end		= $("#search_end").val();
		var user	= $("#persons_id").val();
		
		var d_url   ="&start="+start+"&end="+end+"&done="+i+"&user="+user+"&type="+done[0]+"&category="+done[1]+"&sub_category="+done[2];
		var url     = aJaxURL+"?act=get_category"+d_url;

		GetDataTable(tName, aJaxURL, "get_list", 12, d_url, 0, "",'','',[2], "<'dataTable_buttons'T><'F'Cfipl>");
		setTimeout(function(){
	    	$('.ColVis, .dataTable_buttons').css('display','none');
	    	}, 90);
        $.getJSON(url, function(json) {
	                options.series[0].data 	= 	json.data;
	                options.title['text'] 	=	json.text;
	                chart = new Highcharts.Chart(options);
	                $("#total_quantity").html("იტვირთება....");
	                setTimeout(function(){ $("#total_quantity").html($("#qnt").html().split(">")[1]);}, 500);
		});
		 $("#report tbody").on("click", "tr", function () {
			 if(i==1){
				 d_url1   ="&start="+start+"&end="+end+"&done="+i+"&user="+user+"&type="+done[0]+"&category="+done[1]+"&sub_category="+done[2];
			 		var nTds = $("td", this);
		            var rID = $(nTds[1]).text();
	     		GetDialog("in_form", "1250px", "auto", '', 'left+43 top');
	     		GetDataTable("table_task", aJaxURL, "get_in_page", 15, d_url1+"&rid="+rID, 0, "", 1, "desc", "", "<'dataTable_buttons'T><'F'Cfipl>");
	     		SetEvents("", "", "", "report_1", "add-edit-form", "server-side/call/tasks/tasks_tab1.action.php");
	     		$('.ui-dialog-buttonset').hide();

	     		}
	     	});
}

function LoadDialog(fname){

	GetDialog(fname, "1154px", "auto");
	$('#add-edit-form, .idle, .idls').attr('disabled', true);
	$('.ui-dialog-buttonset').hide();


};
$(document).on("click", "#show_copy_prit_exel", function () {
    if($(this).attr('myvar') == 0){
        $('.ColVis,.dataTable_buttons').css('display','block');
        $(this).css('background','#2681DC');
        $(this).children('img').attr('src','media/images/icons/select_w.png');
        $(this).attr('myvar','1');
    }else{
    	$('.ColVis,.dataTable_buttons').css('display','none');
    	$(this).css('background','#FAFAFA');
        $(this).children('img').attr('src','media/images/icons/select.png');
        $(this).attr('myvar','0');
    }
});
	</script>
	</head>
	<body>


       	 <div id="container" style="width:90%">
            <div id="dynamic">
             <div id="button_area" style="margin-top: 28px;">
             <button id="back">უკან</button>
			</div>
	       <div id="button_area" style="margin: 2% 0 0 0">
	         <div class="left" style="width: 175px;">
	           <input type="text" name="search_start" id="search_start" class="inpt right"/>
	             </div>
	            	<label for="search_start" class="left" style="margin:5px 0 0 3px">-დან</label>
	             <div class="left" style="width: 185px;">
		            <input type="text" name="search_end" id="search_end" class="inpt right" />
	             </div>
	            	<label for="search_end" class="left" style="margin:5px 0 0 3px">–მდე</label>
	            
	            	
	         	<div class="left" style="width: 195px;">
	         		<label for="search_end" class="left" style="margin:-18px 0 0 28px">პასუხისმგებელი პირი</label>
		          <select style="width: 186px;" id="persons_id" class="inpt right"><?php 
		          
		       
							   $rResult = mysql_query("	SELECT users.`id`, persons.`name`
														FROM `persons`
														JOIN	`users` ON persons.id = users.person_id
														WHERE persons.actived=1 AND users.group_id in(15,3,18,19,20)");

								echo'<option value="0" selected="selected">ყველა</option>';
								
							    while ( $aRow = mysql_fetch_array( $rResult ) )
							    {
							    	echo '<option value="'.$aRow[0].'">'.$aRow[1].'</option>';
							    }?>
				</select>
	             </div>
	            	
	           <label class="left" style="margin:5px 0 0 40px">ზარების  ჯამური რაოდენობა: </label> <label id="total_quantity" class="left" style="margin:5px 0 0 2px; font-weight: bold;">5</label>
	       <br /><br /><br />
	            </div>
			<div id="chart_container" style="width: 100%; height: 480px; margin-top:-30px;"></div>
			<input type="text" id="hidden_name" value="" style="display: none;" />
			<br><br><br><br><br><br><br><br><br><br>

			<table id="table_right_menu">
            <tr>
            <td style="cursor: pointer;padding: 4px;border-right: 1px solid #A3D0E4;background:#2681DC;" id="show_table" myvar="0"><img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
            </td>
            <td style="cursor: pointer;padding: 4px;border-right: 1px solid #A3D0E4;" id="show_log" myvar="0"><img alt="log" src="media/images/icons/log.png" height="14" width="14">
            </td>
            <td style="cursor: pointer;padding: 4px;border-right: 1px solid #A3D0E4;" id="show_copy_prit_exel" myvar="0"><img alt="link" src="media/images/icons/select.png" height="14" width="14">
            </td>
            </tr>
            </table>
			 <table class="display" id="report" style="width:100%">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width:70%">დასახელება</th>
                            <th style="width:15%" class="min">რაოდენობა</th>
                            <th style="width:15%" class="min">პროცენტი</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                            	<input type="text" name="search_number" value="ფილტრი" class="search_init">
                            </th>
                            <th>
                            	<input type="text" name="search_object" value="ფილტრი" class="search_init">
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>

                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th id="qnt">&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </tfoot>
                </table>
              
		</div>
 <div  id="add-edit-form" class="form-dialog" title="დავალებები">	</div>
  <div id="in_form"  class="form-dialog">
  <br/>
  <br/>
  <br/>
  <table class="display" id="table_task">
    <thead>
        <tr id="datatable_header">
            <th>ID</th>
            <th style="width: 30px;">№</th>
            <th style="width: 50%;">ფორმირების თარიღი</th>
            <th style="width: 50%;">დასაწყისი</th>
            <th style="width: 50%;">დასასრული</th>
            <th style="width: 50%;">დამფორმირებელი</th>
            <th style="width: 50%;">აბონენტი</th>
            <th style="width: 50%;">პასუხისმგებელი პირი</th>
            <th style="width: 50%;">სტატუსი</th>
            <th class="check" style="width: 30px;">&nbsp;</th>
        </tr>
    </thead>
    <thead>
        <tr class="search_header">
            <th class="colum_hidden">
        	   <input type="text" name="search_id" value="ფილტრი" class="search_init" />
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
                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
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
            	<input type="text" name="search_number" value="ფილტრი" class="search_init" />
            </th>
            <th style="border-right: 1px solid #E6E6E6 !important;">
            	<div class="callapp_checkbox">
                    <input type="checkbox" id="check-all-task" name="check-all-task" />
                    <label for="check-all-task"></label>
                </div>
            </th>
        </tr>
    </thead>
</table>
               
  </div>
</body>
</html>
