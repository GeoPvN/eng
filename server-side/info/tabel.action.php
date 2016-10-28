<?php
/* ******************************
 *	Workers aJax actions
 * ******************************
 */
include('../../includes/classes/core.php');



$action 	= $_REQUEST['act'];
$user_id	= $_SESSION['USERID'];
$error 		= '';
$data 		= '';

switch ($action) {
	case 'get_come_in_page':
		$action     = $_REQUEST['action'];

 		$page		= GetComeIn($action);
 		$data		= array('page'	=> $page);
		
		break; 
	case 'get_deep':	    
	
	    $page		= GetBalanceDeep();
	    $data		= array('page'	=> $page);
	
	    break;
    case 'gdl':
    
        $page		= gdl();
        $data		= array('page'	=> $page);
    
        break;
	    
    case 'check_status':
        
        $person_id     = $_SESSION[USERID];
        
        $res = mysql_fetch_assoc(mysql_query("SELECT  actived
                                              FROM  worker_action
                                              WHERE  person_id = 1
                                              ORDER BY id DESC
                                              LIMIT 1"));
	
    	if($res[actived] == 0){
    		$check = 1;
    	}else{
    	    $check = 0;
    	}
        $data		= array('check'	=> $check);
    
        break;
	case 'save_act':
	    
		$person_id     = $_SESSION[USERID];
		$pwd           = $_REQUEST['pwd'];
		$action        = $_REQUEST['action'];
		$comment_start = $_REQUEST['comment_start'];
		$comment_end   = $_REQUEST['comment_end'];
		$check         = CheckPassword($person_id, $pwd);
		
            switch ($action){
// 				case '1' :
// 					if(CheckHere($person_id)){
// 						$status = WorkerStart($person_id);
// 					}else{
// 						$error = "შეცდომა:  უკვე არის აღრიცხული";
// 					}			
// 					break;
// 				case '2' :
// 					if(!CheckHere($person_id)){
// 						$status = WorkerEnd($person_id);
// 					}else{
// 						$error = "შეცდომა:  არ  არის აღრიცხული";
// 					}
// 					break;
				case '3' :
				    $check=CheckGoTimeOut($person_id);
				    
				    if ($check==3) {
				        $error = "შეცდომა: არ გაქვთ გაკეთებული მოსვლა";
				    }else {
    				    if($check==1){
    					    $status = GoTimeOut($person_id,$comment_start);
    					    
    				    }else{
    						$error = "შეცდომა:  უკვე არის შესვენებაზე";
    					}
				    }
					break;
				case '4' :
				    if(!CheckBackTimeOut($person_id)){
					    $status = BackTimeOut($person_id,$comment_end);
				    }else{
						$error = "შეცდომა:  ჯერ შესვენებაზე გადი";
					}
					break;
				default:
					break;
			}
			
			$data = array('status'	=> $status[0], 'done' => $status[1], 'timer'=>$status[2]);
		
		break;
	case 'get_balance' :
	    
		$page = GetBalance();
 		$data = array('page'	=> $page);
 		
 		break;
	case 'break_checker' :
	    $logout_actions = $_REQUEST['logout_actions'];
	    $res_pay = mysql_num_rows(mysql_query(" SELECT work_activities.id
                                                FROM `work_activities`
                                                WHERE work_activities.id = $logout_actions AND work_activities.pay = 2"));
	    $date_checker = mysql_fetch_array(mysql_query(" SELECT IF(TIME(NOW()) >= start_break AND TIME(NOW()) <= end_break,1,0) AS `checker`
                                            	        FROM `work_real`
                                            	        JOIN work_real_break ON work_real.id = work_real_break.work_real_id AND work_real_break.work_activities_id = $logout_actions
                                            	        JOIN work_activities ON work_real_break.work_activities_id = work_activities.id AND date_checker = 1
                                            	        WHERE work_real.user_id = $_SESSION[USERID] AND DATE(work_real.date) = DATE(NOW())
                                            	        ORDER BY work_real_break.id DESC
                                                        LIMIT 1;"));
	    if($res_pay==0){
	        if($date_checker[0] == 1 || $date_checker[0] == ''){
	           $data = array('checker'	=> 1);
	        }else{
	           $data = array('checker'	=> 0);
	        }
	    }else{
    	    $break_checker_count = mysql_num_rows(mysql_query(" SELECT worker_action.id
                                                                FROM `worker_action`
                                                                JOIN worker_action_break ON worker_action.id = worker_action_break.worker_action_id AND ISNULL(worker_action_break.end_date)
                                                                JOIN work_activities ON worker_action_break.work_activities_id = work_activities.id AND work_activities.pay = 2 AND (work_activities.all_limit = 1 or work_activities.id = $logout_actions)
                                                                WHERE DATE(worker_action.start_date) = DATE(NOW())"));
    	    $cnobar = mysql_fetch_assoc(mysql_query("SELECT `limit` FROM work_activities WHERE id = $logout_actions"));
    	    if($break_checker_count == $cnobar[limit]){

    	        $data = array('checker'	=> 0);
    	    }else{
    	        if($date_checker[0] == 1 || $date_checker[0] == ''){
    	           $data = array('checker'	=> 1);
    	        }else{
    	           $data = array('checker'	=> 0);
    	        }
    	    }
	    }
	    break;
    default:
       $error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Tabel Functions
 * ******************************
 */

function WorkerStart($person_id){
	
	$date = date('Y-m-d H:i:s');
	
	mysql_query("INSERT INTO  worker_action
				     (person_id, start_date, actived)
				 VALUES
				     ($person_id, '$date', 1)
			    ");
	
	return  $data=1;
}

function WorkerEnd($person_id){
	
	$date = date('Y-m-d H:i:s');
	
	$res = mysql_fetch_assoc(mysql_query("SELECT  MAX(id) AS `id`
											FROM  worker_action
											WHERE person_id = $person_id"));
	
	mysql_query("UPDATE worker_action
				SET
				end_date = TIME_FORMAT(NOW(),'%H:%i:%s'),
				actived  = 0
				WHERE    person_id = $person_id AND id = $res[id]");
	
	return  $data=4;
}

function GoTimeOut($person_id,$comment_start){
    $user_id        = $_SESSION['USERID'];
    $logout_actions = $_REQUEST['logout_actions'];
    $logout_comment = $_REQUEST['logout_comment'];
	$date = date('H:i:s');
	
	$res_timer = mysql_fetch_assoc(mysql_query("SELECT IF(
                                                            ISNULL(TIME_TO_SEC(TIMEDIFF(work_activities.timer,SEC_TO_TIME(SUM(worker_action_break.end_date) - SUM(worker_action_break.start_date)))))
                                                            ,TIME_TO_SEC(work_activities.timer)
                                                            ,TIME_TO_SEC(TIMEDIFF(work_activities.timer,SEC_TO_TIME(SUM(worker_action_break.end_date) - SUM(worker_action_break.start_date))))
                                                        ) AS `timer`
                                        	    FROM `worker_action`
                                        	    JOIN worker_action_break ON worker_action.id = worker_action_break.worker_action_id AND worker_action_break.work_activities_id = $logout_actions
                                        	    JOIN work_activities ON work_activities.id = $logout_actions
                                        	    WHERE worker_action.person_id = $_SESSION[USERID] AND DATE(worker_action.start_date) = DATE(NOW())"));
	$res = mysql_fetch_assoc(mysql_query("  SELECT  MAX(id) AS `id`
											FROM  worker_action
											WHERE person_id = $person_id"));
	if($logout_actions != 0){
    mysql_query("INSERT INTO `worker_action_break`
                (`worker_action_id`, `start_date`, `end_date`, `comment_start`, `comment_end`, `work_activities_id`)
                VALUES
                ('$res[id]', TIME_FORMAT(NOW(),'%H:%i:%s'), NULL, '$logout_comment', '', '$logout_actions');");
	}
    
    
     
    mysql_query("UPDATE `user_log` SET
                        `logout_date`= NOW(),
                        `comment`='$logout_comment',
                        `work_activities_id`='$logout_actions'
                 WHERE  `user_id` = '$user_id' AND ISNULL(logout_date)");
    
    if($logout_actions == 0){
        $date = date('Y-m-d H:i:s');
        
        $res = mysql_fetch_assoc(mysql_query("SELECT  MAX(id) AS `id`
            FROM  worker_action
            WHERE person_id = $person_id"));
        
        mysql_query("UPDATE worker_action
                    SET
                    end_date = NOW(),
                    actived  = 0
                    WHERE    person_id = $person_id AND id = $res[id]");
        
        mysql_query("UPDATE `users`
                     SET	`logged` 	= 0
                     WHERE	`id` = $_SESSION[USERID]");
        
        unset($_SESSION['USERID']);
        unset($_SESSION['lifetime']);
        $data[1]=1;
    }
    $data[0]=2;
    
    
    $data[2]=$res_timer[timer];
    
    return $data;
	
}
function CheckGoTimeOut($person_id){
    $date = date('H:i:s');
    
    $res_num = mysql_fetch_assoc(mysql_query("SELECT  MAX(id) AS `id`
                                              FROM  worker_action
                                              WHERE person_id = $person_id AND actived=1"));
    
    if ($res_num[id]==null || empty($res_num[id])) {
        return 3;
    }else {
        
        $break_check = mysql_num_rows(mysql_query(" SELECT id
                                                    FROM `worker_action_break`
                                                    WHERE actived = 1 AND worker_action_id = '$res_num[id]' AND ISNULL(end_date)"));
        
        if($break_check == 0){
            return 1;
        }else{
            return 0;
        }  
    }
}

function CheckBackTimeOut($person_id){
    $date = date('H:i:s');

    $res = mysql_fetch_assoc(mysql_query("  SELECT  MAX(id) AS `id`
                                            FROM  worker_action
                                            WHERE person_id = $person_id"));

    $break_check = mysql_num_rows(mysql_query(" SELECT id
                                                FROM `worker_action_break`
                                                WHERE actived = 1 AND worker_action_id = '$res[id]' AND ISNULL(end_date)"));

    if($break_check == 1){
        return false;
    }else{
        return true;
    }
}

function BackTimeOut($person_id,$comment_end){
	$date = date('H:i:s');
	
	$res = mysql_fetch_assoc(mysql_query("  SELECT  MAX(id) AS `id`
                                			FROM  worker_action
                                			WHERE person_id = $person_id"));
	
	mysql_query("UPDATE `worker_action_break` SET
                        `end_date`=TIME_FORMAT(NOW(),'%H:%i:%s'),
	                    `comment_end`='$_REQUEST[logout_comment]'
                 WHERE worker_action_id = '$res[id]'
                 ORDER BY id DESC
                 LIMIT 1");
	$user_id        = $_SESSION['USERID'];
	
	mysql_query("INSERT INTO `user_log`
        	    (`user_id`, `session_id`, `ip`, `login_date`)
        	    VALUES
        	    ($user_id, '', '', NOW())");
	$data[0]=3;
	return $data;
}

function CheckPassword($person_id, $pwd){
	$check = false;
	$res = mysql_fetch_assoc(mysql_query("	SELECT `password` AS `pwd`
											FROM   users
											WHERE  person_id = $person_id"));
	
	$check = true;
	
	return $check;
}

function GetWorkers($action){
    
	$data = '';
	if($_SESSION[USERID] != 1){
	    $check_group=mysql_fetch_assoc(mysql_query("SELECT users.group_id
                                                    FROM users
                                                    WHERE users.id=$_SESSION[USERID] and users.actived=1"));
	    
	    if ($check_group[group_id]==1 && $action!=1 && $action!=2 && $action!=3 && $action!=4) {
	        $check_user='';
	    }else {
	       $check_user = "AND `users`.id = $_SESSION[USERID]";
	    }
	}
	
	$req = mysql_query("SELECT id, 
                              `name` 
                        FROM  `group`
	                    WHERE `group`.id !=5
						");
	$report_checker = 1;
	
    if($report_checker == 1){
	    $data = '<option value="0">ყველა</option>';
	}else{
		$data = '<option value="0" selected="selected"></option>';
	}

	while( $res = mysql_fetch_assoc($req)){	        
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}

	return $data;
}

function CheckHere($person_id){
	$check = true;
	$res = mysql_query("SELECT  id
						  FROM  worker_action
						 WHERE  person_id = $person_id AND ISNULL(end_date)");
	
	if(mysql_num_rows($res) > 0){
		$check = false;
	}
	
	return $check;
}

function gdl(){
    $res = mysql_query("SELECT id,`name` FROM `work_activities` WHERE actived = 1 ORDER BY `order` ASC");
    $option = '<option value="0">----</option>';
    while ($req = mysql_fetch_assoc($res)){
        $option .= '<option value="'.$req[id].'">'.$req[name].'</option>';
    }
    $data = '<div id="dialog-form">
        	               <fieldset style="height: auto;">
	                           <table class="dialog-form-table">
	                                <tr>
                    					<td>	
                    						<label for="logout_actions">აქტივობა</label>
                    					</td>
                    			    </tr>
                    				<tr>
                    					<td>	
                    						<select id="logout_actions" style="width:200px;">'.$option.'</select>
                    					</td>
                    			   </tr>
	                               <tr>
                    					<td>	
                    						<label for="logout_comment">კომენტარი</label>
                    					</td>
                    			   </tr>
	                               <tr>
                    					<td>	
                    						<textarea id="logout_comment" style="width:300px; height:100px;background: #fff;"></textarea>
                    					</td>
                    			   </tr>
                    		   </table>
            	           </fieldset>
            		     </div>';
    
    return $data;
}

function GetComeIn($action){	
	$data = '
	<div id="dialog-form">
 	    <fieldset style="width: 400px;height: 100%;">
	    	<legend>ძირითადი ინფორმაცია</legend>
    			<table width="100%"  cellpadding="10px" >
    				<tr	style="float: left;">
    					<td style="width: 170px;"><label for="user">მომხმარებელი</label></td>
    					<td>
    						<select id="user" class="idls">' . GetWorkers($action) . '</select>
    					</td>
    				</tr>
    				<tr	style="float: left;margin-top: 12px;display:none;" id="showTr">
    					<td style="width: 170px;"><label for="user">კომენტარი</label></td>
    					<td>
    						<textarea id="comment_start" style="background:#F8F8F8;width: 157px;border: 1px solid #75AD3B;height: 35px;"></textarea>
    					</td>
    				</tr>
    			    <tr	style="float: left;margin-top: 12px;display:none;" id="showTr1">
    					<td style="width: 170px;"><label for="user">კომენტარი</label></td>
    					<td>
    						<textarea id="comment_end" style="background:#F8F8F8;width: 157px;border: 1px solid #75AD3B;height: 35px;"></textarea>
    					</td>
    				</tr>
    			</table>
        </fieldset>						
    </div>

	<input type="hidden" id="action" value="0"/>
			
    ';
	return $data;
}

function GetBalance(){
    $user_id = $_SESSION['USERID'];
    
    $check_group = mysql_fetch_assoc(mysql_query("SELECT group_id
                                                  FROM  `users`
                                                  WHERE  id = $user_id"));
    $style = '';
    if ($check_group[group_id] !=1 && $check_group[group_id] !=3 && $check_group[group_id] !=5) {
      $style="display:none;";  
    }
    
	$data = '
	<div id="dialog-form">
 	    <fieldset style="width: 400px;">
	    	<legend>ძირითადი ინფორმაცია</legend>
			<div style=" margin-top: 2px; ">
				<div style="width: 170px; display: inline;">
			<table width="80%" class="dialog-form-table" cellpadding="10px" >
				<tr	style="float: left">
		
					<td style="width: 170px;"><label for="user">ჯგუფები</label></td>
					<td>
						<select id="user1" class="idls">' . GetWorkers(10) . '</select>
					</td>
										
				</tr>
				
			</table>		
				</div>
			</th>

		<div style="margin-left:180px;"><input type="button" id="check" value="შემოწმება" /></div>
	 </div>
    </fieldset>
	<br><br>
	<fieldset>	
		<legend>ბალანსი</legend>	
						    
		    <div id="button_area">
				<table>
    				<tr>
        				<td>
            	        	<label for="search_start" class="left">დასაწყისი</label>
                    		<input style="width: 120px; margin-left: 10px; margin-top: -5px;" type="text" name="search_start" id="search_start" class="inpt right"/>
                    	</td>
        				<td>
                        	<label for="search_end" style="margin-left: 20px;" class="left" >დასასრული</label>
                        	<input style="width: 120px; margin-left: 10px; margin-top: -5px;" type="text" name="search_end" id="search_end" class="inpt right" />
                    	</td>
        				<td>
            			    <button style="width: 120px; margin-left: 20px; margin-top: -5px; '.$style.'" id="exel_button">სრული ექსელი</button>
            			</td>
    				</tr>
			    </table>
             </div>							
			 
			<div class="inner-table" style="width: 100%; margin-top: 33px;">
			    <div id="container" style="width: 100%;">        	
            		<div id="dynamic">
                		<table class="display" id="report">
                                    <thead>
                                        <tr id="datatable_header">
                                            <th>ID</th>
                                            <th style="width: 85px !important;">თარიღი</th>
                                            <th style="width: 140px !important;">პიროვნება</th>
            			                    <th style="width: 120px !important;">ჯგუფი</th>
                                            <th style="width: 100px !important;">სამ. გეგმ. დრო</th>
                                            <th style="width: 100px !important;">სამ. ფაქტ. დრო</th>
                                            <th style="width: 100px !important;">შეს  გეგმ. დრო</th>
                						    <th style="width: 100px !important;">შეს  ფაქტ. დრო</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr class="search_header">
                                            <th class="colum_hidden">
                                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 80px !important;"/>
                                            </th>
                                            <th>
                                            	<input type="text" name="search_number" value="ფილტრი" class="search_init" style="width: 80px !important;">
                                            </th>
                                            <th>
                                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                                            </th>
            			                    <th>
                                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                                            </th>
                                            <th>
                                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                                            </th>
                                            <th>
                                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                                            </th>
                						    <th>
                                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                                            </th>
                                            <th>
                                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                                            </th>
                                        </tr>
                                   </thead>
                				   <tfoot>
                                        <tr id="datatable_header" class="search_header">
                							<th style="width: 150px"></th>
                							<th style="width: 150px"></th>
            			                    <th style="width: 150px"></th>
                						    <th style="width: 150px; text-align: right;">ჯამი :<br>სულ :</th>
                							<th style="width: 150px"></th>
                							<th style="width: 150px;"></th>
                							<th style="width: 150px;"></th>
                						    <th style="width: 150px;"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
		          </div>
		      </fieldset>
        </div>


		
    ';
	return $data;
}

function GetBalanceDeep(){
    $data = '
	<div id="dialog-form">
	<fieldset style="padding-top: 50px;">
		<legend>ბალანსი</legend>
        <div class="inner-table" style="width: 100%;">
                <table class="display" id="report_deep">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 120px !important;">Log In</th>
                            <th style="width: 120px !important;">სამ. დასაწყისი</th>
                            <th style="width: 120px !important;">შეს. გასვლა</th>
                            <th style="width: 120px !important;">შეს. დაბრუნება</th>
                            <th style="width: 120px !important;">სამ. დასრულება</th>
                            <th style="width: 120px !important;">Log Out</th>
                            <th style="width: 120px !important;">კომენტარი</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 80px !important;"/>
                            </th>
                            <th>
                            	<input type="text" name="search_number" value="ფილტრი" class="search_init" style="width: 80px !important;">
                            </th>
                            <th>
                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                            </th>
                            <th>
                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                            </th>
                            <th>
                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                            </th>
                            <th>
                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                            </th>
                            <th>
                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                            </th>
                            <th>
                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                            </th>
                        </tr>
                   </thead>
                    
                </table>
            </div>
        
		</fieldset>
    </div>



    ';
    return $data;
}

?>