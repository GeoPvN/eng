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
		$sms_id		= $_REQUEST['id'];
		$page		= GetPage(Get_sms($sms_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
		$inc_id	= $_REQUEST['inc_id'];
		
		$rResult = mysql_query("SELECT sent_sms.id,
									   sent_sms.date,
									   sent_sms.phone,
									   sent_sms.content,
									   IF(sent_sms.`status`=1,'გაგზავნილი','არ გაეგზავნა')AS sent_status
								FROM `sent_sms`
								WHERE sent_sms.incomming_call_id=$inc_id");

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
	case 'save_source':
		$source_id 		= $_REQUEST['id'];
		$source_name    = $_REQUEST['name'];
		$content    	= $_REQUEST['content'];



		if($source_name != ''){
			
			if ($source_id == '') {
					if(!ChecksourceExist($source_name, $source_id)){
						Addsource($source_name,$content);
					} else {
						$error = '"' . $source_name . '" უკვე არის სიაში!';
					
					}
			}else {
				Savesource($source_id, $source_name,$content);
			}

		}

		break;
	case 'disable':
		$source_id	= $_REQUEST['id'];
		Disablesource($source_id);

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

function Addsource($source_name, $content)
{	$user    = $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 	`sms`
									(`name`, `message`, `user_id`, `actived`)
							VALUES 		
									('$source_name', '$content', '$user', '1')");
}

function Savesource($source_id, $source_name, $content)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `sms`
					SET    `name` = '$source_name',
						   `message`='$content'
					WHERE  `id` = $source_id");
}

function Disablesource($source_id)
{
	mysql_query("	UPDATE `sms`
					SET    `actived` = 0
					WHERE  `id` = $source_id");
}

function ChecksourceExist($source_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `sms`
											WHERE  `name` = '$source_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Get_sms($sms_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT sent_sms.id,
												   sent_sms.phone,
												   sent_sms.content
											FROM  `sent_sms`
											WHERE  sent_sms.id=$sms_id" ));

	return $res;
}

function GetPage($res = '')
{
	$data = '
	<div id="dialog-form">
	    <fieldset style="width: 299px;">
					<legend>SMS</legend>
			    	<table class="dialog-form-table">
						<tr>
							<td style="width: 180px;"><label for="d_number">ადრესატი</label></td>
						</tr>
			    		<tr>
							<td style="width: 180px;">
								<span id="errmsg" style="color: red; display: none;">მხოლოდ რიცხვი</span>
								<input onkeypress="{if (event.which != 8 &amp;&amp; event.which != 0 &amp;&amp; event.which!=46 &amp;&amp; (event.which < 48 || event.which > 57)) {$(\'#errmsg\').html(\'მხოლოდ რიცხვი\').show().fadeOut(\'slow\'); return false;}}" type="text" id="sms_phone" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['phone'] . '"/>
							</td>
							<td>
								<button id="copy_phone" class="center">copy</button>
							</td>
							<td style="width: 69px;">
								<button id="sms_shablon" class="center">შაბლონი</button>
							</td>
						</tr>
						<tr>
							<td style="width: 180px;"><label for="content">ტექსტი</label></td>
						</tr>
					
						<tr>
							
							<td colspan="6">	
								<textarea maxlength="150"  style="width: 290px; resize: vertical;" id="sms_text" class="idle" name="call_content" cols="300" rows="4">' . $res['content'] . '</textarea>
							</td>
						</tr>
						<tr>
							<td style="width: 215px;">
								<input style="width: 50px;" type="text" id="simbol_caunt" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="0/150"/>
							</td>
							<td>
								
							</td>
							
							<td style="width: 69px;">
								<button id="send_sms" class="center">გაგზავნა</button>
							</td>
						</tr>	
					</table>
		        </fieldset>
    </div>
    ';
	return $data;
}

?>