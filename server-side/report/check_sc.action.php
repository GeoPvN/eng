<?php
include('../../includes/classes/core.php');
if ($_REQUEST['act']=='check_sc'){
    $res = mysql_query("SELECT id, `name` 
                        FROM   `service_center` 
                        WHERE   actived = 1 AND parent_id = 0 AND branch_id IN($_REQUEST[ids])");
    $data = array('test'=>array());
    $data['test'][] = '<option value="0" selected="selected">(ყველა)</option>';
    while ($req = mysql_fetch_array($res)) {
        $data['test'][] = '<option value="'.$req['id'].'">'.$req['name'].'</option>';
    }
}
echo json_encode($data);
?>