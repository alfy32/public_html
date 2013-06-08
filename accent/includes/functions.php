<?php

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