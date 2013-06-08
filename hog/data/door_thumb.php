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

	$result = mysql_query("SELECT * FROM door_thumbs WHERE style='{$_REQUEST['style']}'");
	
	if($result)
	{
		$row = mysql_fetch_array($result);
	
		header("Content-type: image/jpeg");	
		echo $row['thumb'];
	}
	
	mysql_close($con);

?>