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
		$id		= $_REQUEST['id'];
	    $page		= GetPage(GetHolidays($id));
        $data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
		 
		$rResult = mysql_query("SELECT  holidays.id,
		                                DATE_FORMAT(date,'%Y-%m-%d') AS `date`,
                        				holidays.`name`,
                                        holidays_category.`name`
                                FROM    holidays
                                LEFT JOIN holidays_category ON holidays.holidays_category_id = holidays_category.id
		                        WHERE holidays.actived = 1");

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
	case 'save_holidays':
		$id 		              = $_REQUEST['id'];
		$name                     = $_REQUEST['name'];
		$date                     = $_REQUEST['date'];
		$holidays_category_id     = $_REQUEST['holidays_category_id'];
		
	
		
		if($name != ''){
			if(!CheckHolidaysExist($name, $id)){
				if ($id == '') {
					AddHolidays($name, $date, $holidays_category_id);
				}else {
					SaveHolidays($id, $name, $date, $holidays_category_id);
				}
								
			} else {
				$error = '"' . $name . '" უკვე არის სიაში!';
				
			}
		}
		
		break;
	case 'disable':
		$id	= $_REQUEST['id'];
		DisableHolidays($id);

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

function AddHolidays($name,$date,$holidays_category_id)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 `holidays`
								(`name`,`user_id`,`date`,`holidays_category_id`)
					VALUES 		('$name', '$user_id','$date','$holidays_category_id')");
}

function SaveHolidays($id, $name, $date, $holidays_category_id)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `holidays`
					SET     `name` = '$name',
							`user_id` ='$user_id',
	                        `date`='$date',
	                        `holidays_category_id`='$holidays_category_id'
					WHERE	`id` = $id");
}

function DisableHolidays($id)
{
	mysql_query("	UPDATE `holidays`
					SET    `actived` = 0
					WHERE  `id` = $id");
}

function CheckHolidaysExist($name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `holidays`
											WHERE  `name` = '$name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function GetHolidays($id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  holidays.id,
            		                                DATE_FORMAT(date,'%Y-%m-%d') AS `date`,
                                    				holidays.`name`,
                                                    holidays.holidays_category_id
                                            FROM    holidays
											WHERE   holidays.`id` = $id" ));

	return $res;
}

function GetHolidaysCat($id){
    $req = mysql_query("	SELECT  `id`,
                                    `name`
                            FROM    `holidays_category`
                            WHERE   `actived` = 1" );
    
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

function GetPage($res = '')
{
	$data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>

	    	<table class="dialog-form-table">
				<tr>
					<td style="width: 170px;"><label for="name">სახელი</label></td>
					<td>
						<input type="text" id="name" value="' . $res['name'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="date">თარიღი</label></td>
					<td>
						<input type="text" id="date"  value="' . $res['date'] . '" />
					</td>
				</tr>
				
				<tr>
					<td style="width: 170px;"><label for="holidays_category_id">კატეგორია</label></td>
					<td>
						<select style="width: 231px;" id="holidays_category_id" >'.GetHolidaysCat($res['holidays_category_id']).'</select>
					</td>
				</tr>

			</table>
			<!-- ID -->
			<input type="hidden" id="id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

?>
