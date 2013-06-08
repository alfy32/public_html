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

	$result = mysql_query( 	"SELECT * FROM pricelist " .
							"WHERE list = 'DOOR_SWING_{$_REQUEST['type']}'");
	
	echo "<option></option>";
	
	$optgroup = false;
	
	if($result)
	{
		while($row = mysql_fetch_array($result))
		{
			if($row['type'] == 'SECTION_HEADING')
			{
				if($optgroup)
					echo "</optgroup>";
					
				echo "<optgroup label='{$row['label']}'>";
				
				$optgroup = true;
			}
			else
				echo "<option value='{$row['single']}'>{$row['label']}</option>";
		}
	}
	
	if($optgroup)
		echo "</optgroup>";
	
	mysql_close($con);

?>