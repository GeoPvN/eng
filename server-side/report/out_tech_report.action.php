<?php
require_once('../../includes/classes/core.php');

//----------------------------- ცვლადი

$agent	= $_REQUEST['agent'];
$queue	= $_REQUEST['queuet'];
$start_time = $_REQUEST['start_time'];
$end_time 	= $_REQUEST['end_time'];
$day = (strtotime($end_time)) -  (strtotime($start_time));
$day_format = round(($day / (60*60*24)) + 1);


// ----------------------------------

if($_REQUEST['act'] =='answear_dialog_table'){
$data		= array('page' => array(
			'answear_dialog' => ''
	));
	$count = 		$_REQUEST['count'];
	$hidden = 		$_REQUEST['hidden'];
	$rResult = mysql_query("SELECT 	call_datetime,
                    				call_datetime,
                    				extension,
                    				phone,
                    				'',
                    				SEC_TO_TIME(duration),
                    				CONCAT('<p onclick=play(', '\'',DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\'',  ')>მოსმენა</p>', '<a download=\"audio.wav\" href=\"http://212.72.155.176:8000/', DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\">ჩამოტვირთვა</a>') AS `file`
	                        FROM 	`asterisk_outgoing`
                            WHERE duration != 0 
                            AND 	DATE(`call_datetime`) >= '$start_time'
                            AND 	DATE(`call_datetime`) <= '$end_time'
                            AND		extension IN ($agent)");
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
	
		
}elseif($_REQUEST['act'] =='undone_dialog_table'){
    $data		= array('page' => array(
        'answear_dialog' => ''
    ));
    $count = 		$_REQUEST['count'];
    $hidden = 		$_REQUEST['hidden'];
    
    $inc = mysql_query("SELECT incomming_call.call_phone
			FROM 	incomming_call
			WHERE 	DATE(incomming_call.call_date) >= '$start_time'
			AND 	DATE(incomming_call.call_date) <= '$end_time'
			AND 	incomming_call.call_phone != '' AND 	incomming_call.call_phone !='anonymous'");
    while($res_i = mysql_fetch_array($inc)){
        $inc_str .= '\''.$res_i[0].'\',';
    }
    $inc_filtr = substr($inc_str, 0, -1);
    
    $rResult = mysql_query("SELECT  call_datetime,
                    				call_datetime,
                    				source,
                    				dst_queue,
                    				dst_extension,
                    				SEC_TO_TIME(duration),
                    				CONCAT('<p onclick=play(', '\'',DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\'',  ')>მოსმენა</p>', '<a download=\"audio.wav\" href=\"http://212.72.155.176:8000/', DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\">ჩამოტვირთვა</a>') AS `file`
                            FROM `asterisk_incomming`
                            JOIN users ON asterisk_incomming.user_id = users.id
                            WHERE 
                            dst_queue IN($queue)
                            AND dst_extension IN($agent)
                            AND disconnect_cause != 'ABANDON'
                            AND DATE(`call_datetime`) >= '$start_time'
                            AND DATE(`call_datetime`) <= '$end_time'
                            AND ISNULL(inc_id)
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
															
            	
                <table id="table_right_menu">
                <tr>
                <td><img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
                </td>
                <td><img alt="log" src="media/images/icons/log.png" height="14" width="14">
                </td>
                <td id="show_copy_prit_exel" myvar="0"><img alt="link" src="media/images/icons/select.png" height="14" width="14">
                </td>
                </tr>
                </table>
                <table class="display" id="example2" >
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 180px;">თარიღი</th>
                            <th style="width: 110px;">წყარო</th>
                            <th style="width: 100px;">ადრესატი</th>
							<th style="width: 100px;">ექსთენშენი</th>
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
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 80px;" />
                            </th>
                            <th>
                                <input type="text" name="search_phone" value="ფილტრი" class="search_init" style="width: 70px;"/>
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
}
else
if($_REQUEST['act'] =='unanswear_dialog_table'){
	

	$data		= array('page' => array(
			'answear_dialog' => ''
	));
	$count = 		$_REQUEST['count'];
	$hidden = 		$_REQUEST['hidden'];
	$rResult = mysql_query("SELECT 	call_datetime,
                            	    call_datetime,
                            	    CONCAT('<span class=\"show_this_phone\">',source,'</span>') AS `source`,
                            	    dst_queue,
	                                SEC_TO_TIME(wait_time)
                            FROM 	`asterisk_incomming`
                            WHERE	disconnect_cause = 'ABANDON'  
                            AND DATE(call_datetime) >= '$start_time'
                            AND DATE(call_datetime) <= '$end_time'
							AND dst_queue IN($queue)");
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

}
else
if($_REQUEST['act'] =='answear_dialog'){

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
                            <th style="width: 180px;">თარიღი</th>
                            <th style="width: 110px;">წყარო</th>
                            <th style="width: 110px;">ადრესატი</th>
							<th style="width: 100px;">ექსთენშენი</th>
                            <th style="width: 80px;">დრო</th>
                            <th style="width: 90px;">ქმედება</th>
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
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 80px;" />
                            </th>
                            <th>
                                <input type="text" name="search_phone" value="ფილტრი" class="search_init" style="width: 70px;"/>
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
			
			
}
else
if($_REQUEST['act'] =='unanswear_dialog'){

	$data['page']['answear_dialog'] = '
				
				<table id="table_right_menu">
                <tr>
                <td><img alt="table" src="media/images/icons/table_w.png" height="14" width="14">
                </td>
                <td><img alt="log" src="media/images/icons/log.png" height="14" width="14">
                </td>
                <td id="show_copy_prit_exel" myvar="0"><img alt="link" src="media/images/icons/select.png" height="14" width="14">
                </td>
                </tr>
                </table>			
                <table class="display" id="example1">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 100%;">თარიღი</th>
                            <th style="width: 120px;">წყარო</th>
                            <th style="width: 100px;">ადრესატი</th>
                            <th style="width: 80px;">დრო</th>
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
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 70px;" />
                            </th>
                        </tr>
                    </thead>
                </table>


	';

		
}
else
{
$data		= array('page' => array(
										'answer_call' => '',
										'technik_info' => '',
										'report_info' => '',
										'answer_call_info' => '',
										'answer_call_by_queue' => '',
										'disconnection_cause' => '',
										'unanswer_call' => '',
										'disconnection_cause_unanswer' => '',
										'unanswered_calls_by_queue' => '',
										'totals' => '',
										'call_distribution_per_day' => '',
										'call_distribution_per_hour' => '',
										'call_distribution_per_day_of_week' => '',
										'service_level' => ''
								));

$data['error'] = $error;	

$row_answer = mysql_fetch_assoc(mysql_query("	SELECT  COUNT(*) AS `count`,
                                                        phone
                                                FROM 	`asterisk_outgoing`
                                                WHERE	duration > 0
                                                AND DATE(call_datetime) >= '$start_time'
                                                AND DATE(call_datetime) <= '$end_time'
                                                AND extension IN ($agent)"));

    $row_abadon = mysql_fetch_assoc(mysql_query("	SELECT  COUNT(*) AS `count`,
                                                    phone
                                                    FROM 	`asterisk_outgoing`
                                                    WHERE	duration = 0
                                                    AND DATE(call_datetime) >= '$start_time'
                                                    AND DATE(call_datetime) <= '$end_time'
                                                    AND extension IN ($agent)
                                                    "));
    
    //---------------------------------------- რეპორტ ინფო
    
    $data['page']['report_info'] = '
    
                <tr>
                    <td>რიგი:</td>
                    <td>'.$queue.'</td>
                </tr>
                <tr>
                    <td>საწყისი თარიღი:</td>
                    <td>'.$start_time.'</td>
                </tr>
                <tr>
                    <td>დასრულების თარიღი:</td>
                    <td>'.$end_time.'</td>
                </tr>
                <tr>
                    <td>პერიოდი:</td>
                    <td>'.$day_format.' დღე</td>
                </tr>
    
							';
    
    //----------------------------------------------

if($_REQUEST['act'] == 'tab_0'){
    

//------------------------------- ტექნიკური ინფორმაცია


	
	
	
	
	$data['page']['technik_info'] = '
							
                    <td>ზარი</td>
                    <td>'.($row_answer[count] + $row_abadon[count]).'</td>
                    <td>'.$row_answer[count].'</td>
                    <td>'.$row_abadon[count].'</td>
                    <td>'.round(((($row_answer[count]) / ($row_answer[count] + $row_abadon[count])) * 100),2).' %</td>
                    <td>'.round(((($row_abadon[count]) / ($row_answer[count] + $row_abadon[count])) * 100),2).' %</td>
                
							';
// -----------------------------------------------------

}

if($_REQUEST['act']=='tab_1'){


//------------------------------------ ნაპასუხები ზარები


$row_clock = mysql_fetch_assoc(mysql_query("	SELECT	ROUND((SUM(wait_time) / COUNT(*)),2) AS `hold`,
                                        				ROUND((SUM(duration) / COUNT(*)),2) AS `sec`,
                                        				ROUND((SUM(duration) / 60 ),2) AS `min`
												FROM 	`asterisk_outgoing`
                                                WHERE	duration > 0 
                                                AND DATE(call_datetime) >= '$start_time'
                                                AND DATE(call_datetime) <= '$end_time'
                                                AND extension in ($agent)"));




	$data['page']['answer_call_info'] = '

                   	<tr>
					<td>ნაპასუხები ზარები</td>
					<td>'.$row_answer[count].' ზარი</td>
					</tr>
					
					<tr>
					<td>საშ. ხანგძლივობა:</td>
					<td>'.$row_clock[sec].' წამი</td>
					</tr>
					<tr>
					<td>სულ საუბრის ხანგძლივობა:</td>
					<td>'.$row_clock[min].' წუთი</td>
					</tr>
					<tr>
					<td>ლოდინის საშ. ხანგძლივობა:</td>
					<td>'.$row_clock[hold].' წამი</td>
					</tr>

							';
	
//---------------------------------------------

	
//--------------------------- ნაპასუხები ზარები ოპერატორების მიხედვით

 	$ress =mysql_query("SELECT 	extension AS `agent`,
                                COUNT(*) AS `num`,
                                ROUND(((COUNT(*) / (
                                		SELECT COUNT(*)
                                		FROM 		`asterisk_outgoing`
                                		WHERE		duration > 0  
                                		AND DATE(call_datetime) >= '$start_time'
                                		AND DATE(call_datetime) <= '$end_time'
                                		AND extension in ($agent)
                                )) * 100),2) AS `call_pr`,
                                ROUND((sum(duration) / 60),2) AS `call_time`,
                                ROUND(((SUM(duration) / (
                                					SELECT SUM(duration)
                                					FROM 		`asterisk_outgoing`
                                					WHERE		duration > 0
                                					AND DATE(call_datetime) >= '$start_time'
                                					AND DATE(call_datetime) <= '$end_time' 
                                					AND extension in ($agent)
                                )) * 100),2) as `call_time_pr`,
                                TIME_FORMAT(SEC_TO_TIME(sum(duration) / count(*)), '%i:%s') AS `avg_call_time`,
                                SEC_TO_TIME(sum(wait_time)) AS `hold_time`,
                                SEC_TO_TIME(SUM(wait_time) / COUNT(*)) AS `avg_hold_time`
                        FROM 		`asterisk_outgoing`
                        WHERE		duration > 0
                        AND DATE(call_datetime) >= '$start_time'
                        AND DATE(call_datetime) <= '$end_time' 
                        AND extension in ($agent)
                        GROUP BY extension");

while($row = mysql_fetch_assoc($ress)){

	$data['page']['answer_call_by_queue'] .= '

                   	<tr>
					<td>'.$row[agent].'</td>
					<td>'.$row[num].'</td>
					<td>'.$row[call_pr].' %</td>
					<td>'.$row[call_time].' </td>
					<td>'.$row[call_time_pr].' %</td>
					<td>'.$row[avg_call_time].' წუთი</td>
					<td>'.$row[hold_time].' წამი</td>
					<td>'.$row[avg_hold_time].' წამი</td>
					</tr>

							';

}

}


if($_REQUEST['act']=='tab_3'){
//------------------------------------------- სულ

	$data['page']['totals'] = '

                   	<tr> 
                  <td>ნაპასუხები ზარების რაოდენობა:</td>
		          <td>'.$row_answer[count].' ზარი</td>
	            </tr>
                <tr>
                  <td>უპასუხო ზარების რაოდენობა:</td>
                  <td>'.$row_abadon[count].' ზარი</td>
                </tr>
		        

							';
	
//------------------------------------------------

	
//-------------------------------- ზარის განაწილება დღეების მიხედვით
	
	$res = mysql_query("
						SELECT 	DATE(gg.call_datetime) AS `datetime`,
                				COUNT(*) AS `answer_count`,
                				ROUND((( COUNT(*) / (
                									SELECT COUNT(*)
                									FROM 		`asterisk_outgoing`
                									WHERE		duration > 0
                									AND DATE(call_datetime) >= '$start_time'
													AND DATE(call_datetime) <= '$end_time'
													AND extension in ($agent)
                										)) *100),2) AS `call_answer_pr`,
                				(
                					SELECT COUNT(*)
                					FROM 		`asterisk_outgoing`
                					WHERE		asterisk_outgoing.duration = 0
                					AND DATE(gg.call_datetime) = DATE(asterisk_outgoing.call_datetime)
	                                AND asterisk_outgoing.extension in ($agent)
									GROUP BY DATE(asterisk_outgoing.call_datetime)
                					
                				) AS `unanswer_call`,
                				(
                					SELECT ROUND(((COUNT(*) / $row_abadon[count] ) * 100),2)
                									
                					FROM 		`asterisk_outgoing`
                					WHERE		asterisk_outgoing.duration = 0
                					AND DATE(gg.call_datetime) = DATE(asterisk_outgoing.call_datetime)
	                                AND asterisk_outgoing.extension in ($agent)
									GROUP BY DATE(asterisk_outgoing.call_datetime)
                					
                				) AS `call_unanswer_pr`,
                				TIME_FORMAT(SEC_TO_TIME((SUM(gg.duration) / COUNT(*))), '%i:%s') AS `avg_durat`,
                				ROUND((SUM(gg.wait_time) / COUNT(*))) AS `avg_hold`
                        FROM 		`asterisk_outgoing` as gg
                        WHERE		duration > 0
                        AND DATE(gg.call_datetime) >= '$start_time'
                        AND DATE(gg.call_datetime) <= '$end_time' 
                        AND gg.extension in ($agent)
                        GROUP BY DATE(gg.call_datetime)
						");
	
	
	
	while($row = mysql_fetch_assoc($res)){
			$data['page']['call_distribution_per_day'] .= '

                   	<tr class="odd">
					<td>'.$row[datetime].'</td>
					<td>'.$row[answer_count].'</td>
					<td>'.$row[call_answer_pr].' %</td>
					<td>'.(($row[unanswer_call]!='')?$row[unanswer_call]:"0").'</td>
					<td>'.($row[call_unanswer_pr]==''?0:$row[call_unanswer_pr]).' %</td>
					<td>'.$row[avg_durat].' წუთი</td>
					<td>'.$row[avg_hold].' წამი</td>
					</tr>

							';
	}
	
//----------------------------------------------------

	
//-------------------------------- ზარის განაწილება საათების მიხედვით


			$res124 = mysql_query("
					SELECT 	HOUR(gg.call_datetime) AS `datetime`,
            				COUNT(*) AS `answer_count`,
            				ROUND((( COUNT(*) / (
            									SELECT COUNT(*)
            									FROM 		`asterisk_outgoing`
            									WHERE		duration > 0 
            									AND DATE(call_datetime) >= '$start_time'
												AND DATE(call_datetime) <= '$end_time'
												AND extension in ($agent)
            										)) *100),2) AS `call_answer_pr`,
            				(
            					SELECT COUNT(*)
            					FROM 		`asterisk_outgoing`
            					WHERE		asterisk_outgoing.duration = 0
            					AND DATE(asterisk_outgoing.call_datetime) >= '$start_time'
            					AND DATE(asterisk_outgoing.call_datetime) <= '$end_time'
			                    AND asterisk_outgoing.extension in ($agent)
            					AND HOUR(gg.call_datetime) = HOUR(asterisk_outgoing.call_datetime)
            									
            					
            				) AS `unanswer_call`,
            				(
            					SELECT ROUND(((COUNT(*) / $row_abadon[count]) * 100),2)
            									
            					FROM 		`asterisk_outgoing`
            					WHERE		asterisk_outgoing.duration = 0 
    							AND DATE(asterisk_outgoing.call_datetime) >= '$start_time'
								AND DATE(asterisk_outgoing.call_datetime) <= '$end_time'
			                    AND asterisk_outgoing.extension in ($agent)
            					AND HOUR(gg.call_datetime) = HOUR(asterisk_outgoing.call_datetime)
            							
            		
            				) AS `call_unanswer_pr`,
            				TIME_FORMAT(SEC_TO_TIME((SUM(gg.duration) / COUNT(*))), '%i:%s') AS `avg_durat`,
            				ROUND((SUM(gg.wait_time) / COUNT(*))) AS `avg_hold`
            		FROM 		`asterisk_outgoing` as gg
            		WHERE		duration > 0
            		AND DATE(gg.call_datetime) >= '$start_time'
            		AND DATE(gg.call_datetime) <= '$end_time' 
            		AND gg.extension in ($agent)
            		GROUP BY HOUR(gg.call_datetime)
					");
			
			
		while($row = mysql_fetch_assoc($res124)){
			$data['page']['call_distribution_per_hour'] .= '
				<tr class="odd">
						<td>'.$row[datetime].':00</td>
						<td>'.(($row[answer_count]!='')?$row[answer_count]:"0").'</td>
						<td>'.(($row[call_answer_pr]!='')?$row[call_answer_pr]:"0").' %</td>
						<td>'.(($row[unanswer_call]!='')?$row[unanswer_call]:"0").'</td>
						<td>'.(($row[call_unanswer_pr]!='')?$row[call_unanswer_pr]:"0").'%</td>
						<td>'.(($row[avg_durat]!='')?$row[avg_durat]:"0").' წამი</td>
						<td>'.(($row[avg_hold]!='')?$row[avg_hold]:"0").' წამი</td>

						</tr>
				';
		}

//-------------------------------------------------


//------------------------------ ზარის განაწილება კვირების მიხედვით


$res12 = mysql_query( "
    					SELECT 	CASE
                						WHEN DAYOFWEEK(gg.call_datetime) = 1 THEN 'კვირა'
                						WHEN DAYOFWEEK(gg.call_datetime) = 2 THEN 'ორშაბათი'
                						WHEN DAYOFWEEK(gg.call_datetime) = 3 THEN 'სამშაბათი'
                						WHEN DAYOFWEEK(gg.call_datetime) = 4 THEN 'ოთხშაბათი'
                						WHEN DAYOFWEEK(gg.call_datetime) = 5 THEN 'ხუთშაბათი'
                						WHEN DAYOFWEEK(gg.call_datetime) = 6 THEN 'პარასკევი'
                						WHEN DAYOFWEEK(gg.call_datetime) = 7 THEN 'შაბათი'
                				END AS `datetime`,
    							COUNT(*) AS `answer_count`,
    							ROUND((( COUNT(*) / (
    												SELECT COUNT(*)
    												FROM 		`asterisk_outgoing`
    												WHERE		duration > 0
    												AND DATE(call_datetime) >= '$start_time'
    												AND DATE(call_datetime) <= '$end_time'
    												AND extension in ($agent)
    													)) *100),2) AS `call_answer_pr`,
    							(
    								SELECT COUNT(*)
    								FROM 		`asterisk_outgoing`
    								WHERE		asterisk_outgoing.duration = 0 
									AND DATE(asterisk_outgoing.call_datetime) >= '$start_time'
									AND DATE(asterisk_outgoing.call_datetime) <= '$end_time'
                                    AND asterisk_outgoing.extension in ($agent)
    								AND DAYOFWEEK(gg.call_datetime) = DAYOFWEEK(asterisk_outgoing.call_datetime)
    							) AS `unanswer_call`,
    							(
    								SELECT ROUND(((COUNT(*) / $row_abadon[count]) * 100),2)
    												
    								FROM 		`asterisk_outgoing`
    								WHERE		asterisk_outgoing.duration = 0
									AND DATE(asterisk_outgoing.call_datetime) >= '$start_time'
									AND DATE(asterisk_outgoing.call_datetime) <= '$end_time'
                                    AND asterisk_outgoing.extension in ($agent)
    								AND DAYOFWEEK(gg.call_datetime) = DAYOFWEEK(asterisk_outgoing.call_datetime)
    							) AS `call_unanswer_pr`,
    							TIME_FORMAT(SEC_TO_TIME((SUM(gg.duration) / COUNT(*))), '%i:%s') AS `avg_durat`,
    							ROUND((SUM(gg.wait_time) / COUNT(*))) AS `avg_hold`
					FROM 		`asterisk_outgoing` as gg
					WHERE		duration > 0
					AND DATE(gg.call_datetime) >= '$start_time'
					AND DATE(gg.call_datetime) <= '$end_time'
					AND gg.extension in ($agent)
					GROUP BY DAYOFWEEK(gg.call_datetime)
					");


	while($row = mysql_fetch_assoc($res12)){
	$data['page']['call_distribution_per_day_of_week'] .= '

                   	<tr class="odd">
					<td>'.$row[datetime].'</td>
					<td>'.(($row[answer_count]!='')?$row[answer_count]:"0").'</td>
					<td>'.(($row[call_answer_pr]!='')?$row[call_answer_pr]:"0").' %</td>
					<td>'.(($row[unanswer_call]!='')?$row[unanswer_call]:"0").'</td>
					<td>'.(($row[call_unanswer_pr]!='')?$row[call_unanswer_pr]:"0").'%</td>
					<td>'.(($row[avg_durat]!='')?$row[avg_durat]:"0").' წამი</td>
					<td>'.(($row[avg_hold]!='')?$row[avg_hold]:"0").' წამი</td>

					</tr>
						';

}

//---------------------------------------------------
}
}

echo json_encode($data);

?>