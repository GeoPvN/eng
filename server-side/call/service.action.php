<?php
require_once('../../includes/classes/core.php');

if(file_get_contents('http://192.168.0.139:8080/api/bill/getCustInfo.php?custNumber='.$_REQUEST[ab_num]) !== false){
    $file = file_get_contents('http://192.168.0.139:8080/api/bill/getCustInfo.php?custNumber='.$_REQUEST[ab_num]);
    $fileData = json_decode($file);
    $cl_addres =  $fileData->address;
    $cl_debt = $fileData->balance;
    $cl_ab_num = $fileData->custNumber;
    $cl_ab = $fileData->custName;
    $user_id = $_SESSION['USERID'];
    mysql_query("INSERT INTO `service_request_log`
                (`cl_addres`, `cl_debt`, `cl_ab`, `cl_ab_num`, `user_id`, `date`, `send_num`)
                VALUES
                ('$cl_addres', '$cl_debt', '$cl_ab', '$cl_ab_num', '$user_id', NOW(), '$_REQUEST[ab_num]');");
    echo file_get_contents('http://192.168.0.139:8080/api/bill/getCustInfo.php?custNumber='.$_REQUEST[ab_num]);
}else{
    $user_id = $_SESSION['USERID'];
    mysql_query("INSERT INTO `service_request_log`
                    (`cl_addres`, `cl_debt`, `cl_ab`, `cl_ab_num`, `user_id`, `date`, `send_num`)
                VALUES
                    ('', '', '', '', '$user_id', NOW(), '$_REQUEST[ab_num]');");
    
    $data['error'] = 'error';
    echo json_encode($data);
}

?>