<html>
<head>
<script src="js/highcharts.js"></script>
<!--script src="http://code.highcharts.com/stock/highstock.js"></script-->
<script src="js/exporting.js"></script>
<script type="text/javascript">

var aJaxURL	= "server-side/report/slevel_report.action.php";		//server side folder url
var tName   = "report";

$(document).ready(function() {
	$("#show_report").button();
	GetDate("search_start");
	GetDate("search_end");
	var start	= $("#search_start").val();
	var end		= $("#search_end").val();
	var number		= $("#number").val();
	var procent		= $("#procent").val();
	var users       = $("#users").val();
	getchart(start,end,number,procent,users);
	//getchart5(start,end,number,procent,users);
});


function getchart(start,end,number,procent,users) {
	var options = {
			chart: {
	            renderTo: 'chart_container',
	            zoomType: 'xy'
	        },
        title: {
            text: 'SL By days'
        },
        subtitle: {
            text: ''
        },
        xAxis: [{
            categories: [],
            crosshair: true,
            labels: {
                rotation: -45,
                align: 'right',
                
            }
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}%',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            opposite: true

        }, { // Secondary yAxis
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value} Call',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            }

        }, { // Tertiary yAxis
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            labels: {
                format: '{value} Call',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 80,
            verticalAlign: 'top',
            y: 55,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: []
    }


    $.getJSON("server-side/report/slevel_report.action.php?done=1&start="+ start + "&end=" + end + "&number=" + number + "&procent=" + procent + "&users=" + users, function(json) {
    	options.xAxis[0].categories = json[0]['date'];
    	options.tooltip.valueSuffix = json[0]['unit'];   

    	$("#all_answer").val(json[0]['all_answer']);	
    	$("#all_procent").val(json[0]['all_procent']+'%');
    	
    	options.series[2] = {};
    	options.series[2].color = "green";
    	options.series[2].name = json[0]['name_answer'];
    	options.series[2].data = json[0]['percent_answer'];
    	options.series[2].type = "spline";
    	
    	options.series[3] = {};
    	options.series[3].color = "yellow";
    	options.series[3].name = 'SL-Planned';
    	options.series[3].data = json[0]['limit_percent'];    	
    	options.series[3].type = "spline";
    	
    	options.series[0] = {};
    	options.series[0].color = "#7CB5EC";
    	options.series[0].name = 'Answered Call';
    	options.series[0].data = json[0]['count_unanswer'];
    	options.series[0].type = "column";
    	options.series[0].yAxis= 1;
    	options.series[0].tooltip= {valueSuffix: ' Call'};
    	
    	options.series[1] = {};
    	options.series[1].color = "red";
    	options.series[1].name = 'Unanswered Call';
    	options.series[1].data = json[0]['unanswer'];
    	options.series[1].type = "column";
    	options.series[1].yAxis= 1;
    	options.series[1].tooltip= {valueSuffix: ' Call'};
    	
        chart = new Highcharts.Chart(options);
        $(".highcharts-axis-labels").on("click", "tspan", function() {
        	getoherchart($(this).text(),number,procent,users);
        	getoherchart1($(this).text(),number,procent,users);
        });
    });
}

function getoherchart(date,number,procent,users){

	var options = {
			chart: {
	            renderTo: 'chart_container1',
	            zoomType: 'xy'
	        },
        title: {
            text: 'SL By hours ' + date
        },
        subtitle: {
            text: ''
        },
        xAxis: [{
            categories: [],
            crosshair: true,
            labels: {
                rotation: -45,
                align: 'right',
                
            }
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}%',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            opposite: true

        }, { // Secondary yAxis
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value} Call',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            }

        }, { // Tertiary yAxis
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            labels: {
                format: '{value} Call',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 80,
            verticalAlign: 'top',
            y: 55,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: []
    }

	    $.getJSON("server-side/report/slevel_report.action.php?done=2&day=" + date + "&number=" + number + "&procent=" +procent + "&users=" + users, function(json) {
	    	options.xAxis[0].categories = json[0]['date'];
	    	options.tooltip.valueSuffix = json[0]['unit'];

	    	//console.log(json[0]['count_unanswer']);
	    	options.series[0] = {};
	    	options.series[0].color = "#7CB5EC";
	    	options.series[0].name = 'Answered Call';
	    	options.series[0].data = json[0]['count_answer'];
	    	options.series[0].type = "column";
	    	options.series[0].yAxis= 1;
	    	options.series[0].tooltip= {valueSuffix: ' Call'};
	    	
	    	options.series[2] = {};
	    	options.series[2].color = "green";
	    	options.series[2].name = json[0]['name_answer'];
	    	options.series[2].data = json[0]['percent_answer'];
	    	options.series[2].type = "spline";
	    	
	    	options.series[1] = {};
	    	options.series[1].color = "red";
	    	options.series[1].name = 'Unanswered Call';
	    	options.series[1].data = json[0]['unhour'];
	    	options.series[1].type = "column";
	    	options.series[1].yAxis= 1;
	    	options.series[1].tooltip= {valueSuffix: ' Call'};

	    	options.series[3] = {};
	    	options.series[3].color = "yellow";
	    	options.series[3].name = 'SL-Planned';
	    	options.series[3].data = json[0]['limit_percent'];
	    	options.series[3].type = "spline";
	        chart = new Highcharts.Chart(options);
	    });
}

function getoherchart1(date,number,procent,users){
	var options = {
			chart: {
	            renderTo: 'chart_container2',
	            zoomType: 'xy'
	        },
        title: {
            text: 'SL By hours ' + date
        },
        subtitle: {
            text: ''
        },
        xAxis: [{
            categories: [],
            crosshair: true,
            labels: {
                rotation: -45,
                align: 'right',
                
            }
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}%',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            opposite: true

        }, { // Secondary yAxis
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value} Call',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            }

        }, { // Tertiary yAxis
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            labels: {
                format: '{value} Call',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 80,
            verticalAlign: 'top',
            y: 55,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: []
    }

	    $.getJSON("server-side/report/slevel_report.action.php?done=3&day=" + date + "&number=" + number + "&procent=" +procent + "&users=" + users, function(json) {
	    	options.xAxis[0].categories = json[0]['date'];
	    	options.tooltip.valueSuffix = json[0]['unit'];

	    	options.series[0] = {};
	    	options.series[0].color = "#7CB5EC";
	    	options.series[0].name = 'Answered Call';
	    	options.series[0].data = json[0]['count_answer'];
	    	options.series[0].type = "column";
	    	options.series[0].yAxis= 1;
	    	options.series[0].tooltip= {valueSuffix: ' Call'};
	    	
	    	options.series[3] = {};
	    	options.series[3].color = "green";
	    	options.series[3].name = json[0]['name_unanswer'];
	    	options.series[3].data = json[0]['percent_unanswer'];
	    	options.series[3].type = "spline";

	    	options.series[1] = {};
	    	options.series[1].color = "red";
	    	options.series[1].name = 'Unanswered Call';
	    	options.series[1].data = json[0]['unmin'];
	    	options.series[1].type = "column";
	    	options.series[1].yAxis= 1;
	    	options.series[1].tooltip= {valueSuffix: ' Call'};

	    	options.series[2] = {};
	    	options.series[2].color = "yellow";
	    	options.series[2].name = 'SL-Planned';
	    	options.series[2].data = json[0]['limit_percent'];
	    	options.series[2].type = "spline";
	        chart = new Highcharts.Chart(options);
	    });
}


$(document).on("change", "#search_start", function () 	{
	var start	= $("#search_start").val();
	var end		= $("#search_end").val();
	var number		= $("#number").val();
	var procent		= $("#procent").val();
	var users       = $("#users").val();
	getchart(start,end,number,procent,users);
});
$(document).on("change", "#search_end"  , function () 	{
	var start	= $("#search_start").val();
	var end		= $("#search_end").val();
	var number		= $("#number").val();
	var procent		= $("#procent").val();
	var users       = $("#users").val();
	getchart(start,end,number,procent,users);
});
$(document).on("click", "#show_report"  , function () 	{
	var start	= $("#search_start").val();
	var end		= $("#search_end").val();
	var number		= $("#number").val();
	var procent		= $("#procent").val();
	var users       = $("#users").val();
	getchart(start,end,number,procent,users);
});


</script>
<style type="text/css">

.highcharts-button{
	float: left;
}
</style>
</head>
<body>
<div id="tabs" style="    width: 100%;    height: 100%;    background: #FFF;    padding: 15px;    margin-bottom: 40px;">
  <div style="width: 75%;margin: auto;margin-top: 25px;">   	 
   	 <input id="search_start" type="text" style="width: 75px;float: left;">
   	 <label style="display: block;float: left;padding-top: 2px;">-From</label>
   	 
   	 <input id="search_end" type="text" style="width: 75px;margin-left: 10px;float: left;">
   	 <label style="display: block;float: left;padding-top: 2px;">-Up to</label>
   	 
   	 <select id="users" style="margin-left: 15px">   	 
       	 <option value="0">All</option>
       	 <?php 
       	 include '../../includes/classes/core.php';
       	        $res = mysql_query("SELECT  user_info.`name`,
       	                                    extension_id
       	                            FROM users
       	                            JOIN user_info ON users.id = user_info.user_id
       	                            WHERE actived = 1");
       	        while ($req = mysql_fetch_array($res)){
       	            echo "<option value=\"$req[1]\">$req[0]</option>";
       	        }
       	 ?>
   	 </select>
   	 <br><br><br>
   	 
   	 <label style="display: block;margin-right: 10px;float: left;padding-top: 4px;">Call</label>
   	 <input id="procent" type="text" value="85" style="float: left;width: 50px;margin-left: 5px;">
   	 
   	 
   	 
   	 <label style="display: block;margin-left: 5px;float: left;padding-top: 4px;"> % Answered be </label>
   	 <input id="number" type="text" value="45" style="float: left;width: 50px;">
   	 <label style="display: block;margin-left: 2px;float: left;padding-top: 4px; margin-right: 10px;"> Seconds</label>
   	 
   	 <button id="show_report">Show report</button>
   	 <br>
   	 <label style="display: block;margin-right: 10px;float: left;padding-top: 4px;">All Answered</label>
   	 <input type="text" id="all_answer" style="float: left;width: 50px;" disabled>
   	 <label style="display: block;margin-right: 10px;margin-left: 10px;float: left;padding-top: 4px;">All Percent</label>
   	 <input type="text" id="all_procent" style="float: left;width: 50px;" disabled>
   	 <div id="chart_container" style="height: 400px; min-width: 310px; margin-top:10px;"></div><br>
   	 <div id="chart_container1" style="height: 400px; min-width: 310px; margin-top:10px;"></div>
   	 <div id="chart_container2" style="height: 400px; min-width: 310px; margin-top:10px;"></div>
  </div>
</body>
</html>
