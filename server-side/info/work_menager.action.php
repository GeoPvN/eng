<?php
require_once ('../../includes/classes/core.php'); 
$action 	= $_REQUEST['act'];
$user_id    = $_SESSION['USERID'];
$error		= '';
$data		= '';
switch ($action) {
    case 'get_list_hist' :
        $count	= $_REQUEST['count'];
        $hidden	= $_REQUEST['hidden'];
        	
        $rResult = mysql_query("SELECT  work_real_log.id,
                                        work_real_log.add_date,
                                        IF(work_real_log.checker = 1,'დაემატა','განახლდა'),
                                        user_info.`name`,
                                        old_w.`name`,
                                        new_w.`name`
                                FROM    `work_real_log`
                                JOIN	user_info ON work_real_log.change_user_id = user_info.user_id
                                JOIN    work_shift AS new_w ON work_real_log.work_shift_id = new_w.id
                                JOIN    work_shift AS old_w ON work_real_log.old_work_shift_id = old_w.id
                                WHERE   work_real_log.work_real_id = $_REQUEST[work_real_id]");
        
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
    case 'get_table' :
        $project_id = $_REQUEST['project_id'];
        $year_month = $_REQUEST['year_month'];
        $ym         = explode("-",$year_month);
        $operator   = "<option value=\"0\">----</option>";
        $cycle      = "<option value=\"0\">----</option>";
        $month_day  = cal_days_in_month(CAL_GREGORIAN, $ym[1], $ym[0]);
        $month      = $ym[1];
        $year       = $ym[0];
        $tr_numb    = 0;
        $h          = 0;
        $f          = 1;
        $del_start  = "$year-$month-01";
        $del_end    = "$year-$month-$month_day";
        if($_REQUEST['add_new_line'] != ''){
            $new_line   = $_REQUEST['add_new_line'];
        }else{
            $new_line   = 0;
        }
        
        $total_houre = mysql_fetch_assoc(mysql_query("  SELECT ROUND(SUM(HOUR(TIMEDIFF(end_time,start_time))/7)) AS `hour`
                                                        FROM `week_day_graphic`
                                                        WHERE project_id = $project_id;"));
        
        $res = mysql_query("    SELECT ROUND((TIME_FORMAT( SEC_TO_TIME(  TIME_TO_SEC( TIMEDIFF(  SUM( `end_time` ), SUM(  `start_time`  ) ) ) ),'%H,%i') / 40)) AS `OPER_NEED`
                                FROM `week_day_graphic`
                                WHERE week_day_graphic.project_id = $project_id AND week_day_graphic.actived = 1
	                            GROUP BY week_day_graphic.cycle");
        
        $get_user = mysql_query("   SELECT  `users`.`id`,
                    				        `user_info`.`name`
                                    FROM    `users`
                                    JOIN    `user_info` ON user_info.user_id = users.id
                                    WHERE   `users`.`actived` = 1");
         
        $get_cycle = mysql_query("  SELECT work_cycle.id,work_cycle.`name`,GROUP_CONCAT(work_cycle_detail.work_shift_id ORDER BY work_cycle_detail.num ASC) AS `shift_id`
                                    FROM `work_cycle`
                                    JOIN work_cycle_detail ON work_cycle.id = work_cycle_detail.work_cycle_id
                                    WHERE work_cycle.project_id = $project_id AND work_cycle.actived = 1
                                    GROUP BY work_cycle_detail.work_cycle_id
                                    ORDER BY work_cycle_detail.work_cycle_id,work_cycle_detail.num ASC");
         
        $get_shift = mysql_query("  SELECT id,`name`,color
                    			    FROM `work_shift`
                    			    WHERE project_id = $project_id AND actived = 1");
        
        function check_holyday($date){
            $req = mysql_num_rows(mysql_query(" SELECT holidays.`name`, DATE(holidays.date)
                                                FROM `project`
                                                JOIN project_holiday ON project.id = project_holiday.project_id AND project_holiday.actived = 1
                                                JOIN holidays ON project_holiday.holidays_id = holidays.id AND holidays.actived = 1
                                                WHERE project.id = $_REQUEST[project_id] AND DATE(holidays.date) = '$date'"));
            return $req;
        }
        
        function check_real($date,$rigi,$project_id){
            $ch_my_br = mysql_num_rows(mysql_query("SELECT  work_real_break.end_break AS `hour`
                                                    FROM    `work_real`
                                                    JOIN    work_real_break ON work_real.id = work_real_break.work_real_id AND work_real_break.actived = 1
                                                    WHERE   DATE(date) = '$date' AND rigi_num = '$rigi' AND `work_real`.project_id = $project_id
                                                    LIMIT 1"));
            if($ch_my_br > 0){
                $req = mysql_fetch_array(mysql_query("  SELECT  work_shift.id,
                                                        color,
                                                        work_shift.`name`,
                                                        ((TIME_TO_SEC(work_shift.end_date) - TIME_TO_SEC(work_shift.start_date)) - IF(ISNULL(work_real_break.end_break),0,(SUM((TIME_TO_SEC(work_real_break.end_break) - TIME_TO_SEC(work_real_break.start_break)))))) AS `hour`,
                                                        if(work_shift.start_date > work_shift.end_date,
                                                        ((TIME_TO_SEC('24:00') - TIME_TO_SEC(work_shift.start_date)) - IF(ISNULL(work_real_break.end_break),0,(SUM((TIME_TO_SEC(work_real_break.end_break) - TIME_TO_SEC(work_real_break.start_break))))))
                                                        ,
                                                        0
                                                        ) AS `start_half`,
                                                        if(work_shift.start_date > work_shift.end_date,
                                                        ((TIME_TO_SEC(work_shift.end_date) - TIME_TO_SEC('00:00'))   - IF(ISNULL(work_real_break.end_break),0,(SUM((TIME_TO_SEC(work_real_break.end_break) - TIME_TO_SEC(work_real_break.start_break))))))
                                                        ,
                                                        0
                                                        ) AS `end_half`,work_real.id
                                                        FROM    `work_real`
                                                        left JOIN    work_real_break ON work_real.id = work_real_break.work_real_id AND work_real_break.actived = 1
                                                        JOIN    work_shift ON work_real.work_shift_id = work_shift.id AND work_shift.actived = 1
                                                        WHERE   DATE(date) = '$date' AND rigi_num = '$rigi' AND `work_real`.project_id = $project_id"));
            }else{
                $req = mysql_fetch_array(mysql_query("  SELECT  work_shift.id,
                                                        color,
                                                        work_shift.`name`,
                                                        ((TIME_TO_SEC(work_shift.end_date) - TIME_TO_SEC(work_shift.start_date)) - TIME_TO_SEC(work_shift.timeout)) AS `hour`,
                                                        if(work_shift.start_date > work_shift.end_date,
                                                        ((TIME_TO_SEC('24:00') - TIME_TO_SEC(work_shift.start_date)) - TIME_TO_SEC(work_shift.timeout))
                                                        ,
                                                        0
                                                        ) AS `start_half`,
                                                        if(work_shift.start_date > work_shift.end_date,
                                                        ((TIME_TO_SEC(work_shift.end_date) - TIME_TO_SEC('00:00'))   - TIME_TO_SEC(work_shift.timeout))
                                                        ,
                                                        0
                                                        ) AS `end_half`,work_real.id
                                                        FROM    `work_real`
                                                        JOIN    work_shift ON work_real.work_shift_id = work_shift.id AND work_shift.actived = 1
                                                        WHERE   DATE(date) = '$date' AND rigi_num = '$rigi' AND `work_real`.project_id = $project_id"));
            }
            return $req;
        }
        
        function check_user_cycle($rigi,$project_id,$date){
            $req = mysql_fetch_array(mysql_query("  SELECT work_real.user_id,work_real.work_cycle_id
                                                    FROM `work_real`
                                                    JOIN work_shift ON work_real.work_shift_id = work_shift.id AND work_shift.actived = 1
                                                    WHERE  rigi_num = '$rigi' AND `work_real`.project_id = $project_id AND DATE(date) = '$date'
                                                    LIMIT 1"));
            return $req;
        }
         
        while ($req_user = mysql_fetch_array($get_user)){
            $operator .= "<option value=\"$req_user[0]\">$req_user[1]</option>";
        }
         
        while ($req_cycle = mysql_fetch_array($get_cycle)){
            $cycle .= "<option shift=\"$req_cycle[2]\" value=\"$req_cycle[0]\">$req_cycle[1]</option>";
        }
        
        while ($req = mysql_fetch_array($res)){
            $h++;
            $marcxena .= "<tr>
            <td rowspan=$req[0] style=\"text-align: center;vertical-align: middle;\">$h</td>";
            for ($i = 1;$i <= $req[0];$i++){
                if($i > 1){
                    $marcxena .= "<tr>";
                }
                
                $marcxena .= "<td style=\"height: 25px;\"><select id=\"user_id_$f\" user_num=\"rigi$f\" style=\"width: 170px;\">$operator</select></td>
                <td style=\"height: 25px;\"><select class=\"cycle\" id=\"$f\" user_num=\"rigi$f\" style=\"width: 170px;\">$cycle</select></td>
                <td style=\"height: 25px;vertical-align: middle;text-align: center;\"><div onclick=\"deletecycle('$f','$del_start','$del_end','$project_id')\" style=\"width: 10px;display: inline;text-align: center;cursor:pointer;\">X</div></td>
                </tr>";
                $f++;
            }
            $marcxena .= "<tr>";
            $tr_numb += $req[0];
        }
        $marcxena .= "<tr><td rowspan=1 style=\"text-align: center;vertical-align: middle;\">".($h+1)."</td>
        <td style=\"height: 25px;\"><select id=\"user_id_$f\" user_num=\"rigi$f\" style=\"width: 170px;\">$operator</select></td>
        <td style=\"height: 25px;\"><select class=\"cycle\" id=\"$f\" user_num=\"rigi$f\" style=\"width: 170px;\">$cycle</select></td>
        <td style=\"height: 25px;vertical-align: middle;text-align: center;\"><div onclick=\"deletecycle('$f','$del_start','$del_end','$project_id')\" style=\"width: 10px;display: inline;text-align: center;cursor:pointer;\">X</div></td>
        </tr>";
        $hj = 1;
        for ($mn = 0;$mn < $new_line;$mn++){
            $marcxena .= "<tr><td rowspan=1 style=\"text-align: center;vertical-align: middle;\">".(($h+1)+$hj)."</td>
            <td style=\"height: 25px;\"><select id=\"user_id_".($f+$hj)."\" user_num=\"rigi".($f+$hj)."\" style=\"width: 170px;\">$operator</select></td>
            <td style=\"height: 25px;\"><select class=\"cycle\" id=\"".($f+$hj)."\" user_num=\"rigi".($f+$hj)."\" style=\"width: 170px;\">$cycle</select></td>
            <td style=\"height: 25px;vertical-align: middle;text-align: center;\"><div onclick=\"deletecycle('".($f+$hj)."','$del_start','$del_end','$project_id')\" style=\"width: 10px;display: inline;text-align: center;cursor:pointer;\">X</div></td>
            </tr>";
            $hj++;
        }
        $marcxena .= "<tr>";

        for($i = 1;$i <= $month_day;$i++){
            $zeda .= "<td style=\"width: 50px;\" onclick=\"openhour('$year-$month-$i','$i/$month/$year')\">$i/$month/$year</td>";
            
            $my_res = mysql_query(" SELECT  
                                            ROUND((((SUM(TIME_TO_SEC(end_break))-SUM(TIME_TO_SEC(start_break)))) * 100 / TIME_TO_SEC(work_shift.timeout)),2) AS `gay`
                                    FROM `work_real`
                                    LEFT JOIN work_real_break ON work_real.id = work_real_break.work_real_id AND work_real_break.actived = 1
                                    JOIN work_shift ON work_real.work_shift_id = work_shift.id AND work_shift.actived = 1
                                    WHERE DATE(work_real.date) = '$year-$month-$i' AND work_real.project_id = $project_id AND work_shift.start_date != 0
                                    GROUP BY work_real.id
                                    ");
            $cc = 0;
            $total_pr = 0;
            while ($my_req = mysql_fetch_assoc($my_res)){
                $cc++;
                $total_pr+=$my_req[gay];
            }
            $last_total = round($total_pr * 100 / intval($cc.'00'));
            $zeda_pr .= '<td style=\"width: 50px;\" >
                         <div class="progress" style="margin: auto;width: 90%;background-color: #f5f5f5;border-radius: 4px;overflow: hidden;">
                            <div style="width:'.$last_total.'%;background-color: green;text-align: center;">
                                '.$last_total.'%
                            </div>
                         </div></td>';
            
            $shua_faq .= '<td class="qveda_dge_'.$i.'" style="width: 50px;">0</td>';
            $shua_geg .= '<td class="qveda_dge_geg_'.$i.'" style="width: 50px;">'.$total_houre[hour].':00</td>';
            $shua_sxvaoba .= '<td class="qveda_dge_sx_'.$i.'" style="width: 50px;"></td>';
        }
        
        if($new_line == 0){
            $new_line1 = 1;
        }else{
            $new_line1 = ($new_line+1);
        }

        for ($g = 1;$g <= ($tr_numb+$new_line1);$g++){
            $qveda .= "<tr>";
            $vrtikal = 'vertikal="'.$g.'"';
            for($i = 1;$i <= $month_day;$i++){
                $horizontal = 'horizontal="'.$i.'"';
                $week_day = "$i-$month-$year";
                if(check_holyday("$year-$month-$i") == 1){
                    $background = 'red';
                    $name_r = '';
                    $hour = 0;
                    $wsi = 0;
                    $hco = 0;
                }else{
                    $background = "none";
                    $rr = check_real("$year-$month-$i","rigi".$g,$project_id);
                    $hour = 0;
                    if($rr[1] != ''){
                        $background = $rr[1];
                        $name_r = $rr[2];
                        if($rr[4]== 0){
                            $hour = $rr[3];//round(((abs($rr[3]) / 60) / 60),2);
                        }else{
                            $hour = $rr[4];//round(((abs($rr[4]) / 60) / 60),2);
                            $hour1 = $rr[5];//round(((abs($rr[5]) / 60) / 60),2);
                            $hco = $i+1;
                        }
                        $wsi = $rr[0];
                    }
                    
                }
                if($hco == $i && $rr[1] != ''){
                    $hour = $hour1;
                    $tarigi1 = "$i/$month/$year";
                    $hco = $i;
                }
                
                $check_update = mysql_num_rows(mysql_query("SELECT id FROM `work_real_log` WHERE checker = 2 AND work_real_id = $rr[6]"));
                
                if($check_update == 0){
                    $update_class = '';
                }else{
                    $update_class = 'comment1';
                }
                    
                $qveda .= '<td class="'.$update_class.'" '.$vrtikal.' '.$horizontal.' hour="'.$hour.'" work_shift_id="'.$wsi.'" tarigi1="'.$tarigi1.'" tarigi_back="'.$year.'-'.$month.'-'.($i-1).'" tarigi="'."$year-$month-$i".'" holy="'.$background.'" rigi_num="rigi'.$g.'" style="height: 25px;background: '.$background.';" onclick="opendialog('.$wsi.',\''.$background.'\',\''."$year-$month-$i".'\',\'rigi'.$g.'\',\''.$rr[6].'\')">'.$name_r.'</td>';
                $rr = '';
                $name_r = '';
                $hour = '';
                $tarigi1 = '';
                $wsi = '';
                $rrr = check_user_cycle('rigi'.$g,$project_id,"$year-$month-$i");
                if(!empty($rrr[user_id])){
                    $test .= "<script>$(\"#$g option[value='".$rrr[1]."']\").prop('selected', true);$(\"#user_id_$g option[value='".$rrr[0]."']\").prop('selected', true);</script>";
                }
            }
            
            $qveda .= "</tr>";
            $marjvena .= "<tr>
                			<td class=\"total_$g\" style=\"height: 25px;\">0</td>
                			</tr>";
        }

        
        while ($req_shift = mysql_fetch_array($get_shift)){
            
            $shift .= "<tr><td style=\"background: $req_shift[2]\">$req_shift[1]</td></tr>";
            $shua='';
            
            for($d = 1;$d <= $month_day;$d++){
                
                $gg = mysql_fetch_array(mysql_query("   SELECT  COUNT(*) AS `count`
                                                        FROM    `work_real`
                                                        JOIN    work_shift ON work_real.work_shift_id = work_shift.id AND work_shift.actived = 1
                                                        
                                                        WHERE   `work_real`.project_id = $project_id AND color = '$req_shift[2]' AND DATE(work_real.date) = '$year-$month-$d'"));
                
                $shua .= '<td style="width: 50px;background: '.$req_shift[2].';">'.$gg[0].'</td>';
            }
            $cvla_op .= "<tr>$shua</tr>";
        }
        $data['day'] = $month_day;
        $data['num'] = $g-1;
        $data['ttt'] = $test;
        $data['table'] = '<table style="width: 1190px;">
    			<tr>
        			<td style="width: 350px;padding-right: 2px;">
            			<table id="pirveli">
            			<tr><td colspan=4 style="border: none;height: 30px;"><div style="display:inline;cursor:pointer;" value="'.$new_line.'" id="add_new_line">დამატება</div><div style="display:inline;margin-left:10px;cursor:pointer;" id="del_new_line">წაშლა</div></td></tr>
            			<tr><td>სადგ.</td><td>ოპერატორი</td><td>ციკლი</td><td>X</td></tr>
            			'.$marcxena.'
            			</table>
            			<table id="qveda_pirveli" style="margin-top: 7px;">
            			<tr>
            			<td style="height: 14px;">გეგმიური სთ/დღე</td>
            			</tr>
            			<tr>
            			<td>საოპერაციო სთ/დღე</td>
            			</tr>
            			<tr>
            			<td>სხვაობა</td>
            			</tr>
            			</table>
            			<table id="qveda_pirveli1" style="margin-top: 5px;">
            			<tr>
            			<td>პროექტის ცვლები</td>
            			</tr>
            			'.$shift.'
            			</table>
        			</td>
        			<td id="ParentContainer" style="width: 700px; overflow: auto; display: block;margin-left: 4px;">
            			<table id="meore">
            			<tr><td colspan='.$month_day.' ><div id="FixedDiv" style="margin-left: 310px;">'.$year.'-'.$month.'</div></td></tr>
            			<tr>
            			'.$zeda.'
            			</tr>
            			<tr>
            			'.$zeda_pr.'
            			</tr>
            			'.$qveda.'
            			<tbody style="border-top: 8px solid #fff;">
            			<tr style="margin-top: 5px;">
            			'.$shua_faq.'
            			</tr>
            			<tr>
            			'.$shua_geg.'
            			</tr>
            			<tr>
            			'.$shua_sxvaoba.'
            			</tr>
            			</tbody>
            			<tbody style="border-top: 8px solid #fff;">
            			<tr style="margin-top: 5px;">
            			<td colspan='.$month_day.' style="text-align: center;">ოპ. რაოდენობა</td>
            			</tr>
            			'.$cvla_op.'
            			</tbody>
            			</table>
        			</td>
        			<td style="width: 80px;">
            			<table id="mesame">
            			<tr>
            			<td style="height: 46px;">სულ სთ/თვე</td>
            			</tr>
            			'.$marjvena.'
            			</table>
        			</td>
    			</tr>
			</table>';
        break;
    case 'get_project' :
        $option = "<option value=\"0\">----</option>";
        $res = mysql_query("SELECT  `id`,
            				`name`
                    FROM `project`
                    WHERE actived = 1");
        
        while ($req = mysql_fetch_array($res)){
            $option .= "<option value=\"$req[0]\">$req[1]</option>";
        }
        $data['project'] = $option;
        break;
    case 'get_shift' :
        $work_shift = $_REQUEST[work_shift];
        $option     = "<option value=\"0\">----</option>";
        $res = mysql_query("SELECT  `id`,
                                    `name`
                            FROM `work_shift`
                            WHERE actived = 1 AND project_id = $_REQUEST[project_id]");
    
        while ($req = mysql_fetch_array($res)){
            if($work_shift == $req[0]){
                $option .= "<option value=\"$req[0]\" selected>$req[1]</option>";
            }else{
                $option .= "<option value=\"$req[0]\">$req[1]</option>";
            }
        }
        $data['shift'] = $option;
        break;
    case 'get_cycle_start_date' :
        $year_month = $_REQUEST['year_month'];
        //echo $year_month;
        $ym         = explode("-",$year_month);
        $month_day  = cal_days_in_month(CAL_GREGORIAN, $ym[1], $ym[0]);
        $month      = $ym[1];
        $year       = $ym[0];
        $option = "";
        
        for($i=1;$i <= $month_day;$i++){
            $option .= "<option value=\"$i\">$year-$month-$i</option>";
        }
        $data['cycle_start_date'] = $option;
        break;
    case 'add_real' :
        $insert = rtrim($_REQUEST['insert'],",");

        mysql_query("INSERT INTO `work_real`
                     (`user_id`, `date`, `work_shift_id`, `rigi_num`, `project_id`, `work_cycle_id`,`change_user_id`)
                     VALUES
                     $insert;");

        break;
    case 'add_update_shift' :
        $date = $_REQUEST['date'];
        $project_id = $_REQUEST['project_id'];
        $work_shift = $_REQUEST['work_shift'];
        $shift_id   = $_REQUEST['shift_id'];
        $rigi_num   = $_REQUEST['rigi_num'];
    
        mysql_query("UPDATE `work_real` SET
                            `change_user_id`='$_SESSION[USERID]',
                            `work_shift_id`='$shift_id'
                     WHERE  `project_id`='$project_id'
                     AND DATE(date) = '$date'
                     AND work_shift_id = '$work_shift'
                     AND rigi_num = '$rigi_num';");
    
        break;
    case 'add_user_break' :
        $r_id           = $_REQUEST['r_id'];
        $b_id           = $_REQUEST['b_id'];
        $start_break    = $_REQUEST['start_break'];
        $end_break      = $_REQUEST['end_break'];
        $work_activities_id = $_REQUEST['work_activities_id'];
        $sty = explode(':',$start_break);
        $ety = explode(':',$end_break);
        $st = intval($sty[0].$sty[1]);
        $et = intval($ety[0].$ety[1]);
        $checker_date = mysql_fetch_assoc(mysql_query(" SELECT TIME_FORMAT(work_shift.start_date,'%H%i') AS start_date,TIME_FORMAT(work_shift.end_date,'%H%i') AS end_date
                                                        FROM `work_real`
                                                        JOIN work_shift ON work_real.work_shift_id = work_shift.id AND work_shift.actived = 1
                                                        WHERE work_real.id = '$r_id' OR work_real.id = '$b_id'"));
        $stt = intval($checker_date[start_date]);
        $ett = intval($checker_date[end_date]);
        if($st > $stt && $et < $ett){
            if($st < $et){
                if($b_id == ''){
                    mysql_query("INSERT INTO `work_real_break` (`work_real_id`, `start_break`, `end_break`, `work_activities_id`) VALUES ('$r_id', '$start_break', '$end_break', '$work_activities_id');");
                }else{
                    mysql_query("UPDATE `work_real_break` SET `start_break`='$start_break', `end_break`='$end_break', `work_activities_id`='$work_activities_id' WHERE `id`='$b_id';");
                }
            }else{
                $error = 1;
            }
        }else{
            $error = 1;
        }
        break;
    case 'disable' :
        $id = $_REQUEST['id'];
        mysql_query("UPDATE `work_real_break` SET `actived`='0' WHERE `id`='$id';");
        break;
    case 'get_user_break' :
        $work_real_id       = $_REQUEST['work_real_id'];
        $work_real_break_id = $_REQUEST['work_real_break_id'];
        
        $data['break'] = '<div id="dialog-form">
                            <fieldset>
                            <legend>საათების მიხედვით</legend>
                            <div style="margin: 10px 0;">
                                <button id="add_button_user">დამატება</button>
                                <button id="delete_button_user">წაშლა</button>
                            </div>
                            <table class="display" id="table_index" style="width: 100%;">
                                <thead>
                                    <tr id="datatable_header">
                                        <th>ID</th>
                                        <th style="width: 45%;">დასახელება</th>
                                        <th style="width: 25%;">დასაწყისი</th>
                                        <th style="width: 25%;">დასასრული</th>
                                        <th class="check" style="width: 30px;">&nbsp;</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr class="search_header">
                                        <th class="colum_hidden">
                                    	   <input type="text" name="search_id" value="ფილტრი" class="search_init" />
                                        </th>
                                        <th>
                                        	<input type="text" name="search_number" value="ფილტრი" class="search_init" />
                                        </th>
                                        <th>
                                            <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                                        </th>    
                                        <th>
                                            <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                                        </th>
                                        <th style="border-right: 1px solid #E6E6E6 !important;">
                                        	<div class="callapp_checkbox">
                                                <input type="checkbox" id="check-all-index" name="check-all-index" />
                                                <label for="check-all-index"></label>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                            <input type="hidden" id="r_id" value="'.$work_real_id.'">
                            </fieldset>
                          </div>';
        break;
    case 'get_add_page' :
        $data['page'] = get_page('');
        break;
    case 'get_edit_page' :
        $work_real_break_id = $_REQUEST['id'];
        $data['page'] = get_page(get_page_sql($work_real_break_id));
        break;
    case 'delete_cycle' :
        $project_id     = $_REQUEST['project_id'];
        $user_id        = $_REQUEST['user_id'];
        $work_cycle_id  = $_REQUEST['cycle_id'];
        $rigi_num       = $_REQUEST['rigi'];
        $start          = $_REQUEST['start'];
        $end            = $_REQUEST['end'];
        mysql_query("   DELETE FROM `work_real`
                        WHERE project_id = '$project_id'
                        AND work_cycle_id = '$work_cycle_id'
                        AND rigi_num = '$rigi_num'
                        AND DATE(date) >= '$start' AND DATE(date) <= '$end'");
        break;
    case 'get_index' :
        $count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
		$work_real_id = $_REQUEST['work_real_id'];
		 
		$rResult = mysql_query("SELECT  work_real_break.id,
		                                work_activities.`name`,
		                                TIME_FORMAT(work_real_break.start_break,'%H:%i') AS `start`,
                            			TIME_FORMAT(work_real_break.end_break,'%H:%i') AS `end`
                                FROM `work_real_break`
                                JOIN work_activities ON work_real_break.work_activities_id = work_activities.id AND work_activities.actived = 1
                                WHERE work_real_id = $work_real_id AND work_real_break.actived = 1");

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
					$row[] = '<div class="callapp_checkbox">
                                  <input type="checkbox" id="callapp_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                                  <label for="callapp_checkbox_'.$aRow[$hidden].'"></label>
                              </div>';
				}
			}
			$data['aaData'][] = $row;
		}
        break;
    case 'checker' :
        $project_id = $_REQUEST['project_id'];
        $rigi = $_REQUEST['rigi'];
        $user = $_REQUEST['user'];
        $check_num = mysql_fetch_assoc(mysql_query("SELECT (num+1) AS `num`
                                                    FROM `work_real`
                                                    JOIN work_cycle ON work_real.work_cycle_id = work_cycle.id AND work_cycle.actived = 1
                                                    JOIN work_cycle_detail ON work_cycle.id = work_cycle_detail.work_cycle_id AND work_cycle_detail.work_shift_id = work_real.work_shift_id AND work_cycle_detail.actived = 1
                                                    WHERE work_real.project_id = $project_id AND work_real.user_id = $user AND work_real.rigi_num = '$rigi'
                                                    ORDER BY work_real.id DESC
                                                    LIMIT 1"));
        $data['num'] = $check_num[num];
        break;
    case 'get_24_hour' :
        $project_id = $_REQUEST['project_id'];
        $date       = $_REQUEST['date'];
        $date1      = $_REQUEST['date1'];
        $hour = "";
        $minute = "";
        $mid = "";
        $m = 1;
        $new_viwe = $_REQUEST['new_viwe'];
        
        $pr = mysql_fetch_assoc(mysql_query("   SELECT HOUR(start_time) AS `start`,
                                                HOUR(end_time) AS `end`
                                                FROM `week_day_graphic`
                                                WHERE project_id = $project_id
                                                LIMIT 1"));
        
        $start = $pr['start'];
        $end   = $pr['end'];
        if($new_viwe == 1){
            $rrrrr  = mysql_query(" SELECT work_activities.`name`
                                    FROM `work_activities`
                                    JOIN work_activities_cat ON work_activities.work_activities_cat_id = work_activities_cat.id
                                    WHERE work_activities.actived = 1 AND work_activities.id != 0 AND work_activities_cat.checker = 1 AND work_activities.project_id = $project_id");
            $hour ='';
            while ($rar = mysql_fetch_array($rrrrr)){
                $minute .= '<td style="width: 500px !important;height:11px;"></td>';
                $hour .= '<th style="width: 500px !important; border-top: 2px solid black;">'.$rar[0].'</th>';
            }
        }else{
        for ($i = $start;$i < $end;$i++){
            if(strlen($i) == 2){
                $hour .= '<th style="border-top: 2px solid black;" colspan="12">'.$i.':00</th>';
            }else{
                $hour .= '<th style="border-top: 2px solid black;" colspan="12">0'.$i.':00</th>';
            }
            for ($g = 0;$g <= 55;$g+=5){
                if(strlen($g) == 2){
                    $minute .= '<th style="width: ;">'.$g.'</th>';
                }else{
                    $minute .= '<th style="width: ;">0'.$g.'</th>';
                }
            }
        }
        }
        
        $my_res = mysql_query(" SELECT  user_info.`name`,
                                        HOUR(work_shift.start_date) AS `start`,
                                        HOUR(work_shift.end_date) AS `end`,
        								CAST(SUBSTRING_INDEX(work_real.rigi_num, 'rigi',-1) AS UNSIGNED) as num,
                                        work_real.id,
                                        IF(HOUR(work_shift.start_date) > HOUR(work_shift.end_date),TIME_FORMAT(ABS(TIMEDIFF(work_shift.start_date,'24:00')),'%H:%i'),TIME_FORMAT(ABS(TIMEDIFF(work_shift.end_date,work_shift.start_date)),'%H:%i')) AS `dif`,
                                        TIME_TO_SEC(work_shift.timeout) AS `timeout`,
                                        IF(HOUR(work_shift.start_date) > HOUR(work_shift.end_date),24,0) AS `start_half`,
				                        IF(HOUR(work_shift.start_date) > HOUR(work_shift.end_date),0,HOUR(work_shift.end_date)) AS `end_half`
                                FROM `work_real`
                                JOIN work_shift ON work_real.work_shift_id = work_shift.id
                                JOIN user_info ON work_real.user_id = user_info.user_id
                                WHERE DATE(date) = '$date' AND work_real.project_id = $project_id AND HOUR(work_shift.start_date) != 0
                                UNION ALL
                                SELECT  user_info.`name`,
                                        HOUR(work_shift.start_date) AS `start`,
                                        HOUR(work_shift.end_date) AS `end`,
        								CAST(SUBSTRING_INDEX(work_real.rigi_num, 'rigi',-1) AS UNSIGNED) as num,
                                        work_real.id,
                                        IF(HOUR(work_shift.start_date) > HOUR(work_shift.end_date),TIME_FORMAT(ABS(TIMEDIFF(work_shift.end_date,'00:00')),'%H:%i'),TIME_FORMAT(ABS(TIMEDIFF(work_shift.end_date,work_shift.start_date)),'%H:%i')) AS `dif`,
                                        TIME_TO_SEC(work_shift.timeout) AS `timeout`,
                                        IF(HOUR(work_shift.start_date) > HOUR(work_shift.end_date),24,0) AS `start_half`,
				                        IF(HOUR(work_shift.start_date) > HOUR(work_shift.end_date),0,HOUR(work_shift.end_date)) AS `end_half`
                                FROM `work_real`
                                JOIN work_shift ON work_real.work_shift_id = work_shift.id
                                JOIN user_info ON work_real.user_id = user_info.user_id
                                WHERE DATE(date) = '$date1' AND work_real.project_id = $project_id AND HOUR(work_shift.start_date) != 0
                                ORDER BY num ASC");
        $ope .= '<tr><td style="height: 11px;border-top: 2px solid black;"></td></tr><tr><td style="height: 11px;"></td></tr>';
        $times = array();
        $time_break = array();
        $time_work = array();
        $cou = 0;
        while ($my_req = mysql_fetch_assoc($my_res)){
            if($my_req[start]==0){
                
            }else{
                $cou++;
                $dif = mysql_fetch_assoc(mysql_query("  SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(end_break))-SUM(TIME_TO_SEC(start_break))),'%H:%i') AS `dif`,
                                                               ROUND((((SUM(TIME_TO_SEC(end_break))-SUM(TIME_TO_SEC(start_break)))) * 100 / $my_req[timeout]),2) AS `gay`,
                                                               TIME_FORMAT(SEC_TO_TIME((TIME_TO_SEC('$my_req[dif]')-(SUM(TIME_TO_SEC(end_break))-SUM(TIME_TO_SEC(start_break))))),'%H:%i') AS `work_time`
                                                        FROM work_real_break
                                                        WHERE work_real_id = $my_req[id] AND work_real_break.actived = 1"));
                
                $fff .= '<tr><td style="height: 12px;">'.$my_req[dif].'</td></tr>';
                $ffq .= '<tr><td style="height: 12px;">'.(($dif[dif]=='')?'00:00':$dif[dif]).'</td></tr>';
                $ffe .= '<tr><td style="height: 12px;">'.(($dif[work_time]=='')?$my_req[dif]:$dif[work_time]).'</td></tr>';
                $gay .= '<tr><td style="height: 12px;">'.$dif[gay].'</td></tr>';
                
                $gay_total += $dif[gay];
                $time_break[] = $dif[dif];
                $times[] = $my_req[dif];
                $time_work[] = (($dif[work_time]=='')?$my_req[dif]:$dif[work_time]);
                
            $color = '';
            $mid .= '<tr>';
            
            $ope .= '<tr><td style="height: 12px;white-space:nowrap;cursor:pointer;" class="user_break" work_real_id="'.$my_req[id].'" rigi="'.$m.'">'.$my_req[name].'</td></tr>';
            if($new_viwe == 1){
                $rrrrr  = mysql_query(" SELECT work_activities.id
                                        FROM `work_activities`
                                        JOIN work_activities_cat ON work_activities.work_activities_cat_id = work_activities_cat.id
                                        WHERE work_activities.actived = 1 AND work_activities.id != 0 AND work_activities_cat.checker = 1 AND work_activities.project_id = 4");
                while ($rar1 = mysql_fetch_array($rrrrr)){
                    $mama  = mysql_fetch_array(mysql_query(" SELECT CONCAT(TIME_FORMAT(start_break,'%H:%i'),' - ',TIME_FORMAT(end_break,'%H:%i')) AS `break_time`
                                                            FROM `work_activities`
                                                            LEFT JOIN work_real_break ON work_activities.id = work_real_break.work_activities_id
                                                            WHERE work_activities.id = $rar1[0] AND project_id = 4 AND work_real_id = $my_req[id] AND work_real_break.actived = 1"));
                    $mid .= '<td style="width: 500px !important;height:12px;">'.$mama[0].'</td>';
                }
                
            }else{
            for ($n = $start;$n < $end;$n++){
                $break = mysql_fetch_array(mysql_query("SELECT  MINUTE(start_break) AS `m_start`,
                                                                MINUTE(end_break) AS `m_end`,
                                                                HOUR(start_break) AS `start`,
                                                                HOUR(end_break) AS `end`,work_real_break.id,work_activities.`color`
                                                        FROM `work_real_break`
                                                        JOIN work_activities ON work_real_break.work_activities_id = work_activities.id AND work_activities.actived = 1
                                                        WHERE work_real_id = $my_req[id] AND work_real_break.actived = 1 AND (HOUR(start_break) = $n OR HOUR(end_break) = $n)"));
                $my_end = $my_req[end];
                $my_start = $my_req[start];
                if($date1 == ''){
                    if($my_req[start_half] != 0){
                       $my_end = $my_req[start_half];
                    }else{
                        $my_end = $my_req[end];
                        $my_start = $my_req[start];
                    }
                }else{
                    if($my_req[end_half] == 0){
                    $my_start = 0;
                    $my_end = $my_req[end];
                    }
                }
                if(($n >= $my_start && $n < $my_end)){
                    $color = 'green';
                }elseif($my_req[start] == 0 && $my_req[end] == 0){
                    $color = 'red';
                }else{
                    $color = '';
                }
                
                if($break[m_start] == 0 || $break[m_start] == 5){
                    $m_start = '0'.$break[m_start];
                }else{
                    $m_start = $break[m_start];
                }
                $start_g  = (int)($break[start].$m_start);
                
                if($break[m_end] == 0 || $break[m_end] == 5){
                    $m_end = '0'.$break[m_end];
                }else{
                    $m_end = $break[m_end];
                }
                $end_g  = (int)($break[end].$m_end);
                for ($j = 0;$j <= 55;$j+=5){
                    if($j == 0 || $j == 5){
                        $jj = '0'.$j;
                    }else{
                        $jj = $j;
                    }
                    $gela = (int)($n.$jj);
                    if($gela >= $start_g && $gela < $end_g){
                        $color1 = $break['color'];
                    }else{
                        $color1 = '';
                    }
                    if($color1 == ''){
                        $original_color = $color;
                    }else{
                        $original_color = $color1;
                    }
                    if(strlen($j) == 2){
                        $mid .= '<td rigi="'.$m.'" clock="'.$n.$j.'" style="background: '.$original_color.';height:12px;"></td>';
                    }else{
                        $mid .= '<td rigi="'.$m.'" clock="'.$n.'0'.$j.'" style="background: '.$original_color.';height:12px;"></td>';
                    }
                }
                
            }
            }
            $mid .= '</tr>';
            $m++;
            }
        }
        
        $gay_total_last = round($gay_total * 100 / intval($cou.'00'));
        
        $data['page'] = '<div id="dialog-form">
                            <fieldset>
                            <legend>საათების მიხედვით</legend>
                                <div>
                                    <select id="select_viwe"><option value="1" '.(($new_viwe==1)?'selected="selected"':"").'>ცხრილის სახით</option><option value="2" '.(($new_viwe==2)?'selected="selected"':"").'>გრაფიკული სახით</option></select>
                                    <table style="margin-top:10px;">
                                    <tr>
                                    <td>
                                    <table id="work_table" style="width: 150px;">
                                    '.$ope.'
                                    </table>
                                    </td>
                                    <td style="width:680px; overflow: auto; display: block;">
                                    <table id="work_table" style="width: 115%;">
                                    <tr>
                                	    '.$hour.'
                                    </tr>
                                    <tr>
                                	    '.$minute.'
                                    </tr>
                                	        '.$mid.'
                                    </table>
                                	</td>
                    	            <td>
                    	            <table id="work_table" style="width: 60px;">
                                    <tr><td style="border-top: 2px solid;">სულ სთ</td></tr>
                    	            <tr><td style="height: 11px;"></td></tr>
                                	            '.$fff.'
                                	                <tr><td style="font-weight: bold;">'.AddPlayTime($times).'</td></tr>
                                    </table>
            	                    </td>
            	                    <td>
                                	<table id="work_table" style="width: 60px;">
                                    <tr><td style="border-top: 2px solid;">დასვ. სთ</td></tr>
                    	            <tr><td style="height: 11px;"></td></tr>
                                	            '.$ffq.'
                                	                <tr><td style="font-weight: bold;">'.AddPlayTime($time_break).'</td></tr>
                                    </table>
                                	</td>
            	                    <td>
                                	<table id="work_table" style="width: 60px;">
                                    <tr><td style="border-top: 2px solid;">სამუ. სთ</td></tr>
                    	            <tr><td style="height: 11px;"></td></tr>
                                	            '.$ffe.'
                                	                <tr><td style="font-weight: bold;">'.AddPlayTime($time_work).'</td></tr>
                                    </table>           
                                    </td>
                                	<td>
                                	<table id="work_table" style="width: 70px;">
                                    <tr><td style="border-top: 2px solid;">შევსების %</td></tr>
                    	            <tr><td style="height: 11px;"></td></tr>
                                	            '.$gay.'
                                	                <tr><td style="font-weight: bold;">'.$gay_total_last.'</td></tr>
                                    </table>           
                                    </td>
                    	            </tr>
                    	            </table>
                                </div>
                            </fieldset>
                                	            <input type="hidden" value="'.$date.'" id="load_date">
                        </div>';
        
    
        break;
    case 'get_work_activities_detail' :
        $data['selector'] = get_work_activities_detail($_REQUEST['work_activities_id']);
        break;
    case 'paste_date' :
        
        $res = mysql_fetch_array(mysql_query("  SELECT TIME_FORMAT(`start`,'%H:%i'),TIME_FORMAT(`end`,'%H:%i')
                                                FROM `work_activities_detail`
                                                WHERE id = $_REQUEST[work_activities_detail_id]"));
        $data['paste']['start'] = $res[0];
        $data['paste']['end'] = $res[1];
        break;
        
    default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);

function get_page_sql($work_real_break_id){
    $res = mysql_fetch_assoc(mysql_query("  SELECT  work_real_break.id,
            		                                work_real_break.work_activities_id,
            		                                work_real_break.start_break AS `start`,
                                        			work_real_break.end_break AS `end`
                                            FROM `work_real_break`
                                            WHERE work_real_break.id = $work_real_break_id AND work_real_break.actived = 1"));
    return $res;
}

function get_work_activities($id){
    $req = mysql_query("SELECT 	work_activities.`id`,
                        work_activities.`name`
                        FROM `work_activities`
                        JOIN work_activities_cat ON work_activities.work_activities_cat_id = work_activities_cat.id
                        WHERE work_activities.actived = 1 AND work_activities_cat.checker = 1");
    
    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }
    
    return $data;
}

function get_work_activities_detail($id){
    $req = mysql_query("SELECT id,CONCAT(TIME_FORMAT(`start`,'%H:%i'),' - ',TIME_FORMAT(`end`,'%H:%i')) as `name`
                        FROM `work_activities_detail`
                        WHERE work_activities_id = $id AND actived = 1");
    
    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }
    
    return $data;
}

function get_page($res){
    $data = '<div id="dialog-form">
                            <fieldset>
                            <legend>საათების მიხედვით</legend>
                                <table>
                                    <tr>
                                        <td colspan=2>აქტივობა</td>
                                    </tr>
                                    <tr>
                                        <td colspan=2><select id="work_activities_id" style="width: 100%;">'.get_work_activities($res[work_activities_id]).'</select></td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>პერიოდი</td>
                                    </tr>
                                    <tr>
                                        <td colspan=2><select id="work_activities_detail_id" style="width: 100%;">'.get_work_activities_detail($res[work_activities_id]).'</select></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100px;">დასაწყისი</td>
                                        <td>დასასრული</td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" id="start_break" style="width: 70px;" value="'.$res[start].'"></td>
                                        <td><input type="text" id="end_break" style="width: 70px;" value="'.$res[end].'"></td>
                                    </tr>
                                </table>
                                <input type="hidden" id="b_id" value="'.$res[id].'">
                            </fieldset>
                           </div>';
    return $data;
}
function AddPlayTime($times) {

    // loop throught all the times
    foreach ($times as $time) {
        list($hour, $minute) = explode(':', $time);
        $minutes += $hour * 60;
        $minutes += $minute;
    }

    $hours = floor($minutes / 60);
    $minutes -= $hours * 60;

    // returns the time already formatted
    return sprintf('%02d:%02d', $hours, $minutes);
}
?>