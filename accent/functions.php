<?php 
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