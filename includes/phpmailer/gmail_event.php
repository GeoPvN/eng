<?php

date_default_timezone_set('Etc/UTC');
header('Content-Type: text/html; charset=utf-8');

require_once('PHPMailerAutoload.php');
require_once('../../includes/classes/core.php');

$mail_event_id 	 	= $_REQUEST['mail_event_id'];

$res  = mysql_query("SELECT email_event_detail.id AS addres_id,
							mail_event.`subject`,
                            mail_event.body,
                            email_event_detail.address
                    FROM `mail_event`
                    JOIN  email_event_detail ON mail_event.id = email_event_detail.mail_event_id
                    WHERE mail_event.id = $mail_event_id AND mail_event.status = 1");

$res1  = mysql_query("SELECT concat('../../media/uploads/file/',rand_name) AS `rand_name`
                    FROM    `mail_event`
                    JOIN    mail_event_detail ON mail_event_detail.mail_event_id = mail_event.id
                    JOIN    file ON mail_event_detail.file_id = file.id
                    WHERE   mail_event.id = $mail_event_id AND status = 1");


$mail = new PHPMailer;

$mail->isSMTP();

$mail->SMTPDebug	= 0;

$mail->Host			= 'smtp.gmail.com';

$mail->Port			= 587;

$mail->SMTPSecure	= 'tls';

$mail->SMTPAuth		= true;

$mail->Username		= "salaroinfo@gmail.com";

$mail->Password		= "gsn123456";

$mail->setFrom('salaroinfo@gmail.com', 'Georgian Service Network');


while ($row = mysql_fetch_assoc($res)) {

    $mail->addAddress($row['address']);
    
    $mail->Subject = $row['subject'];
    
    $signature          = '</br></br><span style="font-style: italic;">პატივისცემით,</span><div><span style="font-style: italic;">შპს "ჯორჯიან სერვის ნეთვორკი"</span></div><div>(0322) 2 500 111</div>';
    
    $body 				= $row['body'].$signature;
    
    $mail->msgHTML($body);
    
    while ($row1 = mysql_fetch_assoc($res1)) {
    	
        $mail->addAttachment($row1['rand_name']);
        
    }
    
    if (!$mail->send()) {
    	
    	$status				= '0';
        $data 				= array("status" => $status);
        
        $user	  			= $_SESSION['USERID'];
        $c_date	  			= date('Y-m-d H:i:s');
        mysql_query("UPDATE `email_event_detail`
			        	SET `status`='0'
			        WHERE  `id`='$row[addres_id]'");
       
    }else {
    	
    	$status				= '1';
    	$data 				= array("status" => $status);
    	
    	$user	  			= $_SESSION['USERID'];
    	$c_date	  			= date('Y-m-d H:i:s');
    	
    	mysql_query("UPDATE `email_event_detail`
			    	 	SET `status`='2'
			    	 WHERE  `id`='$row[addres_id]'");
    	
    }
    
    $mail->ClearAllRecipients();

}

if ($status==1) {
	mysql_query("UPDATE `mail_event`
			 	    SET `status`='2'
			     WHERE  `id`='$mail_event_id'");
	
}else{
	mysql_query("UPDATE `mail_event`
				   SET `status`='0'
				 WHERE  `id`='$mail_event_id'");
	
}

$data		= array('status'	=> $status);
		
echo json_encode($data);
