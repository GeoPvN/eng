<?php
require_once('../../includes/classes/core.php');
//asteriskcdrdb

header('Content-Type: application/json');
$start_time = $_REQUEST['start'];
$end_time   = $_REQUEST['end'];
$agent = $_REQUEST['agent'];
$queue = $_REQUEST['queuet'];

$quantity = array();
$cause = array();
$cause1 = array();

$name = array();
$agentt = array();

$call_count = array();







$name[]     = '';


//-----------------------ნაპასუხები ზარები ოპერატორების მიხედვით
if($_REQUEST['act']=='answer_call_operator'){
											$ress =mysql_query("SELECT 	extension AS `agent`,
                                                            			COUNT(*) AS `num`
                                                                FROM 	`asterisk_outgoing`
                                                                WHERE	duration > 0
                                                                AND DATE(call_datetime) >= '$start_time'
                                                                AND DATE(call_datetime) <= '$end_time' 
                                                                AND extension in ($agent)
                                                                GROUP BY extension");
			
while($row1 = mysql_fetch_assoc($ress)){

	$call_count[] = (float)$row1[num];
	$agentt[]		= $row1[agent];
}
}
//------------------------------ ნაპასუხები ზარები კვირის დღეების მიხედვით
if($_REQUEST['act']=='answer_call_week'){
		$res3 =mysql_query("SELECT  CASE
										WHEN DAYOFWEEK(call_datetime) = 1 THEN 'Sunday'
										WHEN DAYOFWEEK(call_datetime) = 2 THEN 'Monday'
										WHEN DAYOFWEEK(call_datetime) = 3 THEN 'Tuesday'
										WHEN DAYOFWEEK(call_datetime) = 4 THEN 'Wednesday'
										WHEN DAYOFWEEK(call_datetime) = 5 THEN 'Wednesday'
										WHEN DAYOFWEEK(call_datetime) = 6 THEN 'Friday'
										WHEN DAYOFWEEK(call_datetime) = 7 THEN 'Saturday'
									END AS `date`,
									COUNT(*) AS `answer_count1`
							FROM 		`asterisk_outgoing` as gg
                            WHERE		`gg`.duration > 0
                            AND DATE(gg.call_datetime) >= '$start_time'
                            AND DATE(gg.call_datetime) <= '$end_time' 
                            AND gg.extension in ($agent)
							GROUP BY DAYOFWEEK(call_datetime)");
									
		while($row3 = mysql_fetch_assoc($res3)){

		$answer_count1[] = (float)$row3[answer_count1];
		$datetime1[]		= $row3[date];
	}
}
	
	//------------------------------ უპასუხო ზარები კვირის დღეების მიხედვით
if($_REQUEST['act']=='unanswer_call_week'){	
	$res10 =mysql_query("SELECT CASE
									WHEN DAYOFWEEK(call_datetime) = 1 THEN 'Sunday'
									WHEN DAYOFWEEK(call_datetime) = 2 THEN 'Monday'
									WHEN DAYOFWEEK(call_datetime) = 3 THEN 'Tuesday'
									WHEN DAYOFWEEK(call_datetime) = 4 THEN 'Wednesday'
									WHEN DAYOFWEEK(call_datetime) = 5 THEN 'Wednesday'
									WHEN DAYOFWEEK(call_datetime) = 6 THEN 'Friday'
									WHEN DAYOFWEEK(call_datetime) = 7 THEN 'Saturday'
								END AS `date`,
								COUNT(*) AS `unanswer_count`
						FROM 		`asterisk_outgoing` as gg
                        WHERE		`gg`.duration = 0
                        AND DATE(gg.call_datetime) >= '$start_time'
                        AND DATE(gg.call_datetime) <= '$end_time' 
                        AND gg.extension in ($agent)
						GROUP BY DAYOFWEEK(call_datetime)
									");
	
	while($row10 = mysql_fetch_assoc($res10)){
	
		$unanswer_call2[] = (float)$row10[unanswer_count];
		$date1[]		= $row10[date];
	}
}


//------------------------------ უპასუხო ზარები დღეების მიხედვით
if($_REQUEST['act']=='unanswer_call_day'){
$res8 =mysql_query("SELECT 	DATE(call_datetime) AS `datetime`,
						COUNT(*) AS `unanswer_call`
						FROM 		`asterisk_outgoing`
                        WHERE		`asterisk_outgoing`.duration = 0
                        AND DATE(call_datetime) >= '$start_time'
                        AND DATE(call_datetime) <= '$end_time'
                        AND extension in ($agent)
						GROUP BY DATE(call_datetime)
					");
		
		while($row8 = mysql_fetch_assoc($res8)){
		
			$unanswer_call[] = (float)$row8[unanswer_call];
			$times[]		= $row8[datetime];
		}
}
		
//------------------------------ ნაპასუხები ზარები დღეების მიხედვით		
if($_REQUEST['act']=='answer_call_day'){
$res4 =mysql_query("SELECT 	DATE(call_datetime) AS `datetime`,
						COUNT(*) AS `answer_count2`
						FROM 		`asterisk_outgoing`
                        WHERE		`asterisk_outgoing`.duration > 0 
                        AND DATE(call_datetime) >= '$start_time'
                        AND DATE(call_datetime) <= '$end_time' 
                        AND extension in ($agent)
						GROUP BY DATE(call_datetime)");

		while($row4 = mysql_fetch_assoc($res4)){

		$answer_count2[] = (float)$row4[answer_count2];
		$datetime2[]		= $row4[datetime];
		}
}
//------------------------------ უპასუხო ზარები საათების მიხედვით
if($_REQUEST['act']=='unanswer_call_hour'){			
	$res9 =mysql_query("SELECT  CASE		
										WHEN HOUR(call_datetime) >= 0 AND HOUR(call_datetime) < 1 THEN '00:00'
										WHEN HOUR(call_datetime) >= 1 AND HOUR(call_datetime) < 2 THEN '01:00'
										WHEN HOUR(call_datetime) >= 2 AND HOUR(call_datetime) < 3 THEN '02:00'
										WHEN HOUR(call_datetime) >= 3 AND HOUR(call_datetime) < 4 THEN '03:00'
										WHEN HOUR(call_datetime) >= 4 AND HOUR(call_datetime) < 5 THEN '04:00'
										WHEN HOUR(call_datetime) >= 5 AND HOUR(call_datetime) < 6 THEN '05:00'
										WHEN HOUR(call_datetime) >= 6 AND HOUR(call_datetime) < 7 THEN '06:00'
										WHEN HOUR(call_datetime) >= 7 AND HOUR(call_datetime) < 8 THEN '07:00'
										WHEN HOUR(call_datetime) >= 8 AND HOUR(call_datetime) < 9 THEN '08:00'
										WHEN HOUR(call_datetime) >= 9 AND HOUR(call_datetime) < 10 THEN '09:00'
										WHEN HOUR(call_datetime) >= 10 AND HOUR(call_datetime) < 11 THEN '10:00'
										WHEN HOUR(call_datetime) >= 11 AND HOUR(call_datetime) < 12 THEN '11:00'
										WHEN HOUR(call_datetime) >= 12 AND HOUR(call_datetime) < 13 THEN '12:00'
										WHEN HOUR(call_datetime) >= 13 AND HOUR(call_datetime) < 14 THEN '13:00'
										WHEN HOUR(call_datetime) >= 14 AND HOUR(call_datetime) < 15 THEN '14:00'
										WHEN HOUR(call_datetime) >= 15 AND HOUR(call_datetime) < 16 THEN '15:00'
										WHEN HOUR(call_datetime) >= 16 AND HOUR(call_datetime) < 17 THEN '16:00'
										WHEN HOUR(call_datetime) >= 17 AND HOUR(call_datetime) < 18 THEN '17:00'
										WHEN HOUR(call_datetime) >= 18 AND HOUR(call_datetime) < 19 THEN '18:00'
										WHEN HOUR(call_datetime) >= 19 AND HOUR(call_datetime) < 20 THEN '19:00'
										WHEN HOUR(call_datetime) >= 20 AND HOUR(call_datetime) < 21 THEN '20:00'
										WHEN HOUR(call_datetime) >= 21 AND HOUR(call_datetime) < 22 THEN '21:00'
										WHEN HOUR(call_datetime) >= 22 AND HOUR(call_datetime) < 23 THEN '22:00'
										WHEN HOUR(call_datetime) >= 23 AND HOUR(call_datetime) < 24 THEN '23:00'

									END AS `times`,
									COUNT(*) AS `unanswer_count`
									FROM 		`asterisk_outgoing`
                                    WHERE		`asterisk_outgoing`.duration = 0 
                                    AND DATE(call_datetime) >= '$start_time'
                                    AND DATE(call_datetime) <= '$end_time'
	                                AND extension in ($agent)
									GROUP BY HOUR(call_datetime)");
	
	while($row9 = mysql_fetch_assoc($res9)){
	
		$unanswer_count1[] = (float)$row9[unanswer_count];
		$times2[]		= $row9[times];
	}
}
	//------------------------------ ნაპასუხები ზარები საათების მიხედვით
if($_REQUEST['act']=='answer_call_hour'){
	$res2 =mysql_query("SELECT  CASE	
									WHEN HOUR(call_datetime) >= 0 AND HOUR(call_datetime) < 1 THEN '00:00'
									WHEN HOUR(call_datetime) >= 1 AND HOUR(call_datetime) < 2 THEN '01:00'
									WHEN HOUR(call_datetime) >= 2 AND HOUR(call_datetime) < 3 THEN '02:00'
									WHEN HOUR(call_datetime) >= 3 AND HOUR(call_datetime) < 4 THEN '03:00'
									WHEN HOUR(call_datetime) >= 4 AND HOUR(call_datetime) < 5 THEN '04:00'
									WHEN HOUR(call_datetime) >= 5 AND HOUR(call_datetime) < 6 THEN '05:00'
									WHEN HOUR(call_datetime) >= 6 AND HOUR(call_datetime) < 7 THEN '06:00'
									WHEN HOUR(call_datetime) >= 7 AND HOUR(call_datetime) < 8 THEN '07:00'
									WHEN HOUR(call_datetime) >= 8 AND HOUR(call_datetime) < 9 THEN '08:00'
									WHEN HOUR(call_datetime) >= 9 AND HOUR(call_datetime) < 10 THEN '09:00'
									WHEN HOUR(call_datetime) >= 10 AND HOUR(call_datetime) < 11 THEN '10:00'
									WHEN HOUR(call_datetime) >= 11 AND HOUR(call_datetime) < 12 THEN '11:00'
									WHEN HOUR(call_datetime) >= 12 AND HOUR(call_datetime) < 13 THEN '12:00'
									WHEN HOUR(call_datetime) >= 13 AND HOUR(call_datetime) < 14 THEN '13:00'
									WHEN HOUR(call_datetime) >= 14 AND HOUR(call_datetime) < 15 THEN '14:00'
									WHEN HOUR(call_datetime) >= 15 AND HOUR(call_datetime) < 16 THEN '15:00'
									WHEN HOUR(call_datetime) >= 16 AND HOUR(call_datetime) < 17 THEN '16:00'
									WHEN HOUR(call_datetime) >= 17 AND HOUR(call_datetime) < 18 THEN '17:00'
									WHEN HOUR(call_datetime) >= 18 AND HOUR(call_datetime) < 19 THEN '18:00'
									WHEN HOUR(call_datetime) >= 19 AND HOUR(call_datetime) < 20 THEN '19:00'
									WHEN HOUR(call_datetime) >= 20 AND HOUR(call_datetime) < 21 THEN '20:00'
									WHEN HOUR(call_datetime) >= 21 AND HOUR(call_datetime) < 22 THEN '21:00'
									WHEN HOUR(call_datetime) >= 22 AND HOUR(call_datetime) < 23 THEN '22:00'
									WHEN HOUR(call_datetime) >= 23 AND HOUR(call_datetime) < 24 THEN '23:00'
								END AS `times`,
							COUNT(*) AS `answer_count`
							FROM 		`asterisk_outgoing`
                            WHERE		`asterisk_outgoing`.duration > 0 
                            AND DATE(call_datetime) >= '$start_time'
                            AND DATE(call_datetime) <= '$end_time' 
                            AND extension in ($agent)
	                        GROUP BY HOUR(call_datetime)");
	
			while($row2 = mysql_fetch_assoc($res2)){
	
			$answer_count[] = (float)$row2[answer_count];
			$datetime[] 	= $row2[times];
			}
}

							
$unit[]="  Call";
$series[] = array('name' => $name, 'unit' => $unit, 'quantity' => $quantity, 'cause' => $cause);
$series[] = array('name' => $name, 'unit' => $unit, 'call_count' => $call_count, 'agent' => $agentt);
$series[] = array('name' => $name, 'unit' => $unit, 'answer_count' => $answer_count, 'datetime' => $datetime);
$series[] = array('name' => $name, 'unit' => $unit, 'answer_count1' => $answer_count1, 'datetime1' => $datetime1);
$series[] = array('name' => $name, 'unit' => $unit, 'answer_count2' => $answer_count2, 'datetime2' => $datetime2);
$series[] = array('name' => $name, 'unit' => $unit, 'answer_count3' => $answer_count3, 'cause1' => $cause1);
$series[] = array('name' => $name, 'unit' => $unit, 'count1' => $count1, 'queue1' => $queue1);
$series[] = array('name' => $name, 'unit' => $unit, 'count2' => $count2, 'queue2' => $queue2);
$series[] = array('name' => $name, 'unit' => $unit, 'unanswer_call' => $unanswer_call, 'times' => $times);
$series[] = array('name' => $name, 'unit' => $unit, 'unanswer_count1' => $unanswer_count1, 'times2' => $times2);
$series[] = array('name' => $name, 'unit' => $unit, 'unanswer_count2' => $unanswer_call2, 'date1' => $date1);
$series[] = array('name' => $name, 'unit' => $unit, 'mas' => $mas, 'call_second' => $call_second);

echo json_encode($series);

?>