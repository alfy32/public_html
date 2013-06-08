<?php

	$database = new mysqli(HOST, USER, PASSWORD, DATABASE);
	if($database->connect_error)
		die("<h3>Database Error: $database->connect_error</h3>");
	
?>