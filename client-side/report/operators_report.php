<head>
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
		var aJaxURL		= "server-side/report/operators_report.action.php";		//server side folder url
		var aJaxURL1	= "server-side/report/sales_statistics.action.php";		//server side folder url
		var tName		= "example0";										//table name
		var tbName		= "tabs";											//tabs name
		var fName		= "add-edit-form";									//form name
		var file_name 	= '';
		var rand_file 	= '';
		
		$(document).ready(function () {   
			GetTabs(tbName);   	
			GetDate("start_time");
			GetDate("end_time");
			$("#show_report").button({
	            
		    });
		});
function getData(){
			 var options = {
			        chart: {
			            renderTo: 'chart_container',
			            margin: [ 50, 50, 100, 80]
			        },
			        title: {
			            text: 'Answered Calls by Operators',
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

			    $.getJSON("server-side/report/operators_rep.action.php?start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
				    
			    	options.xAxis.categories = json[1]['agent'];
			    	options.tooltip.valueSuffix = json[1]['unit'];
			    	options.series[0] = {};
			    	options.series[0].name = json[1]['name'];
			    	options.series[0].data = json[1]['call_count'];
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
			paramm		= "server-side/report/operators_report.action.php";
			
			getData();
			
		
			
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
			
			parame.start_time = $('#start_time').val();
			parame.end_time = $('#end_time').val();
			parame.act = 'check';
			if(parame.queuet==''){
				alert('აირჩიე რიგი');
			}else if(parame.agent==''){
				alert('აირჩიე ოპერატორი');
			}else{
				$.ajax({
			        url: paramm,
				    data: parame,
			        success: function(data) {		        	
						$("#answer_call_by_queue").html(data.page.answer_call_by_queue);
						//$("#technik_info").html(data.page.answer_call_by_queue);
					}
			    });
			}
        });

		$(document).on("click", "#answear_dialog", function () {
			var name = $(this).attr('user');
			
			LoadDialog(name);
		});
		$(document).on("click", "#undone_dialog", function () {
			var name = $(this).attr('user1');
			
			LoadDialog1(name);
		});
		$(document).on("click", "#answear_dialog1", function () {
			var name = $(this).attr('user2');
			LoadDialog3(name);
		});
		
		$(document).on("click", "#undone_dialog1", function () {
			var name = $(this).attr('user3');
			LoadDialog2(name)
		});
		
		function LoadDialog(name){
			parame 				= new Object();
			paramm		= "server-side/report/operators_report.action.php";
			parame.start_time 	= $('#start_time').val();
			parame.end_time 	= $('#end_time').val();
			parame.act 			= 'answear_dialog';
			parame.agent	= '';
			parame.queuet = '';
			parame.name =name;
			
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
					$("#test").html(data.page.answear_dialog);
					GetDialog("add-edit-form", 941, "auto", "", "no");
					/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
					//GetDataTable("example", aJaxURL, "answear_dialog_table&start_time="+parame.start_time+"&end_time="+parame.end_time+"&queuet="+parame.queuet+"&agent="+parame.agent+"&name="+name,8, "", 0, "", 1, "desc");
					GetDataTable("example", aJaxURL, "answear_dialog_table&start_time="+parame.start_time+"&end_time="+parame.end_time+"&queuet="+parame.queuet+"&agent="+parame.agent,8, "", 0, "", 1, "desc",'',"<'dataTable_buttons'T><'F'Cfipl>");
			    }
		    });
		}
		
		function LoadDialog1(name){
			parame 				= new Object();
			paramm		= "server-side/report/operators_report.action.php";
			parame.start_time 	= $('#start_time').val();
			parame.end_time 	= $('#end_time').val();
			parame.act 			= 'undone_dialog';
			parame.agent	= '';
			parame.queuet = '';
			parame.name =name;
			
			
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
					GetDialog("add-edit-form-undone", 941, "auto", button);
					/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
					//GetDataTable("example2", aJaxURL, "undone_dialog_table&start_time="+parame.start_time+"&end_time="+parame.end_time+"&queuet="+parame.queuet+"&agent="+parame.agent+"&name="+name,8, "", 0, "", 1, "desc");
					GetDataTable("example2", aJaxURL, "undone_dialog_table&start_time="+parame.start_time+"&end_time="+parame.end_time+"&queuet="+parame.queuet+"&agent="+parame.agent,8, "", 0, "", 1, "desc",'',"<'dataTable_buttons'T><'F'Cfipl>");
					$( "div" ).removeClass( "ui-widget-overlay" );
			    }
		    });
		}
		function LoadDialog2(name){
			parame 				= new Object();
			paramm		= "server-side/report/operators_report.action.php";
			parame.start_time 	= $('#start_time').val();
			parame.end_time 	= $('#end_time').val();
			parame.act 			= 'undone_dialog1';
			parame.agent	= '';
			parame.queuet = '';
			parame.name =name;
			
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
					$("#add-edit-form-undone1").html(data.page.answear_dialog);
					var button = {
							"cancel": {
					            text: "დახურვა",
					            id: "cancel-dialog",
					            click: function () {
					                $(this).dialog("close");
					            }
					        }
						};
					GetDialog("add-edit-form-undone1", 1000, "auto", button);
					/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
					GetDataTable("example2", aJaxURL, "undone_dialog_table1&start_time="+parame.start_time+"&end_time="+parame.end_time+"&queuet="+parame.queuet+"&agent="+parame.agent+"&name="+name,8, "", 0, "", 1, "desc");
					
					$( "div" ).removeClass( "ui-widget-overlay" );
			    }
		    });
		}
		function LoadDialog3(name){
			parame 				= new Object();
			paramm		= "server-side/report/operators_report.action.php";
			parame.start_time 	= $('#start_time').val();
			parame.end_time 	= $('#end_time').val();
			parame.act 			= 'answear_dialog1';
			parame.agent	= '';
			parame.queuet = '';
			parame.name =name;
			
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
					$("#add-edit-form1").html(data.page.answear_dialog);
					GetDialog("add-edit-form1", 850, "auto", "", "no");
					/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
					GetDataTable("example2_1", aJaxURL, "answear_dialog_table1&start_time="+parame.start_time+"&end_time="+parame.end_time+"&queuet="+parame.queuet+"&agent="+parame.agent+"&name="+name,8, "", 0, "", 1, "desc");

			    }
		    });
		}
		$(document).on("click", ".excel_answer_call_by_agent_info", function () {	
			 var i=0;
				
				parame 			= new Object();
				parame.start_time = $('#start_time').val();
				parame.end_time = $('#end_time').val();
				parame.agent	= '';
				var options = $('#myform_List_Agent_to option');
				var values = $.map(options ,function(option){
					if(parame.agent != ''){
						parame.agent+=',';
						
					}
					parame.agent+="'"+option.value+"'";
				});

		    	$.ajax({
	 		        url: 'server-side/report/technical/excel_answer_call_by_agent_info.php',
	 			    data: parame,
			        success: function(data) {
				        if(data == 1){
					        alert('ჩანაწერი არ მოიძებნა');
				        }else{
			        	SaveToDisk('server-side/report/technical/excel.xls', 'excel.xls');
				        }
	 			    }
	 		    });
		    	
			});
		function play(record){
 			var link = 'http://'+location.hostname + ":8000/" + record;
 			GetDialog("audio_dialog", "auto", "auto","" );
 			$(".ui-dialog-buttonpane").html(" ");
 			$( ".ui-dialog-buttonpane" ).removeClass( "ui-widget-content ui-helper-clearfix ui-dialog-buttonpane" );
 			$("#audio_dialog").html('<audio controls autoplay style="width:500px; min-height: 33px;"><source src="'+link+'" type="audio/wav"> Your browser does not support the audio element.</audio>');
 		}
		
		
		
		 function SaveToDisk(fileURL, fileName) {
		        // for non-IE
		        if (!window.ActiveXObject) {
		            var save = document.createElement('a');
		            save.href = fileURL;
		            save.target = '_blank';
		            save.download = fileName || 'unknown';

		            var event = document.createEvent('Event');
		            event.initEvent('click', true, true);
		            save.dispatchEvent(event);
		            (window.URL || window.webkitURL).revokeObjectURL(save.href);
		        }
			     // for IE
		        else if ( !! window.ActiveXObject && document.execCommand)     {
		            var _window = window.open(fileURL, "_blank");
		            _window.document.close();
		            _window.document.execCommand('SaveAs', true, fileName || fileURL)
		            _window.close();
		        }
		    }
// 		$(document).on("click", "#name", function () {
// 			var start_time 	= $('#start_time').val();
// 			//var start_time = start_timee.substring(0,10);
// 			var end_time 	= $('#end_time').val();
// 			//var end_time = end_timee.substring(0,10);
// 			var name = $(this).text();
// 			var pathname = window.location.pathname;
// 			window.open("person_info.php?name="+name+"&start_time="+start_time+"&end_time="+end_time, '_blank');
// 		});
		
    </script>
    
</head>

<body>
<div id="loading1">
    <p><img src="media/images/loader.gif" /></p>
</div>
<div id="tabs" style="width: 90%; height: 700px;">
		<div class="callapp_head">Report by Operators<hr class="callapp_head_hr"></div>

			<div style="width: 30%; float:left;">
			<span>Select Queue</span>
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
			<div style="width: 30%; float:left; margin-left:20px;">
				<span>Select Operator</span>
				<hr>
				<table border="0" cellspacing="0" cellpadding="8">
					<tbody><tr>
					   <td>Available<br><br>
					    <select size="10" name="excel_answer_call_by_agent_info" multiple="multiple" id="myform_List_Agent_from" style="height: 100px;width: 173px;">
							 <?php 
							   $rResult = mysql_query("SELECT persons.`name`
                                                       FROM  users
                                                       LEFT JOIN persons ON persons.user_id = users.id
                                                       AND users.actived = 1");
							    while ( $aRow = mysql_fetch_array( $rResult ) )
							    {
							    	echo '<option value="'.$aRow[0].'">'.$aRow[0].'</option>';
							    	
							    }
							    
							    	?>
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
			<div id="rest" style="margin-top: 200px; width: 100%; float:none;">
				<h2>Select date</h2>
				<hr>
				<div id="button_area">
	            	<div class="left" style="width: 170px;">
	            		<label for="search_start" class="left" style="margin: 6px 0 0 9px; font-size: 12px;">Start Date</label>
	            		<input type="text" name="search_start" value="" id="start_time" class="inpt right" style="width: 95px; height: 16px;"/>
	            	</div>
	            	<div class="right" style="width: 170px;">
	            		<label for="search_end" class="left" style="margin: 6px 0 0 9px; font-size: 12px;">End Date</label>
	            		<input type="text" name="search_end" id="end_time" value="" class="inpt right" style="width: 95px; height: 16px;"/>
            		</div>	
            	</div>
            	
            		<input style="margin-left: 15px;" id="show_report" name="show_report" type="submit" value="Show Report">
         <div style="margin-top: 5px; position: absolute; right: 40px; display:none;"><button class="excel_answer_call_by_agent_info">Excel</button></div>   	
		<table width="99%" cellpadding="3" cellspacing="3" border="0" class="sortable" id="table1">
		<caption style="background: #fff;">Answered Calls by Operators</caption>
            <thead>
            <tr>
                  <th><a  class="sortheader">Oper<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
                  <th><a  class="sortheader">Reg. hours<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
                  <th><a  class="sortheader">Calls<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
                  <th><a  class="sortheader">Treated<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
                  <th><a  class="sortheader">Untreated<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
                  <th><a  class="sortheader">% Answ.<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
                  <th><a  class="sortheader">Duratation<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
                  <th><a  class="sortheader">AVG Duratation<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
                  <th><a  class="sortheader">Max Duration<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
                  <th><a  class="sortheader">Min duration<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
                  <th><a  class="sortheader">Coefficient<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
                  <th><a  class="sortheader">Task<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
                  <th><a  class="sortheader">Out Call<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
            </tr>
             <tr id="technik_info">
                    <td></td>
                    <td></td>
                    <td id="answear_dialog" style="cursor: pointer; text-decoration: underline;"></td>
                    <td></td>
                    <td ></td>
                    <td id="undone_dialog" style="cursor: pointer; text-decoration: underline;"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <!--<td id="answear_dialog1" style="cursor: pointer; text-decoration: underline;"></td>
                    <td ></td>
                    <td id="undone_dialog1" style="cursor: pointer; text-decoration: underline;"></td>-->
                </tr>
            </thead>
            <tbody id="answer_call_by_queue">
                
			</tbody>
			
        </table>
        
        <br>
         <div id="chart_container" style="float:left; width: 90%; height: 300px; margin-left: 68px;"></div>
      <br>
	</div>
	</div>
<!-- jQuery Dialog -->
<div id="add-edit-form" class="form-dialog" title="Answered Call">
<div id="test"></div>
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form-undone" class="form-dialog" title="Untreated Call">
<!-- aJax -->
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form1" class="form-dialog" title="გამავალი ზარები">
<!-- aJax -->
</div>
<!-- jQuery Dialog -->
<div id="add-edit-form-undone1" class="form-dialog" title="დაუმუშავებელი გამავალი ზარები">
<div id="test1"></div>
<!-- jQuery Dialog -->
	<div id="audio_dialog" title="Record">
	</div>
</div>
</body>