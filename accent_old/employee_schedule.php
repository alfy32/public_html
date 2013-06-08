<!DOCTYPE html>
<?php
	define(HOST, "localhost");
	define(USER, "alan_alan");
	define(PASSWORD, "ze3^717?0F-e");
	define(DATABASE, "alan_salon");
	
	$data = new mysqli(HOST, USER, PASSWORD, DATABASE);
	if($data->connect_error)
		die("<h3>Database Error: $data->connect_error</h3>");
		
?>
<html lang='en'>
	<head>
		<title>Employee Schedules</title>
		<meta charset="UTF-8" />
	</head>
	<body>
		<h2>Enter Employee Schedules</h2>
		<table>
			<tr>
				<th>Name</th>
				<th colspan=2>Sunday</th>
				<th colspan=2>Monday</th>
				<th colspan=2>Tuesday</th>
				<th colspan=2>Wednessday</th>
				<th colspan=2>Thursday</th>
				<th colspan=2>Friday</th>
				<th colspan=2>Saturday</th>
			</tr>
<?php
	$result = $data->query("
		SELECT employee_id, name_first
		FROM employee
	");
	
	while($row = $result->fetch_assoc()) {
		echo "<tr>";
		echo "<td>$row[name_first]</td>";
		echo "</tr>";
	}
?>
		</table>
	</body>
</html>