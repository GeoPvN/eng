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
#result {
    text-align: right;
    color: gray;
    min-height: 2em;
}
#table-sparkline {
    margin: 0 auto;
    border-collapse: collapse;
}
th {
    font-weight: bold;
    text-align: left;
}
td, th {
    padding: 5px;
    border-bottom: 1px solid silver;
    height: 20px;
}

thead th {
    border-top: 2px solid gray;
    border-bottom: 2px solid gray;
}
.highcharts-tooltip>span {
    background: white;
    border: 1px solid silver;
    border-radius: 3px;
    box-shadow: 1px 1px 2px #888;
    padding: 8px;
}
</style>
<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>
<script type="text/javascript">
var title 	= '0';
var i 		= 0;
var done 	= ['','','','','','',''];
var aJaxURL	= "server-side/report/top_10.action.php";		//server side folder url
var tName   = "report";
var start	= $("#search_start").val();
var end		= $("#search_end").val();
$(document).ready(function() {
	GetDate("search_start");
	GetDate("search_end");
	
    	param 			= new Object();
		param.start     = $('#search_start').val();
		param.end       = $('#search_end').val();
        $.ajax({
            url: aJaxURL,
            data: param,
            success: function(data) {
                $("#tbody-sparkline").html(data.dc);
            }
        }).done(function() {
        	drawFirstLevel();
        });

	
	$("#back").button({ disabled: true });
	$("#back").button({ icons: { primary: "ui-icon-arrowthick-1-w" }});

});

$(document).on("change", "#search_start", function () 	{
	param 			= new Object();
    param.start     = $('#search_start').val();
    param.end       = $('#search_end').val();
    $.ajax({
        url: aJaxURL,
        data: param,
        success: function(data) {
            $("#tbody-sparkline").html(data.dc);
        }
    }).done(function() {
    	drawFirstLevel();
    });
});
$(document).on("change", "#search_end"  , function () 	{
	param 			= new Object();
	param.start     = $('#search_start').val();
	param.end       = $('#search_end').val();
    $.ajax({
        url: aJaxURL,
        data: param,
        success: function(data) {
            $("#tbody-sparkline").html(data.dc);
        }
    }).done(function() {
    	drawFirstLevel();
    });
});

function drawFirstLevel(){
	var start	= $("#search_start").val();
	var end		= $("#search_end").val();

	    /**
	     * Create a constructor for sparklines that takes some sensible defaults and merges in the individual
	     * chart options. This function is also available from the jQuery plugin as $(element).highcharts('SparkLine').
	     */
	    Highcharts.SparkLine = function (a, b, c) {
	        var hasRenderToArg = typeof a === 'string' || a.nodeName,
	            options = arguments[hasRenderToArg ? 1 : 0],
	            defaultOptions = {
	                chart: {
	                    renderTo: (options.chart && options.chart.renderTo) || this,
	                    backgroundColor: null,
	                    borderWidth: 0,
	                    type: 'area',
	                    margin: [2, 0, 2, 0],
	                    width: 120,
	                    height: 20,
	                    style: {
	                        overflow: 'visible'
	                    },
	                    skipClone: true
	                },
	                title: {
	                    text: ''
	                },
	                credits: {
	                    enabled: false
	                },
	                xAxis: {
	                    labels: {
	                        enabled: false
	                    },
	                    title: {
	                        text: null
	                    },
	                    startOnTick: false,
	                    endOnTick: false,
	                    tickPositions: []
	                },
	                yAxis: {
	                    endOnTick: false,
	                    startOnTick: false,
	                    labels: {
	                        enabled: false
	                    },
	                    title: {
	                        text: null
	                    },
	                    tickPositions: [0]
	                },
	                legend: {
	                    enabled: false
	                },
	                tooltip: {
	                    backgroundColor: null,
	                    borderWidth: 0,
	                    shadow: false,
	                    useHTML: true,
	                    hideDelay: 0,
	                    shared: true,
	                    padding: 0,
	                    positioner: function (w, h, point) {
	                        return { x: point.plotX - w / 2, y: point.plotY - h };
	                    }
	                },
	                plotOptions: {
	                    series: {
	                        animation: false,
	                        lineWidth: 1,
	                        shadow: false,
	                        states: {
	                            hover: {
	                                lineWidth: 1
	                            }
	                        },
	                        marker: {
	                            radius: 1,
	                            states: {
	                                hover: {
	                                    radius: 2
	                                }
	                            }
	                        },
	                        fillOpacity: 0.25
	                    },
	                    column: {
	                        negativeColor: '#910000',
	                        borderColor: 'silver'
	                    }
	                }
	            };

	        options = Highcharts.merge(defaultOptions, options);

	        return hasRenderToArg ?
	            new Highcharts.Chart(a, options, c) :
	            new Highcharts.Chart(options, b);
	    };

	    var start = +new Date(),
	        $tds = $('td[data-sparkline]'),
	        fullLen = $tds.length,
	        n = 0;

	    // Creating 153 sparkline charts is quite fast in modern browsers, but IE8 and mobile
	    // can take some seconds, so we split the input into chunks and apply them in timeouts
	    // in order avoid locking up the browser process and allow interaction.
	    function doChunk() {
	        var time = +new Date(),
	            i,
	            len = $tds.length,
	            $td,
	            stringdata,
	            arr,
	            data,
	            chart;

	        for (i = 0; i < len; i += 1) {
	            $td = $($tds[i]);
	            stringdata = $td.data('sparkline');
	            arr = stringdata.split('; ');
	            data = $.map(arr[0].split(', '), parseFloat);
	            chart = {};

	            if (arr[1]) {
	                chart.type = arr[1];
	            }
	            $td.highcharts('SparkLine', {
	                series: [{
	                    data: data,
	                    pointStart: 1
	                }],
	                tooltip: {
	                    headerFormat: '<span style="font-size: 10px">' + $td.parent().find('th').html() + ', Q{point.x}:</span><br/>',
	                    pointFormat: '<b>{point.y}</b>'
	                },
	                chart: chart
	            });

	            n += 1;

	            // If the process takes too much time, run a timeout to allow interaction with the browser
	            if (new Date() - time > 500) {
	                $tds.splice(0, i + 1);
	                setTimeout(doChunk, 0);
	                break;
	            }

	            // Print a feedback on the performance
	            if (n === fullLen) {
	                //$('#result').html('Generated ' + fullLen + ' sparklines in ' + (new Date() - start) + ' ms');
	            }
	        }
	    }
	    doChunk();

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
	<div id="tabs" style="width: 90%; height: 1800px;">
		<div class="callapp_head">კომპანიაში შემოსული მომართვები<hr class="callapp_head_hr"></div>
         <div id="button_area" style="margin: 3% 0 0 0">
		</div>
       <div id="button_area" style="margin: 3% 0 0 0">
         <div class="left" style="width: 175px;">
           <input type="text" name="search_start" id="search_start"  class="inpt right"/>
             </div>
            	<label for="search_start" class="left" style="margin:5px 0 0 3px">-დან</label>
             <div class="left" style="width: 185px;">
	            <input type="text" name="search_end" id="search_end"  class="inpt right" />
             </div>
            	<label for="search_end" class="left" style="margin:5px 0 0 3px">–მდე</label>
           <br /><br /><br />
            </div>
		
		<input type="text" id="hidden_name" value="" style="display: none;" />
		<br><br><br><br>

<table id="table-sparkline" style="width: 100%; margin-top:20px;">
    <thead>
        <tr>
            <th>მომსახურების ცენტრი</th>
            <th>სულ</th>
            <th>ბოლო 4 თვე</th>
            <th>ტექნიკური</th>
            <th>ტექნიკური ბოლო 4 თვე</th>
            <th>სხვა</th>
            <th>სხვა ბოლო 4 თვე</th>
        </tr>
    </thead>
    <tbody id="tbody-sparkline">
         
    </tbody>
</table>

</body>
</html>

