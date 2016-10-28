<head>
<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
	<style type="text/css">
		caption{
		    margin: 0;
			padding: 0;
			background: #f3f3f3;
			height: 40px;
			line-height: 40px;
			text-indent: 2px;
			font-family: "Trebuchet MS", Trebuchet, Arial, sans-serif;
			font-size: 140%;
			font-weight: bold;
			color: #000;
			text-align: left;
			letter-spacing: 1px;
			border-top: dashed 1px #c2c2c2;
			border-bottom: dashed 1px #c2c2c2;
		}
		#call_distribution_per_day  td:nth-child( 3 ),
		#call_distribution_per_day  td:nth-child( 5 ),
		#call_distribution_per_hour  td:nth-child( 3 ),
		#call_distribution_per_hour  td:nth-child( 5 ),
		#call_distribution_per_day_of_week  td:nth-child( 3 ),
		#call_distribution_per_day_of_week  td:nth-child( 5 ),
		#technik_info table td:nth-child( 6 ),
		#technik_info table td:nth-child( 3 ),
		#technik_info table td:nth-child( 4 ){
		    cursor: pointer;
            text-decoration: underline;
        }
		div, caption, td, th, h2, h3, h4 {
			font-size: 11px;
			font-family: verdana,sans-serif;
			voice-family: "\"}\"";
			voice-family: inherit;
			color: #333;
		}
		table th,table td{
    		color: #333;
            font-family: pvn;
            background: #E6F2F8;
			border: 1px solid #A3D0E4;
			vertical-align: middle;
			
			
		}
		table td{
			word-wrap: break-word;
        }
		#technik_info table td,#technik_info table th,
		#report_info table td,#report_info table th,
		#answer_call_info table td,#answer_call_info table th,
        #answer_call_by_queue table tbody td,#answer_call_by_queue table thead th,
        #service_level table tbody td,#service_level table thead th,
        #answer_call table tbody td,#answer_call table thead th,
        #disconnection_cause table tbody td,#disconnection_cause table thead th,
        #unanswer_call table td,#unanswer_call table th,
        #disconnection_cause_unanswer table tbody td,#disconnection_cause_unanswer table thead th,
        #unanswered_calls_by_queue table tbody td,#unanswered_calls_by_queue table thead th,
        #totals table tbody td,#totals table thead th,
        #call_distribution_per_day table tbody td,#call_distribution_per_day table thead th,
        #call_distribution_per_hour table tbody td,#call_distribution_per_hour table thead th,
        #call_distribution_per_day_of_week table tbody td,#call_distribution_per_day_of_week table thead th{
			padding: 6px;
		}
		#technik_info table,
		#report_info table,
		#answer_call_info table,
		#answer_call_by_queue table,
		#service_level table,
		#answer_call table,
		#disconnection_cause table,
		#unanswer_call table,
		#disconnection_cause_unanswer table,
		#unanswered_calls_by_queue table,
		#totals table,
		#call_distribution_per_day table,
		#call_distribution_per_hour table,
		#call_distribution_per_day_of_week table{
			width: 100%;
			padding: 0;
		}
		table {
			padding: 10px;
            text-align: left;
            vertical-align: middle;
			margin: 0 auto;
            clear: both;
            border-collapse: collapse;
            table-layout: fixed;
            border: 1px solid #E6E6E6;
		}
		a{
			cursor: pointer;
		}
		#loading1{
			z-index:999;
			top:45%;
			left:45%;
			position: absolute;
			display: none;
			padding-top: 15px;
		}
    </style>
    <script src="js/highcharts.js"></script>
     <script src="js/exporting.js"></script>
	<script type="text/javascript">
		var aJaxURL		= "server-side/report/inc_tech_report.action.php";		//server side folder url
		var aJaxURL1	= "server-side/report/sales_statistics.action.php";		//server side folder url
		var tName		= "example0";										//table name
		var tbName		= "tabs";											//tabs name
		var fName		= "add-edit-form";									//form name
		var file_name 	= '';
		var rand_file 	= '';
		
		$(document).ready(function () {   
			GetDate("start_time");
			GetDate("end_time");
			$("#show_report,#technik_info_but,#report_info_but,#answer_call_info_but,#answer_call_by_queue_but,#service_level_but,#answer_call_but,#disconnection_cause_but,#unanswer_call_but,#disconnection_cause_unanswer_but,#unanswered_calls_by_queue_but,#totals_but,#call_distribution_per_day_but,#call_distribution_per_hour_but,#call_distribution_per_day_of_week_but").button();
			$('#change_tab').chosen({ search_contains: true });
			$('#tab-1,#tab-2,#tab-3').css('display','none');
		});

		function getData(){
			 var options = {
			        chart: {
			            renderTo: 'chart_container',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'Answered calls by carrier',
			            x: -20 
			        },
			       
			        xAxis: {
			            categories: [],
			            labels: {
				            
			            	align: 'center'
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'Calls'
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

			 var i=0;
			
				agent	= '';
				queuet = '';
			
				var optionss = $('#myform_List_Queue_to option');
				var values = $.map(optionss ,function(option) {
					if(queuet != ""){
						queuet+=",";
						
					}
					queuet+="'"+option.value+"'";
				});
			
			var optionss = $('#myform_List_Agent_to option');
			var values = $.map(optionss ,function(option) {
				if(agent != ''){
					agent+=',';
					
				}
				agent+="'"+option.value+"'";
			});
			
			start_time = $('#start_time').val();
			end_time = $('#end_time').val();

			    $.getJSON("server-side/report/sales_statistics.action.php?act=answer_call_operator&start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
				    
			    	options.xAxis.categories = json[1]['agent'];
			    	options.tooltip.valueSuffix = json[1]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[1]['name'];
			    	options.series[0].data = json[1]['call_count'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
		}
		function getData1(){
			 var options = {
			        chart: {
			            renderTo: 'chart_container1',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'Disconnect cause',
			            x: -20 
			        },
			        subtitle: {
			            text: '',
			            x: -20
			        },
			        xAxis: {
			            categories: [],
			            labels: {
			            	align: 'center'
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'Calls'
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

			 var i=0;
			
				agent	= '';
				queuet = '';
			
				var optionss = $('#myform_List_Queue_to option');
				var values = $.map(optionss ,function(option) {
					if(queuet != ""){
						queuet+=",";
						
					}
					queuet+="'"+option.value+"'";
				});
			
			var optionss = $('#myform_List_Agent_to option');
			var values = $.map(optionss ,function(option) {
				if(agent != ''){
					agent+=',';
					
				}
				agent+="'"+option.value+"'";
			});
			
			start_time = $('#start_time').val();
			end_time = $('#end_time').val();

			    $.getJSON("server-side/report/sales_statistics.action.php?act=cause_fix&start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
				    
			    	options.xAxis.categories = json[0]['cause'];
			    	options.tooltip.valueSuffix = json[0]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[0]['name'];
			    	options.series[0].data = json[0]['quantity'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
		}
		function getData2(){
			 var options = {
			        chart: {
			            renderTo: 'chart_container2',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'Answered Calls by time',
			            x: -20 
			        },
			        subtitle: {
			            text: '',
			            x: -20
			        },
			        xAxis: {
			            categories: [],
			            labels: {
			            	 rotation: -45,
			            	align: 'right'
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'Calls'
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

			 var i=0;
			
				agent	= '';
				queuet = '';
			
				var optionss = $('#myform_List_Queue_to option');
				var values = $.map(optionss ,function(option) {
					if(queuet != ""){
						queuet+=",";
						
					}
					queuet+="'"+option.value+"'";
				});
			
			var optionss = $('#myform_List_Agent_to option');
			var values = $.map(optionss ,function(option) {
				if(agent != ''){
					agent+=',';
					
				}
				agent+="'"+option.value+"'";
			});
			
			start_time = $('#start_time').val();
			end_time = $('#end_time').val();

			    $.getJSON("server-side/report/sales_statistics.action.php?act=answer_call_hour&start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
				    
			    	options.xAxis.categories = json[2]['datetime'];
			    	options.tooltip.valueSuffix = json[2]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[2]['name'];
			    	options.series[0].data = json[2]['answer_count'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
		}
		function getData3(){
			 var options = {
			        chart: {
			            renderTo: 'chart_container3',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'Answered Calls by weekdays',
			            x: -20 
			        },
			        subtitle: {
			            text: '',
			            x: -20
			        },
			        xAxis: {
			            categories: [],
			            labels: {
			            	 rotation: -45,
			            	align: 'right'
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'Calls'
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

			 var i=0;
			
				agent	= '';
				queuet = '';
			
				var optionss = $('#myform_List_Queue_to option');
				var values = $.map(optionss ,function(option) {
					if(queuet != ""){
						queuet+=",";
						
					}
					queuet+="'"+option.value+"'";
				});
			
			var optionss = $('#myform_List_Agent_to option');
			var values = $.map(optionss ,function(option) {
				if(agent != ''){
					agent+=',';
					
				}
				agent+="'"+option.value+"'";
			});
			
			start_time = $('#start_time').val();
			end_time = $('#end_time').val();

			    $.getJSON("server-side/report/sales_statistics.action.php?act=answer_call_week&start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
				    
			    	options.xAxis.categories = json[3]['datetime1'];
			    	options.tooltip.valueSuffix = json[3]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[3]['name'];
			    	options.series[0].data = json[3]['answer_count1'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
		}

		function getData4(){
			 var options = {
			        chart: {
			            renderTo: 'chart_container4',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'Answered Calls by Day',
			            x: -20 
			        },
			        subtitle: {
			            text: '',
			            x: -20
			        },
			        xAxis: {
			            categories: [],
			            labels: {
			            	 rotation: -45,
			            	align: 'right'
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'Calls'
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

			 var i=0;
			
				agent	= '';
				queuet = '';
			
				var optionss = $('#myform_List_Queue_to option');
				var values = $.map(optionss ,function(option) {
					if(queuet != ""){
						queuet+=",";
						
					}
					queuet+="'"+option.value+"'";
				});
			
			var optionss = $('#myform_List_Agent_to option');
			var values = $.map(optionss ,function(option) {
				if(agent != ''){
					agent+=',';
					
				}
				agent+="'"+option.value+"'";
			});
			
			start_time = $('#start_time').val();
			end_time = $('#end_time').val();

			    $.getJSON("server-side/report/sales_statistics.action.php?act=answer_call_day&start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
				    
			    	options.xAxis.categories = json[4]['datetime2'];
			    	options.tooltip.valueSuffix = json[4]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[4]['name'];
			    	options.series[0].data = json[4]['answer_count2'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
		}

		function getData5(){
			 var options = {
			        chart: {
			            renderTo: 'chart_container5',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'Disconnect cause',
			            x: -20 
			        },
			        subtitle: {
			            text: '',
			            x: -20
			        },
			        xAxis: {
			            categories: [],
			            labels: {
			            	 rotation: -45,
			            	align: 'right'
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'Calls'
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

			 var i=0;
			
				agent	= '';
				queuet = '';
			
				var optionss = $('#myform_List_Queue_to option');
				var values = $.map(optionss ,function(option) {
					if(queuet != ""){
						queuet+=",";
						
					}
					queuet+="'"+option.value+"'";
				});
			
			var optionss = $('#myform_List_Agent_to option');
			var values = $.map(optionss ,function(option) {
				if(agent != ''){
					agent+=',';
					
				}
				agent+="'"+option.value+"'";
			});
			
			start_time = $('#start_time').val();
			end_time = $('#end_time').val();

			    $.getJSON("server-side/report/sales_statistics.action.php?act=disconect_couse&start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
				    
			    	options.xAxis.categories = json[5]['cause1'];
			    	options.tooltip.valueSuffix = json[5]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[5]['name'];
			    	options.series[0].data = json[5]['answer_count3'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
		}

		function getData6(){
			 var options = {
			        chart: {
			            renderTo: 'chart_container6',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'Unanswered Calls By Queue',
			            x: -20 
			        },
			        subtitle: {
			            text: '',
			            x: -20
			        },
			        xAxis: {
			            categories: [],
			            labels: {
			            	 rotation: -45,
			            	align: 'right'
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'Calls'
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

			 var i=0;
			
				agent	= '';
				queuet = '';
			
				var optionss = $('#myform_List_Queue_to option');
				var values = $.map(optionss ,function(option) {
					if(queuet != ""){
						queuet+=",";
						
					}
					queuet+="'"+option.value+"'";
				});
			
			var optionss = $('#myform_List_Agent_to option');
			var values = $.map(optionss ,function(option) {
				if(agent != ''){
					agent+=',';
					
				}
				agent+="'"+option.value+"'";
			});
			
			start_time = $('#start_time').val();
			end_time = $('#end_time').val();

			    $.getJSON("server-side/report/sales_statistics.action.php?act=unanswer_call_queue&start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
				    
			    	options.xAxis.categories = json[6]['queue1'];
			    	options.tooltip.valueSuffix = json[6]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[6]['name'];
			    	options.series[0].data = json[6]['count1'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
		}

		function getData7(){
			 var options = {
			        chart: {
			            renderTo: 'chart_container7',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'Answered Calls by Queue',
			            x: -20 
			        },
			        subtitle: {
			            text: '',
			            x: -20
			        },
			        xAxis: {
			            categories: [],
			            labels: {
			            	 rotation: -45,
			            	align: 'right'
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'Calls'
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

			 var i=0;
			
				agent	= '';
				queuet = '';
			
				var optionss = $('#myform_List_Queue_to option');
				var values = $.map(optionss ,function(option) {
					if(queuet != ""){
						queuet+=",";
						
					}
					queuet+="'"+option.value+"'";
				});
			
			var optionss = $('#myform_List_Agent_to option');
			var values = $.map(optionss ,function(option) {
				if(agent != ''){
					agent+=',';
					
				}
				agent+="'"+option.value+"'";
			});
			
			start_time = $('#start_time').val();
			end_time = $('#end_time').val();

			    $.getJSON("server-side/report/sales_statistics.action.php?act=answer_call_queue&start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
				    
			    	options.xAxis.categories = json[7]['queue2'];
			    	options.tooltip.valueSuffix = json[7]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[7]['name'];
			    	options.series[0].data = json[7]['count2'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
		}


		 function getData8(){
			 var options = {
			        chart: {
			            renderTo: 'chart_container8',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'Unanswered Calls by Day',
			            x: -20 
			        },
			        subtitle: {
			            text: '',
			            x: -20
			        },
			        xAxis: {
			            categories: [],
			            labels: {
			            	 rotation: -45,
			            	align: 'right'
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'Calls'
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

			 var i=0;
			
				agent	= '';
				queuet = '';
			
				var optionss = $('#myform_List_Queue_to option');
				var values = $.map(optionss ,function(option) {
					if(queuet != ""){
						queuet+=",";
						
					}
					queuet+="'"+option.value+"'";
				});
			
			var optionss = $('#myform_List_Agent_to option');
			var values = $.map(optionss ,function(option) {
				if(agent != ''){
					agent+=',';
					
				}
				agent+="'"+option.value+"'";
			});
			
			start_time = $('#start_time').val();
			end_time = $('#end_time').val();

			    $.getJSON("server-side/report/sales_statistics.action.php?act=unanswer_call_day&start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
				    
			    	options.xAxis.categories = json[8]['times'];
			    	options.tooltip.valueSuffix = json[8]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[8]['name'];
			    	options.series[0].data = json[8]['unanswer_call'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
		}



		 function getData9(){
			 var options = {
			        chart: {
			            renderTo: 'chart_container9',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'Unanswered Calls by time',
			            x: -20 
			        },
			        subtitle: {
			            text: '',
			            x: -20
			        },
			        xAxis: {
			            categories: [],
			            labels: {
			            	 rotation: -45,
			            	align: 'right'
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'Calls'
			            },
			            plotLines: [{
			                value: 0,
			                width: 1,
			                color: '#808080'
			            }]
			        },
			        tooltip: {
			        	//valueSuffix: ' áƒªáƒ�áƒšáƒ˜'
			                
			        },
			        legend: {
		                layout: 'vertical',
		                align: 'left',
		                verticalAlign: 'top',
		                borderWidth: 0
			        },
			        series: []
			    }

			 var i=0;
			
				agent	= '';
				queuet = '';
			
				var optionss = $('#myform_List_Queue_to option');
				var values = $.map(optionss ,function(option) {
					if(queuet != ""){
						queuet+=",";
						
					}
					queuet+="'"+option.value+"'";
				});
			
			var optionss = $('#myform_List_Agent_to option');
			var values = $.map(optionss ,function(option) {
				if(agent != ''){
					agent+=',';
					
				}
				agent+="'"+option.value+"'";
			});
			
			start_time = $('#start_time').val();
			end_time = $('#end_time').val();

			    $.getJSON("server-side/report/sales_statistics.action.php?act=unanswer_call_hour&start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
				    
			    	options.xAxis.categories = json[9]['times2'];
			    	options.tooltip.valueSuffix = json[9]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[9]['name'];
			    	options.series[0].data = json[9]['unanswer_count1'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
		}

		 function getData10(){
			 var options = {
			        chart: {
			            renderTo: 'chart_container10',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'Unanswered Calls by weekdays',
			            x: -20 
			        },
			        subtitle: {
			            text: '',
			            x: -20
			        },
			        xAxis: {
			            categories: [],
			            labels: {
			            	 rotation: -45,
			            	align: 'right'
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'Calls'
			            },
			            plotLines: [{
			                value: 0,
			                width: 1,
			                color: '#808080'
			            }]
			        },
			        tooltip: {
			        	//valueSuffix: ' áƒªáƒ�áƒšáƒ˜'
			                
			        },
			        legend: {
		                layout: 'vertical',
		                align: 'left',
		                verticalAlign: 'top',
		                borderWidth: 0
			        },
			        series: []
			    }

			 var i=0;
			
				agent	= '';
				queuet = '';
			
				var optionss = $('#myform_List_Queue_to option');
				var values = $.map(optionss ,function(option) {
					if(queuet != ""){
						queuet+=",";
						
					}
					queuet+="'"+option.value+"'";
				});
			
			var optionss = $('#myform_List_Agent_to option');
			var values = $.map(optionss ,function(option) {
				if(agent != ''){
					agent+=',';
					
				}
				agent+="'"+option.value+"'";
			});
			
			start_time = $('#start_time').val();
			end_time = $('#end_time').val();

			    $.getJSON("server-side/report/sales_statistics.action.php?act=unanswer_call_week&start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
				    
			    	options.xAxis.categories = json[10]['date1'];
			    	options.tooltip.valueSuffix = json[10]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[10]['name'];
			    	options.series[0].data = json[10]['unanswer_count2'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
			    
		}

		 function drawFirstLevel(){
			    var options = {
			                  chart: {
			                      renderTo: 'chart_container0',
			                      plotBackgroundColor: null,
			                      plotBorderWidth: null,
			                      plotShadow: null,
			                  },
			                  colors: ['#538DD5', '#FA3A3A'],
			                  title: {
			                      text: 'Technical information'
			                  },
			                  tooltip: {
			                      formatter: function() {
			                          return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
			                      }
			                  },
			                  plotOptions: {
			                   pie: {
			                          allowPointSelect: true,
			                          cursor: 'pointer',
			                          dataLabels: {
			                              enabled: true,
			                              color: '#000000',
			                              connectorColor: '#FA3A3A',
			                              formatter: function() {
			                                  return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
			                              }
			                          },
			                          point: {
			                              events: {
			                                  click: function() {                   
			                                  }
			                              }
			                          }
			                      }
			                  },
			                  series: [{
			                      type: 'pie',
			                      name: 'Categories',
			                     // color: '#FA3A3A',
			                      data: []
			                  }]
			              }
			    var i=0;
			    
			    agent = '';
			    queuet = '';
			   
			    var optionss = $('#myform_List_Queue_to option');
			    var values = $.map(optionss ,function(option) {
			     if(queuet != ""){
			      queuet+=",";
			      
			     }
			     queuet+="'"+option.value+"'";
			    });
			   
			   var optionss = $('#myform_List_Agent_to option');
			   var values = $.map(optionss ,function(option) {
			    if(agent != ''){
			     agent+=',';
			     
			    }
			    agent+="'"+option.value+"'";
			   });
			   
			   start_time = $('#start_time').val();
			   end_time = $('#end_time').val();
			              $.getJSON("server-side/report/tab_0.action.php?start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
			                  options.series[0].data = json;
			                  chart = new Highcharts.Chart(options);
			              });
			   }
		 function drawFirstLevel1(){
			    var options = {
			                  chart: {
			                      renderTo: 'chart_container0r',
			                      plotBackgroundColor: null,
			                      plotBorderWidth: null,
			                      plotShadow: null,
			                  },
			                  colors: ['#538DD5', '#76933C'],
			                  title: {
			                      text: 'Technical information'
			                  },
			                  tooltip: {
			                      formatter: function() {
			                          return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
			                      }
			                  },
			                  plotOptions: {
			                   pie: {
			                          allowPointSelect: true,
			                          cursor: 'pointer',
			                          dataLabels: {
			                              enabled: true,
			                              color: '#000000',
			                              connectorColor: '#FA3A3A',
			                              formatter: function() {
			                                  return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
			                              }
			                          },
			                          point: {
			                              events: {
			                                  click: function() {                   
			                                  }
			                              }
			                          }
			                      }
			                  },
			                  series: [{
			                      type: 'pie',
			                      name: 'Categories',
			                     // color: '#FA3A3A',
			                      data: []
			                  }]
			              }
			    var i=0;
			    
			    agent = '';
			    queuet = '';
			   
			    var optionss = $('#myform_List_Queue_to option');
			    var values = $.map(optionss ,function(option) {
			     if(queuet != ""){
			      queuet+=",";
			      
			     }
			     queuet+="'"+option.value+"'";
			    });
			   
			   var optionss = $('#myform_List_Agent_to option');
			   var values = $.map(optionss ,function(option) {
			    if(agent != ''){
			     agent+=',';
			     
			    }
			    agent+="'"+option.value+"'";
			   });
			   
			   start_time = $('#start_time').val();
			   end_time = $('#end_time').val();
			              $.getJSON("server-side/report/tab_0_0.action.php?start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
			                  options.series[0].data = json;
			                  chart = new Highcharts.Chart(options);
			              });
			   }
		 
		 function getData11(){
			 var options = {
			        chart: {
			            renderTo: 'chart_container11',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'Answered Calls by seconds',
			            x: -20 
			        },
			        subtitle: {
			            text: '',
			            x: -20
			        },
			        xAxis: {
			            categories: [],
			            labels: {
			            	 rotation: -45,
			            	align: 'right'
			            }
			        },
			        yAxis: {
			            title: {
			                text: 'Calls'
			            },
			            plotLines: [{
			                value: 0,
			                width: 1,
			                color: '#808080'
			            }]
			        },
			        tooltip: {
			        	//valueSuffix: ' áƒªáƒ�áƒšáƒ˜'
			                
			        },
			        legend: {
		                layout: 'vertical',
		                align: 'left',
		                verticalAlign: 'top',
		                borderWidth: 0
			        },
			        series: []
			    }

			 var i=0;
			
				agent	= '';
				queuet = '';
			
				var optionss = $('#myform_List_Queue_to option');
				var values = $.map(optionss ,function(option) {
					if(queuet != ""){
						queuet+=",";
						
					}
					queuet+="'"+option.value+"'";
				});
			
			var optionss = $('#myform_List_Agent_to option');
			var values = $.map(optionss ,function(option) {
				if(agent != ''){
					agent+=',';
					
				}
				agent+="'"+option.value+"'";
			});
			
			start_time = $('#start_time').val();
			end_time = $('#end_time').val();

			    $.getJSON("server-side/report/sales_statistics.action.php?act=answer_call_sec&start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
				    
			    	options.xAxis.categories = json[11]['call_second'];
			    	options.tooltip.valueSuffix = json[11]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[11]['name'];
			    	options.series[0].data = json[11]['mas'];
			    	options.series[0].type = "column";
			    	
			        chart = new Highcharts.Chart(options);
			        
			    });
			    
		}
      

		function go_next(val,par){
			if(val != undefined){
				$("#myform_List_"+par+"_from option:selected").remove();
				$("#myform_List_"+par+"_to").append(new Option(val, val));
			}
		}

		function go_previous(val,par){
			if(val != undefined){
				$("#myform_List_"+par+"_to option:selected").remove();
				$("#myform_List_"+par+"_from").append(new Option(val, val));
			}
		}

		function go_last(par){
			var options = $('#myform_List_'+par+'_from option');
			$("#myform_List_"+par+"_from option").remove();
			var values = $.map(options ,function(option) {
			    $("#myform_List_"+par+"_to").append(new Option(option.value, option.value));
			});
		}

		function go_first(par){
			var options = $('#myform_List_'+par+'_to option');
			$("#myform_List_"+par+"_to option").remove();
			var values = $.map(options ,function(option) {
			    $("#myform_List_"+par+"_from").append(new Option(option.value, option.value));
			});
		}

		$(document).on("click", "#show_report", function () {
			var i=0;
			paramq 			= new Object();
			parama 			= new Object();
			parame 			= new Object();
			parame.agent	= '';
			parame.queuet = '';
			paramm		= "server-side/report/inc_tech_report.action.php";
			
			var options = $('#myform_List_Queue_to option');
			var values = $.map(options ,function(option) {
				if(parame.queuet != ""){
					parame.queuet+=",";
					
				}
				parame.queuet+="'"+option.value+"'";
			});

			
			var options = $('#myform_List_Agent_to option');
			var values = $.map(options ,function(option) {
				if(parame.agent != ''){
					parame.agent+=',';
					
				}
				parame.agent+="'"+option.value+"'";
			});
			parame.act = 'check';
			parame.start_time = $('#start_time').val();
			parame.end_time   = $('#end_time').val();
			parame.change_tab = $('#change_tab').val();
			
			
			if(parame.queuet==''){
				alert('Choose Queue');
			}else if(parame.agent==''){
				alert('Choose Operator');
			}else{
				$('#loading1').css('display','block');
				$('#tab-0,#tab-1,#tab-2,#tab-3').css('display','none');
				$('#tab-'+$('#change_tab').val()).css('display','block');
				if ($('#change_tab').val() == 0) {
					parame.act = 'tab_0';
	        		drawFirstLevel();
	        		drawFirstLevel1();
	        		$('#tabs').css('height','800px');
	        	}else if($('#change_tab').val() == 1){
	        		parame.act = 'tab_1';
	        		getData();
	       			getData11();
	       			getData7();
	       			getData1();
	       			$('#tabs').css('height','1800px');
	            }else if($('#change_tab').val() == 2){
	            	parame.act = 'tab_2';
	            	getData5();
	            	getData6();
	            	$('#tabs').css('height','1200px');
	            }else if($('#change_tab').val() == 3){
	            	parame.act = 'tab_3';
	            	getData4();
	            	getData8();
	            	getData2();
	            	getData9();
	            	getData3();
	            	getData10();
	            	$('#tabs').css('height','1900px');
	            }
				$.ajax({
			        url: paramm,
				    data: parame,
			        success: function(data) {		        	
						$("#answer_call table tbody").html(data.page.answer_call);
						$($("#technik_info table tr")[1]).html(data.page.technik_info);
						$("#report_info table").html(data.page.report_info);
						$("#answer_call_info table").html(data.page.answer_call_info);
						$("#answer_call_by_queue table tbody").html(data.page.answer_call_by_queue);
						$("#disconnection_cause table tbody").html(data.page.disconnection_cause);
						$("#unanswer_call table").html(data.page.unanswer_call);
						$("#disconnection_cause_unanswer table tbody").html(data.page.disconnection_cause_unanswer);
						$("#unanswered_calls_by_queue table tbody").html(data.page.unanswered_calls_by_queue);
						$("#totals table tbody").html(data.page.totals);
						$("#call_distribution_per_day table tbody").html(data.page.call_distribution_per_day);
						$("#call_distribution_per_hour table tbody").html(data.page.call_distribution_per_hour);
						$("#call_distribution_per_day_of_week table tbody").html(data.page.call_distribution_per_day_of_week);
						$("#service_level table tbody").html(data.page.service_level);
				    }
			    }).done(function() {
			    	$('#loading1').css('display','none');
			    });
			}
        });

		$(document).on("click", "#technik_info table td:nth-child( 3 )", function () {
			LoadDialog();
		});
		
		$(document).on("click", "#technik_info table td:nth-child( 4 )", function () {
			LoadDialog1();
		});

		$(document).on("click", "#technik_info table td:nth-child( 6 )", function () {
			LoadDialog2();
		});
		
		var record;
 		function play(record){
 			var link = 'http://'+location.hostname + ":8000/" + record;
 			GetDialog_audio("audio_dialog", "auto", "auto","" );
 			$(".ui-dialog-buttonpane").html(" ");
 			$( ".ui-dialog-buttonpane" ).removeClass( "ui-widget-content ui-helper-clearfix ui-dialog-buttonpane" );
 			$("#audio_dialog").html('<audio controls autoplay style="width:500px; min-height: 33px;"><source src="'+link+'" type="audio/wav"> Your browser does not support the audio element.</audio>');
 		}
 		function GetDialog_audio(fname, width, height, buttons,distroy) {
 		    var defoult = {
 		        "save": {
 		            text: "save",
 		            id: "save-dialog",
 		            click: function () {
 		            }
 		        },
 		        "cancel": {
 		            text: "Close",
 		            id: "cancel-dialog",
 		            click: function () {
 		                $(this).dialog("close");
 		            }
 		        }
 		    };
 		    var ok_defoult = "save-dialog";

 		    if (!empty(buttons)) {
 		        defoult = buttons;
 		    }
 		    
 		    $("#" + fname).dialog({
 		    	position: ['center',20],
 		        resizable: false,
 		        width: width,
 		        height: height,
 		        modal: false,
 		        stack: false,
 		        dialogClass: fname + "-class",
 		        buttons: defoult,
 		        close : function(event, ui) {
 		        	if(distroy!="no")$("#"+fname).html("");
 		         }
 		    });
 		}
		function LoadDialog(){
			parame 				= new Object();
			paramm		= "server-side/report/inc_tech_report.action.php";
			parame.start_time 	= $('#start_time').val();
			parame.end_time 	= $('#end_time').val();
			parame.act 			= 'answear_dialog';
			parame.agent	= '';
			parame.queuet = '';
			
			
			var options = $('#myform_List_Queue_to option');
			var values = $.map(options ,function(option) {
				if(parame.queuet != ""){
					parame.queuet+=",";
					
				}
				parame.queuet+="'"+option.value+"'";
			});

			
			var options = $('#myform_List_Agent_to option');
			var values = $.map(options ,function(option) {
				if(parame.agent != ''){
					parame.agent+=',';
					
				}
				parame.agent+="'"+option.value+"'";
			});
			$.ajax({
		        url: paramm,
			    data: parame,
		        success: function(data) {		        	
					$("#add-edit-form").html(data.page.answear_dialog);
					var button = {
							"cancel": {
					            text: "Close",
					            id: "cancel-dialog",
					            click: function () {
					                $(this).dialog("close");
					            }
					        }
						};
					GetDialog("add-edit-form", 700, "auto", button, "no");
					/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
					GetDataTable("example", aJaxURL, "answear_dialog_table&start_time="+parame.start_time+"&end_time="+parame.end_time+"&queuet="+parame.queuet+"&agent="+parame.agent,8, "", 0, "", 1, "desc",'',"<'dataTable_buttons'T><'F'Cfipl>");
					$(".add-edit-form-class,#add-edit-form").css("overflow","visible");
					setTimeout(function(){
					    $('.ColVis, .dataTable_buttons').css('display','none');
			    	}, 90);
			    }
		    });
		}
		
		function LoadDialog1(){
			parame 				= new Object();
			paramm		= "server-side/report/inc_tech_report.action.php";
			parame.start_time 	= $('#start_time').val();
			parame.end_time 	= $('#end_time').val();
			parame.act 			= 'unanswear_dialog';
			parame.agent	= '';
			parame.queuet = '';
			
			
			var options = $('#myform_List_Queue_to option');
			var values = $.map(options ,function(option) {
				if(parame.queuet != ""){
					parame.queuet+=",";
					
				}
				parame.queuet+="'"+option.value+"'";
			});

			
			var options = $('#myform_List_Agent_to option');
			var values = $.map(options ,function(option) {
				if(parame.agent != ''){
					parame.agent+=',';
					
				}
				parame.agent+="'"+option.value+"'";
			});
			$.ajax({
		        url: paramm,
			    data: parame,
		        success: function(data) {		        	
					$("#add-edit-form-unanswer").html(data.page.answear_dialog);
					var button = {
							"cancel": {
					            text: "Close",
					            id: "cancel-dialog",
					            click: function () {
					                $(this).dialog("close");
					            }
					        }
						};
					GetDialog("add-edit-form-unanswer", 500, "auto", button,"no");
					/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
					GetDataTable("example1", aJaxURL, "unanswear_dialog_table&start_time="+parame.start_time+"&end_time="+parame.end_time+"&queuet="+parame.queuet+"&agent="+parame.agent,6, "", 0, "", 1, "desc",'',"<'dataTable_buttons'T><'F'Cfipl>");
					$(".add-edit-form-unanswer-class,#add-edit-form-unanswer").css("overflow","visible");
					setTimeout(function(){
					    $('.ColVis, .dataTable_buttons').css('display','none');
			    	}, 90);
			    }
		    });
		}

		function LoadDialog2(){
			parame 				= new Object();
			paramm		= "server-side/report/inc_tech_report.action.php";
			parame.start_time 	= $('#start_time').val();
			parame.end_time 	= $('#end_time').val();
			parame.act 			= 'undone_dialog';
			parame.agent	= '';
			parame.queuet = '';
			
			
			var options = $('#myform_List_Queue_to option');
			var values = $.map(options ,function(option) {
				if(parame.queuet != ""){
					parame.queuet+=",";
					
				}
				parame.queuet+="'"+option.value+"'";
			});

			
			var options = $('#myform_List_Agent_to option');
			var values = $.map(options ,function(option) {
				if(parame.agent != ''){
					parame.agent+=',';
					
				}
				parame.agent+="'"+option.value+"'";
			});
			$.ajax({
		        url: paramm,
			    data: parame,
		        success: function(data) {		        	
					$("#add-edit-form-undone").html(data.page.answear_dialog);
					var button = {
							"cancel": {
					            text: "Close",
					            id: "cancel-dialog",
					            click: function () {
					                $(this).dialog("close");
					            }
					        }
						};
					GetDialog("add-edit-form-undone", 700, "auto", button);
					/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
					GetDataTable("example2", aJaxURL, "undone_dialog_table&start_time="+parame.start_time+"&end_time="+parame.end_time+"&queuet="+parame.queuet+"&agent="+parame.agent,8, "", 0, "", 1, "desc",'',"<'dataTable_buttons'T><'F'Cfipl>");
					
					$( "div" ).removeClass( "ui-widget-overlay" );
					$(".add-edit-form-undone-class,#add-edit-form-undone").css("overflow","visible");
					setTimeout(function(){
					    $('.ColVis, .dataTable_buttons').css('display','none');
			    	}, 90);
			    }
		    });
		}

		$(document).on("click", "#call_distribution_per_day  td:nth-child( 3 )", function() {
			var firstvar = $($(this).siblings()[0]).text();
		    NEWDIALOG('answear_dialog',firstvar,'new_dealog_table',7);
		});
		$(document).on("click", "#call_distribution_per_day  td:nth-child( 5 )", function() {
			var firstvar = $($(this).siblings()[0]).text();
			NEWDIALOG('unanswear_dialog',firstvar,'new_dealog_table_1',5);
		});

		$(document).on("click", "#call_distribution_per_hour  td:nth-child( 3 )", function() {
			var firstvar = $($(this).siblings()[0]).text();
		    NEWDIALOG('answear_hour_dialog',firstvar,'new_dealog_hour_table',7);
		});
		$(document).on("click", "#call_distribution_per_hour  td:nth-child( 5 )", function() {
			var firstvar = $($(this).siblings()[0]).text();
			NEWDIALOG('unanswear_hour_dialog',firstvar,'new_dealog_hour_table_1',5);
		});

		$(document).on("click", "#call_distribution_per_day_of_week  td:nth-child( 3 )", function() {
			var firstvar = $($(this).siblings()[0]).text();
		    NEWDIALOG('answear_dayofweek_dialog',firstvar,'new_dealog_dayofweek_table',7);
		});
		$(document).on("click", "#call_distribution_per_day_of_week  td:nth-child( 5 )", function() {
			var firstvar = $($(this).siblings()[0]).text();
			NEWDIALOG('unanswear_dayofweek_dialog',firstvar,'new_dealog_dayofweek_table_1',5);
		});
		
		$(document).on("click", ".show_this_phone", function() {
			//alert();
			var firstvar = $(this).text();
			NEWDIALOG('answear_un_dialog',firstvar,'new_dealog_un_table',7);
		});

		function NEWDIALOG(myact,myvar,table_id,row_count){
			parame 				= new Object();
			dialog_link		    = "server-side/report/new_dialog.action.php";
			parame.act 			= myact;
			parame.dialog_date  = myvar;
			parame.agent	    = '';
			parame.queuet       = '';
			
			
			var options = $('#myform_List_Queue_to option');
			var values = $.map(options ,function(option) {
				if(parame.queuet != ""){
					parame.queuet+=",";
					
				}
				parame.queuet+="'"+option.value+"'";
			});

			
			var options = $('#myform_List_Agent_to option');
			var values = $.map(options ,function(option) {
				if(parame.agent != ''){
					parame.agent+=',';
					
				}
				parame.agent+="'"+option.value+"'";
			});
			$.ajax({
		        url: dialog_link,
			    data: parame,
		        success: function(data) {
			        $("#new_dialog").html('');
					$("#new_dialog").html(data.page.answear_dialog);
					GetDialog("new_dialog", "700", "auto", "","no");
					/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
					GetDataTable(table_id, dialog_link, myact+"_table&dialog_date="+parame.dialog_date+"&start_time="+$('#start_time').val()+"&end_time="+$('#end_time').val()+"&queuet="+parame.queuet+"&agent="+parame.agent, row_count, "", 0, "", 1, "desc",'',"<'dataTable_buttons'T><'F'Cfipl>");
			    }
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
		$(document).on("click", "button", function (e) {
			var tbl_id = $(this).attr('id');
			var table = tbl_id.substr(0,tbl_id.length - 4);

			var tableToExcel = (function() {
		          var uri = 'data:application/vnd.ms-excel;base64,'
		            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
		            , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
		            , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
		          return function(table, name) {
		            if (!table.nodeType) table = document.getElementById(table)
		            var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
		            window.location.href = uri + base64(format(template, ctx))
		          }
		        })();
			tableToExcel(table, 'excel export')
		});
    </script>
    
</head>


<body>
<div id="loading1">
    <p><img src="media/images/loader.gif" /></p>
</div>
<div id="tabs" style="width: 90%;">
		<div class="callapp_head">Technical incoming<hr class="callapp_head_hr"></div>
		
        <label>Tabs</label>
		<select id="change_tab" >
		<option value="0">Main</option>
		<option value="1">Answered</option>
		<option value="2">Unanswered</option>
		<option value="3">Call Distribution</option>
		</select>
		<div class="clear"></div>
		
		<div style="width: 27%; float:left;">
			<span>Choose Queue</span>
			<hr>
			
			    <table border="0" cellspacing="0" cellpadding="8">
					<tbody>
					<tr>
					   	<td>
							Available<br><br>
						    <select name="List_Queue_available" multiple="multiple" id="myform_List_Queue_from" size="10" style="height: 100px;width: 125px;" >
							    <option value="2022028">2022028</option>
							</select>
						</td>
						<td align="left">
							<a onclick="go_next($('#myform_List_Queue_from option:selected').val(),'Queue')"><img src="media/images/go-next.png" width="16" height="16" border="0"></a>
							<a onclick="go_previous($('#myform_List_Queue_to option:selected').val(),'Queue')"><img src="media/images/go-previous.png" width="16" height="16" border="0"></a>
							<br>
							<br>
							<a  onclick="go_last('Queue')"><img src="media/images/go-last.png" width="16" height="16" border="0"></a>
							<a  onclick="go_first('Queue')"><img src="media/images/go-first.png" width="16" height="16" border="0"></a>
						</td>
						<td>
							Selected<br><br>
						    <select size="10" name="List_Queue[]" multiple="multiple" style="height: 100px;width: 125px;" id="myform_List_Queue_to">
								
						    </select>
					   </td>
					</tr> 
					</tbody>
				</table>
			</div>
			<div style="width: 27%; float:left; margin-left:75px;">
				<span>Choose Operator</span>
				<hr>
				<table border="0" cellspacing="0" cellpadding="8" >
					<tbody><tr>
					   <td>Available<br><br>
					    <select size="10" name="List_Agent_available" multiple="multiple" id="myform_List_Agent_from" style="height: 100px;width: 125px;">
							<option value="99">99</option>
							<option value="TD5">TD5</option>
					    </select>
					</td>
					<td align="left">
							<a  onclick="go_next($('#myform_List_Agent_from option:selected').val(),'Agent')"><img src="media/images/go-next.png" width="16" height="16" border="0"></a>
							<a  onclick="go_previous($('#myform_List_Agent_to option:selected').val(),'Agent')"><img src="media/images/go-previous.png" width="16" height="16" border="0"></a>
							<br>
							<br>
							<a  onclick="go_last('Agent')"><img src="media/images/go-last.png" width="16" height="16" border="0"></a>
							<a  onclick="go_first('Agent')"><img src="media/images/go-first.png" width="16" height="16" border="0"></a>
					</td>
					<td>
						Selected<br><br>
					    <select size="10" name="List_Agent[]" multiple="multiple" style="height: 100px;width: 125px;" id="myform_List_Agent_to" >
					
					    </select>
					   </td>
					</tr> 
					</tbody>
				</table>
			</div>
			<div class="clear"></div>
			<div id="rest" style="width: 100%; float:none;">
				<h2>Choose date</h2>
				<hr>
				<div id="button_area">
	            	<div class="left" style="width: 160px;">
	            		<label for="search_start" class="left" style="margin: 6px 0 0 9px; font-size: 12px;">Start Date</label>
	            		<input type="text" name="search_start" id="start_time" value="" class="inpt right" style="width: 80px; height: 16px;"/>
	            	</div>
	            	<div class="right" style="width: 160px;">
	            		<label for="search_end" class="left" style="margin: 6px 0 0 9px; font-size: 12px;">End Date</label>
	            		<input type="text" name="search_end" id="end_time" value="" class="inpt right" style="width: 80px; height: 16px;"/>
            		</div>	
            	</div>
            	
            		<input style="margin-left: 15px;" id="show_report" name="show_report" type="submit" value="Show Report">
            	
		<div class="clear"></div>
		<div id="tab-0">	
		<div class="clear"></div>
		<div><span style="float: left;">Technical information</span><button style="float: right;" id="technik_info_but">Excel</button></div>
		<div class="clear"></div>
		<div id="technik_info">
                <table>                
                <tr>
                	<th></th>
                    <th>Total</th>
                    <th>Answered</th>
                    <th>Unanswered</th>
                    <th>Processed</th>
                    <th>Untreated</th>
                    <th>Answered%</th>
                    <th>Unanswered %</th>
                    <th>Processed %</th>
                    <th>Untreated %</th>
                </tr>
                <tr>
                    <td>Call</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </table>
                </div>
                <br>
                <div id="chart_container0" style="float:left; width: 50%; height: 300px;"></div>
                 <div id="chart_container0r" style="float:left; width: 50%; height: 300px; "></div>
		</div>
		 </div>
		  <!-- tab 0 end -->
		 
		 <!-- tab 1 start -->
		<div id="tab-1">
		   <div width="99%">
            <div style="width: 49%;float: left;margin-right: 20px;">
                <div class="clear"></div>
                <div>
                    <span style="float: left;">Report Info</span>
                    <button style="float: right;" id="report_info_but">Excel</button>
                </div>
                <div class="clear"></div>
                <div id="report_info">
                <table>
                
                <tr>
                    <td>Queue:</td>
                    <td></td>
                </tr>
                <tr>
	                	<td>Start Date:</td>
	                    <td></td>
                </tr>
                
                <tr>
                       <td>End Date:</td>
                       <td></td>
                </tr>
                <tr>
                       <td>Period:</td>
                       <td></td>
                </tr>
                </table>
                </div>
               </div>
               
               <div style="width: 49%;float: left;"> 
                <div class="clear"></div>
                <div>
                    <span style="float: left;">Answered Calls</span>
                    <button style="float: right;" id="answer_call_info_but">Excel</button>
                </div>
                <div class="clear"></div>
                <div id="answer_call_info">
                <table>
                <tr> 
                  <td>Answered Calls</td>
                  <td></td>
                </tr>
               
                <tr>
                  <td>Avg. Duration:</td>
                  <td></td>
                </tr>
                <tr>
                  <td>Total Avg Duration:</td>
                  <td> </td>
                </tr>
                <tr>
                  <td>Avg Waiting time:</td>
                  <td></td>
                </tr>
                </table>
                </div>
            </div>
        </div>
        <br>
        <div class="clear"></div>
		<div><span style="float: left;">Answered calls by carrier</span><button style="float: right;" id="answer_call_by_queue_but">Excel</button></div>
		<div class="clear"></div>
		<div id="answer_call_by_queue">
        <table>
            <thead>
            <tr>
                  <th>Operator</th>
                  <th>Calls</th>
                  <th>% Calls</th>
                  <th>Duration</th>
                  <th>% Duration</th>
                  <th>AVG Duration</th>
                  <th>Waiting time</th>
                  <th>Avg. Waiting time</th>
            </tr>
            </thead>
            <tbody>
                
			</tbody>
        </table>
        </div>
        <br>
          <div id="chart_container" style="float:left; width: 96%; height: 300px; margin-left: 20px;"></div>
      <br>
        <div width="99%">
            <div style="width: 49%;float: left;margin-right: 20px;">
            <div class="clear"></div>
    		<div><span style="float: left;">Service Level</span><button style="float: right;" id="service_level_but">Excel</button></div>
    		<div class="clear"></div>
    		<div id="service_level">
                <table>
                <thead>
                <tr> 
                    <th>Answer</th>
                    <th>count</th>
                    <th>Delta</th>
                    <th>%</th>
                </tr>
                </thead>
                <tbody>
                
              </tbody>
              </table>
            </div>
            </div>
            <div style="width: 49%;float: left;">
            <div id="chart_container11" bgcolor="#fffdf3" style="float:left; width: 100%; height: 300px;"></div>
            </div>
        </div>
            
        <div class="clear"></div>
        
        <div width="99%">
            <div style="width: 49%;float: left;margin-right: 20px;">
            <div class="clear"></div>
    		<div><span style="float: left;">Answered Calls by Queue</span><button style="float: right;" id="answer_call_but">Excel</button></div>
    		<div class="clear"></div>
    		    <div id="answer_call">
                <table>
                <thead>
                <tr> 
                    <th>Queue</th>
                    <th>Total</th>
                    <th>%</th>
                </tr>
                </thead>
                <tbody>
                
              </tbody>
              </table>
              </div>
              </div>
            <div style="width: 49%;float: left;">
            <div id="chart_container7" bgcolor="#fffdf3" style="float:left; width: 100%; height: 300px;"></div>
            </div>
        </div>
            
        <div class="clear"></div>
        
        
        <div width="99%">
            <div style="width: 49%;float: left;margin-right: 20px;">
            <div class="clear"></div>
    		<div><span style="float: left;">Disconnect cause</span><button style="float: right;" id="disconnection_cause_but">Excel</button></div>
    		<div class="clear"></div>
    		    <div id="disconnection_cause">
                <table>
                <thead>
                <tr>
                    <th>Cause</th>
                    <th>Total</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
	                <tr>
						<td>ოპერატორმა გათიშა:</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Customers cut off the line:</td>
						<td></td>
						<td></td>
					</tr>
                </tbody>
                </table>
                </div>
              </div>
            <div style="width: 49%;float: left;">
              <div id="chart_container1" style="float:left; width: 100%; height: 300px;"></div>
            </div>
        </div>
		 </div>
		 <!-- tab 1 end -->
		 
		 <!-- tab 2 start -->
		 <div id="tab-2">
		    <div width="99%">
            <div style="width: 49%;float: left;margin-right: 20px;">
            <div class="clear"></div>
    		<div><span style="float: left;">Report Info</span><button style="float: right;" id="report_info_but">Excel</button></div>
    		<div class="clear"></div>
    		    <div id="report_info">
				<table>
				<tbody>
				<tr>
                    <td>Queue:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Start Date:</td>
                    <td></td>
                </tr>
                
                <tr>
                       <td>End Date:</td>
                       <td></td>
                </tr>
                <tr>
                       <td>Period:</td>
                       <td></td>
                </tr>
				</tbody>
				</table>
                </div>
                </div>
			
            <div style="width: 49%;float: left;">
            <div class="clear"></div>
    		<div><span style="float: left;">Unanswered Calls</span><button style="float: right;" id="unanswer_call_but">Excel</button></div>
    		<div class="clear"></div>
    		<div id="unanswer_call">
				<table>
		        <tr> 
                  <td>Unanswered Callsს რაოდენობა:</td>
		          <td></td>
	            </tr>
                <tr>
                  <td>ლოდინის საშ. დრო კავშირის გაწყვეტამდე:</td>
                  <td></td>
                </tr>
		        <tr>
                  <td>საშ. რიგში პოზიცია კავშირის გაწყვეტამდე:</td>
		          <td></td>
	            </tr>
                <tr>
                  <td>საშ. Start პოზიცია რიგში:</td>
                  <td></td>
                </tr>
	          </table>
	          </div>
	          </div>
</div>
		
<div class="clear"></div>

            <div width="99%">
            <div style="width: 49%;float: left;margin-right: 20px;">
            <div class="clear"></div>
    		<div><span style="float: left;">Disconnect cause</span><button style="float: right;" id="disconnection_cause_unanswer_but">Excel</button></div>
    		<div class="clear"></div>
    		    <div id="disconnection_cause_unanswer">
				<table>
				<thead>
				<tr>
					<th>Cause</th>
					<th>Total</th>
					<th>%</th>
				</tr>
				</thead >
				<tbody>
                <tr> 
                  <td>Customers cut off the line</td>
			      <td></td>
			      <td></td>
		        </tr>
			    <tr> 
                  <td>Time out</td>
			      <td></td>
			      <td></td>
		        </tr>
				</tbody>
			  </table>
			</div>
			</div>
			<div style="width: 49%;float: left;">
			<div id="chart_container5" style="float:left; width: 100%; height: 300px;"></div>
			</div>
			</div>
			
			<div class="clear"></div>
			
			<div width="99%">
            <div style="width: 49%;float: left;margin-right: 20px;">
            <div class="clear"></div>
    		<div><span style="float: left;">Unanswered Calls By Queue</span><button style="float: right;" id="unanswered_calls_by_queue_but">Excel</button></div>
    		<div class="clear"></div>
    		    <div id="unanswered_calls_by_queue">
			
				<table>
				<thead>
                <tr> 
				   	<th>Queue</th>
					<th>Total</th>
					<th>%</th>
                </tr>
				</thead>
				<tbody>
				<tr><td></td><td></td><td></td></tr>
			  </tbody>
			  </table>
			</div>
			</div>
			<div style="width: 49%;float: left;">
			  <div id="chart_container6" style="float:left; width: 100%; height: 300px;"></div>
			  </div>
			</div>
		 </div>
		 <!-- tab 2 end -->
		 
		 <!-- tab 3 start -->
		 <div id="tab-3">
		   <div width="99%">
            <div style="width: 49%;float: left;margin-right: 20px;">
            <div class="clear"></div>
    		<div><span style="float: left;">Report Info</span><button style="float: right;" id="report_info_but">Excel</button></div>
    		<div class="clear"></div>
    		    <div id="report_info">
				<table>
				<tr>
                    <td>Queue:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Start Date:</td>
                    <td></td>
                </tr>
                
                <tr>
                       <td>End Date:</td>
                       <td></td>
                </tr>
                <tr>
                       <td>Period:</td>
                       <td></td>
                </tr>
				</tbody>
				</table>
                </div>
                </div>
			
            <div style="width: 49%;float: left;">
            <div class="clear"></div>
    		<div><span style="float: left;">Total</span><button style="float: right;" id="totals_but">Excel</button></div>
    		<div class="clear"></div>
    		<div id="totals">
    			<table>
		        <tr> 
                  <td>Answered Calls:</td>
		          <td></td>
	            </tr>
                <tr>
                  <td>Unanswered Callsა:</td>
                  <td></td>
                </tr>
	          </table>
                </div>
                </div>
            </div>
            
		<div class="clear"></div>
		
		    <div class="clear"></div>
    		<div><span style="float: left;">Call distribution by days</span><button style="float: right;" id="call_distribution_per_day_but">Excel</button></div>
    		<div class="clear"></div>
    		    <div id="call_distribution_per_day">
		        <table>
				<thead>
				<tr>
					<th>Date</th>
					<th>Total</th>
					<th>Answered</th>
					<th>% Answered</th>
					<th>Unanswered</th>
					<th>% Unanswered</th>
					<th>Avg. Duration</th>
					<th>Avg. Wait Time</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					</tr>
				</tbody>
			</table>
			</div>
			<br>
			<div id="chart_container4" style="float:left; width: 47%; height: 300px; margin-left: 20px;"></div>
			<div id="chart_container8" style="float:right; width: 47%; height: 300px; margin-left: 20px;"></div>
			
			<div class="clear"></div>
		
		    <div class="clear"></div>
    		<div><span style="float: left;">Call distribution by hours</span><button style="float: right;" id="call_distribution_per_hour_but">Excel</button></div>
    		<div class="clear"></div>
    		    <div id="call_distribution_per_hour">
			<table>
				<thead>
				<tr>
                    <th>Hour</th>
                    <th>Total</th>
                    <th>Answered</th>
                    <th>% Answered</th>
                    <th>Unanswered</th>
                    <th>% Unanswered</th>
                    <th>Avg. Duration</th>
                    <th>Avg. Wait Time</th>
                </tr>
				</thead>
				<tbody>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			    </tbody>
			</table>
			</div>
			<div id="chart_container2" style="float:left; width: 47%; height: 300px; margin-left: 20px;"></div>
			  <div id="chart_container9" style="float:right; width: 47%; height: 300px; margin-left: 20px;"></div>
			
			  <div class="clear"></div>
		
		    <div class="clear"></div>
    		<div><span style="float: left;">Call distribution by week days</span><button style="float: right;" id="call_distribution_per_day_of_week_but">Excel</button></div>
    		<div class="clear"></div>
    		    <div id="call_distribution_per_day_of_week">
			<table>
				<thead>
				<tr>
                    <th>Day</th>
                    <th>Total</th>
                    <th>Answered</th>
                    <th>% Answered</th>
                    <th>Unanswered</th>
                    <th>% Unanswered</th>
                    <th>Avg. Duration</th>
                    <th>Avg. Wait Time</th>
                </tr>
				</thead>
				<tbody>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
			</table>
			</div>
			 <div id="chart_container3" style="float:left; width: 47%; height: 300px; margin-left: 20px;"></div>
			<div id="chart_container10" style="float:right; width: 47%; height: 300px; margin-left: 20px;"></div>
		 </div>
		
<!-- jQuery Dialog -->
<div id="add-edit-form" class="form-dialog" title="Answered Calls">
<div id="test"></div>
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form-unanswer" class="form-dialog" title="Unanswered Calls">
<div id="test"></div>
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form-undone" class="form-dialog" title="Uncultivated Calls">
<div id="test"></div>
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form1" class="form-dialog" title="Outgoing call">
<!-- aJax -->
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form2" class="form-dialog" title="Outgoing call">
<!-- aJax -->
</div>

<div id="add-responsible-person" class="form-dialog" title="Responsible Person">
<!-- aJax -->
</div>
<!-- jQuery Dialog -->
<div id="audio_dialog" title="Record">
</div>
<!-- jQuery Dialog -->
<div id="new_dialog" title="Dialog">
</div>
</body>