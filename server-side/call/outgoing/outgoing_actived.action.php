<?php
// MySQL Connect Link
require_once('../../../includes/classes/core.php');
 
// Main Strings
$action                     = $_REQUEST['act'];
$error                      = '';
$data                       = '';
$user_id	                = $_SESSION['USERID'];
$actived_number             = $_REQUEST['actived_number'];
$operator                   = $_REQUEST['user_id'];
$ids                        = $_REQUEST['id'];

switch ($action) {
    case 'get_edit_page':
        $page		= GetPage();
        $data		= array('page'	=> $page);
    
        break;
	case 'get_list':
        $count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
	  	$rResult = mysql_query("SELECT  outgoing_campaign_detail.`id`,
	  	                                outgoing_campaign_detail.`id`,
	  	                                outgoing_campaign.create_date,
	  	                                phone_base_detail.`phone1`,
                        				phone_base_detail.`phone2`,
                        				CONCAT(phone_base_detail.`firstname`,' ',phone_base_detail.`lastname`),
                        				phone_base_detail.`pid`
                                FROM `outgoing_campaign`
                                JOIN outgoing_campaign_detail ON outgoing_campaign.id = outgoing_campaign_detail.outgoing_campaign_id
                                JOIN phone_base_detail ON outgoing_campaign_detail.phone_base_detail_id = phone_base_detail.id
                                WHERE outgoing_campaign_detail.actived = 1 AND outgoing_campaign.project_id = $_REQUEST[id] AND outgoing_campaign_detail.`status` = 1");
	  
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
    
    case 'save_actived':
        ActivedUsers($user_id,$actived_number,$operator);
        
        break;
    case 'save_actived_select':
        ActivedUsersSelect($user_id,$operator,$ids);
        
        break;
    case 'get_user':
        $page 		= GetUsers();
        $data		= array('user'	=> $page);
        
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

function GetUsers(){
    $data = '';
    $req = mysql_query("SELECT  `users`.`id`,
				                `user_info`.`name`
                        FROM    `users`
                        JOIN    `user_info` ON `users`.`id` = `user_info`.`user_id`
                        WHERE   `users`.`actived` = 1");
    
    while( $res = mysql_fetch_assoc($req)){
        $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
    }
    return $data;
}

function ActivedUsers($user_id,$actived_number,$operator){
    mysql_query("UPDATE `outgoing_campaign_detail` SET
                        `user_id`='$user_id',
                        `responsible_person_id`='$operator',
                        `status`='2'
                WHERE   `status`='1'
                LIMIT   $actived_number");
}

function ActivedUsersSelect($user_id,$operator,$ids) {
    mysql_query("UPDATE `outgoing_campaign_detail` SET
                        `user_id`='$user_id',
                        `responsible_person_id`='$operator',
                        `status`='2'
                WHERE   `status`='1' AND id IN($ids)");
}

function getpage(){
    $data = '<div id="dialog-form">
                <fieldset>
                   <legend>ძირითადი ინფორმაცია</legend>
        <div style="float:left;margin-left: 5px;">
                   <label for="actived_number" style="margin-top: 5px;">ფორმირების ტიპი</label>
                   <select id="chose_actived_form" style="width: 173px;">
                   <option value="1">რაოდენობრივი</option>
                   <option value="2">კონკრეტული</option>
                   </select>
        </div>
        <div style="float:left;margin-left: 5px;">
                   <label for="actived_number" style="margin-top: 5px;">ოპერატორი</label>
                   <select id="user_id" style="width: 173px;"></select>
                   
        </div>
        <div style="float:left;margin-left: 5px;" id="raodenoba">
            	   <label for="actived_number" style="margin-top: 5px;">რაოდენობა</label>
                   <input type="number" id="actived_number" min="1" />
        </div>
                   
                   <div id="select_number" style="margin-top: 55px;">
                   <table class="display" id="table_actived_in">
                        <thead>
                            <tr id="datatable_header">
                                <th>ID</th>
                                <th style="width: 20px;">№</th>
                                <th style="width: 100%;">თარიღი</th>
                                <th style="width: 100%;">ტელეფონი 1</th>
                                <th style="width: 100%;">ტელეფონი 2</th>
                                <th style="width: 100%;">სახელი გვარი</th>
                                <th style="width: 100%;">პირადი ნომერი</th>
                                <th class="check" style="width: 20px;">#</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr class="search_header">
                                <th class="colum_hidden">
                            	   <input type="text" name="search_id" value="ფილტრი" class="search_init" />
                                </th>
                                <th>
                                	<input type="text" name="search_number" value="ფილტრი" class="search_init" />
                                </th>
                                <th>
                                    <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                                </th>    
                                <th>
                                    <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                                </th>
                                <th>
                                    <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                                </th>                         
                                <th>
                                    <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                                </th>
                                <th>
                                    <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                                </th>
                                <th>
                                	<div class="callapp_checkbox">
                                        <input type="checkbox" id="check-all-actived_in" name="check-all" />
                                        <label for="check-all-actived_in"></label>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                   </div>
            	</fieldset>	 <input type="hidden" id="hidden_id" value="'.$_REQUEST[id].'" />   
            </div>';
    return $data;
}

?>