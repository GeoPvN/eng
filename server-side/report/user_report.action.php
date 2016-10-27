<?php
require_once('../../includes/classes/core.php');

//----------------------------- ცვლადი

$agent	= $_REQUEST['agent'];
$name	= $_REQUEST['name'];
$queue	= $_REQUEST['queuet'];
$start_time = $_REQUEST['start_time'];
$end_time 	= $_REQUEST['end_time'];
$day = (strtotime($end_time)) -  (strtotime($start_time));
$day_format = ($day / (60*60*24)) + 1;

if($_REQUEST['act'] =='check'){

		
//--------------------------- ნაპასუხები ზარები ოპერატორების მიხედვით

 	$ress =mysql_query("SELECT 	pers.`name` as `name`,
                				COUNT(*) AS zarebi,
                				(SELECT  COUNT(*)
                					FROM `incomming_call`
                					LEFT JOIN users ON incomming_call.user_id=users.id
                					LEFT JOIN user_info ON users.id=user_info.user_id
                					WHERE user_info.`name`=pers.`name`
                					AND `incomming_call`.`date` BETWEEN '$start_time' AND '$end_time' AND incomming_call.actived =1 AND incomming_call.phone !=''
                				)AS damushavebuli,
                				'0' AS task_count,
                				'0' AS gamavali_damushavebuli,
                				(SELECT SEC_TO_TIME(sum(TIMESTAMPDIFF(SECOND,
                						IF(person_work_graphic.`start`< '$start_time','$start_time', person_work_graphic.`start`),
                						IF(person_work_graphic.`end`  > '$end_time', '$end_time', person_work_graphic.`end`))))
                					FROM 	person_work_graphic
                					LEFT JOIN users ON person_work_graphic.person_id=users.id
                					LEFT JOIN user_info ON user_info.user_id=users.id 										
                					WHERE  	user_info.`name` = pers.`name`
                					AND  	person_work_graphic.actived=1 AND users.actived=1										
                					AND   	(person_work_graphic.`start` >= '$start_time' OR 	person_work_graphic.`end` 	>= '$start_time')
                					AND   	(person_work_graphic.`start` <= '$end_time' OR  person_work_graphic.`end` 	<= '$end_time')
                					)AS gegmiuri,
                													concat(ROUND((SUM(asterisk_incomming.duration)/
                													(SELECT 	sum(TIMESTAMPDIFF(SECOND,
                															IF(person_work_graphic.`start`< '$start_time','$start_time', person_work_graphic.`start`),
                															IF(person_work_graphic.`end`  > '$end_time', '$end_time', person_work_graphic.`end`)))
                														FROM 	person_work_graphic
                														LEFT JOIN users ON person_work_graphic.person_id=users.id
                														LEFT JOIN user_info ON user_info.user_id=users.id 										
                														WHERE  	user_info.`name` = pers.`name`
                														AND  		person_work_graphic.actived=1 AND users.actived=1										
                														AND   	(person_work_graphic.`start` >= '$start_time' OR 	person_work_graphic.`end` 	>= '$start_time')
                														AND   	(person_work_graphic.`start` <= '$end_time' OR  person_work_graphic.`end` 	<= '$end_time')
                					))*100,1),'%') AS datvirtva,
                				'0' AS gamavali,
                		ROUND((COUNT(*)/(SELECT 	COUNT(*)
                						FROM 	asterisk_incomming
                						LEFT JOIN  users ON asterisk_incomming.user_id = users.id
                						LEFT JOIN user_info ON user_info.user_id=users.id
                						WHERE 	user_info.`name` IN($agent)
                						AND `asterisk_incomming`.`call_datetime` BETWEEN '$start_time' AND '$end_time'
                						) *100), 2) AS `percent`,
                				SEC_TO_TIME(SUM(asterisk_incomming.duration)) AS saubris_dro,
                				SEC_TO_TIME(ROUND((SUM(asterisk_incomming.duration)/COUNT(*)),0)) AS sashualo,
                				SEC_TO_TIME(MAX(asterisk_incomming.duration)) AS max_time,
                				SEC_TO_TIME(MIN(asterisk_incomming.duration)) AS min_time
                		FROM asterisk_incomming
                		LEFT JOIN users ON asterisk_incomming.user_id = users.id
                		LEFT JOIN user_info AS pers ON pers.user_id=users.id
                		WHERE pers.`name` IN($agent)
                		AND `asterisk_incomming`.`call_datetime` BETWEEN '$start_time' AND '$end_time'
                		AND asterisk_incomming.disconnect_cause !='ABANDON'
                		GROUP BY users.`id`
		 	  ");

while($row = mysql_fetch_assoc($ress)){
	
	$gamavali_daum 	 = $row[gamavali]-$row[gamavali_damushavebuli];
	$coeficienti = round($row[damushavebuli]/$row[zarebi],2);
	$daumushavebebli= $row[zarebi] - $row[damushavebuli];
	$data['page']['answer_call_by_queue'] .= '

                   	<tr>
					<td style="cursor:pointer;" id="name">'.$row[name].'</td>
					<td>'.$row[gegmiuri].' სთ</td>
					<td id="answear_dialog" style="cursor: pointer; text-decoration: underline;" user="'.$row[name].'">'.$row[zarebi].' ზარი</td>
					<td>'.$row[damushavebuli].' ზარი</td>
					<td>'.$daumushavebebli.' ზარი</td>
					<td>'.$row[percent].'%</td>
					<td>'.$row[saubris_dro].'</td>
					<td>'.$row[sashualo].'</td>
					<td>'.$row[max_time].'</td>
					<td>'.$row[min_time].'</td>
					<td>'.$row[datvirtva].'</td>
					<td>'.$row[task_count].'</td>
					<td id="answear_dialog1" style="cursor: pointer; text-decoration: underline;" user2="'.$row[name].'">'.$row[gamavali].' ზარი</td>
					<td>'.$row[gamavali_damushavebuli].'ზარი</td>
					<td id="undone_dialog1" style="cursor: pointer; text-decoration: underline;" user3="'.$row[name].'">'.$gamavali_daum.' ზარი</td>
					</tr>

							';

}

//------------------------------//
}else if($_REQUEST['act'] =='answear_dialog_table'){
$data		= array('page' => array(
			'answear_dialog' => ''
	));
	$count = 		$_REQUEST['count'];
	$hidden = 		$_REQUEST['hidden'];
	$rResult = mysql_query("SELECT 	asterisk_incomming.call_datetime,
									asterisk_incomming.call_datetime,
									asterisk_incomming.source,
									'2500111' as QUEUE,
									user_info.`name`,
									time_format(SEC_TO_TIME(asterisk_incomming.duration), '%i:%s') as time,
									concat('<button class=\'download\' str=', `asterisk_incomming`.`file_name`,'>შემომავალი</button>') as qmedeba
							FROM asterisk_incomming
							JOIN users ON asterisk_incomming.user_id=users.id
							JOIN user_info ON user_info.user_id= users.id
							WHERE asterisk_incomming.file_name!='' 
							AND asterisk_incomming.call_datetime >= '$start_time'
			 				AND asterisk_incomming.call_datetime <= '$end_time'
							AND user_info.`name`= '$name'
			
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
    
   $rResult = mysql_query("SELECT * FROM (SELECT `asterisk_incomming`.`call_datetime` AS `date1`,
												 `asterisk_incomming`.`call_datetime` AS `date`,
												  IF (isnull(`inc`.`phone`),`asterisk_incomming`.`source`,`inc`.`phone`) AS `phone`,
   												  '2500111' as QUEUE,
												  persons1.`name`,
												  time_format(sec_to_time((`asterisk_incomming`.`duration` - 1)), '%i:%s') AS `duration`,
												CASE
													WHEN `asterisk_incomming`.`disconnect_cause` = 'ABANDON'
														THEN concat('<button class=\'download2\' str=', `asterisk_incomming`.`file_name`,'>უპასუხო</button>')
													WHEN `asterisk_incomming`.`disconnect_cause` != 'ABANDON'
														THEN IF (isnull(`inc`.`id`),concat('<button class=\'download1\' str=', `asterisk_incomming`.`file_name`,'>დაუმ.შემომ...</button>'),concat('<button class=\'download\' str=', `asterisk_incomming`.`file_name`,'>შემომავალი</button>'))
												END AS `file_name`
											FROM      `asterisk_incomming`
											
											LEFT JOIN `incomming_call` AS `inc` ON `inc`.`asterisk_incomming_id` = `asterisk_incomming`.`id`
											LEFT JOIN `task` 	ON `task`.`incomming_call_id` = `inc`.`id`
											LEFT JOIN `users` ON `task`.`responsible_user_id` = `users`.`id`
											LEFT JOIN `users` AS `users1` ON `asterisk_incomming`.`user_id` = `users1`.`id`
											LEFT JOIN  `user_info` ON user_info.user_id=`users`.id
											LEFT JOIN  `user_info` AS persons1 ON persons1.user_id=users1.id
											WHERE asterisk_incomming.call_datetime > '$start_time'  
											AND asterisk_incomming.call_datetime <= '$end_time' )AS daum_Shem
							WHERE daum_Shem.file_name LIKE '%დაუმ.შემომ...%' AND daum_Shem.`name`= '$name'
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
	$rResult = mysql_query("SELECT  asterisk_outgoing.call_datetime,
									asterisk_outgoing.call_datetime,
									asterisk_outgoing.phone,
									user_info.`name`,
									time_format(SEC_TO_TIME(asterisk_outgoing.duration), '%i:%s') as time,
									concat('<button class=\'download2\' str=',`asterisk_outgoing`.`file_name`,'>მოსმენა</button>') as qmedeba
							FROM asterisk_outgoing
							JOIN users ON asterisk_outgoing.user_id=users.id
							JOIN user_info ON user_info.user_id= users.id
							WHERE asterisk_outgoing.file_name!='' 
							AND asterisk_outgoing.call_datetime>'$start_time' 
							AND asterisk_outgoing.call_datetime<'$end_time'
							AND user_info.`name`= '$name'
							AND LENGTH(phone)>3
							AND asterisk_outgoing.extension IN (SELECT extention FROM extention)

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
		
}else if($_REQUEST['act'] =='undone_dialog_table1'){
    $data		= array('page' => array(
        'answear_dialog' => ''
    ));
    $count = 		$_REQUEST['count'];
    $hidden = 		$_REQUEST['hidden'];
    
    $rResult = mysql_query("SELECT * FROM (SELECT   `asterisk_outgoing`.`call_datetime` AS `date1`,
													`asterisk_outgoing`.`call_datetime` AS `date`,
													`asterisk_outgoing`.`phone` AS `phone`,
													`user_info`.`name` AS `user1`,
													 time_format(sec_to_time(`asterisk_outgoing`.`duration`),'%i:%s') AS `duration`,
													 IF (isnull(`tsk`.`asterisk_outgoing_id`),concat('<button class=\'download3\' str=',concat(DATE_FORMAT(asterisk_outgoing.call_datetime, '%Y/%m/%d/'),`asterisk_outgoing`.`file_name`),'>დაუმ.გამავ...</button>'),concat('<button class=\'download1\' str=',concat(DATE_FORMAT(asterisk_outgoing.call_datetime, '%Y/%m/%d/'),`asterisk_outgoing`.`file_name`),'>გამავალი</button>')) AS `file_name`

											FROM      `asterisk_outgoing`
											LEFT JOIN `task` `tsk` ON `tsk`.`asterisk_outgoing_id` = `asterisk_outgoing`.`id` 
											LEFT JOIN `incomming_call` `inc` ON `tsk`.`incomming_call_id` = `inc`.`id`
											LEFT JOIN `category` ON `tsk`.`call_subcategory_id` = `category`.`id`
											LEFT JOIN `category` `cat` ON `inc`.`call_subcategory_id` = `cat`.`id`
											LEFT JOIN `users` ON `asterisk_outgoing`.`user_id` = `users`.`id`
											LEFT JOIN `user_info` ON 	`users`.`id` = `user_info`.`user_id`
											LEFT JOIN `status` ON `tsk`.`status` = `status`.`id`
											WHERE     length(`asterisk_outgoing`.`phone`) > 3 AND `asterisk_outgoing`.`file_name` <> '' 
											AND `asterisk_outgoing`.`extension` IN (100, 101, 102, 103, 104) 
											AND asterisk_outgoing.call_datetime > '$start_time'  
											AND asterisk_outgoing.call_datetime <= '$end_time') AS daum_gam
							WHERE daum_gam.file_name LIKE '%დაუმ.გამავ...%'
							AND daum_gam.user1='$name'
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
															
													
												                <table class="display" id="example2" >
												                    <thead>
												                        <tr id="datatable_header">
												                            <th>ID</th>
												                            <th style="width: 180px;">თარიღი</th>
												                            <th style="width: 150px;">ადრესატი</th>
																			<th style="width: 100px;">ექსთენშენი</th>
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
    
}else if($_REQUEST['act'] =='answear_dialog'){

				$data['page']['answear_dialog'] = '
															
													
                <table class="display" id="example">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 180px;">თარიღი</th>
                            <th style="width: 150px;">წყარო</th>
                            <th style="width: 120px;">ადრესატი</th>
							<th style="width: 100%;">ოპერატორი</th>
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
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100px;"/>
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
			
			
}elseif($_REQUEST['act'] =='undone_dialog1'){
    $data['page']['answear_dialog'] = '
															
													
												                <table class="display" id="example2">
												                    <thead>
												                        <tr id="datatable_header">
												                            <th>ID</th>
												                            <th style="width: 190px;">თარიღი</th>
												                            <th style="width: 150px;">ადრესატი</th>
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
                            <th style="width: 150px;">ადრესატი</th>
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
			
			
}
echo json_encode($data);
?>