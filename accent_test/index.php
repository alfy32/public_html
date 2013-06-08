<?php session_start();

	include 'php_include/database.php';
	include 'php_include/functions.php';
	
	echo "Appointment: ";
	print_r($appointment);
	echo "<br>Get: ";
	print_r($_GET);
	echo "<br>Post: ";
	print_r($_POST);
	echo "<br>Request: ";
	print_r($_REQUEST);
	
	if(isset($_POST['submit']))
	{	
		if($_POST['submit'] == 'edit' || $_POST['submit'] == 'new')
		{
			include_once "new_appointment.php";
		}
	}
	else
		include_once "schedule.php";
?>