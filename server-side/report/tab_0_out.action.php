<?php
require_once('../../includes/classes/core.php');
header('Content-Type: application/json');
$start = $_REQUEST['start'];
$end   = $_REQUEST['end'];
$agent = $_REQUEST['agent'];
$queuet = $_REQUEST['queuet'];

$result = mysql_query("SELECT   COUNT(*) AS `count`,
                    			'ნაპასუხები' AS `cause`
                        FROM 	`asterisk_outgoing`
                        WHERE	duration > 0
                        AND DATE(call_datetime) >= '$start'
                        AND DATE(call_datetime) <= '$end'
                        AND extension in ($agent)
                        UNION ALL
                        SELECT 	COUNT(*) AS `count`,
                        		'უპასუხო' AS `cause`
                        FROM 	`asterisk_outgoing`
                        WHERE	duration = 0
                        AND DATE(call_datetime) >= '$start'
                        AND DATE(call_datetime) <= '$end'
                        AND extension in ($agent)");


$row = array();
$rows = array();
while($r = mysql_fetch_array($result)) {
	$row[0] = $r[1].': '.$r[0];
	$row[1] = (float)$r[0];
	array_push($rows,$row);
}


echo json_encode($rows);

?>