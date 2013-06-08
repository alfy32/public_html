<?php

	function get_cell($col, $hour, $pos)
	{
		global $appointment;
		global $schedule_names;
		
		$cell_str = "<td>\n";
		
		for($min = 0; $min < 60; $min+=15)
		{
			$employee_id = $schedule_names[$col]['id'];
			$employee_name = $schedule_names[$col]['name_first'];
			$time = $min == 0 ? $hour.':00' : $hour.':'.$min;
				
			if($appointment[$col][$hour][$min]['name']) 
			{
				$name = $appointment[$col][$hour][$min]['name'];
				$id = $appointment[$col][$hour][$min]['id'];
				
				$cell_str .= "<p class='write_{$pos}' onclick='edit_appointment(\"{$id}\",\"{$name}\",\"{$time}\",\"{$employee_name}\")'>{$name}</p>\n";
				
				$cell_str .= "<p class='{$pos}_min{$min}' onclick='edit_appointment(\"{$id}\",\"{$name}\",\"{$time}\",\"{$employee_name}\")'>{$min}</p>\n";
			}
			else
				$cell_str .= "<p class='{$pos}_min{$min}' onclick='new_appointment(\"{$time}\",\"{$employee_id}\")'>{$min}</p>\n";
		}
		$cell_str .= "</td>\n";
		
		return $cell_str;
	}
	
	function get_cell_hours($hour)
	{
		//convert from 24 hours to 12 hours
		$hour = ($hour-1)%12+1;
		$am = $hour > 7 ? 'AM' : 'PM';
		
		return "<td>
					<p class='hour'>$hour</p>
					<p class='$am'>$am</p>
				</td>";
	}