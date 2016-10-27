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
		div, caption, td, th, h2, h3, h4 {
			font-size: 12px;
			font-family: verdana,sans-serif;
			voice-family: "\"}\"";
			voice-family: inherit;
			color: #333;
		}
		tbody {
			display: table-row-group;
			vertical-align: middle;
			border-color: inherit;
		}
		tbody tr {
			background: #dfedf3;
			font-size: 110%;
		}
		tr {
			display: table-row;
			vertical-align: inherit;
			border-color: inherit;
		}
		tbody tr th, tbody tr td {
			padding: 5px;
			border: solid 1px #326e87;
			text-align: center;
			vertical-align:middle;
		}
		thead tr th {
			height: 32px;
			aline-height: 32px;
			text-align: center;
			vertical-align:middle;
			color: #1c5d79;
			background: #CBDFEE;
			border-left: solid 1px #FF9900;
			border-right: solid 1px #FF9900;
			border-collapse: collapse;
		}
		table.sortable a.sortheader {
			text-decoration: none;
			display: block;
			color: #1c5d79;
			xcolor: #000000;
			font-weight: bold;
		}
		a{
			cursor: pointer;
		}
		.tdstyle{
			text-align: left;
			vertical-align:middle;
		}
		
.download {

	
	background-color:#4997ab;
	border-radius:2px;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:arial;
	font-size:14px;
	border:0px;
	text-decoration:none;
	text-overflow: ellipsis;
	width: 78px;
	
}
#logout1{
	float: right;
}
.download1 {

	background-color:#71b251;
	border-radius:2px;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:arial;
	font-size:14px;
	border:0px;
	text-decoration:none;
	text-overflow: ellipsis;
	width: 78px;
}
.download2 {

	background-color:#ce8a14;
	border-radius:2px;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:arial;
	font-size:14px;
	border:0px;
	text-decoration:none;
	text-overflow: ellipsis;
	width: 78px;
	
}
.download4 {

	
	background-color:#a07ab3;
	border-radius:2px;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:arial;
	font-size:14px;
	border:0px;
	text-decoration:none;
	text-overflow: ellipsis;
	width: 78px;
}
.download3 {

	
	background-color:#a07ab3;
	border-radius:2px;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:arial;
	font-size:14px;
	border:0px;
	text-decoration:none;
	text-overflow: ellipsis;
	width: 78px;
	
	
}
    </style>
    <script src="js/highcharts.js"></script>
     <script src="js/exporting.js"></script>
	<script type="text/javascript">
		var aJaxURL		= "server-side/report/user_report.action.php";		//server side folder url
		var aJaxURL1	= "server-side/report/sales_statistics.action.php";		//server side folder url
		var tName		= "example0";										//table name
		var tbName		= "tabs";											//tabs name
		var fName		= "add-edit-form";									//form name
		var file_name 	= '';
		var rand_file 	= '';
		
		$(document).ready(function () {   
			GetTabs(tbName);   	
			GetDateTimes("start_time");
			GetDateTimes("end_time");
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
			            text: 'ნაპასუხები ზარები ოპერატორების მიხედვით',
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
			                text: 'ზარები'
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

			    $.getJSON("server-side/report/operators_rep_new.action.php?start="+start_time + "&end=" + end_time + "&agent=" + agent + "&queuet=" + queuet, function(json) {
				    
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
			paramm		= "server-side/report/user_report.action.php";
			
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
		
		var record;
		function play(record){
			
			link = 'http://'+location.hostname + ":8000/" + record;
			var newWin = window.open(link, 'newWin','width=320,height=200');
            newWin.focus();
            
		}
		
		function LoadDialog(name){
			parame 				= new Object();
			paramm		= "server-side/report/user_report.action.php";
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
					GetDialog("add-edit-form", 1000, "auto", "", "no");
					/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
					GetDataTable("example", aJaxURL, "answear_dialog_table&start_time="+parame.start_time+"&end_time="+parame.end_time+"&queuet="+parame.queuet+"&agent="+parame.agent+"&name="+name,8, "", 0, "", 1, "desc");
					
			    }
		    });
		}
		
		function LoadDialog1(name){
			parame 				= new Object();
			paramm		= "server-side/report/user_report.action.php";
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
					            text: "დახურვა",
					            id: "cancel-dialog",
					            click: function () {
					                $(this).dialog("close");
					            }
					        }
						};
					GetDialog("add-edit-form-undone", 1000, "auto", button);
					/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
					GetDataTable("example2", aJaxURL, "undone_dialog_table&start_time="+parame.start_time+"&end_time="+parame.end_time+"&queuet="+parame.queuet+"&agent="+parame.agent+"&name="+name,8, "", 0, "", 1, "desc");
					
					$( "div" ).removeClass( "ui-widget-overlay" );
			    }
		    });
		}
		function LoadDialog2(name){
			parame 				= new Object();
			paramm		= "server-side/report/user_report.action.php";
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
			paramm		= "server-side/report/user_report.action.php";
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
		$(document).on("click", ".download1", function () {
			var str = 1;
			var link = ($(this).attr("str"));
			link = 'http://212.72.155.175:8181/records/' + link;
			var btn = {
			        "cancel": {
			            text: "დახურვა",
			            id: "cancel-dialog",
			            click: function () {
			                $(this).dialog("close");
			            }
			        }
			    };
			GetDialog_audio("audio_dialog", "auto", "auto",btn);
			
			$("#audio_dialog").html('<audio controls autoplay style="width:500px;"><source src="'+link+'" type="audio/wav"> Your browser does not support the audio element.</audio>');
            $(".download1").css( "background","#71b251" );
            $(this).css( "background","#71b251" );
           
        });
		$(document).on("click", ".download3", function () {
			var str = 1;
			var link = ($(this).attr("str"));
			link = 'http://212.72.155.175:8181/records/' + link;
			var btn = {
			        "cancel": {
			            text: "დახურვა",
			            id: "cancel-dialog",
			            click: function () {
			                $(this).dialog("close");
			            }
			        }
			    };
			GetDialog_audio("audio_dialog", "auto", "auto",btn);
			
			$("#audio_dialog").html('<audio controls autoplay style="width:500px;"><source src="'+link+'" type="audio/wav"> Your browser does not support the audio element.</audio>');
            $(".download3").css( "background","#a07ab3" );
            $(this).css( "background","#a07ab3" );
           
        });
		$(document).on("click", ".download", function () {
			var str = 1;
			var link = ($(this).attr("str"));
			link = 'http://212.72.155.175:8181/records/' + link;
			var btn = {
			        "cancel": {
			            text: "დახურვა",
			            id: "cancel-dialog",
			            click: function () {
			                $(this).dialog("close");
			            }
			        }
			    };
			GetDialog_audio("audio_dialog", "auto", "auto",btn);
		//	alert('hfgj');
			$("#audio_dialog").html('<audio controls autoplay style="width:500px;"><source src="'+link+'" type="audio/wav"> Your browser does not support the audio element.</audio>');
            $(".download").css( "background","#4997ab" );
            $(this).css( "background","#4997ab" );
           
        });
		$(document).on("click", ".download2", function () {
			var str = 1;
			var link = ($(this).attr("str"));
			link = 'http://212.72.155.175:8181/records/' + link;
			var btn = {
			        "cancel": {
			            text: "დახურვა",
			            id: "cancel-dialog",
			            click: function () {
			                $(this).dialog("close");
			            }
			        }
			    };
			GetDialog_audio("audio_dialog", "auto", "auto",btn);
		//	alert('hfgj');
			$("#audio_dialog").html('<audio controls autoplay style="width:500px;"><source src="'+link+'" type="audio/wav"> Your browser does not support the audio element.</audio>');
            $(".download2").css( "background","#ce8a14" );
            $(this).css( "background","#ce8a14" );
           
        });
        
		 function SaveToDisk(fileURL, fileName) {
// 		        // for non-IE
// 		        if (!window.ActiveXObject) {
// 		            var save = document.createElement('a');
// 		            save.href = fileURL;
// 		            save.target = '_blank';
// 		            save.download = fileName || 'unknown';

// 		            var event = document.createEvent('Event');
// 		            event.initEvent('click', true, true);
// 		            save.dispatchEvent(event);
// 		            (window.URL || window.webkitURL).revokeObjectURL(save.href);
// 		        }
// 			     // for IE
// 		        else if ( !! window.ActiveXObject && document.execCommand)     {
// 		            var _window = window.open(fileURL, "_blank");
// 		            _window.document.close();
// 		            _window.document.execCommand('SaveAs', true, fileName || fileURL)
// 		            _window.close();
// 		        }
			 var iframe = document.createElement("iframe"); 
		        iframe.src = fileURL; 
		        iframe.style.display = "none"; 
		        document.body.appendChild(iframe);
		        return false;
		    }
		$(document).on("click", "#name", function () {
			var start_time 	= $('#start_time').val();
			//var start_time = start_timee.substring(0,10);
			var end_time 	= $('#end_time').val();
			//var end_time = end_timee.substring(0,10);
			var name = $(this).text();
			var pathname = window.location.pathname;
			window.open("person_info_new.php?name="+name+"&start_time="+start_time+"&end_time="+end_time, '_blank');
		});
		
    </script>
    
</head>

<body>

<div id="tabs" style="width: 95%;height: 750px;">
<div class="callapp_head">ოპერატორების მიხედვით<hr class="callapp_head_hr"></div>

		
			<div style="width: 30%; float:left;">
			<span>აირჩიე რიგი</span>
			<hr>
			
			    <table border="0" cellspacing="0" cellpadding="8">
					<tbody>
					<tr>
					   	<td>
							ხელმისაწვდომია<br><br>
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
							არჩეული<br><br>
						    <select size="10" name="List_Queue[]" multiple="multiple" style="height: 100px;width: 125px;" id="myform_List_Queue_to">
								
						    </select>
					   </td>
					</tr> 
					</tbody>
				</table>
			</div>
			<div style="width: 30%; float:left; margin-left:20px;">
				<span>აირჩიე ოპერატორი</span>
				<hr>
				<table border="0" cellspacing="0" cellpadding="8">
					<tbody><tr>
					   <td>ხელმისაწვდომია<br><br>
					    <select size="10" name="excel_answer_call_by_agent_info" multiple="multiple" id="myform_List_Agent_from" style="height: 100px;width: 173px;">
							 <?php 
							   $rResult = mysql_query(" SELECT user_info.`name`
                                                        FROM user_info
                                                        JOIN users ON users.id=user_info.user_id
                                                        where users.id!=1 AND users.actived = 1
													  ");
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
						არჩეული<br><br>
					    <select size="10" name="List_Agent[]" multiple="multiple" style="height: 100px;width: 125px;" id="myform_List_Agent_to" >
					
					    </select>
					   </td>
					</tr> 
					</tbody>
				</table>
			</div>
			<div id="rest" style="margin-top: 200px; width: 100%; float:none;">
				<h2>თარიღის ამორჩევა</h2>
				<hr>
				<div id="button_area">
	            	<div class="left" style="width: 180px;">
	            		<label for="search_start" class="left" style="margin: 6px 0 0 9px; font-size: 12px;">დასაწყისი</label>
	            		<input type="text" name="search_start" value="<?php echo date('Y-m-d')." 00:00";?>" id="start_time" class="inpt right" style="width: 95px; height: 16px;"/>
	            	</div>
	            	<div class="right" style="width: 190px;">
	            		<label for="search_end" class="left" style="margin: 6px 0 0 9px; font-size: 12px;">დასასრული</label>
	            		<input type="text" name="search_end" id="end_time" value="<?php echo  date('Y-m-d')." 23:59"; ?>" class="inpt right" style="width: 95px; height: 16px;"/>
            		</div>	
            	</div>
            	
            		<input style="margin-left: 15px;" id="show_report" name="show_report" type="submit" value="რეპორტების ჩვენება">
        <table class="sortable" id="table1">
		<caption>ნაპასუხები ზარები ოპერატორების მიხედვით</caption>
            <thead>
            <tr>
                  <th>ოპერატორი</th>
                  <th>გეგმიური სთ</th>
                  <th>მიღებული ზარები</th>
                  <th>დამუშავებული</th>
                  <th>დაუმუშავებელი</th>
                  <th>% ნაპასუხები</th>
                  <th>ზარის დრო</th>
                  <th>საშუა.ზარის ხანგრძლ.</th>
                  <th>ზარის მაქს.დრო</th>
                  <th>ზარის მინ.დრო</th>
                  <th>დატვირთულობა</th>
                  <th>შესრულებული დავალება</th>
                  <th>გამავალი ზარი</th>
                  <th>დამუშავებული</th>
                  <th>დაუმუშავებელი</th>
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
                    <td id="answear_dialog1" style="cursor: pointer; text-decoration: underline;"></td>
                    <td ></td>
                    <td id="undone_dialog1" style="cursor: pointer; text-decoration: underline;"></td>
                </tr>
            </thead>
            <tbody id="answer_call_by_queue">
                
			</tbody>
			
        </table>
        
        <br>
         <div id="chart_container" style="float:left; width: 90%; height: 300px; margin-left: 68px;"></div>
      <br>
	</div>

<!-- jQuery Dialog -->
<div id="add-edit-form" class="form-dialog" title="ნაპასუხები ზარები">
<div id="test"></div>
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form-undone" class="form-dialog" title="დაუმუშავებელი შემომავავლი ზარები">
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
	<div id="audio_dialog" title="ჩანაწერი">
	</div>
</div>
</body>