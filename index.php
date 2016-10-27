<?php session_start(); ?>
<?php require_once(dirname(__FILE__) . '/config.php'); ?>

<?php if ($_SESSION['USERID'] == ''): ?>
	<?php require_once(ABSPATH . 'includes/login.php');?>
<?php else: ?>
	<?php require_once(ABSPATH . 'includes/functions.php');?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>CallApp - E-Pro</title>

			<link rel="shortcut icon" type="image/ico" href="media/images/CA-mini.png" />
			<link rel="stylesheet" type="text/css" media="screen" href="media/css/reset/reset.css" />
			<!--[if IE]>
				<link rel="stylesheet" type="text/css" media="screen" href="css/reset/ie.css" />
			<![endif]-->

			<?php echo GetJs();?>
			<?php echo GetCss();?>

			<script type="text/javascript">
				$(document).ready(function (){
					AjaxSetup();
				});
			</script>

			<style>
            #news{
            	display: block;
            	position: fixed !important;
            	bottom: 10px !important;
            	right: 10px !important;
            	background: #DB3340;
            	width: 200px;
            	height: 40px;
            	border-radius:3px;
            	color: #E8B71A;
            	text-align:center;
            }
            #news span{
            	display: block;
            	padding-top: 7px;
            }
            #news_action{
            	display: block;
            	position: fixed !important;
            	bottom: 55px !important;
            	right: 10px !important;
            	background: #DB3340;
            	width: 200px;
            	height: 40px;
            	border-radius:3px;
            	color: #E8B71A;
            	text-align:center;
            }
            #news_action span{
            	display: block;
            	padding-top: 7px;
            }
            
            #news_call{
            	display: block;
            	position: fixed !important;
            	bottom: 100px !important;
            	right: 10px !important;
            	background: #DB3340;
            	width: 200px;
            	height: 40px;
            	border-radius:3px;
            	color: #E8B71A;
            	text-align:center;
            }
            #news_call span{
            	display: block;
            	padding-top: 7px;
            }
            
            #news_elva{
            	display: block;
            	position: fixed !important;
            	bottom: 150px !important;
            	right: 10px !important;
            	background: #DB3340;
            	width: 200px;
            	height: 40px;
            	border-radius:3px;
            	color: #E8B71A;
            	text-align:center;
            }
            #news_elva span{
            	display: block;
            	padding-top: 7px;
            }
            </style>


    <link href="css/mbExtruder.css" media="all" rel="stylesheet" type="text/css">

    <script type="text/javascript">
        $(function(){


        	checker1();
			
		    function checker1(){
				setTimeout(function(){
                	$.ajax({url: 'server-side/ckeck_user.action.php'});
                   	checker1();
                }, 1000);
                      
            }
            
            $(document).ready(function (){
            	checker();
                function checker(){
                	setTimeout(function(){
                		$.ajax({
        		            url: "checker.php",
        		            type: "POST",
        		            data: "act=get_checker",
        		            dataType: "json",
        		            success: function (data) {
            		            if(data.count != 0){
        		            	$("#new_news").attr('class','badge1');
        		            	$("#new_news").attr('data-badge',data.count);
            		            }else{
            		            	$("#new_news").removeClass('badge1');
            		            	$("#new_news").removeAttr('data-badge');
            		            }
        		            }
        		        });
                		checker();
                    	}, 3000);
                }
            	$(document).on("click", ".logout", function () {
	            	$.ajax({
				        url: "server-side/logout.action.php",
					    data: "act=logout_save",
				        success: function(data) {
				        	window.location.href="index.php?act=logout";
					    }
				    });						
		        });
				
				$(document).on("click", "#news", function () {
					location.href='index.php?pg=33#tab-1'
				});
				$(document).on("click", "#news_call", function () {
					location.href='index.php?pg=33#tab-5'
				});
				$(document).on("click", "#news_elva", function () {
					location.href='index.php?pg=33#tab-6'
				});
				$(document).on("click", "#news_action", function () {
					location.href='index.php?pg=13'
				});
			});
        });
        
    </script>

		</head>
		<body>
		<div id="disable_all" style="position: absolute;height: 100%;width: 100%;display: none;"></div>
			<div id="npm"></div>
			<?php require_once(ABSPATH . 'includes/pages.php'); ?>
			
			<div id="newsmain">
			
			</div>
			<div id='yesnoclose' class="form-dialog">
                <div id="dialog-form">
                    <fieldset>
                                                 გსურთ თუ არა ცვლილებების შენახვა?
                    </fieldset>
                </div>
            </div>
            
            <!-- jQuery Dialog -->
            <div  id="play_audio" class="form-dialog" title="მოსმენა">
                <audio controls autoplay>
                  <source src="" type="audio/wav">
                </audio>
            </div>
		</body>
	</html>
<?php endif;?>