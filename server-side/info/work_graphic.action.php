<?php

require_once ('../../includes/classes/core.php');

$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';
$user_id	= $_SESSION['USERID'];
$project_id = $_REQUEST['project_id'];
$wday       = $_REQUEST['wday'];
switch ($action) {
	case 'get_list' :
		$count 		= $_REQUEST['count'];
		$hidden 	= $_REQUEST['hidden'];
	  	$rResult 	= mysql_query("SELECT id, `start`, `end` 
                                    FROM `work_graphic`
                                    WHERE actived=1	");

		$data = array(
				"aaData"	=> array()
		);
		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				if($i == ($count - 1)){
					$row[] = '<div class="callapp_checkbox">
                                  <input type="checkbox" id="callapp_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                                  <label for="callapp_checkbox_'.$aRow[$hidden].'"></label>
                              </div>';
				}
				$row[] = $aRow[$i];

			}
			$data['aaData'][] = $row;
		}

		break;
		
	case "get_edit_page":
	$data['page'][]=page();
	   break;
	
	case 'disable':
		mysql_query("UPDATE `work_graphic` SET `actived`='0' WHERE (`id`='$_REQUEST[id]')");
		break;
		
	case 'get_add_page' :
	$data['page'][]=page();
		break;
	case 'get_wk' :
	    $req = mysql_fetch_array(mysql_query("  SELECT  TIME_FORMAT(start_time,'%H'),
                                                        TIME_FORMAT(end_time,'%H'),
                                                        TIME_FORMAT(start_time,'%i'),
                                                        TIME_FORMAT(end_time,'%i')
                                                FROM `week_day_graphic`
                                                WHERE project_id = '$project_id' AND week_day_id = $wday"));
	    $maxH = $req[1];
	    $minH = $req[0];
	    if($req[1] == ''){
	        $maxH = '23';
	    }
	    if($req[0] == ''){
	        $minH = '00';
	    }
	    $data['start'][] = "<script>
	    $('#pasteStart').html('<input id=\"start\" style=\"width:145px;\" 	type=\"text\" value=\"\" />');
	    $('#pasteEnd').html('<input id=\"end\" style=\"width:145px;\" 	type=\"text\" value=\"\" />');
	    $('#start,#end').timepicker({
	        hourMax: $maxH,
	        hourMin: $minH,
	        minuteMax: 55,
	        minuteMin: 00,
	        stepMinute: 5,
	        minuteGrid: 10,
	        hourGrid: 3,
	        dateFormat: '',
	        timeFormat: 'HH:mm'
	    });
	    </script>";
	    
	    $res1 = mysql_query("  SELECT   TIME_FORMAT(week_day_graphic_break.break_start,'%H'),
                        				TIME_FORMAT(week_day_graphic_break.break_end,'%H'),
                        				TIME_FORMAT(week_day_graphic_break.break_start,'%i'),
                        				TIME_FORMAT(week_day_graphic_break.break_end,'%i')
                                FROM `week_day_graphic`
                                JOIN week_day_graphic_break ON week_day_graphic.id = week_day_graphic_break.week_day_graphic_id
                                WHERE project_id = $project_id AND week_day_id = $wday");
	    $data['end'][0] = '';
	    $i=1;
	    while ($req1 = mysql_fetch_array($res1)){
	        $data['end'][$i] = "<script>
	        $('#pasteTable').append('<tr><td style=\"width: 200px;\"><label for=\"\">შესვენება იწყება ($i)</label></td><td ><label for=\"\">შესვენება მთავრდება ($i)</label></td></tr><tr><td><input class=\"breakStart$i\" style=\"width:145px;\" 	type=\"text\" value=\"\" /></td><td><input class=\"breakEnd$i\"   style=\"width:145px;\"  type=\"text\" value=\"\" /></td></tr>');
	        $('.breakStart$i,.breakEnd$i').timepicker({
    	        hourMax: $req1[1],
    	        hourMin: $req1[0],
    	        minuteMax: 55,
    	        minuteMin: 00,
    	        stepMinute: 5,
    	        minuteGrid: 10,
    	        hourGrid: 3,
    	        dateFormat: '',
    	        timeFormat: 'HH:mm'
	        });
	        </script>";
	        $i++;
	    }
	    break;
   	case 'save_dialog' :
   		if($_REQUEST[id]==''){
		mysql_query("INSERT INTO `work_graphic`
		              (`start`, `end`, `user_id`,`project_id`,`week_day_id`)
				     VALUES
		              ('$_REQUEST[start]', '$_REQUEST[end]', '$user_id', '$_REQUEST[project_id]', '$_REQUEST[wday]')");
		$next_id = mysql_fetch_array(mysql_query("  SELECT id
                                                    FROM `work_graphic`
                                                    WHERE actived = 1
                                                    ORDER BY id DESC
                                                    LIMIT 1"));
    		if($_REQUEST[breakStart1] != '' && $_REQUEST[breakEnd1] != ''){
    		    mysql_query("INSERT INTO `week_graphic_break`
                            (`user_id`, `week_graphic_id`, `break_start`, `break_end`)
                            VALUES
                            ('$user_id', '$next_id[0]', '$_REQUEST[breakStart1]', '$_REQUEST[breakEnd1]');");
    		}
    		if($_REQUEST[breakStart2] != '' && $_REQUEST[breakEnd2] != ''){
    		    mysql_query("INSERT INTO `week_graphic_break`
                            (`user_id`, `week_graphic_id`, `break_start`, `break_end`)
                            VALUES
                            ('$user_id', '$next_id[0]', '$_REQUEST[breakStart2]', '$_REQUEST[breakEnd2]');");
    		}
    		if($_REQUEST[breakStart3] != '' && $_REQUEST[breakEnd3] != ''){
    		    mysql_query("INSERT INTO `week_graphic_break`
                            (`user_id`, `week_graphic_id`, `break_start`, `break_end`)
                            VALUES
                            ('$user_id', '$next_id[0]', '$_REQUEST[breakStart3]', '$_REQUEST[breakEnd3]');");
    		}
    		if($_REQUEST[breakStart4] != '' && $_REQUEST[breakEnd4] != ''){
    		    mysql_query("INSERT INTO `week_graphic_break`
                            (`user_id`, `week_graphic_id`, `break_start`, `break_end`)
                            VALUES
                            ('$user_id', '$next_id[0]', '$_REQUEST[breakStart4]', '$_REQUEST[breakEnd4]');");
    		}
    		if($_REQUEST[breakStart5] != '' && $_REQUEST[breakEnd5] != ''){
    		    mysql_query("INSERT INTO `week_graphic_break`
                            (`user_id`, `week_graphic_id`, `break_start`, `break_end`)
                            VALUES
                            ('$user_id', '$next_id[0]', '$_REQUEST[breakStart5]', '$_REQUEST[breakEnd5]');");
    		}
    		if($_REQUEST[breakStart6] != '' && $_REQUEST[breakEnd6] != ''){
    		    mysql_query("INSERT INTO `week_graphic_break`
                            (`user_id`, `week_graphic_id`, `break_start`, `break_end`)
                            VALUES
                            ('$user_id', '$next_id[0]', '$_REQUEST[breakStart6]', '$_REQUEST[breakEnd6]');");
    		}
   		}else{

			mysql_query("UPDATE  `work_graphic` SET
            			         `start`='$_REQUEST[start]',
            			         `end`='$_REQUEST[end]'
			             WHERE (`id`='$_REQUEST[id]')");
			if($_REQUEST[breakStart1] != '' && $_REQUEST[breakEnd1] != ''){
			    mysql_query("UPDATE `week_graphic_break` SET
                        			`break_start`='$_REQUEST[breakStart1]',
                        			`break_end`='$_REQUEST[breakEnd1]'
                             WHERE (`id`='$_REQUEST[my_id1]');");
			}
			if($_REQUEST[breakStart2] != '' && $_REQUEST[breakEnd2] != ''){
			    mysql_query("UPDATE `week_graphic_break` SET
                        			`break_start`='$_REQUEST[breakStart2]',
                        			`break_end`='$_REQUEST[breakEnd2]'
                             WHERE (`id`='$_REQUEST[my_id2]');");
			}
			if($_REQUEST[breakStart3] != '' && $_REQUEST[breakEnd3] != ''){
			    mysql_query("UPDATE `week_graphic_break` SET
                        			`break_start`='$_REQUEST[breakStart3]',
                        			`break_end`='$_REQUEST[breakEnd3]'
                             WHERE (`id`='$_REQUEST[my_id3]');");
			}
			if($_REQUEST[breakStart4] != '' && $_REQUEST[breakEnd4] != ''){
			    mysql_query("UPDATE `week_graphic_break` SET
                        			`break_start`='$_REQUEST[breakStart4]',
                        			`break_end`='$_REQUEST[breakEnd4]'
                             WHERE (`id`='$_REQUEST[my_id4]');");
			}
			if($_REQUEST[breakStart5] != '' && $_REQUEST[breakEnd5] != ''){
			    mysql_query("UPDATE `week_graphic_break` SET
                        			`break_start`='$_REQUEST[breakStart5]',
                        			`break_end`='$_REQUEST[breakEnd5]'
                             WHERE (`id`='$_REQUEST[my_id5]');");
			}
			if($_REQUEST[breakStart6] != '' && $_REQUEST[breakEnd6] != ''){
			    mysql_query("UPDATE `week_graphic_break` SET
                        			`break_start`='$_REQUEST[breakStart6]',
                        			`break_end`='$_REQUEST[breakEnd6]'
                             WHERE (`id`='$_REQUEST[my_id6]');");
			}
		}
   		break;
   		
	default:
		$error = 'Action is Null';
}

function getProject($id){
    
    $data = '';
    $req = mysql_query("SELECT id,`name`
                        FROM `project`
                        WHERE actived = 1
						");
    
    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
    
        if($res['id'] == $id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        }else{
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }
    return $data;
}

function getWday($id){
    $data = '<option value="0" selected="selected">----</option>
             <option value="1" '.(($id==1)?'selected="selected"':"").'>ორშაბათი</option>
             <option value="2" '.(($id==2)?'selected="selected"':"").'>სამშაბათი</option>
             <option value="3" '.(($id==3)?'selected="selected"':"").'>ოთხშაბათი</option>
             <option value="4" '.(($id==4)?'selected="selected"':"").'>ხუთშაბათი</option>
             <option value="5" '.(($id==5)?'selected="selected"':"").'>პარსკევი</option>
             <option value="6" '.(($id==6)?'selected="selected"':"").'>შაბათი</option>
             <option value="7" '.(($id==7)?'selected="selected"':"").'>კვირა</option>';
    return $data;
}

function page()
{
		$rResult 	= mysql_query("SELECT 	project_id,
                            				week_day_id,
                            				TIME_FORMAT(start,'%H:%i') AS `start`,
                            				TIME_FORMAT(end,'%H:%i') AS `end`,
                            				TIME_FORMAT(start,'%H') AS `startH`,
                            				TIME_FORMAT(end,'%H') AS `endH`
                				   FROM `work_graphic`
                				   WHERE id='$_REQUEST[id]' AND work_graphic.actived=1");
		$res = mysql_fetch_array( $rResult );
		
		$rResult1 	= mysql_query(" SELECT  id,
                                            TIME_FORMAT(break_start,'%H:%i') AS break_start,
                                            TIME_FORMAT(break_end,'%H:%i') AS break_end,
                                            TIME_FORMAT(break_start,'%H') AS `startH`,
                                            TIME_FORMAT(break_end,'%H') AS `endH`
                                    FROM `week_graphic_break`
                                    WHERE week_graphic_id = '$_REQUEST[id]' AND actived = 1");
		
	

	$data =  '
        	<div id="dialog-form">
        		<fieldset >
        	    	<legend >ძირითადი ინფორმაცია</legend>
        	    	<table class="dialog-form-table">
        	            <tr>
        					<td style="width: 200px;"><label for="">პროექტი</label></td>
        	                <td ><label for="">კვირის დღე</label></td>
        				</tr>
        	            <tr>
        					<td style="width: 200px;"><select id="project_id" style="width:150px;">'.getProject($res[project_id]).'</select></td>
        					<td ><select id="wday" style="width:150px;">'.getWday($res[week_day_id]).'</select></td>
        				</tr>
        				<tr>
        					<td style="width: 200px;"><label for="">მუშაობის დასაწყისი</label></td>
        					<td ><label for="">მუშაობის დასასრული</label></td>
        				</tr>
        			    <tr>
        					<td id="pasteStart"><input id="start" style="width:145px;" 	type="text" value="'.$res[start].'" /></td>
        					<td id="pasteEnd"><input id="end"   style="width:145px;"  type="text" value="'.$res[end].'" /></td>
        				</tr>
        			</table>';
        					    if($res[endH]!=''){
        					        $data .='<script>        					    
            					    $("#start,#end").timepicker({
                            	        hourMax: '.$res[endH].',
                            	        hourMin: '.$res[startH].',
                            	        minuteMax: 55,
                            	        minuteMin: 00,
                            	        stepMinute: 5,
                            	        minuteGrid: 10,
                            	        hourGrid: 3,
                            	        dateFormat: "",
                            	        timeFormat: "HH:mm"
                            	    });
                            	    </script>';
        					    }
        			$data .='<table class="dialog-form-table" id="pasteTable">';
	                    $i=1;
        	            while ($res1 = mysql_fetch_array( $rResult1 )){
        	                $data .= '<tr>
                	                    <td style="width: 200px;"><label for="">შესვენება იწყება ('.$i.')</label></td>
                	                    <td ><label for="">შესვენება მთავრდება ('.$i.')</label></td>
                	                  </tr>
        	                          <tr>
        	                           <td><input class="breakStart'.$i.'" my_id'.$i.'="'.$res1[id].'" style="width:145px;" type="text" value="'.$res1[break_start].'" /></td>
                	                   <td><input class="breakEnd'.$i.'" style="width:145px;" type="text" value="'.$res1[break_end].'" /></td>
                	                  </tr>
                	                       <script>
	        $(".breakStart'.$i.',.breakEnd'.$i.'").timepicker({
    	        hourMax: '.$res1[endH].',
    	        hourMin: '.$res1[startH].',
    	        minuteMax: 55,
    	        minuteMin: 00,
    	        stepMinute: 5,
    	        minuteGrid: 10,
    	        hourGrid: 3,
    	        dateFormat: "",
    	        timeFormat: "HH:mm"
	        });
	        </script>';
        	                $i++;
        	            }
        			$data .='</table>
        		</fieldset >
        	</div>
        <input type="hidden" id="id" value='.$_REQUEST[id].'>';
	return $data;

}

$data['error'] = $error;

echo json_encode($data);

?>