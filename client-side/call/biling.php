<?php 
require_once('../../includes/classes/core.php');
$res = mysql_fetch_assoc(mysql_query("SELECT    `link`,
                                				`user`,
                                				`password`
                                      FROM 		`author`
                                      WHERE 	`service_center_id` = $_REQUEST[service_center_id] "));

$url        = $res[link];
$j_username = $res[user];
$j_password = $res[password];

echo '<html>
        <body>           
            <form  action="'.$url.'" method="POST" id="myform">
              <input type="text" name="j_username" value="'. $j_username .'" style="display:none;">
              <input type="text" name="j_password" value="'. $j_password .'" style="display:none;">
              <input type="submit" value="login" id="trigger" style="display:none;">
            </form>           
        </body>
      </html>';

?>

<script type="text/javascript">

	document.forms["myform"].submit();

</script>
