<?php
require_once('../../includes/classes/core.php');
header('Content-Type: application/json');
$start = $_REQUEST['start'];
$end   = $_REQUEST['end'];
$agent = $_REQUEST['agent'];
$queuet = $_REQUEST['queuet'];

$row_done_blank = mysql_query(" SELECT  COUNT(incomming_call.id) as `count`,
                                        CONCAT('ნაპასუხბი',' ',COUNT(*)) AS `cause`
                                FROM 	incomming_call
                                JOIN    asterisk_incomming ON incomming_call.asterisk_incomming_id = asterisk_incomming.id
                                WHERE   DATE(incomming_call.date) >= '$start'
                                AND     DATE(incomming_call.date) <= '$end'
                                AND     asterisk_incomming.dst_queue IN ($queuet) 
                                AND     asterisk_incomming.dst_extension IN ($agent)
                                AND     incomming_call.phone != '' AND incomming_call.user_id > 0");



    


$result = mysql_query("SELECT   COUNT(*) AS `count`,
                    			CONCAT('ნაპასუხბი',' ',COUNT(*)) AS `cause`
                        FROM 	`asterisk_incomming`
                        WHERE	disconnect_cause != 'ABANDON' 
                        AND DATE(call_datetime) >= '$start'
                        AND DATE(call_datetime) <= '$end' 
                        AND dst_queue IN ($queuet) 
                        AND dst_extension in ($agent)");
					
$row = array();
$row1 = array();
$rows = array();
while($r = mysql_fetch_array($result)) {
    $r1 = mysql_fetch_array($row_done_blank);
	$row[0] = 'დაუმუშავებელი: '.($r[0] - $r1[0]);
	$row[1] = (float)($r[0] - $r1[0]);
	$row1[0] = 'დამუშავებული: '.$r1[0];
	$row1[1] = (float)$r1[0];
	array_push($rows,$row);
	array_push($rows,$row1);
}



echo json_encode($rows);

?>