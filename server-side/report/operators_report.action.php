<?php
require_once('../../includes/classes/core.php');

//----------------------------- ცვლადი

$agent	= $_REQUEST['agent'];
$name	= $_REQUEST['name'];
$queue	= $_REQUEST['queuet'];
$start_time = $_REQUEST['start_time'];
$end_time 	= $_REQUEST['end_time'];

if($_REQUEST['act'] =='check'){

		
//--------------------------- ნაპასუხები ზარები ოპერატორების მიხედვით

 	$ress =mysql_query("SELECT 	  pers.`name`,
            					  '' AS gegm_dro,
            					  COUNT(*) AS raod,
            					  SUM(IF(NOT ISNULL(`inc`.`inc_status_id`),1,0)) AS damushavebuli,
            					  SUM(IF(ISNULL(inc.inc_status_id) AND asterisk_incomming.disconnect_cause != 'ABANDON',1,0)) AS daumushavebeli,
            					  ROUND(COUNT(*)/(SELECT COUNT(*) 
												  FROM  asterisk_incomming AS ast
 	                                              JOIN  incomming_call ON incomming_call.asterisk_incomming_id = ast.id
												  JOIN  user_info ON user_info.user_id = ast.user_id
												  WHERE ast.disconnect_cause != 'ABANDON'
												  AND   user_info.`name` IN($agent)
												  AND   DATE(`ast`.`call_datetime`) BETWEEN '$start_time' AND '$end_time'
 	                                              AND   asterisk_incomming.duration>0
												  )*100,2) AS percent,
            					  SEC_TO_TIME(SUM(asterisk_incomming.duration)) AS dur,
            					  SEC_TO_TIME(AVG(asterisk_incomming.duration)) AS avgdur,
            					  SEC_TO_TIME(MAX(asterisk_incomming.duration)) AS maxdur,
            					  SEC_TO_TIME(MIN(asterisk_incomming.duration)) AS mindur,
            					  ROUND((SUM(IF(NOT ISNULL(`inc`.`inc_status_id`),1,0)))/COUNT(*),2) AS coeficienti,
            					  '0' AS task_count,
            					  '0' AS out_count
                        FROM      asterisk_incomming
                        JOIN      incomming_call AS inc ON inc.asterisk_incomming_id = asterisk_incomming.id
                        LEFT JOIN users ON asterisk_incomming.user_id = users.id
                        JOIN      user_info AS pers  ON pers.user_id = users .id
                        WHERE     pers.`name` IN($agent)
                        AND       DATE(`asterisk_incomming`.`call_datetime`) BETWEEN '$start_time' AND '$end_time'
                        AND       asterisk_incomming.duration>0
                        GROUP BY  pers.`name`
		 	  ");

while($row = mysql_fetch_assoc($ress)){
	
	$data['page']['answer_call_by_queue'] .= '

                   	<tr>
					<td style="cursor:pointer;" id="name">'.$row[name].'</td>
					<td>'.$row[gegm_dro].'</td>
					<td id="answear_dialog" style="cursor: pointer; text-decoration: underline;" user="'.$row[name].'">'.$row[raod].'</td>
					<td>'.$row[damushavebuli].'</td>
					<td id="undone_dialog" style="cursor: pointer; text-decoration: underline;" user1="'.$row[name].'">'.$row[daumushavebeli].'</td>
					<td>'.$row[percent].'%</td>
					<td>'.$row[dur].'</td>
					<td>'.$row[avgdur].'</td>
					<td>'.$row[maxdur].'</td>
					<td>'.$row[mindur].'</td>
					<td>'.$row[coeficienti].'</td>
					<td>'.$row[task_count].'</td>
					<!--<td>'.$row[shes_time].'</td>
					<td id="answear_dialog1" style="cursor: pointer; text-decoration: underline;" user2="'.$row[name].'"></td>-->
					<td>'.$row[out_count].'</td>
					<!--<td id="undone_dialog1" style="cursor: pointer; text-decoration: underline;" user3="'.$row[name].'">'.$row[daum_gamavali].' ზარი</td>-->
					</tr>';

}

}else if($_REQUEST['act'] =='answear_dialog_table'){
$data		= array('page' => array(
			'answear_dialog' => ''
	));
	$count = 		$_REQUEST['count'];
	$hidden = 		$_REQUEST['hidden'];
	$rResult = mysql_query("SELECT 	  asterisk_incomming.call_datetime,
	                                  asterisk_incomming.call_datetime,
                					  asterisk_incomming.source,
                					  asterisk_incomming.dst_queue,
                					  user_info.`name`,
                                      SEC_TO_TIME(asterisk_incomming.duration),
                            					CONCAT('<p onclick=play(', '\'',DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\'',  ')>Listen</p>', '<a download=\"audio.wav\" href=\"http://212.72.155.176:8000/', DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\">Download</a>') AS `file`
                            FROM 	  asterisk_incomming
                            JOIN   	  user_info ON asterisk_incomming.user_id = user_info.user_id
                            JOIN      incomming_call ON incomming_call.asterisk_incomming_id = asterisk_incomming.id       
                            WHERE 	  user_info.`name` in ('$name') AND DATE(`asterisk_incomming`.`call_datetime`) BETWEEN '$start_time' AND '$end_time' AND  asterisk_incomming.duration>0
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
	
	//------------------------------//
		
}elseif($_REQUEST['act'] =='undone_dialog_table'){
    $data		= array('page' => array(
        'answear_dialog' => ''
    ));
    $count = 		$_REQUEST['count'];
    $hidden = 		$_REQUEST['hidden'];
    
   $rResult = mysql_query("SELECT 	  asterisk_incomming.call_datetime,
	                                  asterisk_incomming.call_datetime,
                					  asterisk_incomming.source,
                					  asterisk_incomming.dst_queue,
                					  user_info.`name`,
                                      SEC_TO_TIME(asterisk_incomming.duration),
                            					CONCAT('<p onclick=play(', '\'',DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\'',  ')>Listen</p>', '<a download=\"audio.wav\" href=\"http://212.72.155.176:8000/', DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\">Download</a>') AS `file`
                            FROM 	  asterisk_incomming
                            JOIN   	  user_info ON asterisk_incomming.user_id = user_info.user_id
                            JOIN      incomming_call ON incomming_call.asterisk_incomming_id = asterisk_incomming.id       
                            WHERE 	  user_info.`name` in ('$name') AND DATE(`asterisk_incomming`.`call_datetime`) BETWEEN '$start_time' AND '$end_time'
							  AND ISNULL(incomming_call.inc_status_id) AND asterisk_incomming.disconnect_cause != 'ABANDON' AND  asterisk_incomming.duration>0
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
    
    //------------------------------//
    
}else if($_REQUEST['act'] =='answear_dialog_table1'){
$data		= array('page' => array(
			'answear_dialog' => ''
	));
	$count = 		$_REQUEST['count'];
	$hidden = 		$_REQUEST['hidden'];
	$rResult = mysql_query("SELECT  `all`.date,
									`all`.date,
									`all`.phone,
									`all`.user1,
									`all`.duration,
									`all`.file_name
							FROM `all`
							WHERE `all`.file_name LIKE '%დაუმ.გამავ%'
							AND `all`.user1='$name'
							AND `all`.`date` BETWEEN '$start_time' AND '$end_time'
							UNION all
							SELECT  `all`.date,
									`all`.date,
									`all`.phone,
									`all`.user1,
									`all`.duration,
									`all`.file_name
							FROM `all`
							WHERE `all`.file_name LIKE '%გამავალი%'
							AND `all`.user1='$name'
							AND `all`.`date` BETWEEN '$start_time' AND '$end_time'

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
}else if($_REQUEST['act'] =='undone_dialog_table1'){
    $data		= array('page' => array(
        'answear_dialog' => ''
    ));
    $count = 		$_REQUEST['count'];
    $hidden = 		$_REQUEST['hidden'];
    
    $rResult = mysql_query("SELECT  `all`.date,
    								`all`.date,
									`all`.phone,
									`all`.user1,
    								`all`.duration,
									`all`.file_name
							FROM `all`
							WHERE `all`.file_name LIKE '%დაუმ.გამავ%'
							AND `all`.user1='$name'
							AND `all`.`date` BETWEEN '$start_time' AND '$end_time'
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

    }elseif($_REQUEST['act'] =='undone_dialog'){
    $data['page']['answear_dialog'] = '
			<table id="table_right_menu" >
                        <tr>
                            <td><img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
                            </td>
                            <td><img alt="log" src="media/images/icons/log.png" height="14" width="14">
                            </td>
                            <td id="show_copy_prit_exel" myvar="0"><img alt="link" src="media/images/icons/select.png" height="14" width="14">
                            </td>
                        </tr>
                    </table>									
                    <table class="display" id="example2">
                        <thead>
                            <tr id="datatable_header">
                                <th>ID</th>
                                <th style="width: 200px;">Date</th>
                                <th style="width: 150px;">Source</th>
                                <th style="width: 150px;">Address</th>
    							<th style="width: 200px;">Operator</th>
                                <th style="width: 100px;">Duration</th>
                                <th style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr class="search_header">
                                <th class="colum_hidden">
                                	<input type="text" name="search_id" value="Filter" class="search_init" style=""/>
                                </th>
                                <th>
                                	<input type="text" name="search_number" value="Filter" class="search_init" style="">
    							</th>
                                                          
                                <th>
                                    <input type="text" name="search_category" value="Filter" class="search_init" style="width: 80px;" />
                                </th>
                                <th>
                                    <input type="text" name="search_phone" value="Filter" class="search_init" style="width: 70px;"/>
                                </th>
    							<th>
                                    <input type="text" name="search_phone" value="Filter" class="search_init" style="width: 70px;"/>
                                </th>
                                <th>
                                    <input type="text" name="search_category" value="Filter" class="search_init" style="width: 70px;" />
                                </th>
    							<th>
                                    <input type="text" name="search_category" value="Filter" class="search_init" style="width: 80px;" />
                                </th>
                                
                            </tr>
                        </thead>
                    </table>			';
    
}else if($_REQUEST['act'] =='answear_dialog'){

				$data['page']['answear_dialog'] = '
					<table id="table_right_menu" >
                        <tr>
                            <td><img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
                            </td>
                            <td><img alt="log" src="media/images/icons/log.png" height="14" width="14">
                            </td>
                            <td id="show_copy_prit_exel" myvar="0"><img alt="link" src="media/images/icons/select.png" height="14" width="14">
                            </td>
                        </tr>
                    </table>									
                    <table class="display" id="example">
                        <thead>
                            <tr id="datatable_header">
                                <th>ID</th>
                                <th style="width: 200px;">Date</th>
                                <th style="width: 150px;">Source</th>
                                <th style="width: 150px;">Address</th>
    							<th style="width: 200px;">Operator</th>
                                <th style="width: 100px;">Duration</th>
                                <th style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr class="search_header">
                                <th class="colum_hidden">
                                	<input type="text" name="search_id" value="Filter" class="search_init" style=""/>
                                </th>
                                <th>
                                	<input type="text" name="search_number" value="Filter" class="search_init" style="">
    							</th>
                                                          
                                <th>
                                    <input type="text" name="search_category" value="Filter" class="search_init" style="width: 80px;" />
                                </th>
                                <th>
                                    <input type="text" name="search_phone" value="Filter" class="search_init" style="width: 70px;"/>
                                </th>
    							<th>
                                    <input type="text" name="search_phone" value="Filter" class="search_init" style="width: 70px;"/>
                                </th>
                                <th>
                                    <input type="text" name="search_category" value="Filter" class="search_init" style="width: 70px;" />
                                </th>
    							<th>
                                    <input type="text" name="search_category" value="Filter" class="search_init" style="width: 80px;" />
                                </th>
                                
                            </tr>
                        </thead>
                    </table>
         ';
			
			
}elseif($_REQUEST['act'] =='undone_dialog1'){
    $data['page']['answear_dialog'] = '
															
													
												                <table class="display" id="example2">
												                    <thead>
												                        <tr id="datatable_header">
												                            <th>ID</th>
												                            <th style="width: 190px;">თარიღი</th>
												                            <th style="width: 120px;">ადრესატი</th>
																			<th style="width: 100%;">ოპერატორი</th>
												                            <th style="width: 80px;">დრო</th>
												                            <th style="width: 100px;">ქმედება</th>
												                        </tr>
												                    </thead>
												                    <thead>
												                        <tr class="search_header">
												                            <th class="colum_hidden">
												                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" style=""/>
												                            </th>
												                            <th>
												                            	<input type="text" name="search_number" value="ფილტრი" class="search_init" style="">
																			</th>
												                            <th>
												                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100px;"/>
												                            </th>                            
												                            <th>
												                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 80px;" />
												                            </th>
												                            <th>
												                                <input type="text" name="search_phone" value="ფილტრი" class="search_init" style="width: 70px;"/>
												                            </th>
												                            <th>
												                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 70px;" />
												                            </th>
																			<th>
												                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 80px;" />
												                            </th>
												                            
												                        </tr>
												                    </thead>
												                </table>
												        
						
													';
}else if($_REQUEST['act'] =='answear_dialog1'){

				$data['page']['answear_dialog'] = '
															
													
                <table class="display" id="example2_1">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 190px;">თარიღი</th>
                            <th style="width: 120px;">ადრესატი</th>
							<th style="width: 100%;">ოპერატორი</th>
                            <th style="width: 80px;">დრო</th>
                            <th style="width: 100px;">ქმედება</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" style=""/>
                            </th>
                            <th>
                            	<input type="text" name="search_number" value="ფილტრი" class="search_init" style="">
							</th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>                            
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 80px;" />
                            </th>
                            <th>
                                <input type="text" name="search_phone" value="ფილტრი" class="search_init" style="width: 70px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 70px;" />
                            </th>
							<th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 80px;" />
                            </th>
                            
                        </tr>
                    </thead>
                </table>';
}
echo json_encode($data);
?>