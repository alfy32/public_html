<!DOCTYPE html>
<?php 
	define(HOST, "localhost");
	define(USER, "alan_alan");
	define(PASSWORD, "ze3^717?0F-e");
	define(DATABASE, "alan_salon");
	
	$database = new mysqli(HOST, USER, PASSWORD, DATABASE);
	if($database->connect_error)
		die("<h3>Database Error: $database->connect_error</h3>");
	
	//// Today's date ////
	$date = date('Y-m-d');
	$sql_date = " WHERE date='{$date}'";
	$day_of_week = date('l', strtotime($date));	
	
	
	//// CHANGE DATE ////
	if(isset($_POST['changeDate']))
	{	
		$date = htmlentities($_POST['date']);
		$sql_date = " WHERE date='{$date}'";
		$day_of_week = date('l', strtotime($date));
	}	
	////  GOTO YESTERDAY  ////
	if(isset($_POST['previous']))
	{
		$date = date("Y-m-d", strtotime(htmlentities($_POST['date'])."-1 day"));
		$sql_date = " WHERE date='{$date}'";
		$day_of_week = date('l', strtotime($date));
	}
	////  GOTO TOMORROW  ////
	if(isset($_POST['next']))
	{
		$date = date("Y-m-d", strtotime(htmlentities($_POST['date'])."+1 day"));
		$sql_date = " WHERE date='{$date}'";
		$day_of_week = date('l', strtotime($date));
	}
	
	//// EMPLOYEE HEADER NAME ////
	function get_employee_header($col) 
	{ 
		global $database;
		$script_name = htmlentities($_SERVER['SCRIPT_NAME']);
		$name = 'Jill';
		
		echo "
			<p class='employee_name'>$name</p>
			<form class='employee_form' action='$script_name' method='POST' style='display:none'>
				<select class='employee_select' name='employee_id'> 
		";
						$result = $database->query("SELECT employee_id, name_first FROM employee");

						while($result && $row = $result->fetch_assoc()) 
							echo "<option value='$row[employee_id]'>$row[name_first]</option>";
		echo "
				</select>
				<input class='changeEmployee' type='submit' name='changeEmployee' value='Change Employee' style='display:none' />
			</form>
		";
	}

	
	
	// arrays that will be filled with the appointment data
	// and the employee names.
	$appointment = array();
	$schedule_names = array();

	
	
	
	
	//include_once "schedule_database.php";
	
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
		{$sql_date}  
		AND `day_of_week`='$day_of_week'
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
			
			$appointment[$row['schedule_col']][$start_hr][$start_min]['appointment_id'] = $row['appointment_id'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['client'] = $row['client'];
			$appointment[$row['schedule_col']][$start_hr][$start_min]['client_name'] = $client_name;
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
	}
	
///////////////////  GET EMPLOYEE DATA  /////////////////////////////////////	
	$result = $database->query(" 
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
	$result = $database->query("
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
	
	function get_cell($col, $date, $time_str, $pos)
	{
		global $appointment;
		global $schedule_names;
		global $schedule;
		
		$time = explode(':',$time_str);
		$hour = intval($time[0]);
		$min = intval($time[1]);
		
		if( (strtotime($schedule[$col]['day_start']) > strtotime($time_str)) ||
			(strtotime($time_str) >= strtotime($schedule[$col]['day_end'])) ) {
			return "
				<td class='cell_{$min}' >
					<p class='cell_write' style='color:black'>x</p>
					<p class='cell_min' >{$min}</p>
				</td>
			";
		}
		
		$employee_id = $schedule_names[$col]['employee_id'];
				
		if($appointment[$col][$hour][$min]['appointment_id']) 
		{
			$appointment_id = $appointment[$col][$hour][$min]['appointment_id'];
			$client = $appointment[$col][$hour][$min]['client'];
			$employee_id = $appointment[$col][$hour][$min]['employee_id'];
			$employee_name = $appointment[$col][$hour][$min]['employee_name'];
			$date = $appointment[$col][$hour][$min]['date'];
			$start_time = $appointment[$col][$hour][$min]['start_time'];
			$end_time = $appointment[$col][$hour][$min]['end_time'];
			$height = $appointment[$col][$hour][$min]['height'];
			$client_name = $appointment[$col][$hour][$min]['client_name'];
			$count = $appointment[$col][$hour][$min]['count'];

			//if($count == 0)
				return "
					<td class='cell_{$min}' >
						<p class='cell_write' onclick=\"edit_appointment('{$appointment_id}','{$client}','{$employee_id}','{$date}','{$start_time}','{$end_time}')\">{$client_name}</p>
						<p class='cell_min' >{$min}</p>
					</td>
				";
			if(0)
				return "
					<td class='cell_{$min}' >
						<p class='cell_min' onclick=\"edit_appointment('{$appointment_id}','{$client}','{$employee_id}','{$date}','{$start_time}','{$end_time}')\" >{$min}</p>
					</td>
				";
		}
		else
		{	
			return "
				<td class='cell_{$min}' >
					<form action='schedule.php' method='post'>
						<p class='cell_min' onclick='new_appointment(this,\"{$time_str}\",\"{$employee_id}\")'>{$min}</p>
						<input type='text' name='client' class='new_input' id='client' style='display:none' />
						<input type='hidden' name='employee_id' value='{$employee_id}'/>
						<input type='hidden' name='date' value='{$date}'/>
						<input type='hidden' name='start_time' value='{$time_str}' />
						<input type='submit' name='submit' value='New Appointment' style='display:none'/>
					</form>
				</td>
			";
		}
	/*
		{
			
			return "
				<td class='cell_{$min}' onclick='new_appointment(this,\"{$time_str}\",\"{$employee_id}\")'>
					<p class='cell_{$pos}' >{$min}</p>
				</td>
			";
		}
	*/
	}

	function get_cell_hours($hour)
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


?>
<html lang="en">
	<head>
		<title>Accent Schedule</title>
		<!--<link rel="stylesheet" type="text/css" href="schedule_mobile_style.css">-->
		<meta charset="UTF-8">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<style>
			body {
			margin: 0px;
			padding: 0px;
			}
			
			/* //// HEADER STYLE //// */
			
			header{
			position: fixed;
			top: 0px;
			left: 0px;
			background-color: black;
			height: 150px;
			width: 100%;
			z-index: 1001;
			}
			div.left_arrow, div.date_center, div.right_arrow {
			float: left;
			text-align: center;
			}
			div.date_center {
			display: inline-block;
			width: 72%;
			height: 100%;
			}
			.date_header, .date_input {
			margin: 33px auto;
			color: white;
			font-family: "Comic Sans MS";
			font-size: 60px;
			text-align: center;
			vertical-align: middle;
			}
			div.left_arrow, div.right_arrow {
			height: 100px;
			margin: 25px 2%;
			border-radius: 20px;
			background-color: white;
			color: black;
			width: 10%;
			}
			div.left_arrow:hover, div.right_arrow:hover, p.header_date:hover {
			background-color: lightgrey;
			cursor: pointer;
			}
			div.left_arrow input, div.right_arrow input {
			font-weight: bold;
			font-size: 40px;
			background: none;
			border: none;
			width: 100%;
			height: 100%;
			padding: 0px;
			margin: 0px;
			}
			div.main {
			margin-top: 160px;
			}
			
			/* //// SCHEDULE STYLE //// */

			.employee_name, .employee_select { 
			margin: 0px;
			width: 100%;
			font-family: "Comic Sans MS";
			font-size: 70px;
			text-align: center;
			}
			table { 
			border-collapse: collapse;
			width: 100%;
			}
			td { 
			background-color: lightgrey;
			border: 3px solid black;
			margin: 0px;
			padding: 0px;
			overflow:hidden;
			}
			td.hour {
			width: 12%;
			height: 160px;
			border: 5px solid black;
			}
			p.hour {
			margin: 0px;
			font-family: "Comic Sans MS";
			font-size: 100px;
			text-align: center;
			}
			.AM, .PM { 
			height: 40px;
			margin: 0px;
			font-family: "Comic Sans MS";
			font-size: 50px;
			text-align: center;
			}
			
			
			td.cell_0, td.cell_30 {
			background-color: white;
			}
			td.cell_0, td.cell_15, td.cell_30, td.cell_45 {
			border-right: 5px solid black;
			border-left: 5px solid black;
			}
			td.cell_0 {
			border-top: 5px solid black;
			}
			td.cell_45 {
			border-bottom: 5px solid black;
			}
			
			p.cell_min {
			display: inline-block;
			width: 42px;
			height: 100px;
			margin: 0px;
			margin-left: 5px;
			font-family: "Comic Sans MS";
			font-size: 60px;
			}
			p.cell_write {
			postition: absolute;
			width: 100%;
			height: 1px;
			margin: 0px;
			margin-left: 35px;
			float: left;
			color: blue; 
			text-align: center;
			font-family: handWrite;
			font-size: 60px;
			background-color: green;
			}			
			p.cell_right {
			text-align: right;
			padding: 0px 3px 2px 0px;
			}
			p.cell_left {
			text-align: left;
			padding: 0px 0px 2px 3px;
			}
			.write_left, .write_right, .write_left_out, .write_right_out {
			
			}
			.write_left_out, .write_right_out {
			color: black;
			}
			.write_right, .write_right_out {
			margin-left: 2px;
			}
			.write_left, .write_left_out {
			margin-left: 42px;
			}
			
		</style>
		<script>
			$(document).ready(function(){
				
				//// DATE HEADER CLICK ////
				$(".date_header").click(function(){
					$(this).hide();
					$(".date_input").show();
					$(".date_input").focus();
				});
				//// DATE HEADER INPUT CHANGED ////
				$(".date_input").change(function() {
					$(".submit_date").click();
				});
				//// DATE HEADER INPUT BLURRED ////
				$(".date_input").blur(function() {
					$(this).hide();
					$(".date_header").show();
				});
				//// EMPLOYEE NAME ////
				$(".employee_name").click(function() {
					$(this).hide();
					$(".employee_form").show();
					$(".employee_select").focus();
				});
				//// EMPLOYEE SELECT CHANGE ///
				$(".employee_select").change(function() {
					$(".changeEmployee").click();
				})
				//// EMPLOYEE SELECT BLUR ///
				$(".employee_select").blur(function() {
					$(".employee_form").hide();
					$(".employee_name").show();
				});
			});
			
			alert($(window).width());
			/*  //// AJAX EXAMPLE ////
			$.ajax({
				url:"ajax/employee_select.php",
				success:function(result){
					$(".employee_select").html(result)							
				}
			});	
			*/
		</script>
	</head>
	<body>
		<header>
			<form class="header_form" method="POST" action="<?php echo htmlentities($_SERVER['SCRIPT_NAME']) ?>" >
				<div class="left_arrow">
					<input type="submit" name="previous" value='<' />
				</div>
				<div class="date_center">
					<p class='date_header' ><?php echo date('l, F j, Y',strtotime($date)) ?></p>	
					<input class='date_input' name='date' type='date' style='color:black;display:none' value='<?php echo $date ?>' />
					<input class="submit_date" type="submit" name="changeDate" value="Change Date" style="display:none" />
				</div>
				<div class="right_arrow">
					<input type="submit" name="next" value=">" />
				</div>	
				<div style="clear:both;">
				</div>
			</form>	
		</header>
		<div class="main"> 	<?php  //print_r($_POST);  ?>
			<table>
				<tbody>
					<tr>
						<th colspan='2'><?php get_employee_header(0); ?></th>
					</tr>
					<?PHP
						// loops through each hour and ouputs the cells
						for($hour = 8; $hour < 18; $hour+=1)
						{
							for($min = 0; $min < 60; $min+=15)
							{
								$time = ($hour < 10 ? '0'.$hour : $hour).':'.($min < 10 ? '0'.$min : $min).":00";
								echo "<tr>\n";
								if($min == 0) echo get_cell_hours($hour);
								echo get_cell(0,$date,$time,'left');				
								echo "</tr>\n";
							}
						}
						
					?>
				</tbody>
			</table>
		</div>
	
		<div class='cover' id='edit_cover' onclick="edit_cancel()" <?php echo $edit_style ?> ></div>
		<div class='form_edit_appointment' id="edit_form_div" <?php echo $edit_style ?> >
			<h2>Edit Appointment</h2>
			<form action="<?php echo $file_name ?>.php" method="post" id="edit_form">
				<input type="hidden" name="appointment_id" id="edit_appointment_id" value="<?php echo $edit_appointment_id ?>" />
				<label>Client:</label>		
				<input type="text" name="client" value="Alan" id="edit_client" value="<?php echo $edit_client ?>" /><br>
				<label>Employee:</label>	
				<select name="employee_id" id="edit_employee">
<?php
	$result = $database->query("
		SELECT employee_id, name_first FROM employee
	");
	while($result && $row = $result->fetch_assoc()) {
		if($row['employee_id'] == $edit_employee_id)  
			$selected = 'selected';
		else
			$selected = '';
			
		echo "<option value='$row[employee_id]' $selected>$row[name_first]</option>";
	}
?>
				</select><br>
				<label>Date:</label>		
				<input type="date" name="date" value="<?php echo $date ?>" id="edit_date" /><br>
				<label>Start Time:</label>	
				<input type="time" name="start_time" id="edit_start_time" value="<?php echo $edit_start_time ?>" /><br>
				<label>End Time:</label>	
				<input type="time" name="end_time" id="edit_end_time" value="<?php echo $edit_end_time ?>" /><br>
				<?php if($error_string) echo "<p>$error_string</p>" ?>
				<input type="submit" class="edit_submit" name="submit" value="Edit Appointment" /> <br/>
				<input type="submit" class="edit_submit" name="submit" value="Delete Appointment" /> <br/>
				<input type="button" class="edit_cancel" id="cancel" value="Cancel" onclick="edit_cancel()" />
			</form>
		</div>
	</body>
</html>