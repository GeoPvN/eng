<?php
/* ******************************
	Incoming Tasks aJax actions
   ******************************
*/
	include('../../../includes/classes/core.php');
	
	$action  = $_REQUEST['act'];
	$start	 = $_REQUEST['start'];
    $end	 = $_REQUEST['end'];
    $user_id = $_SESSION[USERID];
    $error 	 = '';
    $data    = '';

	switch ($action) {
	    case 'get_list':
    	    $count = $_REQUEST['count'];
    	    $person_id  =   $_REQUEST['person_id'];
    	    $password   =   $_REQUEST['password'];
    	    
    	    $check_group = mysql_fetch_assoc(mysql_query("SELECT group_id 
                                                          FROM  `users`
                                                          WHERE  id = $user_id"));
    	    
    	    $check_user = '';
    	    
    	    if ($check_group[group_id] != 1 && $check_group[group_id] != 3 && $check_group[group_id] != 5) {
    	        $check_user = "AND users.id = $user_id";
    	    }
   	    
    	    if($person_id==0){
    	        $checker = "";
    	    }else{
    	        $checker = "AND users.group_id = $person_id";
    	    }

    	   $rResult = mysql_query(" SELECT 		WA.id AS `id`,
                                                DATE(WA.start_date) AS `date`,
                                                user_info.`name` AS `person`,
    	                                        `group`.`name`,
                                                IF(ISNULL(TIMEDIFF(work_shift.`end_date`,work_shift.`start_date`)),'00:00:00',TIMEDIFF(TIMEDIFF(work_shift.`end_date`,work_shift.`start_date`),TIMEDIFF(work_real_break.`end_break`,work_real_break.`start_break`))) AS `r`,
                                                SEC_TO_TIME(SUM(IF(TIME_TO_SEC(IF(TIME(IFNULL(WA.end_date,'00:00:00'))=0,TIME(NOW()),TIME(WA.end_date)))< TIME_TO_SEC(TIME(WA.start_date)),((24*60*60)-(TIME_TO_SEC(TIME(WA.start_date))))+TIME_TO_SEC(IF(TIME(IFNULL(WA.end_date,'00:00:00'))=0,TIME(NOW()),TIME(WA.end_date))),TIME_TO_SEC(IF(TIME(IFNULL(WA.end_date,'00:00:00'))=0,TIME(NOW()),TIME(WA.end_date)))-TIME_TO_SEC(TIME(WA.start_date))))) AS `real_work`,
                                                IF(ISNULL(TIMEDIFF(work_real_break.`end_break`,work_real_break.`start_break`)),'00:00:00',TIMEDIFF(work_real_break.`end_break`,work_real_break.`start_break`)) AS `r2`,
                                                IFNULL(SEC_TO_TIME(SUM(IF(TIME_TO_SEC(IF(TIME(IFNULL(WAB.end_date,'00:00:00'))=0,TIME(NOW()),TIME(WAB.end_date)))< TIME_TO_SEC(TIME(WAB.start_date)),((24*60*60)-(TIME_TO_SEC(TIME(WAB.start_date))))+TIME_TO_SEC(IF(TIME(IFNULL(WAB.end_date,'00:00:00'))=0,TIME(NOW()),TIME(WAB.end_date))),TIME_TO_SEC(IF(TIME(IFNULL(WAB.end_date,'00:00:00'))=0,TIME(NOW()),TIME(WAB.end_date)))-TIME_TO_SEC(TIME(WAB.start_date))))),'00:00:00') AS `real_break_time`
                                    FROM 		`worker_action` AS `WA`
    	                            JOIN       	users ON users.id = WA.person_id
                                    JOIN       	user_info ON user_info.user_id = WA.person_id
    	                            JOIN        `group` ON users.group_id = `group`.id
                                    LEFT JOIN   worker_action_break AS `WAB` ON WA.id = WAB.worker_action_id
                                    LEFT JOIN   work_real ON DATE(WA.start_date) = DATE(work_real.date) AND users.id = work_real.user_id
                                    LEFT JOIN   work_real_break ON work_real.id = work_real_break.work_real_id
                                    LEFT JOIN   work_shift ON work_real.work_shift_id = work_shift.id
									WHERE       DATE(WA.start_date) >= '$start' and DATE(WA.start_date) <= '$end' AND users.group_id!=5 AND users.id !=1 $checker $check_user
									GROUP BY 	DATE(WA.start_date) , WA.person_id
									ORDER BY   	WA.start_date");
												
			$output = array("aaData"	=> array());
			
			while ( $aRow = mysql_fetch_array( $rResult ) ){
			    
				$row = array();
				for ( $i = 0 ; $i < $count ; $i++ )
				{
					/* General output */
					$row[] = $aRow[$i];
				}
				$output['aaData'][] = $row;
			}
			
			echo json_encode( $output );


	        break;
        case 'get_list_deep':
            $count = $_REQUEST['count'];
            $id    = $_REQUEST['id'];
            
            $getId = mysql_fetch_array(mysql_query("SELECT 	person_id,DATE(start_date)
                                                    FROM 	`worker_action`
                                                    WHERE 	id = $id"));
        
            $rResult = mysql_query("    SELECT 	worker_action.`id`,TIME(`worker_action`.`start_date`),IF(TIME(`worker_action`.`start_date`) < TIME(PWG.`start`),TIME(PWG.`start`),TIME(`worker_action`.`start_date`))
                                        FROM 	`worker_action`
                                        LEFT JOIN person_work_graphic AS PWG ON DATE(worker_action.start_date) = DATE(PWG.`start`) AND worker_action.person_id = PWG.person_id AND PWG.actived = 1
                                        WHERE 	worker_action.`person_id` = $getId[0] AND DATE(worker_action.start_date) = '$getId[1]'
                                        GROUP BY worker_action.`id`
                                        ");
        
            $output = array(
                "aaData"	=> array()
            );
            	
            while ( $aRow = mysql_fetch_array( $rResult ) )
            {
                $row = array();
                $row2 = array();
                for ( $i = 0 ; $i < $count ; $i++ )
                {
                    /* General output */
                    $row[] = $aRow[$i];
                }
                $output['aaData'][] = $row;
                
                $rResult1 = mysql_query("    SELECT 	$aRow[0] as id,'','',worker_action_break.start_date,
                                            		worker_action_break.end_date,'','',CONCAT(`comment_start`,' ',`comment_end`)
                                             FROM 	`worker_action_break`
                                             WHERE 	worker_action_id = $aRow[0]
                                        ");
                while ( $aRow1 = mysql_fetch_array( $rResult1 ) )
                {
                    $row1 = array();
                    for ( $i = 0 ; $i < $count ; $i++ )
                    {
                        /* General output */
                        $row1[] = $aRow1[$i];
                    }
                    $output['aaData'][] = $row1;
                }
                $aRow2 = mysql_fetch_array($rResult2 = mysql_query("    SELECT 	worker_action.id,'','','','',IF(TIME(`worker_action`.`end_date`) > TIME(PWG.`end`),TIME(PWG.`end`),TIME(`worker_action`.`end_date`)),
                                                                                TIME(worker_action.end_date)
                                                                        FROM 	`worker_action`
                                                                        LEFT JOIN person_work_graphic AS PWG ON DATE(worker_action.start_date) = DATE(PWG.`start`) AND worker_action.person_id = PWG.person_id AND PWG.actived = 1
                                                                        WHERE 	worker_action.id =$aRow[0]
                                                                        "));
                for ( $i = 0 ; $i < $count ; $i++ )
                {
                    /* General output */
                    $row2[] = $aRow2[$i];
                }
                $output['aaData'][] = $row2;
            }
            	
            echo json_encode( $output );
        
        
            break;
	    default:
	       	echo "null";
	}
	
?>