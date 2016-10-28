<?php
// MySQL Connect Link
require_once('../../includes/classes/core.php');

// Main Strings
$action                     = $_REQUEST['act'];
$error                      = '';
$data                       = '';

// Incomming Call Dialog Strings
$scenario_id                = $_REQUEST['scenario_id'];

switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);
		
		break;
	case 'disable':
		$hidden_id        = $_REQUEST['id'];
		mysql_query("	UPDATE  `project` SET
                		        `actived` = 0
                		WHERE   `id`='$hidden_id'");
	
		break;
	case 'save-import-actived':
		$hidden_project_id = $_REQUEST['hidden_project_id'];
    	$project_hidden_id = $_REQUEST['project_hidden_id'];
    	if($hidden_project_id == ''){
    	    $last_id = $project_hidden_id;
    	}else {
    	    $last_id = $hidden_project_id;
    	}

    	AddImport($last_id, $scenario_id);

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

function AddImport($last_id, $scenario_id){
    
	$user   = $_SESSION['USERID'];
	$c_date	= date('Y-m-d H:i:s');
	if($_REQUEST['note'] == '' || $_REQUEST['note'] == 0){
	    $note = '';
	}else {
	    $note = "AND note = '$_REQUEST[note]'";
	}

	mysql_query("INSERT INTO `outgoing_campaign`
        	    (`user_id`, `create_date`, `project_id`, `scenario_id`)
        	    VALUES
        	    ('$user', '$c_date', '$last_id', '$scenario_id');");
	$camping_id = mysql_fetch_array(mysql_query("SELECT id FROM outgoing_campaign ORDER BY id DESC LIMIT 1"));
	
	$res = mysql_query("  SELECT id FROM phone_base_detail
                          WHERE status = 1  $note
                          LIMIT $_REQUEST[actived_number]");
	$upId = '';
	while ($req = mysql_fetch_array($res)){
	    $base_id .= "('$user', '$camping_id[0]', '$req[0]'),";
	    $upId .= $req[0].',';
	}
	$upId = substr($upId, 0, -1);
	$base_id_last = substr($base_id, 0, -1);
	mysql_query("UPDATE `phone_base_detail` SET `status`='2' WHERE `id` IN($upId);");
	mysql_query("INSERT INTO `outgoing_campaign_detail`
                    ( `user_id`, `outgoing_campaign_id`, `phone_base_detail_id`)
                    VALUES
                    $base_id_last");
	
}

function GetScenario($id){
    $data = '';
    $req = mysql_query("SELECT 	`id`,
                				`name`
                        FROM `scenario`
                        WHERE actived = 1");

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){

        if($res['id'] == $id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        }else{
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }
    return $data;
}

function GetNote(){
    $data = '';
    $req = mysql_query("SELECT note
                        FROM phone_base_detail
                        where actived = 1 AND `status` = 1
                        GROUP BY note");

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        $data .= '<option value="' . $res['note'] . '">' . $res['note'] . '</option>';
    }
    if(mysql_num_rows($req) == 0){
        $data .= '<option value="ყველა კატეგოერია დაფორმირებულია">ყველა კატეგოერია დაფორმირებულია</option>';
    }
    return $data;
}

function GetPage(){

	$data  .= '
	
	<div id="dialog-form">
	    <fieldset style="width: 90%;">
	       <legend>ძირითადი ინფორმაცია</legend>
		   <label for="actived_number">რაოდენობა</label>
	       <input type="number" id="actived_number" min="1" value="1">
	       <label for="note">საქმიანობის სფერო</label>
	       <select id="note_actived" style="width:173px;">'.GetNote().'</select>
	       <label for="actived_number" style="margin:5px 0;width:173px;">სცენარი</label>
	       <select id="scenario_id">'.GetScenario().'</select>
		</fieldset>	    
	</div>';

	return $data;
}

?>