<html>
<head>
<link rel="stylesheet" href="media/css/bootstrap.min.css">
<script src="js/bootstrap.min.js"></script>
<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>

<style type="text/css">
.box_main{
	overflow: hidden;
	width: 250px;
	float: left;
    margin-right: 25px;
}
.box_head{
	width: 100%;
	background: #333333;
	font-family: pvn;
    font-weight: bold;
	color: #FFF;
	padding: 5px;
}
.box_body{
	width: 250px;
	border: 1px solid #E8E8E8;
	background: #FFF;
	height: 160px;
}
#box_content{
	width: 100%;
	margin-top: 17px;
}
.gray_blue{
	background: #EEEEEE;
	color: #2681DC;
}
.box_tab_table{
	width: 100%;
}
.box_tab_table td{
	width: 50%;
	padding: 5px;
	cursor: pointer;
}
.circle
{
	float: left;
    width:30px;
    height:30px;
    border-radius:50%;
    font-size:11px;
    color: #fff;
    line-height: 30px;
    text-align: center;
}
.circle_yellow{
	background: #EFB838;
}
.circle_green{
	background: #50AD4B;
}
.circle_blue{
	background: #2F7ED8;
}
.circle_red{
	background: #AD2C2C;
}
.box_tab_table_content{
	margin-top: 10px;
	width: 100%;
}
.box_tab_table_content td{
	padding: 0 5px 0 5px;
	width: 50%;
}
.box_tab_table_content_text{
	float: left;
	margin-top: 10px;
    margin-left: 5px;
	width: 70px;
}
.bottom_margin_10{
	margin-bottom: 10px;
}
.top_margin_25{
	margin-top: 25px;
}
.now_res{
	text-align: center;
    font-size: 12px;
    font-weight: bold;
	margin-top: 45px;
}
.now_res span {
    font-size: 24px;
    margin-left: -4px;
}
#sl_head{
	width: 165px;
    height: 150px;
    border-radius: 50%;
    border: 5px solid #EEE;
    margin: auto;
    margin-top: 5px;
}
#sl{
	width: 55px;
    margin: auto;
    margin-top: 32px;
    font-size: 30px;
    color: #AD2C2C;
    font-weight: bold;
}
#sls{
    width: 40px;
    margin: auto;
    font-size: 18px;
    color: #222;
    font-weight: bold;
}
.phone_red{
	margin-left: 5px;
	float: left;
	width: 24px;
	height: 24px;
	background: url("media/images/phone_red.png");
}
.phone_yellow{
	margin-left: 5px;
	float: left;
	width: 24px;
	height: 24px;
	background: url("media/images/phone_yellow.png");
}
.phone_progres{
	width: 100%;
}
.phone_progres div{
	height: 24px;
	width: 195px;
    margin-left: 34px;
    border-radius: 4px;
	border: 1px solid #DEDEDE;
	overflow: hidden;
}
.red{
	color: #fff;
	font-size: 16px;
    font-weight: bold;
    text-align: center;
	height: 23px !important;
    margin-left: 0px !important;
	background: #AD2C2C;
}
.yellow{
	color: #fff;
	font-size: 16px;
    font-weight: bold;
    text-align: center;
	height: 23px !important;
    margin-left: 0px !important;
	background: #EFB838
}
.phone_box_hint{
	width: 94%;
	border: 1px solid #9F9F9F;
	height: 25px;
	margin: auto;
	margin-top: 22px;
	border-radius: 6px;

}
.phone_content_box{
	float: left;
	width: 50%;
    height: 100%;
}
.color_box{
    height: 12px;
    width: 16px;
    float: left;
    margin-top: 6px;
    margin-left: 7px;
	border-radius: 3px;
}
.name_box{
	cursor: pointer;
	margin-top: 4px;
    margin-left: 30px;
    font-size: 12px;
}
.content_duration{

}
.total_duration{
	width: 78px;
    margin: auto;
    margin-top: 24px;
    font-size: 30px;
    color: #2F7ED8;
    font-weight: bold;
	margin-bottom: 20px;
}
.min_duration{
	width: 50%;
	float: left;
	font-size: 24px;
    text-align: center;
}
.max_duration{
	width: 50%;
	float: left;
	font-size: 24px;
    text-align: center;
}
.min_icon{
	margin-top: 3px;
	margin-left: 25px;
	float: left;
	width: 24px;
	height: 24px;
	background: url("media/images/min.png");
}
.max_icon{
	margin-top: 3px;
	margin-left: 15px;
	float: left;
	width: 24px;
	height: 24px;
	background: url("media/images/max.png");
}
.min_value{
	float: left;
    margin-left: 5px;
}
.max_value{
	float: left;
    margin-left: 5px;
}
.mini_boxs{
	float: left;
	width: 840px;
}
.table_boxs{
	float: left;
	width: 27%;
}
.table {
  margin: 0 0 40px 0;
  width: 100%;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
  display: table;
}
@media screen and (max-width: 580px) {
  .table {
    display: block;
  }
}

.row {
  display: table-row;
  background: #f6f6f6;
}
.row:nth-of-type(odd) {
  background: #e9e9e9;
}
.row.header {
	font-size: 12px;
    font-weight: 900;
    color: #333;
    background: #FFF;
    font-family: pvn;
}
.row.green {
  background: #27ae60;
}
.row.blue {
  background: #2980b9;
}
@media screen and (max-width: 580px) {
  .row {
    padding: 8px 0;
    display: block;
  }
}
.align_right{
	text-align: left;
}
.cell {
	font-size: 10px;
    padding: 3px;
    display: table-cell;
	vertical-align: middle;
}
@media screen and (max-width: 580px) {
  .cell {
    padding: 2px 2px;
    display: block;
  }
}
</style>
<script type="text/javascript">
$(document).ready(function () {
	news();
	task();
	incomming_call();
	outgoing_call();
	inner_call();
	answer_unanswer();
	sl();
	space();
	live_operators();
	live_calls();
	operator_answer();
	operator_answer_dur();
	hold_avg_time();
	asa();
});

$(document).on("click", ".box_tab_table td", function () {
    if($(this).hasClass('gray_blue') == true){
    	$(this).removeClass();
    	$($(this).siblings('td')[0]).addClass('gray_blue');
    }
});

function news(){
	$.ajax({
        url: "server-side/main.action.php",
        data: 'act=news',
        success: function(data) {
            $("#news_now").html(data.news.news_now);
            $("#news_arch").html(data.news.news_arch);
        }
    });
}

function task(){
	$.ajax({
        url: "server-side/main.action.php",
        data: 'act=task',
        success: function(data) {
            $("#task_new").html(data.task.task_new);
            $("#task_proces").html(data.task.task_proces);
            $("#task_done").html(data.task.task_done);
            $("#task_delete").html(data.task.task_delete);
        }
    });
}

function incomming_call(){
	$.ajax({
        url: "server-side/main.action.php",
        data: 'act=incomming_call',
        success: function(data) {
        	var last_array = $(data.incomming_call_day[1].data).size();
        	$("#incomming_call_today").html(data.incomming_call_day[1].data[(last_array-1)]);
        	$('#incomming_call').highcharts({
    	        chart: {
    	            type: 'line'
    	        },
    	        title: {
    	            text: ''
    	        },
    	        subtitle: {
    	            text: ''
    	        },
    	        xAxis: {
    	        	categories: data.incomming_call_day_date,
    	        	opposite: false,
    	        	visible: false,
    	        	labels: {
    	        		enabled: false
    	        	}
    	        },
    	        yAxis: {
    	            title: {
    	                text: ''
    	            },
    	            opposite: false,
    	        	visible: false,
    	        	labels: {
    	        		enabled: false
    	        	}
    	        },
    	        tooltip: {
                    pointFormat: 'Call: <b>{point.y:.0f}</b>'
                },
    	        plotOptions: {
    	            line: {
    	            	color: '#50AD4B',
    	                dataLabels: {
    	                    enabled: false
    	                },
    	                enableMouseTracking: true
    	            }
    	        },
    	        series: data.incomming_call_day,
    	        legend:{
    	        	enabled : false
    	        },
    	        exporting :{
    	        	enabled : false
    	        }
    	    });
        }
    });
}

function outgoing_call(){
	$.ajax({
        url: "server-side/main.action.php",
        data: 'act=outgoing_call',
        success: function(data) {
//             var last_array = $(data.outgoing_call_day[1].data).size();
//         	$("#outgoing_call_day").html(data.outgoing_call_day[1].data[(last_array-1)]);
        	$('#outgoing_call').highcharts({
    	        chart: {
    	            type: 'line'
    	        },
    	        title: {
    	            text: ''
    	        },
    	        subtitle: {
    	            text: ''
    	        },
    	        xAxis: {
    	        	opposite: false,
    	        	visible: false,
    	        	labels: {
    	        		enabled: false
    	        	}
    	        },
    	        yAxis: {
    	            title: {
    	                text: ''
    	            },
    	            opposite: false,
    	        	visible: false,
    	        	labels: {
    	        		enabled: false
    	        	}
    	        },
    	        tooltip: {
                    pointFormat: 'Call: <b>{point.y:.0f}</b>'
                },
    	        plotOptions: {
    	            line: {
    	            	color: '#EFB838',
    	                dataLabels: {
    	                    enabled: false
    	                },
    	                enableMouseTracking: true
    	            }
    	        },
    	        series: data.outgoing_call_day,
    	        legend:{
    	        	enabled : false
    	        },
    	        exporting :{
    	        	enabled : false
    	        }
    	    });
        }
    });
}

function inner_call(){
	$.ajax({
        url: "server-side/main.action.php",
        data: 'act=inner_call',
        success: function(data) {
            var last_array = $(data.inner_call_day[1].data).size();
        	$("#inner_call_day").html(data.inner_call_day[1].data[(last_array-1)]);
        	$('#inner_call').highcharts({
    	        chart: {
    	            type: 'line'
    	        },
    	        title: {
    	            text: ''
    	        },
    	        subtitle: {
    	            text: ''
    	        },
    	        xAxis: {
    	        	opposite: false,
    	        	visible: false,
    	        	labels: {
    	        		enabled: false
    	        	}
    	        },
    	        yAxis: {
    	            title: {
    	                text: ''
    	            },
    	            opposite: false,
    	        	visible: false,
    	        	labels: {
    	        		enabled: false
    	        	}
    	        },
    	        tooltip: {
                    pointFormat: 'Call: <b>{point.y:.0f}</b>'
                },
    	        plotOptions: {
    	            line: {
    	            	color: '#AD2C2C',
    	                dataLabels: {
    	                    enabled: false
    	                },
    	                enableMouseTracking: true
    	            }
    	        },
    	        series: data.inner_call_day,
    	        legend:{
    	        	enabled : false
    	        },
    	        exporting :{
    	        	enabled : false
    	        }
    	    });
        }
    });
}

function answer_unanswer(){
	$.ajax({
        url: "server-side/main.action.php",
        data: 'act=answer_unanswer',
        success: function(data) {
        	$("#answer").html(data.answer_unanswer_today.ans);
        	$("#unanswer").html(data.answer_unanswer_today.unans);
        	$('#answer_unanswer').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
    	                text: ''
    	            },
    	            opposite: false,
    	        	visible: false,
    	        	labels: {
    	        		enabled: false
    	        	}
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: 'Call: <b>{point.y:.0f}</b>'
                },
                series: data.answer_unanswer,
                plotOptions: {
                    column: {
                    	colorByPoint: true,
                      colors: ['#50AD4B', '#AD2C2C'],
                    }
                },
    	        exporting :{
    	        	enabled : false
    	        }
            });
        }
    });
}

function sl(){
	$.ajax({
        url: "server-side/main.action.php",
        data: 'act=sl',
        success: function(data) {
        	str = parseInt(data.sl.percent);
            str1 = parseInt(data.sl.sl_procent);
            if(str < str1){
            	$("#sl").css("color","#AD2C2C");
            	$("#sl").html(data.sl.percent+'%');
            	$("#sls").html('in'+' ' +data.sl.min+'sec');
            }else{
            	$("#sl").css("color","#50AD4B");
            	$("#sl").html(data.sl.percent+'%');
            	$("#sls").html('in' + ' ' +data.sl.min+ 'sec');
            }
        }
	});
}

function space(){
	$.ajax({
        url: "server-side/main.action.php",
        data: 'act=free_space_on_disk',
        success: function(data) {
        	$('#space').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: 0,
                    plotShadow: false
                },
                colors: ['#AD2C2C', '#50AD4B'],
                title: {
                    text: 'Full<br>Space<br>'+data.space.total_space+'GB',
                    align: 'center',
                    verticalAlign: 'middle',
                    style: { "color": "#000", "fontSize": "18px", "fontWeight": "bold" },
                    y: -30,
                    x: 65
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        dataLabels: {
                            enabled: false
                        },
                        showInLegend: true,
                        startAngle: 0,
                        endAngle: 0,
                        size: 120,
                        center: ['30%', '50%']
                    }
                },
                legend: {
                	y: 15
                },
                series: data.space.array_space,
                exporting :{
                	enabled : false
                }
            });
        }
	});
}

function live_operators(){
	$.ajax({
        url: "server-side/main.action.php",
        data: 'act=live_operators',
        success: function(data) {
        	$('#operators').highcharts({
        		chart: {
                    type: 'column',
                    height: 166
                },
                colors: ['#50AD4B', '#EFB838', '#AD2C2C'],
                title: {
                    text: ' '
                },
                
                xAxis: {
                    categories: [
                        'Agents'
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: ''
                    },
                    opposite: false,
                	visible: false,
                	labels: {
                		enabled: false
                	}
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                exporting :{
                	enabled : false
                },
                credits: {
                    enabled: false
                },
                series: data.live_operators
            });
        }
	});
}

function live_calls(){
	$.ajax({
        url: "server-side/main.action.php",
        data: 'act=live_calls',
        success: function(data) {
            $('.phone_progres .red').html(data.live_calls.in_talk);
            $('.phone_progres .yellow').html(data.live_calls.in_queue);
            $('.phone_progres .red').css('width',(parseInt(data.live_calls.in_talk)+10)+'%');
            $('.phone_progres .yellow').css('width',(parseInt(data.live_calls.in_queue)+10)+'%');
        }
	});
}

function operator_answer(){
	$.ajax({
        url: "server-side/main.action.php",
        data: 'act=operator_answer',
        success: function(data) {
            $('#operator_answer_content').html(data.operator_answer);
        }
	});
}

function operator_answer_dur(){
	$.ajax({
        url: "server-side/main.action.php",
        data: 'act=operator_answer_dur',
        success: function(data) {
            $('#operator_answer_dur').html(data.operator_answer_dur);
        }
	});
}

function hold_avg_time(){
	$.ajax({
        url: "server-side/main.action.php",
        data: 'act=hold_avg_time',
        success: function(data) {
            $('#duration .total_duration').html(data.hold_avg_time.wait_time_avg);
            $('#duration .min_value').html(data.hold_avg_time.wait_time_min);
            $('#duration .max_value').html(data.hold_avg_time.wait_time_max);
        }
	});
}

function asa(){
	$.ajax({
        url: "server-side/main.action.php",
        data: 'act=asa',
        success: function(data) {
            $('#asa .total_duration').html(data.asa.wait_time_avg);
            $('#asa .min_value').html(data.asa.wait_time_min);
            $('#asa .max_value').html(data.asa.wait_time_max);
        }
	});
}
function go_sl(){
	if(location.hostname == '37.143.152.21'){
		linkurl = '37.143.152.21';
	}else{
		linkurl = '192.168.0.33';
	}
	window.location="http://"+linkurl+":8181/index.php?pg=52";
}
</script>
</head>
<body onselectstart='return false;'>
<div id="tabs" style="width: 98%;margin-bottom: 60px;height: 950px;">
<div class="callapp_head">Home<hr class="callapp_head_hr"></div>
    <div id="box_content">
        <div class="mini_boxs">
        <div class="box_main">
            <div class="box_head">Agents LIVE</div>
            <div class="box_body">
                <div>
                     <div id="operators" style="min-width: 250px; max-width: 250px; height: 150px;margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        
        <div class="box_main">
            <div class="box_head">Incoming Calls</div>
            <div class="box_body">
                <div>
                    <table class="box_tab_table_content">
                        <tr>
                            <td>
                                <div id="incomming_call" style="width: 180px; height: 145px; margin: 0 auto"></div>
                            </td>
                            <td>
                                <div class="now_res"><span id="incomming_call_today">0</span><br> Today</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="box_main">
            <div class="box_head">Answered / Unanswered</div>
            <div class="box_body">
                <div>
                    <table class="box_tab_table_content">
                        <tr>
                            <td>
                                <div id="answer_unanswer" style="width: 160px; height: 146px; margin: 0 auto"></div>
                            </td>
                            <td>
                                <div class="now_res" style="margin-top: 20px;"><span id="answer">0</span><br> Answered</div>
                                <div class="now_res" style="margin-top: 13px;"><span id="unanswer">0</span><br> Unanswered</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="box_main top_margin_25">
            <div class="box_head">Calls LIVE</div>
            <div class="box_body">
                <div>
                    <table class="box_tab_table_content" style="margin-top: 35px;">
                        <tr>
                            <td style="padding-bottom: 20px;">
                                <div class="phone_red"></div>
                                <div class="phone_progres"><div> <div class="red">0</div> </div></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="phone_yellow"></div>
                                <div class="phone_progres"><div> <div class="yellow">0</div> </div></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="phone_box_hint"><div class="phone_content_box"> <div class="color_box circle_red"></div><div class="name_box">Speaks</div> </div><div class="phone_content_box"> <div class="color_box circle_yellow"></div><div class="name_box">Queued</div> </div></div>
                            </td>
                        </tr>
                    </table>
                    
                </div>
            </div>
        </div>
        
        <div class="box_main top_margin_25">
            <div class="box_head">Outgoing calls</div>
            <div class="box_body">
                <div>
                    <table class="box_tab_table_content">
                        <tr>
                            <td>
                                <div id="outgoing_call" style="width: 180px; height: 145px; margin: 0 auto"></div>
                            </td>
                            <td>
                                <div class="now_res"><span id="outgoing_call_day">0</span><br> Today</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="box_main top_margin_25">
            <div class="box_head">ASA</div>
            <div class="box_body">
                <div id="asa">
                    <div class="content_duration"><div class="total_duration">00:00</div><div class="min_duration"><div class="min_icon"></div><div class="min_value">00:00</div></div><div class="max_duration"><div class="max_icon"></div><div class="max_value">00:00</div></div> </div>
                </div>
            </div>
        </div>
        
        <div class="box_main top_margin_25">
            <div class="box_head">News</div>
            <div class="box_body">
                <div>
                    <table class="box_tab_table">
                        <tr><td>Personal</td><!--  td class="gray_blue">სულ</td--></tr>
                    </table>
                    <table class="box_tab_table_content">
                        <tr>
                            <td>
                                <div class="circle circle_yellow" id="news_now">0</div>
                                <div class="box_tab_table_content_text">Unread</div>
                            </td>
                            <td>
                                <div class="circle circle_blue" id="news_arch">0</div>
                                <div class="box_tab_table_content_text">Archive</div>
                            </td>
                        </tr>
                    </table>
                    <table class="box_tab_table_content bottom_margin_10">
                        <tr>
                            <td>
                                <div class="circle circle_green">0</div>
                                <div class="box_tab_table_content_text">Read</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="box_main top_margin_25">
            <div class="box_head">Internal Calls</div>
            <div class="box_body">
                <div>
                    <table class="box_tab_table_content">
                        <tr>
                            <td>
                                <div id="inner_call" style="width: 180px; height: 145px; margin: 0 auto"></div>
                            </td>
                            <td>
                                <div class="now_res"><span id="inner_call_day">0</span><br> Today</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="box_main top_margin_25">
            <div class="box_head">AVG waiting time before hangup</div>
            <div class="box_body">
                <div id="duration">
                    <div class="content_duration"><div class="total_duration">00:00</div><div class="min_duration"><div class="min_icon"></div><div class="min_value">00:00</div></div><div class="max_duration"><div class="max_icon"></div><div class="max_value">00:00</div></div> </div>
                </div>
            </div>
        </div>
        
        <div class="box_main top_margin_25">
            <div class="box_head">Tasks</div>
            <div class="box_body">
                <div>
                    <table class="box_tab_table">
                        <tr><td>Personal</td><td class="gray_blue">All</td></tr>
                    </table>
                    <table class="box_tab_table_content">
                        <tr>
                            <td>
                                <div class="circle circle_yellow" id="task_new">0</div>
                                <div class="box_tab_table_content_text">New</div>
                            </td>
                            <td>
                                <div class="circle circle_blue" id="task_done">0</div>
                                <div class="box_tab_table_content_text">Resolved</div>
                            </td>
                        </tr>
                    </table>
                    <table class="box_tab_table_content bottom_margin_10">
                        <tr>
                            <td>
                                <div class="circle circle_green" id="task_proces">0</div>
                                <div class="box_tab_table_content_text" style="margin-top: 4px;">In Progres</div>
                            </td>
                            <td>
                                <div class="circle circle_red" id="task_delete">0</div>
                                <div class="box_tab_table_content_text">Cancelled</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="box_main top_margin_25" onclick="go_sl()">
            <div class="box_head">SL</div>
            <div class="box_body">
                <div>
                    <div id="sl_head">
                        <div id="sl">0%</div>
                        <div id="sls" style="width: 72px">0sec</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="box_main top_margin_25">
            <div class="box_head">Free hard disk space</div>
            <div class="box_body">
                <div>
                    <div id="space" style="min-width: 250px; height: 150px; max-width: 250px;margin: 0 auto"></div>
                </div>
            </div>
        </div>
        
        </div>
        
        <div class="table_boxs">
             <div class="table" id="operator_answer_content"></div>
             <div class="table" id="operator_answer_dur"></div>
        </div>
    
    </div>

</body>
</html>
