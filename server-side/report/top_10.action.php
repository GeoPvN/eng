<?php
include('../../includes/classes/core.php');
$start  	= $_REQUEST['start'];
$end    	= $_REQUEST['end'];

$res = mysql_query("SELECT  `service_center`.`name`,
                            COUNT(`ic`.`id`) AS `sc_count`,
                            (
                            SELECT COUNT(`incomming_call`.`id`) AS `sc_count`
                            FROM 	`incomming_call`
                            JOIN `personal_info` AS ww ON incomming_call.id = ww.incomming_call_id
                            WHERE ww.service_center_id = personal_info.service_center_id AND incomming_call.cat_1 = 11 AND DATE(incomming_call.date) >= '$start' AND DATE(incomming_call.date) <= '$end'
                            ) AS `teqnik`,
                            (
                            SELECT COUNT(`incomming_call`.`id`) AS `sc_count`
                            FROM 	`incomming_call`
                            JOIN `personal_info` AS ww ON incomming_call.id = ww.incomming_call_id
                            WHERE ww.service_center_id = personal_info.service_center_id AND incomming_call.cat_1 != 11 AND DATE(incomming_call.date) >= '$start' AND DATE(incomming_call.date) <= '$end'
                            ) AS `no_teqn`
                    FROM 	`service_center`
                    LEFT JOIN `personal_info` ON service_center.id = personal_info.service_center_id
                    LEFT JOIN `incomming_call` AS `ic` ON personal_info.incomming_call_id = ic.id AND DATE(ic.date) >= '$start' AND DATE(ic.date) <= '$end'
                    WHERE parent_id = 0
                    GROUP BY service_center.id
                    ORDER BY COUNT(ic.id) DESC");

$data = array(
    "dc"	=> array()
);

while ($req = mysql_fetch_array($res)){
    $data[dc][] = '<tr>
                        <th>'.$req[0].'</th>
                        <td>'.$req[1].'</td>
                        <td data-sparkline="0, 0, 0, '.$req[1].' "/>
                        <td>'.$req[2].'</td>
                        <td data-sparkline="0, 0, 0, '.$req[2].' "/>
                        <td>'.$req[3].'</td>
                        <td data-sparkline="0, 0, 0, '.$req[3].' "/>
                    </tr>';
}
                    
echo json_encode($data);

?>