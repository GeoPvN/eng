<?php
require_once('../../includes/classes/core.php');
$action	= $_REQUEST['act'];
$error	= '';
$data	= '';
 
$id		     = $_REQUEST['id'];
$name        = mysql_real_escape_string($_REQUEST['name']);
$value       = mysql_real_escape_string($_REQUEST['value']);
$id_detail   = $_REQUEST['id_detail'];
$id_original = $_REQUEST['id_original'];
$new_str     = $_REQUEST['new_str'];
 
switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
	       $page		= GetPage(Get($id));
           $data		= array('page'	=> $page);

		break;
	case 'get_add_page_detail':
	    $page		= GetPageDetail();
	    $data		= array('page'	=> $page);
	
	    break;
	case 'get_edit_page_detail':
	    $page		= GetPageDetail(GetDetail($id));
	    $data		= array('page'	=> $page);
	
	    break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
		 
		$rResult = mysql_query("SELECT  `scenario_handbook`.`id`,
		                                `scenario_handbook`.`name`
		                        FROM    `scenario_handbook`
							    WHERE 	`scenario_handbook`.`actived`=1");

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
	case 'get_list_detail' :
	    $count	= $_REQUEST['count'];
	    $hidden	= $_REQUEST['hidden'];
	    	
	    $rResult = mysql_query("SELECT  `scenario_handbook_detail`.`id`,
    	                                `scenario_handbook_detail`.`value`
    	                        FROM    `scenario_handbook_detail`
    						    WHERE 	`scenario_handbook_detail`.`actived` = 1 AND scenario_handbook_id = $id");
	
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
                              <input type="checkbox" id="callapp_checkbox_detail_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                              <label for="callapp_checkbox_detail_'.$aRow[$hidden].'"></label>
                          </div>';
	            }
	        }
	        $data['aaData'][] = $row;
	    }
	
	    break;
	case 'save':
		
		if($name != ''){
			if(!CheckExist($name)){
				if ($new_str == 1) {
					Add( $id, $name);
				}else {
					Save($id, $name);
				}
								
			} else {
				$error = '"' . $name . '" უკვე არის სიაში!';
				
			}
		}
		
		break;
	case 'save_detail':
	    
    	if($value != ''){
    		if(!CheckDetailExist($value)){
    			if ($id_detail == '') {
    				AddDetail( $id_original, $value);
    			}else {
    				SaveDetail($id_detail, $value);
    			}
    							
    		} else {
    			$error = '"' . $name . '" უკვე არის სიაში!';
    			
    		}
    	}
	
		break;
	case 'disable':
		Disable($id);

		break;
	case 'disable_detail':
	    DisableDetail($id);
	
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

function Add($id, $name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 `scenario_handbook`
								(`name`,`user_id`)
					VALUES 		('$name', '$user_id')");
}

function AddDetail($id, $name)
{
    $user_id	= $_SESSION['USERID'];
    mysql_query("INSERT INTO 	 `scenario_handbook_detail`
                (`value`,`user_id`,`scenario_handbook_id`)
                VALUES 		('$name', '$user_id','$id')");
}

function Save($id, $name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `scenario_handbook`
					SET     `name` = '$name',
							`user_id` ='$user_id'
					WHERE	`id` = $id");
}

function SaveDetail($id, $name)
{
    $user_id	= $_SESSION['USERID'];
    mysql_query("	UPDATE `scenario_handbook_detail`
                    SET     `value` = '$name',
                            `user_id` ='$user_id'
                    WHERE	`id` = $id");
}

function Disable($id)
{
	mysql_query("	UPDATE `scenario_handbook`
					SET    `actived` = 0
					WHERE  `id` = $id");
}

function DisableDetail($id)
{
    mysql_query("	UPDATE `scenario_handbook_detail`
                    SET    `actived` = 0
                    WHERE  `id` = $id");
}

function CheckExist($name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `scenario_handbook`
											WHERE  `name` = '$name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}

function CheckDetailExist($name)
{
    $res = mysql_fetch_assoc(mysql_query("	SELECT `id`
                                            FROM   `scenario_handbook_detail`
                                            WHERE  `name` = '$name' && `actived` = 1"));
    if($res['id'] != ''){
        return true;
    }
    return false;
}

function Get($id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`name`
											FROM    `scenario_handbook`
											WHERE   `id` = $id" ));

	return $res;
}

function GetDetail($id)
{
    $res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
                                                    `value`
                                            FROM    `scenario_handbook_detail`
                                            WHERE   `id` = $id" ));

    return $res;
}

function GetPage($res = '')
{
    $req = mysql_fetch_array(mysql_query("SELECT  `id`+1 as `id`
										  FROM    `scenario_handbook`
                                          ORDER BY id DESC
									      LIMIT 1"));
    if($res['id'] == ''){
        $id_checker = (($req[0]=='')?1:$req[0]);
        $new = 1;
    }else{
        $id_checker = $res['id'];
        $new = 0;
    }
	$data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>

	    	<table class="dialog-form-table">
				<tr>
					<td style="width: 170px;"><label for="name">სახელი</label></td>
					<td>
						<input style="width: 370px !important;" type="text" id="name" value="' . $res['name'] . '" />
					</td>
				</tr>

			</table>
            <div id="button_area" style="margin-top: 15px;">
            	<button id="add_button_detail">დამატება</button>
            	<button id="delete_button_detail">წაშლა</button>
            </div>
            <table class="display" id="table_detail">
                <thead>
                    <tr id="datatable_header">
                        <th>ID</th>
                        <th style="width: 100%;">მნიშვნელობა</th>
                    	<th class="check">#</th>
                    </tr>
                </thead>
                <thead>
                    <tr class="search_header">
                        <th class="colum_hidden">
                        <th>
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                        </th>
                      <th>
                        	<div class="callapp_checkbox">
                                <input type="checkbox" id="check-all-de" name="check-all" />
                                <label for="check-all-de"></label>
                            </div>
                        </th>
                    </tr>
                </thead>
            </table>
			<!-- ID -->
			<input type="hidden" id="id" value="' . $id_checker . '" new="'.$new.'" />
        </fieldset>
    </div>
    ';
	return $data;
}

function GetPageDetail($res = '')
{
    $data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>

	    	<table class="dialog-form-table">
				<tr>
					<td style="width: 170px;"><label for="value">სახელი</label></td>
					<td>
						<input style="width: 370px !important;" type="text" id="value" value="' . $res['value'] . '" />
					</td>
				</tr>

			</table>            
			<!-- ID -->
			<input type="hidden" id="id_original" value="' .$_REQUEST['id_original']. '" />
			<input type="hidden" id="id_detail" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
    return $data;
}

?>
