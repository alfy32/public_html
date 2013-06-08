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
	
	echo "<option></option>";
	
	$result = mysql_query( 	"SELECT DISTINCT type FROM pricelist " .
							"WHERE list='JAMB_MATERIAL' ");
	
	if($result)
	{
		while($row = mysql_fetch_array($result))
		{
			echo "<option value='{$row['type']}'>{$row['type']}</option>"; 
		}
	}
	
	
	mysql_close($con);

?>