<?php
/* ******************************
 *	Request aJax actions
 * ******************************
*/

require_once ('../../../../includes/classes/core.php');
$action = $_REQUEST['act'];
$error	= '';
$data	= '';

$task_id				= $_REQUEST['id'];
$call_date				= $_REQUEST['call_date'];
$phone					= $_REQUEST['phone'];
$problem_comment 		= $_REQUEST['problem_comment'];
$call_duration 			= $_REQUEST['call_duration'];
$template_id			= $_REQUEST['template_id'];
$priority_id			= $_REQUEST['priority_id'];
$comment 	        	= $_REQUEST['comment'];

$hidden_inc				= $_REQUEST['hidden_inc'];
$edit_id				= $_REQUEST['edit_id'];
$delete_id				= $_REQUEST['delete_id'];

// file
$rand_file				= $_REQUEST['rand_file'];
$file					= $_REQUEST['file_name'];

switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);
		
        break;
    case 'get_edit_page':
	  
		$page		= GetPage(Getincomming($task_id));
        
        $data		= array('page'	=> $page);
        
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
	     
	    $scenar_name	= $_REQUEST['scenar_name'];
	    $rResult = mysql_query("SELECT 	`production`.`id`,
										`production`.`name`,
										`production`.`price`,
										`production`.`description`,
										`production`.`comment`
								FROM 	`production`
								JOIN 	`shabloni` ON `production`.`id` = `shabloni`.`product_id`
								WHERE 	`production`.`actived`=1 AND shabloni.quest_id = '4' AND shabloni.`name` = '$scenar_name'");
	    
										    		
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
        case 'delete_file':
        
        	mysql_query("DELETE FROM file WHERE id = $delete_id");
        
        	$increm = mysql_query("	SELECT  `name`,
        			`rand_name`,
        			`id`
        			FROM 	`file`
        			WHERE   `task_id` = $edit_id
        			");
        
        	$data1 = '';
        
        	while($increm_row = mysql_fetch_assoc($increm))	{
        	$data1 .='<tr style="border-bottom: 1px solid #85b1de;">
				          <td style="width:110px; display:block;word-wrap:break-word;">'.$increm_row[name].'</td>
        			<td ><button type="button" value="media/uploads/file/'.$increm_row[rand_name].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none;background-image:url(\'media/images/get.png\');" id="download" ></button><input type="text" style="display:none;" id="download_name" value="'.$increm_row[rand_name].'"> </td>
        					<td ><button type="button" value="'.$increm_row[id].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none; background-image:url(\'media/images/x.png\');" id="delete"></button></td>
 					  </tr>';
		}
        
		$data = array('page' => $data1);
        
        		break;
        
                		case 'up_now':
                		$user		= $_SESSION['USERID'];
                		if($rand_file != ''){
                		mysql_query("INSERT INTO 	`file`
                				( 	`user_id`,
                				`task_id`,
                					`name`,
                					`rand_name`
                					)
                					VALUES
                					(	'$user',
                					'$edit_id',
                					'$file',
                					'$rand_file'
                				);");
                				}
        
                				$increm = mysql_query("	SELECT  `name`,
                				`rand_name`,
                				`id`
                				FROM 	`file`
                				WHERE   `task_id` = $edit_id
                				");
        
                				$data1 = '';
        
                				while($increm_row = mysql_fetch_assoc($increm))	{
                				$data1 .='<tr style="border-bottom: 1px solid #85b1de;">
                					<td style="width:110px; display:block;word-wrap:break-word;">'.$increm_row[name].'</td>
        		<td ><button type="button" value="media/uploads/file/'.$increm_row[rand_name].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none;background-image:url(\'media/images/get.png\');" id="download" ></button><input type="text" style="display:none;" id="download_name" value="'.$increm_row[rand_name].'"> </td>
				          <td ><button type="button" value="'.$increm_row[id].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none; background-image:url(\'media/images/x.png\');" id="delete"></button></td>
        				          </tr>';
		}
        
		$data = array('page' => $data1);
        
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
					WHERE 	actived=1");


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


function Getdepartment($task_department_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `department`
						WHERE actived=1 AND id=37 ");


		
		while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $task_department_id){
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


}function Getincomming($task_id)
{
$res = mysql_fetch_assoc(mysql_query("	SELECT		task.id AS `id`,
													incomming_call.id AS `call_id`,
													IF(ISNULL(task.phone), incomming_call.phone, task.phone) AS `phone`,
													IF(ISNULL(incomming_call.date), task.date, incomming_call.date) AS call_date,
													incomming_call.call_type_id AS call_type_id,
													incomming_call.call_category_id AS category_id,
													IF(ISNULL(task.`status`), 3, task.`status`) AS `status`,
													incomming_call.call_subcategory_id AS category_parent_id,
													incomming_call.problem_date ,
													incomming_call.call_content AS call_content,
													incomming_call.pay_type_id AS pay_type_id,
													incomming_call.bank_id AS bank_id,
													incomming_call.bank_object_id AS bank_object_id,
													incomming_call.card_type_id AS card_type_id,
													incomming_call.card_type_id AS card_type1_id,
													incomming_call.pay_aparat_id AS pay_aparat_id,
													incomming_call.object_id AS object_id,
													site_user.`name` AS `name`,
													site_user.mail AS mail,
													site_user.personal_id AS personal_id,
													site_user.phone AS personal_phone,
													site_user.pin AS personal_pin,
													site_user.friend_pin AS friend_pin,
													site_user.`name` AS `name1`,
													site_user.`mail` AS `mail`,
													site_user.`user` AS `user`,
													task.task_type_id AS task_type_id,
													task.responsible_user_id AS persons_id,
													task.priority_id AS priority_id,
													task.planned_end_date AS planned_end_date,
													task.fact_end_date   AS fact_end_date,
													task.call_duration   AS 	call_duration,
													task.department_id AS task_department_id,
													task.phone AS phone,
													task.`comment` AS `comment`,
													task.problem_comment AS problem_comment,
													template.id AS template_id

										FROM 	   	task
										LEFT JOIN  	incomming_call  ON incomming_call.id = task.incomming_call_id
										LEFT JOIN  	site_user ON incomming_call.id = site_user.incomming_call_id
										LEFT JOIN  	template ON task.template_id = template.id
										WHERE      	task.id = $task_id
			" ));
	
	return $res;
}


function GetPage($res='', $number)
{
	$num = 0;
	if($res[phone]==""){
		$num=$number;
	}else{ 
		$num=$res[phone]; 
	}

		$data  .= '<div id="dialog-form">
							<div style="float: left; width: 670px;">
								<fieldset >
							    	<legend>ძირითადი ინფორმაცია</legend>
						
							    	<table width="65%" class="dialog-form-table">
										<tr>
											<td style="width: 180px;"><label for="">დავალების №</label></td>
											<td style="width: 180px;"><label for="">თარიღი</label></td>
										</tr>
										<tr>
											<td>
												<input type="text" id="id" class="idle" onblur="this.className=\'idle\'"  value="' . $res['id']. '" disabled="disabled" />
											</td>
											<td>
												<input type="text" id="c_date" class="idle" onblur="this.className=\'idle\'"  value="' .  $res['call_date']. '" disabled="disabled" />
											</td>		
										</tr>
											</table>
														
								<fieldset style="width:250px; float:left;">
							    	<legend>დავალების ტიპი</legend>
								<table class="dialog-form-table">
							    		<tr>
											<td><select style="width: 270px;" id="" class="idls object">'.Gettask_type($res['task_type_id']).'</select></td>
										</tr>
									</table>
								</fieldset>
								<fieldset style="width:340px; float:left; margin-left:10px;">
							    	<legend>სცენარის დასახელება</legend>
								<table class="dialog-form-table">
							    		<tr>
											<td><select style="width: 345px;" id="" class="idls object">'.Gettask_type($res['task_type_id']).'</select></td>
										</tr>
									</table>
								</fieldset>
						        ';
						
						$data .= '
							<div id="seller">
									<ul>
										<li style="margin-left:0;" id="0" onclick="seller(this.id)" class="seller_select">მისალმება</li>
										<li id="1" onclick="seller(this.id)" class="">შეთავაზება</li>
										<li id="2" onclick="seller(this.id)" class="">შედეგი</li>
									</ul>
									<div id="seller-0">
									<fieldset style="width:95%; float:left; ">
									<fieldset style="width:95%;">
								    	<legend>მისალმება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 620px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
									</table>
									</fieldset>
									<table class="dialog-form-table" style="width:500px;">
								    		<tr>
												<td style="text-align:right;"><span>აქვს</span></td>
					  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
					  							<td><span>(ვაგრძელებთ)</span></td>
					  						</tr>
											<tr>
												<td style="text-align:right;"><span>სურს სხვა დროს</span></td>
					  							<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='2')?"checked":"").'></td>
					  							<td><span>(ვიფორმირებთ დავალებას)</span></td>
					  						</tr>
					  						<tr>
												<td style="text-align:right;"><span>არ სურს</span></td>
					  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
					  							<td><span>(ვასრულებთ)</span></td>
					  						</tr>
									</table>
					  				<fieldset style="width:95%; float:left; ">
								    	<legend>კომენტარი</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 620px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
											</tr>
									</table>
									</fieldset>
											<button style="float:right; margin-top:10px;" onclick="seller(1)" class="next"> >> </button>
											<button style="float:right; margin-top:10px;" class="done">დასრულება</button>
									</fieldset>
									 </div>

														
														
									<div id="seller-1" class="dialog_hidden">
									<fieldset style="width:95%; float:left; ">
									<fieldset style="width:95%;">
								    	<legend>შეთავაზება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 620px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
									</table>
									</fieldset>
									<fieldset style="width:96.6%;">
								    	<legend>პროდუქტი</legend>
									<div id="dt_example" class="inner-table">
								        <div style="width:100%;" id="container" >        	
								            <div id="dynamic">
								            	<div id="button_area">
								            		<button id="add_button_pp">დამატება</button>
							        			</div>
								                <table class="" id="example4" style="width: 100%;">
								                    <thead>
														<tr  id="datatable_header">
																
								                           <th style="display:none">ID</th>
															<th style="width:4%;">#</th>
															<th style="">პაკეტი</th>
															<th style="">ფასი</th>
															<th style="">აღწერილობა</th>
															<th style="">შენიშვნა</th>
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
																<input style="width:100px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
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
										<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 99%; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
										</table>
									</fieldset>
					  				<fieldset style="width:95%; float:left; ">
								    	<legend>კომენტარი</legend>
														
									<div id="dt_example" class="inner-table">
								        <div style="width:100%;" id="container" >        	
								            <div id="dynamic">
								            	<div id="button_area">
								            		<button id="add_button_pp">დამატება</button>
							        			</div>
								                <table class="" id="example3" style="width: 100%;">
								                    <thead>
														<tr  id="datatable_header">
																
								                           <th style="display:none">ID</th>
															<th style="width:4%;">#</th>
															<th style="">პაკეტი</th>
															<th style="">ფასი</th>
															<th style="">აღწერილობა</th>
															<th style="">შენიშვნა</th>
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
																<input style="width:100px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
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
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 620px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
									</table>
									</fieldset>
											<fieldset >
												<legend>ინფორმაცია</legend>
											<table class="dialog-form-table" style="width:250px; float:left;">
									    		<tr>
													<td style="text-align:right;">მოისმინა ბოლომდე</td>
													<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
													
												</tr>
												<tr>
													<td style="text-align:right;">მოისმინა და კითხვები დაგვისვა</td>
													<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='2')?"checked":"").'></td>		
												</tr>
												<tr>
													<td style="text-align:right;">შეგვაწყვეტინა</td>
													<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
												</tr>
											</table>
											<table class="dialog-form-table" style="width:350px; float:left; margin-left: 15px;">
												<tr>
													<td>კომენტარი</td>
												</tr>
									    		<tr>
													<td><textarea  style="width: 100%; height:50px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
												</tr>
											</table>
											</fieldset>
											<button style="float:right; margin-top:10px;" onclick="seller(2)" class="next"> >> </button>
											<button style="float:right; margin-top:10px;" onclick="seller(0)" class="back"> << </button>
									
									</fieldset>
													
									 </div>
									 <div id="seller-2" class="dialog_hidden">
											<fieldset style="width:95%; float:left; ">
											<fieldset style="width:95%;">
										    	<legend>შეთავაზება</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 620px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
													</tr>
													<tr>
														<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
													</tr>
											</table>
											</fieldset>
											
															
																
							  				<fieldset style="width:95%; float:left; ">
										    	<legend>კომენტარი</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 620px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
													</tr>
											</table>
											</fieldset>
													<button style="float:right; margin-top:10px;" class="done">დასრულება</button>
													<button style="float:right; margin-top:10px;" onclick="seller(1)" class="next"> << </button>
											</fieldset>		
									 </div>
									
							</div>
								<fieldset style="width:320px;; float:left;">
								    	<legend>ზარის დაზუსტება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 320px; height:70px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
											</tr>
									</table>
									</fieldset>	
									<fieldset style="width:250px;; float:left; margin-left:10px; height:90px;">
								    	<legend>სტატუსი</legend>
									<table class="dialog-form-table">
											<tr>
												<td></td>
											</tr>
								    		<tr>
												<td><select style="width: 290px;" id="" class="idls object">'.Gettask_type($res['task_type_id']).'</select></td>
											</tr>
									</table>
									</fieldset>
								<fieldset style="margin-top: 5px;">
								    	<legend>დავალების ფორმირება</legend>
							
								    	<table class="dialog-form-table" >
											<tr>
												<td style="width: 190px;"><label for="d_number">განყოფილება</label></td>
												<td style="width: 190px;"><label for="d_number">პასუხისმგებელი პირი</label></td>
												<td style="width: 190px;"><label for="d_number">პრიორიტეტი</label></td>
											</tr>
								    		<tr>
												<td><select style="width: 180px;" id="task_type_id" class="idls object">'.Gettask_type($res['task_type_id']).'</select></td>
												<td><select style="width: 180px;" id="task_department_id" class="idls object">'. Getdepartment($res['task_department_id']).'</select></td>
												<td><select style="width: 180px;" id="persons_id" class="idls object">'.Getpersons($res['persons_id']).'</select></td>
											</tr>
											<tr>
												<td style="width: 150px;"><label>შესრულების პერიოდი</label></td>
												<td style="width: 150px;"><label></label></td>
												<td style="width: 150px;"><label>კომენტარი</label></td>
											</tr>
											<tr>
												<td><input style="width: 130px; float:left;" class="idle" type="text"><span style="margin-left:5px; ">დან</span></td>
										  		<td><input style="width: 130px; float:left;" class="idle" type="text"><span style="margin-left:5px; ">მდე</span></td>
												<td>
													<textarea  style="width: 250px; resize: none;" id="comment" class="idle" name="content" cols="300">' . $res['comment'] . '</textarea>
												</td>
											</tr>
										</table>
							        </fieldset>	
							</fieldset>	
							</div>';
						
						
						$data .='<div style="float: right;  width: 355px;">
								  <fieldset>
									<legend>სასარგებლო ბმულები</legend>
									<table>
										<tr>
											<td style="width: 180px;">link 1</td>
											<td style="width: 180px;">link 2</td>
											<td style="width: 180px;">link 3</td>
										</tr>
										
									</table>
								</fieldset>
								<fieldset>
								<legend>აბონენტი</legend>
								<table style="height: 243px;">						
									<tr>
										<td style="width: 180px; color: #3C7FB1;">ტელეფონი</td>
										<td style="width: 180px; color: #3C7FB1;">პირადი ნომერი</td>
									</tr>
									<tr>
										<td>
											<input type="text" id="personal_phone" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_phone'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="personal_id" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_id'] . '" />
										</td>					
									</tr>
									<tr>
										<td style="width: 180px; color: #3C7FB1;">სახელი</td>
										<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
									</tr>
									<tr >
										<td style="width: 180px;">
											<input type="text" id="personal_contragent" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_contragent'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="personal_mail" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_mail'] . '" />
										</td>			
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">გვარი</td>
										<td td style="width: 180px; color: #3C7FB1;">დაბადების თარიღი</td>
									</tr>
									<tr>
										<td style="width: 180px;">
											<input type="text" id="personal_addres" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_addres'] . '" />		
										</td>
										<td td style="width: 180px;">
											<input type="text" id="personal_status" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_status'] . '" />		
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">ქალაქი</td>
										<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
									</tr>
									<tr>
										<td><select style="width: 165px;" id="persons_id" class="idls object">'.Getpersons($res['persons_id']).'</select></td>
										<td td style="width: 180px;">
											<input type="text" id="personal_status" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_status'] . '" />		
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">ოჯახური სტატუსი</td>
										<td td style="width: 180px; color: #3C7FB1;">პროფესია</td>
									</tr>
									<tr>
										<td><select style="width: 165px;" id="persons_id" class="idls object">'.Getpersons($res['persons_id']).'</select></td>
										<td td style="width: 180px;">
											<input type="text" id="personal_status" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_status'] . '" />		
										</td>
									</tr>
								</table>
							</fieldset>
								
							</div>
				    </div>';
	
	
	$data .= '<input type="hidden" id="outgoing_call_id" value="' . $res['id'] . '" />';

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