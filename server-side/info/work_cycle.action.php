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
	    $lang_id	= $_REQUEST['id'];
        $page		= GetPage($lang_id);
        $data		= array('page'	=> $page);

		break;
	case 'get_add_page_detail':
	    $page		= GetPageDetail('',$_REQUEST['next_project']);
	    $data		= array('page'	=> $page);
	
	    break;
	case 'get_edit_page_detail':
	    $id		    = $_REQUEST['id'];
	    $page		= GetPageDetail(my_detail($id));
	    $data		= array('page'	=> $page);
	
	    break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
		 
		$rResult = mysql_query("SELECT 	`work_cycle`.`id`,
		                                `work_cycle`.`id`,
                        				`work_cycle`.`name`,
										GROUP_CONCAT(work_shift.`name` ORDER BY work_cycle_detail.num),
		                                `project`.`name`
                                FROM 	`work_cycle`
		                        JOIN    `work_cycle_detail` ON work_cycle.id = `work_cycle_detail`.`work_cycle_id` AND work_cycle_detail.actived = 1
								JOIN    `work_shift` ON work_cycle_detail.work_shift_id = `work_shift`.`id`
								JOIN    `project` ON work_cycle.project_id = `project`.`id`
                                WHERE 	`work_cycle`.`actived` = 1
                                GROUP BY `work_cycle`.`id`");

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
                                  <input type="checkbox" id="callapp_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[0].'" class="check" />
                                  <label for="callapp_checkbox_'.$aRow[$hidden].'"></label>
                              </div>';
				}
				
			}
			$data['aaData'][] = $row;
		}

		break;
	case 'get_list1' :
	    $count	= $_REQUEST['count'];
	    $hidden	= $_REQUEST['hidden'];
	    	
	    $rResult = mysql_query("SELECT 	`work_cycle_detail`.`id`,
	                                    `work_cycle_detail`.`num`,
                                        `work_shift`.`name`,
                                        `work_shift`.`start_date`,
                                        `work_shift`.`end_date`,
                                        `work_type`.`name`,
                                        `work_pay`.`name`,
                                        `work_shift`.`comment`,
                                        CONCAT('<div style=\"height: 100%; weight: 100%; background: ',`work_shift`.`color`,'\"></div>') AS `color`
                                FROM 	`work_cycle`
								JOIN work_cycle_detail ON work_cycle.id = work_cycle_detail.work_cycle_id
                                JOIN    work_shift ON work_cycle_detail.work_shift_id = work_shift.id
                                JOIN    work_pay ON work_shift.pay = work_pay.id
                                JOIN    work_type ON work_shift.type = work_type.id
                                WHERE 	work_cycle.`id` = '$_REQUEST[id]' AND `work_cycle_detail`.`actived` = 1");
	
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
                              <input type="checkbox" id="callapp_checkbox1_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[0].'" class="check" />
                              <label for="callapp_checkbox1_'.$aRow[$hidden].'"></label>
                          </div>';
	            }
	
	        }
	        $data['aaData'][] = $row;
	    }
	
	    break;
    case 'save_cycle':
        $name          = $_REQUEST['name'];
        $project_id    = $_REQUEST['project_id'];
    
        if(check_name($name)==0){
            AddCycle($name,$project_id);
        }else{
            $error = 'ესეთი სახელით უკვე არსებობს ციკლი!';
        }
        break;
        case 'update_cycle':
        $name          = $_REQUEST['name'];
        $project_id    = $_REQUEST['project_id'];
        $cycle_id      = $_REQUEST['cycle_id'];
    
        mysql_query("UPDATE `work_cycle` SET `name`='$name' WHERE `id`='$cycle_id'");
    
        break;
	case 'save_detail':
	    $detail_id     = $_REQUEST['detail_id'];
		$project_id    = $_REQUEST['project_id'];
		$cycle_id      = $_REQUEST['cycle_id'];
		$work_shift_id = $_REQUEST['work_shift_id'];
		$num           = $_REQUEST['num'];

		if($detail_id==''){
		    AddDetail($project_id, $cycle_id, $work_shift_id, $num);
		}else{
		    UpDetail($detail_id, $project_id, $cycle_id, $work_shift_id, $num);
		}

		break;
	case 'disable':
	    $id	= $_REQUEST['id'];
	
	    DisableCycle($id);
	
	
	    break;
	case 'delete_detail':
		$id	= $_REQUEST['id'];

		DisableDetail($id);


		break;
    case 'get_project':
        $data['page'] = GetProject('');
    
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
function check_name($name){
    $res = mysql_num_rows(mysql_query("SELECT * FROM work_cycle WHERE `name` = '$name'"));
    
    return $res;
}
function AddCycle($name,$project_id)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 `work_cycle`
								(`user_id`, `name`, `project_id`)
					VALUES 		('$user_id', '$name', '$project_id')");
}

function AddDetail($project_id, $cycle_id, $work_shift_id, $num)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 `work_cycle_detail`
								(`user_id`, `work_cycle_id`, `work_shift_id`, `num`)
					VALUES 		('$user_id', '$cycle_id', '$work_shift_id', '$num')");
}

function UpDetail($detail_id, $project_id, $cycle_id, $work_shift_id, $num)
{
    $user_id	= $_SESSION['USERID'];
    mysql_query("UPDATE `work_cycle_detail` SET 
                        `work_shift_id`='$work_shift_id',
                        `num`='$num',
                        `project_id`='$project_id'
                 WHERE  `id`='$detail_id'");
}

function DisableCycle($id)
{
	mysql_query("	UPDATE `work_cycle`
					SET    `actived` = 0
					WHERE  `id` = '$id'");
}

function DisableDetail($id)
{
    mysql_query("	UPDATE `work_cycle_detail`
                    SET    `actived` = 0
                    WHERE  `id` = '$id'");
}

function GetWorkShift($id,$pay){
    $req = mysql_query("    SELECT  `id`,
                                    `name`
                            FROM    `work_shift`
                            WHERE actived = 1 AND project_id = $pay");

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

function GetPage($lg)
{
    if($lg == ''){
        $req = mysql_fetch_array(mysql_query("  SELECT MAX(id)
                                                FROM `work_cycle`
                                                WHERE actived = 1"));
        $lg = $req[0];
    }
    $req1 = mysql_fetch_array(mysql_query("     SELECT `name`,`project_id`
                                                FROM `work_cycle`
                                                WHERE actived = 1 AND id = $lg"));
	$data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>
            <!-- ID -->
			<input type="hidden" id="cycle_id" value="' . $lg . '" />
			<input type="hidden" id="next_project" value="' . $req1[1] . '" />
	    	<table class="dialog-form-table">
			    <tr>
			        <td style="width: 190px;"><label for="name">სახელი</label></td>
				</tr>
			    <tr>
					<td>
						<input type="text" id="name" value="'.$req1[0].'" />
					</td>
				    <td>
				        <button id="add_button1">დამატება</button>
				    </td>
				    <td>
                        <button id="delete_button1">წაშლა</button>
				    </td>
				</tr>
			    
			</table>
			<table class="display" id="table_2" style="margin-top:20px;">
                <thead>
                    <tr id="datatable_header">
                        <th>ID</th>
						<th style="width: 11%;">რიგითობა</th>
                        <th style="width: 14%;">დასახელება</th>
                        <th style="width: 12%;">დასაწყისი</th>
                        <th style="width: 12%;">დასასრული</th>
                        <th style="width: 14%;">სამუშაო ტიპი</th>
                        <th style="width: 13%;">გადახდახი/არაგადახდადი</th>
                        <th style="width: 16%;">კომენტარი</th>
                        <th style="width: 10%;">ფერი</th>
                    	<th class="check">#</th>
                    </tr>
                </thead>
                <thead>
                    <tr class="search_header">
                        <th class="colum_hidden">
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                        </th>
                        <th>
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                        </th>
						<th>
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                        </th>
                        <th>
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                        </th>
                        <th>
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                        </th>
                        <th>
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                        </th>
                        <th>
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                        </th>
                        <th>
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                        </th>
                        <th>
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                        </th>
                        <th>
                        	<div class="callapp_checkbox">
                                <input type="checkbox" id="check-all1" name="check-all1" />
                                <label for="check-all1"></label>
                            </div>
                        </th>
                    </tr>
                </thead>
            </table>
        </fieldset>
    </div>
    ';
	return $data;
}

function my_detail($id){
    $res = mysql_fetch_assoc(mysql_query("	SELECT 	`work_cycle_detail`.`id`,
                                    				`work_cycle_detail`.`work_shift_id`,
                                                    `work_cycle_detail`.`num`,
                                    				`work_cycle`.`project_id`
                                            FROM 	`work_cycle_detail`
                                            JOIN    `work_cycle` ON work_cycle_detail.work_cycle_id = work_cycle.id
                                            WHERE 	`work_cycle_detail`.`id` = $id" ));

    return $res;
}

function GetPageDetail($res,$project)
{
if($res == ''){
    $project_id = $project;
}else{
    $project_id = $res[project_id];
}
    $data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>
            <!-- ID -->
			<input type="hidden" id="detail_id" value="' . $res[id] . '" />
	    	<table class="dialog-form-table">
			    <tr>
			        <td style="width: 190px;"><label for="work_shift_id">ცვლები</label></td>
			    </tr>
			    <tr>
					<td>
						<select id="work_shift_id" style="width: 174px;">'.GetWorkShift($res[work_shift_id],$project_id).'</select>
					</td>
				</tr>
			</table>
			<table class="dialog-form-table">
			    <tr>
			        <td style="width: 190px;"><label for="num">რიგითობა</label></td>
				</tr>
				<tr>
					<td>
						<input id="num" type="number" value="'.$res[num].'">
					</td>
				</tr>
			</table>
        </fieldset>
    </div>
    ';
    return $data;
}

?>
