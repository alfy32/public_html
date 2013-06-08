<?php
	define(HOST, "localhost");
	define(USER, "alan_alan");
	define(PASSWORD, "ze3^717?0F-e");
	define(DATABASE, "alan_salon");
	
	$database = new mysqli(HOST, USER, PASSWORD, DATABASE);
	if($database->connect_error)
		die("<h3>Database Error: $database->connect_error</h3>");
		
	$result = $database->query("SELECT employee_id, name_first FROM employee");
	
	while($result && $row = $result->fetch_assoc()) 
		echo "<option value='$row[employee_id]'>$row[name_first]</option>";
?>