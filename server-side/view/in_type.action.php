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
		   $info_sorce_id		= $_REQUEST['id'];
	       $page		= GetPage(Getinfo_sorce($info_sorce_id));
           $data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
		 
		$rResult = mysql_query("SELECT 	`id`,
                                        `id`,
                        				`name`
                                FROM    `in_type`
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
	case 'get_list_log' :
	    $count	= $_REQUEST['count'];
	    $hidden	= $_REQUEST['hidden'];
	
	    $rResult = mysql_query("SELECT 	`logs`.`id`,
                        				`logs`.`row_id`,
                        				`logs`.`date`,
	                                    `user_info`.`name`,
                        				IF(`logs`.`event` = 1,'Add',IF(`logs`.actived = 0,'Delete','Update')) AS `act`,
										CASE 
												WHEN `logs`.`collumn` = 'name' then 'დასახელება'
										END AS `colum`,
                        				`logs`.`old_value`,
                        				`logs`.`new_value`
                                FROM    `logs`
                                JOIN    `users` ON `logs`.user_id = users.id
                                JOIN    `user_info` ON users.id = user_info.user_id
                                WHERE   `logs`.`table` = 'in_type'");
	
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
	case 'save_info_sorce':
		$info_sorce_id 	= $_REQUEST['id'];
		$info_sorce_name  = $_REQUEST['name'];
		
		if($info_sorce_name != ''){
			if(!Checkinfo_sorceExist($info_sorce_name, $info_sorce_id)){
				if ($info_sorce_id == '') {
					Addinfo_sorce( $info_sorce_id, $info_sorce_name);
				}else {
					Saveinfo_sorce($info_sorce_id, $info_sorce_name);
				}
								
			} else {
				$error = '"' . $info_sorce_name . '" It is already in the list!';
				
			}
		}
		
		break;
	case 'disable':
		$info_sorce_id	= $_REQUEST['id'];
		Disableinfo_sorce($info_sorce_id);

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

function Addinfo_sorce($info_sorce_id, $info_sorce_name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 `in_type`
								(`name`,`user_id`)
					VALUES 		('$info_sorce_name', '$user_id')");
}

function Saveinfo_sorce($info_sorce_id, $info_sorce_name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `in_type`
					SET     `name`    = '$info_sorce_name',
							`user_id` = '$user_id'
					WHERE	`id` = $info_sorce_id");
}

function Disableinfo_sorce($info_sorce_id)
{
	mysql_query("	UPDATE `in_type`
					SET    `actived` = 0
					WHERE  `id` = $info_sorce_id");
}

function Checkinfo_sorceExist($info_sorce_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `in_type`
											WHERE  `name` = '$info_sorce_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Getinfo_sorce($info_sorce_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`name`
											FROM    `in_type`
											WHERE   `id` = $info_sorce_id" ));

	return $res;
}

function GetPage($res = '')
{
	$data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>Basic information</legend>

	    	<table class="dialog-form-table">
                <tr>
					<td style="width: 170px;"><label for="CallType">Type</label></td>
					<td>
						<input type="text" id="name" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['name'] . '" />
					</td>
				</tr>
			</table>
			<!-- ID -->
			<input type="hidden" id="info_sorce_id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

?>
