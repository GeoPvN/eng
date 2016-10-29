<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../includes/classes/core.php');
$action 	            = $_REQUEST['act'];
$error		            = '';
$data		            = '';

switch ($action) {
	case 'get_list' :
		$count  = $_REQUEST['count'];
		$hidden = $_REQUEST['hidden'];
		$date_start = $_REQUEST['date_start'];
		$date_end   = $_REQUEST['date_end'];
		
	  	$rResult = mysql_query("	SELECT  id,id,
                                            call_datetime,
                                            extension,
                                            user_id,
                                            phone,
                                            SEC_TO_TIME(wait_time) AS `wait_time`,
                                            SEC_TO_TIME(duration) AS `duration`,
                                            concat('<p style=\"padding: 3px 0;\" onclick=play(','\'',date_format(cast(`asterisk_outgoing`.`call_datetime` AS date),	'%Y/%m/%d/'),`asterisk_outgoing`.`file_name`,'\'',')>Listen</p>') AS `file`
                                    FROM `asterisk_outgoing`
                                    WHERE DATE(call_datetime) >= '$date_start' AND DATE(call_datetime) <= '$date_end'");
	  
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
			}
			$data['aaData'][] = $row;
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

function GetPage($res='', $number)
{

	$data  .= '
	<div id="dialog-form">
			<div style="float: left; width: 580px;">	
				<fieldset >
			    	<legend>ინფო</legend>
					<fieldset float:left;">
				    	<table width="100%" class="dialog-form-table">
							<tr>
								<td>დასახელება</td>
								<td style="width:20px;></td>
								
								<td colspan "5">
									<input  type="text" id="action_name" class="idle" onblur="this.className=\'idle\'"  value="' . $res['action_name']. '"  />
								</td>
							</tr>
							<tr>
								<td style="width: 150px;"><label for="d_number">პერიოდი</label></td>
								<td>
									<input type="text" id="start_date" class="idle" onblur="this.className=\'idle\'" value="' . $res['start_date']. '" />
								</td>
								<td style="width: 150px;"><label for="d_number">-დან</label></td>
								<td>
									<input type="text" id="end_date" class="idle" onblur="this.className=\'idle\'"  value="' . $res['end_date']. '"  />
								</td>
								<td style="width: 150px;"><label for="d_number">-მდე</label></td>
							</tr>
						</table>
									
					</fieldset>
					<fieldset style="float: left; width: 536px;">
						<legend>აღწერა</legend>
				    		<table width="100%" class="dialog-form-table">
							<tr>
								<td colspan="5">
									<textarea  style="width: 530px; height: 500px; resize: none;" id="action_content" class="idle" name="content" cols="100" rows="2">' . $res['action_content'] . '</textarea>
								</td>
							</tr>		
							</table>
					</fieldset>	
			</div>
			<div style="float: right;  width: 360px;">
				</fieldset>
										
				<fieldset style="width: 440px; float: right;">
						<legend>მიმაგრებული ფაილები</legend>				
				 
		 '.show_file($res).'
 				
	  			</fieldset>		
			</div>
				<input type="hidden" id="actionn_id" value="'.$res['id'].'"/>
				<input type="hidden" id="act_id" value="'.(($res['id']!='')?$res['id']:increment('action')).'"/>
    </div>';

	return $data;
}

?>