<?PHP
	
$host = "localhost";
$user = "alan_alan";
$password = "ze3^717?0F-e";

$table = "category";
$database = "alan_recipies";

$con = mysql_connect($host, $user, $password);

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

mysql_select_db($database, $con);

$result = mysql_query("SELECT * FROM {$table}");

while($row = mysql_fetch_array($result))
{
	echo "<option value='{$row['name']}'>{$row['name']}</option>";
}

mysql_close($con);

?>