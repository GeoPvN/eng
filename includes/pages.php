<?php

$user_id	= $_SESSION['USERID'];
$user_gr    = $_SESSION['USERGR'];
$page		= $_REQUEST['pg'];
$action		= $_REQUEST['act'];

if($page == '1'){
	require_once ("includes/logout.php");
}else{	
	require_once ("includes/menu.php");	
	require_once ("includes/classes/page.class.php");
	if (empty($page)) {
	    if($user_gr == 7){
		    $page = 68;
	    }elseif ($user_gr==8){
	        $page = 69;
	    }else{
	        $page = 7;
	    }
	}
	$page = new page($user_id, $page);
	$page->reqPage();
}

?>