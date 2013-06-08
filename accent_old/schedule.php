<!DOCTYPE html>
<?php //print_r($_POST); echo "<br/>";
	// keeps track of the current file name for the forms
	$file_name = "schedule";

	$date = date('Y-m-d');
	$date_str = date('l, F j, Y');
	$sql_date = " WHERE date='{$date}'";
	$day_of_week = date('l', strtotime($date));
	
	// arrays that will be filled with the appointment data
	// and the employee names.
	$appointment = array();
	$schedule_names = array();

	include_once "schedule_database.php";
	include_once "schedule_functions.php";
?>
<html lang="en">
	<head>
		<title>Schedule</title>
		<link rel="stylesheet" type="text/css" href="<?php echo $file_name ?>_style.css">
		<script type="text/javascript" src="<?php echo $file_name ?>.js"></script>
		<meta charset="UTF-8">
	</head>
	<body>
		<header>
			<form action="<?php echo $file_name ?>.php" method="post" id="header_form">
				<div class="header_left">
					<a href="<?php echo $file_name ?>.php">
					<img class='logo_left' src='images/accent_salon.jpg' alt='Accent Logo' />
					</a>
				</div>
				<div class="header_center">
					<div class="left_arrow">
						<input type="submit" name="submit" value='<' />
					</div>
					<div class="date_center">
						<p id='header_date' class='header_date' onclick='click_header_date("<?php echo $date ?>")' ><?php echo $date_str ?></p>	
						<input id='change_date' name='date' class='header_date' type='date' style='display:none' value='<?php echo $date ?>' onkeypress='date_keypress(this,event)' onchange='date_changed(this,event)'/>
						<input type="submit" name="submit" value="Change Date" id="submit_date" style="display:none" />
					</div>
					<div class="right_arrow">
						<input type="submit" name="submit" value=">" />
					</div>	
				</div>
				<div class="header_right">
					<a href="<?php echo $file_name ?>.php">
					<img class='logo_right' src='images/accent_salon.jpg' alt='Accent Logo' />
					</a>
				</div>
			</form>	
		</header>
		<!--<form action="<?php echo $file_name ?>.php" method="post" id="table_form">-->
			<input type="hidden" name="date" value="<?php echo $date ?>" />
			<table>
				<tbody>
					<tr>
						<?php get_cell_names(0, $schedule_names, $day_of_week, $date); ?>
						<th></th>
						<?php get_cell_names(1, $schedule_names, $day_of_week, $date); ?>
						<?php get_cell_names(2, $schedule_names, $day_of_week, $date); ?>
						<th></th>
						<?php get_cell_names(3, $schedule_names, $day_of_week, $date); ?>
						<?php get_cell_names(4, $schedule_names, $day_of_week, $date); ?>
						<th></th>
						<?php get_cell_names(5, $schedule_names, $day_of_week, $date); ?>
						<?php get_cell_names(6, $schedule_names, $day_of_week, $date); ?>
						<th></th>
						<?php get_cell_names(7, $schedule_names, $day_of_week, $date); ?>
					</tr>
					<?PHP
						// loops through each hour and ouputs the cells
						for($hour = 8; $hour < 18; $hour+=1)
						{
							for($min = 0; $min < 60; $min+=15)
							{
								$time = ($hour < 10 ? '0'.$hour : $hour).':'.($min < 10 ? '0'.$min : $min).":00";
								echo "<tr>\n";
								echo get_cell(0,$date,$time,'right');
								if($min == 0) echo get_cell_hours($hour);
								echo get_cell(1,$date,$time,'left');
								echo get_cell(2,$date,$time,'right');
								if($min == 0) echo get_cell_hours($hour);
								echo get_cell(3,$date,$time,'left');
								echo get_cell(4,$date,$time,'right');
								if($min == 0) echo get_cell_hours($hour);
								echo get_cell(5,$date,$time,'left');
								echo get_cell(6,$date,$time,'right');
								if($min == 0) echo get_cell_hours($hour);
								echo get_cell(7,$date,$time,'left');					
								echo "</tr>\n";
							}
						}
						
					?>
				</tbody>
			</table>
		<!--</form>-->
	
		<div class='cover' id='edit_cover' onclick="edit_cancel()" <?php echo $edit_style ?> ></div>
		<div class='form_edit_appointment' id="edit_form_div" <?php echo $edit_style ?> >
			<h2>Edit Appointment</h2>
			<form action="" method="post" id="edit_form">
				<input type="hidden" name="appointment_id" id="edit_appointment_id" value="<?php echo $edit_appointment_id ?>" />
				<label>Client:</label>		
				<input type="text" name="client" value="Alan" id="edit_client" value="<?php echo $edit_client ?>" /><br>
				<label>Employee:</label>	
				<select name="employee_id" id="edit_employee">
<?php
	$result = $connection->query("
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