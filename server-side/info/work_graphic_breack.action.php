<?php

require_once ('../../includes/classes/core.php');

$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';
switch ($action) {
	case 'get_list' :
		$count 		= $_REQUEST['count'];
		$hidden 	= $_REQUEST['hidden'];
		$w_id 	    = $_REQUEST['w_id'];
		
		if ($w_id=='') {
		    $req = mysql_fetch_assoc(mysql_query("SELECT MAX(id)+1 AS worck_id 
                                                    FROM work_graphic"));
		    $w_id=$req[worck_id];
		}
	  	$rResult 	= mysql_query(" SELECT  `id`,
                                	  	    `start`,
                                	  	    `end`
                                    FROM    `work_graphic_break`
                                    WHERE   `actived` = 1 AND wg_id=$w_id");

		$data = array(
				"aaData"	=> array()
		);
		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				if($i == ($count - 1)){
					$row[] = '<div class="callapp_checkbox">
                          <input type="checkbox" id="callapp_b_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                          <label for="callapp_b_checkbox_'.$aRow[$hidden].'"></label>
                      </div>';
				}
				$row[] = $aRow[$i];

			}
			$data['aaData'][] = $row;
		}

		break;
		
	case "get_edit_page":
	    
	   $data['page'][]=page();
	   
	   break;
	case 'disable':
	    
		mysql_query("UPDATE `work_graphic_break` SET `actived`='0' WHERE (`id`='$_REQUEST[id]')");
		
		break;
	case 'get_add_page' :
	    
	   $data['page'][]=page();
	   
		break;
   	case "break":
   	    
	    if($_REQUEST[w_id] == ''){
	        
	        $wg_id = increment('work_graphic');
	        
	    }else{
	        
	        $wg_id = $_REQUEST[w_id];
	        
	    }
	    
	    if ($_REQUEST[id]=='') {
	        
	        $user = $_SESSION['USERID'];
	        
	        mysql_query("INSERT INTO `work_graphic_break`
                	            (`wg_id`, `user_id`, `start`, `end`)
                	            VALUES
                	            ('$wg_id', '$user', '$_REQUEST[start_b]', '$_REQUEST[end_b]')");
	    }else {
	        
	        $user = $_SESSION['USERID'];
	        
	        mysql_query("UPDATE `work_graphic_break` 
                        	SET `user_id`= '$user', 
                        		`start`  = '$_REQUEST[start_b]', 
                        		`end`    = '$_REQUEST[end_b]' 
                         WHERE  `id`     = '$_REQUEST[id]'");
	    }
	    
	    break;
	default:
		$error = 'Action is Null';
}
function page()
{
		$rResult 	= mysql_query(" SELECT 	id,
		                                    start,
		                                    end
                				    FROM `work_graphic_break`
                				    WHERE id = '$_REQUEST[id]' AND work_graphic_break.actived = 1");
		$res = mysql_fetch_array( $rResult );

	

	return '
	<div id="dialog-form">
		<fieldset >
	    	<legend >ძირითადი ინფორმაცია</legend>
            <input type="" style="opacity: 0; height: 0px;" id="hidden"/>
	    	<table class="dialog-form-table">
				<tr>
					<td style="width: 200px;"><label for="">შესვენების დასაწყისი</label></td>
					<td style="width: 200px;"><label for="">შესვენების დასასრული</label></td>
				</tr>
	            <tr>
					<td><input id="start_breack" class="idle time" type="text" value="'.$res[start].	'" /></td>
					<td><input id="end_breack" class="idle time" type="text" value="'.$res[end].'" /></td>
				</tr>
			</table>
		</fieldset>
	</div>
    <input type="hidden" id="w_g_break" value='.$_REQUEST[id].'>';

}

$data['error'] = $error;

echo json_encode($data);

function increment($table){

    $result   		= mysql_query("SHOW TABLE STATUS LIKE '$table'");
    $row   			= mysql_fetch_assoc($result);
    $increment   	= $row['Auto_increment'];

    return $increment;
}
?>