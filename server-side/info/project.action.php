<?php
// MySQL Connect Link
require_once('../../includes/classes/core.php');

// Main Strings
$action                     = $_REQUEST['act'];
$error                      = '';
$data                       = '';


// Incomming Call Dialog Strings
$hidden_id         = $_REQUEST['id'];
$project_name      = $_REQUEST['project_name'];
$project_type      = $_REQUEST['project_type'];
$project_add_date  = $_REQUEST['project_add_date'];

switch ($action) {
	case 'get_add_page':
		$page		= GetPage('',object($hidden_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$page		= GetPage(object($hidden_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_hour':
	    $page		= GetHour($_REQUEST[wday],$_REQUEST[clock],$_REQUEST[project_id]);
	    $data		= array('hour'	=> $page);
	
	    break;
    case 'get_week':
        $page		= GetDialogWeek($_REQUEST[week_id],$_REQUEST[project_id]);
        $data		= array('week'	=> $page);
    
        break;
    case 'get_weekADD':
        $page		= GetDialogWeekAdd($_REQUEST[week_id],$_REQUEST[project_id]);
        $data		= array('weekADD'	=> $page);
    
        break;
    case 'get_langdialog':
        $page		= GetDialogLangAdd($_REQUEST[week_id],$_REQUEST[project_id]);
        $data		= array('lang'	=> $page);
    
        break;
    case 'get_infosorce':
        $page		= GetDialogInfoSorceAdd($_REQUEST[week_id],$_REQUEST[project_id]);
        $data		= array('infosorce'	=> $page);
    
        break;
	case 'disable':
		mysql_query("	UPDATE  `project` SET
                		        `actived` = 0
                		WHERE   `id`='$hidden_id'");
		
		break;
	case 'delete_all_holiday':
	    $project_id    = $_REQUEST['project_id'];
	    mysql_query("	UPDATE  `project_holiday` SET
        	                    `actived` = 0
            	        WHERE   `project_id`='$project_id'");
	
	    break;
    case 'delete_gr':
        $project_id = $_REQUEST['project_id'];
        $wday       = $_REQUEST['wday'];
        mysql_query("UPDATE  `week_day_graphic` SET
                             `actived` = 0
                     WHERE   `project_id`='$project_id' AND `week_day_id` = '$wday'");
    
        break;
    case 'delete_holiday':
        mysql_query("	UPDATE  `project_holiday` SET
                                `actived` = 0
                        WHERE   `id`='$hidden_id'");
    
        break;
    case 'delete_break':
        mysql_query("	UPDATE  `week_day_graphic_break` SET
                                `actived` = 0
                        WHERE   `id`='$hidden_id'");
    
        break;
        
    case 'check_weak':
        $project_id    = $_REQUEST['project_id'];
        $wday          = $_REQUEST['wday'];
        $req = mysql_fetch_array(mysql_query("  SELECT  TIME_FORMAT(start_time,'%H:%i'),
                                                        TIME_FORMAT(end_time,'%H:%i'),
                                                        ext_number
                                                FROM `week_day_graphic`
                                                WHERE project_id = '$project_id' AND week_day_id = $wday"));
        $data = array('start_time'=>$req[0],'end_time'=>$req[1],'ext_number'=>$req[2]);
        break;
	case 'add_all_holiday':
	    $project_id    = $_REQUEST['project_id'];
	    $user          = $_SESSION['USERID'];
	    
	    $res = mysql_query("SELECT id
                            FROM `holidays`
                            WHERE actived = 1");
	    while ($req = mysql_fetch_array($res)){
	        $ch_hl = mysql_num_rows(mysql_query("SELECT id FROM project_holiday WHERE project_id=$project_id AND holidays_id=$req[0] AND actived = 1"));
    	    if($ch_hl == 0){
    	    mysql_query("INSERT INTO `project_holiday`
                         (`user_id`, `project_id`, `holidays_id`)
                         VALUES
                         ('$user', '$project_id', '$req[0]')");
    	    }
	    }
	    break;
    case 'add_holiday':
        $project_id    = $_REQUEST['project_id'];
        $holiday_id    = $_REQUEST['holiday_id'];
        $user          = $_SESSION['USERID'];
         
        $req = mysql_num_rows(mysql_query("SELECT id
                                           FROM `project_holiday`
                                           WHERE project_id = $project_id AND holidays_id = $holiday_id AND actived = 1"));
        if($req == 0){
        mysql_query("INSERT INTO `project_holiday`
                     (`user_id`, `project_id`, `holidays_id`)
                     VALUES
                     ('$user', '$project_id', '$holiday_id')");
        }else{
            $error = 'ეს დასვენების დღე უკვე დამატებულია!';
        }
            
        break;
    case 'get_wk':
        $project_id    = $_REQUEST['project_id'];
         
        $res = mysql_query("SELECT  week_day_id,
                                    start_time,
                                    end_time,
                                    ext_number
                            FROM `week_day_graphic`
                            WHERE project_id = '$project_id' AND actived = 1 AND type = 1");
        
        $res1 = mysql_query("SELECT  week_day_id,
                                     start_time,
                                     end_time,
                                     ext_number
                             FROM `week_day_graphic`
                             WHERE project_id = '$project_id' AND actived = 1 AND type = 2");
        
        while ($req = mysql_fetch_array($res)){
            $data['work'][] = array('wday'	=> $req[0],'starttime' => $req[1],'endtime' => $req[2],'ext_number' => $req[3]);
        }
        
        while ($req1 = mysql_fetch_array($res1)){
            $data['break'][] = array('wday'=> $req1[0],'breakstarttime'=> $req1[1],'breakendtime'=> $req1[2]);
        }
    
        break;
        
    case 'work_gr':

            mysql_query("INSERT INTO `week_day_graphic`
                        (`project_id`, `week_day_id`, `start_time`, `end_time`, `ext_number`, `type`)
                        VALUES
                        ('$_REQUEST[project_id]', '$_REQUEST[wday]', '$_REQUEST[start_time]', '$_REQUEST[end_time]', '$_REQUEST[ext_number]', '$_REQUEST[type]');");

    
        break;
	case 'save-project':
		$hidden_id		  = $_REQUEST['project_hidden_id'];
    	$hidden_client_id = $_REQUEST['hidden_client_id'];
    	$start_date_holi  = $_REQUEST['start_date_holi'];
    	$end_date_holi    = $_REQUEST['end_date_holi'];
    	
    	if($hidden_id==''){
    		Addproject($hidden_client_id, $project_name, $project_type, $project_add_date, $start_date_holi, $end_date_holi);
    	}else{
    		Saveproject($hidden_id,$project_name, $project_type, $project_add_date, $start_date_holi, $end_date_holi);
    	}
    		
    	break;
	case 'get_list_lang':
	    $count = 		$_REQUEST['count'];
	    $hidden = 		$_REQUEST['hidden'];
	    $rResult = mysql_query("SELECT 	week_day_lang.id,
                        				spoken_lang.`name`
                                FROM    `week_day_graphic`
                                JOIN    week_day_lang ON week_day_graphic.id = week_day_lang.week_day_graphic_id
                                JOIN    spoken_lang ON week_day_lang.spoken_lang_id = spoken_lang.id
                                WHERE   week_day_graphic.project_id = 1 
	                            AND     week_day_graphic.week_day_id = 1 
	                            AND     week_day_graphic.actived = 1");
	
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
                                  <input type="checkbox" id="callapp_checkbox_lang_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                                  <label style="margin-top: 2px;" for="callapp_checkbox_lang_'.$aRow[$hidden].'"></label>
                              </div>';
	            }
	        }
	        $data['aaData'][] = $row;
	    }
	    break;
    case 'get_list_infosorce':
        $count = 		$_REQUEST['count'];
        $hidden = 		$_REQUEST['hidden'];
        $rResult = mysql_query("SELECT 	week_day_info_sorce.id,
                        				information_source.`name`
                                FROM    `week_day_graphic`
                                JOIN    week_day_info_sorce ON week_day_graphic.id = week_day_info_sorce.week_day_graphic_id
                                JOIN    information_source ON week_day_info_sorce.information_source_id = information_source.id
                                WHERE   week_day_graphic.project_id = 1
                                AND     week_day_graphic.week_day_id = 1
                                AND     week_day_graphic.actived = 1");
    
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
                                  <input type="checkbox" id="callapp_checkbox_infosorce_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                                  <label style="margin-top: 2px;" for="callapp_checkbox_infosorce_'.$aRow[$hidden].'"></label>
                              </div>';
                }
            }
            $data['aaData'][] = $row;
        }
        break;
    
   default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Request Functions
* ******************************
*/

function Addproject($hidden_client_id, $project_name, $project_type, $project_add_date, $start_date_holi, $end_date_holi){
	
	$user = $_SESSION['USERID'];

	mysql_query("INSERT INTO `project` 
						(`user_id`, `client_id`, `name`, `type_id`, `create_date`, `actived`, `start_date`, `end_date`) 
					VALUES 
						('$user', '$hidden_client_id', '$project_name', '$project_type', '$project_add_date', '1', '$start_date_holi', '$end_date_holi')");

}

function Saveproject($hidden_id,$project_name, $project_type, $project_add_date, $start_date_holi, $end_date_holi){
	
	$user = $_SESSION['USERID'];
	
	mysql_query("UPDATE  `project`
	 				SET  `user_id`='$user', 
						 `name`='$project_name', 
						 `type_id`='$project_type', 
						 `create_date`='$project_add_date',
	                     `start_date`='$start_date_holi',
	                     `end_date`='$end_date_holi'
				WHERE `id`='$hidden_id'");

}

function GetScenario($id){
    $data = '';
    $req = mysql_query("SELECT 	`id`,
                				`name`
                        FROM `scenario`
                        WHERE actived = 1");
    
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

function Get_type($count){
	$data = '';
	$req = mysql_query("SELECT id, `name`
						FROM `call_type`");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){

		if($res['id'] == $count){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		}else{
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}
	return $data;
}
function object($hidden_id){
	
	$res = mysql_fetch_assoc(mysql_query("SELECT  project.id,
												  project.`name`,
												  project.type_id,
												  project.create_date
											FROM `project`
											WHERE project.id='$hidden_id'"));
	return $res;
}

function GetHoliday(){
    $data = '';
    $req = mysql_query("SELECT  `id`,
                                `name`
						FROM    `holidays`
                        WHERE   `actived` = 1");
    
    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
    
        if($res['id'] == $count){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        }else{
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }
    return $data;
}

function GetLang($id){
    $data = '';
    $req = mysql_query("SELECT  `id`,
								`name`
						FROM    `spoken_lang`
                        WHERE   `actived` = 1");

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

function GetInfoSource($id){
    $data = '';
    $req = mysql_query("SELECT 	`id`,
                				`name`
                        FROM    `information_source`
                        WHERE   `actived` = 1");

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

function GetHour($wday,$clock,$project_id){
    if(strlen($clock)==1){
        $real_clock = '0'.$clock;   
    }else{
        $real_clock = $clock;
    }
    
    $data = '
        <style>
	    #table_hour{
	    
	    width: 100%;
	    margin-top:25px;
	    }
	    #table_hour td,#table_hour th{
	    border: 1px solid;
        font-size: 11px;
        font-weight: normal;
        text-align: center;
        height: 13px;
	    }
	    </style>
        <div id="dialog-form">
    	    <fieldset style="width: 175px;">
    	       <legend>ძირითადი ინფორმაცია</legend>
                <div style="width: 855px;">
                <table id="table_hour">
                    <tr>
                        <th style="width: ;"></th>';
                        for($i = 5;$i < 60;$i+=5){
                            if(strlen($i) == 1){
                                $data .= '<th style="width: ;"><span id="clock_hour">'.$real_clock.'</span>:0'.$i.'</th>';
                            }else{
                                $data .= '<th style="width: ;"><span id="clock_hour">'.$real_clock.'</span>:'.$i.'</th>';
                            }
                        }
                        $data .= '
                    </tr>
    	            <tr id="wday1">
                        <td class="wday">'.$req[0].'</td>';
                            for($i = 5;$i < 60;$i+=5){
                                    $data .= '<td clockid="'.$i.'"  check_clock=""></td>';
                            }
                            
                            $req = mysql_fetch_array($res = mysql_query("SELECT  CASE
                                                            WHEN week_day_id = 1 THEN 'ორშ'
                                                            WHEN week_day_id = 2 THEN 'სამ'
                                                            WHEN week_day_id = 3 THEN 'ოთხ'
                                                            WHEN week_day_id = 4 THEN 'ხუთ'
                                                            WHEN week_day_id = 5 THEN 'პარ'
                                                            WHEN week_day_id = 6 THEN 'შაბ'
                                                            WHEN week_day_id = 7 THEN 'კვი'
                                                            END AS `week_day`,
                                                            TIME_FORMAT(start_time,'%H:%i') AS `start_time`,
                                                            TIME_FORMAT(end_time,'%H:%i') AS `end_time`,
                                                            TIME_FORMAT(start_time,'%i'),
                                                            TIME_FORMAT(end_time,'%i'),
                                                            TIME_FORMAT(start_time,'%H'),
                                                            TIME_FORMAT(end_time,'%H'),
                                                            ext_number
                                                FROM `week_day_graphic`
                                                WHERE TIME_FORMAT(start_time,'%H') <= '$real_clock' AND TIME_FORMAT(end_time,'%H') >= '$real_clock' AND  project_id = '$project_id' AND week_day_id = '$wday' AND actived = 1 AND type = 1"));
                            
                           
                                if($req[3] == '00' && $req[4] == '00'){
                                    $data .= '<script>
                                              $("td[clockid]").css("background","green");
                                              $("td[clockid]").html("'.$req[7].'");
                                              $(".wday").html("'.$req[0].'");
                                              </script>';
                                }
                                
                                if($req[3] != '00' && $req[4] == '00'){
                                    $data .= '<script>
                                                        $("td[clockid]").css("background","green");
                                                        $("td[clockid]").html("'.$req[7].'");
                                                            $(".wday").html("'.$req[0].'");
                                                        </script>';
                                    for($i = 5;$i < 60;$i+=5){
                                        if($i < $req[3] && $real_clock == $req[5]){
                                            $data .= '<script>
                                                        $("td[clockid='.$i.']").css("background","");
                                                        $("td[clockid='.$i.']").html("");
                                                            $(".wday").html("'.$req[0].'");
                                                        </script>';
                                        }
                                        if($i > $req[4] && $real_clock == $req[6]){
                                            $data .= '<script>
                                                        $("td[clockid='.$i.']").css("background","");
                                                        $("td[clockid='.$i.']").html("");
                                                            $(".wday").html("'.$req[0].'");
                                                        </script>';
                                        }
                                    }
                                }
                                if($req[3] != '00' && $req[4] != '00'){
                                    $data .= '<script>
                                                        $("td[clockid]").css("background","green");
                                                        $("td[clockid]").html("'.$req[7].'");
                                                            $(".wday").html("'.$req[0].'");
                                                        </script>';
                                    for($i = 5;$i < 60;$i+=5){
                                        if($i < $req[3] && $real_clock == $req[5]){
                                            $data .= '<script>
                                                        $("td[clockid='.$i.']").css("background","");
                                                        $("td[clockid='.$i.']").html("");
                                                            $(".wday").html("'.$req[0].'");
                                                        </script>';
                                        }
                                        if($i > $req[4] && $real_clock == $req[6]){
                                            $data .= '<script>
                                                        $("td[clockid='.$i.']").css("background","");
                                                        $("td[clockid='.$i.']").html("");
                                                            $(".wday").html("'.$req[0].'");
                                                        </script>';
                                        }
                                    }
                                }
                                if($req[3] == '00' && $req[4] != '00'){
                                    $data .= '<script>
                                                        $("td[clockid]").css("background","green");
                                                        $("td[clockid]").html("'.$req[7].'");
                                                            $(".wday").html("'.$req[0].'");
                                                        </script>';
                                    for($i = 5;$i < 60;$i+=5){
                                        if($i < $req[3] && $real_clock == $req[5]){
                                            $data .= '<script>
                                                        $("td[clockid='.$i.']").css("background","");
                                                        $("td[clockid='.$i.']").html("");
                                                            $(".wday").html("'.$req[0].'");
                                                        </script>';
                                        }
                                        if($i > $req[4] && $real_clock == $req[6]){
                                            $data .= '<script>
                                                        $("td[clockid='.$i.']").css("background","");
                                                        $("td[clockid='.$i.']").html("");
                                                            $(".wday").html("'.$req[0].'");
                                                        </script>';
                                        }
                                    }
                                }
                            
                            
                            $g = mysql_query("  SELECT  TIME_FORMAT(start_time,'%H:%i') AS `start_time`,
                                        				TIME_FORMAT(end_time,'%H:%i') AS `end_time`,
                                                        TIME_FORMAT(start_time,'%i'),
                                                        TIME_FORMAT(end_time,'%i'),
                                                        TIME_FORMAT(start_time,'%H'),
                                                        TIME_FORMAT(end_time,'%H'),
                                                        CASE 
                                    						WHEN week_day_id = 1 THEN 'ორშ'
                                    						WHEN week_day_id = 2 THEN 'სამ'
                                    						WHEN week_day_id = 3 THEN 'ოთხ'
                                    						WHEN week_day_id = 4 THEN 'ხუთ'
                                    						WHEN week_day_id = 5 THEN 'პარ'
                                    						WHEN week_day_id = 6 THEN 'შაბ'
                                    						WHEN week_day_id = 7 THEN 'კვი'
                                    				    END AS `week_day`
                                                FROM `week_day_graphic`
                                                WHERE project_id = '$project_id' AND week_day_id = '$wday' AND actived = 1 AND type = 2 ");
                            while ($gg = mysql_fetch_array($g)){
                                for($i = 5;$i < 60;$i+=5){
                                if($gg[4] == $real_clock && $gg[2]=='00' && $gg[3]=='00'){
                                    $data .= '<script>
                                                $("td[clockid='.$i.']").css("background","yellow");
                                                $(".wday").html("'.$gg[6].'")
                                                    $("td[clockid]").html("");
                                              </script>';
                                }elseif($gg[4] == $real_clock && $gg[2]!='00' && $gg[3]!='00' && $gg[2]< $i){
                                    $data .= '<script>
                                                $("td[clockid='.$i.']").css("background","yellow");
                                                $(".wday").html("'.$gg[6].'");
                                                    $("td[clockid]").html("");
                                              </script>';
                                }elseif($gg[5] == $real_clock && $gg[2]!='00' && $gg[3]!='00' && $gg[3]> $i){
                                        $data .= '<script>
                                                    $("td[clockid='.$i.']").css("background","yellow");
                                                    $(".wday").html("'.$gg[6].'");
                                                        $("td[clockid]").html("");
                                                  </script>';
                                    }
                                }
                            }
                            
                        $data .= '
                	   
                    </tr>
                </table>
                </div>
            </fieldset>
        </div>';
    return $data;
}

function GetPage($res,$increment){
	if ($res[id]=='') {
		$incr_id=increment(project);
	}else{
		$incr_id=$res[id];
	}
	
	$data  .= '
	
	<div id="dialog-form">
	    <fieldset style="width: 260px; height: 255px; float: left;">
	       <legend>ძირითადი ინფორმაცია</legend>
			<table class="dialog-form-table">
	           <tr>
	               <td colspan="2"><label for="incomming_cat_1_1_1">დასახელება</label></td>
    	       </tr>
	           <tr>
	               <td colspan="2"><input id="project_name" style="resize: vertical;width: 250px;" value="'.$res[name].'"></td>
    	       </tr>
	       		<tr>
	               <td colspan="2"><label style="margin-top: 30px;" for="incomming_comment">ტიპი</label></td>
	           </tr>
	           <tr>
               		<td>
						<select style="margin-top: 10px; width: 257px;"  id="project_type">'. Get_type($res[type_id]).'</select>
					</td>
	               
	           </tr>
			   <tr>
                             
        	       <td><label style="margin-top: 30px;" for="client_person_phone2">შექმნის თარიღი</label></td>
               </tr>
    	       <tr>
                   <td><input id="project_add_date" type="text" value="'.$res[create_date].'"></td>
               </tr>
	       </table>
		 </fieldset>
	    
	    
        <div id="side_menu1" style="float: left;height: 273px; width: 80px;margin-left: 10px; background: #272727; color: #FFF;margin-top: 6px;">
	       <spam class="phone" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side1(\'phone\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/info.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">ნომერი</div></spam>
           <spam class="holiday" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side1(\'holiday\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/holiday.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">სამუშაო<br>დღე/სთ</div></spam>
	       <spam class="import" style="display: none;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side1(\'import\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/import.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">იმპორტი</div></spam>
	       <spam class="actived" style="display: none;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side1(\'actived\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/actived.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">აქტივაცია</div></spam>
	    </div>
	    
	    <div style="width: 790px; float: left; margin-left: 10px;" id="right_side_project">
            <fieldset style="display:none;" id="phone">
                <legend>ნომერი</legend>
	            <span id="hide_said_menu_number" class="hide_said_menu">x</span>
                <div class="margin_top_10">           
	            <div id="button_area">
                    <button id="add_number">დამატება</button>
					<button id="delete_number">წაშლა</button>
                </div>
				<table class="display" id="table_number" style="width: 100%;">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 30%;">ნომერი</th>
                            <th style="width: 20%;">რიგი</th>
                            <th style="width: 30%;">შიდა ნომ.</th>
                            <th style="width: 20%;">სცენარი</th>
							<th style="width: 25px;" class="check">&nbsp;</th>
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
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
							<th>
				            	<div class="callapp_checkbox">
				                    <input type="checkbox" id="check-all-number" name="check-all" />
				                    <label style="margin-top: 3px;" for="check-all-number"></label>
				                </div>
				            </th>
						</tr>
                    </thead>
                </table>
	            </div>
		</fieldset>
                       
                       <fieldset style="display:none;" id="holiday">
                <legend>სამუშაო დღე/სთ</legend>
	            <span class="hide_said_menu">x</span>
	    <style>
	    #work_table{
	    
	    width: 100%;
	    margin-top:15px;
	    }
	    #work_table td,#work_table th{
	    border: 1px solid;
        font-size: 11px;
        font-weight: normal;
        text-align: center;
	    }
	    .im_border{
	    border:1px solid;
	    }
        #work_table td input{
        display:none;
        }
	    </style>
               
	            <table class="dialog-form-table" id="work_table">
                    <tr>
                        <th style="width: ;"></th>
                	    <th style="width: ;">00:00</th>
                	    <th style="width: ;">01:00</th>
                	    <th style="width: ;">02:00</th>
                	    <th style="width: ;">03:00</th>
                	    <th style="width: ;">04:00</th>
                	    <th style="width: ;">05:00</th>
                	    <th style="width: ;">06:00</th>
                	    <th style="width: ;">07:00</th>
                	    <th style="width: ;">08:00</th>
                	    <th style="width: ;">09:00</th>
                	    <th style="width: ;">10:00</th>
                	    <th style="width: ;">11:00</th>
                	    <th style="width: ;">12:00</th>
                	    <th style="width: ;">13:00</th>
                	    <th style="width: ;">14:00</th>
	                    <th style="width: ;">15:00</th>
                	    <th style="width: ;">16:00</th>
                	    <th style="width: ;">17:00</th>
                	    <th style="width: ;">18:00</th>
                	    <th style="width: ;">19:00</th>
	                    <th style="width: ;">20:00</th>
                	    <th style="width: ;">21:00</th>
                	    <th style="width: ;">22:00</th>
                	    <th style="width: ;">23:00</th>
                    </tr>
    	            <tr id="wday1">
                        <td onclick="OpenWeek(1)">ორშ</td>
                	    <td style="" clock="0"  check_clock="" wday="1" ></td>
                	    <td style="" clock="1"  check_clock="" wday="1" ></td>
                	    <td style="" clock="2"  check_clock="" wday="1" ></td>
                	    <td style="" clock="3"  check_clock="" wday="1" ></td>
                	    <td style="" clock="4"  check_clock="" wday="1" ></td>
                	    <td style="" clock="5"  check_clock="" wday="1" ></td>
                	    <td style="" clock="6"  check_clock="" wday="1" ></td>
                	    <td style="" clock="7"  check_clock="" wday="1" ></td>
                	    <td style="" clock="8"  check_clock="" wday="1" ></td>
                	    <td style="" clock="9"  check_clock="" wday="1" ></td>
                	    <td style="" clock="10"  check_clock="" wday="1" ></td>
                	    <td style="" clock="11"  check_clock="" wday="1" ></td>
                	    <td style="" clock="12"  check_clock="" wday="1" ></td>
                	    <td style="" clock="13"  check_clock="" wday="1" ></td>
                	    <td style="" clock="14"  check_clock="" wday="1" ></td>
	                    <td style="" clock="15"  check_clock="" wday="1" ></td>
                	    <td style="" clock="16"  check_clock="" wday="1" ></td>
                	    <td style="" clock="17"  check_clock="" wday="1" ></td>
                	    <td style="" clock="18"  check_clock="" wday="1" ></td>
                	    <td style="" clock="19"  check_clock="" wday="1" ></td>
	                    <td style="" clock="20"  check_clock="" wday="1" ></td>
                	    <td style="" clock="21"  check_clock="" wday="1" ></td>
                	    <td style="" clock="22"  check_clock="" wday="1" ></td>
                	    <td style="" clock="23"  check_clock="" wday="1" ></td>
                    </tr>
	                <tr id="wday2">
                        <td onclick="OpenWeek(2)">სამ</td>
                	    <td style="" clock="0"  check_clock="" wday="2" ></td>
                	    <td style="" clock="1"  check_clock="" wday="2" ></td>
                	    <td style="" clock="2"  check_clock="" wday="2" ></td>
                	    <td style="" clock="3"  check_clock="" wday="2" ></td>
                	    <td style="" clock="4"  check_clock="" wday="2" ></td>
                	    <td style="" clock="5"  check_clock="" wday="2" ></td>
                	    <td style="" clock="6"  check_clock="" wday="2" ></td>
                	    <td style="" clock="7"  check_clock="" wday="2" ></td>
                	    <td style="" clock="8"  check_clock="" wday="2" ></td>
                	    <td style="" clock="9"  check_clock="" wday="2" ></td>
                	    <td style="" clock="10"  check_clock="" wday="2" ></td>
                	    <td style="" clock="11"  check_clock="" wday="2" ></td>
                	    <td style="" clock="12"  check_clock="" wday="2" ></td>
                	    <td style="" clock="13"  check_clock="" wday="2" ></td>
                	    <td style="" clock="14"  check_clock="" wday="2" ></td>
	                    <td style="" clock="15"  check_clock="" wday="2" ></td>
                	    <td style="" clock="16"  check_clock="" wday="2" ></td>
                	    <td style="" clock="17"  check_clock="" wday="2" ></td>
                	    <td style="" clock="18"  check_clock="" wday="2" ></td>
                	    <td style="" clock="19"  check_clock="" wday="2" ></td>
	                    <td style="" clock="20"  check_clock="" wday="2" ></td>
                	    <td style="" clock="21"  check_clock="" wday="2" ></td>
                	    <td style="" clock="22"  check_clock="" wday="2" ></td>
                	    <td style="" clock="23"  check_clock="" wday="2" ></td>
                    </tr>
	                <tr id="wday3">
                        <td onclick="OpenWeek(3)">ოთხ</td>
                	    <td style="" clock="0"  check_clock="" wday="3" ></td>
                	    <td style="" clock="1"  check_clock="" wday="3" ></td>
                	    <td style="" clock="2"  check_clock="" wday="3" ></td>
                	    <td style="" clock="3"  check_clock="" wday="3" ></td>
                	    <td style="" clock="4"  check_clock="" wday="3" ></td>
                	    <td style="" clock="5"  check_clock="" wday="3" ></td>
                	    <td style="" clock="6"  check_clock="" wday="3" ></td>
                	    <td style="" clock="7"  check_clock="" wday="3" ></td>
                	    <td style="" clock="8"  check_clock="" wday="3" ></td>
                	    <td style="" clock="9"  check_clock="" wday="3" ></td>
                	    <td style="" clock="10"  check_clock="" wday="3" ></td>
                	    <td style="" clock="11"  check_clock="" wday="3" ></td>
                	    <td style="" clock="12"  check_clock="" wday="3" ></td>
                	    <td style="" clock="13"  check_clock="" wday="3" ></td>
                	    <td style="" clock="14"  check_clock="" wday="3" ></td>
	                    <td style="" clock="15"  check_clock="" wday="3" ></td>
                	    <td style="" clock="16"  check_clock="" wday="3" ></td>
                	    <td style="" clock="17"  check_clock="" wday="3" ></td>
                	    <td style="" clock="18"  check_clock="" wday="3" ></td>
                	    <td style="" clock="19"  check_clock="" wday="3" ></td>
	                    <td style="" clock="20"  check_clock="" wday="3" ></td>
                	    <td style="" clock="21"  check_clock="" wday="3" ></td>
                	    <td style="" clock="22"  check_clock="" wday="3" ></td>
                	    <td style="" clock="23"  check_clock="" wday="3" ></td>
                    </tr>
	                <tr id="wday4">
                        <td onclick="OpenWeek(4)">ხუთ</td>
                	    <td style="" clock="0"  check_clock="" wday="4" ></td>
                	    <td style="" clock="1"  check_clock="" wday="4" ></td>
                	    <td style="" clock="2"  check_clock="" wday="4" ></td>
                	    <td style="" clock="3"  check_clock="" wday="4" ></td>
                	    <td style="" clock="4"  check_clock="" wday="4" ></td>
                	    <td style="" clock="5"  check_clock="" wday="4" ></td>
                	    <td style="" clock="6"  check_clock="" wday="4" ></td>
                	    <td style="" clock="7"  check_clock="" wday="4" ></td>
                	    <td style="" clock="8"  check_clock="" wday="4" ></td>
                	    <td style="" clock="9"  check_clock="" wday="4" ></td>
                	    <td style="" clock="10"  check_clock="" wday="4" ></td>
                	    <td style="" clock="11"  check_clock="" wday="4" ></td>
                	    <td style="" clock="12"  check_clock="" wday="4" ></td>
                	    <td style="" clock="13"  check_clock="" wday="4" ></td>
                	    <td style="" clock="14"  check_clock="" wday="4" ></td>
	                    <td style="" clock="15"  check_clock="" wday="4" ></td>
                	    <td style="" clock="16"  check_clock="" wday="4" ></td>
                	    <td style="" clock="17"  check_clock="" wday="4" ></td>
                	    <td style="" clock="18"  check_clock="" wday="4" ></td>
                	    <td style="" clock="19"  check_clock="" wday="4" ></td>
	                    <td style="" clock="20"  check_clock="" wday="4" ></td>
                	    <td style="" clock="21"  check_clock="" wday="4" ></td>
                	    <td style="" clock="22"  check_clock="" wday="4" ></td>
                	    <td style="" clock="23"  check_clock="" wday="4" ></td>
                    </tr>
	                <tr id="wday5">
                        <td onclick="OpenWeek(5)">პარ</td>
                	    <td style="" clock="0"  check_clock="" wday="5" ></td>
                	    <td style="" clock="1"  check_clock="" wday="5" ></td>
                	    <td style="" clock="2"  check_clock="" wday="5" ></td>
                	    <td style="" clock="3"  check_clock="" wday="5" ></td>
                	    <td style="" clock="4"  check_clock="" wday="5" ></td>
                	    <td style="" clock="5"  check_clock="" wday="5" ></td>
                	    <td style="" clock="6"  check_clock="" wday="5" ></td>
                	    <td style="" clock="7"  check_clock="" wday="5" ></td>
                	    <td style="" clock="8"  check_clock="" wday="5" ></td>
                	    <td style="" clock="9"  check_clock="" wday="5" ></td>
                	    <td style="" clock="10"  check_clock="" wday="5" ></td>
                	    <td style="" clock="11"  check_clock="" wday="5" ></td>
                	    <td style="" clock="12"  check_clock="" wday="5" ></td>
                	    <td style="" clock="13"  check_clock="" wday="5" ></td>
                	    <td style="" clock="14"  check_clock="" wday="5" ></td>
	                    <td style="" clock="15"  check_clock="" wday="5" ></td>
                	    <td style="" clock="16"  check_clock="" wday="5" ></td>
                	    <td style="" clock="17"  check_clock="" wday="5" ></td>
                	    <td style="" clock="18"  check_clock="" wday="5" ></td>
                	    <td style="" clock="19"  check_clock="" wday="5" ></td>
	                    <td style="" clock="20"  check_clock="" wday="5" ></td>
                	    <td style="" clock="21"  check_clock="" wday="5" ></td>
                	    <td style="" clock="22"  check_clock="" wday="5" ></td>
                	    <td style="" clock="23"  check_clock="" wday="5" ></td>
                    </tr>
	                <tr id="wday6">
                        <td onclick="OpenWeek(6)">შაბ</td>
                	    <td style="" clock="0"  check_clock="" wday="6" ></td>
                	    <td style="" clock="1"  check_clock="" wday="6" ></td>
                	    <td style="" clock="2"  check_clock="" wday="6" ></td>
                	    <td style="" clock="3"  check_clock="" wday="6" ></td>
                	    <td style="" clock="4"  check_clock="" wday="6" ></td>
                	    <td style="" clock="5"  check_clock="" wday="6" ></td>
                	    <td style="" clock="6"  check_clock="" wday="6" ></td>
                	    <td style="" clock="7"  check_clock="" wday="6" ></td>
                	    <td style="" clock="8"  check_clock="" wday="6" ></td>
                	    <td style="" clock="9"  check_clock="" wday="6" ></td>
                	    <td style="" clock="10"  check_clock="" wday="6" ></td>
                	    <td style="" clock="11"  check_clock="" wday="6" ></td>
                	    <td style="" clock="12"  check_clock="" wday="6" ></td>
                	    <td style="" clock="13"  check_clock="" wday="6" ></td>
                	    <td style="" clock="14"  check_clock="" wday="6" ></td>
	                    <td style="" clock="15"  check_clock="" wday="6" ></td>
                	    <td style="" clock="16"  check_clock="" wday="6" ></td>
                	    <td style="" clock="17"  check_clock="" wday="6" ></td>
                	    <td style="" clock="18"  check_clock="" wday="6" ></td>
                	    <td style="" clock="19"  check_clock="" wday="6" ></td>
	                    <td style="" clock="20"  check_clock="" wday="6" ></td>
                	    <td style="" clock="21"  check_clock="" wday="6" ></td>
                	    <td style="" clock="22"  check_clock="" wday="6" ></td>
                	    <td style="" clock="23"  check_clock="" wday="6" ></td>
                    </tr>
	                <tr id="wday7">
                        <td onclick="OpenWeek(7)">კვი</td>
                	    <td style="" clock="0"  check_clock="" wday="7" ></td>
                	    <td style="" clock="1"  check_clock="" wday="7" ></td>
                	    <td style="" clock="2"  check_clock="" wday="7" ></td>
                	    <td style="" clock="3"  check_clock="" wday="7" ></td>
                	    <td style="" clock="4"  check_clock="" wday="7" ></td>
                	    <td style="" clock="5"  check_clock="" wday="7" ></td>
                	    <td style="" clock="6"  check_clock="" wday="7" ></td>
                	    <td style="" clock="7"  check_clock="" wday="7" ></td>
                	    <td style="" clock="8"  check_clock="" wday="7" ></td>
                	    <td style="" clock="9"  check_clock="" wday="7" ></td>
                	    <td style="" clock="10"  check_clock="" wday="7" ></td>
                	    <td style="" clock="11"  check_clock="" wday="7" ></td>
                	    <td style="" clock="12"  check_clock="" wday="7" ></td>
                	    <td style="" clock="13"  check_clock="" wday="7" ></td>
                	    <td style="" clock="14"  check_clock="" wday="7" ></td>
	                    <td style="" clock="15"  check_clock="" wday="7" ></td>
                	    <td style="" clock="16"  check_clock="" wday="7" ></td>
                	    <td style="" clock="17"  check_clock="" wday="7" ></td>
                	    <td style="" clock="18"  check_clock="" wday="7" ></td>
                	    <td style="" clock="19"  check_clock="" wday="7" ></td>
	                    <td style="" clock="20"  check_clock="" wday="7" ></td>
                	    <td style="" clock="21"  check_clock="" wday="7" ></td>
                	    <td style="" clock="22"  check_clock="" wday="7" ></td>
                	    <td style="" clock="23"  check_clock="" wday="7" ></td>
                    </tr>
	            </table>
	            <table class="dialog-form-table">
                    <tr>
                       <td style="width: 210px;"><label for="queue_scenar">საანგარიშო პერიოდი</label></td>    
	                   <td></td>
                    </tr>
    	            <tr>
                       <td><input style="width: 150px; float: left;" id="start_date_holi" type="text"><span style="margin-top: 5px;float: left;">-დან</span></td>
	                   <td><input style="width: 150px; float: left;" id="end_date_holi" type="text"><span style="margin-top: 5px;float: left;">-მდე</span></td>
                    </tr>
	            </table>
	            <table class="dialog-form-table">
                    <tr>
	                   <td><input id="holiday_all" type="checkbox"></td>
                       <td style="width: ;"><label for="holiday_id">დღესასწაულები</label></td>
                	   <td style="width: ;"><select id="holiday_id" style="width:253px;">'.GetHoliday().'</select></td>
	                   <td style="width: ;"><button id="add_holiday">დამატება</button></td>
                	   <td style="width: ;"><button id="delete_holiday">წაშლა</button></td>
                    </tr>
	            </table>
                <table class="display" id="table_holiday" >
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 29%;">თარიღი</th>
                            <th style="width: 40%;;">სახელი</th>
                            <th style="width: 29%;">კატეგორია</th>
							<th style="width: 30px;" class="check">&nbsp;</th>
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
							<th>
				            	<div class="callapp_checkbox">
				                    <input type="checkbox" id="check-all-holiday" name="check-all" />
				                    <label style="margin-top: 3px;" for="check-all-holiday"></label>
				                </div>
				            </th>
						</tr>
                    </thead>
                </table>
            </fieldset>
                       
        <fieldset style="display:none;" id="import">
                <legend>იმპორტი</legend>
	            <span id="hide_said_menu_number" class="hide_said_menu">x</span>
                <div class="margin_top_10">           
	            <div id="button_area">
                       
                    <button id="download_exel">შაბლონის ჩამოტვირთვა</button>
                    <button id="open_choseFile">ბაზის ატვირთვა</button>
					<button id="add_import">დამატება</button>
                    <button id="delete_import">წაშლა</button>
                </div>
				<table class="display" id="table_import" >
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 70px;">სახელი</th>
                            <th style="width: 70px;">გვარი</th>
                            <th style="width: 70px;">პირადი ნომერი</th>
                            <th style="width: 95px;">ტელეფონი 1</th>
                            <th style="width: 95px;">ტელეფონი 2</th>
							<th style="width: 11px;" class="check">&nbsp;</th>
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
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
							<th>
				            	<div class="callapp_checkbox">
				                    <input type="checkbox" id="check-all-import" name="check-all" />
				                    <label style="margin-top: 3px;" for="check-all-import"></label>
				                </div>
				            </th>
						</tr>
                    </thead>
                </table>
	            </div>
		</fieldset>
                       
        <fieldset style="display:none;" id="actived">
                <legend>აქტივაცია</legend>
	            <span id="hide_said_menu_number" class="hide_said_menu">x</span>
                <div class="margin_top_10">           
	            <div id="button_area">
                    <button id="add_import_actived">აქტივაცია</button>
                    <button id="delete_import_actived">წაშლა</button>
                </div>
                </div>
				<table class="display" id="table_import_actived" >
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 70px;">სახელი</th>
                            <th style="width: 70px;">გვარი</th>
                            <th style="width: 70px;">პირადი ნომერი</th>
                            <th style="width: 95px;">ტელეფონი 1</th>
                            <th style="width: 95px;">ტელეფონი 2</th>
							<th style="width: 11px;" class="check">&nbsp;</th>
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
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
							<th>
				            	<div class="callapp_checkbox">
				                    <input type="checkbox" id="check-all-import-actived" name="check-all" />
				                    <label style="margin-top: 3px;" for="check-all-import-actived"></label>
				                </div>
				            </th>
						</tr>
                    </thead>
                </table>
	            </div>
		</fieldset>
    	</div>
	</div>
	</div>
	<input type="hidden" value="'.$res[id].'" id="project_hidden_id">
	<input type="hidden" value="'.$incr_id.'" id="hidden_project_id">';

	return $data;
}

function GetDialogWeek($week_id,$project_id){
    switch ($week_id) {
        case 1:
            $lang = 'ორშაბათი';
        break;
        case 2:
            $lang = 'სამშაბათი';
        break;
        case 3:
            $lang = 'ოთხშაბათი';
        break;
        case 4:
            $lang = 'ხუთშაბათი';
        break;
        case 5:
            $lang = 'პარასკევი';
        break;
        case 6:
            $lang = 'შაბათი';
        break;
        case 7:
            $lang = 'კვირა';
        break;
    }
    $data = '<div id="dialog-form">
        	    <fieldset>
        	       <legend>'.$lang.'</legend>
                    <input id="wday" type="hidden" value="'.$week_id.'">
                    <div id="button_area">
                        <button id="add_week">დამატება</button>
                        <button id="delete_week">წაშლა</button>
                    </div>
    				<table class="display" id="table_week" >
                        <thead>
                            <tr id="datatable_header">
                                <th>ID</th>
                                <th style="width: 70px;">დასაწყისი</th>
                                <th style="width: 70px;">დასასრული</th>
                                <th style="width: 70px;">სადგური</th>
                                <th style="width: 95px;">სამუშაოს ტიპი</th>
                                <th style="width: 95px;">ენა</th>
                                <th style="width: 95px;">ინფ. წყარო</th>
    							<th style="width: 11px;" class="check">&nbsp;</th>
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
                                <th>
                                    <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                                </th>
                                <th>
                                    <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                                </th>
                                <th>
                                    <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                                </th>
    							<th>
    				            	<div class="callapp_checkbox">
    				                    <input type="checkbox" id="check-all-import-actived" name="check-all" />
    				                    <label style="margin-top: 3px;" for="check-all-import-actived"></label>
    				                </div>
    				            </th>
    						</tr>
                        </thead>
                    </table>
                </fieldset>
             </div>
            ';
    return $data;
}

function GetDialogWeekAdd($week_id,$project_id){
    $data = '<div id="dialog-form">
        	    <fieldset>
        	       <legend>ძირითადი ინფორმაცია</legend>
                    <table class="dialog-form-table">
                        <tr>
                           <td ><button id="addlang">სასაუბრო ენა</button></td>
                           <td ><button id="addinfosorce">ინფორმაციის წყარო</button></td>
                        </tr>
	               </table>
                   <table class="dialog-form-table">
                    <tr>
                       <td style="width: 99px;">სამუშაო<br>იწყება</td>
                       <td style="width: 99px;">სამუშაო<br>მთავრდება</td>
                       <td style="width: 99px;">სადგურის<br>რაოდენობა</td>
                       <td >სამუშაოს<br>ტიპი</td>
                    </tr>
                    <tr>
                        <td><input id="start_time" type="text" style="width: 60px;"></td>
                        <td><input id="end_time" type="text" style="width: 60px;"></td>
                        <td><input id="ext_number" type="number" style="width: 60px;" min="1" value="1"></td>
                        <td><select id="type"><option value="1">სამუშაო სთ.</option><option value="2">არა სამუშაო სთ.</option></select></td>
                    </tr>
	              </table>
                </fieldset>
             </div>
            ';
    return $data;
}

function GetDialogLangAdd($week_id,$project_id){
    $data = '<div id="dialog-form">
        	    <fieldset>
        	       <legend>ძირითადი ინფორმაცია</legend>
                    <table class="dialog-form-table">
                        <tr>
                           <td>სასაუბრო ენა</td>
                        </tr>
                        <tr>
                           <td><select id="spoken_lang_id">'.GetLang().'</select></td>
                        </tr>
	               </table>
                    <div id="button_area">
                        <button id="add_lang">დამატება</button>
                        <button id="delete_lang">წაშლა</button>
                    </div>
    				<table class="display" id="table_lang" style="width: 100%;">
                        <thead>
                            <tr id="datatable_header">
                                <th>ID</th>
                                <th style="width: 70px;">სასაუბრო ენა</th>
    							<th style="width: 11px;" class="check">&nbsp;</th>
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
    				            	<div class="callapp_checkbox">
    				                    <input type="checkbox" id="check-all-lang" name="check-all" />
    				                    <label style="margin-top: 3px;" for="check-all-lang"></label>
    				                </div>
    				            </th>
    						</tr>
                        </thead>
                    </table>
                </fieldset>
             </div>
            ';
    return $data;
}

function GetDialogInfoSorceAdd($week_id,$project_id){
    $data = '<div id="dialog-form">
        	    <fieldset>
        	       <legend>ძირითადი ინფორმაცია</legend>
                    <table class="dialog-form-table">
                        <tr>
                           <td>ინფორმაციის წყარო</td>
                        </tr>
                        <tr>
                           <td><select id="information_source_id">'.GetInfoSource().'</select></td>
                        </tr>
	               </table>
                   <div id="button_area">
                        <button id="add_infosorce">დამატება</button>
                        <button id="delete_infosorce">წაშლა</button>
                   </div>
                   <table class="display" id="table_infosorce" style="width: 100%;">
                        <thead>
                            <tr id="datatable_header">
                                <th>ID</th>
                                <th style="width: 70px;">ინფორმაციის წყარო</th>
    							<th style="width: 11px;" class="check">&nbsp;</th>
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
    				            	<div class="callapp_checkbox">
    				                    <input type="checkbox" id="check-all-infosorce" name="check-all" />
    				                    <label style="margin-top: 3px;" for="check-all-infosorce"></label>
    				                </div>
    				            </th>
    						</tr>
                        </thead>
                    </table>
                </fieldset>
             </div>
            ';
    return $data;
}

function increment($table){

    $result   		= mysql_query("SHOW TABLE STATUS LIKE '$table'");
    $row   			= mysql_fetch_array($result);
    $increment   	= $row['Auto_increment'];
    $next_increment = $increment+1;
    mysql_query("ALTER TABLE '$table' AUTO_INCREMENT=$next_increment");

    return $increment;
}


?>