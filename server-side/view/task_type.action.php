<?php
require_once('../../includes/classes/core.php');
$action	= $_REQUEST['act'];
$error	= '';
$data	= '';
 
switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$departmetn_id		= $_REQUEST['id'];
	       $page		= GetPage(Getdepartment($departmetn_id));
           $data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
		 
		$rResult = mysql_query("SELECT 	`id`,
                        				`name`
                                FROM    `task_type`
                                WHERE   `actived` = 1");

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
	case 'save_department':
		$department_id 		= $_REQUEST['id'];
		$department_name    = $_REQUEST['name'];
		
		if($department_name != ''){
			if(!CheckdepartmentExist($department_name, $department_id)){
				if ($department_id == '') {
					Adddepartment( $department_id, $department_name);
				}else {
					Savedepartment($department_id, $department_name);
				}
								
			} else {
				$error = '"' . $department_name . '" უკვე არის სიაში!';
				
			}
		}
		
		break;
	case 'disable':
		$department_id	= $_REQUEST['id'];
		Disabledepartment($department_id);

		break;
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Category Functions
* ******************************
*/

function Adddepartment($department_id, $department_name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 `task_type`
								(`name`,`user_id`)
					VALUES 		('$department_name', '$user_id')");
}

function Savedepartment($department_id, $department_name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `task_type`
					SET     `name`    = '$department_name',
							`user_id` = '$user_id'
					WHERE	`id` = $department_id");
}

function Disabledepartment($department_id)
{
	mysql_query("	UPDATE `task_type`
					SET    `actived` = 0
					WHERE  `id` = $department_id");
}

function CheckdepartmentExist($department_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `task_type`
											WHERE  `name` = '$department_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Getdepartment($department_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`name`
											FROM    `task_type`
											WHERE   `id` = $department_id" ));

	return $res;
}

function GetPage($res = '')
{
	$data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>
	    	<table class="dialog-form-table">
                <tr>
					<td style="width: 170px;"><label for="CallType">სახელი</label></td>
					<td>
						<input type="text" id="name" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['name'] . '" />
					</td>
				</tr>
			</table>
			<!-- ID -->
			<input type="hidden" id="department_id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

?>
