<?php
/* ******************************
 *	Request aJax actions
 * ******************************
*/

require_once ('../../../includes/classes/core.php');
$action = $_REQUEST['act'];
$error	= '';
$data	= '';

$task_id				= $_REQUEST['id'];
$shabloni				= $_REQUEST['shabloni'];
$call_date				= $_REQUEST['call_date'];
$phone					= $_REQUEST['phone'];
$problem_comment 		= $_REQUEST['problem_comment'];
$call_duration 			= $_REQUEST['call_duration'];
$template_id			= $_REQUEST['template_id'];
$priority_id			= $_REQUEST['priority_id'];
$comment 	        	= $_REQUEST['comment'];
$task_type_id_seller 	= $_REQUEST['task_type_id_seller'];

$hidden_inc				= $_REQUEST['hidden_inc'];
$edit_id				= $_REQUEST['edit_id'];
$delete_id				= $_REQUEST['delete_id'];

// file
$rand_file				= $_REQUEST['rand_file'];
$file					= $_REQUEST['file_name'];

$status_id				= $_REQUEST['status_id'];

switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);
		
        break;
    case 'disable':
        mysql_query("DELETE FROM task
                     WHERE id = $task_id");
        
        break;
    case 'shablon':
        $page		= Getshablon($task_type_id_seller);
        $data		= array('page'	=> $page);
   
        break;
    case 'save_my_task':
        mysql_query("UPDATE `task` SET 
					 		`status`='$status_id',
					 		`problem_comment`='$problem_comment'
					WHERE 	`id`='$task_id'");
        	 
        break;
    case 'get_edit_page':
	  
		$page		= GetPage(Getincomming($task_id));
        
        $data		= array('page'	=> $page);
        
        break;
	
 	case 'get_list' :
		$count		= $_REQUEST['count'];
	   	$hidden		= $_REQUEST['hidden'];
	    $user_id	= $_REQUEST['user_id'];
	    $status	    = $_REQUEST['status'];
	    $user		= $_SESSION['USERID'];
	    
	    $group		= checkgroup($user);
	    
	    $filter = '';
	    if ($group != 2) {
	    	$filter = 'AND outgoing_call.responsible_user_id ='. $user;
	    }
	    if($user == 1 || $user == 3){
	        $permiss = '';
	    }else {
	        $permiss = "AND (task.responsible_user_id = '$user' || task.user_id = '$user')";
	    }
	     
	    $rResult = mysql_query("SELECT 	task.id,
										task.id,
										task.date,
										task.start_date,
										task.end_date,
										task_type.`name`,
										department.`name`,
										persons.`name`,
	                                    ps.`name`,
										priority.`name`,
	                                    `status`.`call_status`
								FROM task
								JOIN task_type ON task.task_type_id = task_type.id
								JOIN department ON task.department_id = department.id
								JOIN users ON task.responsible_user_id = users.id
								JOIN persons ON users.person_id = persons.id	        
	                            JOIN `users` AS `us` ON task.user_id = us.id 
                                JOIN `persons` AS `ps` ON us.person_id = ps.id 	        
    						    JOIN `status` ON task.`status` = `status`.id
								LEFT JOIN priority ON task.priority_id = priority.id
								WHERE task.`status` = $status $permiss
	                            ORDER BY task.priority_id ASC");
	    
										    		
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
				    $row[] = '<input type="checkbox" name="check_' . $aRow[$hidden] . '" class="check_gas" value="' . $aRow[$hidden] . '" />';
				}
			}
			$data['aaData'][] = $row;
		}

        break;
    case 'save_outgoing':
	
		$user_id		= $_SESSION['USERID'];
		
		Savetask($task_id, $problem_comment, $file, $rand_file);
        break;
        case 'done_outgoing':
        
        	$user_id		= $_SESSION['USERID'];
        
        	Savetask1($task_id, $problem_comment, $file, $rand_file);
        	break;
    default:
       $error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	task Functions
 * ******************************
 */

function checkgroup($user){
	$res = mysql_fetch_assoc(mysql_query("
											SELECT users.group_id
											FROM    users
											WHERE  users.id = $user
										"));
	return $res['group_id'];
	
}



function Savetask($task_id, $problem_comment, $file, $rand_file)
{
	$c_date		= date('Y-m-d H:i:s');
	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE `task` SET  
								`user_id`			='$user',
								`problem_comment`	='$problem_comment', 
								`status`	='2', 
								`actived`	='1'
								 WHERE `id`			='$task_id'
									");

}
function Savetask1($task_id, $problem_comment, $file, $rand_file)
{
	$c_date		= date('Y-m-d H:i:s');
	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE `task` SET
								`user_id`			='$user',
								`problem_comment`	='$problem_comment',
								`status`	='3'
				WHERE 			`id`				='$task_id'
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

	if($res['id'] == $status){
	$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['call_status'] . '</option>';
} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['call_status'] . '</option>';
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
							WHERE actived=1 ");
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


function Getpersons($persons_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `persons`
						WHERE actived=1 ");

		$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $persons_id){
		$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
		$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getprio($pero_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `priority`
						WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $pero_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getpersonss($persons_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
							FROM `persons`
							WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $persons_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function getCalls(){
		$db1 = new sql_db ( "212.72.155.176", "root", "Gl-1114", "asteriskcdrdb" );

	$req = mysql_query("

	SELECT  	DISTINCT
	IF(SUBSTR(cdr.src, 1, 3) = 995, SUBSTR(cdr.src, 4, 9), cdr.src) AS `src`
						FROM    	cdr
						GROUP BY 	cdr.src
						ORDER BY 	cdr.calldate DESC
						LIMIT 		12


						");

	$data = '<tr class="trClass">
					<th class="thClass">#</th>
					<th class="thClass">ნომერი</th>
					<th class="thClass">ქმედება</th>
	</tr>
	';
	$i	= 1;
	while( $res3 = mysql_fetch_assoc($req)){

		$data .= '
	    		<tr class="trClass">
					<td class="tdClass">' . $i . '</td>
			<td class="tdClass" style="width: 30px !important;">' . $res3['src'] . '</td>
					<td class="tdClass" style="font-size: 13px !important;"><button class="insert" number="' . $res3['src'] . '">დამატება</button></td>
					</tr>';
		$i++;
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

function Getshablon($id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	shabloni
							WHERE 	scenar_id = $id
							GROUP BY 	`shabloni`.`name`
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $id){
			$data .= '<option value="' . $res['name'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['name'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getstatus($status,$user_id){
    $tratata_kaifob="";
    if($_SESSION['USERID'] == 13 || $_SESSION['USERID'] == 42 || $_SESSION['USERID'] == 43){
        $tratata_kaifob="AND `id` in(1,2,3,4,6)";
    }else{
        $tratata_kaifob="AND `id` in(1,2,3,4,5)";
    }
	$req = mysql_query("	SELECT 	`id`,
									`call_status`
							FROM 	`status`
							WHERE 	`actived`=1 $tratata_kaifob
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $status){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['call_status'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['call_status'] . '</option>';
		}
	}

	return $data;
}

function Getfamily($family_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	`family`
							WHERE 	`actived`=1
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

function Getcity($city_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	city
							WHERE 	actived=1
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
												task.`status`,
                                                task.`outgoing_id`,
                                                task.incomming_call_id,
                                                task.user_id,
                                                incomming_call.phone,
                                                incomming_call.first_name,
                                                DATE_FORMAT(incomming_call.`date`,'%y-%m-%d') AS `date_inc`,
												phone.phone1,
												phone.phone2,
												phone.first_last_name,
												phone.addres,
												phone.person_n,
                                                DATE_FORMAT(task_scenar.`date`,'%y-%m-%d') as `out_date`,
                                                phone.mail,
                                                phone.city,
                                                phone.born_day,
                                                phone.sex,
                                                phone.age,
                                                phone.profession,
                                                phone.interes
										FROM task
                                        LEFT JOIN incomming_call ON task.incomming_call_id = incomming_call.id
										LEFT JOIN task_detail ON task.outgoing_id = task_detail.id
										LEFT JOIN phone ON task_detail.phone_base_id = phone.id
                                        LEFT JOIN task_scenar ON task_detail.id = task_scenar.task_detail_id
                                        WHERE task.id = $task_id
			" ));
	
	return $res;
}


function GetPage($res='')
{
	$data  .= '<div id="dialog-form">
						<div style="float: left; width: 760px;">
							<fieldset >
						    	<legend>დავალება</legend>
			
						    	<table width="100%" class="dialog-form-table">
									<tr>
										<td style="width: 180px;"><label for="req_num">დავალების №</label></td>
										<td style="width: 180px;"><label for="req_num">შექმნის თარიღი</label></td>
										<td style="width: 180px;"><label for="req_data">შესრულების პერიოდი</label></td>
										<td style="width: 180px;"><label for="req_phone"></label></td>
									</tr>
									<tr>
										<td style="width: 150px;">
											<input style="width: 150px;" disabled type="text" id="id_my_task" class="idle" onblur="this.className=\'idle\'"  value="' . $res['id'] . '" />
										</td>
										<td style="width: 150px;">
											<input style="width: 150px;" disabled type="text" id="cur_date" class="idle" onblur="this.className=\'idle\'"  value="' . $res['date'] . '" />
										</td>
										<td style="width: 200px;">
											<input style="float:left;" disabled type="text" id="done_start_time" class="idle" onblur="this.className=\'idle\'" value="' .  $res['start_date']. '"  /><span style="float:left; margin-top:5px;">-დან</span>
										</td>
										<td style="width: 200px;">
											<input style="float:left;" disabled type="text" id="done_end_time" class="idle" onblur="this.className=\'idle\'" value="' .  $res['end_date'] . '" /><span style="float:left; margin-top:5px;">-მდე</span>
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
										<td style="width: 220px;"><select style="width: 220px;" disabled id="task_type_id" class="idls object">'. Gettask_type($res['task_type_id']).'</select></td>
									    <td style="width: 220px;"><select style="width: 220px;" disabled id="task_department_id" class="idls object">'.Getdepartment($res['department_id']).'</select></td>
										<td style="width: 220px;"><select style="width: 217px;" disabled id="priority_id" class="idls object">'. Getprio($res['priority_id']).'</select></td>
									</tr>
								</table>
	
								<table width="100%" class="dialog-form-table" id="task_comment_table">
								   <tr>
										<td style="width: 220px;">დავალების შინაარსი</td>
									</tr>
									<tr>
										<td><textarea disabled  style="width: 99%; resize: vertical; height: 70px;" id="task_comment" class="idle" name="task_comment" cols="300" >' . $res['comment'] . '</textarea></td>
									</tr>
								</table>
								</fieldset>
								<fieldset>
									<legend>შესრულება</legend>
								<table width="100%" class="dialog-form-table" id="">
								   <tr>
										<td style="width: 220px;">სტატუსი</td>
										<td style="width: 220px;">კომენტარი</td>
									</tr>
									<tr>
										<td style="width: 220px;"><select style="width: 217px;"  id="status_id" class="idls object">'. Getstatus($res['status'],$res['user_id']).'</select></td>
										<td><textarea  style="width: 98%; resize: vertical; height: 70px;" id="problem_comment" class="idle" name="problem_comment" cols="300" >' . $res['problem_comment'] . '</textarea></td>
									
									</tr>
										
									
								</table>
                            </fieldset>
 					</table>
					</fieldset>
				</div>';

	if($res['outgoing_id'] != ''){
	    $data .='<div style="float: right;  width: 355px;">
								<fieldset id="open_out">
								<legend>აბონენტი</legend>
								<table style="height: 243px;">
									<tr>
										<td style="width: 180px; color: #3C7FB1;">ტელეფონი 1</td>
										<td style="width: 180px; color: #3C7FB1;">ტელეფონი 2</td>
									</tr>
									<tr>
										<td>
											<input type="text" id="phone"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['phone1'] . '" />
										</td>
										<td>
											<input type="text" id="phone1"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['phone2'] . '" />
										</td>
								
									</tr>
									<tr>
										<td style="width: 180px; color: #3C7FB1;">სახელი</td>
										<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
									</tr>
									<tr >
										<td style="width: 180px;">
											<input type="text" id="first_name"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['first_last_name'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="mail"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['mail'] . '" />
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">ქალაქი</td>
										<td td style="width: 180px; color: #3C7FB1;">დაბადების თარიღი</td>
									</tr>
									<tr>
										<td><input type="text" id="city_id"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['city'] . '" /></td>
										<td td style="width: 180px;">
											<input type="text" id="b_day"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['born_day'] . '" />
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
										<td style="width: 180px; color: #3C7FB1;">პირადი ნომერი</td>
									</tr>
									<tr>
										<td td style="width: 180px;">
											<input type="text" id="addres"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['addres'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="person_n"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['person_n'] . '" />
										</td>
									</tr>
							
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">ასაკი</td>
										<td style="width: 180px; color: #3C7FB1;">სქესი</td>
									</tr>
									<tr>
										<td td style="width: 180px;">
											<input type="text" id="age"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['age'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="sex"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['sex'] . '" />
										</td>
									</tr>
					
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">პროფესია</td>
										<td style="width: 180px; color: #3C7FB1;">ინტერესების სფერო</td>
									</tr>
									<tr>
										<td td style="width: 180px;">
											<input type="text" id="profession"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['profession'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="interes"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['interes'] . '" />
											    <input id="open_out_id" type="text" value="'.$res[outgoing_id].'" style="display:none;">
										</td>
									</tr>
					
								</table>
							</fieldset>';
	}else{
	           $data .= '<div style="float: left; margin-left:10px; width: 355px;">
					<fieldset id="open_inc">
						<legend>ინფორმაცია</legend>
							<table class="dialog-form-table">
								<tr>
									<td style="width: 150px;"><label for="req_num">ტელეფონი</label></td>
								</tr>
								
								<tr>
									<td style="width: 150px;">
										<input style="width: 130px;" disabled type="text" id="id_my_task" class="idle" onblur="this.className=\'idle\'"  value="' . $res['phone'] . '" />
									</td>
								</tr>
							    <tr>
									<td style="width: 150px;"><label for="req_num">სახელი</label></td>
								</tr>
							    <tr>
									<td>
										<input style="width: 130px;" disabled type="text" id="cur_date" class="idle" onblur="this.className=\'idle\'"  value="' . $res['first_name'] . '" />
										    <input id="open_inc_id" type="text" value="'.$res[incomming_call_id].'" style="display:none;">
									</td>
								</tr>
							</table>
	                </fieldset>';
	}
            	    if(!empty($res[phone])){
					   $data .= GetRecordingsSection($res);
				    }
				    
				    if($res['outgoing_id'] != ''){
				        $data .= GetRecordingsSection1($res);
				    }
				    
	$data .= ' </div>
		    </div>';
	
	
	$data .= '<input type="hidden" id="outgoing_call_id" value="' . $res['id'] . '" />';
	
	return $data;
}

function GetRecordingsSection1($res)
{
    $phone = '';
    if(strlen($res['phone1']) > 3){
        $phone = $res[phone1];
    }
    elseif(strlen($res['phone2']) > 3) {
        $phone = $res[phone2];
    }else{
        $phone = 'fmyjeans';
    }

    $req = mysql_query("SELECT  TIME(`calldate`) AS 'time',
        SUBSTR(`userfield`, 7) as userfield
        FROM     `cdr`
        WHERE     (LENGTH(`src`) = 3 && `dst` LIKE '%$phone%')");

    $data .= '
        <fieldset style="margin-top: 10px; width: 333px; float: left;">
            <legend>ჩანაწერები</legend>

            <table style="width: 66%; border: solid 1px #85b1de; margin:auto;">
                <tr style="border-bottom: solid 1px #85b1de; height: 20px;">
                    <th style="padding-left: 10px;">დრო</th>
                    <th  style="border: solid 1px #85b1de; padding-left: 10px;">ჩანაწერი</th>
                </tr>';
    if (mysql_num_rows($req) == 0){
        $data .= '<td colspan="2" style="height: 20px; text-align: center;">ჩანაწერები ვერ მოიძებნა</td>';
    }

    while( $res2 = mysql_fetch_assoc($req)){
        $link = $res2['userfield'];
        $data .= '
                <tr style="border-bottom: solid 1px #85b1de; height: 20px;">
                    <td>' . $res2['time'] . '</td>
                    <td><button class="download1" str="' . $link . '">მოსმენა</button></td>
                </tr>';
    }

    $data .= '
            </table>
        </fieldset>';

    return $data;
}

function GetRecordingsSection($res)
{
    $phone = '';
    if(strlen($res['phone']) > 3){
        $phone = $res[phone];
    }else{
        $phone = 'fmyjeans';
    }
	$req = mysql_query("SELECT  `calldate` AS 'time',
			`userfield`
			FROM     `cdr`
			WHERE     ((`dst` = 2196013 or `dst` = 2196053 or `dst` = 2420421 or `dst` = 2486844 or `dst` = 2159030 or `dst` = 2470046 or `dst` = 2486844) && `userfield` != '' && `src` LIKE '%$phone%')
			OR      (`dst` LIKE '%$phone%' && `userfield` != '');");

	$data .= '
        <fieldset style="margin-top: ; width: 333px; float: left;">
            <legend>ჩანაწერები</legend>

            <table style="width: 66%; border: solid 1px #85b1de; margin:auto;">
                <tr style="border-bottom: solid 1px #85b1de; height: 20px;">
                    <th style="padding-left: 10px;">დრო</th>
                    <th  style="border: solid 1px #85b1de; padding-left: 10px;">ჩანაწერი</th>
                </tr>';
	if (mysql_num_rows($req) == 0){
		$data .= '<td colspan="2" style="height: 20px; text-align: center;">ჩანაწერები ვერ მოიძებნა</td>';
	}

	while( $res2 = mysql_fetch_assoc($req)){
		$src = $res2['userfield'];
		$link = explode("/", $src);
		$data .= '
                <tr style="border-bottom: solid 1px #85b1de; height: 20px;">
                    <td style="vertical-align: middle; text-align: center;">' . $res2['time'] . '</td>
                    <td style="vertical-align: middle; text-align: center;"><button class="download" str="' . $link[5] . '">მოსმენა</button></td>
                </tr>';
	}

	$data .= '
            </table>
        </fieldset>';

	return $data;
}


?>