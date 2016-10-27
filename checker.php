<?php 

include 'includes/classes/core.php';

if($_REQUEST['act'] == 'get_checker'){
    
    $action_now_count = mysql_fetch_row(mysql_query("   SELECT COUNT(*) AS `new_news`
                                                        FROM `action`
                                                        WHERE TIMEDIFF(NOW(),create_date) <= '04:00:00'
                                                        AND actived = 1"));

    $count = $action_now_count[0];

    $data = array('count'=>$count);
    
    echo json_encode($data);
}

?>