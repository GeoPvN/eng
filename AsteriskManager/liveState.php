<?php


require_once '../includes/classes/core.php';
require("config.php");
require("asmanager.php");
require("realtime_functions.php");

$user           = $_SESSION['USERID'];
$group          = $_SESSION['GROUPID'];
$checkState     = $_REQUEST['checkState'];
$extensions     = array();
$inuse          = Array();
$filter_queues  = array("2022028");


$am             = new AsteriskManager();

$userFilter = '';
if ($group != 5 && $group != 1) {
    $userFilter = 'AND users.id='.$user;
}

$query = mysql_query("SELECT CONCAT('SIP/',`extension_id`) AS 'ext',
                            `user_info`.`name` AS 'name',
                            `users`.id AS user_id
                      FROM  `users`
                      JOIN  `user_info` ON `users`.`id` = `user_info`.`user_id`
                      WHERE `logged` = 1 AND `extension_id` != 0 ORDER BY `extension_id`");

while ($row = mysql_fetch_assoc($query) ){

    $extensions[$row[ext]] = $row[name];

}

$am->connect($manager_host,$manager_user,$manager_secret);
//get channels
$channels = get_channels($am);

foreach($channels as $ch=>$chv) {
    list($chan,$ses) = split("-",$ch,2);
    $inuse["$chan"]  = $ch;
}

//get queues
$queues   = get_queues($am,$channels);

$color['unavailable'] = "flesh_off.png";
$color['unknown']     = "#dadada";
$color['busy']        = "flesh_inc.png";
$color['dialout']     = "#d0303f";
$color['ringing']     = "flesh_ringing.png";
$color['not in use']  = "flesh_free.png";
$color['paused']      = "#000000";

if ($checkState==1) {

    echo '<table>
                  <thead>
                      <tr>
                        <td colspan="3" style="border-left: 1px solid #E6E6E6;border-right: 1px solid #E6E6E6;">Phone Station</td>
                      </tr>
                      <tr class="tb_head" style="border: 1px solid #E6E6E6;">
            	        <td style="width:75px">Extension</td>
                        <td style="width:50px">Status</td>
                      </tr>
                  </thead>
              <tbody>';

}else{

    echo '<table>
                  <thead>
                      <tr>
                        <td colspan="7" style="border-left: 1px solid #E6E6E6;border-right: 1px solid #E6E6E6;">Phone Station</td>
                      </tr>
                      <tr class="tb_head" style="border: 1px solid #E6E6E6;">
            	        <td style="width:75px">Queue</td>
                        <td style="width:75px">Extension</td>
                        <td style="width:35%">Worker</td>
                        <td style="width:50px">Status</td>
                        <td style="width:35%">Number</td>
                        <td style="width:50px">Time</td>
                        <td style="width:30%">Name</td>
                      </tr>
                  </thead>
              <tbody>';

}

foreach($filter_queues as $qn) {

    foreach ($extensions as $key=>$name){

        $stat     = "";
        $last     = "";
        $dur      = "";
        $clid     = "";

        $akey     = $queues[$qn]['members'][$key]['agent'];
        $aname    = $queues[$qn]['members'][$key]['name'];
        $status   = $queues[$qn]['members'][$key]['type'];
        $aval     = $queues[$qn]['members'][$key]['type'];

        if ($status != 'unavailable') {
             
            if(array_key_exists($key,$inuse)) {

                if($status=="not in use") {
                    	
                    $status = "dialout";

                }

                if($channels[$inuse[$key]]['duration']=='') {
                    	
                    $newkey = $channels[$inuse[$key]]['bridgedto'];
                    $dur    = $channels[$newkey]['duration_str'];
                    $clid   = $channels[$newkey]['callerid'];

                } else {
                    	
                    $newkey = $channels[$inuse[$key]]['bridgedto'];
                    $clid   = $channels[$newkey]['callerid'];
                    $dur    = $channels[$inuse[$key]]['duration_str'];

                }
                 
            }

            $stat = $queues[$qn]['members'][$key]['status'];
            $last = $queues[$qn]['members'][$key]['lastcall'];

            if ($checkState == 1) {
                echo "<tr style=\"border: 1px solid #E6E6E6;\">
                        <td style=\"font-size: 11px;\">$aname</td>
                        <td class='td_center'><img alt='inner' src=\"media/images/icons/$color[$aval]\" height='14' width='14'></td>
                     </tr>";
            }else {
                echo "<tr style=\"border: 1px solid #E6E6E6;\">
                        <td style=\"font-size: 11px;\">$qn</td>
                        <td style=\"font-size: 11px;\">$aname</td>";
            }

            if($stat<>"") {
                $aval="paused";
            }

            if(!array_key_exists($key,$inuse)) {
                if($status=="busy"){
                    $aval="not in use";
                }
            }

            $aval2        = ereg_replace(" ","_",$status);
            $mystringaval = $lang[$language][$aval2];

            if($mystringaval=="") {
                $mystringaval = $status;
            }

            $result_name = mysql_fetch_assoc(mysql_query("SELECT `name`
                                                          FROM `caller_history`
                                                          WHERE `name` != '' AND phone = '$clid'
                                                          LIMIT 1"));

            $clid_name=$result_name[check_name];

            if ($checkState != 1) {
                echo "<td style=\"font-size: 11px;\">$name</td>
                        <td class='td_center'><img alt='inner' src=\"media/images/icons/$color[$aval]\" height='14' width='14'></td>
                        <td style='cursor: pointer; font-size: 11px;' id='cid' class='open_dialog' extention='$aname' number='$clid'>$clid</td>
                        <td style=\"font-size: 11px;\">$dur</td>
                        <td style=\"color: rgb(244, 56, 54); font-size: 12px;\">$clid_name</td>
                     </tr>";
            }
        }

    }
}



//QUEUE details
	
foreach($filter_queues as $qn) {
    $position  = 1;
    foreach($queues[$qn]['calls'] as $key=>$val) {

        if ($position == 1) {
            if ($checkState == 1) {
                echo '<table>
                         <thead>
                             <tr>
                                 <td colspan="2" style="border-left: 1px solid #E6E6E6;border-right: 1px solid #E6E6E6;"></td>
                             </tr>
        			         <tr>
                                 <td colspan="2" style="border-left: 1px solid #E6E6E6;border-right: 1px solid #E6E6E6;">The number of calls</td>
                             </tr>
        			         <tr class="tb_head" style="border: 1px solid #E6E6E6;">
                    			 <td>Position</th>
                    			 <td>Number</td>
        	                 </tr>
                         </thead>
                    <tbody>';
            }else{
                echo '<table>
                         <thead>
                             <tr>
                                 <td colspan="7" style="border-left: 1px solid #E6E6E6;border-right: 1px solid #E6E6E6;"></td>
                             </tr>
        			         <tr>
                                 <td colspan="7" style="border-left: 1px solid #E6E6E6;border-right: 1px solid #E6E6E6;">The number of calls</td>
                             </tr>
        			         <tr class="tb_head" style="border: 1px solid #E6E6E6;">
                    			 <td>Queue</td>
                    			 <td>Position</th>
                    			 <td>Number</td>
        	                     <td colspan="3">Name</td>
                    			 <td>Wait Time</td>
                			 </tr>
                         </thead>
                <tbody>';
                 
            }
             
        }
         
        $callerNumber = $queues[$qn]['calls'][$key]['chaninfo']['callerid'];

        $result_name = mysql_fetch_assoc(mysql_query("SELECT `name`
                                                      FROM `caller_history`
                                                      WHERE `name` != '' AND phone = '$callerNumber'
                                                      LIMIT 1"));


        $callername = $result[check_name];
        $pixel      = '15px';

        if ($checkState == 1) {
            echo "<tr>
                    <td>$position</td>
                    <td>$callerNumber</td>
    		      </tr>";
        }else{
            echo "<tr>
                    <td>$qn</td>
                    <td>$position</td>
                    <td>$callerNumber</td>
                    <td colspan=\"3\" style=\"color: rgb(244, 56, 54);\">$callername</td>
                    <td>".$queues[$qn]['calls'][$key]['chaninfo']['duration_str']." Minutes </td>
                 </tr>";
        }

        $position++;

    }
    	
}
echo "</tbody>
      </table>";
?>

