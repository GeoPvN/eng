<?php
/* ******************************
 *	Request aJax actions
 * ******************************
*/

require_once ('../../../includes/classes/core.php');
$action = $_REQUEST['act'];
$error	= '';
$data	= '';

$user				= $_SESSION['USERID'];
$task_id 			= $_REQUEST['id'];

$cur_date			= $_REQUEST['cur_date'];
$done_start_time	= $_REQUEST['done_start_time'];
$done_end_time		= $_REQUEST['done_end_time'];
$task_type_id		= $_REQUEST['task_type_id'];
$template_id		= $_REQUEST['template_id'];
$task_department_id	= $_REQUEST['task_department_id'];
$persons_id			= $_REQUEST['persons_id'];
$task_comment		= mysql_real_escape_string($_REQUEST['task_comment']);
$priority_id		= $_REQUEST['priority_id'];

switch ($action) {
	case 'get_add_page':
		$page		= GetPage($res='');
		$data		= array('page'	=> $page);
		
        break;
    case 'dialog_formireba':
        $page		= GetDialogForm();
        $data		= array('page'	=> $page);
        
        break;
    case 'dialog_formireba_done':
        $page		= GetDialogFormDone();
        $data		= array('page'	=> $page);
        
        break;
    case 'disable':
        mysql_query("DELETE FROM task
                     WHERE id = $task_id");
        
        break;
    case 'save_formireba':
    	$f_number		= $_REQUEST['f_number'];
    	$f_note			= $_REQUEST['f_note'];
    	$f_sorce		= $_REQUEST['f_sorce'];
    	$f_name		    = $_REQUEST['f_name'];
    	
    	Addformireba($f_number, $f_note, $f_sorce, $f_name, $task_id);
        break;
    case 'save_formireba_done':
        $f_number		= $_REQUEST['f_number'];
        $f_note			= $_REQUEST['f_note'];
        $f_sorce		= $_REQUEST['f_sorce'];
        $f_name		    = $_REQUEST['f_name'];
         
        AddformirebaDone($f_number, $f_note, $f_sorce, $f_name, $task_id);
        break;
    case 'get_task':
        $page		= Gettask();
        $data		= array('page'	=> $page);
        
        break;
    case 'save_phone_base':
    	
    	$phone_base_id		= $_REQUEST['phone_base_id'];    	
    	$hidden_base		= $_REQUEST['hidden_base']; 
    	
    	if($hidden_base == 2){
    		mysql_query("INSERT INTO `task_detail`
				    	( `user_id`, `task_id`, `phone_base_id`, `status`, `actived`)
				    	VALUES
				    	( '$user', '$task_id', '$phone_base_id', '0', '1')");
    	}else{
    		mysql_query("INSERT INTO `task_detail`
			    		( `user_id`, `task_id`, `phone_base_inc_id`, `status`, `actived`)
			    		VALUES
			    		( '$user', '$task_id', '$phone_base_id', '0', '1')");
    	}
        
        break;
    case 'phone_base_dialog':
        $page		= Getphonebase();
        $data		= array('page'	=> $page);
        
        break;
    case 'get_edit_page':
	   
		$page		= GetPage(Getincomming($task_id));
        
        $data		= array('page'	=> $page);
        
        break;
    case 'get_list_base':
    	$count	= $_REQUEST['count'];
    	$hidden	= $_REQUEST['hidden'];
    		
    	$rResult = mysql_query("	SELECT 	incomming_call.id,
											incomming_call.phone,
											'',
											incomming_call.first_name,
											'',
											'',
											'',
											incomming_call.date,
											IF(incomming_call.type_id=1, 'ფიზიკური','იურიდიული') AS `type`,
    										''
									FROM 	incomming_call
									
    								WHERE incomming_call.phone != ''");
    	
    	$data = array(
    			"aaData"	=> array()
    	);
    	
    	while ( $aRow = mysql_fetch_array( $rResult ) )
    	{
    		$row = array();
    		for ( $i = 0 ; $i < $count ; $i++ )
    		{
    			/* General output */
    			$row[] = addslashes($aRow[$i]);
    			if($i == ($count - 1)){
    				$row[] = '<input type="checkbox" name="check_' . $aRow[$hidden] . '" class="check" value="' . $aRow[$hidden] . '" />';
    			}
    		}
    		$data['aaData'][] = $row;
    	}
        
        break;
    case 'get_list_base_phone':
    	$count	= $_REQUEST['count'];
    	$hidden	= $_REQUEST['hidden'];
    	$pager 	= $_REQUEST['pager'];
    	$pager_ch = 0;
    	if($pager == 0){
    		$pager_ch = 0;
    	}else{
    		$pager_ch = $pager.'000';
    	}
    	
    	$rResult = mysql_query("	SELECT 	id,
    										id,
											phone1,
											phone2,
											first_last_name,
											addres,
											city,
											sorce,
											create_date,
											person_status,
    										note
									FROM 	`phone`
									WHERE	actived = 1 
    								LIMIT 	1000 OFFSET $pager_ch");
    	 
    	$data = array(
    			"aaData"	=> array()
    	);
    	 
    	while ( $aRow = mysql_fetch_array( $rResult ) )
    	{
    		$row = array();
    		for ( $i = 0 ; $i < $count ; $i++ )
    		{
    			/* General output */
    			$row[] = addslashes($aRow[$i]);
    			if($i == ($count - 1)){
    				$row[] = '<input type="checkbox" name="check_' . $aRow[$hidden] . '" class="check" value="' . $aRow[$hidden] . '" />';
    			}
    		}
    		$data['aaData'][] = $row;
    	}
    	break;
 	case 'get_list' :
		$count		= $_REQUEST['count'];
	   	$hidden		= $_REQUEST['hidden'];
	    $user_id	= $_REQUEST['user_id'];
	    $user		= $_SESSION['USERID'];
	    $group		= checkgroup($user);
	    
	    $filter = '';
	    if ($group != 2) {
	    	$filter = 'AND outgoing_call.responsible_user_id ='. $user;
	    }
		    	
    	$rResult = mysql_query("SELECT 	task.id,
										task.id,
										task.date,
										task.start_date,
										task.end_date,
										task_type.`name`,
										department.`name`,
										persons.`name`,
										priority.`name`
								FROM task
								JOIN task_type ON task.task_type_id = task_type.id
								JOIN department ON task.department_id = department.id
								LEFT JOIN users ON task.responsible_user_id = users.id
								LEFT JOIN persons ON users.person_id = persons.id
    						    LEFT JOIN `status` ON task.`status` = `status`.id
								JOIN priority ON task.priority_id = priority.id
								WHERE task.template_id = 0 AND task.`status` = 0 AND task.responsible_user_id = 0
    							");
		    
		$data = array(
			"aaData"	=> array()
		);
		
		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			
			$row = array();
			for ( $i = 0 ; $i < $count; $i++ )
			{
				/* General output */
				$row[] = addslashes($aRow[$i]);
				if($i == ($count - 1)){
					$row[] ='<input type="checkbox" id="' . $aRow[$hidden] . '" name="check_' . $aRow[$hidden] . '" class="check" value="' . $aRow[$hidden] . '" />';
				}
			}
			$data['aaData'][] = $row;
		}
		
        break;
    case 'save_outgoing':
	
		$user_id		= $_SESSION['USERID'];
		
		if(empty($task_id)){
			$task_id = mysql_insert_id();
			Addtask($cur_date, $done_start_time, $done_end_time, $task_type_id, $template_id, $task_department_id, $persons_id, $task_comment, $priority_id);
			//Addsite_user($incomming_call_id, $personal_pin, $friend_pin, $personal_id);
		}else{
			
			Savetask($task_id, $cur_date, $done_start_time, $done_end_time, $task_type_id, $template_id, $task_department_id, $persons_id, $priority_id);
			//Savesite_user($incom_id, $personal_pin, $name, $personal_phone, $mail,  $personal_id);
			
		}
		
        break;
        
    case 'get_responsible_person_add_page':
        $page 		= GetResoniblePersonPage();
        $data		= array('page'	=> $page);
        
        break;
    case 'save_task':
    	// task_detail------------------------
    	$phone				= mysql_real_escape_string($_REQUEST['p_phone']);
    	$person_n			= mysql_real_escape_string($_REQUEST['p_person_n']);
    	$first_name			= mysql_real_escape_string($_REQUEST['p_first_name']);
    	$mail				= $_REQUEST['p_mail'];
    	$last_name 			= $_REQUEST['p_last_name'];
    	$person_status 		= $_REQUEST['p_person_status'];
    	$addres 			= $_REQUEST['p_addres'];
    	$b_day				= $_REQUEST['p_b_day'];
    	$city_id 			= $_REQUEST['p_city_id'];
    	$family_id 			= $_REQUEST['p_family_id'];
    	$profesion 			= $_REQUEST['p_profesion'];
    	$user				= $_SESSION['USERID'];
    	//------------------------------------
    	
    	mysql_query("INSERT INTO `task_detail` 
    			( `user_id`, `task_id`, `person_n`, `first_name`, `last_name`, `person_status`, `phone`, `mail`, `addres`, `b_day`, `city_id`, `family_id`, `profesion`, `status`, `actived`) 
    			VALUES 
    			( '$user', '$task_id', '$person_n', '$first_name', '$last_name', '$person_status', '$phone', '$mail', '$addres', '$b_day', '$city_id', '$family_id', '$profesion', '1', '1')");
        
        break;
        
    case 'change_responsible_person':
        $letters 			= json_decode( '['.$_REQUEST['lt'].']' );
        $responsible_person = $_REQUEST['rp'];
        
        ChangeResponsiblePerson($letters, $responsible_person);
        
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

function Addformireba($f_number, $f_note, $f_sorce, $f_name, $task_id)
{
	$user		= $_SESSION['USERID'];
    if($f_note != ''){
        $check_note = "AND `note`='$f_note'";    
    }else{
        $check_note = '';
    }
    
    if($f_sorce != ''){
        $check_sorce = "AND `sorce`='$f_sorce'";
    }else{
        $check_sorce = '';
    }
    
    if($f_name){
        $check_name = "AND `first_last_name`='$f_sorce'";
    }else{
        $check_name = "";
    }
    
	$r = mysql_query("	SELECT 	`id`
						FROM 	`phone`
						WHERE	`import` = 1 $check_note $check_sorce $check_name
						LIMIT 	$f_number");
	
	while($m = mysql_fetch_row($r)){
		$g .= "($m[0],0,$user,$task_id),";
	}
	
	$data = substr($g, 0, -1);
	
	mysql_query("INSERT INTO `task_detail` (`phone_base_id`,`status`,`user_id`,`task_id`) VALUES $data");
	
	// update query
	$r = mysql_query("select phone_base_id FROM task_detail");
	while($m = mysql_fetch_row($r)){
		$a .= "$m[0],";
	}
	$data = substr($a, 0, -1);
	mysql_query("UPDATE `phone` SET
						`import`='2'
				WHERE 	`id`in($data)");
}

function AddformirebaDone($f_number, $f_note, $f_sorce, $f_name, $task_id)
{
    $user		= $_SESSION['USERID'];
    if($f_note != ''){
        $check_note = "AND `note`='$f_note'";    
    }else{
        $check_note = '';
    }
    
    if($f_sorce != ''){
        $check_sorce = "AND `sorce`='$f_sorce'";
    }else{
        $check_sorce = '';
    }
    
    if($f_name){
        $check_name = "AND `first_last_name`='$f_sorce'";
    }else{
        $check_name = "";
    }
    
    $r = mysql_query("	SELECT 	`id`
                        FROM 	`phone`
                        WHERE	`import` = 2 $check_note $check_sorce $check_name
                        LIMIT 	$f_number");

    while($m = mysql_fetch_row($r)){
        $g .= "($m[0],0,$user,$task_id),";
    }

    $data = substr($g, 0, -1);

    mysql_query("INSERT INTO `task_detail` (`phone_base_id`,`status`,`user_id`,`task_id`) VALUES $data");

//     // update query
//     $r = mysql_query("select phone_base_id FROM task_detail");
//     while($m = mysql_fetch_row($r)){
//         $a .= "$m[0],";
//     }
//     $data = substr($a, 0, -1);
//     mysql_query("UPDATE `phone` SET
//     `import`='2'
//     WHERE 	`id`in($data)");
}

function checkgroup($user){
	$res = mysql_fetch_assoc(mysql_query("
											SELECT users.group_id
											FROM    users
											WHERE  users.id = $user
										"));
	return $res['group_id'];
	
}


function Addtask($cur_date, $done_start_time, $done_end_time, $task_type_id, $template_id, $task_department_id, $persons_id, $task_comment, $priority_id)
{  
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `task` 
				( `user_id`, `responsible_user_id`, `date`, `start_date`, `end_date`, `department_id`, `template_id`, `task_type_id`, `status`, `actived`, `comment`, `priority_id`)
				VALUES
				( '$user', '$persons_id', '$cur_date', '$done_start_time', '$done_end_time', '$task_department_id', '$template_id', '$task_type_id', '0', '1', '$task_comment', '$priority_id')
				");
	
// 	include '../../../mailer/class.phpmailer.php';
	
// 	$mail = new PHPMailer;
	
// 	$mail->CharSet = 'utf-8';
// 	$mail->IsSMTP();
// 	$mail->Host = 'smtp.gmail.com';
// 	$mail->Port = 587;
	
// 	$mail->SMTPAuth = true;
// 	$mail->Username = 'palitracc@gmail.com';
// 	$mail->Password = 'mevardato';
// 	$mail->SMTPSecure = 'tls';
	
// 	$mail->From = 'callcenter@palitracc.ge';
// 	$mail->FromName = 'Palitra Call Center';
// 	$mail->IsHTML(true);
	
// 	$mail->Subject = 'ახალი დავალება';
	
// 	$res = mysql_fetch_row(mysql_query("  SELECT persons.`name`
// 	    FROM users
// 	    JOIN persons ON users.person_id = persons.id
// 	    WHERE users.id = $user"));
	
//     $mail->Body    = " <h3>$action_name</h3><br><br>
//     <p>აღწერა: $task_comment</p><br><br><br>
//     <p>პერიოდი: $done_start_time - $done_end_time</p><br>
//     <p>ავტორი: $res[0]</p>
//     ";
	
// 	$mail->AddAddress("irasharvashidze@palitra.ge");
// 	$mail->AddAddress("l.tevdorashvili@palitra.ge");
// 	$mail->AddAddress("zura@biblusi.ge");
// 	$mail->AddAddress("z.kiknadze@biblusi.ge");
// 	$mail->AddAddress("contact@kvirispalitra.ge");
// 	$mail->AddAddress("t.morbedadze@palitra.ge");
// 	$mail->AddAddress("kh.jgutashvili@elva.ge");
// 	$mail->AddAddress("t.mujiri@salesland.ge");
// 	$mail->AddAddress("book@palitra.ge");
// 	$mail->AddAddress("anogogibedashvili@gmail.com");
// 	$mail->AddAddress("n.khucishvili@elva.ge");
// 	$mail->AddAddress("a.palavandishvili@elva.ge");
// 	$mail->AddAddress("lela@saqme.ge");
// 	$mail->AddAddress("g_popkhadze@yahoo.com");
// 	$mail->Send();
// 	$mail->ClearAddresses();

}
function Addsite_user($incomming_call_id, $personal_pin, $friend_pin, $personal_id)
{
	
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `site_user` 	(`incomming_call_id`, `site`, `pin`, `friend_pin`, `name`, `phone`, `mail`, `personal_id`, `user`)
						           		 VALUES 
											( '$incomming_call_id', '243', '$personal_pin', '$friend_pin', '11111111', 22222, '333', '$personal_id', '$user')");

}
				

function Savetask($task_id, $cur_date, $done_start_time, $done_end_time, $task_type_id, $template_id, $task_department_id, $persons_id)
{
	$c_date		= date('Y-m-d H:i:s');
	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE `task` SET  
								`user_id`				='$user',
								`responsible_user_id`	='$persons_id', 
								`date`					='$c_date',
								`planned_end_date`		='$planned_end_date',
								`fact_end_date`			='$fact_end_date', 
								`call_duration`			='$call_duration', 
								`priority_id`			='$priority_id',
								`template_id` 			='$template_id',
								`phone`					='$phone', 
								`comment`				='$comment', 
								`problem_comment`		='$problem_comment', 
								`status`='0', 
								`actived`='1'
								 WHERE `id`				='$task_id'
									");


}
function Savesite_user($incom_id, $personal_pin, $name, $personal_phone, $mail,  $personal_id)
{

	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE 	`site_user` 
				SET			
							`site`						='243', 
							`pin`						='$personal_pin', 
							`name`						='$name', 
							`phone`						='$personal_phone', 
							`mail`						='$mail', 
							`personal_id`				='$personal_id', 
							`user`						='$user'
							 WHERE `incomming_call_id`	='$incom_id'
							
					");

}


function Getcall_status($status)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `call_status`
						FROM 	`status`
						WHERE 	actived=1 AND `id` > 0");
	

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		
		if($res['id'] == $call_status_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['$status'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['$status'] . '</option>';
		}
	}
	return $data;
}
	function Getpay_type($pay_type_id)
	{
		$data = '';
		$req = mysql_query("SELECT 	`id`, `name`
							FROM 	`pay_type`
							WHERE 	actived=1");
	
	
		$data .= '<option value="0" selected="selected">----</option>';
		while( $res = mysql_fetch_assoc($req)){
			if($res['id'] == $pay_type_id){
				$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
			} else {
				$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
			}
		}
	
		return $data;
	}
	function Get_bank($bank_id)
	{
		$data = '';
		$req = mysql_query("SELECT 	`id`, `name`
							FROM 	`bank`
							WHERE 	actived=1");
	
	
		$data .= '<option value="0" selected="selected">----</option>';
		while( $res = mysql_fetch_assoc($req)){
			if($res['id'] == $bank_id){
				$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
			} else {
				$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
			}
		}
	
		return $data;
	}
	
function Getbank_object($bank_object_id)
{  
	$data = '';
	$req = mysql_query("SELECT  id,
						     	`name`
						FROM 	bank_object
						WHERE 	bank_object.bank_id=$bank_object_id && actived =1");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $bank_object_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getbank_object_edit($bank_object_id)
{

	$data = '';
	$req = mysql_query("SELECT  id,
								`name`
						FROM 	bank_object
						WHERE 	bank_object.id=$bank_object_id && actived =1");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $bank_object_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
	
function Getcard_type($card_type_id)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `name`
						FROM 	`card_type`
						WHERE 	actived=1");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $card_type_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
function Getcard_type1($card_type1_id)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `name`
						FROM 	`card_type`
						WHERE 	actived=1");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $card_type1_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
function Getpay_aparat($pay_aparat_id)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `name`
						FROM 	`pay_aparat`
						WHERE 	actived=1");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $pay_aparat_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
function Getobject($object_id)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `name`
						FROM 	`object`
						WHERE 	actived=1");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $object_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
function Getcategory($category_id)

{ 			

	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `category`
						WHERE actived=1 && parent_id=0 ");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getcategory1($category_id)
{

	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `category`
						WHERE actived=1 && parent_id=$category_id");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
	
}

function Getcategory1_edit($category_id)
{

	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `category`
						WHERE actived=1 && id=$category_id");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;

}

function Getcall_type($call_type_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `call_type`
						WHERE actived=1");
		
	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $call_type_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getpriority($priority_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `priority`
						WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $priority_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Gettemplate($template_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `template`
						WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $template_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Gettask_type($task_type_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
					    FROM `task_type`
					    WHERE actived=1 AND id < 10");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $task_type_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getdepartment($department_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	department
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $department_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function GetPersonss(){
	$data = '';
	$req = mysql_query("SELECT 		users.id AS `id`,
									persons.`name` AS `name`
						FROM 		`persons`
						JOIN    	users ON users.person_id = persons.id");

	$data .= '<option value="' . 0 . '" selected="selected">' . '' . '</option>';

	while( $res = mysql_fetch_assoc($req)){
		$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
	}
	return $data;
}

function Getpattern($id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `pattern`
						WHERE actived=1 ");

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


function Getcity($city_id){
    $req = mysql_query(" SELECT  `id`,
         `name`
       FROM  city
       WHERE  actived=1
       ");

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $city_id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}

function Getfamily($family_id){
    $req = mysql_query(" SELECT  `id`,
         `name`
       FROM  family
       WHERE  actived=1
       ");

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $family_id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}

function Getscenar(){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	shabloni
							GROUP BY 	`shabloni`.`name`
							");

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

function Getphonebase(){
	$data .= '
			
			<div id="dialog-form">
			<fieldset>
				<legend>ძირითადი ინფორმაცია</legend>
			<div id="dt_example" class="inner-table">
    							        <div style="width:100%;" id="container">        	
    							            <div id="dynamic">
    							            	<div id="button_area" >
													<button class="left_side" id="phone_base">სატელეფონო ბაზა</button>
													<button class="left_side" id="incomming_base">შემომავალი ზარები</button>
			
													<button class="left_side" id="back_1000_phone" > << 1000 </button>
    					 							<input style="width: 60px; border: none; text-align: center; background: #DFEFFC;" class="left_side" id="mtvleli_phone" value="0">
    					 							<button class="left_side" id="next_1000_phone" > 1000 >> </button>
			
													<button class="left_side dialog_hidden" id="back_1000_inc" > << 1000 </button>
							    					<input  style="width: 60px; border: none; text-align: center; background: #DFEFFC;" class="left_side dialog_hidden" id="mtvleli_inc" value="0">
							    					<button class="left_side dialog_hidden" id="next_1000_inc" > 1000 >> </button>
													
													<button class="left_side" id="add_formireba">ფორმირება</button>
	                                                <button class="left_side" id="add_formireba_done">გამოყენებულის დაფორმირება</button>
    						        			</div>
    							                <table class="display" id="base" style="width: 100%;">
    							                    <thead>
    													<tr  id="datatable_header">
    														<th style="width: 30px;">#</th>
															<th style="width: 30px;">#</th>
    														<th style="width: %;">ტელეფონი 1</th>
								                            <th style="width: %;">ტელეფონი 2</th>
								                            <th style="width: %;">სახელი/ <br> გვარი</th>
								                            <th style="width: %;">მისამართი</th>
								                            <th style="width: %;">ქალაქი</th>
								                            <th style="width: %;">წყარო</th>
								                            <th style="width: %;">ფორმირების<br>თარიღი</th>
								                            <th style="width: %;">ფიზიკური/<br>იურიდიული</th>
															<th style="width: %;">შენიშვნა</th>
															<th style="width: 30px;">#</th>
    													</tr>
    												</thead>
    												<thead class="filter">
    													<tr class="search_header">
    														<th class="colum_hidden">
    					                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
    					                            		</th>
															<th class="">
    					                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
    					                            		</th>
    														<th>
								                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
								                            </th>
								                            
								                            <th>
								                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
								                            </th>
								                             <th>
								                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
								                            </th>
								                            <th>
								                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
								                            </th>
								                             <th>
								                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
								                            </th>
								                            <th>
								                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
								                            </th>
								                             <th>
								                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
								                            </th>
								                            <th>
								                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
								                            </th>
								                            <th>
								                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
								                            </th>
															<th>
								                                <input type="checkbox" name="check-all" id="check-all-base">
								                            </th>
    													</tr>
    												</thead>
    							                </table>
    							            </div>
    							            <div class="spacer">
    							            </div>
    							        </div>
			</fieldset>
			<input type="text" style="display:none;" id="hidden_base" value="2" />		
										
			</div>
			';
	
	return $data;
}

function Gettask(){
	$data  .= '<div id="dialog-form">
							<div style="float: left; width: 380px;">
								<fieldset >
							    	<legend>ძირითადი ინფორმაცია</legend>
	
							    	<table style="height: 243px;">						
									<tr>
										<td style="width: 180px; color: #3C7FB1;">ტელეფონი</td>
										<td style="width: 180px; color: #3C7FB1;">პირადი ნომერი</td>
									</tr>
									<tr>
										<td>
											<input type="text" id="p_phone" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_phone'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="p_person_n" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_id'] . '" />
										</td>					
									</tr>
									<tr>
										<td style="width: 180px; color: #3C7FB1;">სახელი</td>
										<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
									</tr>
									<tr >
										<td style="width: 180px;">
											<input type="text" id="p_first_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_contragent'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="p_mail" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_mail'] . '" />
										</td>			
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">გვარი</td>
										<td td style="width: 180px; color: #3C7FB1;">ფიზ / იურ. პირი</td>
									</tr>
									<tr>
										<td style="width: 180px;">
											<input type="text" id="p_last_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_addres'] . '" />		
										</td>
										<td td style="width: 180px;">
											<input type="text" id="p_person_status" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_status'] . '" />		
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
										<td td style="width: 180px; color: #3C7FB1;">დაბადების თარირი</td>
									</tr>
									<tr>
										<td td style="width: 180px;">
											<input type="text" id="p_addres" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['person_status'] . '" />		
										</td>
										<td td style="width: 180px;">
											<input type="text" id="p_b_day" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['b_day'] . '" />		
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">ქალაქი</td>
										<td td style="width: 180px; color: #3C7FB1;">ოჯახური სტატუსი</td>
									</tr>
									<tr>
										<td><select style="width: 165px;" id="p_city_id" class="idls object">'.Getcity($res['city_id']).'</select></td>
										<td><select style="width: 165px;" id="p_family_id" class="idls object">'.Getfamily($res['family_id']).'</select></td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">პროფესია</td>
									</tr>
									<tr>
										<td td style="width: 180px;">
											<input type="text" id="p_profesion" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['profesion'] . '" />		
										</td>
									</tr>
								</table>
									
					</fieldset>
				</div>
		    </div>';
	
	
	return $data;
}

function Getincomming($task_id)
{
    $res = mysql_fetch_assoc(mysql_query("	SELECT 	task.id,
                                                    task.date,
                                                    task.start_date,
                                                    task.end_date,
                                                    task.task_type_id,
                                                    task.department_id,
                                                    task.responsible_user_id,
                                                    task.priority_id,
                                                    task.`comment`,
                                                    task.problem_comment,
                                                    task.`status`
                                               FROM task
                                               WHERE task.id = $task_id
                                         " ));

    return $res;
}

function GetPage($res='')
{
		$c_date		= date('Y-m-d H:i:s');
		
		$data  .= '<div id="dialog-form">
						<div style="float: left; width: 760px;">
							<fieldset >
						    	<legend>ძირითადი ინფორმაცია</legend>
					
						    	<table width="100%" class="dialog-form-table">
									<tr>
										<td style="width: 180px;"><label for="req_num">დავალების №</label></td>
										<td style="width: 180px;"><label for="req_num">შექმნის თარიღი</label></td>
										<td style="width: 180px;"><label for="req_data">შესრულების პერიოდი</label></td>
										<td style="width: 180px;"><label for="req_phone"></label></td>
									</tr>
									<tr>
										<td style="width: 150px;">
											<input style="width: 150px;" type="text" id="id" class="idle" onblur="this.className=\'idle\'"  value="' . (($res['id']!='')?$res['id']:increment('task')). '" />
										</td>
										<td style="width: 150px;">
											<input style="width: 150px;" type="text" id="cur_date" class="idle" onblur="this.className=\'idle\'"  value="' . (($res['date']!='')?$res['date']:$c_date). '" />
										</td>
										<td style="width: 200px;">
											<input style="float:left;" type="text" id="done_start_time" class="idle" onblur="this.className=\'idle\'" value="' .  $res['start_date']. '"  /><span style="float:left; margin-top:5px;">-დან</span>
										</td>
										<td style="width: 200px;">
											<input style="float:left;" type="text" id="done_end_time" class="idle" onblur="this.className=\'idle\'" value="' .  $res['end_date'] . '" /><span style="float:left; margin-top:5px;">-მდე</span>
										</td>
									</tr>
								</table>
										
								<table width="100%" class="dialog-form-table">
								   <tr>
										<td style="width: 220px;">დავალების ტიპი</select></td>
									    <td style="width: 220px;">ქვე-განყოფილება</select></td>
										<td style="width: 220px;">პრიორიტეტი</select></td> 
									</tr>
									<tr>
										<td style="width: 220px;"><select style="width: 220px;" id="task_type_id" class="idls object">'. Gettask_type($res['task_type_id']).'</select></td>
									    <td style="width: 220px;"><select style="width: 220px;" id="task_department_id" class="idls object">'.Getdepartment($res['department_id']).'</select></td>
										<td style="width: 220px;"><select style="width: 217px;" id="priority_id" class="idls object">'. Getpersons($res['priority_id']).'</select></td> 
									</tr>
								</table>
								
								<table width="100%" class="dialog-form-table" id="task_comment_table">
								   <tr>
										<td style="width: 220px;">დავალების შინაარსი</td>
									</tr>
									<tr>
										<td><textarea  style="width: 99%; resize: none; height: 50px;" id="task_comment" class="idle" name="task_comment" cols="300" >' . $res['comment'] . '</textarea></td>
									</tr>
								</table>
                            </fieldset>';
		              
		                //if (Gettask_type($res['task_type_id']) == 1 && Gettask_type($res['task_type_id']) == 2) {
		                
    											    
    					   $data.='<fieldset id="additional" class="hidden">
    							    	<legend>კლიენტთა ბაზა</legend>
    									<table width="100%" class="dialog-form-table">
    									    <tr>
                                                <td>სცენარი</td>
    											<!--td style="text-align: right;">ფაილის ატვირთვა</td-->
    					   						<td style="text-align: right;">სატელეფონო ბაზა</td>
    									   </tr>
    										<tr>
    											<td style="width: 200px;"><select style="width: 200px;" id="template_id" class="idls object">'. Getscenar($res['template_id']).'</select></td>
    											<!--td style="width: 100px;">
    											    <div class="file-uploader">
    									               <input id="choose_file" type="file" name="choose_file" class="input" style="display: none;">
    									               <button style="margin-right: 0px !important;" id="choose_button" class="center">აირჩიეთ ფაილი</button>
    									            </div>
    											</td-->
    											<td style="width: 100px;"><button style="margin-right: 0px !important;" id="choose_base" class="center">აირჩიეთ ბაზა</button>	</td>
    										</tr>
        								</table>
    													
    									<div id="dt_example" class="inner-table">
    							        <div style="width:100%;" id="container" >        	
    							            <div id="dynamic">
    							            	<div id="button_area">
    							            		<!--button id="add_button_pp">დამატება</button-->
    						        			</div>
    							                <table class="" id="example4" style="width: 100%;">
    							                    <thead>
    													<tr  id="datatable_header">
    							                           <th style="display:none">ID</th>
    														<th style="width:4%;">#</th>
    														<th style="width:%; word-break:break-all;">პირადი №<br>საიდ. კოდი</th>
    														<th style="width:%; word-break:break-all;">დასახელება</th>
    														<th style="width:%; word-break:break-all;">ფიზ / იურ.<br> პირი</th>
    														<th style="width:%; word-break:break-all;">ტელეფონი</th>
    														<th style="width:%; word-break:break-all;">ელ-ფოსტა</th>
    														<th style="width:%; word-break:break-all;">მისამართი</th>
    													</tr>
    												</thead>
    												<thead>
    													<tr class="search_header">
    														<th class="colum_hidden">
    					                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
    					                            		</th>
    														<th>
    															<input style="width:100px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
    														</th>
    														<th>
    															<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
    														</th>
    													<th>
    															<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
    														</th>
    													<th>
    															<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
    														</th>
    													<th>
    															<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
    														</th>
    													<th>
    															<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
    														</th>
    													</tr>
    												</thead>
    							                </table>
    							            </div>
    							            <div class="spacer">
    							            </div>
    							        </div>
								    </fieldset>';
    					
		               // }
		 $data .= '
 					</table>
					</fieldset>
				</div>
		    </div>';	

	
	$data .= '<input type="hidden" id="outgoing_call_id" value="' . $res['id'] . '" />';

	return $data;
}

function GetNote()
{
	$req = mysql_query("	SELECT 	`note` 
							FROM 	`phone`
							WHERE 	`actived` = 1 AND `note` != '' AND `import` = 1
							GROUP BY `note`");
	$data .= '<option value="" selected="selected"></option>';
	while( $res = mysql_fetch_assoc($req)){
		
		$data .= '<option value="' . $res['note'] . '">' . $res['note'] . '</option>';
		
	}
	
	return $data;
}

function GetNoteDone()
{
    $req = mysql_query("	SELECT 	`note`
							FROM 	`phone`
							WHERE 	`actived` = 1 AND `note` != '' AND `import` = 2
							GROUP BY `note`");
    $data .= '<option value="" selected="selected"></option>';
    while( $res = mysql_fetch_assoc($req)){

        $data .= '<option value="' . $res['note'] . '">' . $res['note'] . '</option>';

    }

    return $data;
}

function GetSorce()
{
	$req = mysql_query("	SELECT 	`sorce` 
							FROM 	`phone`
							WHERE 	`actived` = 1 AND `sorce` != '' AND `import` = 1
							GROUP BY `sorce`");
	$data .= '<option value="" selected="selected"></option>';
	while( $res = mysql_fetch_assoc($req)){
	
		$data .= '<option value="' . $res['sorce'] . '">' . $res['sorce'] . '</option>';
	
	}
	
	return $data;
}
function GetSorceDone()
{
    $req = mysql_query("	SELECT 	`sorce`
							FROM 	`phone`
							WHERE 	`actived` = 1 AND `sorce` != '' AND `import` = 2
							GROUP BY `sorce`");
    $data .= '<option value="" selected="selected"></option>';
    while( $res = mysql_fetch_assoc($req)){

        $data .= '<option value="' . $res['sorce'] . '">' . $res['sorce'] . '</option>';

    }

    return $data;
}

function GetDialogForm()
{
	$data  .= '	<div id="dialog-form">
					<fieldset>
					<legend>ფორმირება</legend>
						<table>
							<tr>
								<td><label for="f_number" style="">რაოდენობა</label></td>
								<td><input style="margin-left:2px; width:150px;" type="text" id="f_number" class="idls" value="" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57"></td>
							</tr>
							<tr>
								<td><label for="f_note" style="">შენიშვნა</label></td>
								<td><select style="margin-top:5px; margin-left:2px; width:155px;"  id="f_note" class="idls object">'. GetNote().'</select></td>
							</tr>
							<tr>
								<td><label for="f_sorce" style="">წყარო</label></td>
								<td><select style="margin-top:5px; margin-left:2px; width:155px;"  id="f_sorce" class="idls object">'. GetSorce().'</select></td>
							</tr>
						    <tr>
								<td><label for="f_name" style="">სახელი/გვარი</label></td>
								<td><input style="margin-top:5px; margin-left:2px; width:150px;" type="text" id="f_name" class="idls"></td>
							</tr>
						</table>
					</fieldset>
		    	</div>';
	
	return $data;
}

function GetDialogFormDone()
{
    $data  .= '	<div id="dialog-form">
					<fieldset>
					<legend>ფორმირება</legend>
						<table>
							<tr>
								<td><label for="f_m_number" style="">რაოდენობა</label></td>
								<td><input style="margin-left:2px; width:150px;" type="text" id="f_m_number" class="idls" value="" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57"></td>
							</tr>
							<tr>
								<td><label for="f_m_note" style="">შენიშვნა</label></td>
								<td><select style="margin-top:5px; margin-left:2px; width:155px;"  id="f_m_note" class="idls object">'. GetNoteDone().'</select></td>
							</tr>
							<tr>
								<td><label for="f_m_sorce" style="">წყარო</label></td>
								<td><select style="margin-top:5px; margin-left:2px; width:155px;"  id="f_m_sorce" class="idls object">'. GetSorceDone().'</select></td>
							</tr>
						    <tr>
								<td><label for="f_m_name" style="">სახელი/გვარი</label></td>
								<td><input style="margin-top:5px; margin-left:2px; width:150px;" type="text" id="f_m_name" class="idls"></td>
							</tr>
						</table>
					</fieldset>
		    	</div>';

    return $data;
}

function ChangeResponsiblePerson($letters, $responsible_person){
	$o_date		= date('Y-m-d H:i:s');
	foreach($letters as $letter) {

		mysql_query("UPDATE 	task
					SET    		task.`status`   			 = 1,
								task.`date` 			     = '$o_date',
								task.responsible_user_id     = '$responsible_person'
					WHERE  	task.id = '$letter'");
	}
}

function GetPersons($id){
	$data = '';
	$req = mysql_query("SELECT 		`id`,
									`name`
						FROM 		`priority`
						WHERE 		`actived` = 1
						");

	$data .= '<option value="' . 0 . '" selected="selected">' . '' . '</option>';

	while( $res = mysql_fetch_assoc($req)){
	    if($res['id'] == $id){
		    $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
	    }else{
	        $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
	    }
	}
	return $data;
}

function GetResoniblePersonPage(){
	$data = '
		<div id="dialog-form">
			<fieldset>
				<legend>ძირითადი ინფორმაცია</legend>
				<table width="100%" class="dialog-form-table" cellpadding="10px" >
					<tr>
						<th><label for="responsible_person">პასუხისმგებელი პირი</label></th>
					</tr>
					<tr>
						<th>
							<select id="responsible_person" class="idls address">'. GetPersonss() .'</select>
						</th>
					</tr>
				</table>
			</fieldset>
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
?>