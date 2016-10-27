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
$title 		= $_REQUEST['title'];
$text[0] 	= "შემოსული  ზარები კატეგორიების  მიხედვით";
$text[1] 	= "'$departament'- შემოსული ზარები  ქვე-კატეგორიების მიხედვით";
$text[2] 	= "'$departament'- შემოსული ზარები ქვე-ქვე-კატეგორიების  მიხედვით";
$text[3] 	= "'$departament'- შემოსული  ქვე–კატეგორიის მიხედვით";
$c="3 or incomming_call.call_type_id=0";
if ($type=="ინფორმაცია")  $c=1;
elseif ($type=="პრეტენზია") $c=2;
elseif ($type=="სხვა") $c=3;
//------------------------------------------------query-------------------------------------------
switch ($done){
	case  1:
		$result = mysql_query(" SELECT  IF(c_type.`name`!='',c_type.`name`,'შეუვსებელია') as type,
                        				COUNT(*),
                        				CONCAT(ROUND(COUNT(*)/(SELECT COUNT(*) FROM incomming_call
                        				LEFT JOIN info_category ON incomming_call.cat_1 = info_category.id
                        				LEFT JOIN info_category as c_type ON incomming_call.cat_1_1 = c_type.id
                        				WHERE DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end' AND info_category.`name` = '$departament')*100,2),'%')
                                FROM 	incomming_call
                                LEFT JOIN info_category ON incomming_call.cat_1 = info_category.id
                                LEFT JOIN info_category as c_type ON incomming_call.cat_1_1 = c_type.id
                                WHERE 	DATE(`incomming_call`.`date`) >= '$start' and  DATE(`incomming_call`.`date`) <= '$end' AND info_category.`name` = '$departament'
                                GROUP BY 	c_type.`name`");
		$text[0]=$text[1];
	break;
	case  2:
	    $result = mysql_query(" SELECT  IF(sub_cat.`name`!='',sub_cat.`name`,'შეუვსებელია') as type,
                        				COUNT(*),
                        				CONCAT(ROUND(COUNT(*)/(SELECT COUNT(*) FROM incomming_call
                        				LEFT JOIN info_category ON incomming_call.cat_1 = info_category.id
                        				LEFT JOIN info_category as c_type ON incomming_call.cat_1_1 = c_type.id
                        				LEFT JOIN info_category as sub_cat ON incomming_call.cat_1_1_1 = sub_cat.id
                        				WHERE DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end' AND info_category.`name` = '$departament' AND c_type.`name` = '$type')*100,2),'%')
                                FROM 	incomming_call
                                LEFT JOIN info_category ON incomming_call.cat_1 = info_category.id
                                LEFT JOIN info_category as c_type ON incomming_call.cat_1_1 = c_type.id
                                LEFT JOIN info_category as sub_cat ON incomming_call.cat_1_1_1 = sub_cat.id
                                WHERE 	DATE(`incomming_call`.`date`) >= '$start' and  DATE(`incomming_call`.`date`) <= '$end' AND info_category.`name` = '$departament' AND c_type.`name` = '$type'
                                GROUP BY 	sub_cat.`name`");
	    $text[0]=$text[2];
	    break;
	default:
		$result = mysql_query(" SELECT  IF(info_category.`name`!='',info_category.`name`,'შეუვსებელია') as type,
                                        COUNT(*),
                                        CONCAT(ROUND(COUNT(*)/(SELECT COUNT(*) FROM incomming_call
                                        LEFT JOIN info_category ON incomming_call.cat_1 = info_category.id
                                        WHERE DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end')*100,2),'%')
                                FROM 	incomming_call
                                LEFT JOIN info_category ON incomming_call.cat_1 = info_category.id
                                WHERE 	DATE(`incomming_call`.`date`) >= '$start' and  DATE(`incomming_call`.`date`) <= '$end'
                                GROUP BY 	info_category.`name`");

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
		    
		    if($_REQUEST[rid] == 'შეუვსებელია'){
		        $rid = 'AND incomming_call.cat_1_1_1 = 0';
		    }else{
		        $rid = "AND cat_1_1_1.`name` = '$_REQUEST[rid]'";
		    }
		    
			mysql_query("SET @i = 0;");
			$rResult = mysql_query("SELECT 	incomming_call.id,
                            				incomming_call.id,
                            				incomming_call.date,
                            				incomming_call.phone,
                            				IF(personal_info.client_person_lname!='',CONCAT(personal_info.client_person_lname,' ',personal_info.client_person_fname),personal_info.client_name) AS `name`,
                        				    cat_1.`name` AS `cat_1`,
                            				cat_1_1.`name` AS `cat_1_1`,
                            				cat_1_1_1.`name` AS `cat_1_1_1`,
                            				`comment`
                                    FROM 	`incomming_call`
                                    LEFT JOIN	info_category AS cat_1 ON incomming_call.cat_1 = cat_1.id
                                    LEFT JOIN	info_category AS cat_1_1 ON incomming_call.cat_1_1 = cat_1_1.id
                                    LEFT JOIN	info_category AS cat_1_1_1 ON incomming_call.cat_1_1_1 = cat_1_1_1.id
                                    LEFT JOIN personal_info ON incomming_call.id = personal_info.incomming_call_id
                                    WHERE incomming_call.actived = 1 
    			                    AND DATE(incomming_call.date)>='$start'
    			                    AND DATE(incomming_call.date)<='$end'
    			                    $rid
                    			    AND cat_1_1.`name` = '$_REQUEST[category]'
                                    AND cat_1.`name` = '$_REQUEST[type]'");
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