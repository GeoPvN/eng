<?php
// MySQL Connect Link
include('../../includes/classes/core.php');
 
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

$in_sorce_info_id = $_REQUEST['in_sorce_info_id'];
$in_service_center_id = $_REQUEST['in_service_center_id'];
$in_branch_id = $_REQUEST['in_branch_id'];
$in_district_id = $_REQUEST['in_district_id'];
$in_type_id = $_REQUEST['in_type_id'];
$cl_id = $_REQUEST['cl_id'];
$cl_name = $_REQUEST['cl_name'];
$cl_ab = $_REQUEST['cl_ab'];
$cl_ab_num = $_REQUEST['cl_ab_num'];
$cl_addres = $_REQUEST['cl_addres'];
$cl_phone = $_REQUEST['cl_phone'];

$task_type_id			= $_REQUEST['task_type_id'];
$task_start_date		= $_REQUEST['task_start_date'];
$task_end_date			= $_REQUEST['task_end_date'];
$task_departament_id	= $_REQUEST['task_departament_id'];
$task_recipient_id		= $_REQUEST['task_recipient_id'];
$task_priority_id		= $_REQUEST['task_priority_id'];
$task_controler_id		= $_REQUEST['task_controler_id'];
$task_status_id		    = $_REQUEST['task_status_id'];
$task_description		= $_REQUEST['task_description'];
$task_note			    = $_REQUEST['task_note'];

switch ($action) {
	case 'get_add_page':
		$page		= GetPage('',increment(incomming_call));
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$page		= GetPage(Getincomming($hidden_id,$open_number),'',$open_number,$queue);
		$data		= array('page'	=> $page);

		break;
    case 'next_quest':
        $page 		= next_quest($hidden_id, $_REQUEST[next_id]);
        $data		= array('ne_id'	=> $page);
    
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
		
		// OPERATOR CHECKER
		if($operator_id != 0){
		    $op_check = " AND user_id = '$operator_id'";
		}else{
		    $op_check = '';
		}
		
		// STATUS CHECKER
		if($tab_id != 0){
		    $tab_check = " AND inc_status_id = '$tab_id'";
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
		
		// OUT DONE
		if($filter_4 == 4){
		    $check_4 = 4;
		}else{
		    $check_4 = 0;
		}
		
		// OUT UNDONE
		if($filter_5 == 5){
		    $check_5 = 5;
		}else{
		    $check_5 = 0;
		}
		
		// OUT UNANSSWER
		if($filter_6 == 6){
		    $check_6 = 6;
		}else{
		    $check_6 = 0;
		}
		
		// INPUT DONE
		if($filter_7 == 7){
		    $check_7 = 7;
		}else{
		    $check_7 = 0;
		}
		if($filter_1 != 'undefined' || $filter_2 != 'undefined' || $filter_3 != 'undefined' || $filter_4 != 'undefined' || $filter_5 != 'undefined' || $filter_6 != 'undefined' || $filter_7 != 'undefined'){
		    $main_status = " AND main_status IN(0,$check_1,$check_2,$check_3,$check_4,$check_5,$check_6,$check_7)";
		}else{
		    $main_status = '';
		}
		
	  	$rResult = mysql_query("SELECT 	id,
                        				id,
                        				date,
                        				source,
                        				queue,
                        				pers_name,
                        				duration,
                        				inc_status,
                        				file
                                FROM 	all_call
	  	                        WHERE DATE(date) >= '$start_date' AND DATE(date) <= '$end_date' $op_check $tab_check $main_status
	  	                        ORDER BY date DESC");
	  
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
    case 'cat_2':
        $page		= get_cat_1_1($_REQUEST['cat_id'],'');
        $data		= array('page'	=> $page);
    
        break;
    case 'cat_3':
        $page		= get_cat_1_1_1($_REQUEST['cat_id'],'');
        $data		= array('page'	=> $page);
    
        break;
    case 'save_incomming':
        if($hidden_id == ''){
            incomming_insert($user_id,$incomming_id,$incomming_date,$incomming_phone,$incomming_cat_1,$incomming_cat_1_1,$incomming_cat_1_1_1,$incomming_comment,$in_sorce_info_id, $in_service_center_id, $in_branch_id, $in_district_id, $in_type_id, $cl_id, $cl_name, $cl_ab, $cl_ab_num, $cl_addres, $cl_phone,$scenario_id,$inc_status_id);
        }else{
            incomming_update($user_id,$hidden_id,$incomming_phone,$incomming_cat_1,$incomming_cat_1_1,$incomming_cat_1_1_1,$incomming_comment,$in_sorce_info_id, $in_service_center_id, $in_branch_id, $in_district_id, $in_type_id, $cl_id, $cl_name, $cl_ab, $cl_ab_num, $cl_addres, $cl_phone,$inc_status_id);
        }
        
        if($hidden_id == ''){
            $inc_id = $incomming_id;
        }else{
            $inc_id = $hidden_id;
        }

		if($task_recipient_id > 0){
		    $task_ck = mysql_fetch_array(mysql_query("SELECT id FROM task WHERE incomming_call_id = $inc_id"));
		    if($task_ck[0] == ''){
		    mysql_query(" INSERT INTO `task`
		                  (`user_id`, `incomming_call_id`, `task_recipient_id`, `task_controler_id`, `task_date`, `task_start_date`, `task_end_date`, `task_departament_id`, `task_type_id`, `task_priority_id`, `task_description`, `task_note`, `task_status_id`)
		                  VALUES
		                  ('$user_id', '$inc_id', '$task_recipient_id', '$task_controler_id', NOW(), '$task_start_date', '$task_end_date', '$task_departament_id', '$task_type_id', '$task_priority_id', '$task_description', '$task_note', '$task_status_id');");
		    }else{
		        mysql_query("UPDATE `task` SET
                                    `task_recipient_id`='$task_recipient_id',
                                    `task_controler_id`='$task_controler_id',
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

function incomming_insert($user_id,$incomming_id,$incomming_date,$incomming_phone,$incomming_cat_1,$incomming_cat_1_1,$incomming_cat_1_1_1,$incomming_comment,$in_sorce_info_id, $in_service_center_id, $in_branch_id, $in_district_id, $in_type_id, $cl_id, $cl_name, $cl_ab, $cl_ab_num, $cl_addres, $cl_phone,$scenario_id,$inc_status_id){
    mysql_query("INSERT INTO    `incomming_call` 
                 (`id`,`user_id`,`date`,`phone`,`cat_1`,`cat_1_1`,`cat_1_1_1`,`comment`,`scenario_id`,`inc_status_id`)
                 VALUES
                 ('$incomming_id','$user_id','$incomming_date','$incomming_phone','$incomming_cat_1','$incomming_cat_1_1','$incomming_cat_1_1_1','$incomming_comment','$scenario_id','$inc_status_id')");
    
    mysql_query("INSERT INTO `personal_info` 
                 (`user_id`, `incomming_call_id`, `in_sorce_info_id`, `in_service_center_id`, `in_branch_id`, `in_district_id`, `in_type_id`, `cl_id`, `cl_name`, `cl_ab`, `cl_ab_num`, `cl_addres`, `cl_phone`)
                 VALUES
                 ('$user_id', '$incomming_id', '$in_sorce_info_id', '$in_service_center_id', '$in_branch_id', '$in_district_id', '$in_type_id', '$cl_id', '$cl_name', '$cl_ab', '$cl_ab_num', '$cl_addres', '$cl_phone');");
}

function incomming_update($user_id,$hidden_id,$incomming_phone,$incomming_cat_1,$incomming_cat_1_1,$incomming_cat_1_1_1,$incomming_comment,$in_sorce_info_id, $in_service_center_id, $in_branch_id, $in_district_id, $in_type_id, $cl_id, $cl_name, $cl_ab, $cl_ab_num, $cl_addres, $cl_phone,$inc_status_id){
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
            (`user_id`, `incomming_call_id`, `in_sorce_info_id`, `in_service_center_id`, `in_branch_id`, `in_district_id`, `in_type_id`, `cl_id`, `cl_name`, `cl_ab`, `cl_ab_num`, `cl_addres`, `cl_phone`)
            VALUES
            ('$user_id', '$hidden_id', '$in_sorce_info_id', '$in_service_center_id', '$in_branch_id', '$in_district_id', '$in_type_id', '$cl_id', '$cl_name', '$cl_ab', '$cl_ab_num', '$cl_addres', '$cl_phone');");
    }else{
    mysql_query("UPDATE `personal_info` SET
                        `user_id`='$user_id',
                        `in_sorce_info_id`='$in_sorce_info_id',
                        `in_service_center_id`='$in_service_center_id',
                        `in_branch_id`='$in_branch_id',
                        `in_district_id`='$in_district_id',
                        `in_type_id`='$in_type_id',
                        `cl_id`='$cl_id',
                        `cl_name`='$cl_name',
                        `cl_ab`='$cl_ab',
                        `cl_ab_num`='$cl_ab_num',
                        `cl_addres`='$cl_addres',
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
    $req = mysql_query("  SELECT  `id`,
                                  `name`
                          FROM `info_category`
                          WHERE actived = 1 AND `parent_id` = $id");
    
    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $child_id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }
    
    return $data;
}
function get_cat_1_1_1($id,$child_id){
    $req = mysql_query("  SELECT  `id`,
                                  `name`
                          FROM `info_category`
                          WHERE actived = 1 AND `parent_id` = $id");
    
    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $child_id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
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
                                    				personal_info.`in_sorce_info_id`,
                                    				personal_info.`in_service_center_id`,
                                    				personal_info.`in_branch_id`,
                                    				personal_info.`in_district_id`,
                                    				personal_info.`in_type_id`,
                                    				personal_info.`cl_id`,
                                    				personal_info.`cl_name`,
                                    				personal_info.`cl_ab`,
	                                                personal_info.`cl_ab_num`,
	                                                personal_info.`cl_addres`,
                                    				personal_info.`cl_phone`,
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
                                	    ORDER BY incomming_call.id DESC
                                        LIMIT 1"));
	return $res;
}

function getStatusTask($id){

    $req = mysql_query("    SELECT 	`id`,
                                    `name`
                            FROM    `task_status`
                            WHERE   `actived` = 1 AND `type` = 2");

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

function getin_sorce_info_id($id){
    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `in_sorce_info`
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
function getin_service_center_id($id){
    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `in_service_center`
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
function getin_branch_id($id){
    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `in_branch`
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
function getin_district_id($id){
    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `in_district`
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
    echo $increment;
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
    $rr = mysql_fetch_array(mysql_query("SELECT scenario_id FROM queue WHERE number = '$ch_queue'"));
	$data  .= '
	<div id="dialog-form">
	    <fieldset style="width: 150px;  float: left;">
	       <input id="scenario_id" type="hidden" value="'.$rr[0].'" />
	       <table class="dialog-form-table">
	           
    	       <tr>
	               <td style="width: 125px;"><label for="incomming_id">მომართვის №</label></td>
	           </tr>
	           <tr>
	               <td><input disabled style="width: 125px;" id="incomming_id" type="text" value="'.(($res['id']=='')?$increment:$res['id']).'"></td>
               </tr>
	           <tr>
	               <td style="width: 125px;"><label for="incomming_date">თარიღი</label></td>
	           </tr>
	           <tr>
	               <td><input disabled style="width: 125px;" id="incomming_date" type="text" value="'.(($res['call_date']=='')?date("Y-m-d H:i:s"):$res['call_date']).'"></td>
               </tr>
	           <tr>
	               <td><label for="incomming_phone">ტელეფონი</td>
    	       </tr>
               <tr>
	               <td><input disabled style="width: 125px;" id="incomming_phone" type="text" value="'.$res['phone'].'"></td>
    	       </tr>
	       </table>
	       <table class="dialog-form-table">
	           
	           <tr>
	               <td><label for="incomming_cat_1">ზარის კატეგორია</label></td>
	           </tr>
	           <tr>
	               <td><select id="incomming_cat_1" style="width: 130px;">'.get_cat_1($res['cat_1']).'</select></td>
	           </tr>
	           <tr>
	               <td><label for="incomming_cat_1_1">ზარის ქვე-კატეგორია 1</label></td>
	           </tr>
	           <tr>
	               <td><select id="incomming_cat_1_1" style="width: 130px;">'.get_cat_1_1($res['cat_1'],$res['cat_1_1']).'</select></td>
	           </tr>
	           <tr>
	               <td><label for="incomming_cat_1_1_1">ზარის ქვე-კატეგორია 2</label></td>
    	       </tr>
	           <tr>
	               <td><select id="incomming_cat_1_1_1" style="width: 130px;">'.get_cat_1_1_1($res['cat_1_1'],$res['cat_1_1_1']).'</select></td>
    	       </tr>
	       </table>
	       <table class="dialog-form-table">
	           <tr>
	               <td><label for="inc_status_id">რეაგირება</label></td>
	           </tr>
	           <tr>
	               <td><select id="inc_status_id" style="width: 130px;">'.get_IncStatus($res['inc_status_id']).'</select></td>
	           </tr>
	           <tr>
	               <td><label for="incomming_comment">დამატებითი ინფორმაცია</label></td>
	           </tr>
	           <tr>
	               <td><textarea id="incomming_comment" style="resize: vertical;width: 125px;height: 50px;">'.$res['comment'].'</textarea></td>
	           </tr>
	       </table>
	       
	    </fieldset>
	    
	    
        <div id="side_menu" style="float: left;height: 520px;width: 80px;margin-left: 10px; background: #272727; color: #FFF;">
	       <spam class="info" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'info\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/info.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">ინფო</div></spam>
	       <spam class="scenar" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'scenar\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/scenar.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">ბილინგი</div></spam>
	       <spam class="task" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'task\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/task.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">დავალება</div></spam>
	       <spam class="sms" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'sms\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/sms.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">SMS</div></spam>
	       <spam class="mail" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'mail\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/mail.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">E-mail</div></spam>
	       <spam class="record" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'record\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/record.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">ჩანაწერი</div></spam>
	       <spam class="file" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'file\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/file.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">ფაილი</div></spam>
	       <spam class="question" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'question\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/question.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">შეკითხვა</div></spam> 
       </div>
	    
	    <div style="width: 900px;float: left;margin-left: 10px;" id="right_side">
            <fieldset style="display:none;" id="info">
	            <span class="hide_said_menu">x</span>
	                   
                        <table class="margin_top_10">
    	                   <tr>
        	                   <td><label style="width: 280px;" for="in_sorce_info_id">ინფორმაციის წყარო</label></td>
        	                   <td><label style="width: 280px;" for="in_service_center_id">მომსახურების ცენტრი</label></td>
        	                   <td><label style="width: 280px;" for="in_district_id">უბანი</label></td>
            	           </tr>
            	           <tr>
            	               <td><select id="in_sorce_info_id" style="width: 245px;">'.getin_sorce_info_id($res['in_sorce_info_id']).'</select></td>
            	               <td><select id="in_service_center_id" style="width: 245px;">'.getin_service_center_id($res['in_service_center_id']).'</select></td>
            	               <td><select id="in_district_id" style="width: 245px;">'.getin_district_id($res['in_district_id']).'</select></td>
            	           </tr>
	                   </table>
	                   <br>
	                   <hr style="width: 75%;position: absolute;margin-left: -11px;margin-top: 6px;">
	                   <hr style="width: 75%;position: absolute;margin-left: -11px;border: 5px solid #fff;">
	                   <hr style="width: 75%;position: absolute;margin-left: -11px;margin-top: 17px;">
	                   <br>
	                   
	                   <table class="margin_top_10">
                           <tr>
                               <td style="width: 280px;"><label for="cl_id">კანცელარიის ნომერი</label></td>
                               <td style="width: 280px;"><label for="cl_name">განმცხადებელი</label></td>
	                           <td style="width: 240px;"><label for="in_type_id">ტიპი</label></td>
                           </tr>
                           <tr>
                               <td><textarea style="width: 240px; resize: vertical;" id="cl_id" >'.$res['cl_id'].'</textarea></td>
                               <td><textarea style="width: 240px; resize: vertical;" id="cl_name" >'.$res['cl_name'].'</textarea></td>
                               <td><select style="width: 245px;" id="in_type_id">'.getin_type_id($res['in_type_id']).'</select></td>
                           </tr>
                           <tr>
                               <td style="width: 280px;"><label for="cl_ab">აბონენტი</label></td>
                               <td style="width: 280px;"><label for="cl_ab_num">აბონენტის ნომერი</label></td>
	                           <td style="width: 240px;"><label for="cl_addres">მისამართი</label></td>
                           </tr>
                           <tr>
                               <td><textarea style="width: 240px; resize: vertical;" id="cl_ab" >'.$res['cl_ab'].'</textarea></td>
                               <td><textarea style="width: 240px; resize: vertical;" id="cl_ab_num" maxlength="10" onkeypress=\'return event.charCode >= 48 && event.charCode <= 57\'>'.$res['cl_ab_num'].'</textarea></td>
                               <td><textarea style="width: 240px; resize: vertical;" id="cl_addres" >'.$res['cl_addres'].'</textarea></td>
                           </tr>
                           <tr>
                               <td style="width: 280px;"><label for="cl_phone">ტელეფონის ნომერი</label></td>
	                           <td style="width: 240px;"><label for="in_branch_id">ფილიალი</label></td>
                           </tr>
                           <tr>
                               <td><textarea style="width: 240px; resize: vertical;" id="cl_phone" >'.$res['cl_phone'].'</textarea></td>
                               <td><select style="width: 245px;" id="in_branch_id">'.getin_branch_id($res['in_branch_id']).'</select></td>
                           </tr>
                        </table>
	    
            </fieldset>
    	    
            <fieldset style="display:none;" id="task">
                <legend>დავალების ფორმირება</legend>
	            <span class="hide_said_menu">x</span>
	            <table>
	               <tr>
                       <td style="width: 280px;"><label for="task_controler_id">პასუხისმგებელი სამსახური</label></td>
	                   <td style="width: 280px;"><label for="task_recipient_id">პასუხისმგებელი პირი</label></td>
                       <td style="width: 280px;"><label for="task_status_id">სტატუსი</label></td>
	               </tr>	              
	               <tr>
                       <td><select style="width: 240px;" id="task_departament_id">'.GetDepartament($res[task_departament_id]).'</select></td>
	                   <td><select style="width: 240px;" id="task_recipient_id">'.getUsers($res[task_recipient_id]).'</select></td>
	                   <td><select style="width: 240px;" id="task_status_id">'.getStatusTask($res[task_status_id]).'</select></td>
	               </tr>
	               <tr>
	                   <td><label for="task_start_date">ფორმირების თარიღი</label></td>
	                   <td><label for="task_start_date">პერიოდი</label></td>
	                   <td><label></label></td>
	               </tr>	              
	               <tr>
	                   <td><input style="float: left;width: 235px;" id="task_create_date" type="text" value="'.(($res[task_date]=='')?date("Y-m-d h:i:s"):$res[task_date]).'"></label></td>
	                   <td><input style="float: left;width: 235px;" id="task_start_date" type="text" value="'.$res[task_start_date].'"><label for="task_start_date" style="float: left;margin-top: 7px;margin-left: 2px;">-დან</label></td>
	                   <td><input style="float: left;width: 235px;" id="task_end_date" type="text" value="'.$res[task_end_date].'"><label for="task_end_date" style="float: left;margin-top: 7px;margin-left: 2px;">-მდე</label></td>
	               </tr>
	               <tr>
	                   <td><label for="task_description">კომენტერი</label></td>
	               </tr>
	               <tr>
	                   <td colspan=3><textarea style="resize: vertical;width: 800px;" id="task_description">'.$res[task_description].'</textarea></td>
	               </tr>
	               <tr>
	                   <td><label for="task_note">შედეგი</label></td>
	               </tr>
	               <tr>
	                   <td colspan=3><textarea style="resize: vertical;width: 800px;" id="task_note">'.$res[task_note].'</textarea></td>
	               </tr>
	            </table>
            </fieldset>
            
            <fieldset style="display:none;" id="sms">
                <legend>SMS</legend>
	            <span class="hide_said_menu">x</span>	 
	            <div class="margin_top_10">           
	            <div id="button_area">
                    <button id="add_sms">ახალი SMS</button>
                </div>
                <table class="display" id="table_sms" >
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 100%;">თარიღი</th>
                            <th style="width: 100%;">ადრესატი</th>
                            <th style="width: 100%;">ტექსტი</th>
                            <th style="width: 100%;">სტატუსი</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                        	   <input type="text" name="search_id" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                            	<input type="text" name="search_number" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
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
                    <button id="add_mail">ახალი E-mail</button>
                </div>
                <table class="display" id="table_mail" >
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 100%;">თარიღი</th>
                            <th style="width: 100%;">ადრესატი</th>
                            <th style="width: 100%;">გზავნილი</th>
                            <th style="width: 100%;">სტატუსი</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                        	    <input type="text" name="search_id" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                            	<input type="text" name="search_number" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
                        </tr>
                    </thead>
                </table>
	            </div>
            </fieldset>
            
            <fieldset style="display:none;" id="record">
                <legend>ჩანაწერები</legend>
	            <span class="hide_said_menu">x</span>
	                '.show_record($res).'
            </fieldset>
            
            <fieldset style="display:none;" id="file">
                <legend>ფაილი</legend>
	            <span class="hide_said_menu">x</span>
	                '.show_file($res).'
            </fieldset>
	                    
	        <fieldset style="display:none;" id="question">
                <legend>ფაილი</legend>
	            <span class="hide_said_menu">x</span>
	                
            </fieldset>';
	                    if($rr[0]==''){
	                        $my_scenario = $res['inc_scenario_id'];
	                    }else {
	                        $my_scenario = $rr[0];
	                    }
	                        
	                    $query = mysql_query("SELECT 	`question`.id,
                    				                    `question`.`name`,
                    				                    `question`.note,
                                                        `scenario`.`name`,
                		                                `scenario_detail`.id AS sc_det_id,
                		                                `scenario_detail`.`sort`
                                            FROM        `scenario`
                                            JOIN        scenario_detail ON scenario.id = scenario_detail.scenario_id
                                            JOIN        question ON scenario_detail.quest_id = question.id
                                            WHERE       scenario.id = $my_scenario AND scenario_detail.actived = 1
                                            ORDER BY    scenario_detail.sort ASC");
		
		$data .= '
                    <fieldset style="display:none;height: 600px;" id="scenar">
                        <legend>ბილინგი</legend>
		              <iframe src="http://localhost:8080/epro/client-side/call/biling.php" style="width: 875px; height:600px;"></iframe>
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
							<td><label for="d_number">ადრესატი</label></td>
						</tr>
			    		<tr>
							<td>
								<span id="errmsg" style="color: red; display: none;">მხოლოდ რიცხვი</span>
								<input type="text" id="sms_phone"  value="">
							</td>
							<td>
								<button id="copy_phone">Copy</button>
							</td>
							<td>
								<button id="sms_shablon">შაბლონი</button>
							</td>
						</tr>
						<tr>
							<td><label for="content">ტექსტი</label></td>
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
								<button id="send_sms">გაგზავნა</button>
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
        					<td style="width: 90px; "><label for="d_number">ადრესატი:</label></td>
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
        					<td style="width: 90px;"><label for="d_number">სათაური:</label></td>
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
                    <legend>შემომავალი ზარი</legend>
    	            <table style="margin: auto;">
    	               <tr>
    	                   <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">თარიღი</td>
                    	   <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">ხანგძლივობა</td>
                    	   <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">Listen</td>
                	    </tr>
    	                '.$str_record_incomming.'
            	    </table>
	            </fieldset>
	            <fieldset style="display:block !important; margin-top: 10px;">
                    <legend>გამავალი ზარი</legend>
    	            <table style="margin: auto;">
    	               <tr>
    	                   <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">თარიღი</td>
                    	   <td style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;">ხანგძლივობა</td>
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
                            	<div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;cursor: pointer;width: 160px;float:left;" onclick="download_file(\''.$file_res_incomming[rand_name].'\')">ჩამოტვირთვა</div>
                            	<div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;cursor: pointer;width: 20px;float:left;" onclick="delete_file(\''.$file_res_incomming[id].'\')">-</div>';
    }
    $data = '<div style="margin-top: 15px;">
                    <div style="width: 68%; margin-left: 130px; border:1px solid #CCC;float: left;">    	            
    	                   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 180px;float:left;">თარიღი</div>
                    	   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 189px;float:left;">დასახელება</div>
                    	   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 160px;float:left;">ჩამოტვირთვა</div>
                           <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 20px;float:left;">-</div>
    	                   <div style="text-align: center;vertical-align: middle;float: left;width: 595px;"><button id="upload_file" style="cursor: pointer;background: none;border: none;width: 100%;height: 25px;padding: 0;margin: 0;">აირჩიეთ ფაილი</button><input style="display:none;" type="file" name="file_name" id="file_name"></div>
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
    mysql_query("ALTER TABLE $table AUTO_INCREMENT=$next_increment");

    return $increment;
}

?>