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
		 
		$rResult = mysql_query("SELECT 	`work_activities`.`id`,
		                                `work_activities`.`name`,
		                                `work_activities_cat`.`name`,
		                                `work_pay`.`name`,
		                                `work_activities`.`comment`,
		                                `project`.`name`,
		                                `work_activities`.`timer`,
		                                CONCAT('<div style=\"height: 100%; weight: 100%; background: ',`work_activities`.`color`,'\"></div>') AS `color`
                                FROM    `work_activities`
		                        JOIN work_pay ON work_activities.pay = work_pay.id
		                        JOIN work_activities_cat ON work_activities.work_activities_cat_id = work_activities_cat.id
		                        JOIN project ON work_activities.project_id = project.id
                                WHERE   `work_activities`.`actived` = 1");

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
		$color      = $_REQUEST['color'];
		$type       = $_REQUEST['type'];
		$name       = $_REQUEST['name'];
		$project_id = $_REQUEST['project_id'];
		$pay        = $_REQUEST['pay'];
		$comment    = $_REQUEST['comment'];
		$timer      = $_REQUEST['timer'];

		if ($lang_id == '') {
			AddLang( $lang_id, $color, $type, $name, $project_id, $pay, $comment, $timer);
		}else {
			SaveLang($lang_id, $color, $type, $name, $project_id, $pay, $comment, $timer);
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

function AddLang($lang_id, $color, $type, $name, $project_id, $pay, $comment, $timer)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 `work_activities`
				 (`user_id`, `color`, `work_activities_cat_id`, `name`, `project_id`, `pay`, `timer`, `comment`)
				 VALUES
	             ('$user_id', '$color', '$type', '$name', '$project_id', '$pay', '$timer', '$comment')");
}

function SaveLang($lang_id, $color, $type, $name, $project_id, $pay, $comment, $timer)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `work_activities`
					SET     `color`      = '$color',
                    	    `work_activities_cat_id`       = '$type',
                    	    `name`       = '$name',
	                        `project_id` = '$project_id',
                    	    `pay`        = '$pay',
                    	    `comment`    = '$comment',
	                        `timer`      = '$timer',
							`user_id`    = '$user_id'
					WHERE	`id`         = $lang_id");
}

function DisableLang($lang_id)
{
	mysql_query("	UPDATE `work_activities`
					SET    `actived` = 0
					WHERE  `id` = $lang_id");
}

function GetLang($lang_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
	                                                `work_activities_cat_id`,
	                                                `name`,
                                                    `color`,
                                            	    `comment`,
                                            	    `pay`,
	                                                `timer`,
	                                                `project_id`
											FROM    `work_activities`
											WHERE   `id` = $lang_id" ));

	return $res;
}

function GetShitType($type){
    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `work_activities_cat`
                            WHERE   `actived` = 1");

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $type){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}

function GetPay($pay){
    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `work_pay`");

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $pay){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}

function GetProject($project_id){
    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `project`
                            WHERE   `actived` = 1");

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $project_id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
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
					<td style="width: 170px;"><label for="project_id">პროექტი</label></td>
					<td>
						<select id="project_id" style="width: 174px;">'.GetProject($res['project_id']).'</select>
					</td>
				</tr>
			    <tr>
					<td style="width: 170px;"><label for="name">სახელი</label></td>
					<td>
						<input type="text" id="name" value="' . $res['name'] . '" />
					</td>
				</tr>
			    <tr>
					<td style="width: 170px;"><label for="pay">ანაზღაურებადი/არა ანაზღაურებადი</label></td>
					<td>
						<select id="pay" style="width: 174px;">'.GetPay($res['pay']).'</select>
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="type">კატეგორია</label></td>
					<td>
						<select id="type" style="width: 174px;">'.GetShitType($res['work_activities_cat_id']).'</select>
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="color">ფერი</label></td>
					<td>
						<input type="color" id="color" value="' . $res['color'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="color">ხანგრძლივობა</label></td>
					<td>
						<input type="text" id="timer" value="' . $res['timer'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="comment">კომენტარი</label></td>
					<td>
						<textarea  id="comment" style="resize: vertical;">' . $res['comment'] . '</textarea>
					</td>
				</tr>
			</table>
        </fieldset>
    </div>
    ';
	return $data;
}

?>