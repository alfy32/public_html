<?PHP
	
	$host = "localhost";
	$user = "alan_alan";
	$password = "ze3^717?0F-e";
	$database = "alan_hog";

	$con = mysql_connect($host, $user, $password);

	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}

	mysql_select_db($database, $con);

	$result = mysql_query( 	"SELECT * FROM door_sizes " .
							"INNER JOIN sizes " . 
							"ON door_sizes.size_id=sizes.sizes_id " .
							"WHERE door_sizes.style='{$_REQUEST['style']}'");
							
	echo "<option></option>";
	
	if($result)
	{
		while($row = mysql_fetch_array($result))
		{
			echo "<option value='{$row['single']}'>{$row['size']}</option>";
		}
	}
	
	mysql_close($con);

?>