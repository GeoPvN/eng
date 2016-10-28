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
		   $lang_id		= $_REQUEST['id'];
	       $page		= GetPage(GetLang($lang_id));
           $data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
		 
		$rResult = mysql_query("SELECT 	`work_activities_cat`.`id`,
		                                `work_activities_cat`.`id`,
		                                `work_activities_cat`.`name`
		                        FROM    `work_activities_cat`
                                WHERE   `work_activities_cat`.`actived` = 1");

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
	case 'save_lang':
		$lang_id 	= $_REQUEST['id'];
		$name       = $_REQUEST['name'];

		if ($lang_id == '') {
		    if(!CheckActivitiesCatExist($name)){
			AddLang($name);
		    }else{
                $error = 'ეს "' . $name . '" კატეგორია უკვე არის სიაში!';
            }
		}else {
			SaveLang($lang_id, $name);
		}

		break;
	case 'disable':
		$lang_id	= $_REQUEST['id'];
		DisableLang($lang_id);

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

function AddLang($name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO `work_activities_cat`
				 (`user_id`, `name`)
				 VALUES
	             ('$user_id', '$name')");
}

function SaveLang($lang_id, $name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `work_activities_cat`
					SET     `name`       = '$name'
					WHERE	`id`         = $lang_id");
}

function DisableLang($lang_id)
{
	mysql_query("	UPDATE `work_activities_cat`
					SET    `actived` = 0
					WHERE  `id` = $lang_id");
}

function CheckActivitiesCatExist($name){
    $res = mysql_fetch_assoc(mysql_query("	SELECT id
                                            FROM   `work_activities_cat`
                                            WHERE  `name` = '$name' AND `actived` = 1"));
    if($res['id'] != ''){
        return true;
    }
    return false;
}

function GetLang($lang_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
	                                                `name`
											FROM    `work_activities_cat`
											WHERE   `id` = $lang_id" ));

	return $res;
}

function GetPage($res = '')
{
	$data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>
            <!-- ID -->
			<input type="hidden" id="lang_id" value="' . $res['id'] . '" />
	    	<table class="dialog-form-table">

			    <tr>
					<td style="width: 170px;"><label for="name">სახელი</label></td>
					<td>
						<input type="text" id="name" value="' . $res['name'] . '" />
					</td>
				</tr>
			</table>
        </fieldset>
    </div>
    ';
	return $data;
}



?>
