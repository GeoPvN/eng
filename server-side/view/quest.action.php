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
$hidden_product_id = $_REQUEST['hidden_product_id'];

switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$page		= GetPage(GetList($quest_id,$quest_detail_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
		$rResult = mysql_query("SELECT 	`question`.`id`,
                        				`question`.`name`,
                        				`question`.`note`
                                FROM 	`question`
                                WHERE 	`question`.`actived` = 1");

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
	    
	    mysql_query("SET @i = 0;");
	    $rResult = mysql_query("SELECT 	`question_detail`.`id`,
	                                    @i := @i+1 AS `order_id`,
                        				IF(LENGTH(answer) < 4 AND answer REGEXP '[0-9]',scenario_handbook.`name`,question_detail.answer) AS `answer`,
                        				`question_type`.`name` AS `quest_type`
                                FROM 	`question_detail`
                                JOIN	`question_type` ON question_detail.quest_type_id = question_type.id
	                            LEFT JOIN	`scenario_handbook` ON question_detail.answer = scenario_handbook.id
                                WHERE 	`question_detail`.`actived` = 1 AND question_detail.quest_id = $quest_id");
	
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
                                  <input type="checkbox" id="callapp_checkbox_de_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                                  <label for="callapp_checkbox_de_'.$aRow[$hidden].'"></label>
                              </div>';
	            }
	        }
	        $data['aaData'][] = $row;
	    }
		    
		break;
	case 'save_quest':
	    if($quest_id == ''){
	       save($user_id, $name, $note);
	    }else{
	       update($quest_id, $name, $note);
	    }

		break;
	case 'save_answer':
	    if($quest_detail_id == ''){
	        save_answer($user_id, $answer, $quest_type_id, $add_id, $hidden_product_id);
	    }else{
	        update_answer($quest_detail_id,  $answer, $quest_type_id, $quest_id, $hidden_product_id);
	    }
		
		break;
	case 'disable':
	    disable($quest_id);

		break;
	case 'disable_detail':
	    disable_det($quest_id);
	
	    break;
	case 'get_product_info':
		    $name 			= $_REQUEST[name];
		    $res 			= GetProductInfo($name);
		    if(!$res){
		        $error = 'პროდუქტი ვერ მოიძებნა!';
		    }else{
		        $data = array(  'genre'	                => $res['genre'],
            		            'category'	     		=> $res['category'],
            		            'description'	 		=> $res['description'],
            		            'price'	        		=> $res['price'],
            		            'id'	    			=> $res['id']);
		    }
		
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

function save($user_id, $name, $note)
{
    global $error;
    $name_cheker = mysql_num_rows(mysql_query("SELECT id FROM quest_1 WHERE `name` = '$name'"));
    if($name_cheker == 0){
    mysql_query("INSERT INTO `question`
                (`user_id`,`name`,`note`)
                VALUES
                ('$user_id','$name','$note')");
    }else{
        $error = 'ესეთი სახელი უკვე არსებობს!';
    }
    return $error;
}

function update($quest_id, $name, $note)
{
    mysql_query("	UPDATE  `question`
                    SET     `name` = '$name',
                            `note` = '$note'
                    WHERE	`id`   = $quest_id");
}

function save_answer($user_id, $answer, $quest_type_id, $quest_id, $hidden_product_id)
{
    global $error;
    if($quest_id==''){
        $quest_id_inc = mysql_fetch_row(mysql_query("SELECT id+1 FROM question ORDER BY id DESC LIMIT 1"));
        $quest_id = (($quest_id_inc[0]=='')?'1':$quest_id_inc[0]);
        $error = $quest_id;
    }
    if($answer == ''){
        $name_checker = "`product_id` = '$hidden_product_id'";
    }else{
        $name_checker = "`answer` = '$answer'";
    }
    $name_cheker = mysql_num_rows(mysql_query("SELECT id FROM question_detail WHERE $name_checker AND `quest_id` = '$quest_id' AND actived = 1"));
    if($name_cheker == 0){
    mysql_query("INSERT INTO `question_detail`
                (`user_id`,`answer`,`quest_id`,`quest_type_id`)
                VALUES
                ('$user_id','$answer','$quest_id','$quest_type_id')");
    }else{
        $error = 'ესეთი სახელი უკვე არსებობს!';
    }
    return $error;
}

function update_answer($quest_detail_id,  $answer, $quest_type_id, $quest_id, $hidden_product_id)
{
    mysql_query("	UPDATE  `question_detail`
                    SET     `answer`        = '$answer',
                            `quest_id`      = '$quest_id',
                            `quest_type_id` = '$quest_type_id'
                    WHERE	`id`            =  $quest_detail_id");
}

function disable($quest_id)
{
    mysql_query("	UPDATE  `question`
                    SET     `actived` = 0
                    WHERE	`id`      = $quest_id");
}

function disable_det($quest_detail_id)
{
    mysql_query("	UPDATE  `question_detail`
                    SET     `actived` = 0
                    WHERE	`id`      = $quest_detail_id");
}

function GetList($quest_id,$quest_detail_id)
{
    if($quest_id != ''){
        $checker = "and question.`id` = $quest_id";
    }
    if($quest_detail_id != ''){
        $checker = "and question_detail.`id` = $quest_id";
    }
	$res = mysql_fetch_assoc(mysql_query(
	    "	SELECT 	    `question`.`id` AS `quest_id`,
                                						`question`.`name`,
                                						`question`.`note`,
                                						`question_detail`.`id` AS `quest_detail_id`,
                                						`question_detail`.`answer`,
                                						`question_detail`.`quest_type_id`
                                            FROM 	    `question`
                                            LEFT JOIN	`question_detail` ON question.id = question_detail.quest_id
                                            LEFT JOIN	`question_type` ON question_detail.quest_type_id = question_type.id
                                            WHERE 	    `question`.`actived` = 1 $checker"));

	return $res;
}

function GetQuestType($quset_type_id)
{
    if($_REQUEST[add_id] != ''){
        $ch_q = $_REQUEST[add_id];
    }else{
        $ch_q = $_REQUEST[quest_detail_id];
    }
    $rr  = mysql_fetch_array(mysql_query("  SELECT quest_type_id
                                            FROM `question`
                                            JOIN question_detail ON question.id = question_detail.quest_id
                                            WHERE question.id = $ch_q
                                            ORDER BY question_detail.id ASC
                                            LIMIT 1"));
    if($rr[0] == ''){
        $type_where = '';
    }else{
        $type_where = "AND question_type.id = $rr[0]";
    }
    
    $req = mysql_query("	SELECT 	`question_type`.`id`,
                                    `question_type`.`name`
                            FROM 	`question_type`
                            WHERE 	`question_type`.`actived` = 1 $type_where" );

    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $quset_type_id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }

    return $data;
}

function GetHandBook($id){
    $req = mysql_query("	SELECT 	`scenario_handbook`.`id`,
                                    `scenario_handbook`.`name`
                            FROM 	`scenario_handbook`
                            WHERE 	`scenario_handbook`.`actived` = 1" );
    
    $data .= '<option value="0" selected="selected">----</option>';
    while( $res = mysql_fetch_assoc($req)){
        if($res['id'] == $id){
            $data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
        } else {
            $data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
        }
    }
    
    return $data;
}

function GetPage($res = '')
{	
    $data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>
    
	    	<table class="dialog-form-table" style="margin:0 0 10px 0;">
				<tr>
					<td style="width: 170px;"><label for="name">სახელი</label></td>
					<td>
						<textarea id="name" style="margin: 0px; width: 504px; resize:vertical;" >' . $res['name'] . '</textarea>
					</td>
				</tr>
    
			</table>';
			
			    if($_REQUEST['dialog_check'] == 0){
    			    $data .=  ' 
    			                <div id="button_area">
                    			    <button id="add_button_detail">დამატება</button>
                    			    <button id="delete_button_detail">წაშლა</button>
                			    </div>
                			    <table class="display" id="table_quest" style="width: 100%; background: #FFF;">
                    			    <thead>
                        			    <tr id="datatable_header">
                            			    <th>ID</th>
    			                            <th style="width: 30px;">№</th>
                            			    <th style="width: 100%;">დასახელება</th>
                            			    <th style="width: 100%;">ტიპი</th>
                            			    <th class="check">&nbsp;</th>
                        			    </tr>
                    			    </thead>
                    			    <thead>
                        			    <tr class="search_header">
                            			    <th class="colum_hidden">
    			                                 <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            			    </th>                            			    
    			                            <th>
    			                                 <input style="width: 20px;" type="text" name="search_category" value="ფილტრი" class="search_init" />
                            			    </th>
    			                            <th>
                            			         <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            			    </th>
                            			    <th>
                            			         <input type="text" name="search_category" value="ფილტრი" class="search_init" />
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
    			                ';
			    }
			
			if($_REQUEST['dialog_check'] == 1){
			    $data .=  ' <script>
			        $("#quest_type_id").chosen({ search_contains: true });
			         $("#add-edit-form-answer, .add-edit-form-answer-class").css("overflow","visible");</script><table class="dialog-form-table">  
			                    <tr>
                					<td style="width: 170px;"><label for="quest_type_id">ტიპი</label></td>
                					<td>
                						<select style="width: 231px;" id="quest_type_id" class="idls object">'. GetQuestType($res['quest_type_id']).'</select>
                					</td>
                				</tr>              				
                                <tr id="show_answer">
                					<td style="width: 170px;"><label for="answer" id="qlabel">პასუხი</label></td>
                					<td>
                						<textarea id="answer" style="width: 97.5%; height:40px; resize: vertical;">' . $res['answer'] . '</textarea>
                					</td>
                				</tr>
                				<tr style="display:none;" id="show_handbook">
                					<td style="width: 170px;"><label for="handbook" id="qlabel">ცნობარი</label></td>
                					<td>
                						<select id="handbook" style="width: 230px;">'.GetHandBook($res['answer']).'</select>
                					</td>
                				</tr>              				
                			</table>
                			<script type="text/javascript">
                						    $("#add-edit-form-answer #name").val($("#add-edit-form #name").val());
                						    $("#add-edit-form-answer #name").prop("disabled", true);
                						    $("#add-edit-form-answer #name").css("width","226");
                						    if($("#quest_type_id").val()==7){
                                		        $("#show_handbook").css("display","table-row");
                                		        $("#show_answer").css("display","none");
                                		    }else{
                                		    	$("#show_answer").css("display","table-row");
                                		    	$("#show_handbook").css("display","none");
                                		    }
                						    </script>';
			}else{
			    $data .=  '<table class="dialog-form-table">   
			                    <tr>
			                         <td><label for="quest_type_id">მინიშნება</label></td>
			                    </tr>             				
                                <tr>			                         
			                         <td><textarea  style="width: 99.5%; height:60px; resize: vertical;" id="note" name="note" cols="300" >'.$res['note'].'</textarea></td>
                				</tr>
                			</table>';
			}
			$quest_id_inc = mysql_fetch_row(mysql_query("SELECT id+1 FROM question ORDER BY id DESC LIMIT 1"));
			$data .=  '<!-- ID -->
			<input type="hidden" id="quest_id" value="' . $res['quest_id'] . '" />
			<input type="hidden" id="quest_detail_id" value="' . $res['quest_detail_id'] . '" />
			<input type="hidden" id="add_id" value="' . $_REQUEST['add_id'] . '" />
			<input type="hidden" id="delete_id" value="' . $quest_id_inc[0] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

function GetProductInfo($name)
{
    $res = mysql_query("SELECT  genre.`name` AS `genre`,
                                department.`name` AS `category`,
                                production.description,
                                production.price,
                                production.id
                        FROM    production
                        JOIN 	genre ON production.genre_id = genre.id
                        JOIN 	department ON production.production_category_id = department.id
                        WHERE   production.`name` = '$name' AND production.actived = 1
        ");

    if (mysql_num_rows($res) == 0){
        return false;
    }

    $row = mysql_fetch_assoc($res);
    return $row;
}
?>

