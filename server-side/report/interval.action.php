<?php
include('../../includes/classes/core.php');

header('Content-Type: application/json');
$start = $_REQUEST['start'];
$end   = $_REQUEST['end'];
$agentt = $_REQUEST['agent'];
$queuet = $_REQUEST['queuet'];
if($_REQUEST['act'] == 'total_sec'){
    $total_sec = mysql_fetch_array(mysql_query("SELECT  SEC_TO_TIME(SUM(ABS((
                                        				SELECT(unix_timestamp(`asterisk_outgoing`.`call_datetime`) + (`asterisk_outgoing`.`wait_time` + `asterisk_outgoing`.`duration`)) AS `end_date1`
                                        				FROM  `asterisk_outgoing`
                                        				WHERE  asterisk_outgoing.extension = outgoing.extension AND asterisk_outgoing.call_datetime > outgoing.call_datetime AND DATE(asterisk_outgoing.call_datetime) >= '$start' AND DATE(asterisk_outgoing.call_datetime) <= '$end' AND asterisk_outgoing.extension = '$agentt' AND TIME(asterisk_outgoing.call_datetime) >= '09:00:00' AND TIME(asterisk_outgoing.call_datetime) <= '22:00:00'
                                        				LIMIT 1
                                        				) - (unix_timestamp(`outgoing`.`call_datetime`) + (`outgoing`.`wait_time` + `outgoing`.`duration`))))) AS `dro`
                                                FROM `asterisk_outgoing` AS outgoing
                                                WHERE DATE(outgoing.call_datetime) >= '$start' AND DATE(outgoing.call_datetime) <= '$end' AND outgoing.extension = '$agentt' AND TIME(outgoing.call_datetime) >= '09:00:00' AND TIME(outgoing.call_datetime) <= '22:00:00'
                                                ORDER BY outgoing.call_datetime"));
    
    $total_talk_sec = mysql_fetch_array(mysql_query("SELECT SEC_TO_TIME(SUM(asterisk_outgoing.duration))
                                                     FROM asterisk_outgoing
                                                     WHERE DATE(asterisk_outgoing.call_datetime) >= '$start'
                                                     AND DATE(asterisk_outgoing.call_datetime) <= '$end'
                                                     AND asterisk_outgoing.extension = '$agentt'
                                                     AND TIME(asterisk_outgoing.call_datetime) >= '09:00:00' AND TIME(asterisk_outgoing.call_datetime) <= '22:00:00'"));

        $sec = array("sec"	=> array(),"talk" => array());
        $sec['sec'][] = $total_sec[0];
        $sec['talk'][] = $total_talk_sec[0];
        echo json_encode($sec);
}elseif ($_REQUEST['act']=='getusers'){
    $res = mysql_query("SELECT users.extension_id AS `ext`,
                        user_info.`name` AS `fnameLname`,
                        CONCAT(user_info.`name`, ' (', users.extension_id, ')') AS `FLnameExt`
                        FROM `users`
                        JOIN user_info ON users.id = user_info.user_id
                        WHERE users.group_id != 1 AND users.actived = 1");
    $data = '';
    while ($req = mysql_fetch_assoc($res)){
        $data .= '<option value="'.$req['ext'].'">'.$req['FLnameExt'].'</option>';
    }
    echo json_encode($data);
}else{
$quantity = array();
$cause = array();
$cause1 = array();

$name = array();
$agent = array();

$ress =mysql_query("SELECT   `outgoing`.`call_datetime` AS `calldate`, 
                    				(SELECT FROM_UNIXTIME(unix_timestamp(`asterisk_outgoing`.`call_datetime`) + (`asterisk_outgoing`.`wait_time` + `asterisk_outgoing`.`duration`)) AS `end_date1`
                    			FROM  `asterisk_outgoing`
                    			WHERE  asterisk_outgoing.extension = outgoing.extension AND asterisk_outgoing.call_datetime > outgoing.call_datetime AND DATE(asterisk_outgoing.call_datetime) >= '$start' AND DATE(asterisk_outgoing.call_datetime) <= '$end' AND asterisk_outgoing.extension = '$agentt' AND TIME(asterisk_outgoing.call_datetime) >= '09:00:00' AND TIME(asterisk_outgoing.call_datetime) <= '22:00:00'
                    			LIMIT 1) AS `end`,         	
                    			ABS((
                    			SELECT(unix_timestamp(`asterisk_outgoing`.`call_datetime`) + (`asterisk_outgoing`.`wait_time` + `asterisk_outgoing`.`duration`)) AS `end_date1`
                    			FROM  `asterisk_outgoing`
                    			WHERE  asterisk_outgoing.extension = outgoing.extension AND asterisk_outgoing.call_datetime > outgoing.call_datetime AND DATE(asterisk_outgoing.call_datetime) >= '$start' AND DATE(asterisk_outgoing.call_datetime) <= '$end' AND asterisk_outgoing.extension = '$agentt' AND TIME(asterisk_outgoing.call_datetime) >= '09:00:00' AND TIME(asterisk_outgoing.call_datetime) <= '22:00:00'
                    			LIMIT 1
                    			) - (unix_timestamp(`outgoing`.`call_datetime`) + (`outgoing`.`wait_time` + `outgoing`.`duration`))) AS `dro`
                    FROM `asterisk_outgoing` AS outgoing
                    WHERE DATE(outgoing.call_datetime) >= '$start' AND DATE(outgoing.call_datetime) <= '$end' AND outgoing.extension = '$agentt' AND TIME(outgoing.call_datetime) >= '09:00:00' AND TIME(outgoing.call_datetime) <= '22:00:00'
                    ORDER BY outgoing.call_datetime
                    ");
    	
    while($row1 = mysql_fetch_assoc($ress)){

    $call_count[]   = (float)$row1[dro];
    $agent[]		= $row1[calldate].' - '. $row1[end];
}

$unit[]=" წამი";
$series[] = array('name' => $name, 'unit' => $unit, 'call_count' => $call_count, 'agent' => $agent);

echo json_encode($series);

}

?>