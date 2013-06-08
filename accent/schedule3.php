<!DOCTYPE html>
<?php 
	$file_name = "schedule3";

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
	
	function get_cell($col, $time, $pos)
	{
		global $appointment;
		global $schedule_names;
		
		$hour = intval($time/100);
		$min = $time%100;
				
		if($appointment[$col][$hour][$min]['appointment_id']) 
		{
			$appointment_id = $appointment[$col][$hour][$min]['appointment_id'];
			$client = $appointment[$col][$hour][$min]['client'];
			$employee_id = $appointment[$col][$hour][$min]['employee_id'];
			$employee_name = $appointment[$col][$hour][$min]['employee_name'];
			$date = $appointment[$col][$hour][$min]['date'];
			$start_time = $appointment[$col][$hour][$min]['start_time'];
			$end_time = $appointment[$col][$hour][$min]['end_time'];
			
			
			
			return "
				<td class='cell_{$min}' >
					<p class='write_{$pos}' onclick='edit_appointment(\"{$appointment_id}\",\"{$client}\",\"{$employee_id}\",\"{$date}\",\"{$start_time}\",\"{$end_time}\")'>{$client}</p>
					<p class='cell_{$pos}' >{$min}</p>
				</td>
			";
		}
		else
		{
			$employee_id = $schedule_names[$col]['employee_id'];
			return "
				<td class='cell_{$min}' onclick='new_appointment(this,\"{$time}\",\"{$employee_id}\")'>
					<p class='cell_{$pos}' >{$min}</p>
				</td>
			";
		}
	}
	
	function get_cell_hours($hour)
	{
		//convert from 24 hours to 12 hours
		$hour = ($hour-1)%12+1;
		$am = $hour > 7 ? 'AM' : 'PM';
		
		return "<td class='hour' rowspan='4'>
					<p class='hour'>$hour</p>
					<p class='$am'>$am</p>
				</td>";
	}
?>
<html lang="en">
	<head>
		<title>Schedule</title>
		<link rel="stylesheet" type="text/css" href="<?php echo $file_name ?>_style.css">
		<script type="text/javascript" src="<?php echo $file_name ?>.js"></script>
		<meta charset="UTF-8">
	</head>
	<body>
		<form action="<?php echo $file_name ?>.php" method="post" id="main_form">
			<header>
					<div class="header_left">
						<img class='logo_left' src='data/accent_salon.jpg' alt='Accent Logo' />
					</div>
					<div class="header_center">
						<div class="left_arrow">
							<input type="submit" name="submit" value='<' />
						</div>
						<div class="date_center">
							<p id='header_date' class='header_date' onclick='click_header_date("<?php echo $date ?>")' ><?php echo $date_str ?></p>	
							<input id='change_date' name='date' class='change_date' type='hidden' value='<?php echo $date ?>' onchange='date_changed(this)' />
						</div>
						<div class="right_arrow">
							<input type="submit" name="submit" value=">" />
						</div>	

					</div>
					<div class="header_right">
						<img class='logo_right' src='data/accent_salon.jpg' alt='Accent Logo' />
					</div>
					
					
				
			</header>
			
			<table>
				<tbody>
					<tr>
						<th class="col"><?php echo $schedule_names[0]['employee_name'] ?></th>
						<th></th>
						<th class="col"><?php echo $schedule_names[1]['employee_name'] ?></th>
						<th class="col"><?php echo $schedule_names[2]['employee_name'] ?></th>
						<th></th>
						<th class="col"><?php echo $schedule_names[3]['employee_name'] ?></th>
						<th class="col"><?php echo $schedule_names[4]['employee_name'] ?></th>
						<th></th>
						<th class="col"><?php echo $schedule_names[5]['employee_name'] ?></th>
						<th class="col"><?php echo $schedule_names[6]['employee_name'] ?></th>
						<th></th>
						<th class="col"><?php echo $schedule_names[7]['employee_name'] ?></th>
					</tr>
					<?PHP
						// loops through each hour and ouputs the cells
						for($hour = 8; $hour < 18; $hour+=1)
						{
							for($min = 0; $min < 60; $min+=15)
							{
								$time = $hour*100+$min;
								echo "<tr>\n";
								echo get_cell(0,$time,'right');
								if($min == 0) echo get_cell_hours($hour);
								echo get_cell(1,$time,'left');
								echo get_cell(2,$time,'right');
								if($min == 0) echo get_cell_hours($hour);
								echo get_cell(3,$time,'left');
								echo get_cell(4,$time,'right');
								if($min == 0) echo get_cell_hours($hour);
								echo get_cell(5,$time,'left');
								echo get_cell(6,$time,'right');
								if($min == 0) echo get_cell_hours($hour);
								echo get_cell(7,$time,'left');					
								echo "</tr>\n";
							}
						}
						
					?>
				</tbody>
			</table>
		</form>
		
		
		<div class='cover' id='edit_cover' onclick="edit_cancel()"></div>
		<div class='form_edit_appointment' id="edit_form_div">
			<h2>Edit Appointment</h2>
			<form action="<?php echo $file_name ?>.php" method="post" id="edit_form">
											<input type="hidden" name="appointment_id" id="edit_appointment_id" />
				<label>Client:</label>		<input type="text" name="client" value="Alan" id="edit_client" /><br>
				<label>Employee:</label>	<select name="employee_id" id="edit_employee">
											<?php	
												foreach($schedule_names as $employee) 
													echo "<option value='".$employee['employee_id']."'>".$employee['employee_name']."</option>";
											?>
											</select><br>
				<label>Date:</label>		<input type="date" name="date" value="<?php echo $date ?>" id="edit_date" /><br>
				<label>Start Time:</label>	<input type="time" name="start_time" id="edit_start_time" /><br>
				<label>End Time:</label>	<input type="time" name="end_time" id="edit_end_time" /><br>
				<br/>
				<input type="submit" class="edit_submit" name="submit" value="Edit Appointment" />
				<input type="button" class="edit_cancel" id="cancel" value="Cancel" onclick="edit_cancel()" />
			</form>
		</div>
	</body>
</html>