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
		 
		$rResult = mysql_query("SELECT 	sms.id,
		                                sms.id,
										sms.`name`,
		                                sms.message
							    FROM 	sms
							    WHERE 	sms.actived=1");

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
	case 'get_list_log' :
	    $count	= $_REQUEST['count'];
	    $hidden	= $_REQUEST['hidden'];
	    	
	    $rResult = mysql_query("SELECT 	`logs`.`id`,
                        				`logs`.`row_id`,
                        				`logs`.`date`,
                        				IF(`logs`.`event` = 1,'Add',IF(`logs`.actived = 0,'Delete','Update')) AS `act`,
                        				`user_info`.`name`,
										CASE 
												WHEN `logs`.`collumn` = 'name' then 'Name'
										END AS `colum`,
                        				`logs`.`old_value`,
                        				`logs`.`new_value`
                                FROM    `logs`
                                JOIN    `users` ON `logs`.user_id = users.id
                                JOIN    `user_info` ON users.id = user_info.user_id
                                WHERE   `logs`.`table` = 'sms'");
	
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
		$message            = $_REQUEST['message'];
	
		
		if($department_name != ''){
			if(!CheckdepartmentExist($department_name, $department_id)){
				if ($department_id == '') {
					Adddepartment( $department_id, $department_name, $message);
				}else {
					Savedepartment($department_id, $department_name, $message);
				}
								
			} else {
				$error = '"' . $department_name . '" It is already in the list!';
				
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

function Adddepartment($department_id, $department_name, $message)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 `sms`
								(`name`,`user_id`, `message`)
					VALUES 		('$department_name', '$user_id', '$message')");
}

function Savedepartment($department_id, $department_name, $message)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `sms`
					SET     `name` = '$department_name',
	                        `message`='$message',
							`user_id` ='$user_id'
					WHERE	`id` = $department_id");
}

function Disabledepartment($department_id)
{
	mysql_query("	UPDATE `sms`
					SET    `actived` = 0
					WHERE  `id` = $department_id");
}

function CheckdepartmentExist($department_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `sms`
											WHERE  `name` = '$department_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Getdepartment($department_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`name`,
	                                                `message`
											FROM    `sms`
											WHERE   `id` = $department_id" ));

	return $res;
}

function GetPage($res = '')
{
	$data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>Basic information</legend>

	    	<table class="dialog-form-table" style="width: 100%;">
				<tr>
					<td style="width: 80px;"><label for="CallType">Name</label></td>
					<td>
						<input style="width: 100%;" type="text" id="name" value="'.$res[name].'">
					</td>
				</tr>
				<tr>
					<td style="width: 80px;"><label for="CallType">Content</label></td>
					<td>	
						<textarea maxlength="150" style="width:100%; resize: vertical;height: 165px;" id="content" name="call_content" cols="300" rows="10">'.$res[message].'</textarea>
					</td>
			   </tr>	
				<tr>
					<td style="width: 80px;"></td>
					<td>
						<input style="width: 50px;" type="text" id="simbol_caunt" value="0/150">
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
