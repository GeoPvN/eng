<?php
require_once('classes/core.php');

$phone  = $_REQUEST['phone'];
$text   = $_REQUEST['text'];
$user	= $_SESSION['USERID'];

$encodedtxt = urlencode($text);
//$check 		= file_get_contents('http://192.168.0.185/pls/sms/phttp2sms.Process?src=54800&dst='.$phone.'&txt='.$encodedtxt);
$check 		= file_get_contents('http://192.168.0.185/mt/oneway?username=energopro&password=EPG343&client_id=343&service_id=1&to='.$phone.'&text='.$encodedtxt);
if($check){
	$status = 1;
	$sms_inc_increm_id	= $_REQUEST['sms_inc_increm_id'];
	$sms_phone			= $_REQUEST['phone'];
	$sms_text			= $_REQUEST['text'];
	
	$sms_hidde_id		= $_REQUEST['sms_hidde_id'];
	$user	  			= $_SESSION['USERID'];
	$c_date	  			= date('Y-m-d H:i:s');
	
	mysql_query("INSERT INTO `sent_sms`
				(`user_id`, `incomming_call_id`, `date`, `phone`, `sms_id`, `content`, `status`, `actived`)
				VALUES
				('$user', '$sms_inc_increm_id', '$c_date', '$sms_phone', '$sms_hidde_id', '$sms_text', '$status', 1);");
}else {
	$status = 0;
}


$data = array("status" => $status);

echo json_encode($data);

?>