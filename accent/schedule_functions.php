<?php 

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
				<p class='write_{$pos}_out')\">x</p>
				<p class='cell_{$pos}' >{$min}</p>
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

		if($count == 0)
			return "
				<td class='cell_{$min}' >
					<p class='write_{$pos}' style='height:{$height}px' onclick=\"edit_appointment('{$appointment_id}','{$client}','{$employee_id}','{$date}','{$start_time}','{$end_time}')\">{$client_name}</p>
					<p class='cell_{$pos}' >{$min}</p>
				</td>
			";
		else
			return "
				<td class='cell_{$min}' >
					<p class='cell_{$pos}' onclick=\"edit_appointment('{$appointment_id}','{$client}','{$employee_id}','{$date}','{$start_time}','{$end_time}')\" >{$min}</p>
				</td>
			";
	}
	else
	{	
		return "
			<td class='cell_{$min}' >
				<form action='schedule.php' method='post'>
					<p class='cell_{$pos}' onclick='new_appointment(this,\"{$time_str}\",\"{$employee_id}\")'>{$min}</p>
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

function get_cell_names($schedule_col, $schedule_names, $day_of_week, $date)
{ 
	global $connection; // database connection in schedule_database

	$employee_name = $schedule_names[$schedule_col]['employee_name'] != '' ? $schedule_names[$schedule_col]['employee_name'] : "_____";
	echo "
		<th>
			<form action='{$file_name}' method='post' style='width:100%;height:100%'>
				<p onclick='change_employee(this)' style='width:100%;height:100%;margin:0px' >{$employee_name}</p>
				<input type='hidden' name='date' value='{$date}' />
				<input type='hidden' name='schedule_col' value='{$schedule_col}' />
				<input type='hidden' name='day_of_week' value='{$day_of_week}' />
				<input type='hidden' name='old_employee_id' value='{$schedule_names[$schedule_col]['employee_id']}' />
				<select name='employee_id' class='select_employee' onchange='employee_changed(this)' style='display:none'>
					<option value='none'>None</option>";
					$result = $connection->query("
						SELECT employee_id, name_first FROM employee
					");
					
					while($result && $row = $result->fetch_assoc()) {
						if($row['employee_id'] == $schedule_names[$schedule_col]['employee_id'])
							$selected = 'selected';
						else
							$selected = '';
						
						echo "<option value='$row[employee_id]' $selected>$row[name_first]</option>";
					}
				
echo "			</select>
				<input type='submit' name='submit' value='Change Employee' style='display:none' />
			</form>
		</th>
	";
}

?>