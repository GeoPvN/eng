<?php
include('../../includes/classes/core.php');

if($_REQUEST['act'] == 'get_list'){
    $count = 		$_REQUEST['count'];
    $hidden = 		$_REQUEST['hidden'];
    $start =        $_REQUEST['start'];
    $end =          $_REQUEST['end'];
    $rResult = mysql_query("SELECT user_info.`name` AS `oper`,
                            user_info.`name` AS `oper`,
                            COUNT(outgoing_campaign_detail.id) as `total_call`,
                            ROUND((COUNT(outgoing_campaign_detail.id) / ( 	SELECT COUNT(outgoing_campaign_detail.id) 
                            																	FROM outgoing_campaign_detail
                            																	WHERE outgoing_campaign_detail.`status` IN(4,5,6)
                            																	AND DATE(outgoing_campaign_detail.update_date) >= '$start'
                            																	AND DATE(outgoing_campaign_detail.update_date) <= '$end' ) *100),2) AS `total_call_proc`,
                            ( SELECT COUNT(not_in.id) as `done_call`
                            	FROM outgoing_campaign_detail AS `not_in`
                            	WHERE outgoing_campaign_detail.responsible_person_id = not_in.responsible_person_id
                            	AND not_in.`status` IN(4)
                            	AND DATE(not_in.update_date) >= '$start'
                            	AND DATE(not_in.update_date) <= '$end'
                            	LIMIT 1 ) AS `not_interes`,
                            ROUND(( ( SELECT COUNT(not_interes_prc.id) as `done_call`
                            					FROM outgoing_campaign_detail AS `not_interes_prc`
                            					WHERE outgoing_campaign_detail.responsible_person_id = not_interes_prc.responsible_person_id
                            					AND not_interes_prc.`status` IN(5)
                            					AND DATE(not_interes_prc.update_date) >= '$start'
                            					AND DATE(not_interes_prc.update_date) <= '$end'
                            					LIMIT 1 ) / ( SELECT COUNT(not_interes_pr.id) as `done_call`
                            												FROM outgoing_campaign_detail AS `not_interes_pr`
                            												WHERE not_interes_pr.`status` IN(4)
                            												AND DATE(not_interes_pr.update_date) >= '$start'
                            												AND DATE(not_interes_pr.update_date) <= '$end'
                            												LIMIT 1 ) *100),2) AS `not_interes_procent`,
                            ( SELECT COUNT(pot_cl.id) as `done_call`
                            	FROM outgoing_campaign_detail AS `pot_cl`
                            	WHERE outgoing_campaign_detail.responsible_person_id = pot_cl.responsible_person_id
                            	AND pot_cl.`status` IN(5)
                            	AND DATE(pot_cl.update_date) >= '$start'
                            	AND DATE(pot_cl.update_date) <= '$end'
                            	LIMIT 1 ) AS `pot_client`,
                            ROUND(( ( SELECT COUNT(pot_client_prc.id) as `done_call`
                            					FROM outgoing_campaign_detail AS `pot_client_prc`
                            					WHERE outgoing_campaign_detail.responsible_person_id = pot_client_prc.responsible_person_id
                            					AND pot_client_prc.`status` IN(5)
                            					AND DATE(pot_client_prc.update_date) >= '$start'
                            					AND DATE(pot_client_prc.update_date) <= '$end'
                            					LIMIT 1 ) / ( SELECT COUNT(pot_client_pr.id) as `done_call`
                            												FROM outgoing_campaign_detail AS `pot_client_pr`
                            												WHERE pot_client_pr.`status` IN(5)
                            												AND DATE(pot_client_pr.update_date) >= '$start'
                            												AND DATE(pot_client_pr.update_date) <= '$end'
                            												LIMIT 1 ) *100),2) AS `pot_client_procent`,
                            ( SELECT COUNT(cl.id) as `done_call`
                            	FROM outgoing_campaign_detail AS `cl`
                            	WHERE outgoing_campaign_detail.responsible_person_id = cl.responsible_person_id
                            	AND cl.`status` IN(6)
                            	AND DATE(cl.update_date) >= '$start'
                            	AND DATE(cl.update_date) <= '$end'
                            	LIMIT 1 ) AS `client`,
                            ROUND(( ( SELECT COUNT(client_prc.id) as `done_call`
                            					FROM outgoing_campaign_detail AS `client_prc`
                            					WHERE outgoing_campaign_detail.responsible_person_id = client_prc.responsible_person_id
                            					AND client_prc.`status` IN(6)
                            					AND DATE(client_prc.update_date) >= '$start'
                            					AND DATE(client_prc.update_date) <= '$end'
                            					LIMIT 1 ) / ( SELECT COUNT(client_pr.id) as `done_call`
                            												FROM outgoing_campaign_detail AS `client_pr`
                            												WHERE client_pr.`status` IN(6)
                            												AND DATE(client_pr.update_date) >= '$start'
                            												AND DATE(client_pr.update_date) <= '$end'
                            												LIMIT 1 ) *100),2) AS `client_procent`
                            FROM outgoing_campaign_detail
                            JOIN users ON outgoing_campaign_detail.responsible_person_id = users.id
                            JOIN user_info ON users.id = user_info.user_id
                            WHERE outgoing_campaign_detail.`status` IN(4,5,6)
                            AND DATE(outgoing_campaign_detail.update_date) >= '$start'
                            AND DATE(outgoing_campaign_detail.update_date) <= '$end'
                            GROUP BY outgoing_campaign_detail.responsible_person_id");
     
    $data = array(
        "aaData"	=> array()
    );
    
    while ( $aRow = mysql_fetch_array( $rResult ) )
    {
        $row = array();
        for ( $i = 0 ; $i < $count ; $i++ )
        {
            /* General output */
            $row[] = $aRow[$i];
        }
        $data['aaData'][] = $row;
    }
    
    echo json_encode($data);
}

if($_REQUEST['act'] == 'chart1'){
    header('Content-Type: application/json');
    $start = $_REQUEST['start'];
    $end   = $_REQUEST['end'];
    
    $quantity = array();
    $cause = array();
    $cause1 = array();
    
    $name = array();
    $agent = array();
    
    $ress =mysql_query("SELECT 	user_info.`name` AS `operat`,
                				COUNT(outgoing_campaign_detail.id) as `done_call`
                        FROM outgoing_campaign_detail
                        JOIN users ON outgoing_campaign_detail.responsible_person_id = users.id
                        JOIN user_info ON users.id = user_info.user_id
                        WHERE outgoing_campaign_detail.`status` IN(6)
                        AND DATE(outgoing_campaign_detail.update_date) >= '$start'
                        AND DATE(outgoing_campaign_detail.update_date) <= '$end'
                        GROUP BY outgoing_campaign_detail.responsible_person_id");
         
        while($row1 = mysql_fetch_assoc($ress)){
    
        $call_count[]   = (float)$row1[done_call];
        $agent[]		= $row1[operat];
    }
    
    $unit=" ზარი";
    $series[] = array('name' => $name, 'unit' => $unit, 'call_count' => $call_count, 'agent' => $agent);
    
    echo json_encode($series);
}

if($_REQUEST['act'] == 'chart2'){
    header('Content-Type: application/json');
    $start = $_REQUEST['start'];
    $end   = $_REQUEST['end'];

    $quantity = array();
    $cause = array();
    $cause1 = array();

    $name = array();
    $agent = array();

    $ress =mysql_query("SELECT 	user_info.`name` AS `operat`,
                				COUNT(outgoing_campaign_detail.id) as `done_call`
                        FROM outgoing_campaign_detail
                        JOIN users ON outgoing_campaign_detail.responsible_person_id = users.id
                        JOIN user_info ON users.id = user_info.user_id
                        WHERE outgoing_campaign_detail.`status` IN(5)
                        AND DATE(outgoing_campaign_detail.update_date) >= '$start'
                        AND DATE(outgoing_campaign_detail.update_date) <= '$end'
                        GROUP BY outgoing_campaign_detail.responsible_person_id");
         
        while($row1 = mysql_fetch_assoc($ress)){

        $call_count[]   = (float)$row1[done_call];
        $agent[]		= $row1[operat];
        }

        $unit=" ზარი";
        $series[] = array('name' => $name, 'unit' => $unit, 'call_count' => $call_count, 'agent' => $agent);

            echo json_encode($series);
}

if($_REQUEST['act'] == 'chart3'){
    header('Content-Type: application/json');
    $start = $_REQUEST['start'];
    $end   = $_REQUEST['end'];

    $quantity = array();
    $cause = array();
    $cause1 = array();

    $name = array();
    $agent = array();

    $ress =mysql_query("SELECT 	user_info.`name` AS `operat`,
                                COUNT(outgoing_campaign_detail.id) as `done_call`
                        FROM outgoing_campaign_detail
                        JOIN users ON outgoing_campaign_detail.responsible_person_id = users.id
                        JOIN user_info ON users.id = user_info.user_id
                        WHERE outgoing_campaign_detail.`status` IN(4)
                        AND DATE(outgoing_campaign_detail.update_date) >= '$start'
                        AND DATE(outgoing_campaign_detail.update_date) <= '$end'
                        GROUP BY outgoing_campaign_detail.responsible_person_id");
     
    while($row1 = mysql_fetch_assoc($ress)){

        $call_count[]   = (float)$row1[done_call];
        $agent[]		= $row1[operat];
    }

    $unit=" ზარი";
    $series[] = array('name' => $name, 'unit' => $unit, 'call_count' => $call_count, 'agent' => $agent);

    echo json_encode($series);
}

if($_REQUEST['act'] == 'chart'){
    header('Content-Type: application/json');
    $start = $_REQUEST['start'];
    $end   = $_REQUEST['end'];

    $quantity = array();
    $cause = array();
    $cause1 = array();

    $name = array();
    $agent = array();

    $ress =mysql_query("SELECT 	user_info.`name` AS `operat`,
                				COUNT(outgoing_campaign_detail.id) as `done_call`
                        FROM outgoing_campaign_detail
                        JOIN users ON outgoing_campaign_detail.responsible_person_id = users.id
                        JOIN user_info ON users.id = user_info.user_id
                        WHERE outgoing_campaign_detail.`status` IN(4,5,6)
                        AND DATE(outgoing_campaign_detail.update_date) >= '$start'
                        AND DATE(outgoing_campaign_detail.update_date) <= '$end'
                        GROUP BY outgoing_campaign_detail.responsible_person_id");
     
    while($row1 = mysql_fetch_assoc($ress)){

        $call_count[]   = (float)$row1[done_call];
        $agent[]		= $row1[operat];
    }

    $unit[]=" ზარი";
    $series[] = array('name' => $name, 'unit' => $unit, 'call_count' => $call_count, 'agent' => $agent);

    echo json_encode($series);
}
?>