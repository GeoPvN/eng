<?php
// MySQL Connect Link
require_once('../../includes/classes/core.php');

// Main Strings
$action                     = $_REQUEST['act'];
$error                      = '';
$data                       = '';


// Incomming Call Dialog Strings
$hidden_id      = $_REQUEST['id'];

$project_number = mysql_real_escape_string($_REQUEST['project_number']);
$project_queue  = mysql_real_escape_string($_REQUEST['project_queue']);



switch ($action) {
	case 'get_add_page':
		$page		= GetPage('',Getnumber($hidden_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$page		= GetPage(Getnumber($hidden_id));
		$data		= array('page'	=> $page);

		break;
	case 'disable':
		$hidden_id        = $_REQUEST['id'];
		mysql_query("	UPDATE  `project_number`
								SET
								`actived` = 0
						WHERE `id`='$hidden_id'
		");
	
		break;
	case 'save-number':
		$hidden_id		   = $_REQUEST['number_hidden_id'];
		$hidden_project_id = $_REQUEST['hidden_project_id'];
		 
		if($hidden_id==''){
			Addproject_number($hidden_project_id, $project_number, $project_queue);
		}else{
			Saveproject_number($hidden_id,$project_number, $project_queue);
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

function Addproject_number($hidden_project_id, $project_number, $project_queue){

	$user = $_SESSION['USERID'];

	mysql_query("INSERT INTO `project_number` 
					(`user_id`,`project_id`, `number`, `queue_id`, `actived`) 
				VALUES 
					('$user', '$hidden_project_id', '$project_number', '$project_queue', '1')");

}

function Saveproject_number($hidden_id,$project_number, $project_queue){

	$user = $_SESSION['USERID'];

	mysql_query("UPDATE `project_number` 
					SET `user_id`  ='$user',
						`number`   ='$project_number', 
						`queue_id` ='$project_queue'
				WHERE `id`= '$hidden_id'");

}

function Get_queue($count){
	$data = '';
	$req = mysql_query("SELECT id, `name`
						FROM `queue`
						WHERE actived=1");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){

		if($res['id'] == $count){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		}else{
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}
	return $data;
}

function Getnumber($hidden_id)
{
	$res = mysql_fetch_assoc(mysql_query("SELECT id,
												 number,
												 queue_id
										FROM project_number
										WHERE id = $hidden_id"));
	return $res;
}

function GetPage($res,$increment){
	
	$data  .= '
	
	<div id="dialog-form">
	    <fieldset style="width: 320px;  float: left;">
	       <legend>ძირითადი ინფორმაცია</legend>
			<table class="dialog-form-table">
	           <tr>
	               <td colspan="2"><label for="incomming_cat_1_1_1">ნომერი</label></td>
    	       </tr>
	           <tr>
	               <td colspan="2"><input type="text" id="project_number" style="resize: vertical;width: 300px; margin-top: 10px;" value="'.$res[number].'"></td>
    	       </tr>
				<tr>
	               <td colspan="2"><label style="margin-top: 30px;" for="incomming_cat_1_1_1">რიგი</label></td>
    	       </tr>
	           <tr>
	               <td colspan="2"><select type="text" id="project_queue" style="resize: vertical;width: 300px; margin-top: 10px;">'.Get_queue($res[queue_id]).'</select></td>
    	       </tr>
			</table>
		 </fieldset>
	  </div>
	<input type="hidden" value="'.$res[id].'" id="number_hidden_id">';
	
	

	return $data;
}

?>