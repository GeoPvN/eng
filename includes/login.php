<?php
require_once 'classes/authorization.class.php';

$user  		= $_POST['username'];
$password	= $_POST['password'];
$ext		= $_POST['ext'];

$login 		= new Authorization();

$login->set_ext($ext);
$login->set_username($user);
$login->set_password($password);

$check = $login->checklogin();

if ($check==1) {
    $login->ip();
	$login->savelogin();
	echo '<meta http-equiv=refresh content="0; URL=index.php">';
	
}else {
    
if ($check==4) {
    $text='აირჩიე ექსთენშენი!';
}elseif ($check==2 || $check==0){
    $text='';
}else {
    $text='მომხმარებლის იუზერი ან პაროლი არასწორია';
}
?>
			<html>
				<head>
					<meta charset="utf-8">
					<title>ავტორიზაცია</title>
					<link rel="stylesheet" type="text/css" href="media/css/login/style.css" />
				</head>
				<body>
					<div class="container">
						<section id="content">
							<form action="" method="post">
								<h1>ავტორიზაცია</h1>
								<div>
									<input name="username" type="text" placeholder="მომხმარებელი" required="" id="username" autocomplete="off"/>
								</div>
								<div>
									<input name="password" type="password" placeholder="პაროლი" required="" id="password" autocomplete="off"/>
								</div>
								<script type="text/javascript">
							    function show_ext(){
								    //alert(document.getElementById("click_ext").checked)
								    if(document.getElementById("click_ext").checked == true){
								        document.getElementById('show_ext').style.display = 'block';
							    	}else{
							    		document.getElementById('show_ext').style.display = 'none';
							    	}
							    }
								</script>
								<div style="margin-bottom: 5px;"><input type="checkbox" id="click_ext" onclick="show_ext()"></div>
								<div id="show_ext" style="display: none;">
									<select name="ext" id="ext" >
									            <option value="0">----</option>
											 <?php 
											   	$rResult = mysql_query("SELECT ext.ext FROM(SELECT extention.extention AS ext
                                                    										FROM   extention
                                                    										WHERE  extention.extention NOT IN(SELECT users.extension_id
                																											  FROM   users
                																											  WHERE  users.logged = 1 
                																											    AND  users.extension_id != 0 
                																											    AND  users.extension_id != 0)
                                                    
                                                    										UNION ALL
                                                    
                                                    										SELECT users.extension_id as ext
                                                    										  FROM users
                                                    										 WHERE users.logged = 1 
                                                    										   AND TIME_TO_SEC(TIMEDIFF(NOW(), users.last_actived_time))>3
                                                    										   AND users.extension_id != 0) AS ext
											   	                             ORDER BY ext ASC");
											    while ( $aRow = mysql_fetch_array( $rResult ) )
											    {
											    	echo '<option>'.$aRow[0].'</option>';
											    	
											    }
							    
							    	?>
									</select>
								</div>
								<div><p style="font-size: 10px; color: #F70404;"><?php echo $text ?></p></div>
								<div>
									<input type="submit" value="შესვლა" />
								</div>
							</form>
						</section>
					</div>
				</body>
			</html>
	<?php }