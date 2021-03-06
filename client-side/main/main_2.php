<html>
<head>
<style type="text/css">
#in_form,.in_form-class{
	overflow: visible;
}
.highcharts-axis-labels {
    cursor: pointer;
    text-decoration: underline;
}
.green
	{
	background-color: rgba(255, 0, 29, 0.51) !important;
	color: #0D233A !important;
	font-size: 14px !important;
		
	}
	.ui-dropdownchecklist-selector{
		background: #FFF;
		padding: 9px 0;
	}
	.ui-dropdownchecklist-text{
		display: inline;
	}
	.ui-dropdownchecklist-item{
		background: #FFF !important;
	}
	#report td:nth-child(3){
	   text-align:right !important;
    }
    #report td:nth-child(4){
	   text-align:right !important;
    }
</style>
<script type="text/javascript" src="js/ui.dropdownchecklist.js"></script>
<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>
<script type="text/javascript">
var title 	= '0';
var i 		= 0;
var done 	= ['','','','','','',''];
var aJaxURL	= "server-side/main_2.action.php";		//server side folder url
var tName   = "report";
var start	= $("#search_start").val();
var end		= $("#search_end").val();
$(document).ready(function() {
	GetDate("search_start");
	GetDate("search_end");
	$("#dropdown").dropdownchecklist({ firstItemChecksAll: true, explicitClose: 'დახურვა',icon: {}, width: 200, zIndex: 111, onComplete : drawFirstLevel});
	$("#dropdown1").dropdownchecklist({ firstItemChecksAll: true, explicitClose: 'დახურვა',icon: {}, width: 200, zIndex: 111, onComplete : filter_sc});  
	drawFirstLevel();
	$("#back").button({ disabled: true });
	$("#back").button({ icons: { primary: "ui-icon-arrowthick-1-w" }});
    $('#back').click(function(){
	    i--;
	    drawFirstLevel();
    	if(i==3)$("#back").button({ disabled: true });
    });
    
});

$(document).on("change", "#search_start", function () 	{drawFirstLevel();});
$(document).on("change", "#search_end"  , function () 	{drawFirstLevel();});



function drawFirstLevel(){
		 var options = {
				 chart: {
			            type: 'column',
			            renderTo: 'chart_container'
			        },
			        title: {
			            text: ''
			        },
			        xAxis: {
			            type: 'category',
			            labels: {
			                
			                style: {
			                    fontSize: '13px',
			                    fontFamily: 'Verdana, sans-serif'
			                }
			            }
			        },
			        yAxis: {
			            min: 0,
			            title: {
			                text: ''
			            }
			        },
			        legend: {
			            enabled: false
			        },
			        plotOptions: {
			            series: {
			                borderWidth: 2,
			                dataLabels: {
			                    enabled: true
			                }
			            }
			        },
			        tooltip: {
			            pointFormat: 'რაოდენობა : <b>{point.y:.0f}</b>'
			        },
			        xAxis: {
			            categories: [],
			            labels: {
			            	 rotation: -45,
			            	align: 'right'
			            }
			        },
	                dataLabels: {
	                    enabled: true,
	                    rotation: -180,
	                    color: '#FFFFFF',
	                    align: 'right',
	                    format: '{point.y:.1f}', // one decimal
	                    y: 10, // 10 pixels down from the top
	                    style: {
	                        fontSize: '13px',
	                        fontFamily: 'Verdana, sans-serif'
	                    }
	                },
	                series: [{
	                    type: 'column',
	                    name: 'კატეგორიები',
	                    data: []
	                }]
	            }
		var start	  = $("#search_start").val();
		var end		  = $("#search_end").val();
		var branches  = $("#dropdown").val();
		
		var d_url   ="&start="+start+"&end="+end+"&done="+i+"&departament="+done[0]+"&type="+done[1]+"&category="+done[2]+"&sub_category="+done[3]+"&branches="+branches;
		var url     = aJaxURL+"?act=get_category"+d_url;
		GetDataTable(tName, aJaxURL, "get_list", 4, d_url, 0, "",'','',[2,3], "<'dataTable_buttons'T><'F'Cfipl>");
		
        
		 $("#report tbody").on("click", "tr", function () {
			 if(i==3){
				 d_url1   ="&start="+start+"&end="+end+"&done="+i+"&type="+done[0]+"&category="+done[1]+"&sub_category="+done[2];
			 		var nTds = $("td", this);
		            var rID = $(nTds[1]).text();
	    	    GetDataTable("report_1", aJaxURL, "get_in_page", 12, d_url1+"&rid="+rID, 0, "", 1, "asc", '', "<'dataTable_buttons'T><'F'Cfipl>");
	    	    GetDialog("in_form", "90%", "auto","","left+43 top");
	     		SetEvents("", "", "", "report_1", "add-edit-form", "server-side/call/incomming.action.php");
	     		$('.ui-dialog-buttonset').hide();
	     		}
			    setTimeout(function(){
		    		$('#in_form  .ColVis,#in_form .dataTable_buttons').css('display','none');
		    		$("#show_copy_prit_exel1").css('background','#E6F2F8');
		            $("#show_copy_prit_exel1").children('img').attr('src','media/images/icons/select.png');
		            $("#show_copy_prit_exel1").attr('myvar','0');
		    	}, 190);
	     	});
        $.getJSON(url, function(json) {
	                options.series[0].data = json.data;
	                options.title['text']=json.text;
	                chart = new Highcharts.Chart(options);

	                $(".highcharts-axis-labels").on("click", "tspan", function() {

	                	$("#back").button({ disabled: false });
                		done[i]=$(this).text();
                		if(i==3) i=0;
                		else i++;
                		drawFirstLevel();
	                	//alert($(this).text());
	                });
	                $("#total_quantity").html("იტვირთება....")
	                setTimeout(function(){ $("#total_quantity").html($("#qnt").html().split(">")[1]);}, 500);
	                $('#report td:nth-child(3)').css('text-align','right');
	                $('#report td:nth-child(4)').css('cssText','text-align: right !important');
	                if(i==3) $("#report td").addClass("green");
	        		else     $(".green").removeClass("green");
		});

        setTimeout(function(){
    		$('.ColVis, .dataTable_buttons').css('display','none');
    		$("#show_copy_prit_exel").css('background','#E6F2F8');
            $("#show_copy_prit_exel").children('img').attr('src','media/images/icons/select.png');
            $("#show_copy_prit_exel").attr('myvar','0');
    	}, 120);
}

$(".highcharts-axis-labels").on("click", "tspan", function() {
	getoherchart($(this).text(),number,procent,users);
	getoherchart1($(this).text(),number,procent,users);
}); 

$(document).on("click", ".download1", function () {
	var str = 1;
	var link = ($(this).attr("str"));
	link = 'http://10.0.18.18:8080/records/' + link;
	var btn = {
	        "cancel": {
	            text: "დახურვა",
	            id: "cancel-dialog",
	            click: function () {
	                $(this).dialog("close");
	            }
	        }
	    };
	GetDialog_audio("audio_dialog", "auto", "auto",btn );
	$("#audio_dialog").html('<audio controls autoplay style="width:500px;"><source src="'+link+'" type="audio/wav"> Your browser does not support the audio element.</audio>');
    $(".download1").css( "background","#F99B03" );
    $(this).css( "background","#33dd33" );
   
});
$(document).on("click", ".download", function () {
	var str = 1;
	var link = ($(this).attr("str"));
	link = 'http://10.0.18.18:8080/records/' + link;
	var btn = {
	        "cancel": {
	            text: "დახურვა",
	            id: "cancel-dialog",
	            click: function () {
	                $(this).dialog("close");
	            }
	        }
	    };
	GetDialog_audio("audio_dialog", "auto", "auto",btn );
	$("#audio_dialog").html('<audio controls autoplay style="width:500px;"><source src="'+link+'" type="audio/wav"> Your browser does not support the audio element.</audio>');
    $(".download").css( "background","#408c99" );
    $(this).css( "background","#FF5555" );
   
});
function LoadDialog(fname){

	GetDialog(fname, "1200", "auto",'','left+43 top');
	$('#add-edit-form, .idle').attr('disabled', true);
	$('.ui-dialog-buttonset').hide();
	$("#client_checker,#add_sms,#add_mail,#show_all_scenario").button();
	LoadTable1('history',7,'get_list_history',"<'F'lip>",'','server-side/call/incomming.action.php');


};
function show_right_side(id){
    $("#right_side fieldset").hide();
    $("#" + id).show();
    $(".add-edit-form-class").css("width", "1200");
    //$('#add-edit-form').dialog({ position: 'left top' });
    hide_right_side();
    var str = $("."+id).children('img').attr('src');
	str = str.substring(0, str.length - 4);
    $("."+id).children('img').attr('src',str+'_blue.png');
    $("."+id).children('div').css('color','#2681DC');
}

function hide_right_side(){
	$("#side_menu").children('spam').children('div').css('color','#FFF');
    $(".info").children('img').attr('src','media/images/icons/info.png');
    $(".scenar").children('img').attr('src','media/images/icons/scenar.png');
    $(".task").children('img').attr('src','media/images/icons/task.png');
    $(".sms").children('img').attr('src','media/images/icons/sms.png');
    $(".mail").children('img').attr('src','media/images/icons/mail.png');
    $(".record").children('img').attr('src','media/images/icons/record.png');
    $(".file").children('img').attr('src','media/images/icons/file.png');
    $("#record fieldset").show();
}

function show_main(id,my_this){
	$("#client_main,#client_other").hide();
	$("#" + id).show();
	$(".client_main,.client_other").css('border','none');
	$(".client_main,.client_other").css('padding','6px');
	$(my_this).css('border','1px solid #ccc');
	$(my_this).css('border-bottom','1px solid #F1F1F1');
	$(my_this).css('padding','5px');
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
$(document).on("click", "#show_copy_prit_exel1", function () {
    
    if($(this).attr('myvar') == 0){
        $('#in_form .ColVis,#in_form .dataTable_buttons,#in_form #table_right_menu_content').css('display','block');
        $(this).css('background','#2681DC');
        $(this).children('img').attr('src','media/images/icons/select_w.png');
        $(this).attr('myvar','1');
    }else{
    	$('#in_form .ColVis,#in_form .dataTable_buttons,#in_form #table_right_menu_content').css('display','none');
    	$(this).css('background','#E6F2F8');
        $(this).children('img').attr('src','media/images/icons/select.png');
        $(this).attr('myvar','0');
    }
});

function filter_sc(){
	var branches1  = $("#dropdown1").val();

    $.ajax({
        url: 'server-side/report/check_sc.action.php',
        data: 'act=check_sc&ids='+branches1,
        success: function(data) {
            $("#dropdown").html(data.test);
            $("#dropdown").dropdownchecklist({ firstItemChecksAll: true, explicitClose: 'დახურვა',icon: {}, width: 200, zIndex: 111, onComplete : drawFirstLevel});
            drawFirstLevel();
        }
    });
}

</script>
</head>
<body>
<div id="tabs" style="width: 97%; height: 850px;">
<div class="callapp_head">მომსახურების ცენტრების მიხედვით<hr class="callapp_head_hr"></div>
     
   <div id="button_area" style="margin: 3% 0 0 0">

 <button id="back" style="margin-top:0px; float: left;">უკან</button>

 <div class="left" style="width: 175px;">
   <input type="text" name="search_start" id="search_start"  class="inpt right"/>
     </div>
    	<label for="search_start" class="left" style="margin:5px 0 0 3px">-დან</label>
     <div class="left" style="width: 185px;">
        <input type="text" name="search_end" id="search_end"  class="inpt right" />
     </div>
	 <label for="search_end" class="left" style="margin:5px 0 0 3px">–მდე</label>
     <!-- >div class="left" style="width: 200px;margin-left: 20px;">
        <select id="dropdown1" multiple="multiple">
            <option value="0" selected="selected">(ყველა)</option>
            <?php 
//         	require_once('includes/classes/core.php');
// 			   $rResult = mysql_query("SELECT id, `name`
//                                        FROM   `branch` 
//                                        WHERE   actived=1");
// 			    while ( $aRow = mysql_fetch_assoc( $rResult ) )
// 			    {
// 			    	echo '<option value="'.$aRow['id'].'">'.$aRow['name'].'</option>';
			    	
// 			    }
			    
			 ?>
        </select>
     </div>
     <div class="left" style="width: 200px;margin-left: 20px;">
     	<select id="dropdown" multiple="multiple">
            <option value="0" selected="selected">(ყველა)</option>
            <?php 
//         	require_once('includes/classes/core.php');
// 			   $rResult = mysql_query("SELECT id, `name` 
//                                        FROM   `service_center` 
//                                        WHERE   actived = 1 AND parent_id = 0");
// 			    while ( $aRow = mysql_fetch_assoc( $rResult ) )
// 			    {
// 			    	echo '<option value="'.$aRow['id'].'">'.$aRow['name'].'</option>';
			    	
// 			    }
			    
			 ?>
        </select>
     </div-->
	        
   <label class="left" style="margin:5px 0 0 20px">ზარების  ჯამური რაოდენობა: </label> <label id="total_quantity" class="left" style="margin:5px 0 0 2px; font-weight: bold;">0</label>
   <br/><br/><br/>
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
            <th style="width:70%">დასახელება</th>
            <th style="width:15%">რაოდენობა</th>
            <th style="width:15%">პროცენტი</th>
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

<div id="audio_dialog" class="form-dialog" title="ჩანაწერი"></div>
<div id="in_form"  class="form-dialog">

<table style="margin-top: 10px;" id="table_right_menu">
	<tr>
		<td>
			<img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
		</td>
		<td>
			<img alt="log" src="media/images/icons/log.png" height="14" width="14">
		</td>
		<td id="show_copy_prit_exel1" myvar="0">
			<img alt="link" src="media/images/icons/select.png" height="14" width="14">
		</td>
	</tr>
</table>

<table class="display" id="report_1" style="width: 100%;">
    <thead>
        <tr id="datatable_header">
            <th>ID</th>
            <th style="width: 60px;">№</th>
            <th style="width: 130px;">თარიღი</th>
            <th style="width: 15%;">ტელეფონი</th>
            <th style="width: 20%;">სახელი</th>
            <th style="width: 20%;">კატეგორია 1</th>
            <th style="width: 20%;">კატეგორია 1_1</th>            
            <th style="width: 20%;">კატეგორია 1_1_1</th>
            <th style="width: 15%;">მოსმენა</th>
        </tr>
    </thead>
    <thead>
        <tr class="search_header">
            <th class="colum_hidden">
        	   <input type="text" name="search_id" value="ფილტრი" class="search_init" />
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
                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
            </th>                         
            <th>
                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_phone" value="ფილტრი" class="search_init" />
            </th>
            <th>
                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
            </th>            
        </tr>
    </thead>
    </table>
</div>
<div  id="add-edit-form" class="form-dialog" title="შემომავალი ზარი">	</div>
</body>
</html>

