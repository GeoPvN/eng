<?php
/* ******************************
	Incoming Tasks aJax actions
   ******************************
*/
	include('../../../includes/classes/core.php');
	$action = $_REQUEST['act'];
	$start		=	$_REQUEST['start'];
    $end		=	$_REQUEST['end'];
    $error 		= '';
    $data       = '';

	switch ($action) {
	    case 'get_list':
    	    $count	= $_REQUEST['count'];
		    $hidden	= $_REQUEST['hidden'];
    	    $person_id  =   $_REQUEST['person_id'];
    	    $password   =   $_REQUEST['password'];
   	    
    	    if($person_id==0){
    	        $checker = "";
    	    }else{
    	        $checker = "AND WA.person_id = $person_id";
    	    }

    	    $rResult = mysql_query("SELECT 		WA.id AS `id`,
                            					DATE(WA.start_date) AS `date`,
                            					user_info.`name` AS `person`,
                            					IF(ISNULL(TIMEDIFF(PWG.`end`,PWG.`start`)),'00:00:00',TIMEDIFF(PWG.`end`,PWG.`start`)),
                            					SEC_TO_TIME(
                            										IF(IF(ISNULL(TIME_TO_SEC(TIME(WA.end_date)) - TIME_TO_SEC(TIME(WA.start_date))),0,TIME_TO_SEC(TIME(WA.end_date)) - TIME_TO_SEC(TIME(WA.start_date)))=0,'00:00:00',
                            											SUM(
                            											IF(ISNULL(TIME_TO_SEC(TIME(WA.end_date)) - TIME_TO_SEC(TIME(WA.start_date))),0,TIME_TO_SEC(TIME(WA.end_date)) - TIME_TO_SEC(TIME(WA.start_date)))
                            											)
                            												-
                            											SUM(
                            											IF(ISNULL(TIME_TO_SEC(TIME(WAB.`end_date`)) - TIME_TO_SEC(TIME(WAB.`start_date`))),0,TIME_TO_SEC(TIME(WAB.`end_date`)) - TIME_TO_SEC(TIME(WAB.`start_date`)))
                            											)
                            										)
                            										),
                            					IF(ISNULL(SEC_TO_TIME(SUM(TIME_TO_SEC(PWB.`end`)) - SUM(TIME_TO_SEC(PWB.`start`)))),'00:00:00',SEC_TO_TIME(SUM(TIME_TO_SEC(PWB.`end`)) - SUM(TIME_TO_SEC(PWB.`start`)))) AS `break_time`,
                            					IF(ISNULL(SEC_TO_TIME(SUM(TIME_TO_SEC(WAB.end_date)) - SUM(TIME_TO_SEC(WAB.start_date)))),'00:00:00',SEC_TO_TIME(
                            																										IF(0>SUM(TIME_TO_SEC(IF(ISNULL(WAB.end_date) AND ISNULL(WAB.start_date),0,WAB.end_date)))
                            																										-
                            																										SUM(TIME_TO_SEC(IF(ISNULL(WAB.end_date) AND ISNULL(WAB.start_date),0,WAB.start_date))),0,
                            																										SUM(TIME_TO_SEC(IF(ISNULL(WAB.end_date) AND ISNULL(WAB.start_date),0,WAB.end_date)))
                            																										-
                            																										SUM(TIME_TO_SEC(IF(ISNULL(WAB.end_date) AND ISNULL(WAB.start_date),0,WAB.start_date)))
                            																										)
                            																										)) AS `real_break_time`
                        			FROM 		`worker_action` AS `WA`
                        			JOIN       	user_info ON WA.person_id = user_info.user_id
                        			LEFT JOIN   worker_action_break AS `WAB` ON WA.id = WAB.worker_action_id
                        			LEFT JOIN		person_work_graphic AS PWG ON DATE(WA.start_date) = DATE(PWG.`start`) AND WA.person_id = PWG.person_id AND PWG.actived = 1
                        			LEFT JOIN		work_graphic_break AS PWB ON PWB.wg_id = PWG.wg_id
									WHERE       DATE(WA.start_date) >= '$start' and DATE(WA.start_date) <= '$end' $checker
									GROUP BY 	DATE(WA.start_date) , WA.person_id
									ORDER BY   	WA.start_date
    	    						");
												

			
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
			
			echo json_encode( $data );


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