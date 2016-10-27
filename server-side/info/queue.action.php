<?php
// MySQL Connect Link
require_once('../../includes/classes/core.php');

// Main Strings
$action                     = $_REQUEST['act'];
$user		                = $_SESSION['USERID'];
$error                      = '';
$data                       = '';
 

// Queue Dialog Strings
$hidden_id                = $_REQUEST['hidden_id'];
$id                       = $_REQUEST['id'];
$global_id		          = $_REQUEST['global_id'];
$id_in_up                 = $_REQUEST['id_in_up'];
$queue_name               = mysql_real_escape_string($_REQUEST['queue_name']);
$queue_number             = mysql_real_escape_string($_REQUEST['queue_number']);
$in_num_name              = mysql_real_escape_string($_REQUEST['in_num_name']);
$in_num_num               = mysql_real_escape_string($_REQUEST['in_num_num']);
$queue_scenar             = mysql_real_escape_string($_REQUEST['queue_scenar']);
 

switch ($action) {
	case 'get_add_page':
		$page		= GetPage('');
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$page		= GetPage(Getincomming($id));
		$data		= array('page'	=> $page);

        break;
    case 'get_in_num_page':
        $page		= get_in_num('');
        $data		= array('page'	=> $page);
    
        break;
    case 'disable':
        mysql_query("UPDATE `queue` SET `actived`='0' WHERE `id`='$id';");
    
        break;
    case 'disable_ext':
        mysql_query("UPDATE `queue_detail` SET `actived`='0' WHERE `id`='$id';");
    
        break;
    case 'work_gr':
        $num_row = mysql_fetch_array(mysql_query(" SELECT 	`id`
                                                FROM 		`week_day_graphic`
                                                WHERE 	`scenario_id` = '$_REQUEST[queue_scenar]' AND queue = '$_REQUEST[queue_number]' AND week_day_id = '$_REQUEST[wday]' "));
        if($num_row[0] == ''){
            mysql_query("INSERT INTO `week_day_graphic`
                         (`scenario_id`, `queue`, `week_day_id`, `start_time`, `end_time`)
                         VALUES
                         ('$_REQUEST[queue_scenar]', '$_REQUEST[queue_number]', '$_REQUEST[wday]', '$_REQUEST[min_val]', '$_REQUEST[max_val]');");
        }else{
            mysql_query("UPDATE `week_day_graphic` SET
                                `scenario_id`='$_REQUEST[queue_scenar]',
                                `queue`='$_REQUEST[queue_number]',
                                `week_day_id`='$_REQUEST[wday]',
                                `start_time`='$_REQUEST[min_val]',
                                `end_time`='$_REQUEST[max_val]'
                         WHERE  `id`='$num_row[0]'");
        }
    
        break;
    case 'get_edit_in_num_page':
        $page		= get_in_num(Get_in_num_query($id));
        $data		= array('page'	=> $page);
    
        break;
	case 'get_list':
        $count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
	  	$rResult = mysql_query("SELECT 	`queue`.`id`,
                        				`queue`.`name`,
                        				`queue`.number,
                        				`scenario`.`name` AS `scenario_name`,
                        				GROUP_CONCAT(`queue_detail`.ext_name) AS `ext_name`
                                FROM    `queue`
                                LEFT JOIN queue_detail ON queue.id = queue_detail.queue_id AND `queue_detail`.`actived` = 1
	  	                        LEFT JOIN scenario ON scenario.id = queue.scenario_id
	  	                        WHERE `queue`.`actived` = 1
	  	                        GROUP BY `queue`.`id`;");
	  
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
    case 'get_list_ext':
        $count = 		$_REQUEST['count'];
        $hidden = 		$_REQUEST['hidden'];
        $rResult = mysql_query("SELECT  `id`,
                                        `ext_name`,
                                        `ext_number` 
                                FROM    `queue_detail`
                                WHERE   `queue_id` = $hidden_id AND `actived` = 1");
         
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
                                  <input type="checkbox" id="callapp_checkbox_ext_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                                  <label for="callapp_checkbox_ext_'.$aRow[$hidden].'"></label>
                              </div>';
                }
            }
            $data['aaData'][] = $row;
        }
    
        break;
    case 'save_queue':
        save_queue($hidden_id,$queue_name,$queue_number,$user,$global_id,$queue_scenar);
    
        break;
    case 'save_in_num':
        $data		= array('global_id'	=> save_in_num($hidden_id,$in_num_name,$in_num_num,$user,$global_id,$id_in_up));
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

function save_queue($hidden_id,$queue_name,$queue_number,$user,$global_id,$queue_scenar){
    if($hidden_id == ''){
        if($global_id == ''){
            $insert_id = increment('queue');
        }else{
            $insert_id = $global_id;
        }
        mysql_query("INSERT INTO `queue`
                    (`id`, `user_id`, `name`, `number`, `scenario_id`)
                    VALUES
                    ('$insert_id', '$user', '$queue_name', '$queue_number',$queue_scenar);");
    }else{
        mysql_query("UPDATE `queue` SET 
                            `user_id`='$user',
                            `name`='$queue_name',
                            `number`='$queue_number',
                            `scenario_id`='$queue_scenar'
                     WHERE  `id`='$hidden_id';");
    }
}

function save_in_num($hidden_id,$in_num_name,$in_num_num,$user,$global_id,$id_in_up){
    if($hidden_id == ''){
        if($global_id == ''){
            $insert_id = increment('queue');
        }else{
            $insert_id = $global_id;
        }
        mysql_query("INSERT INTO `queue_detail`
                    (`queue_id`, `user_id`, `ext_name`, `ext_number`)
                    VALUES
                    ('$insert_id', '$user', '$in_num_name', '$in_num_num');");
    }else{
        if($id_in_up == ''){
        mysql_query("INSERT INTO `queue_detail`
                     (`user_id`, `queue_id`, `ext_name`, `ext_number`)
                     VALUES
                     ('$user', '$hidden_id', '$in_num_name', '$in_num_num');");
        }else{
            mysql_query("UPDATE `queue_detail` SET
                                `user_id`='$user',
                                `ext_name`='$in_num_name',
                                `ext_number`='$in_num_num'
                         WHERE  `id`='$id_in_up';");
        }
    }

    return $insert_id;
}

function Getincomming($id)
{
	$res = mysql_fetch_assoc(mysql_query("SELECT 	`queue`.`id`,
                                    				`queue`.`name`,
                                    				`queue`.`number`,
	                                                `queue`.`scenario_id`
                                          FROM      `queue`
                                          WHERE     `queue`.`id` = $id"));
	return $res;
}

function Get_in_num_query($id)
{
    $res = mysql_fetch_assoc(mysql_query("SELECT 	`id`,
                                                    `ext_name`,
                                    				`ext_number`
                                          FROM 		`queue_detail`
                                          WHERE		`id` = $id"));
    return $res;
}

function getscenario($id){
    $res = mysql_query("SELECT  `id`,
                		        `name`
                        FROM 	`scenario`
                        WHERE 	`actived` = 1");
    $data = '<option value="0" selected>-----</option>';
    while ($req = mysql_fetch_assoc($res)){
        if($req['id'] == $id){
            $data .= '<option value="'.$req['id'].'" selected>'.$req['name'].'</option>';
        }else{
            $data .= '<option value="'.$req['id'].'" >'.$req['name'].'</option>';
        }
    }
    return $data;
}

function GetPage($res)
{
	$data  .= '
	<div id="dialog-form">
	    <fieldset style="width: 275px;  float: left;">
	       <legend>ძირითადი ინფორმაცია</legend>
	       <table class="dialog-form-table">
    	       <tr>
	               <td style="width: 210px;"><label for="queue_name">დასახელება</label></td>
	               <td><input id="queue_name" type="text" value="'.$res['name'].'"></td>
    	       </tr>
	           <tr>
	               <td><label for="queue_number">ნომერი</td>
	               <td><input id="queue_number" type="text" value="'.$res['number'].'"></td>
    	       </tr>
	       </table>
	    </fieldset>
	    
	    
        <div id="side_menu" style="float: left;height: 346px;width: 80px;margin-left: 10px; background: #272727; color: #FFF;margin-top: 6px;">
	       <spam class="info" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'info\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/info.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">შიდა ნომერი</div></spam>
	       <spam class="task" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'task\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/task.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">სცენარი</div></spam>
        </div>
	    
	    <div style="width: 574px;float: left;margin-left: 10px;" id="right_side">
            <fieldset style="display:none;" id="info">
                <legend>შიდა ნომერი</legend>
	            <span class="hide_said_menu">x</span>
	            <div style="margin: 15px 0;">
                    <button id="add_button_ext">დამატება</button>
                    <button id="delete_button_ext">წაშლა</button>
                </div>
	            <table class="display" id="table_ext" style="width:100%;">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 100%;">დასახელება</th>
                            <th style="width: 100%;">ნომერი</th>
                            <th class="check" style="width: 25px;">&nbsp;</th>
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
                            	<div class="callapp_checkbox">
                                    <input type="checkbox" id="check-all-ext" name="check-all" />
                                    <label for="check-all-ext"></label>
                                </div>
                            </td>           
                        </tr>
                    </thead>
                </table>
            </fieldset>
    	     
            <fieldset style="display:none;" id="task">
                <legend>სცენარი</legend>
	            <span class="hide_said_menu">x</span>
	            <table class="dialog-form-table">
                    <tr>
                       <td style="width: 210px;"><label for="queue_scenar">დასახელება</label></td>                   
                    </tr>
    	            <tr>
                       <td><select id="queue_scenar" style="width: 300px;">'.getscenario($res['scenario_id']).'</select></td>
                    </tr>
	            </table>
	    <style>
	    #work_table{
	    
	    width: 100%;
	    margin-top:5px;
	    }
	    #work_table td,#work_table th{
	    border: 1px solid;
        font-size: 11px;
        font-weight: normal;
	    }
	    .im_border{
	    border:1px solid;
	    }
        #work_table td input{
        display:none;
        }
	    </style>
	            <table class="dialog-form-table" id="work_table">
                    <tr>
                        <th style="width: ;"></th>
                	    <th style="width: ;">00:00</th>
                	    <th style="width: ;">01:00</th>
                	    <th style="width: ;">02:00</th>
                	    <th style="width: ;">03:00</th>
                	    <th style="width: ;">04:00</th>
                	    <th style="width: ;">05:00</th>
                	    <th style="width: ;">06:00</th>
                	    <th style="width: ;">07:00</th>
                	    <th style="width: ;">08:00</th>
                	    <th style="width: ;">09:00</th>
                	    <th style="width: ;">10:00</th>
                	    <th style="width: ;">11:00</th>
                	    <th style="width: ;">12:00</th>
                	    <th style="width: ;">13:00</th>
                	    <th style="width: ;">14:00</th>
	                    <th style="width: ;">15:00</th>
                	    <th style="width: ;">16:00</th>
                	    <th style="width: ;">17:00</th>
                	    <th style="width: ;">18:00</th>
                	    <th style="width: ;">19:00</th>
	                    <th style="width: ;">20:00</th>
                	    <th style="width: ;">21:00</th>
                	    <th style="width: ;">22:00</th>
                	    <th style="width: ;">23:00</th>
                    </tr>
    	            <tr id="wday1">
                        <td style="width: ;">ორშ</td>
                	    <td style=""><input type="checkbox" value="00:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="01:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="02:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="03:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="04:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="05:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="06:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="07:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="08:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="09:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="10:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="11:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="12:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="13:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="14:00" wday="1" ></td>
	                    <td style=""><input type="checkbox" value="15:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="16:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="17:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="18:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="19:00" wday="1" ></td>
	                    <td style=""><input type="checkbox" value="20:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="21:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="22:00" wday="1" ></td>
                	    <td style=""><input type="checkbox" value="23:00" wday="1" ></td>
                    </tr>
	                <tr id="wday2">
                        <td style="width: ;">სამ</td>
                	    <td style=""><input type="checkbox" value="00:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="01:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="02:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="03:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="04:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="05:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="06:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="07:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="08:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="09:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="10:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="11:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="12:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="13:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="14:00" wday="2" ></td>
	                    <td style=""><input type="checkbox" value="15:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="16:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="17:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="18:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="19:00" wday="2" ></td>
	                    <td style=""><input type="checkbox" value="20:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="21:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="22:00" wday="2" ></td>
                	    <td style=""><input type="checkbox" value="23:00" wday="2" ></td>
                    </tr>
	                <tr id="wday3">
                        <td style="width: ;">ოთხ</td>
                	    <td style=""><input type="checkbox" value="00:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="01:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="02:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="03:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="04:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="05:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="06:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="07:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="08:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="09:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="10:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="11:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="12:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="13:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="14:00" wday="3" ></td>
	                    <td style=""><input type="checkbox" value="15:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="16:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="17:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="18:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="19:00" wday="3" ></td>
	                    <td style=""><input type="checkbox" value="20:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="21:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="22:00" wday="3" ></td>
                	    <td style=""><input type="checkbox" value="23:00" wday="3" ></td>
                    </tr>
	                <tr id="wday4">
                        <td style="width: ;">ხუთ</td>
                	    <td style=""><input type="checkbox" value="00:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="01:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="02:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="03:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="04:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="05:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="06:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="07:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="08:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="09:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="10:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="11:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="12:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="13:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="14:00" wday="4" ></td>
	                    <td style=""><input type="checkbox" value="15:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="16:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="17:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="18:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="19:00" wday="4" ></td>
	                    <td style=""><input type="checkbox" value="20:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="21:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="22:00" wday="4" ></td>
                	    <td style=""><input type="checkbox" value="23:00" wday="4" ></td>
                    </tr>
	                <tr id="wday5">
                        <td style="width: ;">პარ</td>
                	    <td style=""><input type="checkbox" value="00:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="01:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="02:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="03:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="04:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="05:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="06:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="07:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="08:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="09:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="10:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="11:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="12:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="13:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="14:00" wday="5" ></td>
	                    <td style=""><input type="checkbox" value="15:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="16:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="17:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="18:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="19:00" wday="5" ></td>
	                    <td style=""><input type="checkbox" value="20:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="21:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="22:00" wday="5" ></td>
                	    <td style=""><input type="checkbox" value="23:00" wday="5" ></td>
                    </tr>
	                <tr id="wday6">
                        <td style="width: ;">შაბ</td>
                	    <td style=""><input type="checkbox" value="00:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="01:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="02:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="03:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="04:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="05:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="06:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="07:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="08:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="09:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="10:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="11:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="12:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="13:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="14:00" wday="6" ></td>
	                    <td style=""><input type="checkbox" value="15:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="16:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="17:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="18:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="19:00" wday="6" ></td>
	                    <td style=""><input type="checkbox" value="20:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="21:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="22:00" wday="6" ></td>
                	    <td style=""><input type="checkbox" value="23:00" wday="6" ></td>
                    </tr>
	                <tr id="wday7">
                        <td style="">კვი</td>
                	    <td style=""><input type="checkbox" value="00:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="01:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="02:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="03:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="04:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="05:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="06:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="07:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="08:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="09:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="10:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="11:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="12:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="13:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="14:00" wday="7" ></td>
	                    <td style=""><input type="checkbox" value="15:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="16:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="17:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="18:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="19:00" wday="7" ></td>
	                    <td style=""><input type="checkbox" value="20:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="21:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="22:00" wday="7" ></td>
                	    <td style=""><input type="checkbox" value="23:00" wday="7" ></td>
                    </tr>
	            </table>
	            <table class="dialog-form-table">
                    <tr>
                       <td style="width: 210px;"><label for="queue_scenar">საანგარიშო პერიოდი</label></td>    
	                   <td></td>              
                    </tr>
    	            <tr>
                       <td><select id="queue_scenar"></select></td>
	                   <td><select id="queue_scenar"></select></td>
                    </tr>
	            </table>
	            <table class="dialog-form-table">
                    <tr>
	                   <td><input id="" type="checkbox"></td>
                       <td style="width: ;"><label for="queue_scenar">დღესასწაულები</label></td>
                	   <td style="width: ;"><input style="width: 100px;" id="" type="text"></td>
                	   <td style="width: ;"><input style="width: 100px;" id="" type="text"></td>
	                   <td style="width: ;"><button>დამატება</button></td>
                    </tr>
    	            <tr>
                       <td></td>
	                   <td></td>
	                   <td class="im_border">თარიღი</td>
	                   <td class="im_border">კომენტარი</td>
	                   <td></td>
                    </tr>
	                <tr>
                       <td></td>
	                   <td></td>
	                   <td class="im_border">01.01.2016</td>
	                   <td class="im_border">ახალი წელი</td>
	                   <td>X</td>
                    </tr>
	            </table>
            </fieldset>

	    </div>
	</div>
	<input type="hidden" value="'.(($res[id]=='')?'':$res[id]).'" id="hidden_id">
	<input type="hidden" value="" id="global_id">';

	return $data;
}

function get_in_num($res){
    $data ='<div id="dialog-form">
            <fieldset>
            <legend>ძირითადი ინფორმაცია</legend>
                <table class="dialog-form-table">
                    <tr>
                	   <td><label for="in_num_name">დასახელება</label></td>
	                   <td><label for="in_num_num">ნომერი</label></td>
                    </tr>
	                <tr>
                       <td><input value="'.$res['ext_name'].'" id="in_num_name" style="width: 100px;" type="text"></td>
                	   <td><input value="'.$res['ext_number'].'" id="in_num_num" style="width: 100px;" type="text"></td>
                    </tr>
	            </table>
            </fieldset>
            </div>
            <input type="hidden" value="'.$res['id'].'" id="id_in_up">';
    return $data;
}

function increment($table){

    $result   		= mysql_query("SHOW TABLE STATUS LIKE '$table'");
    $row   			= mysql_fetch_array($result);
    $increment   	= $row['Auto_increment'];
    $increment   	= $row['Auto_increment'];
    $next_increment = $increment+1;
    mysql_query("ALTER TABLE $table AUTO_INCREMENT=$next_increment");

    return $increment;
}

?>