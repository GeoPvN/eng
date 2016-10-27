<head>
<style type="text/css">
.hidden{
	display: none;
}
td{
	text-align: right;
}
td:nth-child(2){
	text-align: left;
}
</style>
<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>
<script type="text/javascript">
		var aJaxURL	= "server-side/report/analiz.action.php";		//server side folder url
		var tName	= "example";										//table name
		var fName	= "add-edit-form";		
		var tbName	= "tabs";							//form name s
		var tName             = "table_";
	    var dialog            = "add-edit-form";
	    var colum_number      = 10;
	    var main_act          = "get_list";
	    var change_colum_main = "<'dataTable_buttons'T><'F'Cfipl><'gg't>";

		$(document).ready(function () {			
			GetDate("search_start_my");
			GetDate("search_end_my");

			start = $("#search_start_my").val();
			end = $("#search_end_my").val();
			LoadTable(start,end,'index',colum_number,main_act,change_colum_main);
			getData(start,end);
			getData1(start,end);
			getData2(start,end);
			getData3(start,end)
		});
		
		function LoadTable(start,end,tbl,col_num,act,change_colum){
			var total=[2,4,6,8];
			GetDataTable(tName+tbl, aJaxURL, act+"&start="+start+"&end="+end, col_num, "", 0, "", 1, "desc", total, change_colum);
			setTimeout(function(){
	    		$('.ColVis, .dataTable_buttons').css('display','none');
	    	}, 90);
		}

		$(document).on("change", "#search_end_my", function () {
			start = $("#search_start_my").val();
			end = $("#search_end_my").val();
			LoadTable(start,end,'index',colum_number,main_act,change_colum_main);
			getData(start,end);
			getData1(start,end);
			getData2(start,end);
			getData3(start,end)
		});

		$(document).on("change", "#search_start_my", function () {
			start = $("#search_start_my").val();
			end = $("#search_end_my").val();
			LoadTable(start,end,'index',colum_number,main_act,change_colum_main);
			getData(start,end);
			getData1(start,end);
			getData2(start,end);
			getData3(start,end);
		});

		function getData(start,end){

			var options = {
			        chart: {
			            renderTo: 'chart_container2',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'კლიენტი ოპერატორების მიხედვით',
			            x: -20 
			        },
			       
			        xAxis: {
			            categories: [],
			            labels: {
			                rotation: -45,
			                style: {
			                    fontSize: '13px',
			                    fontFamily: 'Verdana, sans-serif'
			                }
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'ზარები'
			            },
			            plotLines: [{
			                value: 0,
			                width: 1,
			                color: '#808080'
			            }]
			        },
			        tooltip: {
			        	
			                
			        },
			        legend: {
		                layout: 'vertical',
		                align: 'left',
		                verticalAlign: 'top',
		                borderWidth: 0
			        },
			        series: []
			    }
						   

			    $.getJSON("server-side/report/analiz.action.php?act=chart1&start="+start + "&end=" + end, function(json) {
				    
			    	options.xAxis.categories = json[0]['agent'];
			    	options.tooltip.valueSuffix = json[0]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[0]['name'];
			    	options.series[0].data = json[0]['call_count'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
			 
			    
		}
		function getData1(start,end){

			var options = {
			        chart: {
			            renderTo: 'chart_container1',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'სულ ზარი ოპერატორების მიხედვით',
			            x: -20 
			        },
			       
			        xAxis: {
			            categories: [],
			            labels: {
			                rotation: -45,
			                style: {
			                    fontSize: '13px',
			                    fontFamily: 'Verdana, sans-serif'
			                }
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'ზარები'
			            },
			            plotLines: [{
			                value: 0,
			                width: 1,
			                color: '#808080'
			            }]
			        },
			        tooltip: {
			        	
			                
			        },
			        legend: {
		                layout: 'vertical',
		                align: 'left',
		                verticalAlign: 'top',
		                borderWidth: 0
			        },
			        series: []
			    }
						   

			    $.getJSON("server-side/report/analiz.action.php?act=chart&start="+start + "&end=" + end, function(json) {
				    
			    	options.xAxis.categories = json[0]['agent'];
			    	options.tooltip.valueSuffix = json[0]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[0]['name'];
			    	options.series[0].data = json[0]['call_count'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
			 
			    
		}
		function getData2(start,end){

			var options = {
			        chart: {
			            renderTo: 'chart_container3',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'პოტენციური კლიენტი ოპერატორების მიხედვით',
			            x: -20 
			        },
			       
			        xAxis: {
			            categories: [],
			            labels: {
			                rotation: -45,
			                style: {
			                    fontSize: '13px',
			                    fontFamily: 'Verdana, sans-serif'
			                }
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'ზარები'
			            },
			            plotLines: [{
			                value: 0,
			                width: 1,
			                color: '#808080'
			            }]
			        },
			        tooltip: {
			        	
			                
			        },
			        legend: {
		                layout: 'vertical',
		                align: 'left',
		                verticalAlign: 'top',
		                borderWidth: 0
			        },
			        series: []
			    }
						   

			    $.getJSON("server-side/report/analiz.action.php?act=chart2&start="+start + "&end=" + end, function(json) {
				    
			    	options.xAxis.categories = json[0]['agent'];
			    	options.tooltip.valueSuffix = json[0]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[0]['name'];
			    	options.series[0].data = json[0]['call_count'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
			 
			    
		}
		function getData3(start,end){

			var options = {
			        chart: {
			            renderTo: 'chart_container4',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'არ დაინტერესდა ოპერატორების მიხედვით',
			            x: -20 
			        },
			       
			        xAxis: {
			            categories: [],
			            labels: {
			                rotation: -45,
			                style: {
			                    fontSize: '13px',
			                    fontFamily: 'Verdana, sans-serif'
			                }
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'ზარები'
			            },
			            plotLines: [{
			                value: 0,
			                width: 1,
			                color: '#808080'
			            }]
			        },
			        tooltip: {
			        	
			                
			        },
			        legend: {
		                layout: 'vertical',
		                align: 'left',
		                verticalAlign: 'top',
		                borderWidth: 0
			        },
			        series: []
			    }
						   

			    $.getJSON("server-side/report/analiz.action.php?act=chart3&start="+start + "&end=" + end, function(json) {
				    
			    	options.xAxis.categories = json[0]['agent'];
			    	options.tooltip.valueSuffix = json[0]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[0]['name'];
			    	options.series[0].data = json[0]['call_count'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
			 
			    
		}
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
</head>

<body>
<div id="tabs" style="width: 90%;">
		<div class="callapp_head">საერთო ანალიზი<hr class="callapp_head_hr"></div>
            	<div id="button_area" style="height: 34px;">
            		<div class="right" style="">
	            		<label for="search_end_my" class="left" style="margin: 5px 0 0 9px;">დასასრული</label>
	            		<input style="width: 100px; margin-left: 5px; height: 11px;" type="text" name="search_end_my" id="search_end_my" class="inpt right" />
            		</div>
	            	<div class="right" style="width: 200px;">
	            		<label for="search_start_my" class="left" style="margin: 5px 0 0 9px;">დასაწყისი</label>
	            		<input style="width: 100px; margin-left: 5px; height: 11px;" type="text" name="search_start_my" id="search_start_my" class="inpt right"/>
	            	</div>
            	</div>
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
                            <th style="width: 100%;">ოპერატორი</th>
                            <th style="width: 100%;">სულ ზარი</th>
                            <th style="width: 100%;">სულ ზარი %</th>
                            <th style="width: 100%;">არ დაინტერესდა</th>
                            <th style="width: 100%;">არ დაინტერესდა %</th>
                            <th style="width: 100%;">პოტენციური კლიენტი</th>    
                            <th style="width: 100%;">პოტენციური კლიენტი %</th>
                            <th style="width: 100%;">კლიენტი</th>    
                            <th style="width: 100%;">კლიენტი %</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" style=""/>
                            </th>
                            <th>
                            	<input style="width: 120px;" type="text" name="search_number" value="ფილტრი" class="search_init" style=""></th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 120px;"/>
                            </th>    
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 120px;"/>
                            </th>    
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 120px;"/>
                            </th>    
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 120px;"/>
                            </th>  
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 120px;"/>
                            </th>  
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 120px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 120px;"/>
                            </th>  
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 120px;"/>
                            </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr id="datatable_header" class="search_header">
							<th style="width: 150px"></th>							
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>							
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
                        </tr>
                     </tfoot>
                </table>
                <div id="chart_container1" style="float:left; width: 50%; height: 400px;"></div>   
                <div id="chart_container4" style="float:left; width: 50%; height: 400px;"></div>  
                <div id="chart_container2" style="float:left; width: 50%; height: 400px;"></div> 
                <div id="chart_container3" style="float:left; width: 50%; height: 400px;"></div> 


    <!-- jQuery Dialog -->
    <div  id="add-edit-form" class="form-dialog" title="შემომავალი ზარი">
	</div>

	<!-- jQuery Dialog -->
	<div id="last_calls" title="ბოლო ზარები">
	</div>
	
	<!-- jQuery Dialog -->
	<div id="read_more_dialog" class="form-dialog" title="ყველა გაყიდვა">
	</div>
</body>