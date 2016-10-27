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
                    				source,
                    				dst_queue,
                    				dst_extension,
                    				SEC_TO_TIME(duration),
                    				CONCAT('<p onclick=play(', '\'',DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\'',  ')>მოსმენა</p>', '<a download=\"audio.wav\" href=\"http://212.72.155.176:8000/', DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\">ჩამოტვირთვა</a>') AS `file`
	                        FROM 	`asterisk_incomming`
                            WHERE disconnect_cause != 'ABANDON' 
                            AND 	DATE(`call_datetime`) >= '$start_time'
                            AND 	DATE(`call_datetime`) <= '$end_time'
                            AND 	dst_queue IN ($queue)
                            AND		dst_extension IN ($agent)");
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
    
    
    $rResult = mysql_query("SELECT  call_datetime,
                    				call_datetime,
                    				source,
                    				dst_queue,
                    				dst_extension,
                    				SEC_TO_TIME(duration),
                    				CONCAT('<p onclick=play(', '\'',DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\'',  ')>მოსმენა</p>', '<a download=\"audio.wav\" href=\"http://212.72.155.176:8000/', DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\">ჩამოტვირთვა</a>') AS `file`
                            FROM `asterisk_incomming`
                            JOIN incomming_call ON asterisk_incomming.id = incomming_call.asterisk_incomming_id
                            WHERE ISNULL(incomming_call.user_id)
                            AND dst_queue IN($queue)
                            AND dst_extension IN($agent)
                            AND disconnect_cause != 'ABANDON'
                            AND DATE(`call_datetime`) >= '$start_time'
                            AND DATE(`call_datetime`) <= '$end_time'
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
                            <th style="width: 200px;">თარიღი</th>
                            <th style="width: 120px;">წყარო</th>
                            <th style="width: 100px;">ადრესატი</th>
                            <th style="width: 120px;">ლოდინის დრო</th>
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
                                                        dst_queue
                                                FROM 	`asterisk_incomming`
                                                WHERE	disconnect_cause != 'ABANDON'
                                                AND DATE(call_datetime) >= '$start_time'
                                                AND DATE(call_datetime) <= '$end_time'
                                                AND dst_queue IN ($queue)
                                                AND dst_extension IN ($agent)"));

    $row_abadon = mysql_fetch_assoc(mysql_query("	SELECT 	COUNT(*) AS `count`,
                                                            ROUND((SUM(wait_time) / COUNT(*))) AS `sec`
                                                    FROM 	`asterisk_incomming`
                                                    WHERE	disconnect_cause = 'ABANDON'
                                                    AND DATE(call_datetime) >= '$start_time'
                                                    AND DATE(call_datetime) <= '$end_time'
                                                    AND dst_queue IN ($queue)
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
    
$row_done_blank = mysql_fetch_assoc(mysql_query("	SELECT COUNT(incomming_call.id) as `count`
                                                    FROM 	incomming_call
                                                    JOIN asterisk_incomming ON incomming_call.asterisk_incomming_id = asterisk_incomming.id
                                                    WHERE DATE(incomming_call.date) >= '$start_time'
                                                    AND DATE(incomming_call.date) <= '$end_time'
                                                    AND asterisk_incomming.dst_queue IN ($queue) 
                                                    AND asterisk_incomming.dst_extension IN ($agent)
                                                    AND incomming_call.phone != '' AND incomming_call.user_id > 0"));




//------------------------------- ტექნიკური ინფორმაცია


	
	
	
	
	$data['page']['technik_info'] = '
							
                    <td>ზარი</td>
                    <td>'.($row_answer[count] + $row_abadon[count]).'</td>
                    <td>'.$row_answer[count].'</td>
                    <td>'.$row_abadon[count].'</td>
                    <td>'.$row_done_blank[count].'</td>
                    <td>'.($row_answer[count] - $row_done_blank[count]).'</td>
                    <td>'.round(((($row_answer[count]) / ($row_answer[count] + $row_abadon[count])) * 100),2).' %</td>
                    <td>'.round(((($row_abadon[count]) / ($row_answer[count] + $row_abadon[count])) * 100),2).' %</td>
                    <td>'.round(((($row_done_blank[count]) / ($row_answer[count])) * 100),2).' %</td>
                    <td>'.round(((($row_answer[count] - $row_done_blank[count]) / ($row_answer[count])) * 100),2).' %</td>
                
							';
// -----------------------------------------------------

}

if($_REQUEST['act']=='tab_1'){
//------------------------------- ნაპასუხები ზარები რიგის მიხედვით

	$answer_que = mysql_query("	SELECT  COUNT(*) AS `count`,
                        	           dst_queue
                        	    FROM 	`asterisk_incomming`
                        	    WHERE	disconnect_cause != 'ABANDON'
                        	    AND DATE(call_datetime) >= '$start_time'
                        	    AND DATE(call_datetime) <= '$end_time'
                        	    AND dst_queue IN ($queue)
                        	    AND dst_extension IN ($agent)
	                            GROUP BY dst_queue");
	
while ($row_answer_que = mysql_fetch_assoc($answer_que)){
	$data['page']['answer_call'] .= '
							<tr><td>'.$row_answer_que[dst_queue].'</td><td>'.$row_answer_que[count].' ზარი</td><td>'.round(((($row_answer_que[count]) / ($row_answer[count])) * 100)).' %</td></tr>
							';
}

//-------------------------------------------------------

//------------------------------- მომსახურების დონე(Service Level)

	
	
	$res_service_level = mysql_query("	SELECT  `wait_time`                                				
                                        FROM 	`asterisk_incomming`
                                        WHERE	disconnect_cause != 'ABANDON'  
                                        AND DATE(call_datetime) >= '$start_time'
                                        AND DATE(call_datetime) <= '$end_time' 
                                        AND dst_queue IN ($queue) 
                                        AND dst_extension in ($agent)
						              ");
	$w15 = 0;
	$w30 = 0;
	$w45 = 0;
	$w60 = 0;
	$w75 = 0;
	$w90 = 0;
	$w91 = 0;
	
	
	
	
	while ($res_service_level_r = mysql_fetch_assoc($res_service_level)) {
	
		if ($res_service_level_r['wait_time'] < 15) {
			$w15++;
		}
	
		if ($res_service_level_r['wait_time'] < 30){
			$w30++;
		}
	
		if ($res_service_level_r['wait_time'] < 45){
			$w45++;
		}
	
		if ($res_service_level_r['wait_time'] < 60){
			$w60++;
		}
	
		if ($res_service_level_r['wait_time'] < 75){
			$w75++;
		}
	
		if ($res_service_level_r['wait_time'] < 90){
			$w90++;
		}
	
		$w91++;
	
	}
	
	$d30 = $w30 - $w15;
	$d45 = $w45 - $w30;
	$d60 = $w60 - $w45;
	$d75 = $w75 - $w60;
	$d90 = $w90 - $w75;
	$d91 = $w91 - $w90;
	
	
	$p15 = round($w15 * 100 / $w91);
	$p30 = round($w30 * 100 / $w91);
	$p45 = round($w45 * 100 / $w91);
	$p60 = round($w60 * 100 / $w91);
	$p75 = round($w75 * 100 / $w91);
	$p90 = round($w90 * 100 / $w91);
	
	
	
	
	
	$data['page']['service_level'] = '
							
							<tr class="odd">
						 		<td>15 წამში</td>
					 			<td>'.$w15.'</td>
					 			<td></td>
					 			<td>'.$p15.'%</td>
					 		</tr>
				 			<tr>
						 		<td>30 წამში</td>
					 			<td>'.$w30.'</td>
					 			<td>'.$d30.'</td>
					 			<td>'.$p30.'%</td>
					 		</tr>
				 			<tr class="odd">
						 		<td>45 წამში</td>
					 			<td>'.$w45.'</td>
					 			<td>'.$d45.'</td>
					 			<td>'.$p45.'%</td>
					 		</tr>
				 			<tr>
						 		<td>60 წამში</td>
					 			<td>'.$w60.'</td>
					 			<td>'.$d60.'</td>
					 			<td>'.$p60.'%</td>
					 		</tr>
				 			<tr class="odd">
						 		<td>75 წამში</td>
					 			<td>'.$w75.'</td>
					 			<td>'.$d75.'</td>
					 			<td>'.$p75.'%</td>
					 		</tr>
					 		<tr>
						 		<td>90 წამში</td>
					 			<td>'.$w90.'</td>
					 			<td>'.$d90.'</td>
					 			<td>'.$p90.'%</td>
					 		</tr>
					 		<tr class="odd">
						 		<td>90+ წამში</td>
					 			<td>'.$w91.'</td>
					 			<td>'.$d91.'</td>
					 			<td>100%</td>
					 		</tr>
							';
	
//-------------------------------------------------------


//------------------------------------ ნაპასუხები ზარები


$row_clock = mysql_fetch_assoc(mysql_query("	SELECT	ROUND((SUM(wait_time) / COUNT(*)),2) AS `hold`,
                                        				ROUND((SUM(duration) / COUNT(*)),2) AS `sec`,
                                        				ROUND((SUM(duration) / 60 ),2) AS `min`
												FROM 	`asterisk_incomming`
                                                WHERE	disconnect_cause != 'ABANDON'  
                                                AND DATE(call_datetime) >= '$start_time'
                                                AND DATE(call_datetime) <= '$end_time' 
                                                AND dst_queue IN ($queue) 
                                                AND dst_extension in ($agent)"));




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

 	$ress =mysql_query("SELECT 	dst_extension AS `agent`,
                				COUNT(*) AS `num`,
                				ROUND(((COUNT(*) / (
                				SELECT COUNT(*)
                				FROM 		`asterisk_incomming`
                				WHERE		disconnect_cause != 'ABANDON'  
                				AND DATE(call_datetime) >= '$start_time'
                                AND DATE(call_datetime) <= '$end_time' 
                                AND dst_queue IN ($queue) 
                                AND dst_extension in ($agent)
                				)) * 100),2) AS `call_pr`,
                				ROUND((sum(duration) / 60),2) AS `call_time`,
                				ROUND(((SUM(duration) / (
                				SELECT SUM(duration)
                				FROM 		`asterisk_incomming`
                				WHERE		disconnect_cause != 'ABANDON' 
                				AND DATE(call_datetime) >= '$start_time'
                                AND DATE(call_datetime) <= '$end_time' 
                                AND dst_queue IN ($queue) 
                                AND dst_extension in ($agent)
                				)) * 100),2) as `call_time_pr`,
                				TIME_FORMAT(SEC_TO_TIME(sum(duration) / count(*)), '%i:%s') AS `avg_call_time`,
                				SEC_TO_TIME(sum(wait_time)) AS `hold_time`,
                				SEC_TO_TIME(SUM(wait_time) / COUNT(*)) AS `avg_hold_time`
                    FROM 		`asterisk_incomming`
                    WHERE		disconnect_cause != 'ABANDON' 
                    AND DATE(call_datetime) >= '$start_time'
                    AND DATE(call_datetime) <= '$end_time' 
                    AND dst_queue IN ($queue) 
                    AND dst_extension in ($agent)
                    GROUP BY dst_extension");

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

//----------------------------------------------------


//--------------------------- კავშირის გაწყვეტის მიზეზეი


$row_COMPLETECALLER = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS `count`
                                                    FROM 	`asterisk_incomming`
                                                    WHERE	disconnect_cause != 'ABANDON'  
                                                    AND DATE(call_datetime) >= '$start_time'
                                                    AND DATE(call_datetime) <= '$end_time' 
                                                    AND dst_queue IN ($queue) 
                                                    AND dst_extension in ($agent)
                                                    AND disconnect_cause = 'COMPLETECALLER'"));

$row_AGENT = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS `count`
                                                    FROM 	`asterisk_incomming`
                                                    WHERE	disconnect_cause != 'ABANDON' 
                                                    AND DATE(call_datetime) >= '$start_time'
                                                    AND DATE(call_datetime) <= '$end_time'
                                                    AND dst_queue IN ($queue)
                                                    AND dst_extension in ($agent)
                                                    AND disconnect_cause = 'COMPLETEAGENT'"));


	$data['page']['disconnection_cause'] = '

                   <tr>
					<td>ოპერატორმა გათიშა:</td>
					<td>'.$row_AGENT[count].' ზარი</td>
					<td>'.ROUND((($row_AGENT[count] / ($row_COMPLETECALLER[count] +$row_AGENT[count]) ) * 100),2).' %</td>
					</tr>
					<tr>
					<td>აბონენტმა გათიშა:</td>
					<td>'.$row_COMPLETECALLER[count].' ზარი</td>
					<td>'.ROUND((($row_COMPLETECALLER[count] / ($row_COMPLETECALLER[count] +$row_AGENT[count]) ) * 100),2).' %</td>
					</tr>
                    ';

//-----------------------------------------------
}

if($_REQUEST['act']=='tab_2'){
//----------------------------------- უპასუხო ზარები


	$data['page']['unanswer_call'] = '

                   	<tr>
					<td>უპასუხო ზარების რაოდენობა:</td>
					<td>'.$row_abadon[count].' ზარი</td>
					</tr>
					<tr>
					<td>ლოდინის საშ. დრო კავშირის გაწყვეტამდე:</td>
					<td>'.$row_abadon[sec].' წამი</td>
					</tr>
					<tr>
					<td>საშ. რიგში პოზიცია კავშირის გაწყვეტამდე:</td>
					<td>1</td>
					</tr>
					<tr>
					<td>საშ. საწყისი პოზიცია რიგში:</td>
					<td>1</td>
					</tr>

							';


//--------------------------------------------

	
//----------------------------------- კავშირის გაწყვეტის მიზეზი


	$data['page']['disconnection_cause_unanswer'] = '

                  <tr> 
                  <td>აბონენტმა გათიშა</td>
			      <td>'.$row_abadon[count].' ზარი</td>
			      <td>'.round((($row_abadon[count] / $row_abadon[count]) * 100),2).' %</td>
		        </tr>
			    <tr> 
                  <td>დრო ამოიწურა</td>
			      <td>0 ზარი</td>
			      <td>0 %</td>
		        </tr>

							';

//--------------------------------------------

//------------------------------ უპასუხო ზარები რიგის მიხედვით

	$Unanswered_Calls_by_Queue_r = mysql_query("	SELECT  COUNT(*) AS `count`,
                                                            dst_queue AS `queue`
                                            	    FROM 	`asterisk_incomming`
                                            	    WHERE	disconnect_cause = 'ABANDON' 
                                            	    AND DATE(call_datetime) >= '$start_time'
                                            	    AND DATE(call_datetime) <= '$end_time'
                                            	    AND dst_queue IN ($queue)
	                                                GROUP BY dst_queue
                                                               ");
	
	while ($Unanswered_Calls_by_Queue = mysql_fetch_assoc($Unanswered_Calls_by_Queue_r)){
	$data['page']['unanswered_calls_by_queue'] .= '

                   	<tr><td>'.$Unanswered_Calls_by_Queue[queue].'</td><td>'.$Unanswered_Calls_by_Queue[count].' ზარი</td><td>'.round((($Unanswered_Calls_by_Queue[count] / $Unanswered_Calls_by_Queue[count]) * 100),2).' %</td></tr>

							';
	}

//---------------------------------------------------
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
                									FROM 		`asterisk_incomming`
                									WHERE		disconnect_cause != 'ABANDON' 
                									AND DATE(call_datetime) >= '$start_time'
                                            	    AND DATE(call_datetime) <= '$end_time'
                                        	        AND dst_queue IN ($queue)
                                        	        AND dst_extension in ($agent)
                										)) *100),2) AS `call_answer_pr`,
                				(
                					SELECT COUNT(*)
                					FROM 		`asterisk_incomming`
                					WHERE		asterisk_incomming.disconnect_cause = 'ABANDON' 
                					AND DATE(gg.call_datetime) = DATE(asterisk_incomming.call_datetime)
	                                AND dst_queue IN ($queue)
	                                GROUP BY DATE(asterisk_incomming.call_datetime)
                					
                				) AS `unanswer_call`,
                				(
                					SELECT ROUND(((COUNT(*) / $row_abadon[count] ) * 100),2)
                									
                					FROM 		`asterisk_incomming`
                					WHERE		asterisk_incomming.disconnect_cause = 'ABANDON' 
                					AND DATE(gg.call_datetime) = DATE(asterisk_incomming.call_datetime)
	                                AND dst_queue IN ($queue)
	                                GROUP BY DATE(asterisk_incomming.call_datetime)
                					
                				) AS `call_unanswer_pr`,
                				TIME_FORMAT(SEC_TO_TIME((SUM(gg.duration) / COUNT(*))), '%i:%s') AS `avg_durat`,
                				ROUND((SUM(gg.wait_time) / COUNT(*))) AS `avg_hold`
                    FROM 		`asterisk_incomming` as gg
                    WHERE		disconnect_cause != 'ABANDON' 
                    AND DATE(gg.call_datetime) >= '$start_time'
                    AND DATE(gg.call_datetime) <= '$end_time' 
                    AND gg.dst_queue IN ($queue)
                    AND gg.dst_extension in ($agent)
                    GROUP BY DATE(gg.call_datetime)
						");
	
	
	
	while($row = mysql_fetch_assoc($res)){
			$data['page']['call_distribution_per_day'] .= '

                   	<tr class="odd">
					<td>'.$row[datetime].'</td>
					<td>'.($row[answer_count] + $row[unanswer_call]).'</td>
					<td>'.$row[answer_count].'</td>
					<td>'.$row[call_answer_pr].' %</td>
					<td>'.$row[unanswer_call].'</td>
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
                									FROM 		`asterisk_incomming`
                									WHERE		disconnect_cause != 'ABANDON'  
                									AND DATE(call_datetime) >= '$start_time'
                                            	    AND DATE(call_datetime) <= '$end_time'
                                        	        AND dst_queue IN ($queue)
                                        	        AND dst_extension in ($agent)
                										)) *100),2) AS `call_answer_pr`,
                				(
                					SELECT COUNT(*)
                					FROM 		`asterisk_incomming`
                					WHERE		asterisk_incomming.disconnect_cause = 'ABANDON' 
			                        AND DATE(asterisk_incomming.call_datetime) >= '$start_time'
                                    AND DATE(asterisk_incomming.call_datetime) <= '$end_time'
                					AND HOUR(gg.call_datetime) = HOUR(asterisk_incomming.call_datetime)
	                                AND dst_queue IN ($queue)
                					
                				) AS `unanswer_call`,
                				(
                					SELECT ROUND(((COUNT(*) / $row_abadon[count]) * 100),2)
                									
                					FROM 		`asterisk_incomming`
                					WHERE		asterisk_incomming.disconnect_cause = 'ABANDON'  
			                        AND DATE(asterisk_incomming.call_datetime) >= '$start_time'
                                    AND DATE(asterisk_incomming.call_datetime) <= '$end_time'
                					AND HOUR(gg.call_datetime) = HOUR(asterisk_incomming.call_datetime)
			                        AND dst_queue IN ($queue)
                		
                				) AS `call_unanswer_pr`,
                				TIME_FORMAT(SEC_TO_TIME((SUM(gg.duration) / COUNT(*))), '%i:%s') AS `avg_durat`,
                				ROUND((SUM(gg.wait_time) / COUNT(*))) AS `avg_hold`
                    FROM 		`asterisk_incomming` as gg
                    WHERE		disconnect_cause != 'ABANDON' 
                    AND DATE(gg.call_datetime) >= '$start_time'
                    AND DATE(gg.call_datetime) <= '$end_time' 
                    AND gg.dst_queue IN ($queue)
                    AND gg.dst_extension in ($agent)
                    GROUP BY HOUR(gg.call_datetime)
					");
			
			
		while($row = mysql_fetch_assoc($res124)){
			$data['page']['call_distribution_per_hour'] .= '
				<tr class="odd">
						<td>'.$row[datetime].':00</td>
						<td>'.((($row[answer_count]!='')?$row[answer_count]:"0") + (($row[unanswer_call]!='')?$row[unanswer_call]:"0")).'</td>
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
                									FROM 		`asterisk_incomming`
                									WHERE		disconnect_cause != 'ABANDON' 
                									AND DATE(call_datetime) >= '$start_time'
                                            	    AND DATE(call_datetime) <= '$end_time'
                                        	        AND dst_queue IN ($queue)
                                        	        AND dst_extension in ($agent)
                										)) *100),2) AS `call_answer_pr`,
                				(
                					SELECT COUNT(*)
                					FROM 		`asterisk_incomming`
                					WHERE		asterisk_incomming.disconnect_cause = 'ABANDON' 
                                    AND DATE(asterisk_incomming.call_datetime) >= '$start_time'
                                    AND DATE(asterisk_incomming.call_datetime) <= '$end_time'
                					AND DAYOFWEEK(gg.call_datetime) = DAYOFWEEK(asterisk_incomming.call_datetime)
                                    AND dst_queue IN ($queue)
                				) AS `unanswer_call`,
                				(
                					SELECT ROUND(((COUNT(*) / $row_abadon[count]) * 100),2)
                									
                					FROM 		`asterisk_incomming`
                					WHERE		asterisk_incomming.disconnect_cause = 'ABANDON' 
                                    AND DATE(asterisk_incomming.call_datetime) >= '$start_time'
                                    AND DATE(asterisk_incomming.call_datetime) <= '$end_time'
                					AND DAYOFWEEK(gg.call_datetime) = DAYOFWEEK(asterisk_incomming.call_datetime)
                                    AND dst_queue IN ($queue)
                				) AS `call_unanswer_pr`,
                				TIME_FORMAT(SEC_TO_TIME((SUM(gg.duration) / COUNT(*))), '%i:%s') AS `avg_durat`,
                				ROUND((SUM(gg.wait_time) / COUNT(*))) AS `avg_hold`
                    FROM 		`asterisk_incomming` as gg
                    WHERE		disconnect_cause != 'ABANDON' 
                    AND DATE(gg.call_datetime) >= '$start_time'
                    AND DATE(gg.call_datetime) <= '$end_time' 
                    AND gg.dst_queue IN ($queue)
                    AND gg.dst_extension in ($agent)
                    GROUP BY DAYOFWEEK(gg.call_datetime)
					");


	while($row = mysql_fetch_assoc($res12)){
	$data['page']['call_distribution_per_day_of_week'] .= '

                   	<tr class="odd">
					<td>'.$row[datetime].'</td>
					<td>'.((($row[answer_count]!='')?$row[answer_count]:"0") + (($row[unanswer_call]!='')?$row[unanswer_call]:"0")).'</td>
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