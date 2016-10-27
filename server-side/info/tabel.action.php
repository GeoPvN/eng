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
	case 'save_act':
		$person_id     = $_REQUEST['user'];
		$pwd           = $_REQUEST['pwd'];
		$action        = $_REQUEST['action'];
		$comment_start = $_REQUEST['comment_start'];
		$comment_end   = $_REQUEST['comment_end'];
		$check         = CheckPassword($person_id, $pwd);
		

			 switch ($action){
				case '1' :
					if(CheckHere($person_id)){
						WorkerStart($person_id);
					}else{
						$error = "შეცდომა:  უკვე არის აღრიცხული";
					}			
					break;
				case '2' :
					if(!CheckHere($person_id)){
						WorkerEnd($person_id);
					}else{
						$error = "შეცდომა:  არ  არის აღრიცხული";
					}
					break;
				case '3' :
				    if(!CheckGoTimeOut($person_id)){
					    GoTimeOut($person_id,$comment_start);
				    }else{
						$error = "შეცდომა:  უკვე არის შესვენებაზე";
					}
					break;
				case '4' :
				    if(!CheckBackTimeOut($person_id)){
					    BackTimeOut($person_id,$comment_end);
				    }else{
						$error = "შეცდომა:  ჯერ შესვენებაზე გადი";
					}
					break;
				default:
					break;
			}
		
		break;
	case 'get_balance' :
		$page = GetBalance();
 		$data = array('page'	=> $page);
 		
 		break;
	case 'check_password' :
		$person_id = $_REQUEST['user'];
		$pwd       = $_REQUEST['pwd'];
		$check = CheckPassword($person_id, $pwd);
		
		if($check){
			$page = 'true';
			$data = array('page'	=> $page);
		}else{
			$error = "პაროლი არასწორია!";
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

function GetName($person_id){
	
	$res = mysql_fetch_assoc(mysql_query("SELECT   CONCAT(`name`,' ', `surname`)
											FROM   persons
										   WHERE   id =$person_id"));
	
	return $res['name'];
}

function WorkerStart($person_id){
	
	$date = date('Y-m-d H:i:s');
	
	mysql_query("INSERT INTO  worker_action
				     (person_id, start_date, actived)
				 VALUES
				     ($person_id, '$date', 1)
			    ");
}

function WorkerEnd($person_id){
	
	$date = date('Y-m-d H:i:s');
	
	$res = mysql_fetch_assoc(mysql_query("SELECT  MAX(id) AS `id`
											FROM  worker_action
											WHERE person_id = $person_id"));
	
	mysql_query("UPDATE worker_action
				SET
				end_date = '$date',
				actived  = 0
				WHERE    person_id = $person_id AND id = $res[id]");
}

function GoTimeOut($person_id,$comment_start){
	
	$date = date('H:i:s');
	
	$res = mysql_fetch_assoc(mysql_query("  SELECT  MAX(id) AS `id`
											FROM  worker_action
											WHERE person_id = $person_id"));
	
    mysql_query("INSERT INTO `worker_action_break`
                (`worker_action_id`, `start_date`, `end_date`, `comment_start`, `comment_end`)
                VALUES
                ('$res[id]', '$date', NULL, '$comment_start', '');");
	
}
function CheckGoTimeOut($person_id){
    $date = date('H:i:s');
    
    $res = mysql_fetch_assoc(mysql_query("SELECT  MAX(id) AS `id`
                                          FROM  worker_action
                                          WHERE person_id = $person_id"));
    
    $break_check = mysql_num_rows(mysql_query(" SELECT id
                                                FROM `worker_action_break`
                                                WHERE actived = 1 AND worker_action_id = '$res[id]' AND ISNULL(end_date)"));
    
    if($break_check == 0){
        return false;
    }else{
        return true;
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
                        `end_date`='$date',
	                    `comment_end`='$comment_end'
                 WHERE worker_action_id = '$res[id]'
                 ORDER BY id DESC
                 LIMIT 1");
}

function CheckPassword($person_id, $pwd){
	$check = false;
	$res = mysql_fetch_assoc(mysql_query("	SELECT `password` AS `pwd`
											FROM   users
											WHERE  person_id = $person_id"));
	
	
		$check = true;
	
	
	
	return $check;
}

function GetWorkers($action)
{
	$data = '';
	if($_SESSION[USERID] != 1){
	$check_user = "AND `users`.id = $_SESSION[USERID]";
	}
	switch ($action){
		case '1' :
		$req = mysql_query("    SELECT	    DISTINCT `users`.`id`,
										    user_info.`name` AS `name`
								FROM		`user_info`
		                        JOIN users ON user_info.user_id = users.id
								LEFT JOIN	`worker_action` ON `worker_action`.`person_id` = `users`.`id`
								WHERE       `users`.`group_id` in (1,3) AND `users`.`actived` = 1 $check_user
							");
			break;
		case '2' :
			
			$req = mysql_query("SELECT	    DISTINCT `users`.`id`,
										    user_info.`name` AS `name`
								FROM		`user_info`
			                    JOIN users ON user_info.user_id = users.id
								LEFT JOIN	`worker_action` ON `worker_action`.`person_id` = `users`.`id`
							    WHERE       `users`.`group_id` in (1,3) AND `users`.`actived` = 1 AND (worker_action.actived = 1 OR worker_action.actived = 3 ) $check_user
								");
			
			break;
			
		case '3' :
			
			$req = mysql_query("SELECT	    DISTINCT `users`.`id`,
										    user_info.`name` AS `name`
								FROM		`user_info`
			                    JOIN users ON user_info.user_id = users.id
								LEFT JOIN	`worker_action` ON `worker_action`.`person_id` = `users`.`id`
							    WHERE       `users`.`group_id` in (1,3) AND `users`.`actived` = 1 $check_user
								");
			
			break;
			
		case '4' :
			
			$req = mysql_query("SELECT	    DISTINCT `users`.`id`,
										    user_info.`name` AS `name`
								FROM		`user_info`
			                    JOIN users ON user_info.user_id = users.id
								LEFT JOIN `worker_action` ON `worker_action`.`person_id` = `users`.`id`
								WHERE     `users`.`group_id` in (1,3) AND `users`.`actived` = 1 $check_user
								");
			
			break;
			
		default:
			$req = mysql_query("SELECT	    DISTINCT `users`.`id`,
										    user_info.`name` AS `name`,
			                                1 AS `checker`
								FROM		`user_info`
			                    JOIN users ON user_info.user_id = users.id
								LEFT JOIN	`worker_action` ON `worker_action`.`person_id` = `users`.`id`
			                    WHERE       `users`.`group_id` in (1,3) AND `users`.`actived` = 1 $check_user
								");
			$report_checker = 1;
			break;	
	}

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

function GetComeIn($action){	
	$data = '
	<div id="dialog-form">
 	    <fieldset style="width: 400px;height: 100%;">
	    	<legend>ძირითადი ინფორმაცია</legend>
    			<table width="100%"  cellpadding="10px" >
    				<tr	style="float: left;">
    					<td style="width: 170px;"><label for="user">მომხმარებელი</label></td>
    					<td>
    						<select style="width: 163px;" id="user" class="idls">' . GetWorkers($action) . '</select>
    					</td>
    				</tr>
    				<tr	style="float: left;margin-top: 12px;display:none;" id="showTr">
    					<td style="width: 170px;"><label for="user">კომენტარი</label></td>
    					<td>
    						<textarea id="comment_start" style="background:#F8F8F8;width: 157px;height: 35px;"></textarea>
    					</td>
    				</tr>
    			    <tr	style="float: left;margin-top: 12px;display:none;" id="showTr1">
    					<td style="width: 170px;"><label for="user">კომენტარი</label></td>
    					<td>
    						<textarea id="comment_end" style="background:#F8F8F8;width: 157px;height: 35px;"></textarea>
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
	$data = '
	<div id="dialog-form">
 	    <fieldset style="width: 400px;">
	    	<legend>ძირითადი ინფორმაცია</legend>
			<div style=" margin-top: 2px; ">
				<div style="width: 170px; display: inline;">
			<table width="80%" class="dialog-form-table" cellpadding="10px" >
				<tr	style="float: left">
		
					<td style="width: 170px;"><label for="user">მომხმარებელი</label></td>
					<td>
						<select style="width: 163px;" id="user1" class="idls">' . GetWorkers(10) . '</select>
					</td>
										
				</tr>
				
			</table>		
				</div>
			</th>

		<div style="margin-top: 15px;margin-left:180px;"><input style="padding:3px;" type="button" id="check" value="შემოწმება" /></div>
	 </div>
        </fieldset>
								<br><br>
	<fieldset style="overflow-y: scroll;height: 400px;">	
		<legend>ბალანსი</legend>									
														
		<div id="button_area">
	            	<div class="left" style="width: 250px;">
	            		<label for="search_start" class="left" style="margin: 5px 0 0 9px;">დასაწყისი</label>
	            		<input type="text" name="search_start" id="search_start" class="inpt right"/>
	            	</div>
	            	<div class="right" style="width: 250px;">
	            		<label for="search_end" class="left" style="margin:5px 0 0 3px">დასასრული</label>
	            		<input type="text" name="search_end" id="search_end" class="inpt right" />
            		</div>
           </div>							
								
		   <table class="display" id="report">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 120px !important;">თარიღი</th>
                            <th style="width: 200px !important;">პიროვნება</th>
                            <th style="width: 120px !important;">სამ. გეგმ. დრო</th>
                            <th style="width: 120px !important;">სამ. ფაქტ. დრო</th>
                            <th style="width: 120px !important;">შეს  გეგმ. დრო</th>
						    <th style="width: 120px !important;">შეს  ფაქტ. დრო</th>
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
                        </tr>
                   </thead>
				   <tfoot>
                        <tr id="datatable_header" class="search_header">
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
		</fieldset>
    </div>



    ';
    return $data;
}

?>