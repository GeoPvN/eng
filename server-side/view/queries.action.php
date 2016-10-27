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
		 
		$rResult = mysql_query("SELECT 	queries.id,
		                                queries.id,
										queries.`quest`,
		                                queries.`answer`
							    FROM 	queries
							    WHERE 	queries.actived=1");

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
												WHEN `logs`.`collumn` = 'name' then 'დასახელება'
										END AS `colum`,
                        				`logs`.`old_value`,
                        				`logs`.`new_value`
                                FROM    `logs`
                                JOIN    `users` ON `logs`.user_id = users.id
                                JOIN    `user_info` ON users.id = user_info.user_id
                                WHERE   `logs`.`table` = 'queries'");
	
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
		$department_id 	  = $_REQUEST['id'];
		$quest            = mysql_real_escape_string($_REQUEST['quest']);
		$answer           = mysql_real_escape_string($_REQUEST['answer']);
	
		
		if($quest != ''){
			
				if ($department_id == '') {
					Adddepartment( $department_id, $quest, $answer);
				}else {
					Savedepartment($department_id, $quest, $answer);
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

function Adddepartment($department_id, $quest, $answer)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 `queries`
								(`quest`, `answer`, `user_id`)
					VALUES 		('$quest', '$answer', '$user_id')");
}

function Savedepartment($department_id, $quest, $answer)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `queries`
					SET     `quest` = '$quest',
	                        `answer` = '$answer'
					WHERE	`id` = $department_id");
}

function Disabledepartment($department_id)
{
	mysql_query("	UPDATE `queries`
					SET    `actived` = 0
					WHERE  `id` = $department_id");
}

function CheckdepartmentExist($department_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `queries`
											WHERE  `name` = '$department_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Getdepartment($department_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`quest`,
	                                                `answer`
											FROM    `queries`
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
					<td style="width: 70px;"><label for="quest">კითხვა</label></td>
					<td>
						<textarea type="text" id="quest" style="width:1170px; resize: vertical;">' . $res['quest'] . '</textarea>
					</td>
				</tr>
				<tr>
					<td style="width: 70px;"><label for="answer">პასუხი</label></td>
					<td style="width: 1270px;">
						<textarea type="text" id="answer" style="width:850px; resize: vertical;height: 380px;">' . $res['answer'] . '</textarea>
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
