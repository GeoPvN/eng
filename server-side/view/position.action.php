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
		 
		$rResult = mysql_query("SELECT 	position.id,
		                                position.id,
										position.`person_position`
							    FROM 	position
							    WHERE 	position.actived=1");

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
	                                    `user_info`.`name`,
                        				IF(`logs`.`event` = 1,'დამატება',IF(`logs`.actived = 0,'წაშლა','განახლება')) AS `act`,
										CASE 
												WHEN `logs`.`collumn` = 'person_position' then 'დასახელება'
										END AS `colum`,
                        				`logs`.`old_value`,
                        				`logs`.`new_value`
                                FROM    `logs`
                                JOIN    `users` ON `logs`.user_id = users.id
                                JOIN    `user_info` ON users.id = user_info.user_id
                                WHERE   `logs`.`table` = 'position'");
	
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
	mysql_query("INSERT INTO 	 `position`
								(`person_position`,`user_id`)
					VALUES 		('$department_name', '$user_id')");
}

function Savedepartment($department_id, $department_name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE  `position`
					SET     `person_position` = '$department_name',
							`user_id` ='$user_id'
					WHERE	`id` = $department_id");
}

function Disabledepartment($department_id)
{
	mysql_query("	UPDATE `position`
					SET    `actived` = 0
					WHERE  `id` = $department_id");
}

function CheckdepartmentExist($department_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `position`
											WHERE  `person_position` = '$department_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Getdepartment($department_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`person_position`
											FROM    `position`
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
					<td style="width: 170px;"><label for="CallType">თანამდებობა</label></td>
					<td>
						<input type="text" id="name" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['person_position'] . '" />
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
