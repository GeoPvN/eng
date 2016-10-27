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


//-----------------------კავშირის გაწყვეტის მიზეზი
if($_REQUEST['act']=='cause_fix'){
				$res = mysql_query("		SELECT	COUNT(*) AS `count`,
													'ოპერატორმა გათიშა' AS `cause`
                                            FROM 	`asterisk_incomming`
                                            WHERE	`asterisk_incomming`.disconnect_cause != 'ABANDON' 
                                            AND DATE(call_datetime) >= '$start_time'
                                            AND DATE(call_datetime) <= '$end_time' 
                                            AND dst_queue IN ($queue) 
                                            AND dst_extension in ($agent)
                                            AND disconnect_cause = 'COMPLETEAGENT'
												

													UNION ALL

											SELECT	COUNT(*) AS `count`,
													'აბონენტმა გათიშა' AS `cause`
											FROM 	`asterisk_incomming`
                                            WHERE	`asterisk_incomming`.disconnect_cause != 'ABANDON'
                                            AND DATE(call_datetime) >= '$start_time'
                                            AND DATE(call_datetime) <= '$end_time'
                                            AND dst_queue IN ($queue)
                                            AND dst_extension in ($agent)
                                            AND disconnect_cause = 'COMPLETECALLER'
														");

while($row = mysql_fetch_assoc($res)){	
	
	$quantity[] = (float)$row[count];
	$cause[]		= $row[cause];
}
}

//-----------------------ნაპასუხები ზარები ოპერატორების მიხედვით
if($_REQUEST['act']=='answer_call_operator'){
											$ress =mysql_query("SELECT 	dst_extension AS `agent`,
                                                            			COUNT(*) AS `num`
                                                                FROM 	`asterisk_incomming`
                                                                WHERE	`asterisk_incomming`.disconnect_cause != 'ABANDON'
                                                                AND DATE(call_datetime) >= '$start_time'
                                                                AND DATE(call_datetime) <= '$end_time' 
                                                                AND dst_queue IN ($queue) 
                                                                AND dst_extension in ($agent)
                                                                GROUP BY dst_extension");
			
while($row1 = mysql_fetch_assoc($ress)){

	$call_count[] = (float)$row1[num];
	$agentt[]		= $row1[agent];
}
}
//------------------------------ ნაპასუხები ზარები კვირის დღეების მიხედვით
if($_REQUEST['act']=='answer_call_week'){
		$res3 =mysql_query("SELECT  CASE
										WHEN DAYOFWEEK(call_datetime) = 1 THEN 'კვირა'
										WHEN DAYOFWEEK(call_datetime) = 2 THEN 'ორშაბათი'
										WHEN DAYOFWEEK(call_datetime) = 3 THEN 'სამშაბათი'
										WHEN DAYOFWEEK(call_datetime) = 4 THEN 'ოთხშაბათი'
										WHEN DAYOFWEEK(call_datetime) = 5 THEN 'ხუთშაბათი'
										WHEN DAYOFWEEK(call_datetime) = 6 THEN 'პარასკევი'
										WHEN DAYOFWEEK(call_datetime) = 7 THEN 'შაბათი'
									END AS `date`,
									COUNT(*) AS `answer_count1`
							FROM 		`asterisk_incomming` as gg
                            WHERE		`gg`.disconnect_cause != 'ABANDON'
                            AND DATE(gg.call_datetime) >= '$start_time'
                            AND DATE(gg.call_datetime) <= '$end_time' 
		                    AND gg.dst_queue IN ($queue)
                            AND gg.dst_extension in ($agent)
							GROUP BY DAYOFWEEK(call_datetime)");
									
		while($row3 = mysql_fetch_assoc($res3)){

		$answer_count1[] = (float)$row3[answer_count1];
		$datetime1[]		= $row3[date];
	}
}
	
	//------------------------------ უპასუხო ზარები კვირის დღეების მიხედვით
if($_REQUEST['act']=='unanswer_call_week'){	
	$res10 =mysql_query("SELECT CASE
				
									WHEN DAYOFWEEK(call_datetime) = 1 THEN 'კვირა'
									WHEN DAYOFWEEK(call_datetime) = 2 THEN 'ორშაბათი'
									WHEN DAYOFWEEK(call_datetime) = 3 THEN 'სამშაბათი'
									WHEN DAYOFWEEK(call_datetime) = 4 THEN 'ოთხშაბათი'
									WHEN DAYOFWEEK(call_datetime) = 5 THEN 'ხუთშაბათი'
									WHEN DAYOFWEEK(call_datetime) = 6 THEN 'პარასკევი'
									WHEN DAYOFWEEK(call_datetime) = 7 THEN 'შაბათი'
									END AS `date`,
									COUNT(*) AS `unanswer_count`
									FROM 		`asterisk_incomming` as gg
                                    WHERE		`gg`.disconnect_cause = 'ABANDON'
                                    AND DATE(gg.call_datetime) >= '$start_time'
                                    AND DATE(gg.call_datetime) <= '$end_time' 
									GROUP BY DAYOFWEEK(call_datetime)
									");
	
	while($row10 = mysql_fetch_assoc($res10)){
	
		$unanswer_call2[] = (float)$row10[unanswer_count];
		$date1[]		= $row10[date];
	}
}
	//------------------------------ კავშირის გაწყვეტის მიზეზი
if($_REQUEST['act']=='disconect_couse'){
$res5 =mysql_query("SELECT	COUNT(*) AS `count`,
							'აბონენტმა გათიშა' AS `cause`
                    FROM 	`asterisk_incomming`
                    WHERE	`asterisk_incomming`.disconnect_cause = 'ABANDON' 
                    AND DATE(call_datetime) >= '$start_time'
                    AND DATE(call_datetime) <= '$end_time' 

					UNION ALL

					SELECT	(COUNT(*)-COUNT(*)) AS `count`,
							'დრო ამოიწურა' AS `cause`
					FROM 	`asterisk_incomming`
                    WHERE	`asterisk_incomming`.disconnect_cause = 'ABANDON'
                    AND DATE(call_datetime) >= '$start_time'
                    AND DATE(call_datetime) <= '$end_time'
                    ");
					
		while($row5 = mysql_fetch_assoc($res5)){
			
		$answer_count3[] = (float)$row5[count];
		$cause1[]		= $row5[cause];
        }
}
//------------------------------ უპასუხო ზარები რიგის მიხედვით
if($_REQUEST['act']=='unanswer_call_queue'){
	$res6 =mysql_query("SELECT	COUNT(*) AS `count1`,
    							dst_queue AS `queue1`
    					FROM 	`asterisk_incomming`
                        WHERE	`asterisk_incomming`.disconnect_cause = 'ABANDON'
                        AND DATE(call_datetime) >= '$start_time'
                        AND DATE(call_datetime) <= '$end_time'
	                    GROUP BY(dst_queue)
                        ");

		while($row6 = mysql_fetch_assoc($res6)){
		
		$count1[] = (float)$row6[count1];
		$queue1[]		= $row6[queue1];
		}
}
//------------------------------ ნაპასუხები ზარები რიგის მიხედვით
if($_REQUEST['act']=='answer_call_queue'){
		$res7 =mysql_query("SELECT  COUNT(*) AS `count`,
                                    dst_queue	                                                        
                            FROM 	`asterisk_incomming`
                            WHERE	`asterisk_incomming`.disconnect_cause != 'ABANDON'
                            AND DATE(call_datetime) >= '$start_time'
                            AND DATE(call_datetime) <= '$end_time' 
                            AND dst_queue IN ($queue) 
                            AND dst_extension in ($agent)
							GROUP BY(dst_queue)");

		while($row7 = mysql_fetch_assoc($res7)){

		$count2[] = (float)$row7[count];
		$queue2[]		= $row7[dst_queue];
}
}
//------------------------------ უპასუხო ზარები დღეების მიხედვით
if($_REQUEST['act']=='unanswer_call_day'){
$res8 =mysql_query("SELECT 	DATE(call_datetime) AS `datetime`,
						COUNT(*) AS `unanswer_call`
						FROM 		`asterisk_incomming`
                        WHERE		`asterisk_incomming`.disconnect_cause = 'ABANDON' 
                        AND DATE(call_datetime) >= '$start_time'
                        AND DATE(call_datetime) <= '$end_time' 
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
						FROM 		`asterisk_incomming`
                        WHERE		`asterisk_incomming`.disconnect_cause != 'ABANDON' 
                        AND DATE(call_datetime) >= '$start_time'
                        AND DATE(call_datetime) <= '$end_time' 
                        AND dst_queue IN ($queue) 
                        AND dst_extension in ($agent)
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
									FROM 		`asterisk_incomming`
                                    WHERE		`asterisk_incomming`.disconnect_cause = 'ABANDON' 
                                    AND DATE(call_datetime) >= '$start_time'
                                    AND DATE(call_datetime) <= '$end_time' 
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
							FROM 		`asterisk_incomming`
                            WHERE		`asterisk_incomming`.disconnect_cause != 'ABANDON'
                            AND DATE(call_datetime) >= '$start_time'
                            AND DATE(call_datetime) <= '$end_time' 
                            AND dst_queue IN ($queue) 
                            AND dst_extension in ($agent)
	                        GROUP BY HOUR(call_datetime)");
	
			while($row2 = mysql_fetch_assoc($res2)){
	
			$answer_count[] = (float)$row2[answer_count];
			$datetime[] 	= $row2[times];
			}
}
			//------------------------------- მომსახურების დონე(Service Level)
			
			
if($_REQUEST['act']=='answer_call_sec'){			
			$res_service_level = mysql_query("	
                            			    SELECT  `wait_time`                                				
                                            FROM 	`asterisk_incomming`
                                            WHERE	`asterisk_incomming`.disconnect_cause != 'ABANDON' 
                                            AND DATE(call_datetime) >= '$start_time'
                                            AND DATE(call_datetime) <= '$end_time' 
                                            AND dst_queue IN ($queue) 
                                            AND dst_extension in ($agent)
					                       ");
			$w15 = 0;
			$w30 = 0;
			$w45 = 0;
			$w60 = 0;
			$w75 = 0;
			$w90 = 0;
			$w91 = 0;
			
			
			
			
			while ($res_service_level_r = mysql_fetch_assoc($res_service_level)) {
			
			if ($res_service_level_r['wait_time'] < 15) {
			$w15++;
			}
			
			if ($res_service_level_r['wait_time'] < 30){
			$w30++;
			}
			
		    if ($res_service_level_r['wait_time'] < 45){
					$w45++;
			}
			
			if ($res_service_level_r['wait_time'] < 60){
			$w60++;
			}
			
			if ($res_service_level_r['wait_time'] < 75){
			$w75++;
			}
			
			if ($res_service_level_r['wait_time'] < 90){
			$w90++;
			}
			
			$w91++;
			
			}

			$d30 = $w30 - $w15;
			$d45 = $w45 - $w30;
			$d60 = $w60 - $w45;
			$d75 = $w75 - $w60;
			$d90 = $w90 - $w75;
			$d91 = $w91 - $w90;
			
$mas = array($w15,$d30,$d45,$d60,$d75,$d90,$d91);
$call_second=array('15 წამში','30 წამში','45წამში','60 წამში','75 წამში','90 წამში','90+წამში');			
}
							
$unit[]="  ზარი";
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