<!DOCTYPE html>
<?php
	define(HOST, "localhost");
	define(USER, "alan_accent");
	define(PASSWORD, "IdrB*@&T[M}w");
	define(DATABASE, "alan_accent");
	
	$database = new mysqli(HOST, USER, PASSWORD, DATABASE);
	if($database->connect_error)
		die("<h3>Database Error: $database->connect_error</h3>");
?>
<html lang='en'>
	<head>
		<title>Employee Manager</title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div class="employeeList">
			<input type='submit' name='addNew' value="New" />
			<table>
				<tr>
					<th>Last, First</th>
				</tr>
				<?php
					$result = $database->query("
						SELECT id, firstName, lastName FROM employee
					");
					
					while($result && $row = $result->fetch_assoc())
						echo "<tr><td>$row[lastName], $row[firstName]</td></tr>";
				?>
			</table>
		</div>
	</body>
</html>