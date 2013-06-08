<?php
session_start();
require_once 'classes/Membership.php';
$membership = new Membership();

// If the user clicks the "Log Out" link on the index page.
if(isset($_GET['status']) && $_GET['status'] == 'loggedout') {
	$membership->log_User_Out();
}

// Did the user enter a password/username and click submit?
if($_POST && !empty($_POST['username']) && !empty($_POST['pwd'])) {
	$response = $membership->validate_User($_POST['username'], $_POST['pwd']);
}
														

?>

<!DOCTYPE html>
<html lang='en'>
	<head>
		<title>Accent Login</title>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=980" />
		<!--<link rel="stylesheet" type="text/css" href="css/default.css">-->
		<style>
			body {
			margin: auto;
			max-width: 980px; 
			min-width: 980px;
			background-color: black;
			color: white;
			}
			header {
			height: 200px;
			background-color: black;
			background: black url("images/logo.jpg") no-repeat top center; 
			}
			.logoLeft {
			float: left;
			}
			.logoRight {
			float: right;
			}
			div.login {
			margin-top: 100px;
			padding: 20px;
			}
			h2 {
			font-size: 80px;
			text-align: center;
			}
			label, input.input, .submit {
			font-size: 60px;
			}
			input.input {
			width: 910px;
			padding: 12px;
			}
			.submit {
			float: right;
			margin-top: 80px;
			padding: 5px 50px;
			background-color: #2E3436;
			color: white;
			border-radius: 100px;
			border: 0px;
			}
		</style>
	</head>
	<body>
		<header></header>
		<div class="login">
			<form method="post" action="">
				<label for="name">Username: </label> <br/>
				<input class="input" type="text" name="username" />
				<br/>
				<label for="pwd">Password: </label> <br/>
				<input class="input" type="password" name="pwd" />
				<br />
				<input class="submit" type="submit" id="submit" value="Login" name="submit" />
			</form>
			<?php if(isset($response)) echo "<h4 class='alert'>" . $response . "</h4>"; ?>
		</div>
	</body>
</html>