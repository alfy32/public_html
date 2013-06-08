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
							"WHERE list='{$_REQUEST['list']}'");
							
	if($result)
	{
		while($row = mysql_fetch_array($result))
		{
			echo "<option value='{$row['id']}'>{$row['label']}</option>";
		}
	}
	
	mysql_close($con);

?>