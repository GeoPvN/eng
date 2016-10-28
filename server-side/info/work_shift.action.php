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
		 
		$rResult = mysql_query("SELECT 	`work_shift`.`id`,
		                                `work_shift`.`name`,
                        				`work_shift`.`start_date`,
		                                `work_shift`.`end_date`,
		                                `work_shift`.`timeout`,
		                                `work_type`.`name`,
		                                `work_pay`.`name`,
		                                `work_shift`.`comment`,
		                                `project`.`name`,
		                                CONCAT('<div style=\"height: 100%; weight: 100%; background: ',`work_shift`.`color`,'\"></div>') AS `color`
                                FROM    `work_shift`
		                        JOIN work_pay ON work_shift.pay = work_pay.id
		                        JOIN work_type ON work_shift.type = work_type.id
		                        JOIN project ON work_shift.project_id = project.id
                                WHERE   `work_shift`.`actived` = 1");

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
		$start_date = $_REQUEST['start_date'];
		$end_date   = $_REQUEST['end_date'];
		$color      = $_REQUEST['color'];
		$type       = $_REQUEST['type'];
		$name       = $_REQUEST['name'];
		$project_id = $_REQUEST['project_id'];
		$pay        = $_REQUEST['pay'];
		$comment    = $_REQUEST['comment'];
		$timeout    = $_REQUEST['timeout'];
		$start_timeout=$_REQUEST['start_timeout'];
		$end_timeout=$_REQUEST['end_timeout'];

		if ($lang_id == '') {
			AddLang( $lang_id, $start_date, $end_date, $color, $type, $name, $project_id, $pay, $comment, $timeout, $start_timeout, $end_timeout);
		}else {
			SaveLang($lang_id, $start_date, $end_date, $color, $type, $name, $project_id, $pay, $comment, $timeout, $start_timeout, $end_timeout);
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

function AddLang($lang_id, $start_date, $end_date, $color, $type, $name, $project_id, $pay, $comment, $timeout, $start_timeout, $end_timeout)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 `work_shift`
								(`user_id`, `start_date`, `end_date`, `color`, `type`, `name`, `project_id`, `pay`, `comment`, `start_timeout`, end_timeout, `timeout`)
					VALUES 		('$user_id', '$start_date', '$end_date', '$color', '$type', '$name', '$project_id', '$pay', '$comment', '$start_timeout', '$end_timeout', '$timeout')");
}

function SaveLang($lang_id, $start_date, $end_date, $color, $type, $name, $project_id, $pay, $comment, $timeout, $start_timeout, $end_timeout)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `work_shift`
					SET     `start_date` = '$start_date',
	                        `end_date`   = '$end_date',
                    	    `color`      = '$color',
                    	    `type`       = '$type',
                    	    `name`       = '$name',
	                        `project_id` = '$project_id',
                    	    `pay`        = '$pay',
                    	    `comment`    = '$comment',
	                        `timeout`    = '$timeout',
	                        `start_timeout`='$start_timeout',
                            `end_timeout`='$end_timeout',
							`user_id`    = '$user_id'
					WHERE	`id`         = $lang_id");
}

function DisableLang($lang_id)
{
	mysql_query("	UPDATE `work_shift`
					SET    `actived` = 0
					WHERE  `id` = $lang_id");
}

function GetLang($lang_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`start_date`,
	                                                `end_date`,
	                                                `type`,
	                                                `name`,
                                                    `color`,
                                            	    `comment`,
                                            	    `pay`,
	                                                `timeout`,
                                            	    `start_timeout`,
                                            	    `end_timeout`,
	                                                `project_id`
											FROM    `work_shift`
											WHERE   `id` = $lang_id" ));

	return $res;
}

function GetShitType($type){
    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `work_type`");

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
	    	<legend>Basic Information</legend>
            <!-- ID -->
			<input type="hidden" id="lang_id" value="' . $res['id'] . '" />
	    	<table class="dialog-form-table">
			    <tr>
					<td style="width: 170px;"><label for="project_id">Project</label></td>
					<td>
						<select id="project_id" style="width: 174px;">'.GetProject($res['project_id']).'</select>
					</td>
				</tr>
			    <tr>
					<td style="width: 170px;"><label for="name">Name</label></td>
					<td>
						<input type="text" id="name" value="' . $res['name'] . '" />
					</td>
				</tr>
			    <tr>
					<td style="width: 170px;"><label for="pay">Paid / not paid</label></td>
					<td>
						<select id="pay" style="width: 174px;">'.GetPay($res['pay']).'</select>
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="type">Type of work</label></td>
					<td>
						<select id="type" style="width: 174px;">'.GetShitType($res['type']).'</select>
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="color">Color</label></td>
					<td>
						<input type="color" id="color" value="' . $res['color'] . '" />
					</td>
				</tr>
                <tr>
					<td style="width: 170px;"><label for="start_date">Start</label></td>
					<td>
						<input type="text" id="start_date" value="' . $res['start_date'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="end_date">End</label></td>
					<td>
						<input type="text" id="end_date" value="' . $res['end_date'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="timeout">The duration of the break</label></td>
					<td>
						<input type="text" id="timeout" value="' . $res['timeout'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="">Break Period</label></td>
					<td>
						<input style="width: 55px;float:left;" type="text" id="start_timeout" value="' . $res['start_timeout'] . '" />
					
						<input style="width: 55px;float:left;margin-left: 52px;" type="text" id="end_timeout" value="' . $res['end_timeout'] . '" />
					</td>
				</tr>	    
				<tr>
					<td style="width: 170px;"><label for="comment">Comments</label></td>
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