<?php
/* ******************************
 *	Category aJax actions
 * ******************************
*/
 
include('../../includes/classes/core.php');
$action	= $_REQUEST['act'];
$error	= '';
$data	= '';
$par_id 		= $_REQUEST['par_id'];
switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);
		
        break;
    case 'get_cat':
        $page		= Get_Category('',$_REQUEST['ch_ubani']);
        $data		= array('page'	=> $page);
    
        break;
    case 'get_edit_page':
	    $cat_id		= $_REQUEST['id'];
		$page		= GetPage(GetCategory($cat_id));
        
        $data		= array('page'	=> $page);
        
        break;
 	case 'get_list' :
		$count	= $_REQUEST['count'];
	    $hidden	= $_REQUEST['hidden'];
	    
	    $rResult = mysql_query("SELECT	`info`.`id`,
	                                    `info`.`id`,
                                		IF(info.parent_id = 0,`info`.`name`,(SELECT `name` FROM `service_center` WHERE `id` = `info`.`parent_id`)),
                                        IF(info.parent_id = 0,'',(SELECT `name` FROM `service_center` WHERE `id` = `info`.`id`)),
	                                    `branch`.`name`
							    FROM	`service_center` AS `info`
	                            LEFT JOIN branch ON branch.id = info.branch_id
	    						WHERE	`info`.`actived` = 1");
	    
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
    case 'get_list_log' :
        $count	= $_REQUEST['count'];
        $hidden	= $_REQUEST['hidden'];
    
        $rResult = mysql_query("SELECT 	`logs`.`id`,
                        				`logs`.`row_id`,
                        				`logs`.`date`,
                                        `user_info`.`name`,
                        				IF(`logs`.`event` = 1,'Add',IF(`logs`.actived = 0,'Delete','Update')) AS `act`,
										CASE 
												WHEN `logs`.`collumn` = 'name' then 'Name'
                                                WHEN `logs`.`collumn` = 'parent' then 'District'
												WHEN `logs`.`collumn` = 'branch' then Branch'
										END AS `colum`,
                        				`logs`.`old_value`,
                        				`logs`.`new_value`
                                FROM    `logs`
                                JOIN    `users` ON `logs`.user_id = users.id
                                JOIN    `user_info` ON users.id = user_info.user_id
                                WHERE   `logs`.`table` = 'service_center'");
    
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
    case 'save_category':
		$cat_id 		= $_REQUEST['id'];
		$par_id 		= $_REQUEST['parent_id'];
		$ubani          = $_REQUEST['ubani'];
		$branch_id      = $_REQUEST['branch_id'];
		
    	$cat_name		= htmlspecialchars($_REQUEST['cat'], ENT_QUOTES);
		
		if($cat_name != '' && $cat_id == ''){
			if(!CheckCategoryExist($cat_name, $par_id)){
				AddCategory($cat_name, $par_id, $branch_id);
			} else {
				$error = '"' . $cat_name . '" It is already in the list!';
			}
		}else{
			SaveCategory($cat_id, $cat_name, $par_id, $branch_id);
		}
		
        break;
    case 'disable':
		$cat_id	= $_REQUEST['id'];
		DisableCategory($cat_id);
		
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

function AddCategory($cat_name, $par_id, $branch_id)
{
	mysql_query("INSERT INTO `service_center`
					(`user_id`, `name`, `parent_id`,  `branch_id`) 
				 VALUES
					('$_SESSION[USERID]', '$cat_name', '$par_id', '$branch_id')");
}

function SaveCategory($cat_id, $cat_name, $par_id, $branch_id)
{
	mysql_query("UPDATE
	    			`service_center`
				 SET
				    `name` = '$cat_name',
				    `parent_id`	= '$par_id',
                    `branch_id` = '$branch_id'
				 WHERE
					`id` = $cat_id");
}

function DisableCategory($cat_id)
{
    mysql_query("UPDATE `service_center`
				 SET    `actived` = 0
				 WHERE	`id` = $cat_id");
}

function CheckCategoryExist($cat_name, $par_id) 
{
    $res = mysql_fetch_assoc(mysql_query("SELECT `id`
										  FROM   `service_center`
										  WHERE  `name` = '$cat_name' && `parent_id` = $par_id && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}
function Get_category($par_id,$ch_ubani)

{ 			
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `service_center`
						WHERE actived=1 AND parent_id = 0");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $par_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function branch($id)

{
    $data = '';
    $req = mysql_query("SELECT 	`id`,
                				`name`
                        FROM 	`branch`
                        WHERE 	`actived` = 1");


    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}

function GetCategory($cat_id) 
{
    $res = mysql_fetch_assoc(mysql_query("SELECT `id`,
    											 `name`,
    											 `parent_id`,
                                                 `branch_id`
									      FROM   `service_center`
									      WHERE  `id` = $cat_id" ));
    
	return $res;
}

function GetPage($res = '')
{
	$data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>Basic information</legend>
	    	
	    	<table class="dialog-form-table">
	            <tr>
					<td style="width: 170px;"><label for="parent_id">District</label></td>
					<td>
						<input type="checkbox" id="ubani" '.(($res[parent_id]>0)?'checked':"").'>
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="category">Sub Category</label></td>
					<td>
						<input type="text" id="category" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['name'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="parent_id">Category</label></td>
					<td>
						<select id="parent_id" class="idls large">' . Get_Category($res['parent_id'])  . '</select>
					</td>
				</tr>
				<tr id="br_show">
					<td style="width: 170px;"><label for="parent_id">branch</label></td>
					<td>
						<select id="branch_id" class="idls large">' . branch($res['branch_id'])  . '</select>
					</td>
				</tr>
			</table>
			<!-- ID -->
			<input type="hidden" id="cat_id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

?>
