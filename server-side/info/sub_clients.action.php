<?php
// MySQL Connect Link
require_once('../../includes/classes/core.php');

// Main Strings
$action                     = $_REQUEST['act'];
$error                      = '';
$data                       = '';


// Incomming Call Dialog Strings

$hidden_id        = $_REQUEST['id'];
$person_name      = mysql_real_escape_string($_REQUEST['person_name']);
$person_surname   = mysql_real_escape_string($_REQUEST['person_surname']);
$person_posityon  = mysql_real_escape_string($_REQUEST['person_posityon']);
$person_mobile    = mysql_real_escape_string($_REQUEST['person_mobile']);
$person_phone     = mysql_real_escape_string($_REQUEST['person_phone']);
$person_comment   = mysql_real_escape_string($_REQUEST['person_comment']);



switch ($action) {
	case 'get_add_page':
		$page		= GetPage('',increment(incomming_call));
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$page		= GetPage(Getincomming($hidden_id));
		$data		= array('page'	=> $page);

		break;
	case 'disable':
		$hidden_id        = $_REQUEST['id'];
		mysql_query("	UPDATE  `client_person` 
						SET 	
							`actived` = 0 
					WHERE `id`='$hidden_id'
				");
	
		break;
    case 'save-client_person':
    	$hidden_id        = $_REQUEST['hidden_id'];
    	$hidden_client_id = $_REQUEST['hidden_client_id'];
    	
		
    		
    	if($hidden_id==''){
    		Addperson($hidden_client_id, $person_name, $person_surname, $person_posityon, $person_mobile, $person_phone, $person_comment);
    	}else{
    		Saveperson($hidden_id, $person_name, $person_surname, $person_posityon, $person_mobile, $person_phone, $person_comment);
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

function Addperson($hidden_client_id, $person_name, $person_surname, $person_posityon, $person_mobile, $person_phone, $person_comment){
	$user = $_SESSION['USERID'];

	mysql_query("INSERT INTO `client_person` 
							(`user_id`, `client_id`, `name`, `lastname`, `position`, `phone`, `mobile_phone`, `email`, `actived`) 
						VALUES 
							('$user', '$hidden_client_id', '$person_name', '$person_surname', '$person_posityon', '$person_mobile', '$person_phone', '$person_comment', '1')");

}

function Saveperson($hidden_id, $person_name, $person_surname, $person_posityon, $person_mobile, $person_phone, $person_comment){

	

	mysql_query("	UPDATE  `client_person` 
						SET 	
							`name`			='$person_name', 
							`lastname`		='$person_surname', 
							`position`		='$person_posityon', 
							`phone`			='$person_mobile', 
							`mobile_phone`	='$person_phone', 
							`email`			='$person_comment' 
					WHERE `id`='$hidden_id'
				");

}

function Getincomming($hidden_id)
{
	$res = mysql_fetch_assoc(mysql_query("SELECT id,
		    									 `name`,
												 lastname,
												 position,
												 phone,
												 mobile_phone,
												 email
										FROM `client_person`
										WHERE id='$hidden_id'"));
	return $res;
}

function GetPage($res,$increment)
{
	$data  .= '
	
	<div id="dialog-form">
	    <fieldset>
	       <legend>ძირითადი ინფორმაცია</legend>
			<table class="dialog-form-table">
	           <tr>
	               <td colspan="2"><label for="incomming_cat_1_1_1">სახელი</label></td>
    	       </tr>
	           <tr>
	               <td colspan="2"><input type="text" id="person_name" style="resize: vertical;width: 300px;" value="'.$res[name].'"></td>
    	       </tr>
				<tr>
	               <td colspan="2"><label for="incomming_cat_1_1_1">გვარი</label></td>
    	       </tr>
	           <tr>
	               <td colspan="2"><input type="text" id="person_surname" style="resize: vertical;width: 300px;" value="'.$res[lastname].'"></td>
    	       </tr>
				<tr>
	               <td colspan="2"><label for="incomming_cat_1_1_1">თანამდებობა</label></td>
    	       </tr>
	           <tr>
	               <td colspan="2"><input type="text" id="person_posityon" style="resize: vertical;width: 300px;" value="'.$res[position].'"></td>
    	       </tr>
			<tr>
	               <td colspan="2"><label for="incomming_cat_1_1_1">მობილური</label></td>
    	       </tr>
	           <tr>
	               <td colspan="2"><input type="text" id="person_mobile" style="resize: vertical;width: 300px;" value="'.$res[phone].'"></td>
    	       </tr>
				<tr>
	               <td colspan="2"><label for="incomming_cat_1_1_1">ტელეფონი</label></td>
    	       </tr>
	           <tr>
	               <td colspan="2"><input type="text" id="person_phone" style="resize: vertical;width: 300px;" value="'.$res[mobile_phone].'"></td>
    	       </tr>
			   <tr>
	               <td colspan="2"><label for="incomming_cat_1_1_1">ელ ფოსტა</label></td>
    	       </tr>
	       	   <tr>
	               <td colspan="2"><input id="person_comment" style="resize: vertical; width: 300px;" value="'.$res[email].'"></td>
    	       </tr>
	       </table>
		 </fieldset>
	  </div>
	<input type="hidden" value="'.$res[id].'" id="person_hidden_id">';

	return $data;
}



function increment($table){

    $result   		= mysql_query("SHOW TABLE STATUS LIKE '$table'");
    $row   			= mysql_fetch_array($result);
    $increment   	= $row['Auto_increment'];
    $next_increment = $increment+1;
    mysql_query("ALTER TABLE $table AUTO_INCREMENT=$next_increment");

    return $increment;
}

?>