<?php

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$begintime = $time;
$inuse      = Array();
$dict_queue = Array();
$filter_queues = array("2022028");
require_once '../includes/classes/core.php';
      $qv=mysql_query("SELECT persons.`name`,
        			          last_extension
                       FROM   `users`
					   JOIN   persons ON users.person_id=persons.id
                       WHERE  logged = 1 AND NOT ISNULL(last_extension)");
      while ( $aRow = mysql_fetch_array( $qv ) )
      {
          $ext[]=$aRow;
      }         
   
      

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

///QUEUES
//echo $lang[$language]['agent_status']." <br/><br/>";

$color['unavailable']="flesh_off.png";
$color['unknown']="#dadada";
$color['busy']="flesh_inc.png";
$color['dialout']="#d0303f";
$color['ringing']="flesh_ringing.png";
$color['not in use']="flesh_free.png";
$color['paused']="#000000";

foreach($filter_queues  as $qn) {
	if($filter=="" || stristr($qn,$filter)) {
		$contador=1;
		if(!isset($queues[$qn]['members'])) continue;

		foreach($queues[$qn]['members'] as $key=>$val) {
			 
			$stat="";
			$last="";
			$dur="";
			$clid="";
			$akey = $queues[$qn]['members'][$key]['agent'];
			$aname = $queues[$qn]['members'][$key]['name'];
			$aval = $queues[$qn]['members'][$key]['type'];
			$myExt = explode("/",$queues[$qn]['members'][$key][id]);
			
			if(array_key_exists($key,$inuse)) {
				if($aval=="not in use") {
					$aval = "dialout";
				}
				if($channels[$inuse[$key]]['duration']=='') {
					$newkey = $channels[$inuse[$key]]['bridgedto'];
					$dur = $channels[$newkey]['duration_str'];
					$clid = $channels[$newkey]['callerid'];
				} else {
					$newkey = $channels[$inuse[$key]]['bridgedto'];
					$clid = $channels[$newkey]['callerid'];
					$dur = $channels[$inuse[$key]]['duration_str'];
				}
			}
			$stat = $queues[$qn]['members'][$key]['status'];
			$last = $queues[$qn]['members'][$key]['lastcall'];

			if(($aval == "unavailable" || $aval == "unknown") && $ocultar=="true") {
				// Skip
			} else {
				if($contador==1) {
				    ////////////////////////////---------------------------------------------------------------------
					echo "<table id='flesh_table'>\n";
					echo "<thead>";
					echo "<tr>";
					echo "<th>Queue</th>";
					echo "<th>Department</th>";
					echo "<th>Extension</th>";
					echo "<th>Worker</th>";
					echo "<th>Status</th>";
					echo "<th>Time</th>";
					echo "<th>Customers</th>";
					echo "</tr>\n";
					echo "</thead><tbody>\n";
				}

				if($contador%2) {
					$odd="";
				} else {
					$odd="";
				}

				if($last<>"") {
					$last=$last." ".$lang[$language]['min_ago'];
				} else {
					$last = $lang[$language]['no_info'];
				}

				$agent_name = agent_name($aname);
				$rr = mysql_fetch_array(mysql_query("   SELECT  `user_info`.`name`,
                                    				            `file`.`rand_name`,
				                                                `department`.`name`
                                    				    FROM    `users`
                                    				    JOIN user_info ON users.id = user_info.user_id
                                    				    LEFT JOIN file ON users.id = file.users_id
				                                        LEFT JOIN department ON user_info.dep_id = department.id
                                    				    WHERE users.extension_id = '$myExt[1]'"));
				echo '<tr '.$odd.' queue="'.$qn.'" dep="'.$rr[2].'" ext="'.$myExt[1].'" user="'.$rr[0].'" state="'.$color[$aval].'" >';
				echo "<td>$qn</td>";
				echo "<td>$rr[2]</td>";
				echo "<td>$agent_name</td>";

				if($stat<>"") {
				$aval="paused";
			}

			if(!array_key_exists($key,$inuse)) {
					if($aval=="busy") $aval="not in use";
			}

			$aval2 = ereg_replace(" ","_",$aval);
			$mystringaval = $lang[$language][$aval2];

			if($mystringaval=="") $mystringaval = $aval;
			
			echo "<td><span style=\"float:left; height: 30px; width: 30px; background: url(media/uploads/file/$rr[1]); background-size: 30px 30px; background-repeat: no-repeat; display: block;\"></span><span style=\"float:left;   margin-top: 11px;  margin-left: 5px;\">$rr[0]</span></td>";
			echo '<td class="td_center"><img alt="inner" src="media/images/icons/'.$color[$aval].'" height="14" width="14"></td>';
			echo "<td>$dur</td>";
			echo "<td>$clid</td>";
			echo "</tr>";
			$contador++;
			}
			}
		if($contador>1) {
		echo "</tbody>";
		echo "</table><br/>\n";
		}
	}
}

///QUEUE details

			
foreach($filter_queues as $qn) {
	$position=1;
	if(!isset($queues[$qn]['calls']))  continue;

	foreach($queues[$qn]['calls'] as $key=>$val) {
		if($position==1) {
		    echo "<BR><h2>".$lang[$language]['calls_waiting_detail']."</h2><BR>";
			echo '<table id="tb" >';
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
			$odd="class=''";
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

