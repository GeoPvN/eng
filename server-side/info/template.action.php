<?php
// MySQL Connect Link
require_once('../../includes/classes/core.php');

// Main Strings
$action                     = $_REQUEST['act'];
$error                      = '';
$data                       = '';


// Incomming Call Dialog Strings
$id                 = $_REQUEST['id'];
$note               = mysql_real_escape_string($_REQUEST['note']);
$import_fname		= mysql_real_escape_string($_REQUEST['import_fname']);
$import_lname		= mysql_real_escape_string($_REQUEST['import_lname']);
$import_pid		    = mysql_real_escape_string($_REQUEST['import_pid']);
$import_date		= mysql_real_escape_string($_REQUEST['import_date']);
$import_age		    = mysql_real_escape_string($_REQUEST['import_age']);
$import_sex	        = mysql_real_escape_string($_REQUEST['import_sex']);
$import_phone1	    = mysql_real_escape_string($_REQUEST['import_phone1']);
$import_phone2		= mysql_real_escape_string($_REQUEST['import_phone2']);
$import_mail1		= mysql_real_escape_string($_REQUEST['import_mail1']);
$import_mail2		= mysql_real_escape_string($_REQUEST['import_mail2']);
$import_address1	= mysql_real_escape_string($_REQUEST['import_address1']);
$import_address2	= mysql_real_escape_string($_REQUEST['import_address1']);
$id_code            = mysql_real_escape_string($_REQUEST['import_id_code']);
$client_name        = mysql_real_escape_string($_REQUEST['import_client_name']);
$activities         = mysql_real_escape_string($_REQUEST['import_activities']);
$import_note		= mysql_real_escape_string($_REQUEST['import_note']);
$import_info1		= mysql_real_escape_string($_REQUEST['import_info1']);
$import_info2		= mysql_real_escape_string($_REQUEST['import_info2']);
$import_info3		= mysql_real_escape_string($_REQUEST['import_info3']);

switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$page		= GetPage(template($id));
		$data		= array('page'	=> $page);

		break;
	case 'disable':
		$hidden_id        = $_REQUEST['id'];
		mysql_query("	UPDATE `phone_base_detail` SET `actived`='0' WHERE `id`='$hidden_id'");
	
		break;
	case 'get_list_import':
	    $count = 		$_REQUEST['count'];
	    $hidden = 		$_REQUEST['hidden'];
	    $rResult = mysql_query("SELECT 	phone_base_detail.`id`,
                    				phone_base_detail.`firstname`,
                    				phone_base_detail.`lastname`,
                    				phone_base_detail.`pid`,
                    				phone_base_detail.`phone1`,
                    				phone_base_detail.`phone2`
                            FROM 	`phone_base`
                            LEFT JOIN phone_base_detail ON phone_base_detail.phone_base_id = phone_base.id AND phone_base_detail.`actived` = 1
                            WHERE   phone_base.`actived` = 1");
	     
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
                          <input type="checkbox" id="callapp_import_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                          <label style="margin-top: 2px;" for="callapp_import_checkbox_'.$aRow[$hidden].'"></label>
                      </div>';
	            }
	        }
	        $data['aaData'][] = $row;
	    }
	
	    break;
	case 'save-import':
		$hidden_project_id = $_REQUEST['hidden_project_id'];
    	$project_hidden_id = $_REQUEST['project_hidden_id'];
    	if($hidden_project_id == ''){
    	    $last_id = $project_hidden_id;
    	}else {
    	    $last_id = $hidden_project_id;
    	}
    	$id = $_REQUEST['import_id'];
    	
    	if($id == ''){
    		AddImport($last_id, $scenario_id, $import_fname, $import_lname, $import_pid, $import_date, $import_age, $import_sex, $import_phone1, $import_phone2, $import_mail1, $import_mail2, $import_address1, $import_address2, $id_code, $client_name, $activities, $import_note, $import_info1, $import_info2, $import_info3);
    	}else{
    		SaveImport($id, $last_id,$scenario_id, $import_fname, $import_lname, $import_pid, $import_date, $import_age, $import_sex, $import_phone1, $import_phone2, $import_mail1, $import_mail2, $import_address1, $import_address2, $id_code, $client_name, $activities, $import_note, $import_info1, $import_info2, $import_info3);
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

function AddImport($last_id, $scenario_id, $import_fname, $import_lname, $import_pid, $import_date, $import_age, $import_sex, $import_phone1, $import_phone2, $import_mail1, $import_mail2, $import_address1, $import_address2, $id_code, $client_name, $activities, $import_note, $import_info1, $import_info2, $import_info3){
	
	$user   = $_SESSION['USERID'];
	$c_date	= date('Y-m-d H:i:s');

	mysql_query("INSERT INTO `phone_base`
                    (`user_id`, `upload_date`, `note`)
                    VALUES
                    ( '$user', '$c_date', '')");
	
	$req = mysql_fetch_array(mysql_query("  SELECT id
                                                FROM `phone_base`
                                                WHERE actived = 1
                                                ORDER BY id DESC
                                                LIMIT 1"));
	
	mysql_query("INSERT INTO `phone_base_detail`
                        (`user_id`, `phone_base_id`, `firstname`, `lastname`, `pid`, `born_date`, `age`, `sex`, `phone1`, `phone2`, `mail1`, `mail2`, `address1`, `address2`, `id_code`, `client_name`, `activities`, `note`, `info1`, `info2`, `info3`)
                        VALUES
                        ('$user', '$req[0]', '$import_fname', '$import_lname', '$import_pid', '$import_date', '$import_age', '$import_sex', '$import_phone1', '$import_phone2', '$import_mail1', '$import_mail2', '$import_address1', '$import_address2', '$id_code', '$client_name', '$activities', '$import_note', '$import_info1', '$import_info2', '$import_info3')");

}

function SaveImport($id, $last_id,$scenario_id, $import_fname, $import_lname, $import_pid, $import_date, $import_age, $import_sex, $import_phone1, $import_phone2, $import_mail1, $import_mail2, $import_address1, $import_address2, $id_code, $client_name, $activities, $import_note, $import_info1, $import_info2, $import_info3){
	
	$user = $_SESSION['USERID'];
	
	mysql_query("UPDATE  `phone_base_detail`
	 				SET  `user_id`='$user', 
						 `firstname`='$import_fname', 
						 `lastname`='$import_lname', 
						 `pid`='$import_pid',
	                     `born_date`='$import_date', 
						 `age`='$import_age', 
						 `sex`='$import_sex',
	                     `phone1`='$import_phone1', 
						 `phone2`='$import_phone2', 
						 `mail1`='$import_mail1',
	                     `mail2`='$import_mail2',
	                     `address1`='$import_address1',
	                     `address2`='$import_address2',
                         `id_code`='$id_code',
                         `client_name`='$client_name',
                         `activities`='$activities',
						 `note`='$import_note', 
						 `info1`='$import_info1',
	                     `info2`='$import_info2', 
						 `info3`='$import_info3'
				WHERE `id`='$id'");

}


function template($hidden_id){
	
	$res = mysql_fetch_assoc(mysql_query("SELECT    id,
                                					firstname,
                                					lastname,
                                					pid,
                                					born_date,
                                					age,
                                					sex,
                                					phone1,
                                					phone2,
                                					mail1,
                                					mail2,
                                					address1,
                                					address2,
                                					id_code,
                                					client_name,
                                					activities,
                                					note,
                                					info1,
                                					info2,
                                					info3
                                            FROM `phone_base_detail`
                                            WHERE id = $hidden_id"));
	return $res;
}

function GetPage($res){

    if($_REQUEST['cp']==1){
	$data  .= '
	
	<div id="dialog-form">
	    <fieldset style="width: 400px;">
	       <legend>ძირითადი ინფორმაცია</legend>
			<table class="dialog-form-table" style="width: 100%;">

	           <tr>
	               <td><label for="import_phone1">ტელეფონი 1</label></td>
	               <td><label for="import_phone2">ტელეფონი 2</label></td>
    	       </tr>
	           <tr>
	               <td><input id="import_phone1" style="width: 150px;" value="'.$res[phone1].'"></td>
	               <td><input id="import_phone2" style="width: 150px;" value="'.$res[phone2].'"></td>
    	       </tr>
	           <tr>
	               <td><label for="import_mail">ელ-ფოსტა 1</label></td>
	               <td><label for="import_addres">მისამართი 1</label></td>
    	       </tr>
	           <tr>
	               <td><input id="import_mail1" style="width: 150px;" value="'.$res[mail1].'"></td>
	               <td><input id="import_address1" style="width: 150px;" value="'.$res[address1].'"></td>
    	       </tr>
	           <tr>
	               <td><label for="import_mail">ელ-ფოსტა 2</label></td>
	               <td><label for="import_addres">მისამართი 2</label></td>
    	       </tr>
	           <tr>
	               <td><input id="import_mail2" style="width: 150px;" value="'.$res[mail2].'"></td>
	               <td><input id="import_address2" style="width: 150px;" value="'.$res[address2].'"></td>
    	       </tr>
	           <tr>
	               <td><label for="import_id_code">საიდ. კოდი</label></td>
	               <td><label for="import_client_name">დასახელება</label></td>
    	       </tr>
	           <tr>
	               <td><input id="import_id_code" style="width: 150px;" value="'.$res[id_code].'"></td>
	               <td><input id="import_client_name" style="width: 150px;" value="'.$res[client_name].'"></td>
    	       </tr>
	           <tr>
                   <td><label for="import_info1">ვებ გვერდი</label></td>
	               <td><label for="import_note">საქმიანობის სფერო</label></td>
    	       </tr>
	           <tr>
                   <td><input id="import_info1" style="width: 150px;" value="'.$res[info1].'"></td>
	               <td><input id="import_note" style="width: 150px;" value="'.$res[note].'"></td>
    	       </tr>
	       </table>
		 </fieldset>	    
	</div><input id="import_id" type="hidden" value="'.$res[id].'">
	</div>';
    }else{
        $data  .= '
    	<div id="dialog-form">
    	    <fieldset style="width: 400px;">
    	       <legend>ძირითადი ინფორმაცია</legend>
    			<table class="dialog-form-table" style="width: 100%;">
    	           <tr>
    	               <td><label for="import_fname">სახელი</label></td>
    	               <td><label for="import_lname">გვარი</label></td>
        	       </tr>
    	           <tr>
    	               <td><input id="import_fname" style="width: 150px;" value="'.$res[firstname].'"></td>
    	               <td><input id="import_lname" style="width: 150px;" value="'.$res[lastname].'"></td>
        	       </tr>
    	       	   <tr>
    	               <td><label for="import_pid">პირადი ნომერი</label></td>
    	               <td><label for="import_date">დაბადების თარიღი</label></td>
        	       </tr>
    	           <tr>
    	               <td><input id="import_pid" style="width: 150px;" value="'.$res[pid].'" maxlength="11"></td>
    	               <td><input id="import_date" style="width: 150px;" value="'.$res[born_date].'"></td>
        	       </tr>
    	           <tr>
    	               <td><label for="import_age">წლოვანება</label></td>
    	               <td><label for="import_sex">სქესი</label></td>
        	       </tr>
    	           <tr>
    	               <td><input id="import_age" style="width: 150px;" value="'.$res[age].'"></td>
    	               <td><input id="import_sex" style="width: 150px;" value="'.$res[sex].'"></td>
        	       </tr>
    	           <tr>
    	               <td><label for="import_phone1">ტელეფონი 1</label></td>
    	               <td><label for="import_phone2">ტელეფონი 2</label></td>
        	       </tr>
    	           <tr>
    	               <td><input id="import_phone1" style="width: 150px;" value="'.$res[phone1].'"></td>
    	               <td><input id="import_phone2" style="width: 150px;" value="'.$res[phone2].'"></td>
        	       </tr>
    	           <tr>
    	               <td><label for="import_mail">ელ-ფოსტა 1</label></td>
    	               <td><label for="import_addres">მისამართი 1</label></td>
        	       </tr>
    	           <tr>
    	               <td><input id="import_mail1" style="width: 150px;" value="'.$res[mail1].'"></td>
    	               <td><input id="import_address1" style="width: 150px;" value="'.$res[address1].'"></td>
        	       </tr>
    	           <tr>
    	               <td><label for="import_mail">ელ-ფოსტა 2</label></td>
    	               <td><label for="import_addres">მისამართი 2</label></td>
        	       </tr>
    	           <tr>
    	               <td><input id="import_mail2" style="width: 150px;" value="'.$res[mail2].'"></td>
    	               <td><input id="import_address2" style="width: 150px;" value="'.$res[address2].'"></td>
        	       </tr>
    	           <tr>
    	               <td><label for="import_note">შენიშვნა</label></td>
    	               <td><label for="import_info1">ინფო 1</label></td>
        	       </tr>
    	           <tr>
    	               <td><input id="import_note" style="width: 150px;" value="'.$res[note].'"></td>
    	               <td><input id="import_info1" style="width: 150px;" value="'.$res[info1].'"></td>
        	       </tr>
    	           <tr>
    	               <td><label for="import_info2">ინფო 2</label></td>
    	               <td><label for="import_info3">ინფო 3</label></td>
        	       </tr>
    	           <tr>
    	               <td><input id="import_info2" style="width: 150px;" value="'.$res[info2].'"></td>
    	               <td><input id="import_info3" style="width: 150px;" value="'.$res[info3].'"></td>
        	       </tr>
    	       </table>
    		 </fieldset>
    	</div><input id="import_id" type="hidden" value="'.$res[id].'">
    	</div>';
    }

	return $data;
}

?>