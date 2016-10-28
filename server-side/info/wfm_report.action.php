<?php
require_once ('../../includes/classes/core.php');
$action 	= $_REQUEST['act'];
$user_id    = $_SESSION['USERID'];
$error		= '';
$data		= '';
switch ($action) {
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
        
        $res = mysql_query("    SELECT ROUND((TIME_FORMAT( SEC_TO_TIME(  TIME_TO_SEC( TIMEDIFF(  SUM( `end_time` ), SUM(  `start_time`  ) ) ) ),'%H,%i') / 40)) AS `OPER_NEED`
                                FROM `week_day_graphic`
                                WHERE week_day_graphic.project_id = $project_id AND week_day_graphic.actived = 1
	                            GROUP BY week_day_graphic.cycle");
        
        $get_user = mysql_query("   SELECT  `users`.`id`,
                    				        `user_info`.`name`
                                    FROM    `users`
                                    JOIN    `user_info` ON users.id = user_info.user_id
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
            $req = mysql_fetch_array(mysql_query("  SELECT  work_shift.id,
                                                            color,
                                                            work_shift.`name`,
                                                            HOUR(SEC_TO_TIME(TIME_TO_SEC(work_shift.end_date) - TIME_TO_SEC(work_shift.start_date) - TIME_TO_SEC(work_shift.timeout))) AS `hour`
                                                    FROM    `work_real`
                                                    JOIN    work_shift ON work_real.work_shift_id = work_shift.id
                                                    WHERE   DATE(date) = '$date' AND rigi_num = '$rigi' AND `work_real`.project_id = $project_id"));
            return $req;
        }
        
        function check_user_cycle($rigi,$project_id,$date){
            $req = mysql_fetch_array(mysql_query("  SELECT work_real.user_id,work_real.work_cycle_id
                                                    FROM `work_real`
                                                    JOIN work_shift ON work_real.work_shift_id = work_shift.id
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
                
                
                $marcxena .= "<td style=\"height: 24px;\"><select id=\"user_id_$f\" user_num=\"rigi$f\" style=\"width: 80px;\">$operator</select></td>
                <td style=\"height: 24px;\"><select class=\"cycle\" id=\"$f\" user_num=\"rigi$f\" style=\"width: 80px;\">$cycle</select></td>
                </tr>";
                $f++;
                
            }
            $marcxena .= "<tr>";
            $tr_numb += $req[0];
        }
        $marcxena .= "<tr><td rowspan=1 style=\"text-align: center;vertical-align: middle;\">".($h+1)."</td>
        <td style=\"height: 24px;\"><select id=\"user_id_$f\" user_num=\"rigi$f\" style=\"width: 80px;\">$operator</select></td>
        <td style=\"height: 24px;\"><select class=\"cycle\" id=\"$f\" user_num=\"rigi$f\" style=\"width: 80px;\">$cycle</select></td>
        </tr><tr>";

        for($i = 1;$i <= $month_day;$i++){
            $zeda .= "<td style=\"width: 50px;\" onclick=\"openhour('$year-$month-$i')\">$i/$month/$year</td>";
            $shua_faq .= '<td class="qveda_dge_'.$i.'" style="width: 50px;">0</td>';
            $shua_geg .= '<td class="qveda_dge_geg_'.$i.'" style="width: 50px;">60</td>';
            $shua_sxvaoba .= '<td class="qveda_dge_sx_'.$i.'" style="width: 50px;"></td>';
        }
         
        for ($g = 1;$g <= $tr_numb+1;$g++){
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
                }else{
                    $background = "none";
                    $rr = check_real("$year-$month-$i","rigi".$g,$project_id);
                    $hour = 0;
                    if($rr[1] !=''){
                        $background = $rr[1];
                        $name_r = $rr[2];
                        $hour = $rr[3];
                        $wsi = $rr[0];
                    }
                    
                }
                
                $qveda .= '<td '.$vrtikal.' '.$horizontal.' hour="'.$hour.'" work_shift_id="'.$wsi.'" tarigi="'."$year-$month-$i".'" holy="'.$background.'" rigi_num="rigi'.$g.'" style="height: 24px;background: '.$background.';" onclick="opendialog('.$wsi.',\''.$background.'\',\''."$year-$month-$i".'\',\'rigi'.$g.'\')">'.$name_r.'</td>';
                $name_r = '';
                $hour = '';
                $rrr = check_user_cycle('rigi'.$g,$project_id,"$year-$month-$i");
                if(!empty($rrr[user_id])){
                    $test .= "<script>$(\"#$g option[value='".$rrr[1]."']\").prop('selected', true);$(\"#user_id_$g option[value='".$rrr[0]."']\").prop('selected', true);</script>";
                }
            }
            
            $qveda .= "</tr>";
            $marjvena .= "<tr>
                			<td class=\"total_$g\" style=\"height: 24px;\">0</td>
                			</tr>";
        }

        
        while ($req_shift = mysql_fetch_array($get_shift)){
            
            $shift .= "<tr><td style=\"background: $req_shift[2]\">$req_shift[1]</td></tr>";
            $shua='';
            
            for($d = 1;$d <= $month_day;$d++){
                
                $gg = mysql_fetch_array(mysql_query("   SELECT  COUNT(*) AS `count`
                                                        FROM    `work_real`
                                                        JOIN    work_shift ON work_real.work_shift_id = work_shift.id
                                                        JOIN calendar ON DATE(work_real.date) = calendar.y_m_d
                                                        WHERE   `work_real`.project_id = $project_id AND color = '$req_shift[2]' AND calendar.`y_m_d` = '$year-$month-$d'"));
                
                $shua .= '<td style="width: 50px;background: '.$req_shift[2].';">'.$gg[0].'</td>';
            }
            $cvla_op .= "<tr>$shua</tr>";
        }
        $data['day'] = $month_day;
        $data['num'] = $g-1;
        $data['ttt'] = $test;
        $data['table'] = '<table style="width: 1190px;">
    			<tr>
        			<td style="width: 200px;padding-right: 2px;">
            			<table id="pirveli">
            			<tr><td style="border: none;height: 13px;"></td></tr>
            			<tr><td>სადგ.</td><td>ოპერატორი</td><td>ციკლი</td></tr>
            			'.$marcxena.'
            			</table>
            			<table id="qveda_pirveli" style="margin-top: 5px;">
            			<tr>
            			<td style="height: 14px;">საოპერაციო სთ/დღე</td>
            			</tr>
            			<tr>
            			<td>გეგმიური სთ/დღე</td>
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
        			<td style="width: 885px; overflow: auto; display: block;margin-left: 4px;">
            			<table id="meore">
            			<tr><td colspan='.$month_day.' style="text-align: center;">'.$year.'-'.$month.'</td></tr>
            			<tr>
            			'.$zeda.'
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
            			<td style="height: 29px;">სულ სთ/თვე</td>
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
                     (`user_id`, `date`, `work_shift_id`, `rigi_num`, `project_id`, `work_cycle_id`)
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
                     `work_shift_id`='$shift_id'
                     WHERE `project_id`='$project_id'
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
        
        if($b_id == ''){
            mysql_query("INSERT INTO `work_real_break` (`work_real_id`, `start_break`, `end_break`, `work_activities_id`) VALUES ('$r_id', '$start_break', '$end_break', '$work_activities_id');");
        }else{
            mysql_query("UPDATE `work_real_break` SET `start_break`='$start_break', `end_break`='$end_break', `work_activities_id`='$work_activities_id' WHERE `id`='$b_id';");
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
    case 'get_index' :
        $count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
		$work_real_id = $_REQUEST['work_real_id'];
		 
		$rResult = mysql_query("SELECT  work_real_break.id,
		                                work_activities.`name`,
		                                work_real_break.start_break AS `start`,
                            			work_real_break.end_break AS `end`
                                FROM `work_real_break`
                                JOIN work_activities ON work_real_break.work_activities_id = work_activities.id
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
    case 'get_24_hour' :
        $project_id = $_REQUEST['project_id'];
        $date       = $_REQUEST['year_month'];
        $hour = "";
        $minute = "";
        $mid = "";
        $m = 1;
        $pr = mysql_fetch_assoc(mysql_query("   SELECT HOUR(start_time) AS `start`,
                                                HOUR(end_time) AS `end`
                                                FROM `week_day_graphic`
                                                WHERE project_id = $project_id
                                                LIMIT 1"));
        $start = $pr['start'];
        $end   = $pr['end'];

        
        $my_res = mysql_query(" SELECT  user_info.`name`,
                                        HOUR(work_shift.start_date) AS `start`,
                                        HOUR(work_shift.end_date) AS `end`,
        								CAST(SUBSTRING_INDEX(work_real.rigi_num, 'rigi',-1) AS UNSIGNED) as num,
                                        work_real.id,work_real.user_id
                                FROM `work_real`
                                JOIN work_shift ON work_real.work_shift_id = work_shift.id
                                JOIN user_info ON work_real.user_id = user_info.user_id
                                WHERE DATE(date) = '$date' AND work_real.project_id = $project_id
                                ORDER BY num ASC");
        $ope .= '<tr><td style="height: 12px;border-top: 2px solid black;"></td></tr><tr><td style="height: 12px;"></td></tr>';
        while ($my_req = mysql_fetch_assoc($my_res)){
            if($my_req[start]==0){
                
            }else{
            $color = '';
            $mid .= '<tr>';
            $hj .= '<tr>';
            $ope .= '<tr><td style="white-space:nowrap;cursor:pointer;height: 15px;" class="user_break" work_real_id="'.$my_req[id].'" rigi="'.$m.'">'.$my_req[name].'</td></tr>';

            $gelasbichinodari = 1;
            for ($n = $start;$n < $end;$n++){
                $break = mysql_fetch_array(mysql_query("SELECT  MINUTE(start_break) AS `m_start`,
                                                                MINUTE(end_break) AS `m_end`,
                                                                HOUR(start_break) AS `start`,
                                                                HOUR(end_break) AS `end`,work_real_break.id,work_activities.`color`
                                                        FROM `work_real_break`
                                                        JOIN work_activities ON work_real_break.work_activities_id = work_activities.id
                                                        WHERE work_real_id = $my_req[id] AND (HOUR(start_break) = $n OR HOUR(end_break) = $n)"));
                
                if(($n >= $my_req[start] && $n < $my_req[end] && $n != 0)){
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
                    $mid .= '<td table_zeda="'.$gelasbichinodari.'" count_green="'.$original_color.'" rigi="'.$m.'" clock="'.$n.$j.'" style="background: '.$original_color.';height:6px;"></td>';
                    $hj .= '<td table_zeda="'.$gelasbichinodari.'" userId="'.$my_req[user_id].'" count_red="'.$original_color.'" rigi="'.$m.'" clock="'.$n.$j.'" style="height:6px;background:red;"></td>';
                }else{
                    $mid .= '<td table_zeda="'.$gelasbichinodari.'" count_green="'.$original_color.'" rigi="'.$m.'" clock="'.$n.'0'.$j.'" style="background: '.$original_color.';height:6px;"></td>';
                    $hj .= '<td table_zeda="'.$gelasbichinodari.'" userId="'.$my_req[user_id].'" count_red="'.$original_color.'" rigi="'.$m.'" clock="'.$n.'0'.$j.'" style="height:6px;background:red;"></td>';
                }
                $gelasbichinodari++;
            }
            
            }
            $hj .= '</tr>';
            $mid .= '</tr>'.$hj;
            $m++;
            $hj = '';
            }
        }
        $gelasbichi = 1;
        for ($i = $start;$i < $end;$i++){
            if(strlen($i) == 2){
                $hour .= '<th style="border-top: 2px solid black;" colspan="12">'.$i.':00</th>';
            }else{
                $hour .= '<th style="border-top: 2px solid black;" colspan="12">0'.$i.':00</th>';
            }
            for ($g = 0;$g <= 55;$g+=5){
                if(strlen($g) == 2){
                    $minute .= '<th style="width: ;">'.$g.'</th>';
                    $gegss1 .= "<td shuaa=\"$gelasbichi\" clock=\"$i$g\" style=\"height:12px;\"><span style=\"width: 13px;display:block;\">0</span></td>";
                }else{
                    $minute .= '<th style="width: ;">0'.$g.'</th>';
                    $gegss1 .= "<td shuaa=\"$gelasbichi\" clock=\"".$i."0".$g."\" style=\"height:12px;\"><span style=\"width: 13px;display:block;\">0</span></td>";
                }
        
                $geg0 .= "<td zeda=\"$gelasbichi\" style=\"height:12px;\"><span style=\"width: 13px;display:block;\">0</span></td>";
                $geg1 .= "<td shua=\"$gelasbichi\" style=\"height:12px;\"><span style=\"width: 13px;display:block;\">0</span></td>";
                $geg2 .= "<td qveda=\"$gelasbichi\" style=\"height:12px;\"><span style=\"width: 13px;display:block;\">0</span></td>";
                $gegss0 .= "<td zedaa=\"$gelasbichi\" style=\"height:12px;\"><span style=\"width: 13px;display:block;\">80</span></td>";
                
                $gegss2 .= "<td qvedaa=\"$gelasbichi\" style=\"height:12px;\"><span style=\"width: 13px;display:block;\">0</span></td>";
                $gelasbichi++;
            }
        }
        
        $sl = mysql_query(" SELECT  
                            ROUND((SUM(IF(asterisk_incomming.wait_time<80, 1, 0)) / COUNT(*) ) * 100) AS `percent`,
                            FLOOR(UNIX_TIMESTAMP(asterisk_incomming.call_datetime) / (5 * 60)) AS time,
                            DATE_FORMAT(asterisk_incomming.call_datetime,'%H') AS `forH`,
                            DATE_FORMAT(asterisk_incomming.call_datetime,'%i') AS `forM`
                            FROM    `asterisk_incomming`
                            WHERE   DATE(asterisk_incomming.call_datetime) = '2016-05-25' AND asterisk_incomming.disconnect_cause != 'ABANDON' 
                            GROUP BY  time");
        
        while ($slq=mysql_fetch_assoc($sl)){
            $data['sl'][] = array('timeH'=>$slq['forH'],'timeM'=>$slq['forM'],'prc'=>$slq['percent']);
        }
        
        $tete = mysql_query("SELECT user_log.user_id,
                                    DATE_FORMAT(login_date,'%H%i') AS login_date,
                                    DATE_FORMAT(logout_date,'%H%i') AS logout_date,
                                    work_activities.`name`
                            FROM `user_log`
                            LEFT JOIN work_activities ON user_log.work_activities_id = work_activities.id
                            WHERE DATE(login_date) = '$date' AND NOT ISNULL(logout_date)");
        $data['tutuci'] .= "<script>";
        while ($ter = mysql_fetch_assoc($tete)){
            for ($j = $ter[login_date];$j <= $ter[logout_date];$j++){
                $data['tutuci'] .= "$(\"td[userid='$ter[user_id]'][clock='$j']\").css('background','green');";
            }
        }
        $data['tutuci'] .= "</script>";
        $data['page'] = '<table>
                        <tr>
                        <td>
                        <table id="work_table" style="width: 150px;">
                        '.$ope.'
                            
                        </table>
                            <table id="work_table" style="width: 150px; margin-top: 10px;">
                            <tr><td>გეგ. ოპ. რ-ბა</td></tr>
                            <tr><td>ფაქტ. ოპ. რ-ბა</td></tr>
                            <tr><td style="text-align: right;">სხვაობა</td></tr>
                            </table>
                            <table id="work_table" style="width: 150px; margin-top: 10px;">
                            <tr><td>გეგ. SL</td></tr>
                            <tr><td>ფაქტ. SL</td></tr>
                            <tr><td style="text-align: right;">სხვაობა</td></tr>
                            </table>
                        </td>
                        <td style="width:1050px; overflow: auto; display: block;">
                        <table id="work_table" style="width: 310%;">
                        <tr>
                            '.$hour.'
                        </tr>
                        <tr>
                    	    '.$minute.'
                        </tr>
                    	        '.$mid.'
                        </table>
                    	            <table id="work_table" style="margin-top: 10px;width: 310%;">
                    	            <tr>'.$geg0.'</tr>
                	                <tr>'.$geg1.'</tr>
            	                    <tr>'.$geg2.'</tr>
                    	            </table>
            	                    <table id="work_table" style="margin-top: 10px;width: 310%;">
                    	            <tr>'.$gegss0.'</tr>
                	                <tr>'.$gegss1.'</tr>
            	                    <tr>'.$gegss2.'</tr>
                    	            </table>
        	            </td>
        	            </tr>
        	            </table>';
    
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
                                            WHERE work_real_break.id = $work_real_break_id"));
    return $res;
}

function get_work_activities($id){
    $req = mysql_query("SELECT 	`id`,
                				`name`
                        FROM `work_activities`
                        WHERE actived = 1");
    
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
?>