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
$done 		= $_REQUEST['done']%4;
$name 		= $_REQUEST['name'];
$title 		= $_REQUEST['title'];
$branches   = $_REQUEST['branches'];
$text[0] 	= "Incoming calls by branches";
$text[1] 	= "'$departament'- შემოსული ზარები კატეგორიების მიხედვით";
$text[2] 	= "'$departament'- შემოსული ზარები ქვე-კატეგორიების  მიხედვით";
$text[3] 	= "'$departament'- შემოსული ზარები ქვე–კატეგორია1-ის მიხედვით";

//------------------------------------------------query-------------------------------------------
switch ($done){
	case  1:
		$result = mysql_query("SELECT  	  category.`name` as type,
                        				  COUNT(incomming_call.id) AS `count`,
                        				  ROUND((COUNT(incomming_call.id) / (SELECT    COUNT(incomming_call.id)
																			 FROM     `incomming_call`
																			 JOIN      personal_info ON personal_info.incomming_call_id = incomming_call.id
																			 JOIN      info_category ON info_category.id=incomming_call.cat_1 
																			 LEFT JOIN branch ON personal_info.branch_id = branch.id
																			 WHERE     DATE(`incomming_call`.`date`) >= '$start'  
            																		   AND DATE(`incomming_call`.`date`) <= '$end' 
            																		   AND IF(ISNULL(branch.`name`),'Other',branch.`name`) = '$departament')  * 100),2)
                                FROM     `incomming_call`
                                JOIN      personal_info ON personal_info.incomming_call_id = incomming_call.id
                                JOIN      info_category AS category ON category.id = incomming_call.cat_1
                                LEFT JOIN branch ON personal_info.branch_id = branch.id
                                WHERE     DATE(`incomming_call`.`date`) >= '$start' 
                                          AND DATE(`incomming_call`.`date`) <= '$end' 
                                          AND IF(ISNULL(branch.`name`),'Other',branch.`name`) = '$departament'
                                GROUP BY  category.`name`
		
		
		
		");
		$text[0]=$text[1];
	break;
	case  2:
	    $result = mysql_query("SELECT     IF(incomming_call.cat_1_1=999, 'No Category', sub_category.`name`) as type,
                        				  COUNT(incomming_call.id) AS `count`,
                        				  ROUND((COUNT(incomming_call.id) / (SELECT    COUNT(incomming_call.id)
    																		 FROM     `incomming_call`
    																		 LEFT JOIN personal_info ON personal_info.incomming_call_id = incomming_call.id
                                                                             JOIN      info_category ON info_category.id = incomming_call.cat_1
    																		 LEFT JOIN info_category AS sub_category ON sub_category.id=incomming_call.cat_1_1 
    																		 LEFT JOIN branch ON personal_info.branch_id = branch.id
    																		 WHERE     DATE(`incomming_call`.`date`) >= '$start'
            																		   AND DATE(`incomming_call`.`date`) <= '$end' 
            																		   AND info_category.`name`='$type'  
            																		   AND IF(ISNULL(branch.`name`),'Other',branch.`name`) ='$departament')  * 100),2)
                                FROM 	 `incomming_call`
                                LEFT JOIN personal_info ON personal_info.incomming_call_id = incomming_call.id
                                JOIN      info_category ON info_category.id = incomming_call.cat_1
                                LEFT JOIN info_category AS sub_category ON sub_category.id = incomming_call.cat_1_1
                                LEFT JOIN branch ON personal_info.branch_id = branch.id
                                WHERE 	  DATE(`incomming_call`.`date`) >= '$start'
                                          AND DATE(`incomming_call`.`date`) <= '$end'
                                          AND info_category.`name`='$type'
                                          AND IF(ISNULL(branch.`name`),'Other',branch.`name`) ='$departament'
                                GROUP BY  type");
	    $text[0]=$text[2];
	    break;
    case  3:
        $result = mysql_query("  SELECT    IF(incomming_call.cat_1_1_1=999, 'No Category', sub_category1.`name`) as type,
                    					   COUNT(incomming_call.id) AS `count`,
                    					   ROUND((COUNT(incomming_call.id) / (SELECT    COUNT(incomming_call.id)
																			  FROM     `incomming_call`
																			  LEFT JOIN personal_info ON personal_info.incomming_call_id = incomming_call.id
																			  JOIN      info_category ON info_category.id = incomming_call.cat_1
																			  LEFT JOIN info_category AS sub_category ON sub_category.id=incomming_call.cat_1_1
																			  LEFT JOIN info_category AS sub_category1 ON sub_category1.id = incomming_call.cat_1_1_1
																			  LEFT JOIN branch ON personal_info.branch_id = branch.id
																			  WHERE     DATE(`incomming_call`.`date`) >= '$start' 
        																			    AND DATE(`incomming_call`.`date`) <= '$end' 
        																			    AND info_category.`name`='$type' 
        																			    AND IF(incomming_call.cat_1_1=999, 'No Category', sub_category.`name`)='$category' 
        																			    AND IF(ISNULL(branch.`name`),'Other',branch.`name`) ='$departament')  * 100),2)
                                 FROM 	  `incomming_call`
                                 LEFT JOIN personal_info ON personal_info.incomming_call_id = incomming_call.id
                                 JOIN 	   info_category ON info_category.id = incomming_call.cat_1
                                 LEFT JOIN info_category AS sub_category ON sub_category.id = incomming_call.cat_1_1
                                 LEFT JOIN info_category AS sub_category1 ON sub_category1.id = incomming_call.cat_1_1_1
                                 LEFT JOIN branch ON personal_info.branch_id = branch.id
                                 WHERE 	   DATE(`incomming_call`.`date`) >= '$start' 
                                           AND DATE(`incomming_call`.`date`) <= '$end' 
                                           AND info_category.`name`='$type' 
                                           AND IF(incomming_call.cat_1_1=999, 'No Category', sub_category.`name`)='$category' 
                                           AND IF(ISNULL(branch.`name`),'Other',branch.`name`) ='$departament'
                                 GROUP BY  type");
        $text[0]=$text[3];
        break;
	default:
		$result = mysql_query(" SELECT    IF(ISNULL(branch.`name`),'Other',branch.`name`) AS `br_name`,
                                          COUNT(incomming_call.id) AS `count`,
                                          ROUND((COUNT(incomming_call.id) / (SELECT     COUNT(incomming_call.id) AS `count`
                                                                              FROM     `incomming_call`
                                                                              JOIN      personal_info ON personal_info.incomming_call_id = incomming_call.id
                                                                              LEFT JOIN branch ON personal_info.branch_id = branch.id
                                                                              WHERE     DATE(`incomming_call`.`date`) >= '$start' and  DATE(`incomming_call`.`date`) <= '$end' AND IF(ISNULL(branch.`name`),'Other',branch.`name`) IN($branches))  * 100),2)
                                FROM     `incomming_call`
                                JOIN      personal_info ON personal_info.incomming_call_id = incomming_call.id
                                LEFT JOIN branch ON personal_info.branch_id = branch.id
                                WHERE     DATE(`incomming_call`.`date`) >= '$start' and  DATE(`incomming_call`.`date`) <= '$end' AND IF(ISNULL(branch.`name`),'Other',branch.`name`) IN($branches)
                                GROUP BY  branch.`id`");

		break;
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
		    
		    if($_REQUEST[rid] == 'No Category'){
		        $rid = 'AND incomming_call.cat_1_1_1 = 999';
		    }else{
		        $rid = "AND cat_1_1_1.`name` = '$_REQUEST[rid]'";
		    }
		    
			mysql_query("SET @i = 0;");
			$rResult = mysql_query("SELECT 	incomming_call.id,
                            				incomming_call.id,
                            				incomming_call.date,
                            				incomming_call.phone,
                            				IF(personal_info.cl_name!='',CONCAT(personal_info.cl_name,' ',personal_info.cl_ab),personal_info.cl_name) AS `name`,
                        				    cat_1.`name` AS `cat_1`,
                            				cat_1_1.`name` AS `cat_1_1`,
                            				cat_1_1_1.`name` AS `cat_1_1_1`,
			                                 concat('<p onclick=play(', '\'', date_format(`asterisk_incomming`.`call_datetime` ,'%Y/%m/%d/'),`asterisk_incomming`.`file_name`, '\'', ')>Listen</p>') AS `file`
                                    FROM 	`incomming_call`
                                    LEFT JOIN	info_category AS cat_1 ON incomming_call.cat_1 = cat_1.id
                                    LEFT JOIN	info_category AS cat_1_1 ON incomming_call.cat_1_1 = cat_1_1.id
                                    LEFT JOIN	info_category AS cat_1_1_1 ON incomming_call.cat_1_1_1 = cat_1_1_1.id
                                    LEFT JOIN personal_info ON incomming_call.id = personal_info.incomming_call_id
									LEFT JOIN branch ON personal_info.branch_id = branch.id
			                        LEFT JOIN asterisk_incomming ON incomming_call.asterisk_incomming_id = asterisk_incomming.id
                                    WHERE incomming_call.actived = 1 
    			                    AND DATE(incomming_call.date)>='$start'
    			                    AND DATE(incomming_call.date)<='$end'
    			                    $rid
                    			    AND IF(incomming_call.cat_1_1=999, 'No Category', cat_1_1.`name`) = '$_REQUEST[sub_category]'
                                    AND cat_1.`name` = '$_REQUEST[category]'
									AND IF(ISNULL(branch.`name`),'Other',branch.`name`) ='$_REQUEST[type]'");
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