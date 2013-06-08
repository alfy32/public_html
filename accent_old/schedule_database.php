<?php 
	$host = "localhost";
	$user = "alan_alan";
	$password = "ze3^717?0F-e";
	$database = "alan_salon";
	
	$connection = new mysqli($host, $user, $password, $database);
	if($connection->connect_error)
	{
		die("<h3>Could not connect to database.</h3>
			<h4>$connection->msqli_error</h4>");
	}
	
	$con = mysql_connect($host, $user, $password);

	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}

	mysql_select_db($database, $con);
	
	// for edit
	$edit_appointment_id = '';
	$edit_client = '';
	$edit_employee_id = '';
	$edit_start_time = '';
	$edit_end_time = '';
	$edit_style = "style='display:none'";
	
///////////////////  ON POST  ///////////////////////////////////////////////
	if(isset($_POST['submit']))
	{	
		//print_r($_POST); echo "<br>";
		
		$date = htmlentities($_POST['date']);
		$date_str = date('l, F j, Y',strtotime($date));
		$sql_date = " WHERE date='{$date}'";
		$day_of_week = date('l', strtotime($date));
		
	///////////////////  GOTO YESTERDAY  ////////////////////////////////////
		if($_POST['submit'] == "<")
		{
			$date = date("Y-m-d", strtotime($date."-1 day"));
			$date_str = date('l, F j, Y',strtotime($date));
			$sql_date = " WHERE date='{$date}'";
			$day_of_week = date('l', strtotime($date));
		}
	///////////////////  GOTO TOMORROW  /////////////////////////////////////
		else if($_POST['submit'] == ">")
		{
			$date = date("Y-m-d", strtotime($date."+1 day"));
			$date_str = date('l, F j, Y',strtotime($date));
			$sql_date = " WHERE date='{$date}'";
			$day_of_week = date('l', strtotime($date));
		}
	///////////////////  NEW APPOINTMENT  ///////////////////////////////////
		else if($_POST['submit'] == "New Appointment")
		{
			$start_time = $connection->real_escape_string(htmlentities($_POST['start_time']));
			$employee_id = $connection->real_escape_string(htmlentities($_POST['employee_id']));
			$client = $connection->real_escape_string(htmlentities($_POST['client']));
			
			//DATE_ADD({$start_time},INTERVAL 30 MINUTE)
			
			$connection->query("
				INSERT INTO appointment 
					(date, start_time, end_time, employee_id, client)
				VALUES ('{$date}',TIME('{$start_time}'),ADDTIME('{$start_time}','0:30:00'),
						{$employee_id},'{$client}')
			");
		}
	///////////////////  EDIT APPOINTMENT  //////////////////////////////////
		else if($_POST['submit'] == "Edit Appointment")
		{
			$appointment_id = $connection->real_escape_string(htmlentities($_POST['appointment_id']));
			$client = $connection->real_escape_string(htmlentities($_POST['client']));
			$employee_id = $connection->real_escape_string(htmlentities($_POST['employee_id']));
			$date = $connection->real_escape_string(htmlentities($_POST['date']));
			$start_time = $connection->real_escape_string(htmlentities($_POST['start_time']));
			$end_time = $connection->real_escape_string(htmlentities($_POST['end_time']));
			
			$error = false;
			
			// Check for overlapping appointments
			$result = $connection->query("
				SELECT COUNT(*) 
				FROM appointment
				WHERE DATE = '$date'
				AND employee_id = $employee_id
				AND appointment_id != $appointment_id
				AND (
				  (
					start_time >= '$start_time'
					AND start_time < '$end_time'
				  )
				  OR (
					end_time > '$start_time'
					AND end_time <= '$end_time'
				  )
				  OR (
					start_time <= '$start_time'
					AND end_time > '$start_time'
				  )
				  OR (
					start_time < '$end_time'
					AND end_time >= '$end_time'
				  )
				)
			");
			// if the count is nonzero then there is a problem
			$row = $result->fetch_array();
			if($row[0]) {
				$error = true;
				$error_string = "There is already an appointment at that time.";
			}
			if(strtotime($end_time) <= strtotime($start_time)) {
				$error = true;
				$error_string = "End time must be after start time.";
			}
			
			// update appointment if no errors
			if($error == false) {
				$connection->query("
					UPDATE appointment
					SET appointment_id='{$appointment_id}', client='{$client}', 
						employee_id='{$employee_id}', date='{$date}',
						start_time='{$start_time}', end_time='{$end_time}'
					WHERE appointment_id={$appointment_id}
				");
			}
			// show the appointment editor on fail
			else {
				$edit_appointment_id = $appointment_id;
				$edit_client = $client;
				$edit_employee_id = $employee_id;
				$edit_start_time = $start_time;
				$edit_end_time = $end_time;
				$edit_style = "style='display:block'";
			}
		}
		
	///////////////////  DELETE APPOINTMENT  //////////////////////////////////
		else if($_POST['submit'] == "Delete Appointment")
		{
			echo $appointment_id;
			$appointment_id = htmlentities($_POST['appointment_id']);
			
			$connection->query("
				DELETE FROM appointment
				WHERE appointment_id={$appointment_id}
			");
		}
	///////////////////  CHANGE EMPLOYEE  ////////////////////////////////////
		else if($_POST['submit'] == "Change Employee")
		{
			$schedule_col = $connection->real_escape_string(htmlentities($_POST['schedule_col']));
			$day_of_week = $connection->real_escape_string(htmlentities($_POST['day_of_week']));
			$old_employee_id = $connection->real_escape_string(htmlentities($_POST['old_employee_id']));
			$employee_id = $connection->real_escape_string(htmlentities($_POST['employee_id']));
			
			//make the current one to nothing 
			$connection->query("
				UPDATE employee_schedule
				SET schedule_col=-{$old_employee_id}
				WHERE employee_id={$old_employee_id}
				AND day_of_week='{$day_of_week}'
			");
			// see if the employee has a schedule for the day
			$result = $connection->query("
				SELECT COUNT(*) FROM employee_schedule
				WHERE employee_id={$employee_id}
				AND day_of_week='{$day_of_week}'
			");
			if($result) {
				$row = $result->fetch_array();
				if($row[0]) { // update the new one
					$connection->query("
						UPDATE employee_schedule
						SET schedule_col={$schedule_col}
						WHERE employee_id={$employee_id}
						AND day_of_week='{$day_of_week}'
					");
				}
				else {
					$connection->query("
						INSERT INTO employee_schedule
							(employee_id,day_of_week,schedule_col)
						VALUES ($employee_id,'$day_of_week',$schedule_col)
					");
				}
			}
		}
	}

///////////////////  GET APPOINTMENT DATA  //////////////////////////////////
	$dayOfWeek = date("l", strtotime($date));
	
	$result = $connection->query("
		SELECT  `appointment_id`, `client`, `appointment`.`employee_id`,  
				`date`, `start_time`, `end_time`, `name_first`, 
				`schedule_col`, `day_start`, `day_end` 
		FROM  `appointment` 
		LEFT JOIN  `employee` 
		ON `appointment`.`employee_id`=`employee`.`employee_id` 
		LEFT JOIN  `employee_schedule` 
		ON `appointment`.`employee_id`=`employee_schedule`.`employee_id` 
		WHERE `date`='$date'
		AND `day_of_week`='$dayOfWeek'
	");
	
	$appointment = array();
	while($row = $result->fetch_assoc())
	{
		$start_time = explode(':', $row['start_time']);
		$start_hr = intval($start_time[0]); 
		$start_min = intval($start_time[1]);
		$end_time = explode(':', $row['end_time']);
		$end_hr = intval($end_time[0]); 
		$end_min = intval($end_time[1]);
		
		$client_name = stripslashes($row['client']);
		
		$count = 0;

		while($start_hr*100+$start_min < $end_hr*100+$end_min)
		{
			$client_name = "$client_name<br/>x";
			
			$appointment[$row['schedule_col']][$start_hr][$start_min]['appointment_id'] = $row['appointment_id'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['client'] = $row['client'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['employee_id'] = $row['employee_id'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['employee_name'] = $row['name_first'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['date'] = $row['date'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['start_time'] = $row['start_time'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['end_time'] = $row['end_time'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['count'] = $count++;
			
			$start_min += 15;
			if($start_min == 60)
			{ 
				$start_min = 0;
				$start_hr += 1;
			}
		}
		$appointment[$row['schedule_col']][intval($start_time[0])][intval($start_time[1])]['client_name'] = $client_name;
		$appointment[$row['schedule_col']][intval($start_time[0])][intval($start_time[1])]['height'] = 17*$count;

	}
	
///////////////////  GET EMPLOYEE DATA  /////////////////////////////////////	
	$result = $connection->query("
		SELECT employee.employee_id,schedule_col,name_first 
		FROM employee
		LEFT JOIN employee_schedule
		ON employee.employee_id=employee_schedule.employee_id
		WHERE day_of_week='$day_of_week'
	");
	
	$schedule_names = array();
	while($row =$result->fetch_assoc())
	{
		$schedule_names[$row['schedule_col']]['employee_id'] = $row['employee_id'];
		$schedule_names[$row['schedule_col']]['employee_name'] = $row['name_first'];
	}
///////////////////  GET SCHEDULE DATA  /////////////////////////////////////	
	$result = $connection->query("
		SELECT employee_id, schedule_col, day_start, day_end
		FROM employee_schedule
		WHERE day_of_week='$day_of_week'
		AND schedule_col >= 0
	");
	$schedule = array();
	while($result && $row = $result->fetch_assoc()) {
		$schedule[$row['schedule_col']]['employee_id'] = $row['employee_id'];
		$schedule[$row['schedule_col']]['day_start'] = $row['day_start'];
		$schedule[$row['schedule_col']]['day_end'] = $row['day_end'];
	}
?>