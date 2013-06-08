<?php
	define(HOST, "localhost");
	define(USER, "alan_alan");
	define(PASSWORD, "ze3^717?0F-e");
	define(DATABASE, "alan_salon");
	
	$database = new mysqli(HOST, USER, PASSWORD, DATABASE);
	if($database->connect_error)
		die("<h3>Database Error: $database->connect_error</h3>");
	
	//// ON NO POST SET THE DATE TO TODAY ////	
	$date = date('Y-m-d');
	
	$employeeName = "Jill";
	$employeeId = 1;
	
	//// CHANGE DATE ////
	if(isset($_POST['changeDate'])){	
		$date = htmlentities($_POST['date']);
	}	
	//// LEFT ARROW ////
	if(isset($_POST['leftArrow'])){
		$date = date("Y-m-d", strtotime(htmlentities($_POST['date'])."-1 day"));
	}
	//// RIGHT ARROW ////
	if(isset($_POST['rightArrow'])){
		$date = date("Y-m-d", strtotime(htmlentities($_POST['date'])."+1 day"));
	}
	//// CHANGE EMPLOYEE ////
	if(isset($_POST['changeEmployee'])){
		$date = htmlentities($_POST['date']);
		$employeeId = htmlentities($_POST['employeeId']);
		
		$result = $database->query("
			SELECT name_first FROM employee
			WHERE employee_id=$employeeId
		");
		$row = $result->fetch_assoc();
		
		$employeeName = $row['name_first'];
	}
	
	function getCell($col, $date, $timeString)
	{
		global $appointment;
		global $schedule;
		
		$time = explode(':',$timeString);
		$hour = intval($time[0]);
		$min = intval($time[1]);
		
		if($appointment[$col][$hour][$min]['appointmentId']) 
		{
			$appointmentId = $appointment[$col][$hour][$min]['appointmentId'];
			$height = $appointment[$col][$hour][$min]['height'];
			$clientName = $appointment[$col][$hour][$min]['clientName'];
			$count = $appointment[$col][$hour][$min]['count'];
			
			if($count == 0)
				return "
					<td class='cell_{$min}' >
						<input class='appId' type='hidden' value='$appointmentId' />
						<p class='write' style='height:{$height}px'>{$clientName}</p>
						<p class='cell' >{$min}</p>
					</td>
				";
			else if($count >= MAX_HEIGHT)
				return "
					<td class='cell_{$min}' >
						<input class='appId' type='hidden' value='$appointmentId' />
						<p class='write' style='height:{$height}px'>x</p>
						<p class='cell' >{$min}</p>
					</td>
				";
			else
				return "
					<td class='cell_{$min}' >
						<p class='cell' >{$min}</p>
					</td>
				";
		}
		else
		{	
			// Time not in the schedule
			if( (strtotime($schedule[$col]['dayStart']) > strtotime($timeString)) ||
				(strtotime($timeString) >= strtotime($schedule[$col]['dayEnd'])) ) {
				
				return "
					<td class='cell_{$min}_out' >
						<p class='cell' >{$min}</p>
						<p class='write_out'>x</p>
					</td>
				";
			}
			else {
				$employeeId = $schedule[$col]['employeeId'];
				return "
				<td class='cell_{$min}' id='cellHover' >
					<form action='' method='post'>
						<p class='cell_new' >{$min}</p>
						<input class='new_input' type='text' name='client' style='display:none' />
						<input type='hidden' name='employeeId' value='{$employeeId}'/>
						<input type='hidden' name='date' value='{$date}'/>
						<input type='hidden' name='startTime' value='{$timeString}' />
						<input type='submit' name='newAppointment' value='New' style='display:none'/>
					</form>
				</td>
				";
			}
		}
	}

	function getHourCell($hour)
	{
		//convert from 24 hours to 12 hours
		$am = $hour < 12 ? 'AM' : 'PM';
		$hour = ($hour-1)%12+1;
		
		return "
			<td class='hour' rowspan='4'>
				<p class='hour'>$hour</p>
				<p class='$am'>$am</p>
			</td>
		";
	}
	
	$dayOfWeek = date("l", strtotime($date));
	
	///////////////////  GET APPOINTMENT DATA  //////////////////////////////////	
	$result = $database->query("
		SELECT `appointment_id`, `client`, `appointment`.`employee_id`, 
				`date`, `start_time`, `end_time`, `name_first`, 
				`schedule_col`, `day_start`, `day_end` 
		FROM `appointment` 
		LEFT JOIN `employee` 
		ON `appointment`.`employee_id`=`employee`.`employee_id` 
		LEFT JOIN `employee_schedule` 
		ON `appointment`.`employee_id`=`employee_schedule`.`employee_id` 
		WHERE date=$date
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
			if($count == 0)
				$client_name = "$client_name";
			else
				$client_name = 'x';
			
			$appointment[$row['schedule_col']][$start_hr][$start_min]['appointmentId'] = $row['appointment_id'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['client'] = $row['client'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['clientName'] = $client_name;
			$appointment[$row['schedule_col']][$start_hr][$start_min]['employeeId'] = $row['employee_id'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['employeeName'] = $row['name_first'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['date'] = $row['date'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['startTime'] = $row['start_time'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['endTime'] = $row['end_time'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['count'] = $count++;
			
			$start_min += 15;
			if($start_min == 60)
			{ 
				$start_min = 0;
				$start_hr += 1;
			}
		}
	}
	
	///////////////////  GET SCHEDULE DATA  /////////////////////////////////////	
	
	
	$result = $database->query("
		SELECT employee.employee_id, schedule_col, day_start, day_end, name_first
		FROM employee_schedule
		LEFT JOIN employee
		ON employee.employee_id = employee_schedule.employee_id
		WHERE day_of_week='$dayOfWeek'
		AND schedule_col >= 0
	");
	
	$schedule = array();
	while($result && $row = $result->fetch_assoc()) {
		$col = $row['schedule_col'];
		$schedule[$col]['employeeId'] = $row['employee_id'];
		$schedule[$col]['employeeName'] = $row['name_first'];
		$schedule[$col]['dayStart'] = $row['day_start'];
		$schedule[$col]['dayEnd'] = $row['day_end'];
	}
	/////////////////////////////////////////////////////////	
?>