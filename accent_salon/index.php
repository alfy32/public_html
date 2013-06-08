<?php 
	//// LOGIN SETUP ////
	if(false) { //$_SESSION['authenticated'])  authentication
		header( 'Location: login.php' );
	}
	
	$date = date('Y-m-d');
 ?>
 
 <!DOCTYPE html>
 <html lang="en">
	<head>
		<title>Accent Schedule</title>
		<link rel="stylesheet" type="text/css" href="css/schedule.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/schedule.js"></script>
		<meta charset="UTF-8">
	</head>
	<body>
		<header>
			<form class="headerForm" action="" method="post">
				<div class="header_left">
					<a href="">
					<img class='logo_left' src='images/accent_salon.jpg' alt='Accent Logo' />
					</a>
				</div>
				<div class="header_center">
					<div class="left_arrow">
						<input type="submit" name="submit" value='<' />
					</div>
					<div class="date_center">
						<p class='header_date'><?php echo date("l, F j, Y", strtotime($date)); ?></p>	
						<input id='change_date' name='date' class='header_date' type='date' style='display:none' value='<?php echo $date ?>' onkeypress='date_keypress(this,event)' onchange='date_changed(this,event)'/>
						<input type="submit" name="submit" value="Change Date" id="submit_date" style="display:none" />
					</div>
					<div class="right_arrow">
						<input type="submit" name="submit" value=">" />
					</div>	
				</div>
				<div class="header_right">
					<a href="">
					<img class='logo_right' src='images/accent_salon.jpg' alt='Accent Logo' />
					</a>
				</div>
			</form>	
		</header>