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

$start_time = $_REQUEST['start'];
$end_time 	= $_REQUEST['end'];
$group_id 	= $_REQUEST['group_id'];

$filt_group = '';

if ($group_id != 0) {
    $filt_group = " AND users.group_id = $group_id";
}

switch ($action) {
	case 'excel' :
        $excel_res = mysql_query("  SELECT    worker_action.id,
                                              user_info.`name`,
                                              DATE(worker_action.start_date),
                                              TIME(`worker_action`.`start_date`),
                                              TIME(worker_action.end_date),
                                              IF(worker_action_break.comment_start = '','',GROUP_CONCAT(worker_action_break.comment_start,' - ',worker_action_break.comment_end separator ';'))
                                    FROM      worker_action 

                                    JOIN      user_info ON user_info.user_id = worker_action.person_id
                                    LEFT JOIN worker_action_break ON worker_action_break.worker_action_id=worker_action.id
                                    LEFT JOIN person_work_graphic AS PWG ON DATE(worker_action.start_date) = DATE(PWG.`start`) AND worker_action.person_id = PWG.person_id AND PWG.actived = 1
                                    WHERE     DATE(worker_action.start_date) BETWEEN '$start_time' AND '$end_time' AND users.group_id!=5 AND users.id !=1 $filt_group
                                    GROUP BY  DATE(worker_action.start_date), TIME(worker_action.start_date), persons.`name`
                                    ORDER BY  persons.`name`,`worker_action`.`start_date`");
        $data = ' <div>
            <button id="goexcel" style="margin-bottom: 10px;">EXCEL</button>
            <table id="exel_tabel">
                    <tr>
                        <th>პიროვნება</th>
                        <th>თარიღი</th>
                        <th>LOG IN</th>
                        <th>LOG OUT</th>';
        $get_content = mysql_query("SELECT  `id`,
                                            `name`
                                    FROM    `work_activities`
                                    WHERE   `actived` = 1 AND `id` != 0
                                    ORDER BY work_activities.order ASC");
        while ($get_content_res = mysql_fetch_array($get_content)){
            $data .= '<th>'.$get_content_res[1].'</th>';
        }
        $data .= '<th>COMMENT</th></tr>';
        while ($excel_req = mysql_fetch_array($excel_res)){
            $data .= '<tr>
                        <td>'.$excel_req[1].'</td>
                        <td>'.$excel_req[2].'</td>
                        <td>'.$excel_req[3].'</td>
                        <td>'.$excel_req[4].'</td>';
            $br = mysql_query(" SELECT   work_activities.`name`,
                                         GROUP_CONCAT(CONCAT(IF(ISNULL(CAST(worker_action_break.start_date AS char)),'',CAST(worker_action_break.start_date AS char)),'-',IF(ISNULL(CAST(worker_action_break.end_date AS char)),'',CAST(worker_action_break.end_date AS char))) separator ';'),
                                         IF(TIMEDIFF(worker_action_break.end_date,worker_action_break.start_date) < work_activities.timer OR TIME_TO_SEC(work_activities.timer) = 0,'',IF((NOT ISNULL(TIMEDIFF(worker_action_break.end_date,worker_action_break.start_date))),'background:red;',''))
                                FROM     `work_activities`
                                LEFT JOIN worker_action_break ON worker_action_break.work_activities_id = work_activities.id AND worker_action_break.worker_action_id='$excel_req[0]'
                                WHERE   work_activities.`actived` = 1 AND work_activities.`id` != 0
                                GROUP BY work_activities.`name`
                                ORDER BY work_activities.order ASC");
            while ($br_res = mysql_fetch_array($br)){
                $data .= '<td style="'.$br_res[2].'">'.$br_res[1].'</td>';
            }
                        $data .= '<td>'.$excel_req[5].'</td>
                      </tr>';
        }
        $data .= '</table></div>';
	    break;
    default:
       $error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);

?>