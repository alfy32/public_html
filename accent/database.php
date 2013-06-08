<?php 
	$file_name = "schedule";

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
	
	$date = date('Y-m-d');
	$date_str = date('l, F j, Y');
	$sql_date = " WHERE date='{$date}'";
	
	if(isset($_POST['submit']))
	{	
		//print_r($_POST); echo "<br>";
		
		$date = $_POST['date'];
		$date_str = date('l, F j, Y',strtotime($date));
		$sql_date = " WHERE date='{$date}'";
		
		if($_POST['submit'] == "<")
		{
			$date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . "-1 day"));
			$date_str = date('l, F j, Y',strtotime($date));
			$sql_date = " WHERE date='{$date}'";
		}
		else if($_POST['submit'] == ">")
		{
			$date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . "+1 day"));
			$date_str = date('l, F j, Y',strtotime($date));
			$sql_date = " WHERE date='{$date}'";
		}
		else if($_POST['submit'] == "Create New Appointment")
		{
			$start_time = $_POST['start_time'];
			$hour = $start_time/100;
			$min = $start_time%100;
			$time = new DateTime();
			$time->setTime($hour,$min);
			$start_time = $time->format("H:i:s");
			$time->setTime($hour,$min+15);
			$end_time = $time->format("H:i:s");
			$employee_id = $_POST['employee_id'];
			$client = $_POST['client'];
			mysql_query("
			INSERT INTO appointment (date, start_time, end_time, employee_id, client)
			VALUES ('{$date}','{$start_time}','{$end_time}',{$employee_id},'{$client}')
			");
		}
		else if($_POST['submit'] == "Edit Appointment")
		{
			$appointment_id = $_POST['appointment_id'];
			$client = $_POST['client'];
			$employee_id = $_POST['employee_id'];
			$start_time = $_POST['start_time'];
			$end_time = $_POST['end_time'];
			
			mysql_query("
				UPDATE appointment
				SET appointment_id='{$appointment_id}', client='{$client}', 
					employee_id='{$employee_id}', 
					start_time='{$start_time}', end_time='{$end_time}'
				WHERE appointment_id={$appointment_id}");
		}
	}
		
	$result = mysql_query("
		SELECT `appointment_id`, `client`, `appointment`.`employee_id`,
				`date`, `start_time`, `end_time`, `name_first`, `schedule_col`
		FROM `appointment` 
		LEFT JOIN `employee`
		ON `appointment`.`employee_id`=`employee`.`employee_id`
		{$sql_date}");
	
	$appointment = array();
	
	while($row = mysql_fetch_array($result))
	{
		$time = explode(':', $row['start_time']);
		$appointment[$row['schedule_col']][intval($time[0])][intval($time[1])]['appointment_id'] = $row['appointment_id'];
		$appointment[$row['schedule_col']][intval($time[0])][intval($time[1])]['client'] = $row['client'];
		$appointment[$row['schedule_col']][intval($time[0])][intval($time[1])]['employee_id'] = $row['employee_id'];
		$appointment[$row['schedule_col']][intval($time[0])][intval($time[1])]['employee_name'] = $row['name_first'];
		$appointment[$row['schedule_col']][intval($time[0])][intval($time[1])]['date'] = $row['date'];
		$appointment[$row['schedule_col']][intval($time[0])][intval($time[1])]['start_time'] = $row['start_time'];
		$appointment[$row['schedule_col']][intval($time[0])][intval($time[1])]['end_time'] = $row['end_time'];
	}
	
	$schedule_names = array();
	
	$result = mysql_query("SELECT employee_id,schedule_col,name_first FROM employee");
	
	while($row = mysql_fetch_array($result))
	{
		$schedule_names[$row['schedule_col']]['employee_name'] = $row['name_first'];
		$schedule_names[$row['schedule_col']]['employee_id'] = $row['employee_id'];
	}
?>