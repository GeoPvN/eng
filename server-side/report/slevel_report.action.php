<?php
require_once('../../includes/classes/core.php');
$done         = $_REQUEST['done'];
$start_time   = $_REQUEST['start'];
$end_time     = $_REQUEST['end'];
$procent      = $_REQUEST['procent'];
$number       = $_REQUEST['number'];
$day          = $_REQUEST['day'];
$users        = $_REQUEST['users'];
if($users == 0){
    $users_query = "";
}else{
    $users_query = "AND dst_extension = '$users'";
}
//------------------------------------------------query-------------------------------------------

    if($done == 1){
	$result = mysql_query("	SELECT    'SL-Actual' AS `status`,
                    	              DATE(asterisk_incomming.call_datetime) AS `date`,
                    	              ROUND((SUM(IF(asterisk_incomming.wait_time<$number, 1, 0)) / COUNT(*) ) * 100, 2) AS `percent`,
	                                  COUNT(asterisk_incomming.wait_time ) AS `num`
                    	    FROM      `asterisk_incomming`
                    	    WHERE     DATE(asterisk_incomming.call_datetime) BETWEEN '$start_time' AND '$end_time' AND asterisk_incomming.disconnect_cause != 'ABANDON'  $users_query
                    	    GROUP BY  DATE(asterisk_incomming.call_datetime)");
	
	$result1 = mysql_query(" SELECT    'SL-Actual' AS `status`,
                    	              DATE(asterisk_incomming.call_datetime) AS `date`,
                    	              ROUND((SUM(IF(asterisk_incomming.wait_time<$number, 1, 0)) / COUNT(*) ) * 100, 2) AS `percent`,
	                                  COUNT(asterisk_incomming.wait_time) AS `num`
                    	    FROM      `asterisk_incomming`
                    	    WHERE     DATE(asterisk_incomming.call_datetime) BETWEEN '$start_time' AND '$end_time' AND asterisk_incomming.disconnect_cause != 'ABANDON'  $users_query
                    	    GROUP BY  DATE(asterisk_incomming.call_datetime)");
	
	$result2 = mysql_query("SELECT    'SL-Actual' AS `status`,
                    	              DATE(asterisk_incomming.call_datetime) AS `date`,
                    	              ROUND((SUM(IF(asterisk_incomming.wait_time<$number, 1, 0)) / COUNT(*) ) * 100, 2) AS `percent`,
	                                  COUNT(asterisk_incomming.wait_time ) AS `num`
                    	    FROM      `asterisk_incomming`
                    	    WHERE     DATE(asterisk_incomming.call_datetime) BETWEEN '$start_time' AND '$end_time' AND asterisk_incomming.disconnect_cause = 'ABANDON' 
                    	    GROUP BY  DATE(asterisk_incomming.call_datetime)");
	
	$all = mysql_fetch_assoc(mysql_query("SELECT      'SL-Actual' AS `status`,
                				    DATE(asterisk_incomming.call_datetime) AS `date`,
                					ROUND((SUM(IF(asterisk_incomming.wait_time<$number, 1, 0)) / COUNT(*) ) * 100, 2) AS `percent`,
                					COUNT(asterisk_incomming.wait_time ) AS `num`
                        FROM        `asterisk_incomming`
                        WHERE       DATE(asterisk_incomming.call_datetime) BETWEEN '$start_time' AND '$end_time' AND asterisk_incomming.disconnect_cause != 'ABANDON'  $users_query"));
	
    
	while ($row = mysql_fetch_assoc($result)) {
	    $name_answer[]     = $row['status'];
	    $percent_answer[] = (float)$row['percent'];
	    $count_answer[] = (float)$row['num'];
	    $date[] = $row['date'];
	    $limit_number[] = (float)$number;
	    $limit_percent[] = (float)$procent;
	}
	
	while ($row = mysql_fetch_assoc($result1)) {
	    $name_unanswer[]     = $row['status'];
	    $percent_unanswer[] = (float)$row['percent'];
	    $count_unanswer[] = (float)$row['num'];
	    $date[] = $row['date'];
	
	}
	
	while ($row = mysql_fetch_assoc($result2)) {
	    $unanswer[] = (float)$row['num'];
	    $date[] = $row['date'];
	
	}
    
    
    }elseif($done == 2){
	$result = mysql_query(" SELECT   'SL-Actual' AS `status`,
                	                 HOUR(asterisk_incomming.call_datetime) AS `hourCount`,
                    	             CONCAT(HOUR(asterisk_incomming.call_datetime), ':00') AS `hour`,
                    	             ROUND((SUM(IF(asterisk_incomming.wait_time<$number, 1, 0)) / COUNT(*) ) * 100, 2) AS `percent`,
	                                 COUNT(asterisk_incomming.wait_time) as `total`
                    	    FROM     `asterisk_incomming`
                    	    WHERE    DATE(asterisk_incomming.call_datetime) = '$day' AND asterisk_incomming.disconnect_cause != 'ABANDON'  $users_query
                    	    GROUP BY HOUR(asterisk_incomming.call_datetime)");
	
	$result1 = mysql_query(" SELECT   'SL-Actual' AS `status`,
                            	     HOUR(asterisk_incomming.call_datetime) AS `hourCount`,
                            	     CONCAT(HOUR(asterisk_incomming.call_datetime), ':00') AS `hour`,
                            	     ROUND((SUM(IF(asterisk_incomming.wait_time<$number, 1, 0)) / COUNT(*) ) * 100, 2) AS `percent`,
                            	     COUNT(asterisk_incomming.wait_time) as `total`
                    	    FROM     `asterisk_incomming`
                    	    WHERE    DATE(asterisk_incomming.call_datetime) = '$day' AND asterisk_incomming.disconnect_cause = 'ABANDON' 
                    	    GROUP BY HOUR(asterisk_incomming.call_datetime)");


	while ($res = mysql_fetch_array($result)) {
	    $myarray[$res[1]] = $res[3];
	    $myarray1[$res[1]] = $res[4];
	}
	
	while ($res1 = mysql_fetch_array($result1)) {
	    $myarray2[$res1[1]] = $res1[3];
	    $myarray12[$res1[1]] = $res1[4];
	}
	
	for($i = 0; $i <= 23; $i++) {
	    if(array_key_exists($i,$myarray)){
	        if(strlen($i) == 1){
	           $date[] = '0'.$i.':00';
	        }else{
	           $date[] = $i.':00';
	        }
	        $percent_answer[] = (float)$myarray[$i];
	        $count_answer[] = (float)$myarray1[$i];
	    }else{
	        if(strlen($i) == 1){
	           $date[] = '0'.$i.':00';
	        }else{
	           $date[] = $i.':00';
	        }
	        $percent_answer[] = 0;
	        $count_answer[] = 0;
	    }
	    
	    if(array_key_exists($i,$myarray2)){
	        $unhour[] = (float)$myarray12[$i];
	    }else{
	        $unhour[] = 0;
	    }
	    
	    $name_answer[]     = 'SL-Actual';
	    $limit_number[] = (float)$number;
	    $limit_percent[] = (float)$procent;
	    
	}
	
    }
    
    if($done == 3){
        $result = mysql_query(" SELECT  CONCAT(HOUR(asterisk_incomming.call_datetime), IF(MINUTE(asterisk_incomming.call_datetime) >= 30, '.5', '')) *2 AS `hour`,
                                        ROUND((SUM(IF(asterisk_incomming.wait_time<$number, 1, 0)) / COUNT(*) ) * 100, 2) AS `percent`,
                                        FLOOR(UNIX_TIMESTAMP(asterisk_incomming.call_datetime) / (30 * 60)) AS time,
                                        COUNT(asterisk_incomming.wait_time) as `total`
                                FROM    `asterisk_incomming`
                                WHERE   DATE(asterisk_incomming.call_datetime) = '$day' AND asterisk_incomming.disconnect_cause != 'ABANDON'  $users_query
                                GROUP BY  time");
        
        $result1 = mysql_query(" SELECT  CONCAT(HOUR(asterisk_incomming.call_datetime), IF(MINUTE(asterisk_incomming.call_datetime) >= 30, '.5', '')) *2 AS `hour`,
                                        ROUND((SUM(IF(asterisk_incomming.wait_time<$number, 1, 0)) / COUNT(*) ) * 100, 2) AS `percent`,
                                        FLOOR(UNIX_TIMESTAMP(asterisk_incomming.call_datetime) / (30 * 60)) AS time,
                                        COUNT(asterisk_incomming.wait_time) as `total`
                                FROM    `asterisk_incomming`
                                WHERE   DATE(asterisk_incomming.call_datetime) = '$day' AND asterisk_incomming.disconnect_cause = 'ABANDON'  $users_query
                                GROUP BY  time");
        
        while ($res = mysql_fetch_array($result)) {
            $my_array[$res[0]] = $res[1];
            $myarray1[$res[0]] = $res[3];
        }
        
        while ($res1 = mysql_fetch_array($result1)) {
            $my_array2[$res1[0]] = $res1[1];
            $myarray12[$res1[0]] = $res1[3];
        }
        
        for($i = 0; $i <= 47; $i++) {
            if(array_key_exists($i,$my_array)){
                if(strlen($i/2) == 1){
                    if($i % 2 == 0) {
                        $date[] = (($i-1)/2+0.5).':00';
                    }else{
                        $date[] = (($i-1)/2).':30';
                    }
                }else{
                    if($i % 2 == 0) {
                        $date[] = (($i)/2).':00';
                    }else{
                        $date[] = (($i-1)/2).':30';
                    }
                }
                $percent_unanswer[] = (float)$my_array[$i];
                $count_answer[] = (float)$myarray1[$i];
            }else{
                if(strlen(round(($i/2))) == 1){
                    if($i % 2 == 0) { 
                        $date[] = '0'.(($i-1)/2+0.5).':00';
                    }else{
                        $date[] = '0'.(($i-1)/2).':30';
                    }
                }else{
                    if($i % 2 == 0) {
                        $date[] = (($i)/2).':00';
                    }else{
                        $date[] = (($i-1)/2).':30';
                        
                    }
                }
                $percent_unanswer[] = 0;
                $count_answer[] = 0;
            }
            
            if(array_key_exists($i,$my_array2)){
                $unmin[] = (float)$myarray12[$i];
            }else{
                $unmin[] = 0;
            }
             
            $name_unanswer[]     = 'SL-Actual';
            $limit_number[] = (float)$number;
            $limit_percent[] = (float)$procent;
             
        }
    }
    if($done == 4){
        mysql_query("   SELECT  persons.`name`,
                                ext
                        FROM    `users`
                        JOIN    persons ON users.person_id = persons.id
                        WHERE NOT ISNULL(ext)
                        ORDER BY ext ASC");
        
    }
 	

    $unit     = " %";
    
    $serie1[] = array('count_unanswer' => $count_unanswer, 'count_answer' => $count_answer, 'name_answer' => $name_answer[0], 'name_unanswer' => $name_unanswer[0], 'percent_answer' => $percent_answer, 'percent_unanswer' => $percent_unanswer, 'unit' => $unit, 'date' => $date, 'limit_number' => $limit_number, 'limit_percent' => $limit_percent,'unanswer' => $unanswer, 'unhour' => $unhour, 'unmin' => $unmin, 'all_answer' => $all[num], 'all_procent' => $all[percent] );
    
    
    echo json_encode($serie1);

?>