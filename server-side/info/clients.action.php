<?php
// MySQL Connect Link
require_once('../../includes/classes/core.php');

// Main Strings
$action                     = $_REQUEST['act'];
$error                      = '';
$data                       = '';


// Incomming Call Dialog Strings
//კლიენტი//
$hidden_id       = $_REQUEST['id'];
$identity_code   = mysql_real_escape_string($_REQUEST['identity_code']);
$client_name     = mysql_real_escape_string($_REQUEST['client_name']);
$jurid_address   = mysql_real_escape_string($_REQUEST['jurid_address']);
$fact_address    = mysql_real_escape_string($_REQUEST['fact_address']);

//კონტრაქტი//
$contract_number       	= mysql_real_escape_string($_REQUEST['contract_number']);
$add_date   			= mysql_real_escape_string($_REQUEST['add_date']);
$contract_start_date    = mysql_real_escape_string($_REQUEST['contract_start_date']);
$contract_end_date   	= mysql_real_escape_string($_REQUEST['contract_end_date']);
$contract_price    		= mysql_real_escape_string($_REQUEST['contract_price']);
$angarish_period       	= mysql_real_escape_string($_REQUEST['angarish_period']);
$angarish_period1   	= mysql_real_escape_string($_REQUEST['angarish_period1']);


//კლიენტი//
$invois    			= mysql_real_escape_string($_REQUEST['invois']);
$migeba_chabareba   = mysql_real_escape_string($_REQUEST['migeba_chabareba']);
$angarishfaqtura    = mysql_real_escape_string($_REQUEST['angarishfaqtura']);


switch ($action) {
	case 'get_add_page':
		$page		= GetPage('',increment($hidden_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$page		= GetPage(Getclient($hidden_id));
		$data		= array('page'	=> $page);

		break;
	case 'disable':
		$hidden_id        = $_REQUEST['id'];
		mysql_query("	UPDATE  `client`
								SET
								`actived` = 0
						WHERE `id`='$hidden_id'
		");
	
		break;
	case 'get_list':
        $count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
	  	$rResult = mysql_query("SELECT  `id`,
	  									`id`,
	  									`name`,
										identity_code,
										juridical_address,
										physical_address
								FROM 	client
								WHERE 	actived=1");
	  
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
                                  <label style="margin-top: 2px;" for="callapp_checkbox_'.$aRow[$hidden].'"></label>
                              </div>';
				}
			}
			$data['aaData'][] = $row;
		}
	
	    break;
    case 'table_week':
        $count = 		$_REQUEST['count'];
        $hidden = 		$_REQUEST['hidden'];
        $rResult = mysql_query("SELECT  week_day_graphic.id,
                        				week_day_graphic.start_time,
                        				week_day_graphic.end_time,
                        				week_day_graphic.ext_number,
                        				week_day_graphic.type,				
                        				(SELECT  GROUP_CONCAT(spoken_lang.`name`)
                        				FROM `week_day_graphic` AS te1
                        				LEFT JOIN week_day_lang ON te1.id = week_day_lang.week_day_graphic_id
                        				LEFT JOIN spoken_lang ON week_day_lang.spoken_lang_id = spoken_lang.id
                        				WHERE te1.id = week_day_graphic.id) AS `lang`,
                        				(SELECT  GROUP_CONCAT(information_source.`name`)
                        				FROM `week_day_graphic` AS te2
                        				LEFT JOIN week_day_info_sorce ON te2.id = week_day_info_sorce.week_day_graphic_id
                        				LEFT JOIN information_source ON week_day_info_sorce.information_source_id = information_source.id
                        				WHERE te2.id = week_day_graphic.id) AS `info_sorce`
                                FROM `week_day_graphic`
                                WHERE week_day_graphic.project_id = $_REQUEST[project_id] AND week_day_graphic.week_day_id = $_REQUEST[wday] AND week_day_graphic.actived = 1");
    
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
                          <input type="checkbox" id="callapp_checkbox_break_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                          <label style="margin-top: 2px;" for="callapp_checkbox_break_'.$aRow[$hidden].'"></label>
                      </div>';
                }
            }
            $data['aaData'][] = $row;
        }
        break;
    case 'get_list_import':
        $count = 		$_REQUEST['count'];
        $hidden = 		$_REQUEST['hidden'];
        
        if($_REQUEST['cp'] == 1){
            $rResult = mysql_query("SELECT 	phone_base_detail.`id`,
                                            phone_base_detail.`client_name`,
                                            phone_base_detail.`note`,
                                            phone_base_detail.`phone1`,
                                            phone_base_detail.`phone2`,
                                            phone_base_detail.`mail1`,
                                            phone_base_detail.`mail2`,
                                            phone_base_detail.`address1`,
                                            phone_base_detail.`address2`,
                                            phone_base_detail.`info1`
                                    FROM 	phone_base
                                    JOIN    phone_base_detail ON phone_base_detail.phone_base_id = phone_base.id AND phone_base_detail.`actived` = 1
                                    WHERE   phone_base.`actived` = 1");
        }else{
            $rResult = mysql_query("SELECT 	phone_base_detail.`id`,
                            				phone_base_detail.`firstname`,
                            				phone_base_detail.`lastname`,
                            				phone_base_detail.`pid`,
                            				phone_base_detail.`phone1`,
                            				phone_base_detail.`phone2`
                                    FROM 	`phone_base`
                                    JOIN phone_base_detail ON phone_base_detail.phone_base_id = phone_base.id AND phone_base_detail.`actived` = 1
                                    WHERE   phone_base.`actived` = 1 AND (ISNULL(phone_base_detail.`client_name`) OR phone_base_detail.`client_name` = '')");
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
                if($i == ($count - 1)){
                    $row[] = '<div class="callapp_checkbox">
                              <input type="checkbox" id="callapp_import_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                              <label style="margin-top: 2px;" for="callapp_import_checkbox_'.$aRow[$hidden].'"></label>
                          </div>';
                }
            }
            $data['aaData'][] = $row;
        }
    
        break;
    case 'get_list_import_actived':
        $count = 		$_REQUEST['count'];
        $hidden = 		$_REQUEST['hidden'];
        if($_REQUEST['cp']==1){
            $rResult = mysql_query("SELECT 	outgoing_campaign_detail.`id`,
                        				    phone_base_detail.`client_name`,
                                            phone_base_detail.`note`,
                                            phone_base_detail.`phone1`,
                                            phone_base_detail.`phone2`,
                                            phone_base_detail.`mail1`,
                                            phone_base_detail.`mail2`,
                                            phone_base_detail.`address1`,
                                            phone_base_detail.`address2`,
                                            phone_base_detail.`info1`
                                    FROM `outgoing_campaign`
                                    JOIN outgoing_campaign_detail ON outgoing_campaign.id = outgoing_campaign_detail.outgoing_campaign_id
                                    JOIN phone_base_detail ON outgoing_campaign_detail.phone_base_detail_id = phone_base_detail.id
                                    WHERE project_id = $_REQUEST[project_id] AND outgoing_campaign_detail.actived = 1");
        }else{
            $rResult = mysql_query("SELECT 	outgoing_campaign_detail.`id`,
                                            phone_base_detail.`firstname`,
                                            phone_base_detail.`lastname`,
                                            phone_base_detail.`pid`,
                                            phone_base_detail.`phone1`,
                                            phone_base_detail.`phone2`,
                                            phone_base_detail.`note`
                                    FROM `outgoing_campaign`
                                    JOIN outgoing_campaign_detail ON outgoing_campaign.id = outgoing_campaign_detail.outgoing_campaign_id
                                    JOIN phone_base_detail ON outgoing_campaign_detail.phone_base_detail_id = phone_base_detail.id
                                    WHERE project_id = $_REQUEST[project_id] AND outgoing_campaign_detail.actived = 1 AND (ISNULL(phone_base_detail.`client_name`) OR phone_base_detail.`client_name` = '')");
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
                if($i == ($count - 1)){
                    $row[] = '<div class="callapp_checkbox">
                          <input type="checkbox" id="callapp_actived_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                          <label style="margin-top: 2px;" for="callapp_actived_checkbox_'.$aRow[$hidden].'"></label>
                      </div>';
                }
            }
            $data['aaData'][] = $row;
        }
    
        break;
    case 'get_list_person':
    	$count = 		$_REQUEST['count'];
    	$hidden = 		$_REQUEST['hidden'];
    	$client_id = 	$_REQUEST['client_id'];
    	$rResult = mysql_query("SELECT  id,
    									`name`,
										lastname,
										position,
										phone,
										email
								FROM `client_person`
								WHERE client_id='$client_id' and actived=1");
    	 
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
                                  <input type="checkbox" id="callapp_person_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                                  <label style="margin-top: 2px;" for="callapp_person_checkbox_'.$aRow[$hidden].'"></label>
                              </div>';
    			}
    		}
    		$data['aaData'][] = $row;
    	}
    
    	break;
    	case 'get_list_project':
    		$count = 		$_REQUEST['count'];
    		$hidden = 		$_REQUEST['hidden'];
    		$client_id = 	$_REQUEST['client_id'];
    		$rResult = mysql_query("SELECT 	project.`id`,
											project.`name`,
											call_type.`name`,
											create_date,
											(SELECT GROUP_CONCAT(project_number.number) AS `number`
											 FROM project_number
											 WHERE project_number.project_id=project.id
											) AS `number`
									
									FROM `project`
									LEFT JOIN call_type ON project.type_id=call_type.id
									WHERE actived=1 AND client_id='$client_id'");
    	
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
	                                  <input type="checkbox" id="callapp_project_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
	                                  <label style="margin-top: 2px;" for="callapp_project_checkbox_'.$aRow[$hidden].'"></label>
	                              </div>';
    				}
    			}
    			$data['aaData'][] = $row;
    		}
    	
    		break;
    	case 'get_list_number':
    			$count  = 		$_REQUEST['count'];
    			$hidden = 		$_REQUEST['hidden'];
    			
    			$project_id =  $_REQUEST['project_id'];
    			$rResult   = mysql_query("SELECT project_number.id,
    											 project_number.number,
												 queue.`name`,
												 queue.number,
												 ''
											FROM project_number
											LEFT JOIN queue ON queue.id=project_number.queue_id
											WHERE project_number.actived=1 AND project_number.project_id='$project_id'");
    			 
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
		                                  <input type="checkbox" id="callapp_number_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
		                                  <label style="margin-top: 2px;" for="callapp_number_checkbox_'.$aRow[$hidden].'"></label>
		                              </div>';
    					}
    				}
    				$data['aaData'][] = $row;
    			}
    			 
    			break;
    case 'save_client':
    	$hidden_id       = $_REQUEST['id'];
    	
    	if($hidden_id==''){
    		Addclient($identity_code, $client_name, $jurid_address, $fact_address, $contract_number, $add_date, $contract_start_date, $contract_end_date, $contract_price, $angarish_period, $angarish_period1, $invois, $migeba_chabareba, $angarishfaqtura);
    	}else{
    		Saveclient($hidden_id, $identity_code, $client_name, $jurid_address, $fact_address, $contract_number, $add_date, $contract_start_date, $contract_end_date, $contract_price, $angarish_period, $angarish_period1, $invois, $migeba_chabareba, $angarishfaqtura);
    	}
	    
	    break;
    case 'view_img':
        $page		= GetIMG($_REQUEST[id]);
		$data		= array('page'	=> $page);
         
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
function Addclient($identity_code, $client_name, $jurid_address, $fact_address, $contract_number, $add_date, $contract_start_date, $contract_end_date, $contract_price, $angarish_period, $angarish_period1, $invois, $migeba_chabareba, $angarishfaqtura){
	$user = $_SESSION['USERID'];
	
	mysql_query("INSERT INTO `client` 
				(`user_id`, `name`, `identity_code`, `juridical_address`, `physical_address`, `image_id`, `actived`) 
		VALUES 
				('$user', '$client_name', '$identity_code', '$jurid_address', '$fact_address', '', '1')");
		
	$client_id = mysql_insert_id();
	
	if ($contract_number !='') {
		mysql_query("INSERT INTO `client_contract` 
								(`user_id`, `client_id`, `number`, `create_date`, `validity_period_start`, `validity_period_end`, `price`, `reporting_period_type`, `reporting_period_count`, `file_id`, `actived`) 
						VALUES 
								('$user', '$client_id', '$contract_number', '$add_date', '$contract_start_date', '$contract_end_date', '$angarish_period1', '', '$angarish_period', '', '1')");
	}
	if ($invois==1 || $migeba_chabareba==1 || $angarishfaqtura==1) {
		mysql_query("INSERT INTO `client_documents` 
							(`user_id`, `client_id`, `invoice`, `taking_over_act`, `report_invoice`, `actived`) 
						VALUES 
							('$user', '$client_id', '$invois', '$migeba_chabareba', '$angarishfaqtura', '1')");
	}
}
function Saveclient($hidden_id, $identity_code, $client_name, $jurid_address, $fact_address, $contract_number, $add_date, $contract_start_date, $contract_end_date, $contract_price, $angarish_period, $angarish_period1, $invois, $migeba_chabareba, $angarishfaqtura){
	$user = $_SESSION['USERID'];
	
	mysql_query("	UPDATE 	`client`  
						SET `user_id`			='$user', 
							`name`				='$client_name', 
							`identity_code`		='$identity_code', 
							`juridical_address`	='$jurid_address', 
							`physical_address`	='$fact_address', 
							`image_id`			='0', 
							`actived`			='1' 
					WHERE `id`='$hidden_id'");
	
	$res=mysql_query("	SELECT *
						FROM client_contract
						WHERE client_id='$hidden_id'
						LIMIT 1
					 ");
	$check=mysql_num_rows($res);
	if ($check==1) {
		mysql_query("UPDATE `client_contract` 
						SET 
							`user_id`='$user', 
							`number`='$contract_number', 
							`create_date`='$add_date', 
							`validity_period_start`='$contract_start_date', 
							`validity_period_end`='$contract_end_date', 
							`price`='$contract_price', 
							`reporting_period_type`='$angarish_period1', 
							`reporting_period_count`='$angarish_period', 
							`file_id`='0' 
					  WHERE `client_id`='$hidden_id'");
	}else {
		mysql_query("INSERT INTO `client_contract`
							(`user_id`, `client_id`, `number`, `create_date`, `validity_period_start`, `validity_period_end`, `price`, `reporting_period_type`, `reporting_period_count`, `file_id`, `actived`)
						VALUES
							('$user', '$hidden_id', '$contract_number', '$add_date', '$contract_start_date', '$contract_end_date', '$contract_price', '$angarish_period1', '$angarish_period', '', '1')");
	}
	
	$res1=mysql_query("	SELECT *
						FROM client_documents
						WHERE client_id='$hidden_id'
						LIMIT 1
			");
	$check1=mysql_num_rows($res1);
	if ($check1==1) {
		mysql_query("UPDATE `client_documents` 
								SET 
									`user_id`='$user', 
									`client_id`='$hidden_id', 
									`invoice`='$invois', 
									`taking_over_act`='$migeba_chabareba', 
									`report_invoice`='$angarishfaqtura'
									 
						WHERE `client_id`='$hidden_id'");
	}else {
		mysql_query("INSERT INTO `client_documents` 
							(`user_id`, `client_id`, `invoice`, `taking_over_act`, `report_invoice`, `actived`) 
						VALUES 
							('$user', '$hidden_id', '$invois', '$migeba_chabareba', '$angarishfaqtura', '1')");
	}
		
}


function Get_reporting_period_type($type){
	$data = '';
	$req = mysql_query("SELECT id, `name`
						FROM `reporting_period_type`
						");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){

		if($res['id'] == $type){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		}else{
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}
	return $data;
}
function Get_reporting_period_count($count){
	$data = '';
	$req = mysql_query("SELECT id,`name` 
						FROM `reporting_period_count`");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){

		if($res['id'] == $count){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		}else{
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}
	return $data;
}
function Getclient($hidden_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  client.`id`,
													client.`name`,
													client.identity_code,
													client.juridical_address,
													client.physical_address,
													client_contract.create_date,
													client_contract.id AS contract_id,
													client_contract.number,
													client_contract.price,
													client_contract.reporting_period_count,
													client_contract.reporting_period_type,
													client_contract.validity_period_end,
													client_contract.validity_period_start,
													client_documents.invoice,
													client_documents.report_invoice,
													client_documents.taking_over_act,
													file.rand_name AS `image`,
	                                                file.id AS `image_id`
											FROM 	client
											LEFT JOIN client_contract ON client.id=client_contract.client_id
											LEFT JOIN client_documents ON client_documents.client_id=client.id
	                                        LEFT JOIN file ON client.id=file.client_id AND file.actived = 1
											WHERE 	client.id='$hidden_id'"));
	return $res;
}

function GetPage($res,$increment){
	
	
	if ($res[id]=='') {
		$hid_id=increment(client);
		$hid_contract_id=increment(client_contract);
	}else{
		$hid_id=$res[id];
		$hid_contract_id=$res[id];
	}
	
	$image = $res['image'];
	if(empty($image)){
		$image = '0.jpg';
	}else{
	    $disable_img = 'disabled';
	}
	if ($res[invoice]==1) {
		$inv_check="checked";
	}else{
		$inv_check="";
	}
	if ($res[report_invoice]==1) {
		$report_check="checked";
	}else {
		$report_check="";
	}
	if ($res[taking_over_act]==1) {
		$taking_check="checked";
	}else {
		$taking_check="";
	}
	$data  .= '
	<div id="tabs1" style="width: auto; height: 557px;">
		<ul>
		<li><a href="#tab-0">მთავარი</a></li>
		<li><a href="#tab-1">პროექტი</a></li>
		</ul>
	<div id="tab-0">
	<div id="dialog-form">
	    <div style="width: 609px;  float: left;">
	    <fieldset>
	       <legend>ძირითადი ინფორმაცია</legend>
			<table>
			<tr>
			<td>
	       <table>
	    		<tr>
					<td id="img_colum">
						<img style="margin-left: 5px;" width="105" height="105" id="upload_img" src="media/uploads/file/'.$image.'" />
					</td>
				</tr>
				<tr>
					<td id="act">
						<span>
							<a href="#" onclick="view_image('.$res[image_id].')" class="complate">View</a> | <a href="#" id="delete_image" image_id="'.$res[image_id].'" class="delete">Delete</a>
						</span>
					</td>
				</tr>
				</tr>
					<td>
						<div style="margin-top:10px; width: 127px; margin-left: -5px;" class="file-uploader">
							<input id="choose_file" type="file" name="choose_file" class="input" style="display: none;">
							<button id="choose_button'.$disable_img.'" class="center" >აირჩიეთ ფაილი</button>
						</div>
					</td>
				</tr>
			</table>
			</td>
			<td>
	       <table class="dialog-form-table">
	           <tr>
	               <td colspan="2"><label for="incomming_cat_1">საიდენტიფიკაციო კოდი</label></td>
	           </tr>
	           <tr>
	                <td>
						<input type="text" id="identity_code" style="width: 300px;" value="'.$res['identity_code'].'"/>
					</td>
				    <td>
						<button style="float:right;" id="client_check" style="width: 60px;">შეამოწმე</button>
					</td>
				</tr>
	           <tr>
	               <td colspan="2"><label for="incomming_cat_1_1">დასახელება</label></td>
	           </tr>
	           <tr>
	               <td colspan="2"><input id="client_name" style="resize: vertical;width: 415px;" value="'.$res['name'].'"/></td>
	           </tr>
	           <tr>
	               <td colspan="2"><label for="incomming_cat_1_1_1">იურიდიული მისამართი</label></td>
    	       </tr>
	           <tr>
	               <td colspan="2"><input id="jurid_address" type="text" style="width: 415px;" value="'.$res['juridical_address'].'"/></td>
    	       </tr>
	       		<tr>
	               <td colspan="2"><label for="incomming_comment">ფაქტიური მისამართი</label></td>
	           </tr>
	           <tr>
	               <td colspan="2"><input id="fact_address" style="resize: vertical;width: 415px;" value="'.$res['physical_address'].'"/></td>
	           </tr>
	       </table>
			 </td> 
			</tr>
			
			</table>
	                   </fieldset>
	                   <fieldset>
	                   <legend>საკონტაქტო პირები</legend>
	    	<div class="" style="width:587px;">           
	            <div id="button_area">
                    <button id="add_client">დამატება</button>
					<button id="delete_client">წაშლა</button>
                </div>
				<table class="display" id="table_client" style="width: 100%;">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 50%;">სახელი</th>
                            <th style="width: 50%;">გვარი</th>
                            <th style="width: 50%;">თანამდებობა</th>
                            <th style="width: 50%;">მობილური</th>
							<th style="width: 50%;">ელ ფოსტა</th>
							<th style="width: 25px;" class="check">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                        	   <input type="text" name="search_id" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                            	<input type="text" name="search_number" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
							<th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
	               			<th>
				            	<div class="callapp_checkbox">
				                    <input type="checkbox" id="check-all-client" name="check-all" />
				                    <label style="margin-top: 3px;" for="check-all-client"></label>
				                </div>
				            </th>
                        </tr>
                    </thead>
                </table>
	            </div>
	                  
         </fieldset>
	                   </div>
	    
	    
        <div id="side_menu" style="float: left;height: 495px;width: 80px;margin-left: 10px; background: #272727; color: #FFF;margin-top: 6px;">
	       <spam class="info" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'info\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/info.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">კონტრაქტი</div></spam>
	       <spam class="task" style="display: block;padding: 10px 5px;  cursor: pointer;" onclick="show_right_side(\'task\')"><img style="padding-left: 22px;padding-bottom: 5px;" src="media/images/icons/task.png" alt="24 ICON" height="24" width="24"><div style="text-align: center;">დოკუმენტი</div></spam>
	   
	    </div>
	    
	    <div style="width: 445px;float: left;margin-left: 10px;" id="right_side">
            <fieldset style="display:none;" id="info">
                <legend>კონტრაქტი</legend>
	            <span style="margin-right: 5px; margin-top: 50px;" class="hide_said_menu">x</span>
                <div id="pers">
	               	<table class="margin_top_10">
                            <tr>
                                <td style="width: 230px;"><label for="client_person_phone1">ხელშეკრულების ნომერი</label></td>
        	                    <td><label for="client_person_phone2">გაფორმების თარიღი</label></td>
                            </tr>
    	                    <tr>
                                <td><input style="margin-top: 10px;" id="contract_number" type="text" value="'.$res[number].'"></td>
        	                    <td><input style="margin-top: 10px;" id="add_date" type="text" value="'.$res[create_date].'"></td>
                            </tr>
    	                    <tr>
        	                    <td colspan="2"><label style="margin-left: 135px; margin-top: 20px;" for="client_person_mail2">მოქმედების პერიოდი</label></td>
                            </tr>
    	                    <tr>
                                <td><input style="margin-top: 10px;" id="contract_start_date" type="text" value="'.$res[validity_period_start].'">
									
								</td>
        	                    <td><input style="margin-top: 10px;" id="contract_end_date" type="text" value="'.$res[validity_period_end].'"></td>
                            </tr>
	                        <tr>
                                <td><label style="margin-top: 20px;" for="client_person_addres2">ხელშეკრულების ღირებულება</label></td>
        	                    <td><label style=" margin-top: 20px;" for="client_person_phone2">საანგარიშო პერიოდი</label></td>
                            </tr>
    	                    <tr>
									<td>
										<td>
											<input style="margin-top: 10px; width: 129px; margin-left: -231px;" id="contract_price" type="text" value="'.$res[price].'">
										</td>
										<td>
											<label style="margin-left: -292px; margin-top: 16px;">-ლარი</label>
										</td>
									</td>
									<td>
										<td>
											<select style="margin-top: 10px; width: 50px; margin-left: -200px;"  id="angarish_period">'. Get_reporting_period_count($res[reporting_period_count]).'</select>
										</td>
		        	                    <td>
											<select style="margin-top: 10px; width: 106px; margin-left: -133px;"  id="angarish_period1">'. Get_reporting_period_type($res[reporting_period_type]).'</select>
										</td>
									</td>
							</tr>
							<tr>
								<td colspan="2">
									'.show_file($res).'
								</td>
							</tr>				
                        </table>
				 </div>
				
			</fieldset>
    	    <fieldset style="display:none; width: 436px;" id="task">
                <legend>დოკუმენტი</legend>
	            <span style="margin-right: 5px; margin-top: 50px;" class="hide_said_menu">x</span>
	            <table>
	               <tr>
						<td><input type="checkbox" id="invois" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="1" style="display: inline; margin-left: 20px; margin-top: -5px; width: 16px;" '.$inv_check.'/></td>
	                    <td><label>ინვოისი</label></td>
	               </tr>
				   <tr>
						<td><input type="checkbox" id="migeba_chabareba" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="1" style="display: inline; margin-left: 20px; margin-top: 14px; width: 16px;" '.$taking_check.'/></td>
	                    <td><label style="margin-top: 20px;">მიღება-ჩაბარების აქტი</label></td>
	               </tr>
			       <tr>
						<td><input type="checkbox" id="angarishfaqtura" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="1" style="display: inline; margin-left: 20px; margin-top: 14px; width: 16px;" '.$report_check.'/></td>
	                    <td><label style="margin-top: 20px;">ანგარიშ-ფაქტურა</label></td>
	               </tr>
	            </table>
            </fieldset>
        </div>
	</div>
	</div>
	<div id="tab-1">
	
	<div class="margin_top_10">           
	            <div id="button_area">
                    <button id="add_project">დამატება</button>
					<button id="delete_project">წაშლა</button>
                </div>
				<table class="display" id="table_project" style="width: 100%;">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 50%;">დასახელება</th>
                            <th style="width: 50%;">ტიპი</th>
                            <th style="width: 50%;">შექმნის თარიღი</th>
                            <th style="width: 50%;">ნომრები</th>
							<th style="width: 25px;" class="check">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                        	   <input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 100%;"/>
                            </th>
                            <th>
                            	<input type="text" name="search_number" value="ფილტრი" class="search_init" style="width: 100%;"/>
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100%;"/>
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100%;"/>
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100%;"/>
                            </th>
							<th>
				            	<div class="callapp_checkbox">
				                    <input type="checkbox" id="check-all-project" name="check-all" />
				                    <label style="margin-top: 3px;" for="check-all-project"></label>
				                </div>
				            </th>
                        </tr>
                    </thead>
                </table>
	        </div>
	</div>
	</div>
	
	<input type="hidden" value="'.$res[id].'" id="hidden_id">
	<input type="hidden" value="'.$hid_id.'" id="hidden_client_id">
	<input type="hidden" value="'.$hid_contract_id.'" id="hidden_clientcontract_id">';

	return $data;
}

function show_file($res){
	$file_incomming = mysql_query(" SELECT `name`,
											`rand_name`,
											`file_date`,
											`id`
									FROM   `file`
									WHERE  `client_contract_id` = $res[contract_id] AND `actived` = 1");
	while ($file_res_incomming = mysql_fetch_assoc($file_incomming)) {
		$str_file_contract .= '<div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 112px;float:left;height: 25px;">'.$file_res_incomming[file_date].'</div>
                            	<div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 110px;float:left;height: 25px;">'.$file_res_incomming[name].'</div>
                            	<div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;cursor: pointer;width: 110px;float:left;height: 25px;" onclick="download_file(\''.$file_res_incomming[rand_name].'\',\''.$file_res_incomming[name].'\')">ჩამოტვირთვა</div>
                            	<div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;cursor: pointer;width: 45px;float:left;height: 25px;" onclick="delete_file(\''.$file_res_incomming[id].'\')">წაშლა</div>';
	}
	$data = '<div style="margin-top: 45px;">
                    <div style="width: 425px; border:1px solid #CCC;float: left;">
    	                   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 112px;float:left;">თარიღი</div>
                    	   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 110px;float:left;">დასახელება</div>
                    	   <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 110px;float:left;">ჩამოტვირთვა</div>
                           <div style="border: 1px solid #CCC;padding: 5px;text-align: center;vertical-align: middle;width: 45px;float:left;">წაშლა</div>
    	                   <div style="border: 1px solid #CCC;text-align: center;vertical-align: middle;float: left;width: 423px;"><button id="upload_file" style="cursor: pointer;background: none;border: none;width: 100%;height: 25px;padding: 0;margin: 0;" class="ui-button-text">აირჩიეთ ფაილი</button><input style="display:none;" type="file" name="file_name" id="file_name"></div>
                           <div id="paste_files">
                           '.$str_file_contract.'
                           </div>
            	    </div>
	            </div>';
	return $data;
}

function GetIMG($id){
    $res = mysql_fetch_array(mysql_query("SELECT rand_name FROM `file` WHERE id = $id"));
    if (empty($res[0])) {
        $image = '0.jpg';
    }else{
        $image = $res[0];
    }
    $data = '<div id="dialog-form">
	           <fieldset>
                <img style="margin: auto;display: block;" width="350" height="350"  src="media/uploads/file/'.$image.'">
               </fieldset>
             </div>
            ';
    
    return $data;
}

function increment($table){

    $result   		= mysql_query("SHOW TABLE STATUS LIKE '$table'");
    $row   			= mysql_fetch_array($result);
    $increment   	= $row['Auto_increment'];
    $next_increment = $increment+1;
    mysql_query("ALTER TABLE '$table' AUTO_INCREMENT=$next_increment");

    return $increment;
}

?>