<?php
include('../../includes/classes/core.php');
$start  	= $_REQUEST['start'];
$end    	= $_REQUEST['end'];
$count 		= $_REQUEST["count"];
$action 	= $_REQUEST['act'];
$departament= $_REQUEST['departament'];
$type       = $_REQUEST['type'];
$category   = $_REQUEST['category'];
$s_category = $_REQUEST['sub_category'];
$done 		= $_REQUEST['done']%3;
$name 		= $_REQUEST['name'];
$user 		= $_REQUEST['user'];
$title 		= $_REQUEST['title'];
$text[0] 	= "დავალებები პასუხისმგებელი პირების მიხედვით";
$text[1] 	= "'$type'- სტატუსების მიხედვით";
$text[2] 	= "'$category'-განყოფილებების მიხედვით";

if ($category=="გადაცემულია გასარკვევად")  $c=1;
elseif ($category=="გარკვევის პროცესშია") $c=2;
elseif ($category=="მოგვარებულია") $c=3;
elseif ($category=="გაუქმებულია") $c=4;

//------------------------------------------------query-------------------------------------------
switch ($done){
	case  1:
		
		$result = mysql_query("	SELECT  `task_status`.`name` AS category,
                                        COUNT(*),
                                        CONCAT(ROUND(COUNT(*)/(SELECT COUNT(*)
                                        FROM 	task
                                        JOIN user_info ON task.task_recipient_id = user_info.user_id
                                        JOIN `task_status` ON task.`task_status_id`=`task_status`.id		
                                        WHERE user_info.`name`='$type' AND DATE(task.task_date) >= '$start' AND DATE(task.task_date) <= '$end'
                                        )*100,2),'%') AS percent
                                FROM 	task
                                JOIN user_info ON task.task_recipient_id = user_info.user_id
                                JOIN `task_status` ON task.`task_status_id`=`task_status`.id												
                                WHERE user_info.`name`='$type' AND DATE(task.task_date) >= '$start' AND DATE(task.task_date) <= '$end'
                                GROUP BY task.`task_status_id`");

		$text[0]=$text[1];
	break;
	case 2:
		if ($user==0){
			$result = mysql_query("SELECT department.`name` AS category,
											COUNT(*),
											CONCAT(ROUND(COUNT(*)/(SELECT COUNT(*)
												FROM 	task
												JOIN users	 ON task.responsible_user_id = users.id
					                            JOIN persons ON users.person_id = persons.id
												LEFT JOIN department ON task.department_id = department.id
												WHERE (task.template_id IS NULL or task.template_id = 0  or task.outgoing_id > 0) AND (task.`status`=$c)AND (persons.`name`='$type') and DATE(task.date) >= '$start' and  DATE(task.date) <= '$end'
												AND task.incomming_call_id is NOT NULL or task.outgoing_id is NOT NULL or task.template_id = 0
												)*100,2),'%') AS percent
									FROM 	task
									JOIN users	 ON task.responsible_user_id = users.id
					                JOIN persons ON users.person_id = persons.id
			                        LEFT JOIN department ON task.department_id = department.id
									WHERE (task.template_id IS NULL or task.template_id = 0  or task.outgoing_id > 0) AND (task.`status`=$c)AND (persons.`name`='$type')  and DATE(task.date) >= '$start' and  DATE(task.date) <= '$end'
									GROUP BY category");
		}else 
		{
			$result = mysql_query("SELECT department.`name` AS category,
					COUNT(*),
					CONCAT(ROUND(COUNT(*)/(SELECT COUNT(*)
					FROM 	task
					JOIN users	 ON task.responsible_user_id = users.id
					JOIN persons ON users.person_id = persons.id
					LEFT JOIN department ON task.department_id = department.id
					WHERE (task.template_id IS NULL or task.template_id = 0  or task.outgoing_id > 0) AND (task.`status`=$c)AND (persons.`name`='$type') and DATE(task.date) >= '$start' and  DATE(task.date) <= '$end'
			)*100,2),'%') AS percent
					FROM 	task
					JOIN users	 ON task.responsible_user_id = users.id
					JOIN persons ON users.person_id = persons.id
					LEFT JOIN department ON task.department_id = department.id
					WHERE (task.template_id IS NULL or task.template_id = 0  or task.outgoing_id > 0) AND (task.`status`=$c)AND (persons.`name`='$type')  and DATE(task.date) >= '$start' and  DATE(task.date) <= '$end'
					GROUP BY category");
		}
		$text[0]=$text[2];
	break;
	default:
		if ($user==0){
		$result = mysql_query(" SELECT  user_info.`name` as type,
                                        COUNT(*),
                                        CONCAT(ROUND(COUNT(*)/(SELECT COUNT(*) FROM task
                                        JOIN user_info ON task.task_recipient_id = user_info.user_id
                                        WHERE  DATE(task.task_date) >= '$start' AND DATE(task.task_date) <= '$end')*100,2),'%')
                                FROM 	task
                                JOIN user_info ON task.task_recipient_id = user_info.user_id
                                WHERE DATE(task.task_date) >= '$start' AND DATE(task.task_date) <= '$end'
                                GROUP BY 	task.task_recipient_id;");

		break;
		}else
		{
			$result = mysql_query("SELECT  user_info.`name` as type,
                                        COUNT(*),
                                        CONCAT(ROUND(COUNT(*)/(SELECT COUNT(*) FROM task
                                        JOIN user_info ON task.task_recipient_id = user_info.user_id
                                        WHERE  DATE(task.task_date) >= '$start' AND DATE(task.task_date) <= '$end' AND task.responsible_user_id=$user)*100,2),'%')
                                FROM 	task
                                JOIN user_info ON task.task_recipient_id = user_info.user_id
                                WHERE DATE(task.task_date) >= '$start' AND DATE(task.task_date) <= '$end' AND task.responsible_user_id=$user
                                GROUP BY 	task.task_recipient_id;");
		}
}
///----------------------------------------------act------------------------------------------
switch ($action) {
	case "get_list":
		$data = array("aaData"	=> array());
		while ( $aRow = mysql_fetch_array( $result ) )
		{	$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				$row[0] = '0';

				$row[$i+1] = $aRow[$i];
			}
			$data['aaData'][] =$row;
		}
		echo json_encode($data); return 0;
		break;
	case 'get_category' :
		$rows = array();
		while($r = mysql_fetch_array($result)) {
			$row[0] = $r[0];
			$row[1] = (float) $r[1];
			$rows['data'][]=$row;
		}
		$rows['text']=$text[0];
		echo json_encode($rows);
		break;
	case 'get_in_page':
		mysql_query("SET @i = 0;");
	  	$rResult = mysql_query("SELECT 	`task`.`id`,
                                        `task`.`id`,
                                        `task_date`,
                                        `task_start_date`,
                                        `task_end_date`,
                                        `former_ps`.`name`,
                                        `personal_info`.`cl_name`,
                                        `recipient_ps`.`name`,
	  	                                IF(task.task_status_id=0,'',`task_status`.`name`) AS `task_status`
                                FROM 	`task`
                                LEFT JOIN	`department` ON task.task_departament_id = department.id
                                LEFT JOIN	`users` AS recipient ON task.task_recipient_id = recipient.id
                                LEFT JOIN	`user_info` AS recipient_ps ON recipient.id = recipient_ps.user_id
	  	                        LEFT JOIN	`personal_info` ON personal_info.incomming_call_id = task.incomming_call_id
                                JOIN	`users` AS former ON task.user_id = former.id
                                JOIN	`user_info` AS former_ps ON former.id = former_ps.user_id
	  	                        JOIN    `task_status` ON task.task_status_id = task_status.id
                                WHERE 	`task`.`actived` = 1 AND `recipient_ps`.`name` = '$type' AND `task_status`.`name` = '$_REQUEST[rid]' AND DATE(task.task_date) >= '$start' AND DATE(task.task_date) <= '$end'");
	  
$data = array(
				"aaData"	=> array()
		);

		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array();
			$row1 = array();
			
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				$row[] = $aRow[$i];
				$a=$aRow;
				
				{
				
				}
			}
			$data['aaData'][] = $row;
		}
		echo json_encode($data); return 0;
		break;
	default :
		echo "Action Is Null!";
		break;

}



?>