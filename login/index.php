<?php

require_once 'classes/Membership.php';
$membership = New Membership();

$membership->confirm_Member();

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Accent Login</title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div id="container">
			<p>
				You've reached the page that stores all of the secret 	launch codes!
			</p>
			<a href="login.php?status=loggedout">Log Out</a>
		</div><!--end container-->
	</body>
</html>
