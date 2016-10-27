<?php
include('../../includes/classes/core.php');
$start  	= $_REQUEST['start'];
$end    	= $_REQUEST['end'];
$action 	= $_REQUEST['act'];

$source_info    = $_REQUEST['source_info'];
$service_center = $_REQUEST['service_center'];
$in_district    = $_REQUEST['in_district'];
$in_type        = $_REQUEST['in_type'];
$branch         = $_REQUEST['branch'];
$info_category  = $_REQUEST['info_category'];
$info_category1 = $_REQUEST['info_category1'];
$info_category2 = $_REQUEST['info_category2'];

if(substr($source_info,0,5) == "'all'"){
    $source_info = "";
}else{
    $source_info = "AND personal_info.source_info_id IN($source_info)";
}

if(substr($service_center,0,5) == "'all'"){
    $service_center = ""; 
}else{
    $service_center = "AND personal_info.service_center_id IN($service_center)";
}

if(substr($in_district,0,5) == "'all'"){
    $in_district = "";
}else{
    $in_district = "AND personal_info.in_district_id IN($in_district)";
}

if(substr($in_type,0,5)  == "'all'"){
    $in_type = "";
}else{
    $in_type = "AND personal_info.in_type_id IN($in_type)";
}

if(substr($branch,0,5)  == "'all'"){
    $branch = "";
}else{
    $branch = "AND personal_info.branch_id IN($branch)";
}

if(substr($info_category1,0,5)  == "'all'"){
    $info_category1 = "";
}else{
    $info_category1 = "AND incomming_call.cat_1_1 IN($info_category1)";
}

if(substr($info_category2,0,5)  == "'all'"){
    $info_category2 = "";
}else{
    $info_category2 = "AND ic3.`name` IN($info_category2)";
}

if(substr($info_category,0,5)  == "'all'"){
    $info_category = "";
}else{
    $info_category = "AND incomming_call.cat_1 IN($info_category)";
}

switch ($action) {
	case "get_list":
	    $count        = $_REQUEST['count'];
	    $hidden       = $_REQUEST['hidden'];
        $rResult = mysql_query("SELECT  `incomming_call`.`id` AS `id`,
                                    	`incomming_call`.`id` AS `id1`,
                                    	`incomming_call`.`date` AS `date`,
                                    	`incomming_call`.`phone` AS `queue`,
                                    	`personal_info`.`cl_ab` AS `cl_ab`,
                                    	`personal_info`.`cl_ab_num` AS `cl_ab_num`,
                                    	`service_center`.`name` AS `sc`,
                                    	`ic1`.`name` AS `ic1`,
                                    	`inc_status`.`name` AS `inst`
                                FROM `incomming_call`
                                LEFT JOIN `personal_info` ON 
                                `incomming_call`.`id` = `personal_info`.`incomming_call_id`
                                LEFT JOIN `users` ON 
                                `users`.`id` = `incomming_call`.`user_id`
                                LEFT JOIN `user_info` ON 
                                `users`.`id` = `user_info`.`user_id`
                                LEFT JOIN `inc_status` ON 
                                `inc_status`.`id` = `incomming_call`.`inc_status_id`
                                LEFT JOIN `service_center` ON 
                                `personal_info`.`service_center_id` = `service_center`.`id`
                                LEFT JOIN `info_category` `ic1` ON 
                                `incomming_call`.`cat_1` = `ic1`.`id`
                                LEFT JOIN `info_category` `ic2` ON 
                                `incomming_call`.`cat_1_1` = `ic2`.`id`
                                LEFT JOIN `info_category` `ic3` ON 
                                `incomming_call`.`cat_1_1_1` = `ic3`.`id`
                                WHERE incomming_call.actived = 1
                                AND DATE(incomming_call.date) >= '$start'
                                AND DATE(incomming_call.date) <= '$end' $source_info $service_center $in_district $in_type $branch $info_category $info_category1 $info_category2");
	  
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
	
	default :
		echo "Action Is Null!";
		break;

}

echo json_encode($data);

?>