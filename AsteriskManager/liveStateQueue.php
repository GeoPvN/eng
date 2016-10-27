<?php
 
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$begintime = $time;
$inuse      = Array();
$dict_queue = Array();
$filter_queues = array("2022028");

require("config.php");
require("asmanager.php");
require("realtime_functions.php");
if(isset($_SESSION['QSTATS']['hideloggedoff'])) {
    $ocultar= $_SESSION['QSTATS']['hideloggedoff'];
} else {
    $ocultar="false";
}
if(isset($_SESSION['QSTATS']['filter'])) {
    $filter= $_SESSION['QSTATS']['filter'];
} else {
    $filter="";
}

$am=new AsteriskManager();
$am->connect($manager_host,$manager_user,$manager_secret);

$channels = get_channels ($am);
foreach($channels as $ch=>$chv) {
  list($chan,$ses) = split("-",$ch,2);
  $inuse["$chan"]=$ch;
}

$queues   = get_queues   ($am,$channels);

foreach ($queues as $key=>$val) {
  $queue[] = $key;
}

///QUEUE details
//echo "<BR><h2>".$lang[$language]['calls_waiting_detail']."</h2><BR>";
			
foreach($filter_queues as $qn) {
	$position=1;
	if(!isset($queues[$qn]['calls']))  continue;

	foreach($queues[$qn]['calls'] as $key=>$val) {
		if($position==1) {
			echo "<table width='520' cellpadding=3 cellspacing=3 border=0 class='sortable' id='box-table-b' >\n";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Queue</th>";
			echo "<th>Position</th>";
			echo "<th>Number</th>";
			echo "<th>Wait Time</th>";
			echo "</tr>\n";
			echo "</thead>\n";
			echo "<tbody>\n";
		}

		if($position%2) {
			$odd="class='odd'";
		} else {
			$odd="";
		}
			
		echo "<tr $odd>";
		echo "<td>$qn</td><td>$position</td>";
		echo "<td>".$queues[$qn]['calls'][$key]['chaninfo']['callerid']."</td>";
		echo "<td>".$queues[$qn]['calls'][$key]['chaninfo']['duration_str']." Minutes</td>";
        echo "</tr>";
		$position++;
	}
			
	if($position>1) {
	echo "</tbody>\n";
	echo "</table>\n";
	}
}

$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$endtime = $time;
$totaltime = ($endtime - $begintime);

?>

