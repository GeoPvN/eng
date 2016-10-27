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
		 
		$rResult = mysql_query("SELECT 	author.id,
		                                author.id,
										service_center.`name`,
                            		    author.user,
                            		    author.password,
                            		    author.link
							    FROM 	author
		                        LEFT JOIN    service_center ON author.service_center_id = service_center.id
							    WHERE 	author.actived=1");

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
                        				IF(`logs`.`event` = 1,'დამატება',IF(`logs`.actived = 0,'წაშლა','განახლება')) AS `act`,
                        				`user_info`.`name`,
										CASE 
												WHEN `logs`.`collumn` = 'user' then 'იუზერი'
                                    	        WHEN `logs`.`collumn` = 'password' then 'პაროლი'
                                    	        WHEN `logs`.`collumn` = 'link' then 'ლინკი'
                                    	        WHEN `logs`.`collumn` = 'service_center' then 'მომსახურების ცენტრი'
										END AS `colum`,
                        				`logs`.`old_value`,
                        				`logs`.`new_value`
                                FROM    `logs`
                                JOIN    `users` ON `logs`.user_id = users.id
                                JOIN    `user_info` ON users.id = user_info.user_id
                                WHERE   `logs`.`table` = 'author'");
	
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
		$department_id 		  = $_REQUEST['id'];
		$user                 = $_REQUEST['user'];
		$password             = $_REQUEST['password'];
		$link                 = $_REQUEST['link'];
		$service_center_id    = $_REQUEST['sc_id'];

		if ($department_id == '') {
			Adddepartment( $department_id, $user, $password, $link, $service_center_id);
		}else {
			Savedepartment($department_id, $user, $password, $link, $service_center_id);
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

function Adddepartment($department_id, $user, $password, $link, $service_center_id)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 `author`
								(`service_center_id`, `user`, `password`, `link`, `user_id`)
					VALUES 		('$service_center_id', '$user', '$password', '$link', '$user_id')");
}

function Savedepartment($department_id, $user, $password, $link, $service_center_id)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `author`
					SET     `service_center_id` = '$service_center_id',
							`user` ='$user',
                    	    `password` ='$password',
                    	    `link` ='$link'
					WHERE	`id` = $department_id");
}

function getService($id)
{
    $data = '';
    $req = mysql_query("SELECT 	`id`,
                				`name`
                        FROM 	`service_center`
                        WHERE 	`actived` = 1 AND parent_id = 0");


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

function Disabledepartment($department_id)
{
	mysql_query("	UPDATE `author`
					SET    `actived` = 0
					WHERE  `id` = $department_id");
}

function CheckdepartmentExist($user)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `author`
											WHERE  `user` = '$user' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Getdepartment($department_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`service_center_id`,
                        							`user`,
                                            	    `password`,
                                            	    `link`
											FROM    `author`
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
					<td style="width: 170px;"><label for="user">მომსახურების ცენტრი</label></td>
					<td>
						<select id="service_center_id">'.getService($res['service_center_id']).'</select>
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="user">იუზერი</label></td>
					<td>
						<input type="text" id="user" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['user'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="password">პაროლი</label></td>
					<td>
						<input type="text" id="password" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['password'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="link">ლინკი</label></td>
					<td>
						<input type="text" id="link" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['link'] . '" />
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
