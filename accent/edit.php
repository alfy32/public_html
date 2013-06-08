<?php print_r($_POST);
	require_once 'includes/constants.php';

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	if($conn->connect_error)
		die("<h3>Database Error: $this->conn->connect_error</h3>");
			
	//// WHEN ACCESSED BY AJAX ////
	if(isset($_POST['editAjax'])) {
					
		$appointmentId = $_POST['appointmentId'];
		
		$result = $conn->query("
			SELECT * FROM appointment 
			WHERE appointment_id=$appointmentId
		");
		
		$row = $result->fetch_assoc();
		
		$appointmentId = $row['appointment_id'];
		$client = $row['client'];
		$employeeId = $row['employee_id'];
		$date = $row['date'];
		$startTime = $row['start_time'];
		$endTime = $row['end_time'];
		
		$showEdit = true;
	}
	else {	
?>
<div class='editCover' <?php if(isset($coverStyle)) echo $coverStyle ?> ></div>
<div class='editFormDiv'>
<?php
	}
	//// WHEN ACCESED ON EDIT FAIL STAY AS IS ////
	if(isset($showEdit) && $showEdit === true){
?>
	<div class="editForm">
		<h2>Edit Appointment</h2>
		<form action="" method="POST" >
			<input type="hidden" name="appointmentId" value="<?php echo $appointmentId ?>" />
			<label>Client:</label>		
			<input type="text" name="client" value="<?php echo $client ?>" /><br>
			<label>Employee:</label>	
			<select name="employeeId" id="edit_employee">
			<?php
				$result = $conn->query("
					SELECT employee_id, name_first FROM employee
				");
				while($result && $row2 = $result->fetch_assoc()) {
					if($row2['employee_id'] == $employeeId)  
						$selected = 'selected';
					else
						$selected = '';
						
					echo "<option value='$row2[employee_id]' $selected>$row2[name_first]</option>";
				}
			?>
			</select><br>
			<label>Date:</label>		
			<input type="date" name="date" value="<?php echo $date ?>" /><br>
			<label>Start Time:</label>	
			<input type="time" name="startTime" value="<?php echo $startTime ?>" /><br>
			<label>End Time:</label>	
			<input type="time" name="endTime" value="<?php echo $endTime ?>" /><br>
			<?php echo "<p>$mysql->editError</p>" ?>
			<input class="edit_submit" type="submit"  name="editAppointment" value="Edit Appointment" /> <br/>
			<input class="edit_submit" type="submit"  name="submit" value="Delete Appointment" /> <br/>
			<input class="edit_cancel" type="button"  value="Cancel" onclick="editCancel()" />
		</form>
	</div>
<?php
	}
	if(!isset($_POST['editAjax'])) {
?>
</div>
<?php
	}
?>