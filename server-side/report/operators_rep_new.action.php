<?php
require_once('../../includes/classes/core.php');
//asteriskcdrdb

header('Content-Type: application/json');
$start = $_REQUEST['start'];
$end   = $_REQUEST['end'];
$agentt = $_REQUEST['agent'];
$queuet = $_REQUEST['queuet'];

$quantity = array();
$cause = array();
$cause1 = array();

$name = array();
$agent = array();

$call_count = array();

$name[]     = '';


	$ress =mysql_query("SELECT   user_info.`name` AS `agent`,
                                 COUNT(*) AS `num`
                        FROM 	 asterisk_incomming
                        JOIN     users ON asterisk_incomming.user_id = users.id
                        JOIN     user_info ON users.id=user_info.user_id
                        WHERE 	 user_info.`name` in ($agentt) AND `asterisk_incomming`.`call_datetime` BETWEEN '$start' AND '$end'
                        GROUP BY user_info.`name`");
			
while($row1 = mysql_fetch_assoc($ress)){

	$call_count[] = (float)$row1[num];
	$agent[]		= $row1[agent];
}

							
$unit[]="  ზარი";
$series[] = array('name' => $name, 'unit' => $unit, 'quantity' => $quantity, 'cause' => $cause);
$series[] = array('name' => $name, 'unit' => $unit, 'call_count' => $call_count, 'agent' => $agent);

echo json_encode($series);

?>