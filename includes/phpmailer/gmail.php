<?php

date_default_timezone_set('Etc/UTC');
header('Content-Type: text/html; charset=utf-8');

require_once('PHPMailerAutoload.php');
require_once('../../includes/classes/core.php');

$sent_mail_id 	 	= $_REQUEST['source_id'];
$incomming_call_id	= $_REQUEST['incomming_call_id'];

$address 			= $_REQUEST['address'];
$cc_address 		= $_REQUEST['cc_address'];
$bcc_address 		= $_REQUEST['bcc_address'];
$subject 	 		= $_REQUEST['subject'];
$body 	 			= $_REQUEST['body'];

$signature          = '';

$body 				= $body.$signature;

$res  = mysql_query("SELECT	concat('../../media/uploads/file/',rand_name) AS `rand_name`
				 	 FROM 	`file`
				  	 JOIN	send_mail_detail ON send_mail_detail.file_id = file.id
					 JOIN   sent_mail ON sent_mail.id = send_mail_detail.sent_mail_id
				  	 WHERE	send_mail_detail.sent_mail_id = $sent_mail_id AND status=1");

$mail = new PHPMailer;

$mail->isSMTP();

$mail->SMTPDebug	= 0;

$mail->Host			= 'mail.energo-pro.ge';

$mail->Port			= 587;

$mail->SMTPSecure	= 'tls';

$mail->SMTPAuth		= true;

$mail->Username		= "customers@energo-pro.ge";

$mail->Password		= "Sc1324";

$mail->setFrom('customers@energo-pro.ge', 'Energo Pro');

$mail->addAddress($address);

$mail->AddCC($bcc_address);

$mail->AddBCC($cc_address);

$mail->Subject = $subject;

$mail->msgHTML($body);


while ($row = mysql_fetch_assoc($res)) {
	
     $mail->addAttachment($row[rand_name]);
    
}

if (!$mail->send()) {
	
	$status				= 'false';
    $data 				= array("status" => $status);
    
    $user	  			= $_SESSION['USERID'];
    $c_date	  			= date('Y-m-d H:i:s');
    
    mysql_query("UPDATE `sent_mail`
			     SET 	`user_id`='$user',
					    `date`='$c_date',
					    `address`='$address',
					    `cc_address`='$cc_address',
					    `bcc_address`='$bcc_address',
					    `subject`='$subject',
					    `body`='$body',
					    `status`='3'
			    WHERE 	`id`=$sent_mail_id;
    ");
    
    
}else {
	
	$status				= 'true';
	$data 				= array("status" => $status);
	
	$user	  			= $_SESSION['USERID'];
	$c_date	  			= date('Y-m-d H:i:s');
	
	$res_check=mysql_query("SELECT sent_mail.id
							FROM sent_mail
							WHERE sent_mail.id=$sent_mail_id");
	if (mysql_num_rows($res_check)==0) {
		mysql_query("INSERT INTO 
						`sent_mail` 
						(`incomming_call_id`, `user_id`, `date`, `address`, `cc_address`, `bcc_address`, `subject`, `body`, `status`, `actived`) 
						VALUES 
						('$incomming_call_id', '1', NOW(), '$address', '$cc_address', '$bcc_address', '$subject', '$body', '2', '1');");
	}else{
	mysql_query("UPDATE `sent_mail`
			 	 SET 	
			 	 		`user_id`='$user', 
						`date`=NOW(), 
						`address`='$address', 
						`cc_address`='$cc_address', 
						`bcc_address`='$bcc_address', 
						`subject`='$subject', 
						`body`='$body', 
						`status`='2'
				WHERE 	`id`=$sent_mail_id;
	");
	
	}
}
echo json_encode($data);
