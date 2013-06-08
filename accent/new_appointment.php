<?php session_start() ?>
<!DOCTYPE html>

<?php
	// opens the database
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

	// Default Html values
	$title = 'New Appointment';
	$client = '';
	$employee = '';
	$date = date('Y-m-d');
	$start_time = date('H:i');
	$end_time = '';
	$submit = 'Create Appointment';
	
	// takes the values from the schedule.php
	if(isset($_GET['edit']))
	{
		$title = "Edit Appointment";
		
		$result = mysql_query("SELECT * FROM appointment WHERE id='{$_GET[id]}'");
		$row = mysql_fetch_array($result);
		
		$client = $row['client'];
		$employee = $row['employee'];
		$date = $row['date'];
		$start_time = $row['start_time'];
		$end_time = $row['end_time'];
		$submit = "Edit Appointment";
		$_SESSION['id'] = $_GET['id'];
	}
	if(isset($_GET['new']))
	{
		$title = "New Appointment";
		$client = $_GET['client'];
		
		$result = mysql_query("SELECT * FROM employee WHERE id='{$_GET[employee]}'");
		$row = mysql_fetch_array($result);
	
		$employee = $row['name_first'];
		$_SESSION['employee_id'] = $row['id'];
		$date = date('Y-m-d');
		$start_time = $_GET['start_time'];
		$end_time = $_GET['start_time'];
		$submit = "Create Appointment";
	}
		
	// for when the form is submited
	if(isset($_POST['submit']))
	{
		// keeps the posted values in the textboxes
		$client = $_POST['client'];
		$employee = $_POST['employee'];
		$date = $_POST['date'];
		$start_time = $_POST['start_time'];
		$end_time = $_POST['end_time'];
		
		// sets the values if they were posted
		if($_POST['submit'] == "Edit Appointment")
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
			echo "We got it all";
			$title = 'Appointment Created';
			
			mysql_query("INSERT INTO appointment(`appointment_id`, `client_id`, `employee_id`, 
												`date`, `start_time`, `end_time`, 
												`service_type_id`, `notes`,
												`employee`, `client`) 
						VALUES (0,0,{$_SESSION['employee_id']},
								'$date', '$start_time', '$end_time',
								0,'hi',
								'$employee','$client')");
			echo "<script>alert('Appointment Created');window.close()</script>";
		}
	}
	
	mysql_close($con);
?>

<html lang="en">
	<head>
		<title><?php echo $title ?></title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" type="text/css" href="new_appointment.css">
	</head>
	<body>
		<form action="new_appointment.php" method="post">
			<label>Client</label>		<input type="text" name="client" value="<?php echo $client ?>" /><br/>
			<label>Employee</label>		<input type="text" name="employee" value="<?php echo $employee ?>" /><br/>
			<label>Date</label>			<input type="date" name="date" value="<?php echo $date ?>" /><br/>
			<label>Start Time</label>	<input type="time" name="start_time" value="<?php echo $start_time ?>" /><br/>
			<label>End Time</label>		<input type="time" name="end_time" value="<?php echo $end_time ?>" /><br/>
			<input type="submit" name="submit" value="<?php echo $submit ?>" />
		</form>
	</body>
</html>