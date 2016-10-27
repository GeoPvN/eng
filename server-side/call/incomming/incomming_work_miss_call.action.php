<?php

/* ******************************
 *	Request aJax actions
* ******************************
*/

require_once('../../../includes/classes/core.php');
include('../../../includes/classes/log.class.php');
$log 		= new Log();

$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';
$status 	= '';

//incomming	
$id								= $_REQUEST['id'];
$id_p							= $_REQUEST['id_p'];
$id_h							= $_REQUEST['id_h'];
$miss_date                      = $_REQUEST['miss_date'];
$phone							= $_REQUEST['phone'];
$person_name					= $_REQUEST['person_name'];
$type_id						= $_REQUEST['type_id'];
$call_type_id					= $_REQUEST['call_type_id'];
$product_type_id				= $_REQUEST['product_type_id'];
$card_id						= $_REQUEST['card_id'];
$department_id					= $_REQUEST['department_id'];
$information_category_id		= $_REQUEST['information_category_id'];
$information_sub_category_id	= $_REQUEST['information_sub_category_id'];
$production_category_id			= $_REQUEST['production_category_id'];
$genre_id						= $_REQUEST['genre_id'];
$production_id					= $_REQUEST['production_id'];
$content						= $_REQUEST['content'];
$sum_pirce						= $_REQUEST['sum_pirce'];
$shipping_id					= $_REQUEST['shipping_id'];
$module_id						= $_REQUEST['module_id'];
$call_comment					= $_REQUEST['call_comment'];
$call_status_id					= $_REQUEST['call_status_id'];
$task_type_id					= $_REQUEST['task_type_id'];
$task_department_id				= $_REQUEST['task_department_id'];
$source_id						= $_REQUEST['source_id'];
$c_date							= $_REQUEST['c_date'];
$persons_id						= $_REQUEST['persons_id'];
$priority_id					= $_REQUEST['priority_id'];
$done_start_time				= $_REQUEST['done_start_time'];
$done_end_time					= $_REQUEST['done_end_time'];
$comment						= $_REQUEST['comment'];

// file
$rand_file				= $_REQUEST['rand_file'];
$file					= $_REQUEST['file_name'];
$hidden_inc				= $_REQUEST['hidden_inc'];
$edit_id				= $_REQUEST['edit_id'];
$delete_id				= $_REQUEST['delete_id'];

switch ($action) {
	case 'get_add_page':
		$number		= $_REQUEST['number'];
		$page		= GetPage($res='', $number);
		$data		= array('page'	=> $page);

		break;
	case 'disable':
		$incom_id				= $_REQUEST['id'];
		mysql_query("			UPDATE `incomming_call`
									    SET `actived`=0
										WHERE `id`=$incom_id ");
		break;
	case 'get_edit_page':
		$page		= GetPage(Getincomming($id));

		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count     = $_REQUEST['count'];
		$hidden    = $_REQUEST['hidden'];
		$start	   = $_REQUEST['start'];
		$end	   = $_REQUEST['end'];
		$tab_num   = $_REQUEST['tab_num'];		
		
		if($tab_num == 6){		
		    $res1 = mysql_query("SELECT  SUBSTR(cdr.dst,3) AS dst
            					FROM	cdr
            					WHERE 	DATE(cdr.calldate) >= '$start'
            					AND 	DATE(cdr.calldate) <= '$end'
            					AND 	cdr.src IN('100','101','150','200','201','250')
            					AND     SUBSTR(cdr.dst,3) >4
            					GROUP BY cdr.dst");
		    
		    while($rq = mysql_fetch_assoc($res1)){
		        $out .= $rq[dst].'|';
		    }
		    $out = substr($out, 0, -1);
		    $out = str_replace("'","",$out);
		    
	  	$rResult = mysql_query("SELECT  cd.calldate,
    									cd.calldate,
    									cd.src,
    									cd.dst,
    									TIME_FORMAT(SEC_TO_TIME(cd.duration), '%i:%s') AS `time`,
                                        'შემდგომში ნაპასუხები' AS `status`                               
                                FROM	queue_stats
                                left JOIN	qname ON	queue_stats.qname = qname.qname_id
                                left JOIN	qevent ON	queue_stats.qevent = qevent.event_id
                                left JOIN	cdr as cd ON	queue_stats.uniqueid = cd.uniqueid
	  	                        LEFT JOIN   incomming_call ON cd.calldate = incomming_call.miss_date
                                WHERE 	DATE(queue_stats.datetime) >= '$start'
                                AND 	DATE(queue_stats.datetime) <= '$end' 
                                AND 	qevent.`event` IN ('ABANDON', 'CONNECT')
	  	                        AND cd.src NOT IN ((SELECT phone FROM incomming_call WHERE DATE(incomming_call.miss_date) >= '$start' AND DATE(incomming_call.miss_date) <= '$end'))
	  	                        AND ISNULL(incomming_call.miss_date)
								AND     cd.src REGEXP '$out'
                                GROUP BY cd.src
                                HAVING SUM(IF(cd.disposition = 'ANSWERED', 0, 1) ) > 0
                                
	  	    
	  	                        UNION ALL
	  	    
	  	                        SELECT  cd.calldate,
                            	  	    cd.calldate,
                            	  	    cd.src,
                            	  	    cd.dst,
                            	  	    TIME_FORMAT(SEC_TO_TIME(cd.duration), '%i:%s') AS `time`,
                            	  	    'უპასუხო' AS `status` 
                    	  	    FROM	queue_stats
                    	  	    left JOIN	qname ON	queue_stats.qname = qname.qname_id
                    	  	    left JOIN	qevent ON	queue_stats.qevent = qevent.event_id
                    	  	    left JOIN	cdr as cd ON	queue_stats.uniqueid = cd.uniqueid
                    	  	    LEFT JOIN   incomming_call ON cd.calldate = incomming_call.miss_date
                    	  	    WHERE 	DATE(queue_stats.datetime) >= '$start'
                    	  	    AND 	DATE(queue_stats.datetime) <= '$end'
                    	  	    AND 	qevent.`event` IN ('ABANDON', 'CONNECT')
                    	  	    AND cd.src NOT IN ((SELECT phone FROM incomming_call WHERE DATE(incomming_call.date) >= '$start' AND DATE(incomming_call.date) <= '$end'))
                    	  	    AND ISNULL(incomming_call.miss_date)
                    	  	    AND     cd.src NOT REGEXP '$out'
                    	  	    GROUP BY cd.src
                    	  	    HAVING SUM(IF(cd.disposition = 'ANSWERED', 0, 1) ) > 0
                    	  	    ");
	  	
		}else{
		    $rResult = mysql_query("SELECT  cdr.calldate,
                            				cdr.calldate,
                            				cdr.src,
                            				cdr.dst,
                            				TIME_FORMAT(SEC_TO_TIME(cdr.duration), '%i:%s'),
                            				department.`name` AS `dep_name`,
                            				info_category.`name` AS `cat_name`,
                            				sub.`name` AS `cat_sub_name`,
                            				incomming_call.first_name,
                            				incomming_call.phone,
                            				incomming_call.call_comment,
                            				call_status.`name` AS `c_status`,
                            				persons.`name`
                                    FROM	cdr
                                    JOIN    incomming_call ON cdr.calldate = incomming_call.miss_date
                                    LEFT JOIN 	info_category  ON incomming_call.information_category_id=info_category.id
                                    LEFT JOIN 	info_category as `sub`  ON incomming_call.information_sub_category_id=sub.id
                                    LEFT JOIN	call_status ON incomming_call.call_status_id = call_status.id
                                    LEFT JOIN	department ON incomming_call.department_id = department.id
                                    JOIN		users ON incomming_call.user_id = users.id
                                    JOIN		persons ON users.person_id = persons.id 
                                    WHERE 	DATE(cdr.calldate) >= '$start'
                                    AND 	  DATE(cdr.calldate) <= '$end' AND miss_status = 1
		                            GROUP BY cdr.calldate");
		    
		    
		}
		
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
	case 'save_incomming':
	    $miss = mysql_fetch_row(mysql_query("SELECT miss_date FROM incomming_call WHERE miss_date = '$miss_date'"));
		if($miss[0] != $miss_date){
			
			Addincomming($miss_date,$phone,$person_name,$type_id,$call_type_id,$product_type_id,$card_id,$department_id,$information_category_id,$information_sub_category_id,$production_category_id,$genre_id,$production_id,$content,$sum_pirce,$shipping_id,$module_id,$call_comment,$call_status_id,$task_department_id,$source_id,$c_date,$persons_id,$priority_id,$done_start_time,$done_end_time,$comment,$task_type_id);
			if($task_type_id > 0){
			    AddTask($id_p,$task_type_id,$task_department_id,$c_date,$done_start_time,$done_end_time,$comment,$persons_id,$priority_id);
			}
		}else {
		    $inc_task_ch = mysql_fetch_assoc(mysql_query("SELECT id FROM task WHERE incomming_call_id = '$id_p'"));
		    
		    if($inc_task_ch['id'] == '' and $task_type_id > 0){
		        AddTask($id_p,$task_type_id,$task_department_id,$c_date,$done_start_time,$done_end_time,$comment,$persons_id,$priority_id);
		    }else{
		        $user		= $_SESSION['USERID'];
		        $r_user = realuser($persons_id);
		        mysql_query("UPDATE `task` SET  `user_id`='$user',
                                    `responsible_user_id`='$r_user',
                                    `start_date`='$done_start_time',
                                    `end_date`='$done_end_time',
                                    `department_id`='$task_department_id',
                                    `task_type_id`='$task_type_id',
                                    `priority_id`='$priority_id',
                                    `comment`='$comment'
                              WHERE `incomming_call_id`='$id_p'");
		    }
			Saveincomming($id_h,$phone,$person_name,$type_id,$call_type_id,$product_type_id,$card_id,$department_id,$information_category_id,$information_sub_category_id,$production_category_id,$genre_id,$production_id,$content,$sum_pirce,$shipping_id,$module_id,$call_comment,$call_status_id,$task_department_id,$source_id,$c_date,$persons_id,$priority_id,$done_start_time,$done_end_time,$comment,$task_type_id);
	
		}
		break;
	case 'get_calls':
	
		$data		= array('calls' => getCalls());
	
		break;		
	case 'category_change':
		
		$information_category_id_check = $_REQUEST['information_category_id_check'];
		$data 	= 	array('cat'=>Getinformation_sub_category('',$information_category_id_check));
		
		break;	
	case 'set_task_type':
	
		$cat_id	=	$_REQUEST['cat_id'];
		$data 	= 	array('cat'=>Getbank_object($cat_id));
	
		break;
		
	case 'delete_file':
		
			mysql_query("DELETE FROM file WHERE id = $delete_id");
		
			$increm = mysql_query("	SELECT  `name`,
					`rand_name`,
					`id`
					FROM 	`file`
					WHERE   `incomming_call_id` = $edit_id
					");
		
			$data1 = '';
		
			while($increm_row = mysql_fetch_assoc($increm))	{
			$data1 .='<tr style="border-bottom: 1px solid #85b1de;">
				          <td style="width:110px; display:block;word-wrap:break-word;">'.$increm_row[name].'</td>
				          <td ><button type="button" value="media/uploads/file/'.$increm_row[rand_name].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none;background-image:url(\'media/images/get.png\');" id="download" ></button><input type="text" style="display:none;" id="download_name" value="'.$increm_row[rand_name].'"> </td>
						          <td ><button type="button" value="'.$increm_row[id].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none; background-image:url(\'media/images/x.png\');" id="delete"></button></td>
 					  </tr>';
		}
		
		$data = array('page' => $data1);
		
				break;
		
				case 'up_now':
				$user		= $_SESSION['USERID'];
				if($rand_file != ''){
				mysql_query("INSERT INTO 	`file`
				( 	`user_id`,
				`incomming_call_id`,
				`name`,
				`rand_name`
				)
				VALUES
				(	'$user',
				'$edit_id',
				'$file',
				'$rand_file'
				);");
				}
		
				$increm = mysql_query("	SELECT  `name`,
				`rand_name`,
				`id`
				FROM 	`file`
				WHERE   `incomming_call_id` = $edit_id
				");
		
				$data1 = '';
		
				while($increm_row = mysql_fetch_assoc($increm))	{
				$data1 .='<tr style="border-bottom: 1px solid #85b1de;">
				<td style="width:110px; display:block;word-wrap:break-word;">'.$increm_row[name].'</td>
				<td ><button type="button" value="media/uploads/file/'.$increm_row[rand_name].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none;background-image:url(\'media/images/get.png\');" id="download" ></button><input type="text" style="display:none;" id="download_name" value="'.$increm_row[rand_name].'"> </td>
				          <td ><button type="button" value="'.$increm_row[id].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none; background-image:url(\'media/images/x.png\');" id="delete"></button></td>
						          </tr>';
		}
		
		$data = array('page' => $data1);
		
		break;
	
	case 'get_add_info':
	
		$pin	=	$_REQUEST['pin'];
		$data 	= 	array('info' => get_addition_all_info($pin));
	
		break;
		case 'get_add_info1':
		
		$personal_id	=	$_REQUEST['personal_id'];
		$data 	= 	array('info1' => get_addition_all_info1($personal_id));
	
		break;
	case 'production_category':
		
			$production_category_id	=	$_REQUEST['production_category_id'];
			$genre_id	=	$_REQUEST['genre_id'];
			
			$page		= Getproduction('', $production_category_id, $genre_id);
			$data		= array('page'	=> $page);
		
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
function realuser($persons_id)
{
    $res_user = mysql_fetch_assoc(mysql_query("SELECT users.id FROM persons JOIN users ON persons.id = users.person_id WHERE persons.id = '$persons_id'"));

    return $res_user[id];
}

function AddTask($id_p,$task_type_id,$task_department_id,$c_date,$done_start_time,$done_end_time,$comment,$persons_id,$priority_id)
{
    $user		= $_SESSION['USERID'];
    $r_user = realuser($persons_id);
    mysql_query("INSERT INTO `task`
    (`user_id`, `responsible_user_id`, `incomming_call_id`, `date`, `start_date`, `end_date`, `department_id`, `task_type_id`, `priority_id`, `comment`, `status`, `actived`)
    VALUES
    ('$user', '$r_user', '$id_p', '$c_date', '$done_start_time', '$done_end_time', '$task_department_id', '$task_type_id', '$priority_id', '$comment', '1', '1')");
}

function Addincomming($miss_date,$phone,$person_name,$type_id,$call_type_id,$product_type_id,$card_id,$department_id,$information_category_id,$information_sub_category_id,$production_category_id,$genre_id,$production_id,$content,$sum_pirce,$shipping_id,$module_id,$call_comment,$call_status_id,$task_department_id,$source_id,$c_date,$persons_id,$priority_id,$done_start_time,$done_end_time,$comment,$task_type_id)
{
	$user		= $_SESSION['USERID'];
	
	mysql_query("INSERT INTO `incomming_call` 
			(`user_id`,`phone`,`first_name`,`type_id`,`call_type_id`,`product_type_id`,`card_id`,`department_id`,`information_category_id`,`information_sub_category_id`,`production_category_id`,`genre_id`,`production_id`,`content`,`sum_pirce`,`shipping_id`,`module_id`,`call_comment`,`call_status_id`,`task_department_id`,`source_id`,`date`,`persons_id`,`priority_id`,`done_start_time`,`done_end_time`,`comment`,`task_type_id`,`miss_date`)
			 VALUES 
			('$user','$phone','$person_name','$type_id','$call_type_id','$product_type_id','$card_id','$department_id','$information_category_id','$information_sub_category_id','$production_category_id','$genre_id','$production_id','$content','$sum_pirce','$shipping_id','$module_id','$call_comment','$call_status_id','$task_department_id','$source_id','$c_date','$persons_id','$priority_id','$done_start_time','$done_end_time','$comment','$task_type_id','$miss_date')");
	
	
	$personal_phone			= $_REQUEST['personal_phone'];
	$personal_id			= $_REQUEST['personal_id'];
	$personal_first_name	= $_REQUEST['personal_first_name'];
	$personal_last_name		= $_REQUEST['personal_last_name'];
	$personal_d_date		= $_REQUEST['personal_d_date'];
	$personal_city			= $_REQUEST['personal_city'];
	$personal_mail			= $_REQUEST['personal_mail'];
	$personal_addres		= $_REQUEST['personal_addres'];
	$personal_status		= $_REQUEST['personal_status'];
	$personal_profession	= $_REQUEST['personal_profession'];

	
	mysql_query("INSERT INTO `personal_info` 
				(`user_id`, `incomming_call_id`, `personal_phone`, `personal_id`, `personal_first_name`, `personal_last_name`, `personal_mail`, `personal_d_date`, `personal_city`, `personal_addres`, `personal_status`, `personal_profession`) 
				VALUES 
				('$user', '$id_p', '$personal_phone', '$personal_id', '$personal_first_name', '$personal_last_name', '$personal_mail', '$personal_d_date', '$personal_city', '$personal_addres', '$personal_status', '$personal_profession')");
	
	

}

				
function Saveincomming($id_h,$phone,$person_name,$type_id,$call_type_id,$product_type_id,$card_id,$department_id,$information_category_id,$information_sub_category_id,$production_category_id,$genre_id,$production_id,$content,$sum_pirce,$shipping_id,$module_id,$call_comment,$call_status_id,$task_department_id,$source_id,$c_date,$persons_id,$priority_id,$done_start_time,$done_end_time,$comment,$task_type_id)
{

	$user		= $_SESSION['USERID'];
	mysql_query("	UPDATE  `incomming_call` 
					SET  
							`user_id` = '$user',
							`phone` = '$phone',
							`first_name` = '$person_name',
							`type_id` = '$type_id',
							`call_type_id` = '$call_type_id',
							`product_type_id` = '$product_type_id',
							`card_id` = '$card_id',
							`department_id` = '$department_id',
							`information_category_id` = '$information_category_id',
							`information_sub_category_id` = '$information_sub_category_id',
							`production_category_id` = '$production_category_id',
							`genre_id` = '$genre_id',
							`production_id` = '$production_id',
							`content` = '$content',
							`sum_pirce` = '$sum_pirce',
							`shipping_id` = '$shipping_id',
							`module_id` = '$module_id',
							`call_comment` = '$call_comment',
							`call_status_id` = '$call_status_id',
							`task_department_id` = '$task_department_id',
							`source_id` = '$source_id',
							`date` = '$c_date',
							`task_type_id`='$task_type_id',
							`persons_id` = '$persons_id',
							`priority_id` = '$priority_id',
							`done_start_time` = '$done_start_time',
							`done_end_time` = '$done_end_time',
							`comment` = '$comment'
				    WHERE	 `id`							='$id_h'
					");
	
	
	$personal_phone			= $_REQUEST['personal_phone'];
	$personal_id			= $_REQUEST['personal_id'];
	$personal_first_name	= $_REQUEST['personal_first_name'];
	$personal_last_name		= $_REQUEST['personal_last_name'];
	$personal_d_date		= $_REQUEST['personal_d_date'];
	$personal_city			= $_REQUEST['personal_city'];
	$personal_mail			= $_REQUEST['personal_mail'];
	$personal_addres		= $_REQUEST['personal_addres'];
	$personal_status		= $_REQUEST['personal_status'];
	$personal_profession	= $_REQUEST['personal_profession'];
	
	mysql_query("	UPDATE 	`personal_info` 
					SET
							`user_id`='$user',
							`personal_phone`='$personal_phone', 
							`personal_id`='$personal_id', 
							`personal_first_name`='$personal_first_name', 
							`personal_last_name`='$personal_last_name', 
							`personal_mail`='$personal_mail', 
							`personal_d_date`='$personal_d_date', 
							`personal_city`='$personal_city', 
							`personal_addres`='$personal_addres', 
							`personal_status`='$personal_status', 
							`personal_profession`='$personal_profession' 
					WHERE 	`incomming_call_id`= '$id_h';
				");

}




function Getobject($object_id)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `name`
					FROM 	`object`
					WHERE 	actived=1");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $object_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
function Getcategory($category_id)

{ 			

	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `category`
						WHERE actived=1 && parent_id=0 ");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getcategory1($category_id)
{

	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `category`
						WHERE actived=1 && parent_id=$category_id");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
	
}

function Getcategory1_edit($category_id)
{

	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `category`
						WHERE actived=1 && id=$category_id");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;

}

function Getcall_type($call_type_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
					FROM `call_type`
					WHERE actived=1");
	
	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $call_type_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}



function Getpriority($priority_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `priority`
						WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $priority_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Gettask_type($task_type_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
					    FROM `task_type`
					    WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $task_type_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Getpersons($persons_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
							FROM `persons`
							WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $persons_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function getCalls(){
	$db1 = new sql_db ( "localhost", "root", "Gl-1114", "asteriskcdrdb" );

	$req = mysql_query("

						SELECT  	DISTINCT
									IF(SUBSTR(cdr.src, 1, 3) = 995, SUBSTR(cdr.src, 4, 9), cdr.src) AS `src`
						FROM    	cdr
						GROUP BY 	cdr.src
						ORDER BY 	cdr.calldate DESC
						LIMIT 		14


						");

	$data = '<tr class="trClass">
					<th class="thClass">#</th>
					<th class="thClass">ნომერი</th>
					<th class="thClass">ქმედება</th>
				</tr>
			';
	$i	= 1;
	while( $res3 = mysql_fetch_assoc($req)){

		$data .= '
	    		<tr class="trClass">
					<td class="tdClass" style="padding:0 2px;">' . $i . ')</td>
					<td class="tdClass" style="width: 30px !important;">' . $res3['src'] . '</td>
					<td class="tdClass" style="font-size: 13px !important;"><button class="insert" number="' . $res3['src'] . '">დამატება</button></td>
				</tr>';
		$i++;
	}

	return $data;


}


function Getinformation_category($information_category_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	info_category 
							WHERE 	parent_id = '0'	AND `actived` = 1");
	
	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $information_category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}
	
	return $data;
}

function Getinformation_sub_category($information_sub_category_id,$information_category_id_check){
	$req = mysql_query("	SELECT 	n1.`id`,
									n1.`name`
							FROM 	info_category
							JOIN 	info_category as n1 ON info_category.id = n1.parent_id
							WHERE 	info_category.id = $information_category_id_check AND n1.`actived` = 1");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $information_sub_category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getcontent($content_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	content
							WHERE `actived` = 1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $content_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getproduct($product_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	product
							WHERE `actived` = 1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $product_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getforward($forward_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	forward
							WHERE `actived` = 1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $forward_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getresults($results_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	results
							WHERE `actived` = 1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $results_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getdepartment($department_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	department
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $department_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getsource($source_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	source
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $source_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getshipping($shipping_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	shipping
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $shipping_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getmodule($module_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	module
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $module_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getcall_status($call_status_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	call_status
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $call_status_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getfamily($family_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	family
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $family_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getcity($city_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	city
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $city_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getproduction_category($production_category_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	production_category
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $production_category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getgenre($genre_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	genre
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $genre_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getproduction($production_id, $production_category_id, $genre_id){
	if($production_id == ''){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	production
							WHERE 	production_category_id = '$production_category_id' and genre_id = '$genre_id' and actived=1
							");
	}else{
		$req = mysql_query("	SELECT 	`id`,
										`name`
								FROM 	production
								WHERE 	id = '$production_id' and actived=1
							");
	}
	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $production_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getincomming($id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT    	incomming_call.id AS id,
														incomming_call.source_id,
														incomming_call.`date` AS call_date,
														DATE_FORMAT(incomming_call.`date`,'%y-%m-%d') AS `date`,
														incomming_call.`phone`,
														incomming_call.first_name,
														incomming_call.type_id,
														incomming_call.call_type_id,
														incomming_call.product_type_id,
														incomming_call.card_id,
														incomming_call.department_id,
														incomming_call.information_category_id,
														incomming_call.information_sub_category_id,
														incomming_call.production_category_id,
														incomming_call.genre_id,
														incomming_call.production_id,
														incomming_call.content,
														incomming_call.shipping_id,
														incomming_call.sum_pirce,
														incomming_call.`comment`,
														incomming_call.module_id,
														incomming_call.call_comment,
														incomming_call.call_status_id,
														incomming_call.`task_department_id`,
														incomming_call.persons_id,
														incomming_call.priority_id,
														incomming_call.task_type_id,
														incomming_call.`done_start_time` AS done_start_time,
														incomming_call.`done_end_time` AS done_end_time,
														personal_info.`incomming_call_id`,
														personal_info.`personal_phone`,
														personal_info.`personal_id`,
														personal_info.`personal_first_name`,
														personal_info.`personal_last_name`,
														personal_info.`personal_mail` ,
														personal_info.`personal_d_date`,
														personal_info.`personal_city`,
														personal_info.`personal_addres`,
														personal_info.`personal_status`,
														personal_info.`personal_profession`
												FROM 	incomming_call
												LEFT JOIN	personal_info ON incomming_call.id = personal_info.incomming_call_id
												where   incomming_call.miss_date =  '$id'
														" ));
	return $res;
}

function GetPage($res='', $number)
{
	$c_date		= date('Y-m-d H:i:s');
	
	$num = 0;
	if($res[phone]==""){
		$num=$number;
	}else{ 
		$num=$res[phone]; 
	}
	
	$data  .= '
	<!-- jQuery Dialog -->
    <div id="add-edit-goods-form" title="საქონელი">
    	<!-- aJax -->
	</div>
	<div id="dialog-form">
			<div style="float: left; width: 800px;">	
				<fieldset >
				<fieldset style="width:300px; float:left;">
			    	<legend>ძირითადი ინფორმაცია</legend>
		
			    	<table width="500px" class="dialog-form-table">
						<tr>
							<td style="width: 180px;"><label for="">მომართვა №</label></td>
							<td style="width: 180px;"><label for="">თარიღი</label></td>
						</tr>							
						
						<tr>
							<td style="width: 180px;">
								<input type="text" id="id" class="idle" onblur="this.className=\'idle\'"  value="' . (($res['id']!='')?$res['id']:increment('incomming_call')). '" disabled="disabled" />
								<input style="display:none;" type="text" id="h_id" class="idle" onblur="this.className=\'idle\'"  value="' . $res['id']. '" disabled="disabled" />
								<input style="display:none;" type="text" id="miss_date" class="idle" onblur="this.className=\'idle\'"  value="' . $_REQUEST['id']. '" disabled="disabled" />
								<input style="display:none;" type="text" id="miss_status"  value="1" />
							</td>
							<td style="width: 180px;">
								<input type="text" id="c_date" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField date\'" value="' . (($res['call_date']!='')?$res['call_date']:$c_date) . '" disabled="disabled" />
							</td>				
						</tr>
						<tr>
							<td style="width: 180px;"><label for="phone">ტელეფონი</label></td>							
							<td><label for="person_name">აბონენტის სახელი</label></td>
						</tr>
						<tr>
							<td style="width: 180px;">
								<input type="text" id="phone" class="idle" onblur="this.className=\'idle\'"  value="' . $_REQUEST['num'] . '" disabled="disabled" />
							</td>
							<td style="width: 69px;">
								<input type="text" id="person_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' .  $res['first_name']. '" />
							</td>	
						</tr>
						<tr>
							<td>
								<label for="source_id">არხი</label>
							</td>
							<td>
							</td>
						</tr>
						<tr>
							<td>
								<select style="width: 165px;" id="source_id" class="idls object">'. Getsource($res['source_id']).'</select>
							</td>
							<td style="width: 69px;">
								<button id="button_calls" class="calls">ნომრები</button>
							</td>
						</tr>				
					</table>
				</fieldset>
				<fieldset style="width:220px; float:left; margin-left:10px; ">
			    	<legend>მომართვის ავტორი</legend>
					<table id="" class="dialog-form-table" width="220px">						
						<tr>
							<td style="width: 220px;"><input style="float:left;" type="radio" name="x" value="1" '.(($res['type_id']=='1')?"checked":"").'><span style="margin-top:5px; display:block;">ფიზიკური</span></td>
					  		<td style="width: 220px;"><input style="float:left;" type="radio" name="x" value="2" '.(($res['type_id']=='2')?"checked":"").'><span style="margin-top:5px; display:block;"">იურიდიული</span></td>
						</tr>
					</table>
				</fieldset>
				<fieldset style="width:220px; float:left; margin-left:10px; ">
			    	<legend>ზარის ტიპი</legend>
					<table id="" class="dialog-form-table" width="220px">						
						<tr>
							<td style="width: 220px;"><input style="float:left;" type="radio" name="xx" value="1" '.(($res['call_type_id']=='1')?"checked":"").'><span style="margin-top:5px; display:block;">ინფორმაცია</span></td>
					  		<td style="width: 220px;"><input style="float:left;" type="radio" name="xx" value="2" '.(($res['call_type_id']=='2')?"checked":"").'><span style="margin-top:5px; display:block;"">პრეტენზია</span></td>
					  	</tr>
					  	<tr>
					  		<td style="width: 220px;"><input style="float:left;" type="radio" name="xx" value="3" '.(($res['call_type_id']=='3')?"checked":"").'><span style="margin-top:5px; display:block;"">სხვა</span></td>
						</tr>
					</table>
				</fieldset>
				<fieldset style="width:220px; float:left; margin-left:10px; ">
			    	<legend>განყოფილება</legend>
					<table id="" class="dialog-form-table" width="220px">						
						<tr>
							<td><select style="width: 220px;" id="department_id" class="idls object">'. Getdepartment($res['department_id']).'</select></td>
						</tr>
					</table>
				</fieldset>
				<fieldset style="width:756px; float:left;">
			    	<legend>ინფორმაცია</legend>
					<table id="" class="dialog-form-table" width="500px">
					  	<tr>
					  		<td><label for="information_category_id">კატეგორია</label></td>
					  	</tr>						
						<tr>
							<td><select style="width: 756px;" id="information_category_id" class="idls object">'. Getinformation_category($res['information_category_id']).'</select></td>
						</tr>
						<tr>
					  		<td><label for="information_category_id">ქვე-კატეგორია</label></td>
					  	</tr>
						<tr>
							<td><select style="width: 756px;" id="information_sub_category_id" class="idls object">'. Getinformation_sub_category($res['information_sub_category_id'],$res['information_category_id']).'</select></td>
						</tr>
					</table>
				</fieldset>
				
				
				<fieldset style="width:557px; float:left;">
			    	<legend>პროდუქცია</legend>
					<table id="" class="dialog-form-table" width="755px">						
						<tr>
							<td style="width: 50px;"><input style="float:left;" type="radio" name="xxx" value="1" '.(($res['product_type_id']=='1')?"checked":"").'><span style="margin-top:5px; display:block;">ახალის შეძენა</span></td>
					  		<td style="width: 50px;"><input style="float:left;" type="radio" name="xxx" value="2" '.(($res['product_type_id']=='2')?"checked":"").'><span style="margin-top:5px; display:block;"">შეძენილი</span></td>
					  		<td style="width: 50px;"><input style="float:left;" type="radio" name="xxx" value="3" '.(($res['product_type_id']=='3')?"checked":"").'><span style="margin-top:5px; display:block;"">საინტერესო</span></td>
						</tr>
					</table>
				<div id="show_all" class="dialog_hidden">
					<table id="" class="dialog-form-table" width="750px">
					  	<tr>
					  		<td><label for="information_category_id">პროდუქტი კატეგორია</label></td>
					  		<td><label for="information_category_id">ჟანრი</label></td>
					  		<td><label for="information_category_id">დასახელება</label></td>
					  	</tr>						
						<tr>
							<td><select style="width: ;" id="production_category_id" class="idls object">'. Getproduction_category($res['production_category_id'],$res['information_category_id']).'</select></td>
							<td><select style="width: ;" id="genre_id" class="idls object">'. Getgenre($res['genre_id']).'</select></td>
							<td><select style="width: ;" id="production_id" class="idls object">'. Getproduction($res['production_id']).'</select></td>
						</tr>	
											
					</table>
					<table id="" class="dialog-form-table" width="700px">
					  	<tr>
					  		<td><label for="information_category_id">ან შეიყვანეთ კოდი</label></td>
					  		<td></td>
					  		<td><label for="information_category_id">წიგნების ID</label></td>
					  	</tr>						
						<tr>
							<td><select style="width: ;" id="" class="idls object"></select></td>
							<td><button id="add_product">დამატება</button></td>
					  		<td><textarea  style="width: 370px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
						</tr>
					</table>													
					</table>
					  	<table class="dialog-form-table" width="400px">				
					  	<tr>
							<td>ჯამური ღირებულება</td>
							<td><input type="text" style="width: 60px;" id="sum_pirce" class="idle" onblur="this.className=\'idle\'"  value="' . $res['sum_pirce'] . '"  /></td>
							<td>ლარი</td>
							<td><input style="float:left;" type="checkbox" name="xxxx" value="1" '.(($res['card_id']=='1')?"checked":"").'><span style="margin-top:8px; display:block;""> + ბარათი</span></td>
						</tr>					
					</table>
					<table class="dialog-form-table" width="750px">
						<tr>
					  		<td><label for="">მიწოდება</label></td>
					  		<td><label style="margin-left:5px;" for="">მოდული</label></td>
					  	</tr>					
					  	<tr>
							<td><select style="width: 330px;" id="shipping_id" class="idls object">'. Getshipping($res['shipping_id']).'</select></td>
							<td><select style="width: 420px; margin-left:5px;" id="module_id" class="idls object">'. Getmodule($res['module_id']).'</select></td>
						</tr>					
					</table>
				</div>					
				</fieldset>
				<fieldset style="width: 400px; float:left;">
					<legend>ზარის დაზუსტება</legend>
					<table id="" class="dialog-form-table" width="150px">
						<tr>
							<td><textarea  style="width: 400px; resize: none;" id="call_comment" class="idle" name="call_comment" cols="300" >' . $res['call_comment'] . '</textarea></td>
						</tr>
					</table>
					</fieldset>
					<fieldset style="width: 317px; margin-left:10px; height:55px;  float:left;">
					<legend>ზარის სტატუსი</legend>
					<table id="" class="dialog-form-table" width="150px">
						<tr>
							<td><select style="width: 310px; margin-left:5px;" id="call_status_id" class="idls object">'. Getcall_status($res['call_status_id']).'</select></td>
						</tr>
					</table>
					</fieldset>
				';
												
		$data  .= '
		   
				<fieldset style="margin-top: 5px;">
			    	<legend>დავალების ფორმირება</legend>
		
			    	<table class="dialog-form-table" style="width: 750px;">
						<tr>
							<td style="width: 180px;"><label for="task_type_id">დავალების ტიპი</label></td>
							<td style="width: 180px;"><label for="task_department_id">განყოფილება</label></td>
							<td style="width: 180px;"><label for="persons_id">პასუხისმგებელი პირი</label></td>
							<td style="width: 180px;"><label for="priority_id">პრიორიტეტი</label></td>
						</tr>
			    		<tr>
							<td><select style="width: 180px;" id="task_type_id" class="idls object">'.Gettask_type($res['task_type_id']).'</select></td>
							<td><select style="width: 180px;" id="task_department_id" class="idls object">'.Getdepartment($res['task_department_id']).'</select></td>
							<td><select style="width: 180px;" id="persons_id" class="idls object">'. Getpersons($res['persons_id']).'</select></td>
							<td><select style="width: 180px;" id="priority_id" class="idls object">'.Getpriority($res['priority_id']).'</select></td>
						</tr>
						</table>
						<table class="dialog-form-table" style="width: 750px;">
						<tr>
							<td style="width: 150px;"><label>შესრულების პერიოდი</label></td>
							<td style="width: 150px;"><label></label></td>
							<td style="width: 150px;"><label>კომენტარი</label></td>
							
						</tr>
						<tr>
							<td><input id="done_start_time" style="width: 150px; float:left;" class="idle" type="text" value="'.$res['done_start_time'].'"><span style="margin-left:5px; ">დან</span></td>
					  		<td><input id="done_end_time" style="width: 150px; float:left;" class="idle" type="text" value="'.$res['done_end_time'].'"><span style="margin-left:5px; ">მდე</span></td>
							<td>
								<textarea  style="width: 300px; resize: none;" id="comment" class="idle" name="content" cols="300" >' . $res['comment'] . '</textarea>
							</td>
						</tr>
					</table>
		        </fieldset>
			</div>
			<div>
				  </fieldset>
			</div>
			<div style="float: right;  width: 355px;">
				 
				<fieldset>
					<legend>მომართვის ავტორი</legend>
					<table style="height: 243px;">						
						<tr>
							<td style="width: 180px; color: #3C7FB1;">ტელეფონი</td>
							<td style="width: 180px; color: #3C7FB1;">პირადი ნომერი</td>
						</tr>
						<tr>
							<td>
								<input type="text" id="personal_phone" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_phone'] . '" />
							</td>
							<td style="width: 180px;">
								<input type="text" id="personal_id" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_id'] . '" />
							</td>					
						</tr>
						<tr>
							<td style="width: 180px; color: #3C7FB1;">სახელი</td>
							<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
						</tr>
						<tr >
							<td style="width: 180px;">
								<input type="text" id="personal_first_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_first_name'] . '" />
							</td>
							<td style="width: 180px;">
								<input type="text" id="personal_mail" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_mail'] . '" />
							</td>			
						</tr>
						<tr>
							<td td style="width: 180px; color: #3C7FB1;">გვარი</td>
							<td td style="width: 180px; color: #3C7FB1;">დაბადების თარიღი</td>
						</tr>
						<tr>
							<td style="width: 180px;">
								<input type="text" id="personal_last_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_last_name'] . '" />		
							</td>
							<td td style="width: 180px;">
								<input type="text" id="personal_d_date" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_d_date'] . '" />		
							</td>
						</tr>
						<tr>
							<td td style="width: 180px; color: #3C7FB1;">ქალაქი</td>
							<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
						</tr>
						<tr>
							<td><select style="width: 165px;" id="personal_city" class="idls object">'.Getcity($res['personal_city']).'</select></td>
							<td td style="width: 180px;">
								<input type="text" id="personal_addres" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_addres'] . '" />		
							</td>
						</tr>
						<tr>
							<td td style="width: 180px; color: #3C7FB1;">ოჯახური სტატუსი</td>
							<td td style="width: 180px; color: #3C7FB1;">პროფესია</td>
						</tr>
						<tr>
							<td><select style="width: 165px;" id="personal_status" class="idls object">'.Getfamily($res['personal_status']).'</select></td>
							<td td style="width: 180px;">
								<input type="text" id="personal_profession" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_profession'] . '" />		
							</td>
						</tr>
					</table>
				</fieldset>	
				<!--fieldset>
					<legend>ყველა გაყიდვა (ბოლო 10)</legend>					
		                <table style="border:2px solid #85B1DE; width:100%;">
		                   		<tr style="background:#F2F2F2; ">	
									<th style="width:7%; padding:5px; border:1px solid #85B1DE;">#</th>
									<th style="border:1px solid #85B1DE; padding:5px;">თარიღი</th>
									<th style="border:1px solid #85B1DE; padding:5px;">მომხმარებელი</th>
									<th style="width:12%; padding:5px; border:1px solid #85B1DE;">ფასი</th>
									<th style="border:1px solid #85B1DE; padding:5px;">წიგნები</th>
								</tr>		
								<tr style="background: #FEFEFE">
										<td style="border:1px solid #85B1DE; padding:2px;">1</td>
										<td style="border:1px solid #85B1DE; padding:2px;">test</td>
										<td style="border:1px solid #85B1DE; padding:2px;">test</td>
										<td style="border:1px solid #85B1DE; padding:2px;">test</td>
										<td style="border:1px solid #85B1DE; padding:2px;">test</td>
								</tr>												
		                </table>
							<button id="read_more" style="float:right;">სრულად ნახვა</button>
				</fieldset-->					
				<fieldset style="margin-top: 10px; width: 150px; margin-left:10px; float: right;">
            		<legend>ფაილი</legend>
					<table style="float: right; border: 1px solid #85b1de; width: 150px; text-align: center;">
     <tr>
      <td>
       <div class="file-uploader">
        <input id="choose_file" type="file" name="choose_file" class="input" style="display: none;">
        <button id="choose_button" class="center">აირჩიეთ ფაილი</button>
        <input id="hidden_inc" type="text" value="'. increment('action') .'" style="display: none;">
       </div>
      </td>
     </tr>
    </table>
        <table style="float: right; border: 1px solid #85b1de; width: 150px; text-align: center;">
             <tr style="border-bottom: 1px solid #85b1de;">
              <td colspan="3">მიმაგრებული ფაილი</td>
             </tr>
    </table>
    <table id="file_div" style="float: right; border: 1px solid #85b1de; width: 150px; text-align: center;">';
     
     while($increm_row = mysql_fetch_assoc($increm)) { 
      $data .=' 
                <tr style="border-bottom: 1px solid #85b1de;">
                  <td style="width:110px; display:block;word-wrap:break-word;">'.$increm_row[name].'</td>              
                  <td ><button type="button" value="media/uploads/file/'.$increm_row[rand_name].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none;background-image:url(\'media/images/get.png\');" id="download" ></button><input type="text" style="display:none;" id="download_name" value="'.$increm_row[rand_name].'"> </td>
                  <td ><button type="button" value="'.$increm_row[id].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none; background-image:url(\'media/images/x.png\');" id="delete"></button></td>
                </tr>';
     }
            
  $data .= '
    </table>
				</fieldset>	
  					
						';
				if(!empty($res[phone])){
					$data .= GetRecordingsSection($res);
				}
	  $data .= '
	  		<button type="button" class="save-dialog" id="save-dialog" style="margin-top:10px;">შენახვა</button>
	  		</div>
			</div>
    </div>';

	return $data;
}

function GetRecordingsSection($res)
{
	mysql_close();
	$conn = mysql_connect('localhost', 'root', 'Gl-1114');
	if (!$conn) {
		$error = 'dgfhg';
	}
	mysql_select_db('asteriskcdrdb');
	$phone = substr($res[phone],6);
	$req = mysql_query("SELECT  `calldate` AS 'time',
                        SUBSTR(`userfield`,7) AS `userfield`
                        FROM     `cdr`
                        WHERE (`dst` LIKE '%$phone%' && `userfield` != '' && DATE(`calldate`) = '$res[date]');");

	$data .= '
        <fieldset style="margin-top: 10px; width: 150px; float: right;">
            <legend>ჩანაწერები</legend>

            <table style="width: 100%; border: solid 1px #85b1de; margin:auto;">
                <tr style="border-bottom: solid 1px #85b1de; height: 20px;">
                    <th style="padding-left: 10px;">დრო</th>
                    <th  style="border: solid 1px #85b1de; padding-left: 10px;">ჩანაწერი</th>
                </tr>';
	if (mysql_num_rows($req) == 0){
		$data .= '<td colspan="2" style="height: 20px; text-align: center;">ჩანაწერები ვერ მოიძებნა</td>';
	}

	while( $res2 = mysql_fetch_assoc($req)){
		$src = $res2['userfield'];
		$data .= '
                <tr style="border-bottom: solid 1px #85b1de; height: 20px;">
                    <td style="vertical-align: middle; text-align: center;">' . $res2['time'] . '</td>
                    <td style="vertical-align: middle; text-align: center;"><button class="download_out" str="' . $src . '">მოსმენა</button></td>
                </tr>';
	}

	$data .= '
            </table>
        </fieldset>';

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