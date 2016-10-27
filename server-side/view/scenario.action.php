<?php
require_once('../../includes/classes/core.php');
$action	= $_REQUEST['act'];
$error	= '';
$data	= '';

$user_id	       = $_SESSION['USERID'];
$quest_id          = $_REQUEST['id'];
$quest_detail_id   = $_REQUEST['quest_detail_id'];
$add_id            = $_REQUEST['add_id'];
$name              = mysql_real_escape_string($_REQUEST['name']);
$note              = mysql_real_escape_string($_REQUEST['note']);
$answer            = mysql_real_escape_string($_REQUEST['answer']);
$quest_type_id     = $_REQUEST['quest_type_id'];
$quest_id1         = $_REQUEST['quest_id1'];

$cat               = $_REQUEST['cat'];
$le_cat            = $_REQUEST['le_cat'];

switch ($action) {
	case 'get_add_page':
		$page		= GetPage(GetList($add_id));
		$data		= array('page'	=> $page);
 
		break;
	case 'get_edit_page':
		$page		= GetPage(GetList($quest_id,$quest_detail_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
		$rResult = mysql_query("SELECT 	`scenario`.`id`,
                        				`scenario`.`name`,
                        				`cat`.`name`,
                        				`le_cat`.`name`
                                FROM 	`scenario`
                                JOIN    `scenario_category` AS `cat` ON `scenario`.`scenario_cat_id` = `cat`.`id`
                                JOIN    `scenario_category` AS `le_cat` ON `scenario`.`scenario_le_cat_id` = `le_cat`.`id`
                                WHERE 	`scenario`.`actived` = 1");

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
				if($i == ($count - 1)){
				    $row[] = '<div class="callapp_checkbox">
                                  <input type="checkbox" id="callapp_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                                  <label for="callapp_checkbox_'.$aRow[$hidden].'"></label>
                              </div>';
				}
			}
			$data['aaData'][] = $row;
		}

		break;
	case 'get_list_detail' :
	    $count	= $_REQUEST['count'];
	    $hidden	= $_REQUEST['hidden'];
	    	
	    $rResult = mysql_query("SELECT 	`scenario_detail`.`id`,
	                                    `scenario_detail`.`sort`,
                        				`question`.`name`
                                FROM 	`scenario_detail`
                                LEFT JOIN question ON scenario_detail.quest_id = question.id
                                WHERE 	`scenario_detail`.`actived` = 1 AND `scenario_detail`.`scenario_id` = $quest_id");
	
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
	            if($i == ($count - 1)){
	                $row[] = '<div class="callapp_checkbox">
                                  <input type="checkbox" id="callapp_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                                  <label for="callapp_checkbox_'.$aRow[$hidden].'"></label>
                              </div>';
	            }
	        }
	        $data['aaData'][] = $row;
	    }
		    
		break;
	case 'save_quest':
	    if($quest_id == ''){
	       save($user_id, $name, $cat, $le_cat);
	    }else{
	       update($quest_id, $name, $cat, $le_cat);
	    }	    
	    
	    //if($_REQUEST[dest_checker] == 1){
	        $checker     = json_decode($_REQUEST[checker]);
    	    foreach ($checker as $key => $value) {
    	         $quest_id = str_replace("scenarquest","",$key);
    	         $val = substr($value,10);
    	         $twoinone = preg_split("/[\s|]+/",$quest_id);

                 $rr = mysql_fetch_array(mysql_query("  SELECT id
                                                        FROM `scenario_destination`
                                                        WHERE scenario_detail_id  = $twoinone[1] AND answer_id = $twoinone[2]"));
                 
                 if($rr[0] == ''){
                     mysql_query("INSERT INTO `scenario_destination`
                                 (`scenario_detail_id`, `destination`, `answer_id`)
                                 VALUES
                                 ( '$twoinone[1]', '$val', '$twoinone[2]');");
                 }else{
                     mysql_query("  UPDATE `scenario_destination`
                                    SET `destination`=$val
                                    WHERE `id`=$rr[0]");
                 }

    	    }
	    //}
		break;
	case 'save_answer':
	    if($_REQUEST['quest_detail_id'] == ''){
	        save_answer($user_id, $quest_id1, $add_id);
	    }else{
	        update_answer($quest_detail_id, $quest_id1, $_REQUEST['quest_id']);
	    }
		
		break;
	case 'disable':
	        disable($quest_id);

		break;
	case 'disable_cd':
	        disable_det($quest_id);
	
	    break;
	case 'get_scen_cat':
	    $data['cat'] = GetLeCat($_REQUEST['cat_id'],'');
		
		break;
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Category Functions
* ******************************
*/

function save($user_id, $name, $cat, $le_cat)
{
    global $error;
    $name_cheker = mysql_num_rows(mysql_query("SELECT id FROM scenario WHERE `name` = '$name'"));
    if($name_cheker == 0){
        mysql_query("INSERT INTO `scenario`
                    (`user_id`,`name`,`scenario_cat_id`,`scenario_le_cat_id`)
                    VALUES
                    ('$user_id','$name','$cat','$le_cat')");
        
    }else{
        $error = 'ესეთი სახელი უკვე არსებობს!';
    }
    return $error;
}

function update($quest_id, $name, $cat, $le_cat)
{
    mysql_query("	UPDATE  `scenario`
                    SET     `name`                  = '$name',
                            `scenario_cat_id`       = '$cat',
                            `scenario_le_cat_id`    = '$le_cat'
                    WHERE	`id`   = $quest_id");
}

function save_answer($user_id, $quest_id1, $add_id)
{
    global $error;
    $name_cheker = mysql_num_rows(mysql_query("SELECT id FROM scenario_detail WHERE actived = 1 AND `quest_id` = '$quest_id1' AND `scenario_id` = '$add_id'"));
    if($name_cheker == 0){
    
    $sort = mysql_fetch_array(mysql_query(" SELECT  `sort`
                                            FROM 	`scenario_detail`
                                            WHERE 	`scenario_id` = $add_id
                                            ORDER BY `id` DESC
                                            LIMIT 1"));
    if($sort[0] == ''){
        $sort_key = 1;
    }else{
        $sort_key = $sort[0]+1;
    }
    
    $answer_check = mysql_num_rows(mysql_query("SELECT question.id
                                                FROM `question`
                                                JOIN question_detail ON question.id = question_detail.quest_id
                                                WHERE question.id = $quest_id1"));
    
    if($answer_check > 0){
    mysql_query("INSERT INTO `scenario_detail`
                (`user_id`,`quest_id`,`scenario_id`,`sort`)
                VALUES
                ('$user_id','$quest_id1','$add_id','$sort_key')");

    }else{
        $error = 'ამ კითხვას არ აქვს პასუხები! ჯერ პასუხები შეავსეთ და მერე დაამატეტ კითხვა სცენარში!';
    }    
    }else{
        $error = 'ესეთი სახელი უკვე არსებობს!';
    }
 return $error;
}

function update_answer($quest_detail_id, $quest_id1, $quest_id)
{
    mysql_query("	UPDATE  `scenario_detail`
                    SET     `quest_id`      = '$quest_id1',
                            `scenario_id`   = '$quest_id'
                    WHERE	`id`            =  $quest_detail_id");
}

function disable($quest_id)
{
    mysql_query("	UPDATE  `scenario`
                    SET     `actived` = 0
                    WHERE	`id`      = $quest_id");
}

function disable_det($quest_detail_id)
{
    mysql_query("	UPDATE  `scenario_detail`
                    SET     `actived` = 0
                    WHERE	`id`      = $quest_detail_id");
}

function GetQuest($quest_detail_id, $rr)
{
    $rrr = mysql_fetch_array(mysql_query("  SELECT  `question`.`id`
                                            FROM 	`scenario`
                                            LEFT JOIN scenario_detail ON scenario.id = scenario_detail.scenario_id
                                            LEFT JOIN question ON scenario_detail.quest_id = question.id
                                            WHERE 	`scenario_detail`.`id` = $rr"));
    
    $req = mysql_query("	SELECT 	`question`.`id`,
                                    `question`.`name`
                            FROM 	`question`
                            WHERE 	`question`.`actived` = 1" );

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $rrr[0]){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}

function GetCat($cat_id)
{
    $req = mysql_query("	SELECT  `id`,
                                    `name`
                            FROM    `scenario_category`
                            WHERE   `parent_id` = 0 AND `actived` = 1" );

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $cat_id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}

function GetLeCat($cat_id,$le_cat_id)
{
    $req = mysql_query("	SELECT  `id`,
                                    `name`
                            FROM    `scenario_category`
                            WHERE   `parent_id` = $cat_id AND `actived` = 1" );

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $le_cat_id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}

function GetAlScenQuest($scenarquest,$dest)
{
    $req = mysql_query("	SELECT  `question`.`id`,
                    				`question`.`name`
                            FROM    `scenario`
                            JOIN 	`scenario_detail` ON `scenario`.`id` = `scenario_detail`.`scenario_id`
                            JOIN 	`question` ON `scenario_detail`.`quest_id` = `question`.`id`
                            WHERE 	`scenario`.`id` = $scenarquest AND scenario_detail.actived = 1 AND scenario.actived = 1" );

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $dest){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}

function gethandbook($id,$done_id){
    $req = mysql_query("  SELECT `id`,
        `value`
        FROM   `scenario_handbook_detail`
        WHERE  `scenario_handbook_id` = $id AND actived = 1");

    $data .= '<option value="0" >----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $done_id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['value'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['value'] . '</option>';
        }
    }

    return $data;

}

function GetList($quest_id,$quest_detail_id)
{
    if($quest_id != ''){
        $checker = "scenario.`id` = $quest_id";
    }
    if($quest_detail_id != ''){
        $checker = "scenario_detail.`id` = $quest_id";
    }
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `scenario`.`id` AS `scenario_id`,
                                    				`scenario`.`name` AS `scenario_name`,
                                    				`scenario_detail`.`id` AS `sc_detal_id`,
	                                                `scenario_detail`.`quest_id` AS `quest_id`,
                                    				`question`.`name` AS `quest_name`,
                                                    `scenario`.`scenario_cat_id`,
                                    				`scenario`.`scenario_le_cat_id`
                                            FROM 	`scenario`
                                            LEFT JOIN scenario_detail ON scenario.id = scenario_detail.scenario_id
                                            LEFT JOIN question ON scenario_detail.quest_id = question.id
                                            WHERE 	$checker"));

	return $res;
}



function GetPage($res = '')
{	
    $data = '
        <!-- ID -->
			<input type="hidden" id="quest_id" value="' . $res['scenario_id'] . '" />
			<input type="hidden" id="quest_detail_id" value="'.$_REQUEST['id'].'" />
			<input type="hidden" id="add_id" value="' . $_REQUEST['add_id'] . '" />
			<input type="hidden" id="dest_checker" value="0" /> <script>$("#cat,#le_cat,#quest_id1").chosen({ search_contains: true });$("#add-edit-form-answer,.add-edit-form-class,.add-edit-form-answer-class").css("overflow","visible")</script>
	<div id="dialog-form">
	    <fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>
    
	    	<table class="dialog-form-table" style="margin:0 0 10px 0;">
				<tr>
					<td style="width: 170px;"><label for="name">სახელი</label></td>
					<td>
						<textarea type="text" id="name" style="margin: 0px; width: 226px; resize:vertical;">' . $res['scenario_name'] . '</textarea>
					</td>
				</tr>
                <tr>
					<td style="width: 170px;"><label for="">კატეგორია</label></td>
					<td>
						<select style="width: 231px;" id="cat" class="idls object">'. GetCat($res[scenario_cat_id]).'</select>
					</td>
				</tr>
			    <tr>
					<td style="width: 170px;"><label for="">ქვე-კატეგორია</label></td>
					<td>
						<select style="width: 231px;" id="le_cat" class="idls object">'. GetLeCat($res[scenario_cat_id],$res[scenario_le_cat_id]).'</select>
					</td>
				</tr>
			</table>';
			if($_REQUEST['id'] != ''){
			    if($_REQUEST['quest_detail_id'] == ''){
    			    $data .=  ' <div id="taab" style="margin: 0 auto; margin-top: 25px;">
                            	<div id="callapp_tab">
                            		<span id="tab1">დიალოგური ფანჯარა</span>
                            		<span id="tab2">კითხვების რიგითობა</span>
    			                    <span id="tab3">შემოწმება</span>
                            	</div>
                            	<div id="tab_content_1">
    			        
    			                <div id="button_area">
                    			    <button id="add_button_detail">დამატება</button>
                    			    <button id="delete_button_detail">წაშლა</button>
                			    </div>
                			    <table class="display" id="table_quest" style="background-color: #FFF;">
                    			    <thead >
                        			    <tr id="datatable_header">
                            			    <th style="display:none;">ID</th>
    			                            <th style="width: 60px;">#</th>
                            			    <th style="width: 100%;">დასახელება</th>
                            			    <th class="check">&nbsp;</th>
                        			    </tr>
                    			    </thead>
                    			    <thead>
                        			    <tr class="search_header">
                            			    <th class="colum_hidden" style="display:none;">
                        			             <input style="width: 100%;" type="text" name="search_category" value="ფილტრი" class="search_init" />
                        			        </th>		    
                            			    <th>
    			                                 <input style="width: 100%;" type="text" name="search_category" value="ფილტრი" class="search_init" />
                            			    </th>
    			                            <th>
                            			         <input style="width: 100%;" type="text" name="search_category" value="ფილტრი" class="search_init" />
                            			    </th>
                            			    <th>
                            			         <div class="callapp_checkbox">
                                                    <input type="checkbox" id="check-all-de" name="check-all" />
                                                    <label for="check-all-de"></label>
                                                </div>
                            			    </th>
                        			    </tr>
                    			    </thead>
                			    </table>
    			             </div>
                        	<div id="tab_content_2">';
                			    $i = 1;
                			    
                			  $query = mysql_query("SELECT 	    `question`.id,
                                            			        `question`.`name`,
                                            			        `question`.note,
                                            			        `scenario`.`name`
                                			        FROM        `scenario`
                                			        JOIN        scenario_detail ON scenario.id = scenario_detail.scenario_id
                                			        JOIN        question ON scenario_detail.quest_id = question.id
                                			        WHERE       scenario.id = $res[scenario_id] AND scenario_detail.actived = 1
                                			        ORDER BY    scenario_detail.sort ASC");
                			  
                			  $query2 = mysql_query(" SELECT 	`destination`,
                                            		            `answer_id`
                                            		    FROM 	`scenario_destination`
                                            		    JOIN 	`scenario_detail` ON scenario_destination.scenario_detail_id = scenario_detail.id
                                            		    JOIN 	`scenario` ON scenario_detail.scenario_id = scenario.id
                                            		    WHERE 	`scenario`.id = $res[scenario_id] AND scenario_destination.destination != 0");
                			  
                			  while ($rame = mysql_fetch_array($query2)){
                			      $destination .= ', '.$rame[0];
                			      $answer_id .= ', '.$rame[1];
                			  }
                			  $destination = substr($destination, 1);
                			  $answer_id = substr($answer_id, 1);
                			        //$row_scen = mysql_fetch_array($query);
                			    $data .= '<div id="dialog-form" style="width:102%; overflow-y:scroll; max-height:400px;">
                                <fieldset>
                                    <legend>კითხვები</legend>';
                			    
            		while ($row = mysql_fetch_array($query)) {
            		    
            		    
            		    if($answer_id==''){
            		        $answer_id = 0;
            		    }
                			    		$query1 = mysql_query(" SELECT 	question.id as q_id,
                                                                		question_detail.quest_type_id,
                			    		                                IF(question_detail.id in($answer_id) ,question_detail.id,'') AS `checked_quest`,
                			    		                                scenario_detail.id as sc_id,
                			    		                                question_detail.id as as_id,
                			    		                                scenario_destination.destination as dest,
                			    		                                question_detail.answer,
                			    		                                scenario_handbook.name as seleqti
                                                                FROM `question_detail`
                                                                JOIN  question ON question_detail.quest_id = question.id
                                                                LEFT JOIN scenario_detail ON question.id = scenario_detail.quest_id
                			    		                        LEFT JOIN scenario_destination ON scenario_detail.id = scenario_destination.scenario_detail_id AND scenario_destination.answer_id = question_detail.id
                                                                LEFT JOIN scenario ON scenario_detail.scenario_id = scenario.id 
                			    		                        LEFT JOIN scenario_handbook ON question_detail.answer = scenario_handbook.id 
                                                                WHERE question_detail.quest_id = $row[0] AND question_detail.actived = 1 AND scenario.id = $res[scenario_id]
                                                                GROUP BY question_detail.id
                                                                ORDER BY question.id, question_detail.quest_type_id ASC");
                			    			
                			    		
                			    
                			    		$data .= '<textarea style="width: 704px; height:100px; resize: none; background: #EBF9FF;" class="idle">'. $row[2] .'</textarea>
                			    		<table class="dialog-form-table">
                			    		<tr>
                			    		<td style="font-weight:bold;">'.$i++.'. '. $row[1] .'</td>
                			    		</tr>
                			    		
                			    		';
                			    		while ($row1 = mysql_fetch_array($query1)) {
                			    		$q_type = $row1[1];
                			    		$dest = $row1[5];
                			    		if($row1[1] == 1){
                			    		     $data .=  '<tr><td style="width:707px; text-align:left;"><input  class="check_input" style="float:left;" type="checkbox" name="checkbox' .$row1[0]. '" value="'.$row1[4].'"><label style="float:left; padding: 7px;">'.mysql_real_escape_string($row1[6]).'</label></td>';
                                        }elseif($row1[1] == 2){
                			    		     $data .=  '<tr><td style="width:707px; text-align:left;"><input value="" class="inputtext" style="float:left;" type="text" id="input' .$row1[0]. '|'.$row1[4].'" /> <label style=\"float:left; padding: 7px;\" for=\"input|'.$row1[0].'|'.$row1[4].'\">'.mysql_real_escape_string($row1[6]).'</label></td>';
                                        }elseif($row1[1] == 4){
                			    		     $data .=  '<tr><td style="width:707px; text-align:left;"><input class="radio_input" style="float:left;" type="radio" name="radio'.$row1[0].'" value="'.$row1[4].'"><label style=\"float:left; padding: 7px;\">'.$row1[6].'</label></td>';
                                        }elseif($row1[1] == 5){
                			    		     $data .=  '<tr><td style="width:707px; text-align:left;"><input value="" class="date_input" style="float:left;" type="text" id="input|'.$row1[0].'|'.$row1[4].'" /> <label style="float:left; padding: 7px;" for="input|'.$row1[0].'|'.$row1[4].'">'.mysql_real_escape_string($row1[6]).'</label></td>';
                                        }elseif($row1[1] == 6){
                			    		     $data .=  '<tr><td style="width:707px; text-align:left;"><input value="" class="date_time_input" style="float:left;" type="text" id="input|'.$row1[0].'|'.$row1[4].'" /> <label style="float:left; padding: 7px;" for="input|'.$row1[0].'|'.$row1[4].'">'.mysql_real_escape_string($row1[6]).'</label></td>';
                                        }elseif($row1[1] == 7){
                			    		     $data .=  '<tr><td style="width:707px; text-align:left;"><select class="hand_select" style="float:left;"  id="hand_select|'.$row1[0].'|'.$row1[4].'" ><option>'.$row1[7].'</option></select> <label style="float:left; padding: 7px;" for="hand_select|'.$row1[0].'|'.$row1[4].'"></label></td>';
                                        }
                                              //$data .= $row1[0];
                                              $data .= '
            			    		                       <td style="float:left; width: 350px;"><select style="width: 231px;" id="scenarquest|'.$row1[3].'|'.$row1[4].'" class="idls object scenarquest">'. GetAlScenQuest($res[scenario_id],$dest).'</select></td>
            			    		                   </tr>'; 
                                          
                			    
                                        }
                                        
                                
                                $data .= '</table>
                                <hr><br>';
            		}
                			    
            		$data .= '</fieldset>
            		</div>';
    			    
                        	$data .= '</div>
                        	    <div id="tab_content_3">';
                        	$query = mysql_query("SELECT 	`question`.id,
                                                    	    `question`.`name`,
                                                    	    `question`.note,
                                                    	    `scenario`.`name`,
                                                    	    `scenario_detail`.id AS sc_det_id,
                                                    	    `scenario_detail`.`sort`
                                        	    FROM    `scenario`
                                        	    JOIN    scenario_detail ON scenario.id = scenario_detail.scenario_id
                                        	    JOIN    question ON scenario_detail.quest_id = question.id
                                        	    WHERE   scenario.id = $res[scenario_id] AND scenario_detail.actived = 1
                                        	    ORDER BY scenario_detail.sort ASC");
                        	
                        	$data .= '<button who="0" id="show_all_scenario" style="margin-bottom: 10px;float: right; margin-top: 15px;">ყველას ჩვენება</button>';
                    
                        	while ($row = mysql_fetch_array($query)) {
                        	
                        	    $last_q = mysql_query(" SELECT question_detail.id,question_detail.quest_id
                                            	        FROM `question_detail`
                                            	        JOIN scenario_detail ON scenario_detail.quest_id = question_detail.quest_id
                                            	        AND scenario_detail.scenario_id = $res[scenario_id]
                                            	        WHERE question_detail.quest_id = $row[0]");
                        	
                        	    $data .= '<div style="margin-top: 15px;" class="quest_body '.$row[5].'" id="'.$row[0].'">
		            <table class="dialog-form-table">
		    		<tr>
						<td style="font-weight:bold;">'.$row[5].'. '. $row[1] .' <img onclick="imnote(\''.$row[5].'\')" style="border: none;padding: 0;margin-left: 8px;margin-top: -7px;cursor: pointer;" src="media/images/icons/kitxva.png" alt="14 ICON" height="24" width="24"></td>
		                </tr><tr style="display:none;" id="imnote_'. $row[5] .'" ><td>'.$row[2].'</td></tr>
		                    ';
                        	
                        	    while ($last_a = mysql_fetch_array($last_q)){
                        	
                        	
                        	
                        	        $query1 = mysql_query(" SELECT CASE 	WHEN question_detail.quest_type_id = 1 THEN CONCAT('<tr><td style=\"width:428px; text-align:left;\"><input next_quest=\"',scenario_destination.destination,'\"  class=\"check_input\" ansver_val=\"',question_detail.answer,'\" style=\"float:left;\" type=\"checkbox\" name=\"checkbox', question_detail.quest_id, '\" id=\"checkbox', question_detail.id, '\" value=\"', question_detail.id, '\"><label for=\"checkbox', question_detail.id, '\" style=\"float:left; padding: 7px;white-space: pre-line;\">', question_detail.answer, '</label></td></tr>')
                                                            	            WHEN question_detail.quest_type_id = 2 THEN CONCAT('<tr><td style=\"width:428px; text-align:left;\"><label style=\"float:left; padding: 7px 0;width: 428px;\" for=\"input|', question_detail.quest_id, '|', question_detail.id, '\">',question_detail.answer,'</label><input next_quest=\"',scenario_destination.destination,'\" value=\"\" class=\"inputtext\"style=\"float:left;\"  type=\"text\" id=\"input|', question_detail.quest_id, '|', question_detail.id, '\" q_id=\"',question_detail.id,'\" /> </td></tr>')
                                                            	            WHEN question_detail.quest_type_id = 4 THEN CONCAT('<tr><td style=\"width:428px; text-align:left;\"><input next_quest=\"',scenario_destination.destination,'\" class=\"radio_input\" ansver_val=\"',question_detail.answer,'\" style=\"float:left;\" type=\"radio\" name=\"radio', question_detail.quest_id, '\" id=\"radio', question_detail.id, '\" value=\"', question_detail.id, '\"><label for=\"radio', question_detail.id, '\" style=\"float:left; padding: 7px;white-space: pre-line;\">', question_detail.answer, '</label></td></tr>')
                                                            	            WHEN question_detail.quest_type_id = 5 THEN CONCAT('<tr><td style=\"width:428px; text-align:left;\"><label style=\"float:left; padding: 7px 0;width: 428px;\" for=\"input|', question_detail.quest_id, '|', question_detail.id, '\">',question_detail.answer,'</label><input next_quest=\"',scenario_destination.destination,'\" value=\"\" class=\"date_input\"  style=\"float:left;\" type=\"text\" id=\"input|', question_detail.quest_id, '|', question_detail.id, '\" q_id=\"',question_detail.id,'\" /> </td></tr>')
                                                            	            WHEN question_detail.quest_type_id = 6 THEN CONCAT('<tr><td style=\"width:428px; text-align:left;\"><label style=\"float:left; padding: 7px 0;width: 428px;\" for=\"input|', question_detail.quest_id, '|', question_detail.id, '\">',question_detail.answer,'</label><input next_quest=\"',scenario_destination.destination,'\" value=\"\" class=\"date_time_input\"  style=\"float:left;\" type=\"text\" id=\"input|', question_detail.quest_id, '|', question_detail.id, '\" q_id=\"',question_detail.id,'\" /> </td></tr>')
                                                            	            WHEN question_detail.quest_type_id = 7 THEN question_detail.answer
                                            	                    END AS `ans`,
                                                    	            question_detail.quest_type_id,
                                                    	            scenario_handbook.`name`,
                                                    	            question_detail.quest_id,
                                                    	            question_detail.id,
                                                    	            scenario_destination.destination
                                            	            FROM question_detail
                                            	            JOIN scenario_detail ON scenario_detail.scenario_id = $res[scenario_id]
                                            	            JOIN scenario_destination ON scenario_detail.id = scenario_destination.scenario_detail_id AND scenario_destination.answer_id = $last_a[0]
                                            	            LEFT JOIN scenario_handbook ON question_detail.answer = scenario_handbook.id
                                            	            WHERE question_detail.id = $last_a[0] AND question_detail.quest_id = $last_a[1] AND scenario_detail.actived = 1
                                            	            ");
                        	
                        	
                        	
                        	        $g =0;
                        	        while ($row1 = mysql_fetch_array($query1)) {
                        	            $q_type = $row1[1];
                        	            if($q_type == 7){
                        	                $data .= '  <tr>
                                                    <td style="width:428px; text-align:left;">
                                                    <label style="float:left; padding: 7px 0;width: 428px;" for="">'.$row1[2].'</label>
                                                    <select class="hand_select" next_quest="'.$row1[5].'" style="float:left;width: 235px;"  id="hand_select|'.$row1[3].'|'.$row1[4].'" >'.gethandbook($row1[0],'').'</select>
                                                    </td>';
                        	            }else{
                        	                $data .= $row1[0];
                        	            }
                        	        }}
                        	
                        	        $data .= '</table>
                    <hr><br></div>';
                        	
                        	}
                        	
                        	$data .= '
	  
	    <div style="margin-top: 15px; display: none;" class="last_quest">
        	<table class="dialog-form-table">
        		<tr>
        			<td style="font-weight:bold;">
        				არ დაგავიწყდეთ სტატუსის შეცვლა და შენახვის ღილაკზე დაკლიკება!
        			</td>
        		</tr>
        	</table>
        	<hr>
        	<br>
        </div>
	  
	    <button id="back_quest" back_id="0" style="float:left;">უკან</button><button id="next_quest" style="float:right;" next_id="0">წინ</button>
                        	
    			                
    			                 </div>
                        	    
                        	    
                        	        			            
                            </div>
    			        ';
			    }
			}
			if($_REQUEST['quest_detail_id'] != '' || $_REQUEST['add_id'] != ''){
			    $data .=  ' <table class="dialog-form-table">  
			                    <tr>
                					<td style="width: 170px;"><label for="quest_id1">კითხვა</label></td>
                					<td>
                						<select style="width: 231px;" id="quest_id1" class="idls object">'. GetQuest($res['quest_id'],$_REQUEST['id']).'</select>
                					</td>
                				</tr>                				
                			</table>
                			<script>$("#name, #cat, #le_cat").prop("disabled", true);</script>';
			}
			$data .=  '
        </fieldset>
    </div>
    ';
	return $data;
}

?>

