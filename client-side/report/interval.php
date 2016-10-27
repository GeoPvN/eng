<head>
<style type="text/css">
.hidden{
	display: none;
}
.highcharts-axis-labels{
	display: none !important;
}

</style>
<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>
<script type="text/javascript">
		var aJaxURL	= "server-side/report/interval.action.php";		//server side folder url
		var tName	= "example";										//table name
		var fName	= "add-edit-form";		
		var tbName	= "tabs";							//form name s
		var file_name = '';
		var rand_file = '';

		$(document).ready(function () {
			getusers();
			GetDate("search_start_my");
			GetDate("search_end_my");
		});

		function getusers(){
			$.ajax({
		        url: "server-side/report/interval.action.php",
			    data: "act=getusers",
		        success: function(data) {
			        console.log(data)
		        	$("#operatori").html(data);
			    }
			}).done(function() {
				getData1();
			});
		}
        
		function getData1(){

			var options = {
			        chart: {
			            renderTo: 'chart_container1',
			            margin: [ 50, 50, 100, 80],
			            zoomType: 'x'
			        },
			        title: {
			            text: 'შუალედები ოპერატორების მიხედვით',
			            x: -20 
			        },
			        xAxis: {
			            categories: [],
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
			        	pointFormat: 'შუალედის ხანგძლივობა: <b>{point.y}</b>'
			        },
			        legend: {
		                layout: 'vertical',
		                align: 'left',
		                verticalAlign: 'top',
		                borderWidth: 0
			        },
			        series: []
			    }
			
			    start_time = $('#search_start_my').val();
			    end_time = $('#search_end_my').val();
			    agent = $('#operatori').val();
			   

			    $.getJSON("server-side/report/interval.action.php?start="+start_time + "&end=" + end_time + "&agent=" + agent, function(json) {
				    
			    	options.xAxis.categories = json[0]['agent'];
			    	options.tooltip.valueSuffix = json[0]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[0]['name'];
			    	options.series[0].data = json[0]['call_count'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
			    
			    $.ajax({
			        url: "server-side/report/interval.action.php",
				    data: "act=total_sec&start="+start_time+"&end="+end_time+"&agent="+agent,
			        success: function(data) {       
			        	$("#total_sec").val(data.sec);
			        	$("#total_talk_sec").val(data.talk);
			        	
				    }
				});
			    
		}

		$(document).on("change", "#search_end_my", function () {
			getData1();
		});

		$(document).on("change", "#search_start_my", function () {
			getData1();
		});
		
		$(document).on("change", "#operatori", function () {
			getData1();
		});
		
    </script>
</head>

<body>
<div id="tabs" style="width: 90%;">
		<div class="callapp_head">საუბრის შუალედები<hr class="callapp_head_hr"></div>
            	<div id="button_area" style="margin-top: 50px;">            	
            	    <div class="right" style="">            	    
            	    <input class="inpt right" type="text" id="total_talk_sec" style="margin-left: 5px; width: 75px;height: 18px;"> 
            	    <label for="total_talk_sec" class="right" style="margin: 5px 0 0 9px;">სულ საუბრის ხანგძლივობა</label> 
            	    
            	    <input class="inpt right" type="text" id="total_sec" style="margin-left: 5px; width: 75px;height: 18px;"> 
            	    <label for="total_sec" class="right" style="margin: 5px 0 0 9px;">შუალედების ჯამი</label>               	    
            	                         
	            		<label for="operatori" class="left" style="margin: 5px 0 0 9px;">ოპერატორი</label>
	            		<select id="operatori" class="inpt right" style="margin-left: 5px; width: 190px;">
                         
                        </select>                        
            		</div>
            		<div class="right" style="">
	            		<label for="search_end_my" class="left" style="margin: 5px 0 0 9px;">დასასრული</label>
	            		<input style="width: 75px; margin-left: 5px; height: 18px;" type="text" name="search_end_my" id="search_end_my" class="inpt right" />
            		</div>
	            	<div class="right" style="width: 200px;">
	            		<label for="search_start_my" class="left" style="margin: 5px 0 0 35px;">დასაწყისი</label>
	            		<input style="width: 75px; margin-left: 5px; height: 18px;" type="text" name="search_start_my" id="search_start_my" class="inpt right"/>
	            	</div>
            	</div>    
            	<div id="chart_container1" style="float:left; width: 100%; height: 500px;"></div>     


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