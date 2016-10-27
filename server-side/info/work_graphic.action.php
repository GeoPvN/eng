<?php

require_once ('../../includes/classes/core.php');

$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';
switch ($action) {
    
	case 'get_list' :
		$count 		= $_REQUEST['count'];
		$hidden 	= $_REQUEST['hidden'];
	  	$rResult 	= mysql_query(" SELECT  `id`,
                                	  	    `start`,
                                	  	    `end`
                                    FROM    `work_graphic`
                                    WHERE   `actived` = 1");

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
                          <input type="checkbox" id="callapp_checkbox_'.$aRow[$hidden].'" name="check_'.$aRow[$hidden].'" value="'.$aRow[$hidden].'" class="check" />
                          <label for="callapp_checkbox_'.$aRow[$hidden].'"></label>
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
	    
		mysql_query("UPDATE `work_graphic` SET `actived`='0' WHERE (`id`='$_REQUEST[id]')");
		
		break;
	case 'get_add_page' :
	    
	   $data['page'][]=page();
	   
		break;
   	case 'save_dialog' :
   	    
   		if($_REQUEST[id]==''){
   		    
    		mysql_query("INSERT INTO `work_graphic`
    		             (`start`, `end`)
    				     VALUES
    		             ('$_REQUEST[start]', '$_REQUEST[end]')");
    		
   		}else{
   		    
			mysql_query("UPDATE `work_graphic` SET
                    			`start`='$_REQUEST[start]',
                    			`end`='$_REQUEST[end]'
			             WHERE (`id`='$_REQUEST[id]')");
			
		}
		
   		break;
	default:
		$error = 'Action is Null';
}
function page()
{
		$rResult 	= mysql_query(" SELECT 	*
                				    FROM `work_graphic`
                				    WHERE id = '$_REQUEST[id]' AND work_graphic.actived = 1");
		$res = mysql_fetch_array( $rResult );

	return '
	<div id="dialog-form">
		<fieldset >
	    	<legend >ძირითადი ინფორმაცია</legend>
            <input type="" style="opacity: 0; height: 0px;" id="hidden"/>
	    	<table class="dialog-form-table">
				<tr>
					<td style="width: 200px;"><label for="">მუშაობის დასაწყისი</label></td>
					<td style="width: 200px;"><label for="">მუშაობის დასასრული</label></td>
				</tr>
	            <tr>
					<td><input id="start" 	class="idle time" type="text" value="'.$res[start].	'" /></td>
					<td><input id="end"     class="idle time" type="text" value="'.$res[end].'" /></td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
    	    	<legend>შესვენებები</legend>
    	    	<div class="inner-table" style="width: 403px;">
        	    	<div id="button_area">
                    	<button id="add_button_brack">დამატება</button>
                    	<button id="delete_button_brack">წაშლა</button>
                    </div>
        	    	<table class="display" id="example-brack" style="width: 100%;">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 50%;">დასაწყისი</th>
					        <th style="width:50%;">დასასრული</th>
                        	<th class="check">#</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>                
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
					        <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                            	<div class="callapp_checkbox">
                                    <input type="checkbox" id="check-all-b" name="check-all-b" />
                                    <label for="check-all-b"></label>
                                </div>
                            </th>
                        </tr>
                    </thead>
                </table>
          </div>
	    </fieldset>
	</div>
    <input type="hidden" id="id" value='.$_REQUEST[id].'>';

}

$data['error'] = $error;

echo json_encode($data);



?>