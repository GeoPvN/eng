<?php
include('../../includes/classes/core.php');
$action     = $_REQUEST['act'];
$start  	= $_REQUEST['start'];
$end    	= $_REQUEST['end'];
switch ($action) {
    case 'get_chart':
        $branchRes = mysql_query("SELECT     branch.id,
                                             branch.name
                                  FROM       branch
                                  ORDER BY   branch.id DESC");
        
        $sourceRes = mysql_query("SELECT     source_info.id,
                                             source_info.name
                                  FROM       source_info");
        
        $sourceArray  = array();
        $branchArray  = array();
        $totalArray   = array();
        
        while ($branchRow = mysql_fetch_assoc($branchRes)) {
            $branchArray[] = $branchRow[name];
        }
        
        $i = 0;
        $totalSum = 0;
        
        while ($sourceRow = mysql_fetch_assoc($sourceRes)) {
            
            $sourceArray[$i]['type'] = 'column';
            $sourceArray[$i]['name'] = $sourceRow['name'];
            
            $branchRes = mysql_query("SELECT    branch.`name`,
                            			        SUM(IF(ISNULL(incomming_call.date), 0,1)) AS `count`
                                      FROM      branch
                                      LEFT JOIN personal_info ON personal_info.branch_id = branch.id AND personal_info.source_info_id = $sourceRow[id]
                                      LEFT JOIN incomming_call ON personal_info.incomming_call_id = incomming_call.id AND DATE(incomming_call.date) BETWEEN '$start' AND '$end'
                                      GROUP BY  branch.id
                                      ORDER BY  branch.id DESC");
            
            $sum = 0;
            while ($branchRow = mysql_fetch_assoc($branchRes)) {        
                
                $sourceArray[$i]['data'][] = $branchRow[count];
                $totalArray[$i] = array('name' => $sourceRow['name'], 'y' =>  $sum + $branchRow[count]);
                
                $sum = $sum + $branchRow[count];
                
            }
            
            $i++;
            $totalSum += $sum; 
              
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
        
        
        $sourceArray[$i]['type']                  = 'pie';
        $sourceArray[$i]['name']                  = 'Total sum';
        $sourceArray[$i]['data']                  = $totalArray;
        $sourceArray[$i]['center']                = array(20, 30);
        $sourceArray[$i]['size']                  = 100;
        $sourceArray[$i]['showInLegend']          = false;
        $sourceArray[$i]['dataLabels']['enabled'] = false;
        
        $data = array('dataBranch' => $branchArray, 'dataSource' => $sourceArray, 'totalSum' => $totalSum);
                            
        echo json_encode($data,JSON_NUMERIC_CHECK);
        break;
    case 'get_list':
        $count        = $_REQUEST['count'];
        $hidden       = $_REQUEST['hidden'];
        
        $rResult = mysql_query("SELECT   br.`name`,br.`name`,
                                        (SELECT  COUNT(*) FROM personal_info JOIN incomming_call ON personal_info.incomming_call_id = incomming_call.id 
                                             WHERE  personal_info.source_info_id = 1 AND personal_info.branch_id = br.id AND DATE(incomming_call.date) BETWEEN '$start' AND '$end'),
                                    
                                        (SELECT  COUNT(*) FROM personal_info JOIN incomming_call ON personal_info.incomming_call_id = incomming_call.id 
                                             WHERE  personal_info.source_info_id = 2 AND personal_info.branch_id = br.id AND DATE(incomming_call.date) BETWEEN '$start' AND '$end'),
                                    
                                            (SELECT  COUNT(*) FROM personal_info JOIN incomming_call ON personal_info.incomming_call_id = incomming_call.id 
                                             WHERE  personal_info.source_info_id = 3 AND personal_info.branch_id = br.id AND DATE(incomming_call.date) BETWEEN '$start' AND '$end'),
                                    
                                            (SELECT  COUNT(*) FROM personal_info JOIN incomming_call ON personal_info.incomming_call_id = incomming_call.id 
                                             WHERE  personal_info.source_info_id = 4 AND personal_info.branch_id = br.id AND DATE(incomming_call.date) BETWEEN '$start' AND '$end')
                                FROM   branch AS `br`");
         
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
        echo json_encode($data);
        break;
}

?>

