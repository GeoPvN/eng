<?php
/* ******************************
 *	Request aJax actions
 * ******************************
*/

require_once('../includes/classes/core.php');
$action = $_REQUEST['act'];
$error	= '';
$data	= '';

switch ($action) {
    case 'logout_save':   
        SaveLogout();
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

function SaveLogout()
{
    $user		= $_SESSION['USERID'];
    
    mysql_query("
                    UPDATE  `worker_action` SET
                            `end_date`=NOW(),
                            `actived`='2'
                    WHERE   `actived`= 1 AND `person_id`='$user' AND DATE(start_date) = DATE(NOW())
                ");
    
}
?>