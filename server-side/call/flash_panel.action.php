<?php
require_once '../../includes/classes/core.php';
$data = array();

// Queue
$res = mysql_query("SELECT  `number`
                    FROM    `queue`
                    WHERE   `actived` = 1");
                    
$data['queue'] =  '<option value="0">----</option>';
while ($req = mysql_fetch_array($res)){
    $data['queue'] .=  '<option value="'.$req[0].'">'.$req[0].'</option>';
}

// Department
$res = mysql_query("SELECT  `name`
                    FROM    `department`
                    WHERE   `actived` = 1");

$data['department'] =  '<option value="0">----</option>';
while ($req = mysql_fetch_array($res)){
    $data['department'] .=  '<option value="'.$req[0].'">'.$req[0].'</option>';
}

// Extension & User
$res = mysql_query("SELECT  `extension_id`,
                            `user_info`.`name`
                    FROM    `users`
                    JOIN    `user_info` ON `users`.`id` = `user_info`.`user_id`
                    WHERE   `users`.`actived` = 1");

$data['ext'] =  '<option value="0">----</option>';
$data['user'] =  '<option value="0">----</option>';
while ($req = mysql_fetch_array($res)){
    $data['ext'] .=  '<option value="'.$req[0].'">'.$req[0].'</option>';
    $data['user'] .=  '<option value="'.$req[1].'">'.$req[1].'</option>';
}

// State
$data['state'] =  '<option value="0">----</option>';
$data['state'] .=  '<option value="flesh_off.png">მიუწდომელი</option>';
$data['state'] .=  '<option value="flesh_inc.png">დაკავებული</option>';
$data['state'] .=  '<option value="flesh_ringing.png">რეკავს</option>';
$data['state'] .=  '<option value="flesh_free.png">თავისუფალი</option>';

echo json_encode($data);
?>