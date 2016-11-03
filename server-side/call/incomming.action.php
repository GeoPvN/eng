<?php

// MySQL Connect Link
require_once('../../includes/classes/core.php');
 
// Main Strings
$action                     = $_REQUEST['act'];
$error                      = '';
$data                       = '';
$user_id	                = $_SESSION['USERID'];
$open_number                = $_REQUEST['open_number'];
$queue                      = $_REQUEST['queue'];
$scenario_id                = $_REQUEST['scenario_id'];
 
// Incomming Call Dialog Strings
$hidden_id                  = $_REQUEST['id'];
$incomming_id               = $_REQUEST['incomming_id'];
$incomming_date             = $_REQUEST['incomming_date'];
$incomming_phone            = $_REQUEST['incomming_phone'];
$incomming_cat_1            = $_REQUEST['incomming_cat_1'];
$incomming_cat_1_1          = $_REQUEST['incomming_cat_1_1'];
$incomming_cat_1_1_1        = $_REQUEST['incomming_cat_1_1_1'];
$incomming_comment          = $_REQUEST['incomming_comment'];
$inc_status_id              = $_REQUEST['inc_status_id'];

$source_info_id             = $_REQUEST['source_info_id'];
$service_center_id          = $_REQUEST['service_center_id'];
$branch_id                  = $_REQUEST['branch_id'];
$in_district_id             = $_REQUEST['in_district_id'];
$in_type_id                 = $_REQUEST['in_type_id'];
$cl_id                      = $_REQUEST['cl_id'];
$cl_name                    = $_REQUEST['cl_name'];
$cl_ab                      = $_REQUEST['cl_ab'];
$cl_ab_num                  = $_REQUEST['cl_ab_num'];
$cl_addres                  = $_REQUEST['cl_addres'];
$cl_phone                   = $_REQUEST['cl_phone'];
$cl_debt                    = $_REQUEST['cl_debt'];

$task_type_id			    = $_REQUEST['task_type_id'];
$task_start_date		    = $_REQUEST['task_start_date'];
$task_end_date			    = $_REQUEST['task_end_date'];
$task_departament_id	    = $_REQUEST['task_departament_id'];
$task_recipient_id		    = $_REQUEST['task_recipient_id'];
$task_priority_id		    = $_REQUEST['task_priority_id'];
$task_controler_id		    = $_REQUEST['task_controler_id'];
$task_status_id		        = $_REQUEST['task_status_id'];
$task_description		    = $_REQUEST['task_description'];
$task_note			        = $_REQUEST['task_note'];
$task_send                  = $_REQUEST['task_send'];

switch ($action) {
	case 'get_add_page':
		$page		= GetPage('',increment(incomming_call));
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$page		= GetPage(Getincomming($hidden_id,$open_number),'',$open_number,$queue);
		$data		= array('page'	=> $page);

		break;
    case 'task_dep':
        $page 		= get_task_dep($task_departament_id);
        $data		= array('get_dep_user'	=> $page);
    
        break;
	case 'get_list':
        $count        = $_REQUEST['count'];
		$hidden       = $_REQUEST['hidden'];
		$start_date   = $_REQUEST['start_date'];
		$end_date     = $_REQUEST['end_date'];
		$operator_id  = $_REQUEST['operator_id'];
		$tab_id       = $_REQUEST['tab_id'];
		$filter_1     = $_REQUEST['filter_1'];
		$filter_2     = $_REQUEST['filter_2'];
		$filter_3     = $_REQUEST['filter_3'];
		$filter_4     = $_REQUEST['filter_4'];
		$filter_5     = $_REQUEST['filter_5'];
		$filter_6     = $_REQUEST['filter_6'];
		$filter_7     = $_REQUEST['filter_7'];
		$filter_8     = $_REQUEST['filter_8'];
		$filter_9     = $_REQUEST['filter_9'];
		$user_inf     = mysql_fetch_array(mysql_query(" SELECT branch_id,service_center_id
                                                        FROM `user_info`
                                                        WHERE user_id = $operator_id"));
		// OPERATOR CHECKER
		if($_REQUEST[user_info_id] == 1){
    		if($operator_id != 0){
    		    $op_check = " AND `incomming_call`.`user_id` = '$operator_id'";
    		}else{
    		    $op_check = '';		    
    		}
		}else if($_REQUEST[user_info_id] == 2){

		        $op_check = " AND `user_info`.`branch_id` = '$user_inf[0]'";

		}else if($_REQUEST[user_info_id] == 3){

		        $op_check = " AND `user_info`.`service_center_id` = '$user_inf[1]'";

		}else if($_REQUEST[user_info_id] == 4){

		        $op_check = "";

		}
		
		// STATUS CHECKER
		if($tab_id != 0){
		    $tab_check = " AND `incomming_call`.`inc_status_id` = '$tab_id'";
		}else{
		    $tab_check = '';
		}
		
        // INCOMMING DONE
		if($filter_1 == 1){
		    $check_1 = 1;
		}else{
		    $check_1 = 0;
		}
		
		// INCOMMING UNDONE
		if($filter_2 == 2){
		    $check_2 = 2;
		}else{
		    $check_2 = 0;
		}
		
		// INCOMMING UNANSSWER
		if($filter_3 == 3){
		    $check_3 = 3;
		}else{
		    $check_3 = 0;
		}
		
		// შეხვედრა
		if($filter_4 == 4){
		    $check_4 = 2;
		}else{
		    $check_4 = 0;
		}
		
		// ინტერნეტი
		if($filter_5 == 5){
		    $check_5 = 4;
		}else{
		    $check_5 = 0;
		}
		
		// ტელეფონი
		if($filter_6 == 6){
		    $check_6 = 1;
		}else{
		    $check_6 = 0;
		}
		
		// გამცხადებელი
		if($filter_7 != ''){
		    $check_7 = " AND personal_info.cl_name LIKE '%$filter_7%'";
		}else{
		    $check_7 = '';
		}
		
		if($check_4 != 0 || $check_5 != 0 || $check_6 != 0){
		    $get_check = " AND personal_info.source_info_id IN($check_4,$check_5,$check_6)";
		}else{
		    $get_check = "";
		}
		
		if($filter_1 != '' || $filter_2 != '' || $filter_3 != ''){
		    $main_status = " AND (CASE WHEN (`incomming_call`.`inc_status_id` IS NOT NULL) THEN 1 WHEN (isnull(`incomming_call`.`inc_status_id`) AND (`asterisk_incomming`.`disconnect_cause` <> 'ABANDON')) THEN 2 WHEN (`asterisk_incomming`.`disconnect_cause` = 'ABANDON') THEN 3 END) IN(0,$check_1,$check_2,$check_3)";
		}else{
		    $main_status = '';
		}

// 	  	$rResult = mysql_query("SELECT 	IF(main_status = 3,'',id) AS id,
//                         				id,
//                         				date,
//                         				queue,
//                         				cl_ab,
//                         				cl_ab_num,
//                         				sc,
//                             	  	    ic1,
//                             	  	    inst,
//                             	  	    IF(main_status = 3,'',file) AS file,
// 	  	                                CASE 
//                     						WHEN main_status = 1 then '<div class=\"gr\"></div>'
//                     						WHEN main_status = 2 then '<div class=\"ye\"></div>'
//                     						WHEN main_status = 3 then '<div class=\"re\"></div>'
//                         				END AS `status_color`
//                                 FROM 	calls
// 	  	                        WHERE DATE(date) >= '$start_date' AND DATE(date) <= '$end_date' AND NOT ISNULL(main_status) $op_check $tab_check $main_status
// 	  	                        ORDER BY date DESC");
	  
// 		$data = array(
// 				"aaData"	=> array()
// 		);

// 		while ( $aRow = mysql_fetch_array( $rResult ) )
// 		{
// 			$row = array();
// 			for ( $i = 0 ; $i < $count ; $i++ )
// 			{
// 				/* General output */
// 				$row[] = $aRow[$i];
// // 				if($i == ($count - 2)){
// // 				    $row[] = '<div class="'.(($aRow[10]==1)?'gr':(($aRow[10]==2)?'ye':'re')).'"></div>';
// // 				}
// 			}
// 			$data['aaData'][] = $row;
// 		}
		
		// DB table to use
		$table = 'incomming_call';
		
		// Table's primary key
		$primaryKey = '`incomming_call`.`id`';
		
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		
		$columns = array(
		    array( 'db' => 'IF ((`asterisk_incomming`.`disconnect_cause` = "ABANDON"),"",`incomming_call`.`id`) AS `original_id`', 		'dt' => 0 ),
		    array( 'db' => '`incomming_call`.`id`', 		        'dt' => 1 ),
		    array( 'db' => '`incomming_call`.`date`',  			'dt' => 2 ),
		    array( 'db' => '`asterisk_incomming`.`source`',  			'dt' => 3 ),
		    array( 'db' => '`personal_info`.`cl_ab`',  			'dt' => 4 ),
		    array( 'db' => '`personal_info`.`cl_ab_num`',         'dt' => 5 ),
		    array( 'db' => '`service_center`.`name`',     		    'dt' => 6 ),
		    array( 'db' => '`ic1`.`name`',	            'dt' => 7 ),
		    array( 'db' => '`inc_status`.`name`',		        'dt' => 8 ),
		    array( 'db' => 'IF ((`asterisk_incomming`.`disconnect_cause` = "ABANDON"),"",concat("<p class=clickmetolisten gotoplay=",date_format(cast(`asterisk_incomming`.`call_datetime` AS date),"%Y/%m/%d/"),`asterisk_incomming`.`file_name`,">Listen</p>"))',	            'dt' => 9 ),
		    array( 'db' => '(CASE WHEN (`incomming_call`.`inc_status_id` IS NOT NULL) THEN	"<div class=gr></div>"	WHEN (isnull(`incomming_call`.`inc_status_id`)	AND (`asterisk_incomming`.`disconnect_cause` <> "ABANDON")) THEN "<div class=ye></div>"	WHEN (`asterisk_incomming`.`disconnect_cause` = "ABANDON") THEN	"<div class=re></div>"	END) AS `status_color`',	    'dt' => 10 )
		
		);
		
		// SQL server connection information
		$sql_details = array(
		    'user' => 'root',
		    'pass' => 'Gl-1114',
		    'db'   => 'epro',
		    'host' => 'localhost'
		);
		
		
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		*/
		//mysql_close();
		require( '../../includes/ssp.class.php' );
		
		$where_param = "LEFT JOIN `personal_info` ON `incomming_call`.`id` = `personal_info`.`incomming_call_id`
LEFT JOIN `asterisk_incomming` ON `incomming_call`.`asterisk_incomming_id` = `asterisk_incomming`.`id`
LEFT JOIN `users` ON `users`.`id` = `incomming_call`.`user_id`
LEFT JOIN `user_info` ON `users`.`id` = `user_info`.`user_id`
LEFT JOIN `inc_status` ON `inc_status`.`id` = `incomming_call`.`inc_status_id`
LEFT JOIN `service_center` ON `personal_info`.`service_center_id` = `service_center`.`id`
LEFT JOIN `info_category` `ic1` ON `incomming_call`.`cat_1` = `ic1`.`id`
		WHERE DATE(date) >= '$start_date' AND DATE(date) <= '$end_date' AND NOT ISNULL((CASE WHEN (`incomming_call`.`inc_status_id` IS NOT NULL) THEN 1 WHEN (isnull(`incomming_call`.`inc_status_id`) AND (`asterisk_incomming`.`disconnect_cause` <> 'ABANDON')) THEN 2 WHEN (`asterisk_incomming`.`disconnect_cause` = 'ABANDON') THEN 3 END)) $get_check $check_7 $op_check $tab_check $main_status";

		$data = SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where_param, "");
		
	    break;
    case 'get_list_log' :
        $count	= $_REQUEST['count'];
        $hidden	= $_REQUEST['hidden'];
    
//         $rResult = mysql_query("SELECT 	`logs`.`id`,
//                         				`logs`.`row_id`,
//                         				`logs`.`date`,
//                         				IF(`logs`.`event` = 1,'დამატება',IF(`logs`.actived = 0,'წაშლა','განახლება')) AS `act`,
//                         				`user_info`.`name`,
//     									CASE
//     											WHEN `logs`.`collumn` = 'date' then 'თარიღი'
//                                                 WHEN `logs`.`collumn` = 'phone' then 'ტელეფონი'
//                                                 WHEN `logs`.`collumn` = 'cat_1' then 'ზარის კატეგორია'
//                                                 WHEN `logs`.`collumn` = 'cat_1_1' then 'ზარის ქვე-კატეგორია 1'
//                                                 WHEN `logs`.`collumn` = 'cat_1_1_1' then 'ზარის ქვე-კატეგორია 2'
//                                                 WHEN `logs`.`collumn` = 'inc_status' then 'რეაგირება'
//                                                 WHEN `logs`.`collumn` = 'comment' then 'დამატებითი ინფორმაცია'
//                                                 WHEN `logs`.`collumn` = 'source_info' then 'მეთოდი'
//                                                 WHEN `logs`.`collumn` = 'service_center' then 'მომსახურების ცენტრი'
//                                                 WHEN `logs`.`collumn` = 'branch' then 'ფილიალი'
//                                                 WHEN `logs`.`collumn` = 'in_district' then 'უბანი'
//                                                 WHEN `logs`.`collumn` = 'in_type' then 'ტიპი'
//                                                 WHEN `logs`.`collumn` = 'cl_id' then 'კანცელარიის ნომერი'
//                                                 WHEN `logs`.`collumn` = 'cl_name' then 'განმცხადებელი'
//                                                 WHEN `logs`.`collumn` = 'cl_ab' then 'აბონენტი'
//                                                 WHEN `logs`.`collumn` = 'cl_ab_num' then 'აბონენტის ნომერი'
//                                                 WHEN `logs`.`collumn` = 'cl_addres' then 'მისამართი'
//                                                 WHEN `logs`.`collumn` = 'cl_phone' then 'ტელეფონის ნომერი'
//     									END AS `colum`,
//                         				`logs`.`old_value`,
//                         				`logs`.`new_value`
//                                 FROM    `logs`
//                                 JOIN    `users` ON `logs`.user_id = users.id
//                                 JOIN    `user_info` ON users.id = user_info.user_id
//                                 WHERE   `logs`.`table` = 'incomming_call' AND DATE(date) >= '$_REQUEST[start_date]' AND DATE(date) <= '$_REQUEST[end_date]'");
    
//         $data = array(
//             "aaData"	=> array()
//         );
    
//         while ( $aRow = mysql_fetch_array( $rResult ) )
//         {
//             $row = array();
//             for ( $i = 0 ; $i < $count ; $i++ )
//             {
//                 /* General output */
//                 $row[] = $aRow[$i];
//             }
//             $data['aaData'][] = $row;
//         }

        // DB table to use
        $table = 'logs';
        
        // Table's primary key
        $primaryKey = '`logs`.`id`';
        
        // Array of database columns which should be read and sent back to DataTables.
        // The `db` parameter represents the column name in the database, while the `dt`
        // parameter represents the DataTables column identifier. In this case simple
        // indexes
        
        $columns = array(
            array( 'db' => '`logs`.`id`', 		'dt' => 0 ),
            array( 'db' => '`logs`.`row_id`', 		        'dt' => 1 ),
            array( 'db' => '`logs`.`date`',  			'dt' => 2 ),
            array( 'db' => 'IF(`logs`.`event` = 1,"დამატება",IF(`logs`.actived = 0,"წაშლა","განახლება"))',  			'dt' => 3 ),
            array( 'db' => '`user_info`.`name`',  			'dt' => 4 ),
            array( 'db' => '(CASE WHEN `logs`.`collumn` = "date" then "თარიღი" WHEN `logs`.`collumn` = "phone" then "ტელეფონი" WHEN `logs`.`collumn` = "cat_1" then "ზარის კატეგორია" WHEN `logs`.`collumn` = "cat_1_1" then "ზარის ქვე-კატეგორია 1" WHEN `logs`.`collumn` = "cat_1_1_1" then "ზარის ქვე-კატეგორია 2" WHEN `logs`.`collumn` = "inc_status" then "რეაგირება" WHEN `logs`.`collumn` = "comment" then "დამატებითი ინფორმაცია" WHEN `logs`.`collumn` = "source_info" then "მეთოდი" WHEN `logs`.`collumn` = "service_center" then "მომსახურების ცენტრი" WHEN `logs`.`collumn` = "branch" then "ფილიალი" WHEN `logs`.`collumn` = "in_district" then "უბანი" WHEN `logs`.`collumn` = "in_type" then "ტიპი" WHEN `logs`.`collumn` = "cl_id" then "კანცელარიის ნომერი" WHEN `logs`.`collumn` = "cl_name" then "განმცხადებელი" WHEN `logs`.`collumn` = "cl_ab" then "აბონენტი" WHEN `logs`.`collumn` = "cl_ab_num" then "აბონენტის ნომერი" WHEN `logs`.`collumn` = "cl_addres" then "მისამართი" WHEN `logs`.`collumn` = "cl_phone" then "ტელეფონის ნომერი" END)',         'dt' => 5 ),
            array( 'db' => '`logs`.`old_value`',     		    'dt' => 6 ),
            array( 'db' => '`logs`.`new_value`',	            'dt' => 7 )
        );
        
        // SQL server connection information
        $sql_details = array(
            'user' => 'root',
            'pass' => 'Gl-1114',
            'db'   => 'epro',
            'host' => 'localhost'
        );
        
        
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP
         * server-side, there is no need to edit below this line.
        */
        //mysql_close();
        require( '../../includes/ssp.class.php' );
        
        $where_param = "JOIN    `users` ON `logs`.user_id = users.id
                        JOIN    `user_info` ON users.id = user_info.user_id
                        WHERE   `logs`.`table` = 'incomming_call' AND DATE(date) >= '$_REQUEST[start_date]' AND DATE(date) <= '$_REQUEST[end_date]'";
        
        $data = SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where_param, "");
    
        break;
    case 'get_list_history' :
        $count	= $_REQUEST['count'];
        $hidden	= $_REQUEST['hidden'];
        $check_ab = $_REQUEST['check_ab'];
        $start_check = $_REQUEST['start_check'];
        $end_check = $_REQUEST['end_check'];
        $task_status_ck = $_REQUEST['task_status_ck'];
        
        if($check_ab == ''){
            $checker = "AND personal_info.cl_ab_num = '$_REQUEST[cl_ab_num]'";
        }else{
            $checker = "AND personal_info.cl_ab like '%$check_ab%'";
        }
        if($task_status_ck == 0){
            $checker_st = "";
        }else{
            $checker_st = "AND task_status.id = $task_status_ck";
        }
    
        $rResult = mysql_query("SELECT 	task.id,
                                        date,
                                        personal_info.cl_ab_num,
                                        phone,
                                        task.task_description,
                                        task.task_note,
                                        task_status.`name`
                                FROM 	incomming_call
                                JOIN 	personal_info ON incomming_call.id = personal_info.incomming_call_id
                                JOIN 	task ON incomming_call.id = task.incomming_call_id
                                JOIN 	task_status ON task.task_status_id = task_status.id
                                WHERE 	DATE(date) >= '$start_check' AND DATE(date) <= '$end_check' $checker $checker_st");
    
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
        
    case 'check_user':
        $user	   = $_SESSION['USERID'];
        $extention = $_REQUEST['extention'];
        $check     = 0;
        
        $res_user_id = mysql_fetch_assoc(mysql_query("SELECT id
                                                      FROM  `users`
                                                      WHERE  extension_id='$extention'
                                                      AND    users.logged=1"));
        if ($user == $res_user_id[id]){$check=1;}
        $data = array('check' => $check);
        break;
    case 'send_sms':
        $page		= GetSmsSendPage();
        $data		= array('page'	=> $page);
    
        break;
    case 'send_mail':
        $page		= GetMailSendPage();
        $data		= array('page'	=> $page);
    
        break;
    case 'get_list_mail':
        $count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];

		    $rResult = mysql_query("SELECT id,
                            		        date,
                            		        address,
                            		        `subject`,
                            		        if(`status`=3,'გასაგზავნია',IF(`status`=2,'გაგზავნილია',''))
                    		        FROM `sent_mail`
                    		        WHERE incomming_call_id = $_REQUEST[incomming_id] AND status != 1");
		
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
				if($i == ($count - 1)){
				    $row[] = '<div class="callapp_checkbox">
                                  <input type="checkbox" id="callapp_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                                  <label for="callapp_checkbox_'.$aRow[$hidden].'"></label>
                              </div>';
				}
			}
			$data['aaData'][] = $row;
		}
	
    
        break;
    case 'get_list_sms':
        $count = 		$_REQUEST['count'];
        $hidden = 		$_REQUEST['hidden'];
    
        $rResult = mysql_query("SELECT 	id,
                        				date,
                        				phone,
                        				`content`,
                        				if(`status`=2,'გასაგზავნია',IF(`status`=1,'გაგზავნილია',''))
                                FROM `sent_sms`
                                WHERE incomming_call_id = $_REQUEST[incomming_id] AND status = 1");
    
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
                if($i == ($count - 1)){
                    $row[] = '<div class="callapp_checkbox">
                              <input type="checkbox" id="callapp_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                              <label for="callapp_checkbox_'.$aRow[$hidden].'"></label>
                          </div>';
                }
            }
            $data['aaData'][] = $row;
        }
    
    
        break;
    case 'get_list_quest':
        $count = 		$_REQUEST['count'];
        $hidden = 		$_REQUEST['hidden'];
    
        $rResult = mysql_query("SELECT  id,
                                        quest,
                                        answer
                                FROM `queries`
                                WHERE actived = 1");
    
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
                if($i == ($count - 1)){
                    $row[] = '<div class="callapp_checkbox">
                              <input type="checkbox" id="callapp_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                              <label for="callapp_checkbox_'.$aRow[$hidden].'"></label>
                          </div>';
                }
            }
            $data['aaData'][] = $row;
        }
    
    
        break;
    case 'cat_2':
        $page		= get_cat_1_1($_REQUEST['cat_id'],'');
        $data		= array('page'	=> $page);
    
        break;
    case 'cat_3':
        $page		= get_cat_1_1_1($_REQUEST['cat_id'],'');
        $data		= array('page'	=> $page);
    
        break;
    case 'sc':
        $page		= getbranch_id($_REQUEST['cat_id']);
        $page_l     = getin_district_id($_REQUEST['cat_id'],'');
        $data		= array('page'	=> $page, 'page_l' => $page_l);
    
        break;
    case 'save_incomming':
        if($hidden_id == ''){
            incomming_insert($user_id,$incomming_id,$incomming_date,$incomming_phone,$incomming_cat_1,$incomming_cat_1_1,$incomming_cat_1_1_1,$incomming_comment,$source_info_id, $service_center_id, $branch_id, $in_district_id, $in_type_id, $cl_id, $cl_name, $cl_ab, $cl_ab_num, $cl_addres, $cl_phone,$scenario_id,$inc_status_id,$cl_debt);
        }else{
            incomming_update($user_id,$hidden_id,$incomming_phone,$incomming_cat_1,$incomming_cat_1_1,$incomming_cat_1_1_1,$incomming_comment,$source_info_id, $service_center_id, $branch_id, $in_district_id, $in_type_id, $cl_id, $cl_name, $cl_ab, $cl_ab_num, $cl_addres, $cl_phone,$inc_status_id,$cl_debt);
        }
        
        if($hidden_id == ''){
            $inc_id = $incomming_id;
        }else{
            $inc_id = $hidden_id;
        }

		if($task_send == 1){
		    $task_ck = mysql_fetch_array(mysql_query("SELECT id FROM task WHERE incomming_call_id = $inc_id"));
		    if($task_ck[0] == ''){
		    mysql_query(" INSERT INTO `task`
		                  (`user_id`, `incomming_call_id`, `task_recipient_id`, `task_controler_id`, `task_date`, `task_start_date`, `task_end_date`, `task_departament_id`, `task_type_id`, `task_priority_id`, `task_description`, `task_note`, `task_status_id`)
		                  VALUES
		                  ('$user_id', '$inc_id', 0, 0, NOW(), '$task_start_date', '$task_end_date', '$task_departament_id', '$task_type_id', '$task_priority_id', '$task_description', '$task_note', '$task_status_id');");
		    }else{
		        mysql_query("UPDATE `task` SET
                                    `task_start_date`='$task_start_date',
                                    `task_end_date`='$task_end_date',
                                    `task_departament_id`='$task_departament_id',
                                    `task_type_id`='$task_type_id',
                                    `task_priority_id`='$task_priority_id',
                                    `task_description`='$task_description',
                                    `task_note`='$task_note',
                                    `task_status_id`='$task_status_id'
                            WHERE `id`='$task_ck[0]';");
		    }
		}
		
		$have_phone = mysql_num_rows(mysql_query("SELECT phone FROM `caller_history` WHERE phone = '$incomming_phone'"));

		if($have_phone == 0){
		    if($incomming_phone != ''){
		    mysql_query(" INSERT INTO `caller_history`
		                  (`name`, `phone`, `client_number`, `client_phone`)
        		          VALUES
        		          ('$cl_ab', '$incomming_phone', '$cl_ab_num', '$cl_phone');");
		    }
		}else{
		    if($incomming_phone != ''){
		    mysql_query("UPDATE `caller_history` SET 
                                `name`='$cl_ab',
                                `client_number`='$cl_ab_num',
                                `client_phone`='$cl_phone'
                         WHERE `phone`='$incomming_phone';");
		    }
		}
        break;
    case 'get_shablon':
    
        $data		 = array('shablon' => getShablon());
    
        break;
        
    case 'log':
        $cl_ab_num_value    = $_REQUEST['cl_ab_num_value'];
        $cl_ab_value        = $_REQUEST['cl_ab_value'];
        $row_id             = $_REQUEST['row_id'];
        
        mysql_query("INSERT INTO `epro`.`service_log` (`cl_ab_num_value`, `cl_ab_value`, `row_id`,`datetime`) VALUES ('$cl_ab_num_value', '$cl_ab_value', $row_id, NOW())");
        break;
        
    case 'menu_clicks':
        $menu_name   = $_REQUEST['menu_name'];
        $row_id      = $_REQUEST['row_id'];
    
        mysql_query("INSERT INTO `epro`.`log_menu_clicks` (`datetime`, `menu_name`, `row_id`) VALUES (NOW(), '$menu_name', $row_id);");
        break;
        
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Request Functions
* ******************************
*/

function incomming_insert($user_id,$incomming_id,$incomming_date,$incomming_phone,$incomming_cat_1,$incomming_cat_1_1,$incomming_cat_1_1_1,$incomming_comment,$source_info_id, $service_center_id, $branch_id, $in_district_id, $in_type_id, $cl_id, $cl_name, $cl_ab, $cl_ab_num, $cl_addres, $cl_phone,$scenario_id,$inc_status_id,$cl_debt){
    $incomming_id = increment(incomming_call);
    mysql_query("INSERT INTO    `incomming_call` 
                 (`id`,`user_id`,`date`,`phone`,`cat_1`,`cat_1_1`,`cat_1_1_1`,`comment`,`scenario_id`,`inc_status_id`)
                 VALUES
                 ('$incomming_id','$user_id','$incomming_date','$incomming_phone','$incomming_cat_1','$incomming_cat_1_1','$incomming_cat_1_1_1','$incomming_comment','$scenario_id','$inc_status_id')");
    
    mysql_query("INSERT INTO `personal_info` 
                 (`user_id`, `incomming_call_id`, `source_info_id`, `service_center_id`, `branch_id`, `in_district_id`, `in_type_id`, `cl_id`, `cl_name`, `cl_ab`, `cl_ab_num`, `cl_addres`, `cl_phone`, `cl_debt`)
                 VALUES
                 ('$user_id', '$incomming_id', '$source_info_id', '$service_center_id', '$branch_id', '$in_district_id', '$in_type_id', '$cl_id', '$cl_name', '$cl_ab', '$cl_ab_num', '$cl_addres', '$cl_phone', '$cl_debt');");
}

function incomming_update($user_id,$hidden_id,$incomming_phone,$incomming_cat_1,$incomming_cat_1_1,$incomming_cat_1_1_1,$incomming_comment,$source_info_id, $service_center_id, $branch_id, $in_district_id, $in_type_id, $cl_id, $cl_name, $cl_ab, $cl_ab_num, $cl_addres, $cl_phone,$inc_status_id,$cl_debt){
    mysql_query("UPDATE `incomming_call` SET 
                        `user_id`='$user_id',
                        `phone`='$incomming_phone',
                        `cat_1`='$incomming_cat_1',
                        `cat_1_1`='$incomming_cat_1_1',
                        `cat_1_1_1`='$incomming_cat_1_1_1',
                        `comment`='$incomming_comment',
                        `inc_status_id`='$inc_status_id'
                 WHERE  `id`='$hidden_id'");
    
    $req = mysql_num_rows(mysql_query("SELECT id FROM personal_info WHERE `incomming_call_id`='$hidden_id'"));
    
    if($req == 0){
        mysql_query("INSERT INTO `personal_info`
            (`user_id`, `incomming_call_id`, `source_info_id`, `service_center_id`, `branch_id`, `in_district_id`, `in_type_id`, `cl_id`, `cl_name`, `cl_ab`, `cl_ab_num`, `cl_addres`, `cl_phone`, `cl_debt`)
            VALUES
            ('$user_id', '$hidden_id', '$source_info_id', '$service_center_id', '$branch_id', '$in_district_id', '$in_type_id', '$cl_id', '$cl_name', '$cl_ab', '$cl_ab_num', '$cl_addres', '$cl_phone', '$cl_debt');");
    }else{
    mysql_query("UPDATE `personal_info` SET
                        `user_id`='$user_id',
                        `source_info_id`='$source_info_id',
                        `service_center_id`='$service_center_id',
                        `branch_id`='$branch_id',
                        `in_district_id`='$in_district_id',
                        `in_type_id`='$in_type_id',
                        `cl_id`='$cl_id',
                        `cl_name`='$cl_name',
                        `cl_ab`='$cl_ab',
                        `cl_ab_num`='$cl_ab_num',
                        `cl_addres`='$cl_addres',
                        `cl_debt`='$cl_debt',
                        `cl_phone`='$cl_phone'
                WHERE   `incomming_call_id`='$hidden_id'");
    }
}

function get_cat_1($id){
    $req = mysql_query("  SELECT  `id`,
                                  `name`
                          FROM `info_category`
                          WHERE actived = 1 AND `parent_id` = 0");
    
    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }
    
    return $data;
    
}

function gethandbook($id,$done_id){
    $req = mysql_query("  SELECT `id`,
                            	 `value`
                          FROM   `scenario_handbook_detail`
                          WHERE  `scenario_handbook_id` = $id AND actived = 1");

    $data .= '<option value="0" >----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $done_id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['value'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['value'] . '</option>';
        }
    }

    return $data;

}

function get_cat_1_1($id,$child_id){
    //echo $id;
    $req = mysql_query("  SELECT  `id`,
                                  `name`
                          FROM `info_category`
                          WHERE actived = 1 AND `parent_id` = $id AND `parent_id` != 0");
    
    $data .= '<option value="0" selected="selected">----</option>';
    $i = 0;
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $child_id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
        $i = 1;
    }
    if($i == 0 && $id > 0){
        $data .= '<option value="999" selected="selected">No Category</option>';
    }
    
    return $data;
}
function get_cat_1_1_1($id,$child_id){
    $req = mysql_query("  SELECT  `id`,
                                  `name`
                          FROM `info_category`
                          WHERE actived = 1 AND `parent_id` = $id AND `parent_id` != 0");
    
    $data .= '<option value="0" selected="selected">----</option>';
    $i = 0;
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $child_id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
        $i = 1;
    }
    if($i == 0 && $id > 0){
        $data .= '<option value="999" selected="selected">No Category</option>';
    }
    
    return $data;
}

function get_IncStatus($inc_status_id){
    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `inc_status`
                            WHERE   `actived` = 1");

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $inc_status_id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}

function Getincomming($hidden_id,$open_number)
{
    if($hidden_id == ''){
        $filter = "incomming_call.`phone` = '$open_number' AND DATE(incomming_call.date) = DATE(NOW())";
    }else{
        $filter = "incomming_call.id =  $hidden_id";
    }

	$res = mysql_fetch_assoc(mysql_query("SELECT    incomming_call.id AS id,
                                    				incomming_call.`date` AS call_date,
                                    				DATE_FORMAT(incomming_call.`date`,'%y-%m-%d') AS `date`,
                                    				incomming_call.`phone`,
                                    				incomming_call.cat_1,
                                    				incomming_call.cat_1_1,
                                    				incomming_call.cat_1_1_1,
                                    				incomming_call.`comment`,
	                                                incomming_call.inc_status_id,
                                    				personal_info.`source_info_id`,
                                    				personal_info.`service_center_id`,
                                    				personal_info.`branch_id`,
                                    				personal_info.`in_district_id`,
                                    				personal_info.`in_type_id`,
                                    				personal_info.`cl_id`,
                                    				personal_info.`cl_name`,
                                    				personal_info.`cl_ab`,
	                                                personal_info.`cl_ab_num`,
	                                                personal_info.`cl_addres`,
                                    				personal_info.`cl_phone`,
                                                    personal_info.`cl_debt`,
	                                                incomming_call.scenario_id AS `inc_scenario_id`,
	                                                asterisk_incomming.dst_queue,
	                                                task.`task_date`,
                                    				task.`task_start_date`,
                                    				task.`task_end_date`,
                                    				task.`task_type_id`,
                                    				task.`task_departament_id`,
                                    				task.`task_recipient_id`,
                                    				task.`task_controler_id`,
                                    				task.`user_id`,
                                    				task.`task_priority_id`,
                                    				task.`task_status_id`,
                                    				task.`task_description`,
                                    				task.`task_note`,
                                    				task.`task_answer`
                                        FROM 	   incomming_call
                                        LEFT JOIN  personal_info ON incomming_call.id = personal_info.incomming_call_id
	                                    LEFT JOIN  asterisk_incomming ON asterisk_incomming.id = incomming_call.asterisk_incomming_id
	                                    LEFT JOIN  task ON task.incomming_call_id = incomming_call.id
                                        WHERE      $filter
                                	    ORDER BY incomming_call.id,personal_info.id DESC
                                        LIMIT 1"));
	return $res;
}

function getStatusTask($id){

    $res = mysql_fetch_assoc(mysql_query("    SELECT 	`id`,
                                                        `name`
                                                FROM    `task_status`
                                                WHERE   `id`=7"));
    
    $data = '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';

    return $data;
}

function getStatusTaskCK(){

    $req = mysql_query("    SELECT 	`id`,
                                    `name`
                            FROM    `task_status`
                            WHERE   `type`=2");
    
    while( $res = mysql_fetch_assoc($req)){
        $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
    }

    return $data;
}

function GetPriority(){

    $req = mysql_query("    SELECT 	`id`,
                                    `name`
                            FROM    `priority`
                            WHERE   `actived` = 1");

    $data .= '<option value="0">-----</option>';
    while( $res = mysql_fetch_assoc($req)){
        $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
    }

    return $data;
}

function GetDepartament($id){

    $req = mysql_query("    SELECT 	`id`,
                                    `name`
                            FROM    `department`
                            WHERE   `actived` = 1");

    $data .= '<option value="0">-----</option>';
    while( $res = mysql_fetch_assoc($req)){
    if($res['id'] == $id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}

function GetTaskType(){

    $req = mysql_query("    SELECT 	`id`,
                                    `name`
                            FROM    `task_type`
                            WHERE   `actived` = 1");

    $data .= '<option value="0">-----</option>';
    while( $res = mysql_fetch_assoc($req)){
        $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
    }

    return $data;
}

function getUsers($id){
    $req = mysql_query("SELECT 	    `users`.`id`,
                                    `user_info`.`name`
                        FROM 		`users`
                        JOIN 		`user_info` ON `users`.`id` = `user_info`.`user_id`
                        WHERE		`users`.`actived` = 1");

    $data .= '<option value="0">-----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}

function get_task_dep($dep_id){
    $req = mysql_query("SELECT 	    `users`.`id`,
                                    `user_info`.`name`
                        FROM 		`users`
                        JOIN 		`user_info` ON `users`.`id` = `user_info`.`user_id`
                        WHERE		`users`.`actived` = 1 AND `user_info`.`dep_id` = $dep_id");
    
    $data .= '<option value="0">-----</option>';
    while( $res = mysql_fetch_assoc($req)){
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
    }
    
    return $data;
}

function getsource_info_id($id){
    if($_SESSION['USERGR'] == 5 || $_SESSION['USERID'] == 6){
        $noId = "AND id != 1";
    }
    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `source_info`
                            WHERE   `actived` = 1 $noId");

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}
function getservice_center_id($id){
    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `service_center`
                            WHERE   `actived` = 1 AND parent_id = 0");

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}
function getbranch_id($id){
    if($id == 0 OR $id == ''){
        $id = '0';
    }else{
        $tt = mysql_fetch_array(mysql_query("SELECT branch_id FROM `service_center` WHERE id = $id"));
    }
    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `branch`
                            WHERE   `actived` = 1 AND id = $tt[0]");

    $data .= '<option value="0" selected="selected">Other</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        }
    }

    return $data;
}
function getin_district_id($id,$chaild_id){

    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `service_center`
                            WHERE   `actived` = 1 AND parent_id = $id");

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $chaild_id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}
function getin_type_id($id){
    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `in_type`
                            WHERE   `actived` = 1");

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}

function GetPage($res,$increment,$open_number,$queue)
{
    
    if($open_number != ''){
        $phone_check = mysql_fetch_array(mysql_query("  SELECT  `name` AS `cl_ab`,
                                                                `client_number` AS `cl_ab_num`,
                                                                `client_phone` AS `cl_phone`
                                                        FROM    `caller_history`
                                                        WHERE   `phone` = '$open_number'"));
    }
//    $phone_check = '';
    if($increment == '' && $res == ''){
        $increment = increment(incomming_call);
    }
    if($queue==''){
        $ch_queue = $res['dst_queue'];
    }else{
        $ch_queue = $queue;
    }
    $dis = '';
    $checked = '';
    if($res != ''){
        $dis='disabled';
    }else{
        $checked = 'checked';
    }
    if($res != '' && $res[client_status] == 1){
        $data .= "<script>client_status('pers')</script>";
    }elseif ($res != '' && $res[client_status] == 2){
        $data .= "<script>client_status('iuri')</script>";
    }
    
    if($_SESSION['USERGR'] == 5 || $_SESSION['USERID'] == 6){
        $disa = "disabled";
    }
    //$rr = mysql_fetch_array(mysql_query("SELECT scenario_id FROM queue WHERE number = '2471707'"));
	$data  .= '
	<div id="dialog-form">
	    <fieldset style="width: 150px;  float: left;">
	       <input id="scenario_id" type="hidden" value="0" />
	       <table class="dialog-form-table">
	           
    	       <tr>
	               <td style="width: 125px;"><label for="incomming_id">Application №</label></td>
	           </tr>
	           <tr>
	               <td><input disabled style="width: 125px;" id="incomming_id" type="text" value="'.(($res['id']=='')?$increment:$res['id']).'"></td>
               </tr>
	           <tr>
	               <td style="width: 125px;"><label for="incomming_date">Date</label></td>
	           </tr>
	           <tr>
	               <td><input disabled style="width: 125px;" id="incomming_date" type="text" value="'.(($res['call_date']=='')?date("Y-m-d H:i:s"):$res['call_date']).'"></td>
               </tr>
	           <tr>
	               <td><label for="incomming_phone">Phone</td>
    	       </tr>
               <tr>
	               <td><input disabled style="width: 125px;" id="incomming_phone" type="text" value="'.$res['phone'].'"></td>
    	       </tr>
	       </table>
	       
	       <table class="dialog-form-table">
	           <tr>
	               <td><label for="inc_status_id">Responses</label></td>
	           </tr>
	           <tr>
	               <td><select id="inc_status_id" style="width: 130px;">'.get_IncStatus($res['inc_status_id']).'</select></td>
	           </tr>
	           <tr>
	               <td><label for="incomming_comment">For more information</label></td>
	           </tr>
	           <tr>
	               <td><textarea id="incomming_comment" style="resize: vertical;width: 125px;height: 285px;">'.$res['comment'].'</textarea></td>
	           </tr>
	       </table>
	       
	    </fieldset>
	    
	    
        <div id="side_menu" style="float: left;height: 585px;width: 80px;margin-left: 10px; background: #272727; color: #FFF;">
	       <spam class="info" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'info\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/info.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">Info</div></spam>
	       <spam class="scenar" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick=""><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/scenar.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">Billing</div></spam>
	       <spam class="task" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'task\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/task.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">Task</div></spam>
	       <spam class="sms" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'sms\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/sms.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">SMS</div></spam>
	       <spam class="mail" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'mail\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/mail.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">E-mail</div></spam>
	       <spam class="record" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'record\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/record.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">Record</div></spam>
	       <spam class="file" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'file\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/file.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">File</div></spam>
	       <spam class="question" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'question\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/question.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">question</div></spam>
	       <spam class="box" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick=""><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/box.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">Chancel<br>lery</div></spam>
       </div>
	    
	    <div style="width: 900px;float: left;margin-left: 10px;" id="right_side">
            <fieldset style="display:block;" id="info">
	            <span class="hide_said_menu">x</span>
	                   
	                    
                        <table class="margin_top_10">
    	                   <tr>
        	                   <td><label style="width: 280px;" for="source_info_id">Source</label></td>
        	                   <td><label style="width: 280px;" for="service_center_id">Service Center</label></td>
        	                   <td><label style="width: 280px;" for="in_district_id">district</label></td>
            	           </tr>
            	           <tr>
            	               <td><select id="source_info_id" style="width: 245px;">'.getsource_info_id($res['source_info_id']).'</select></td>
            	               <td><select id="service_center_id" style="width: 245px;">'.getservice_center_id($res['service_center_id']).'</select></td>
            	               <td><select id="in_district_id" style="width: 245px;">'.getin_district_id($res['service_center_id'],$res['in_district_id']).'</select></td>
            	           </tr> 
	                   </table>
	                   <br>
	                   <hr style="width: 75%;position: absolute;margin-left: -11px;margin-top: 6px;">
	                   <hr style="width: 75%;position: absolute;margin-left: -11px;border: 5px solid #fff;">
	                   <hr style="width: 75%;position: absolute;margin-left: -11px;margin-top: 17px;">
	                   <br>
	                   
	                   <table class="margin_top_10">
                           <tr>
                               <td style="width: 280px;"><label for="cl_id">Chancellery number</label></td>
                               <td style="width: 280px;"><label for="cl_name">The applicant</label></td>
	                           <td style="width: 240px;"><label for="in_type_id">Type</label></td>
                           </tr>
                           <tr>
                               <td><input type="text" style="width: 240px; resize: vertical;" id="cl_id" value="'.$res['cl_id'].'"></td>
                               <td><input type="text" style="width: 240px; resize: vertical;" id="cl_name" value="'.$res['cl_name'].'"></td>
                               <td><select style="width: 245px;" id="in_type_id">'.getin_type_id($res['in_type_id']).'</select></td>
                           </tr>
                           <tr>
                               <td style="width: 280px;"><label for="cl_ab_num">Customer Number</label></td>
                               <td style="width: 280px;"><label for="cl_ab">Customer</label></td>
	                           <td style="width: 240px;"><label for="cl_debt">Current debt</label></td>
                           </tr>
                           <tr>
                               <td><input type="text" style="width: 200px; resize: vertical;float: left;" id="cl_ab_num" maxlength="10" onkeypress=\'return event.charCode >= 48 && event.charCode <= 57\' value="'.(($res['cl_ab_num']=='')?$phone_check[1]:$res[cl_ab_num]).'"> <button id="go" style="cursor: pointer;float: right;margin-right: 34px;border: 0;padding: 7px;background: green;">GO</button></td>
                               <td><input type="text" style="width: 240px; resize: vertical;" id="cl_ab" value="'.(($res['cl_ab']=='')?$phone_check[0]:$res[cl_ab]).'"></td>
                               <td><input type="text" style="width: 240px; resize: vertical;" id="cl_debt" value="'.$res['cl_debt'].'"></td>
                           </tr>
                           <tr>
                               <td style="width: 280px;"><label for="cl_phone">Phone</label></td>
	                           <td style="width: 240px;"><label for="branch_id">branch</label></td>
                               <td style="width: 240px;"><label for="cl_addres">Address</label></td>
                           </tr>
                           <tr>
                               <td><input type="text" style="width: 240px; resize: vertical;" id="cl_phone" value="'.(($res['cl_phone']=='')?$phone_check[2]:$res[cl_phone]).'"></td>
                               <td><select style="width: 245px;" id="branch_id">'.getbranch_id($res['service_center_id']).'</select></td>
                               <td><input type="text" style="width: 240px; resize: vertical;" id="cl_addres" value="'.$res['cl_addres'].'"></td>
                           </tr>
                        </table>
                        <br>
	                    <hr style="width: 75%;position: absolute;margin-left: -11px;margin-top: 6px;">
	                    <hr style="width: 75%;position: absolute;margin-left: -11px;border: 5px solid #fff;">
	                    <hr style="width: 75%;position: absolute;margin-left: -11px;margin-top: 17px;">
	                    <br>
                        <table class="margin_top_10">
            	           <tr>
            	               <td><label style="width: 280px;" for="incomming_cat_1">Call Category</label></td>
            	               <td><label style="width: 280px;" for="incomming_cat_1_1">Call Sub-Category 1</label></td>
	                           <td><label style="width: 280px;" for="incomming_cat_1_1_1">Call Sub-Category 2</label></td>
            	           </tr>
            	           <tr>
	                           <td><select id="incomming_cat_1" style="width: 245px;">'.get_cat_1($res['cat_1']).'</select></td>
            	               <td><select id="incomming_cat_1_1" style="width: 245px;">'.get_cat_1_1($res['cat_1'],$res['cat_1_1']).'</select></td>
            	               <td><select id="incomming_cat_1_1_1" style="width: 245px;">'.get_cat_1_1_1($res['cat_1_1'],$res['cat_1_1_1']).'</select></td>
                	       </tr>
            	        </table>
            	        <br>
	                    <hr style="width: 75%;position: absolute;margin-left: -11px;margin-top: 6px;">
	                    <hr style="width: 75%;position: absolute;margin-left: -11px;border: 5px solid #fff;">
	                    <hr style="width: 75%;position: absolute;margin-left: -11px;margin-top: 17px;">
	                    <br>
            	        <div id="check_history" style="padding-top: 15px;">
            	            <div style="float: left; width: 878px;">
        	                <span style="float: left;">
                            <label for="start_check" style="margin-left: 110px;">-From</label>
                            <input class="callapp_filter_body_span_input date_input" type="text" id="start_check" style="width: 100px;" value="'.date('Y-m-d', strtotime('-10 days')).'">
                            </span>
                            <span style="float: left;margin-left: 12px;">
                            <label for="end_check" style="margin-left: 110px;">-Up to</label>
                            <input class="callapp_filter_body_span_input date_input" type="text" id="end_check" style="width: 100px;" value="'.date('Y-m-d').'">
                            </span>
            	            <span style="float: left;margin-left: 12px;">
                            <label for="check_ab" style="margin-left: 195px;">Customers</label>
                            <input class="callapp_filter_body_span_input" type="text" id="check_ab" style="width: 185px;">
                            </span>
                            <span style="margin-left: 25px;float: left;">
                            <select id="task_status_ck" style="width: 240px;">'.getStatusTaskCK().'</select>
                            </span>
            	            </div>
            	            <table class="display" id="table_history" style="width: 100%;">
                                <thead>
                                    <tr id="datatable_header">
                                        <th>ID</th>
                                        <th style="width: 120px;">Date</th>
                                        <th style="width: 120px;">Number of customers</th>
                                        <th style="width: 25%;">Phone</th>
                                        <th style="width: 25%;">Comment</th>
                                        <th style="width: 25%;">Result</th>
                                        <th style="width: 25%;">Status</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr class="search_header">
                                        <th class="colum_hidden">
                                    	   <input type="text" name="search_id" value="Filter" class="search_init" />
                                        </th>             
                                        <th>
                                            <input type="text" name="search_category" value="Filter" class="search_init" />
                                        </th>            
                                        <th>
                                            <input type="text" name="search_category" value="Filter" class="search_init" />
                                        </th>
                                        <th>
                                            <input type="text" name="search_category" value="Filter" class="search_init" />
                                        </th>
                                        <th>
                                            <input type="text" name="search_phone" value="Filter" class="search_init" />
                                        </th>
                                        <th>
                                            <input type="text" name="search_category" value="Filter" class="search_init" />
                                        </th>
            	                        <th>
                                            <input type="text" name="search_category" value="Filter" class="search_init" />
                                        </th>          
                                    </tr>
                                </thead>
                            </table>
            	        </div>
	    
            </fieldset>
    	    
            <fieldset style="display:none;" id="task">
                <legend>Task Formation</legend>
	            <span class="hide_said_menu">x</span>
	            <table>
	               <tr>
                       <td style="width: 280px;"><label for="task_status_id">Status</label></td>
                       <td style="width: 280px;"><label for="task_send">Formation</label></td>
	               </tr>	              
	               <tr>
	                   <td><select id="task_status_id" style="width: 240px;" disabled>'.getStatusTask($res[task_status_id]).'</select></td>
	                   <td style="width: 280px;"><input '.$disa.' type="checkbox" id="task_send" value="0" '.(($res[task_date]=='')?'':'checked').'/></td>
	               </tr>
	               <tr>
	                   <td><label for="task_start_date">Formation Date</label></td>
	                   <td><label for="task_start_date">Period</label></td>
	                   <td><label></label></td>
	               </tr>	              
	               <tr>
	                   <td><input style="float: left;width: 235px;" id="task_create_date" type="text" value="'.(($res[task_date]=='')?date("Y-m-d h:i:s"):$res[task_date]).'"></label></td>
	                   <td><input style="float: left;width: 235px;" id="task_start_date" type="text" value="'.$res[task_start_date].'"><label for="task_start_date" style="float: left;margin-top: 7px;margin-left: 2px;">-For</label></td>
	                   <td><input style="float: left;width: 235px;" id="task_end_date" type="text" value="'.$res[task_end_date].'"><label for="task_end_date" style="float: left;margin-top: 7px;margin-left: 2px;">-Up to</label></td>
	               </tr>
	               <tr>
	                   <td><label for="task_description">Comment</label></td>
	               </tr>
	               <tr>
	                   <td colspan=3><textarea style="resize: vertical;width: 800px;" id="task_description">'.$res[task_description].'</textarea></td>
	               </tr>
	               <tr>
	                   <td><label for="task_note">Result</label></td>
	               </tr>
	               <tr>
	                   <td colspan=3><textarea style="resize: vertical;width: 800px;" id="task_note" disabled>'.$res[task_note].'</textarea></td>
	               </tr>
	            </table>
            </fieldset>
            
            <fieldset style="display:none;" id="sms">
                <legend>SMS</legend>
	            <span class="hide_said_menu">x</span>	 
	            <div class="margin_top_10">           
	            <div id="button_area">
                    <button id="add_sms">New SMS</button>
                </div>
                <table class="display" id="table_sms" >
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 100%;">Date</th>
                            <th style="width: 100%;">Addres</th>
                            <th style="width: 100%;">Text</th>
                            <th style="width: 100%;">Status</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                        	   <input type="text" name="search_id" value="Filter" class="search_init" />
                            </th>
                            <th>
                            	<input type="text" name="search_number" value="Filter" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="Filter" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="Filter" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="Filter" class="search_init" />
                            </th>
                        </tr>
                    </thead>
                </table>
	            </div>
            </fieldset>
            
            <fieldset style="display:none;" id="mail">
                <legend>E-mail</legend>
	            <span class="hide_said_menu">x</span>
	            <div class="margin_top_10">           
	            <div id="button_area">
                    <button id="add_mail">New E-mail</button>
                </div>
                <table class="display" id="table_mail" >
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 100%;">Date</th>
                            <th style="width: 100%;">Address</th>
                            <th style="width: 100%;">message</th>
                            <th style="width: 100%;">status</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                        	    <input type="text" name="search_id" value="Filter" class="search_init" />
                            </th>
                            <th>
                            	<input type="text" name="search_number" value="Filter" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="Filter" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="Filter" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="Filter" class="search_init" />
                            </th>
                        </tr>
                    </thead>
                </table>
	            </div>
            </fieldset>
            
            <fieldset style="display:none;" id="record">
                <legend>Records</legend>
	            <span class="hide_said_menu">x</span>
	                '.show_record($res).'
            </fieldset>
            
            <fieldset style="display:none;" id="file">
                <legend>File</legend>
	            <span class="hide_said_menu">x</span>
	                '.show_file($res).'
            </fieldset>
	                    
	        <fieldset style="display:none;" id="question">
                <legend>question</legend>
	            <span class="hide_said_menu">x</span>
	                    <div style="margin-top:30px;">
	                <table class="display" id="table_question">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 50%;">Question</th>
                            <th style="width: 50%;">Answer</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                        	    <input type="text" name="search_id" value="Filter" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="Filter" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="Filter" class="search_init" />
                            </th>
                        </tr>
                    </thead>
                </table>
	                    </div>
            </fieldset>
	        <fieldset style="display:none;" id="box">
                
            </fieldset>
                    <fieldset style="display:none;height: 600px;" id="scenar">
                        
	       </fieldset>
	    </div>
	</div><input type="hidden" value="'.$res[id].'" id="hidden_id">';

	return $data;
}

function GetSmsSendPage() {
    $data = '
        <div id="dialog-form">
            <fieldset style="width: 299px;">
					<legend>SMS</legend>
			    	<table class="dialog-form-table">
						<tr>
							<td><label for="d_number">Addess</label></td>
						</tr>
			    		<tr>
							<td>
								<span id="errmsg" style="color: red; display: none;">Only the number of</span>
								<input type="text" id="sms_phone" placeholder="9955XXXXXXXX" value="">
							</td>
							<td>
								<button id="copy_phone">Copy</button>
							</td>
							<td>
								<button id="sms_shablon">Template</button>
							</td>
						</tr>
						<tr>
							<td><label for="content">Text</label></td>
						</tr>
					
						<tr>
							
							<td colspan="6">	
								<textarea maxlength="150" style="width: 298px; resize: vertical;" id="sms_text" name="call_content" cols="300" rows="4"></textarea>
							</td>
						</tr>
						<tr>
							<td>
								<input style="width: 50px;" type="text" id="simbol_caunt" value="0/150">
							</td>
							<td>
								
							</td>
							
							<td>
								<button id="send_sms">Send</button>
							</td>
						</tr>	
					</table>
		        </fieldset>
        </div>';
    return $data;
}

function GetMailSendPage(){
   $data = '
            <div id="dialog-form">
        	    <fieldset style="height: auto;">
        	    	<table class="dialog-form-table">
        				
        				<tr>
        					<td style="width: 90px; "><label for="d_number">Addressee:</label></td>
        					<td>
        						<input type="text" style="width: 490px !important;"id="mail_address" value="" />
        					</td>
        				</tr>
        				<tr>
        					<td style="width: 90px;"><label for="d_number">CC:</label></td>
        					<td>
        						<input type="text" style="width: 490px !important;" id="mail_address1" value="" />
        					</td>
        				</tr>
        				<tr>
        					<td style="width: 90px;"><label for="d_number">Bcc:</label></td>
        					<td>
        						<input type="text" style="width: 490px !important;" id="mail_address2" value="" />
        					</td>
        				</tr>
        				<tr>
        					<td style="width: 90px;"><label for="d_number">title:</label></td>
        					<td>
        						<input type="text" style="width: 490px !important;" id="mail_text" value="" />
        					</td>
        				</tr>
        			</table>
        			<table class="dialog-form-table">
        				<tr>
        					<td>	
        						<textarea id="input" style="width:551px; height:200px"></textarea>
        					</td>
        			   </tr>
        			</table>
			    </fieldset>
		    </div>';
    return $data;
}

function show_record($res){
    $ph1 = "`source` LIKE '%test%'";
    if(strlen($res[phone]) > 4){
        $ph1 = "`source` LIKE '%$res[phone]%'";
    }
    
$record_incomming = mysql_query("SELECT  `datetime`,
                                             TIME_FORMAT(SEC_TO_TIME(duration),'%i:%s') AS `duration`,
                                             CONCAT(DATE_FORMAT(asterisk_incomming.call_datetime, '%Y/%m/%d/'),`file_name`) AS file_name
                                     FROM    `asterisk_incomming`
                                     WHERE   $ph1 AND disconnect_cause != 'ABANDON'");
    while ($record_res_incomming = mysql_fetch_assoc($record_incomming)) {
        $str_record_incomming .= '<tr>
                                    <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">'.$record_res_incomming[datetime].'</td>
                            	    <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">'.$record_res_incomming[duration].'</td>
                            	    <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;cursor: pointer;" onclick="listen(\''.$record_res_incomming[file_name].'\')"><span>Listen</span></td>
                        	      </tr>';
    }
    
    $ph1 = "`phone` LIKE '%test%'";
    $ph2 = "or `phone` LIKE '%test%'";
    if(strlen($res[phone1]) > 4){
        $ph1 = "`phone` LIKE '%$res[phone1]%'";
    }
    if(strlen($res[phone2]) > 4){
        $ph2 = " or `phone` LIKE '%$res[phone2]%'";
    }
    
    $record_outgoing = mysql_query("SELECT  `call_datetime`,
                                            TIME_FORMAT(SEC_TO_TIME(duration),'%i:%s') AS `duration`,
                                            CONCAT(DATE_FORMAT(asterisk_outgoing.call_datetime, '%Y/%m/%d/'),`file_name`) AS file_name
                                    FROM    `asterisk_outgoing`
                                    WHERE   $ph1 $ph2");
    while ($record_res_outgoing = mysql_fetch_assoc($record_outgoing)) {
        $str_record_outgoing .= '<tr>
                                    <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">'.$record_res_outgoing[call_datetime].'</td>
                            	    <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">'.$record_res_outgoing[duration].'</td>
                            	    <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;cursor: pointer;" onclick="listen(\''.$record_res_outgoing[file_name].'\')"><span>Listen</span></td>
                        	      </tr>';
    }
    
    if($str_record_outgoing == ''){
        $str_record_outgoing = '<tr>
                                    <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;" colspan=3>No records found</td>
                        	      </tr>';
    }
    
    if($str_record_incomming == ''){
        $str_record_incomming = '<tr>
                                    <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;" colspan=3>No records found</td>
                        	      </tr>';
    }
    
    $data = '  <div style="margin-top: 10px;">
                    <audio controls style="margin-left: 280px;">
                      <source src="" type="audio/wav">
                      Your browser does not support the audio element.
                    </audio>
               </div>
               <fieldset style="display:block !important; margin-top: 10px;">
                    <legend>Incoming calls</legend>
    	            <table style="margin: auto;">
    	               <tr>
    	                   <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">Date</td>
                    	   <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">Duration</td>
                    	   <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">Listen</td>
                	    </tr>
    	                '.$str_record_incomming.'
            	    </table>
	            </fieldset>
	            <fieldset style="display:block !important; margin-top: 10px;">
                    <legend>Outgoing call</legend>
    	            <table style="margin: auto;">
    	               <tr>
    	                   <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">Date</td>
                    	   <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">Duration</td>
                    	   <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">Listen</td>
                	    </tr>
    	                '.$str_record_outgoing.'
            	    </table>
	            </fieldset>';
    return $data;
}

function show_file($res){
    $file_incomming = mysql_query("  SELECT `name`,
                                            `rand_name`,
                                            `file_date`,
                                            `id`
                                     FROM   `file`
                                     WHERE  `incomming_call_id` = $res[id] AND `actived` = 1");
    while ($file_res_incomming = mysql_fetch_assoc($file_incomming)) {
        $str_file_incomming .= '<div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 180px;float:left;">'.$file_res_incomming[file_date].'</div>
                            	<div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 189px;float:left;">'.$file_res_incomming[name].'</div>
                            	<div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;cursor: pointer;width: 160px;float:left;" onclick="download_file(\''.$file_res_incomming[rand_name].'\')">Download</div>
                            	<div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;cursor: pointer;width: 20px;float:left;" onclick="delete_file(\''.$file_res_incomming[id].'\')">-</div>';
    }
    $data = '<div style="margin-top: 15px;">
                    <div style="width: 68%; margin-left: 130px; border:1px solid #CCC;float: left;">    	            
    	                   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 180px;float:left;">Date</div>
                    	   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 189px;float:left;">Name</div>
                    	   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 160px;float:left;">Download</div>
                           <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 20px;float:left;">-</div>
    	                   <div style="text-align: center;vertical-align: middle;float: left;width: 595px;"><button id="upload_file" style="cursor: pointer;background: none;border: none;width: 100%;height: 25px;padding: 0;margin: 0;">Choose</button><input style="display:none;" type="file" name="file_name" id="file_name"></div>
                           <div id="paste_files">
                           '.$str_file_incomming.'
                           </div>
            	    </div>
	            </div>';
    return $data;
}

function increment($table){

    $result   		= mysql_query("SHOW TABLE STATUS LIKE '$table'");
    $row   			= mysql_fetch_array($result);
    $increment   	= $row['Auto_increment'];
    $next_increment = $increment+1;
    mysql_query("ALTER TABLE '$table' AUTO_INCREMENT=$next_increment");

    return $increment;
}

function getShablon(){

    $req = mysql_query("SELECT 	sms.id,
								sms.`name`,
								sms.`message`
							    FROM 	sms
							    WHERE 	sms.actived=1 ");

    $data = '<table id="box-table-b1">
				<tr class="odd">
					<th style="width: 26px;">#</th>
					<th style="width: 160px;">Text</th>
					<th>Action</th>
				</tr> ';

    while( $res3 = mysql_fetch_assoc($req)){

        $data .= '<tr class="odd">
					<td>' . $res3[id] . '</td>
					<td style="width: 30px !important;">' . $res3['name'] . '</td>
					<td style="font-size: 10px !important;"><button style="width: 45px;" class="download_shablon" sms_id="' . $res3['id'] . '" number="' . $res3['message'] . '">Choose</button></td>
				 </tr>';

    }
    $data.='</table>';
    return $data;
}

?>