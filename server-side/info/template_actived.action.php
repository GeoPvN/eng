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

	mysql_query("INSERT INTO `outgoing_campaign`
        	    (`user_id`, `create_date`, `project_id`, `scenario_id`)
        	    VALUES
        	    ('$user', '$c_date', '$last_id', '$scenario_id');");
	$camping_id = mysql_fetch_array(mysql_query("SELECT id FROM outgoing_campaign ORDER BY id DESC LIMIT 1"));
	
	$res = mysql_query("  SELECT id FROM phone_base_detail
                          WHERE (id % 1) = floor(rand() * 1)
                          ORDER BY rand()
                          LIMIT $_REQUEST[actived_number]");
	
	while ($req = mysql_fetch_array($res)){
	    $base_id .= "('$user', '$camping_id[0]', '$req[0]'),";
	}
	$base_id_last = substr($base_id, 0, -1);
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

function GetPage(){

	$data  .= '
	
	<div id="dialog-form">
	    <fieldset style="width: 400px;">
	       <legend>ძირითადი ინფორმაცია</legend>
		   <label for="actived_number">რაოდენობა</label>
	       <input type="number" id="actived_number" min="1">
	       <label for="actived_number" style="margin:5px 0">სცენარი</label>
	       <select id="scenario_id">'.GetScenario().'</select>
		</fieldset>	    
	</div>';

	return $data;
}

?>