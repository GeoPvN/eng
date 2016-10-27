<?php
require_once ('../../includes/classes/core.php');
$action 	= $_REQUEST['act'];
$user_id    = $_SESSION['USERID'];
$error		= '';
$data		= '';
$work_graphic_id = '';
switch ($action) {
case 'get_list1' :
    $data3 .= '<table style="font-weight: bold; width: 400px"><tr><td><table style="width: 200px;" id="deep_table">';		    
    $dates=dateRange($_REQUEST[start], $_REQUEST[end]);	    
    $time = array('00:00', '01:00', '02:00', '03:00',  '04:00', '05:00','06:00', '07:00', '08:00','09:00','10:00','11:00','12:00','13:00','14:00',
                  '15:00', '16:00','17:00',  '18:00',  '19:00', '20:00','21:00', '22:00', '23:00');
    
      foreach ($dates as &$date){
          $result  = mysql_query("SELECT  COALESCE(p0.id,p1.id) as id1,
                                          user_info.`name` AS `name`,
                            			  users.`id`,
                                          DATE('$date') AS `date`,
                                          time(COALESCE(p0.`start`,IF(NOT ISNULL(p1.`end`),'00:00',''))) AS `start`,
                            			  time(COALESCE(p1.`end`,  IF(NOT ISNULL(p0.`start`),'24:00',''))) AS `end`,
                                          p1.wg_id
                            FROM  users
                            JOIN  user_info ON users.id = user_info.user_id
                            left JOIN    person_work_graphic AS p0  ON users.id = p0.person_id   and  p0.`status` = 1 AND 
                            						(DATE(p0.`start`)='$date') AND p0.actived=1
                            left JOIN    person_work_graphic AS p1 ON users.id = p1.person_id   and  p1.`status` = 1 AND 
                            						(DATE(p1.`end`)='$date') AND p1.actived=1
                            WHERE users.group_id != 1 AND users.actived = 1");
          
    $data1 .= '<td style="border: none;"><table style="font-weight: bold; ">';
    
    $data1 .= '<tr style="height:30px; background:#E6F2F8;">               
                    <td colspan ="24" style="text-align: center; vertical-align: middle;"> '.$date.'</td>
               </tr>';
    for ($i = 0; $i < sizeof($time); $i++) {
        
        $data1.='<td><div style="transform: rotate(270deg);margin: 12px -7px;" >' . $time[$i] . '</div></td>';
    }
    
    $data1 .= '</tr>';
    $data2='<tr style="height:70px; ">   
               </tr>'        ;
    while ( $row = mysql_fetch_array( $result ) ){
    
        $data1 .= '<tr style="height:20px">';
        
        $data2 .= '<tr style="height:20px"><td>' . $row['name'] . '</td></tr>';
    
        for ($i = 0; $i < sizeof($time); $i++) {
    
            switch ($time[$i]) {
                case $time[$i] >= substr( $row['start'],0, 5) && $time[$i] < substr( $row['end'],0, 5):
                    $data1.='<td clock="'.$time[$i].'" class="'.$row[id1].'" style="background: green; cursor:pointer; "  onclick="change_worc('.$row[id1].')"> </td>';
                    $work_graphic_id = $row['wg_id'];
                    $wo_g = mysql_query("SELECT TIME_FORMAT(start,'%H:00')
                                         FROM `work_graphic_break`
                                         WHERE wg_id = '$work_graphic_id' AND actived = 1");
                    while ($wo_r = mysql_fetch_array($wo_g)){
                        $data3 .= '<script>
                                    if($(".'.$row[id1].'[clock=\''.$time[$i].'\']").attr("clock")=="'.$wo_r[0].'"){
                                        $(".'.$row[id1].'[clock=\''.$time[$i].'\']").css("background","yellow");
                                    }
                                   </script>';
                    }
                break;
    
                         default:
                    $data1.='<td></td>';
                break;
            }
        }
    
        $data1 .= '</td>';
    
    }
    $data1 .= '</table></td>';
    
        }
        $data3 .=$data2.'</table></td></td><td style="width: 900px; overflow: auto; display: block; "><table>'. $data1.'</table></td></tr></table>';    
    
    $data['aaData'] = $data3;
     break;
case "get_edit_page":
    
	$data['page'][]=page();
	break;
	
case 'disable':
		mysql_query("UPDATE `person_work_graphic` SET `actived`='0' WHERE (`id`='$_REQUEST[graphic_id]')");
		break;
		
case 'get_add_page' :
	$data['page'][]=page1($_REQUEST[id]);
	
		break;
case 'save_dialog' :
 //---------------- save / edit ---------------   
$date   =   split(' - ',$_REQUEST[graphic_time]);
if (empty($_REQUEST[id])) {   
$qvr    =   mysql_query("SELECT * FROM `person_work_graphic`
                        WHERE person_id='$_REQUEST[user]' AND  (date(`start`)='$_REQUEST[date]' OR date(`end`)='$_REQUEST[date]') AND actived=1;");
if (mysql_num_rows($qvr)>0) {
    $error="ამ დღეს ამ მომხმარებელზე უკვე არსებობს გრაფიკი";
}
else {
mysql_query("
	INSERT INTO `person_work_graphic` (`user_id`, `start`, `end`,`person_id`,`wg_id`)
	VALUES ('$user_id', '$_REQUEST[date] $date[0]' , IF('$date[0]'<'$date[1]', '$_REQUEST[date] $date[1]',ADDTIME('$_REQUEST[date] $date[1]','24:00:00')),'$_REQUEST[user]','$_REQUEST[work_graphic_id]')
");}
}else{
   mysql_query("UPDATE  `person_work_graphic` SET 
                        `user_id`='$user_id',
                        `start` ='$_REQUEST[date] $date[0]',
                        `wg_id`='$_REQUEST[work_graphic_id]',
                        `end`   =IF('$date[0]'<'$date[1]', '$_REQUEST[date] $date[1]',ADDTIME('$_REQUEST[date] $date[1]','24:00:00'))
                WHERE (`id`='$_REQUEST[id]')");
    
}
//------------------------------------
   		break;
	default:
		$error = 'Action is Null';
}

function dateRange($start, $end){
    $dates = array($start);
    while(end($dates) < $end){

        $dates[]= date('Y-m-d', strtotime(end($dates).' +1 day'));
    }
    return $dates;
}

function getgraphic($time=''){   
    
    $rResult= mysql_query( "SELECT TIME_FORMAT(`start`,'%H:%i') AS `start`,
                    			   TIME_FORMAT(`end`,'%H:%i') AS `end`,
                                    id
                            FROM   `work_graphic`
                            where  actived=1;");

    $code.="<option selected>$time[start]  -  $time[end]</option>";    
    while ( $aRow= mysql_fetch_assoc( $rResult ) )
    {

        $code.="<option original=\"$aRow[id]\">$aRow[start]  -  $aRow[end]</option>";


    };
    return   $code;
}
function getusers($id=''){

$rResult = mysql_query( "SELECT users.id,
                                user_info.`name` AS `name`
                         FROM   users
                         JOIN   user_info ON users.id = user_info.user_id
                         WHERE  users.group_id != 1 AND users.actived = 1");
    while ( $aRow= mysql_fetch_assoc( $rResult ) )
    {
        if ($id==$aRow[id]) {
            $code.="<option value='$aRow[id]' selected>$aRow[name]</option>";
        }else{
        $code.="<option value='$aRow[id]'>$aRow[name]</option>";}

    };
    return   $code;
}
function page1($id='')
{
    $time=$date='';
    $worc     =   mysql_fetch_assoc(mysql_query("SELECT * FROM `person_work_graphic`  WHERE id='$id'"));
    
    if (empty($worc[start])) {
        $date=  date('Y-m-d');
    }else{ 
        $date=date('Y-m-d', strtotime($worc[start])); 
    $time= array( 'start' => date('H:i', strtotime ($worc[start])), 'end' => date('H:i', strtotime ($worc[end])) );    
    };
    return '
	<div id="dialog-form">
		<fieldset >
	    	<legend>ძირითადი ინფორმაცია</legend>
        <table>
          <tr>
            <th>აირჩიეთ მომხმარებელი</th>
            <th width="20px"></th>
            <th>აირჩიეთ თარიღი</th>
            <th width="20px"></th>
            <th style="text-align: left;">აირჩიეთ გრაფიკი</th>
          </tr>
          <tr>
            <td>
                <select class="idle" id="user">'.
                getusers($worc[person_id]).'  </select>
            </td>
        <td></td>
            <td><input id="date" value="'.$date.'" class="idle date"/> </td>
        <td></td>
                    <td>
                <select class="idle" id="graphic_time" > '.
                getgraphic($time)
                .'</select>
            </td>
        </tr>

        </table>



		</fieldset >
	</div>
<input type="hidden" id="id" value='.$_REQUEST[id].'>';

}


$data['error'] = $error;

echo json_encode($data);

?>