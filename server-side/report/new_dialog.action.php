<?php
require_once('../../includes/classes/core.php');

//----------------------------- ცვლადი
$action      = $_REQUEST[act];
$agent	     = $_REQUEST['agent'];
$queue	     = $_REQUEST['queuet'];
$dialog_date = $_REQUEST['dialog_date'];
$start_date  = $_REQUEST['start_time'];
$end_date    = $_REQUEST['end_time'];
// ----------------------------------
$data		= array('page' => array(
    'answear_dialog' => ''
));
switch ($action) {
    case 'answear_dialog_table':        
        	$count = 		$_REQUEST['count'];
        	$hidden = 		$_REQUEST['hidden'];
        	$rResult = mysql_query("SELECT 	call_datetime,
                            				call_datetime,
                            				source,
                            				dst_queue,
                            				dst_extension,
                            				SEC_TO_TIME(duration),
                            				CONCAT('<p onclick=play(', '\'',DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\'',  ')>მოსმენა</p>', '<a download=\"audio.wav\" href=\"http://212.72.155.176:9191/records/', DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\">ჩამოტვირთვა</a>') AS `file`
        	                        FROM 	`asterisk_incomming`
                                    WHERE disconnect_cause != 'ABANDON' 
                                    AND 	DATE(`call_datetime`) = '$dialog_date'
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
    break;
    case 'answear_dialog':
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
                <table class="display" id="new_dealog_table" >
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
                </table>';
    break;
    case 'unanswear_dialog_table':
        $count = 		$_REQUEST['count'];
        $hidden = 		$_REQUEST['hidden'];
        $rResult = mysql_query("SELECT 	call_datetime,
                                        call_datetime,
                                        source,
                                        dst_queue,
                                        SEC_TO_TIME(wait_time)
                                FROM 	`asterisk_incomming`
                                WHERE   disconnect_cause = 'ABANDON'
                                AND 	DATE(`call_datetime`) = '$dialog_date'");
        
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
        break;
    case 'unanswear_dialog':
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
                <table class="display" id="new_dealog_table_1" >
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 100%;">თარიღი</th>
                            <th style="width: 100%;">წყარო</th>
                            <th style="width: 100%;">ადრესატი</th>
                            <th style="width: 100%;">დრო</th>
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
                        </tr>
                    </thead>
                </table>';
        break;
        
//REPORT HOUR
        
    case 'answear_hour_dialog_table':
        $count = 		$_REQUEST['count'];
        $hidden = 		$_REQUEST['hidden'];
        $rResult = mysql_query("SELECT 	call_datetime,
                                        call_datetime,
                                        source,
                                        dst_queue,
                                        dst_extension,
                                        SEC_TO_TIME(duration),
                                        CONCAT('<p onclick=play(', '\'',DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\'',  ')>მოსმენა</p>', '<a download=\"audio.wav\" href=\"http://212.72.155.176:9191/records/', DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\">ჩამოტვირთვა</a>') AS `file`
                                FROM 	`asterisk_incomming`
                                WHERE   disconnect_cause != 'ABANDON'
                                AND 	DATE(`call_datetime`) >= '$start_date'
                                AND 	DATE(`call_datetime`) <= '$end_date'
                                AND 	HOUR(`call_datetime`) = '$dialog_date'
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
    break;
    case 'answear_hour_dialog':
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
            <table class="display" id="new_dealog_hour_table" >
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
            </table>';
    break;
    case 'unanswear_hour_dialog_table':
        $count = 		$_REQUEST['count'];
        $hidden = 		$_REQUEST['hidden'];
        $rResult = mysql_query("SELECT 	call_datetime,
                                        call_datetime,
                                        source,
                                        dst_queue,
                                        SEC_TO_TIME(wait_time)
                                FROM 	`asterisk_incomming`
                                WHERE   disconnect_cause = 'ABANDON'
                                AND 	DATE(`call_datetime`) >= '$start_date'
                                AND 	DATE(`call_datetime`) <= '$end_date'
                                AND 	HOUR(`call_datetime`) = '$dialog_date'");
    
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
    break;
    case 'unanswear_hour_dialog':
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
            <table class="display" id="new_dealog_hour_table_1" >
                <thead>
                    <tr id="datatable_header">
                        <th>ID</th>
                        <th style="width: 100%;">თარიღი</th>
                        <th style="width: 100%;">წყარო</th>
                        <th style="width: 100%;">ადრესატი</th>
                        <th style="width: 100%;">დრო</th>
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
                    </tr>
                </thead>
            </table>';
    break;

//REPORT DAYOFWEEK

    case 'answear_dayofweek_dialog_table':
        $count = 		$_REQUEST['count'];
        $hidden = 		$_REQUEST['hidden'];
        $rResult = mysql_query("SELECT 	call_datetime,
            call_datetime,
            source,
            dst_queue,
            dst_extension,
            SEC_TO_TIME(duration),
            CONCAT('<p onclick=play(', '\'',DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\'',  ')>მოსმენა</p>', '<a download=\"audio.wav\" href=\"http://212.72.155.176:9191/records/', DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\">ჩამოტვირთვა</a>') AS `file`
            FROM 	`asterisk_incomming`
            WHERE   disconnect_cause != 'ABANDON'
            AND 	DATE(`call_datetime`) >= '$start_date'
            AND 	DATE(`call_datetime`) <= '$end_date'
            AND 	CASE
						WHEN 'კვირა' = '$dialog_date' THEN DAYOFWEEK(call_datetime) = 1
						WHEN 'ორშაბათი' = '$dialog_date' THEN DAYOFWEEK(call_datetime) = 2
						WHEN 'სამშაბათი' = '$dialog_date' THEN DAYOFWEEK(call_datetime) = 3
						WHEN 'ოთხშაბათი' = '$dialog_date' THEN DAYOFWEEK(call_datetime) = 4
						WHEN 'ხუთშაბათი' = '$dialog_date' THEN DAYOFWEEK(call_datetime) = 5
						WHEN 'პარასკევი' = '$dialog_date' THEN DAYOFWEEK(call_datetime) = 6
						WHEN 'შაბათი' = '$dialog_date' THEN DAYOFWEEK(call_datetime) = 7
					END
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
        break;
    case 'answear_dayofweek_dialog':
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
            <table class="display" id="new_dealog_dayofweek_table" >
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
            </table>';
        break;
    case 'unanswear_dayofweek_dialog_table':
        $count = 		$_REQUEST['count'];
        $hidden = 		$_REQUEST['hidden'];
        $rResult = mysql_query("SELECT 	call_datetime,
            call_datetime,
            source,
            dst_queue,
            SEC_TO_TIME(wait_time)
            FROM 	`asterisk_incomming`
            WHERE   disconnect_cause = 'ABANDON'
            AND 	DATE(`call_datetime`) >= '$start_date'
            AND 	DATE(`call_datetime`) <= '$end_date'
            AND 	CASE
						WHEN 'კვირა' = '$dialog_date' THEN DAYOFWEEK(call_datetime) = 1
						WHEN 'ორშაბათი' = '$dialog_date' THEN DAYOFWEEK(call_datetime) = 2
						WHEN 'სამშაბათი' = '$dialog_date' THEN DAYOFWEEK(call_datetime) = 3
						WHEN 'ოთხშაბათი' = '$dialog_date' THEN DAYOFWEEK(call_datetime) = 4
						WHEN 'ხუთშაბათი' = '$dialog_date' THEN DAYOFWEEK(call_datetime) = 5
						WHEN 'პარასკევი' = '$dialog_date' THEN DAYOFWEEK(call_datetime) = 6
						WHEN 'შაბათი' = '$dialog_date' THEN DAYOFWEEK(call_datetime) = 7
					END");
    
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
        break;
    case 'unanswear_dayofweek_dialog':
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
            <table class="display" id="new_dealog_dayofweek_table_1" >
                <thead>
                    <tr id="datatable_header">
                        <th>ID</th>
                        <th style="width: 100%;">თარიღი</th>
                        <th style="width: 100%;">წყარო</th>
                        <th style="width: 100%;">ადრესატი</th>
                        <th style="width: 100%;">დრო</th>
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
                    </tr>
                </thead>
            </table>';
        break;
        
//REPORT UNANSWER CALL IN ANSWER CALL
    case 'answear_un_dialog_table':
        $count = 		$_REQUEST['count'];
        $hidden = 		$_REQUEST['hidden'];
        $rResult = mysql_query("SELECT 	call_datetime,
                                        call_datetime,
                                        source,
                                        dst_queue,
                                        dst_extension,
                                        SEC_TO_TIME(duration),
                                        CONCAT('<p onclick=play(', '\'',DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\'',  ')>მოსმენა</p>', '<a download=\"audio.wav\" href=\"http://212.72.155.176:9191/records/', DATE_FORMAT(DATE(call_datetime),'%Y/%m/%d/'), file_name, '\">ჩამოტვირთვა</a>') AS `file`
                                FROM 	`asterisk_incomming`
                                WHERE   disconnect_cause != 'ABANDON'
                                AND 	DATE(`call_datetime`) >= '$start_date'
                                AND 	DATE(`call_datetime`) <= '$end_date'
                                AND 	source = '$dialog_date'
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
        break;
    case 'answear_un_dialog':
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
            <table class="display" id="new_dealog_un_table" >
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
            </table>';
        break;
    default:
        $data = 'Action is Null';
}


echo json_encode($data);

?>