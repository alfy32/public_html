<td class='cell_{$min}' >
				<p class='cell_{$pos}' >{$min}</p>
				<form action='schedule.php' method='post'>
					<input type='text' name='client' />
					<input type='hidden' name='employee_id' />
					<input type='hidden' name='appointment_date' />
					<input type='hidden' name='start_time' />
					<input type='submit' name='submit' value='New Appointment' style='display:none'/>
				</form>
			</td>