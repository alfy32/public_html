<?php

	$host = "localhost";
	$user = "alan_alan";
	$password = "ze3^717?0F-e";
	$database = "alan_salon";

	$con = mysql_connect($host, $user, $password);

	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}

	mysql_select_db($database, $con);
	
	$appointment = array();
	
	$result = mysql_query("SELECT * FROM appointment");
		
	while($row = mysql_fetch_array($result))
	{
		$time = explode(':', $row['start_time']);
		$appointment[$row['employee_id']][intval($time[0])][intval($time[1])]['name'] = $row['client'];
		$appointment[$row['employee_id']][intval($time[0])][intval($time[1])]['id'] = $row['id'];
		$appointment[$row['employee_id']][intval($time[0])][intval($time[1])]['appointment_id'] = $row['appointment_id'];
	}
	
	echo "database post: ";
	print_r($_POST);
	
	// for when the form is submited
	if(isset($_POST['submit']))
	{
		// keeps the posted values in the textboxes
		$client = $_POST['client'];
		$employee = $_POST['employee'];
		$date = $_POST['date'];
		$start_time = $_POST['start_time'];
		$end_time = $_POST['end_time'];
		echo "submit";
		if($_POST['submit'] == 'edit')
		{
			$title = "Edit Appointment";
			
			$result = mysql_query("SELECT * FROM appointment WHERE id='{$_POST[id]}'");
			$row = mysql_fetch_array($result);
			echo "<br>Row: ";
			print_r($row);
			
			$client = $row['client'];
			$employee = $row['employee'];
			$date = $row['date'];
			$start_time = $row['start_time'];
			$end_time = $row['end_time'];
			$submit = "Edit Appointment";
			$_SESSION['id'] = htmlentities($_POST['id']);
		}
		else if($_POST['submit'] == 'new')
		{
			$title = "New Appointment";
			$client = $_POST['client'];
			$employee = $_POST['employee'];
			$date = date('Y-m-d');
			$start_time = $_POST['start_time'];
			$end_time = $_POST['start_time'];
			$submit = "Create Appointment";
		}
		else if($_POST['submit'] == "Edit Appointment")
		{
			echo "We got it all";
			$title = 'Appointment Updated';
			
			if(isset($_SESSION['id']))
			{
				mysql_query("UPDATE appointment
							SET client='{$client}', employee='{$employee}', date='{$date}',
								start_time='{$start_time}', end_time='{$end_time}'
							WHERE id={$_SESSION['id']}");
			}
			echo "<script>alert('Appointment Updated');window.close()</script>";
		}
		else if($_POST['submit'] == "Create Appointment")
		{
			mysql_query("INSERT INTO appointment(`appointment_id`, `client_id`, `employee_id`, 
												`date`, `start_time`, `end_time`, 
												`service_type_id`, `notes`,
												`employee`, `client`) 
						VALUES (0,0,0,
								'$date', '$start_time', '$end_time',
								0,'hi',
								'$employee','$client')");
		}
	}
	
	mysql_close($con);

?>