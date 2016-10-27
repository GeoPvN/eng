<?php
require_once('../../includes/classes/core.php');
$action	= $_REQUEST['act'];
$error	= '';
$data	= '';



$rand_file	= $_REQUEST['rand_file'];
$file		= $_REQUEST['file_name'];
$mail_id    = $_REQUEST['mail_id'];


switch ($action) {
	case 'send_mail':
	    if($_REQUEST['call_type'] == 'inc'){
	        $out_id     = $_REQUEST['incomming_id'];
	    }else{
	        $out_id 	= $_REQUEST['out_id'];
	    }
		
		$page				= GetPage($out_id,GetSMS($mail_id));
		$data				= array('page'	=> $page);

		break;
	case 'send_mail_shablon':
	
	    $page				= GetShablon();
	    $data				= array('page'	=> $page);
	
	    break;
	case 'get_list' :
		$count				= $_REQUEST['count'];
		$hidden				= $_REQUEST['hidden'];
		$inc_id	= $_REQUEST['inc_id'];
		
		$rResult = mysql_query("SELECT sent_mail.id,
									   sent_mail.date,
									   sent_mail.address,
									   sent_mail.`subject`,
									   IF(sent_mail.`status`=2,'გაგზავნილი','არ გაიგზავნა')
								FROM sent_mail
								WHERE sent_mail.incomming_call_id='$inc_id'
				");

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
					$row[] = '<input type="checkbox" name="check_' . $aRow[$hidden] . '" class="check" value="' . $aRow[$hidden] . '" />';
				}
			}
			$data['aaData'][] = $row;
		}

		break;
	case 'disable':
		$source_id	= $_REQUEST['id'];
		Disablesource($source_id);
		

		break;
		
		
		case 'delete_mail_file':
			
			$file_id 	= $_REQUEST['file_id'];
			$inccall_id = $_REQUEST['inccall_id'];
			
			mysql_query("UPDATE send_mail_detail
			JOIN   sent_mail ON sent_mail.id = send_mail_detail.sent_mail_id
			SET    send_mail_detail.actived = 0
			WHERE  file_id = $file_id AND sent_mail.incomming_call_id = $inccall_id  AND `status` = 1");
			
			$increm = mysql_query("	SELECT  file.`name`,
				    		    		file.`rand_name`,
				    		    		file.`id`
				    		    		FROM 	`sent_mail`
				    		    		LEFT JOIN send_mail_detail ON send_mail_detail.sent_mail_id=sent_mail.id
				    		    		LEFT JOIN file ON file.id=send_mail_detail.file_id
				    		    		WHERE   `sent_mail`.incomming_call_id = $inccall_id AND sent_mail.`status`=1 AND send_mail_detail.actived=1");
				
			$data1 = '';
		
			while($increm_row = mysql_fetch_assoc($increm))	{	
				
					$data1 .=' <tr style="border-bottom: 1px solid #85b1de;">
							          <td style="width:110px; display:block;word-wrap:break-word;">'.$increm_row['rand_name'].'</td>													 
							          <td style=" width: 18px;"><button type="button" value="media/uploads/file/'.$increm_row['name'].'" style="cursor:pointer; border:none; margin-top:5%; display:block; height:16px;   margin-right: 0px; width:16px; background:none;background-image:url(\'media/images/get.png\');" id="download_name1" value="'.$increm_row[rand_name].'"> </td>
							          <td style=" width: 18px;"><button file_id="'. $increm_row[id] .'" inccall_id="'. $inccall_id .'"  type="button" value="'.$increm_row['id'].'" style="cursor:pointer; border:none; margin-top:5%; display:block; height:16px; width:16px;   margin-right: 0px; background:none; background-image:url(\'media/images/x.png\');" id="delete_mail_file"></button></td>
						          </tr>';
			}
		
			$data = array('deletedfiles' => $data1);
		
			break;
		
		case 'up_now_mail':
			$user    			= $_SESSION['USERID'];
			$incomming_call_id	= $_REQUEST['sms_inc_increm_id'];
			$send_email_hidde	= $_REQUEST['send_email_hidde'];
			$sms_inc_increm_id	= $_REQUEST['sms_inc_increm_id'];
			
					
					$check = mysql_query("SELECT sent_mail.id
										  FROM sent_mail
										  WHERE sent_mail.`status`=1 AND sent_mail.incomming_call_id=$incomming_call_id");
					if (mysql_num_rows($check)>0){
					    
					    
					    
					    $rand_file = $_REQUEST['rand_file'];
					    $file_name = $_REQUEST['file_name'];
					    if($rand_file != ''){
					        mysql_query("INSERT INTO `file`
					            (`user_id`, `incomming_call_id`, `task_id`, `name`, `rand_name`, `actived`)
					            VALUES
					            ('$user', NULL, NULL, '$file_name', '$rand_file', '1')");
					    }
					    	
					    $file_id = mysql_insert_id();
					    	
					    mysql_query("INSERT INTO `send_mail_detail`
					        (`user_id`, `sent_mail_id`, `file_id`, `actived`)
					        VALUES
					        ( '$user', '$send_email_hidde', '$file_id', '1')");
						
					}else {
					    
					    $rand_file = $_REQUEST['rand_file'];
					    $file_name = $_REQUEST['file_name'];
					    
					    mysql_query("INSERT INTO `sent_mail` 
                                            (`incomming_call_id`, `user_id`, `date`, `status`, `actived`) 
                                           VALUES 
                                            ( '$incomming_call_id', '$user', NOW(), '1', '1');");
					    
					    $send_email_id = mysql_insert_id();
					    
					    if($rand_file != ''){
					        mysql_query("INSERT INTO `file`
					            (`user_id`, `incomming_call_id`, `task_id`, `name`, `rand_name`, `actived`)
					            VALUES
					            ('$user', NULL, NULL, '$file_name', '$rand_file', '1')");
					    }
					    	
					    $file_id = mysql_insert_id();
					    	
					    mysql_query("INSERT INTO `send_mail_detail`
					        (`user_id`, `sent_mail_id`, `file_id`, `actived`)
					        VALUES
					        ( '$user', '$send_email_id', '$file_id', '1')");
					    
					}
					
					
			
			
		
				$increm = mysql_query("	SELECT  file.`name`,
				    		    		file.`rand_name`,
				    		    		file.`id`
				    		    		FROM 	`sent_mail`
				    		    		LEFT JOIN send_mail_detail ON send_mail_detail.sent_mail_id=sent_mail.id
				    		    		LEFT JOIN file ON file.id=send_mail_detail.file_id
				    		    		WHERE   `sent_mail`.incomming_call_id = $incomming_call_id AND sent_mail.`status`=1 AND file.actived=1");
				$data = '';
		
				while($increm_row = mysql_fetch_assoc($increm))	{	
					$data_file .=' <tr style="border-bottom: 1px solid #85b1de;">
							          <td style="width:110px; display:block;word-wrap:break-word;">'.$increm_row['rand_name'].'</td>													 
							          <td style=" width: 18px;"><button type="button" value="media/uploads/file/'.$increm_row['name'].'" style="cursor:pointer; border:none; margin-top:5%; display:block; height:16px;   margin-right: 0px; width:16px; background:none;background-image:url(\'media/images/get.png\');" id="download_name1" value="'.$increm_row[rand_name].'"> </td>
							          <td style=" width: 18px;"><button file_id="'. $increm_row[id] .'" inccall_id="'. $incomming_call_id .'"  type="button" value="'.$increm_row['id'].'" style="cursor:pointer; border:none; margin-top:5%; display:block; height:16px; width:16px;   margin-right: 0px; background:none; background-image:url(\'media/images/x.png\');" id="delete_mail_file"></button></td>
						          </tr>';
					}
	
	 		
			$data = array('new_file_name' => $data_file);
		
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

function Addsource($source_name, $content){
		
    $user    = $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 	`mail`
									(`subject`, `body`, `user_id`, `actived`)
							VALUES 		
									('$source_name', '$content', '$user', '1')");
	
}

function Savesource($source_id, $source_name, $content){
	
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `mail`
					SET    `subject` = '$source_name',
						   `body`='$content'
					WHERE  `id` = $source_id");
	
}

function Disablesource($source_id){
	
	mysql_query("	UPDATE `mail`
					SET    `actived` = 0
					WHERE  `id` = $source_id");
	
}

function ChecksourceExist($source_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `mail`
											WHERE  `subject` = '$source_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Getsource($source_id){
	$res = mysql_fetch_assoc(mysql_query("	SELECT  id,
                                    				address,
	                                                cc_address,
	                                                bcc_address,
                                    				`subject`,
                                    				body
                                            FROM `sent_mail`
                                            WHERE actived=1 AND `status`=2 AND id=$source_id" ));

	return $res;
}

function GetSMS($id){
    $res = mysql_fetch_array(mysql_query("SELECT sent_mail.id,
                                                date,
                                                address,
                                                cc_address,
                                                bcc_address,
                                                `subject`,
                                                body                                                
                                            FROM `sent_mail`                                            
                                            WHERE sent_mail.id = $id"));
    return $res;
}

function GetPage($out_id, $res){
    if($_REQUEST['call_type'] == 'inc'){
        if($out_id!=''){
            mysql_query("INSERT INTO `sent_mail`
                (`incomming_call_id`, `status`)
                VALUES
                ('$out_id','1')");
            $rrr = mysql_fetch_array(mysql_query("SELECT id AS id FROM `sent_mail` WHERE actived = 1 ORDER BY id DESC LIMIT 1"));
        }
    }else{
        if($out_id!=''){
            mysql_query("INSERT INTO `sent_mail`
                (`outgoing_id`, `status`)
                VALUES
                ('$out_id','1')");
            $rrr = mysql_fetch_array(mysql_query("SELECT id AS id FROM `sent_mail` WHERE actived = 1 ORDER BY id DESC LIMIT 1"));
        }
    }
    $file = mysql_query("SELECT file.file_date,file.`name`,rand_name,file.id
                        FROM `send_mail_detail`
                        JOIN file ON send_mail_detail.file_id = file.id
                        WHERE sent_mail_id = $res[id] AND file.actived = 1");
	$data = '
	<div id="dialog-form">
	    <fieldset style="height: auto;">
	    	<table class="dialog-form-table">
				
				<tr>
					<td style="width: 90px; "><label for="d_number">ადრესატი:</label></td>
					<td>
						<input type="text" style="width: 490px !important;"id="mail_address" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['address'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 90px;"><label for="d_number">CC:</label></td>
					<td>
						<input type="text" style="width: 490px !important;" id="mail_address1" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['cc_address'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 90px;"><label for="d_number">Bcc:</label></td>
					<td>
						<input type="text" style="width: 490px !important;" id="mail_address2" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['bcc_address'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 90px;"><label for="d_number">სათაური:</label></td>
					<td>
						<input type="text" style="width: 490px !important;" id="mail_text" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['subject'] . '" />
					</td>
				</tr>
			</table>
			<table class="dialog-form-table">
				<tr>
					<td>	
						<textarea id="input" style="width:400px; height:200px">' . $res['body'] . '</textarea>
					</td>
			   </tr>
			</table>
			<div style="margin-top: 15px;">
                    <div style="width: 100%; border:1px solid #CCC;float: left;">    	            
    	                   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 180px;float:left;">თარიღი</div>
                    	   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 189px;float:left;">დასახელება</div>
                    	   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 160px;float:left;">ჩამოტვირთვა</div>
                           <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 20px;float:left;">-</div>
    	                   <div style="text-align: center;vertical-align: middle;float: left;width: 595px;"><button id="choose_button_mail" style="cursor: pointer;background: none;border: none;width: 100%;height: 25px;padding: 0;margin: 0;">აირჩიეთ ფაილი</button><input style="display:none;" type="file" name="choose_mail_file" id="choose_mail_file"></div>
                           <div id="paste_files1">';
	while ($file_body = mysql_fetch_array($file)) {
	    $data .= '<div id="first_div">'.$file_body[0].'</div>
        	        <div id="two_div">'.$file_body[1].'</div>
        	        <div id="tree_div" onclick="download_file(\''.$file_body[2].'\',\''.$file_body[1].'\')">ჩამოტვირთვა</div>
        	            <div id="for_div" onclick="delete_file1(\''.$file_body[3].'\')">-</div>';
	}
                           $data .='</div>
            	    </div>
	            </div>
			
			<fieldset style="display: inline-flex; width: 100%; margin-left: -11px; float: left;">
			<table class="dialog-form-table">
			<tr>
					<td style="width: 69px;">
						<button id="email_shablob" class="center">შაბლონი</button>
					</td>		
					<td>
						<div class="file-uploader">
							
							<input id="hidden_inc" type="text" value="'. $res['name'] .'" style="display: none;">
						</div>
				    </td>
					<td style="width: 69px;">
						
					</td>
					<td style="width: 69px;">
					</td>
					<td style="width: 69px;">
					</td>
					<td style="width: 69px;">
					</td>
					<td style="width: 69px;">
					</td>
					<td style="width: 69px;">
					</td>
					<td style="width: 69px;">
						<button id="send_email" class="center">გაგზავნა</button>
					</td>
				</tr>
			</table>
			</fieldset>
			<!-- ID -->
			<input type="hidden" id="source_id" value="'; if($out_id!=''){ $data.=$rrr[0]; }else{ $data.=$res['id']; } $data.='" />
        </fieldset>
    </div>
    ';
	return $data;
}

function GetShablon() {
    $res = mysql_query("SELECT 	`id`,
                				`subject`,
                				`body`
                        FROM    `mail`
                        WHERE 	`actived` = 1");
    
    while ($req = mysql_fetch_array($res)){
        $tbody .= '<tr>
                    <td>'.$req[0].'</td>
                    <td>'.$req[1].'</td>
                    <td><span onclick="pase_body(\'body_'.$req[0].'\',\''.$req[1].'\')">არჩევა</span> <div id="body_'.$req[0].'" style="display:none;">'.$req[2].'</div></td>
                   </tr>';
    }
    $data = '<div id="dialog-form">
        	    <fieldset style="height: auto;">
                    <table class="display">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>სათაური</th>
                                <th>ქმედება</th>
                            </tr>
                        </thead>
                        <tbody>
        '.$tbody.'
                        </tbody>
                    </table>
                </fieldset>
             </div>';
    return $data;
}

?>