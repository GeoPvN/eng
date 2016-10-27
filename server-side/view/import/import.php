<?php
require_once '../../../includes/classes/core.php';
require_once '../../../includes/excel_reader2.php';
?>
<?php
/* ******************************
 *	File Upload aJax actions
 * ******************************
 */

$action = $_REQUEST['act'];
$error	= '';
$data	= '';

switch ($action) {
	case 'upload_file':
		$element	= 'choose_file';
		$file_name	= $_REQUEST['file_name'];
		$type		= $_REQUEST['type'];
		$path		= $_REQUEST['path'];
		$path		= $path . $file_name . '.' . $type;

		if (! empty ( $_FILES [$element] ['error'] )) {
			switch ($_FILES [$element] ['error']) {
				case '1' :
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2' :
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3' :
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4' :
					$error = 'No file was uploaded.';
					break;
				case '6' :
					$error = 'Missing a temporary folder';
					break;
				case '7' :
					$error = 'Failed to write file to disk';
					break;
				case '8' :
					$error = 'File upload stopped by extension';
					break;
				case '999' :
				default :
					$error = 'No error code avaiable';
			}
		} elseif (empty ( $_FILES [$element] ['tmp_name'] ) || $_FILES [$element] ['tmp_name'] == 'none') {
			$error = 'No file was uploaded..';
		} else {

$filename=$_FILES [$element] ['tmp_name'];

	$data = new Spreadsheet_Excel_Reader($filename);
	$r=$data->rowcount($sheet_index=0); $i=0;
	echo  $r;
	$c_date		= date('Y-m-d H:i:s');
	while (1!=$r){
		mysql_query("INSERT INTO `phone`
							  (`user_id`, `create_date`, `import`,  `person_n`, `first_last_name`, `addres`, `city`, `mail`,`born_day`, `sorce`, `person_status`, `phone1`, `phone2`, `note`)
							    VALUES ( '".$_SESSION['USERID']."', '".$c_date."', '1',
										 '".mysql_real_escape_string($data->val($r,'A'))."', '".mysql_real_escape_string($data->val($r,'B'))."',
									 	 '".mysql_real_escape_string($data->val($r,'C'))."', '".mysql_real_escape_string($data->val($r,'D'))."',
									 	 '".mysql_real_escape_string($data->val($r,'E'))."', '".mysql_real_escape_string($data->val($r,'F'))."',
									 	 '".mysql_real_escape_string($data->val($r,'G'))."', '".mysql_real_escape_string($data->val($r,'H'))."',
									 	 '".mysql_real_escape_string($data->val($r,'I'))."', '".mysql_real_escape_string($data->val($r,'J'))."', '".mysql_real_escape_string($data->val($r,'K'))."')") or die (err);
		$r--; //return 0;
	}

	echo "xls File has been successfully Imported";

			if (file_exists($path)) {
				unlink($path);
			}
//			move_uploaded_file ( $_FILES [$element] ['tmp_name'], $path);
//
			// for security reason, we force to remove all uploaded file
//			@unlink ( $_FILES [$element] );
		}

		break;
    default:
       $error = 'Action is Null';
}
$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	File Upload Functions
 * ******************************
 */

?>