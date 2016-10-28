<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../../includes/classes/core.php');
$action 	            = $_REQUEST['act'];
$error		            = '';
$data		            = '';

//action
$action_id			    = $_REQUEST['id'];
$action_name		    = $_REQUEST['action_name'];
$start_date			    = $_REQUEST['start_date'];
$end_date			    = $_REQUEST['end_date'];
$action_content	        = $_REQUEST['action_content'];

//task
$task_type_id			= $_REQUEST['task_type_id'];
$priority_id			= $_REQUEST['persons_id'];
$comment 	        	= $_REQUEST['comment'];
$task_department_id 	= $_REQUEST['task_department_id'];
$hidden_inc				= $_REQUEST['hidden_inc'];
$edit_id				= $_REQUEST['edit_id'];
$delete_id				= $_REQUEST['delete_id'];

// file
$rand_file				= $_REQUEST['rand_file'];
$file					= $_REQUEST['file_name'];

switch ($action) {
	case 'get_add_page':
		$number		= $_REQUEST['number'];
		$page		= GetPage($res='', $number);
		$data		= array('page'	=> $page);

		break;
	case 'disable':
		mysql_query("UPDATE `action`
					 SET    `actived` = 0
					 WHERE  `id` = '$action_id'");
		break;
	case 'get_edit_page':
		$page		= GetPage(Getaction($action_id));

		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count  = $_REQUEST['count'];
		$hidden = $_REQUEST['hidden'];
		$status = $_REQUEST['status'];
		
		if($status == 1){
		    $chStatus = " AND action.end_date >= NOW()";
		}else{
		    $chStatus = "";
		}
	  	$rResult = mysql_query("	SELECT action.id,
										   action.id,
										   action.start_date,
										   action.end_date,
	  	                                   action.`name`,
										   action.content,
										   users.username
									FROM   action
									JOIN   users ON action.user_id=users.id
									WHERE  action.actived=1 $chStatus");
	  
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
	case 'save_action':
		if($action_id == ''){
			Addaction(  $action_name,  $start_date, $end_date, $action_content, $file, $rand_file, $edit_id);
		}else {
			saveaction($action_id,  $action_name,  $start_date, $end_date, $action_content);
		}
		break;
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Request Functions
* ******************************
*/

function Addaction(  $action_name,  $start_date, $end_date, $action_content, $file, $rand_file, $edit_id){
	
	$user		= $_SESSION['USERID'];	
	mysql_query("INSERT INTO `action` 
				 (`user_id`, `name`, `start_date`, `end_date`, `content`, `actived`) 
				 VALUES
				 ('$user', '$action_name', '$start_date', '$end_date', '$action_content', '1');");
	
}
				
function saveaction($action_id,  $action_name,  $start_date, $end_date, $action_content)
{
	
	$user		= $_SESSION['USERID'];
	mysql_query("UPDATE `action` SET 
						`user_id`     = '$user',
						`name`        = '$action_name',
						`start_date`  = '$start_date', 
						`end_date`    = '$end_date', 
						`content`     = '$action_content', 
						`actived`     = '1' 
				WHERE 	`id`          = '$action_id'");
	

}       

function Getaction($action_id)
{
$res = mysql_fetch_assoc(mysql_query("	SELECT 	action.id,
												action.`name` AS action_name,
												action.start_date AS start_date,
												action.end_date AS end_date,
												action.content AS action_content
										FROM 	action
										WHERE 	action.id = '$action_id'"));
	
	return $res;
}



function GetPage($res='', $number)
{

	$data  .= '
	<div id="dialog-form">
			<div style="float: left; width: 580px;">	
				<fieldset >
			    	<legend>Info</legend>
					<fieldset float:left;">
				    	<table width="100%" class="dialog-form-table">
							<tr>
								<td>Name</td>
								<td style="width:20px;></td>
								
								<td colspan "5">
									<input  type="text" id="action_name" class="idle" onblur="this.className=\'idle\'"  value="' . $res['action_name']. '"  />
								</td>
							</tr>
							<tr>
								<td style="width: 150px;"><label for="d_number">Period</label></td>
								<td>
									<input type="text" id="start_date" class="idle" onblur="this.className=\'idle\'" value="' . $res['start_date']. '" />
								</td>
								<td style="width: 150px;"><label for="d_number">-From</label></td>
								<td>
									<input type="text" id="end_date" class="idle" onblur="this.className=\'idle\'"  value="' . $res['end_date']. '"  />
								</td>
								<td style="width: 150px;"><label for="d_number">-Up to</label></td>
							</tr>
						</table>
									
					</fieldset>
					<fieldset style="float: left; width: 536px;">
						<legend>Description</legend>
				    		<table width="100%" class="dialog-form-table">
							<tr>
								<td colspan="5">
									<textarea  style="width: 530px; height: 500px; resize: none;" id="action_content" class="idle" name="content" cols="100" rows="2">' . $res['action_content'] . '</textarea>
								</td>
							</tr>		
							</table>
					</fieldset>	
			</div>
			<div style="float: right;  width: 360px;">
				</fieldset>
										
				<fieldset style="width: 440px; float: right;">
						<legend>Attachments</legend>				
				 
		 '.show_file($res).'
 				
	  			</fieldset>		
			</div>
				<input type="hidden" id="actionn_id" value="'.$res['id'].'"/>
				<input type="hidden" id="act_id" value="'.(($res['id']!='')?$res['id']:increment('action')).'"/>
    </div>';

	return $data;
}

function increment($table){
    $result   		= mysql_query("SHOW TABLE STATUS LIKE '$table'");
    $row   			= mysql_fetch_array($result);
    $increment   	= $row['Auto_increment'];
    $next_increment = $increment+1;
    mysql_query("ALTER TABLE '$table' AUTO_INCREMENT='$next_increment'");
    
    return $increment;
}

function show_file($res){
    $file_incomming = mysql_query("  SELECT `name`,
                                            `rand_name`,
                                            `file_date`,
                                            `id`
                                     FROM   `file`
                                     WHERE  `action_id` = $res[id] AND `actived` = 1");
    while ($file_res_incomming = mysql_fetch_assoc($file_incomming)) {
        $str_file_incomming .= '<div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 180px;float:left;">'.$file_res_incomming[file_date].'</div>
                            	<div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 189px;float:left;">'.$file_res_incomming[name].'</div>
                            	<div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;cursor: pointer;width: 160px;float:left;" onclick="download_file(\''.$file_res_incomming[rand_name].'\')">Download</div>
                            	<div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;cursor: pointer;width: 20px;float:left;" onclick="delete_file(\''.$file_res_incomming[id].'\')">-</div>';
    }
    $data = '<div style="margin-top: 15px;">
                    <div style="width: 100%;  border:1px solid #CCC;float: left;">    	            
    	                   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 180px;float:left;">Date</div>
                    	   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 189px;float:left;">Name</div>
                    	   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 160px;float:left;">Download</div>
                           <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 20px;float:left;">-</div>
    	                   <div style="text-align: center;vertical-align: middle;float: left;width: 595px;"><button id="upload_file" style="cursor: pointer;background: none;border: none;width: 100%;height: 25px;padding: 0;margin: 0;">Choose</button><input style="display:none;" type="file" name="file_name" id="file_name"></div>
                           <div id="paste_files">
                           '.$str_file_incomming.'
                           </div>
            	    </div>
	            </div>';
    return $data;
}
?>