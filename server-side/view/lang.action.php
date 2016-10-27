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
		 
		$rResult = mysql_query("SELECT 	`id`,
                        				`name`
                                FROM    `spoken_lang`
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
	case 'save_lang':
		$lang_id 	= $_REQUEST['id'];
		$lang_name  = $_REQUEST['name'];
		
		if($lang_name != ''){
			if(!CheckLangExist($lang_name, $lang_id)){
				if ($lang_id == '') {
					AddLang( $lang_id, $lang_name);
				}else {
					SaveLang($lang_id, $lang_name);
				}
								
			} else {
				$error = '"' . $lang_name . '" უკვე არის სიაში!';
				
			}
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

function AddLang($lang_id, $lang_name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 `spoken_lang`
								(`name`,`user_id`)
					VALUES 		('$lang_name', '$user_id')");
}

function SaveLang($lang_id, $lang_name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `spoken_lang`
					SET     `name`    = '$lang_name',
							`user_id` = '$user_id'
					WHERE	`id` = $lang_id");
}

function DisableLang($lang_id)
{
	mysql_query("	UPDATE `spoken_lang`
					SET    `actived` = 0
					WHERE  `id` = $lang_id");
}

function CheckLangExist($lang_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `spoken_lang`
											WHERE  `name` = '$lang_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function GetLang($lang_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`name`
											FROM    `spoken_lang`
											WHERE   `id` = $lang_id" ));

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
					<td style="width: 170px;"><label for="CallType">სასაუბრო ენა</label></td>
					<td>
						<input type="text" id="name" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['name'] . '" />
					</td>
				</tr>
			</table>
			<!-- ID -->
			<input type="hidden" id="lang_id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

?>
