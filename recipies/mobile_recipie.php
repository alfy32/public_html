<?PHP

	$host = "localhost";
	$user = "alan_alan";
	$password = "ze3^717?0F-e";
	
	$table = "recipie";
	$database = "alan_recipies";

	$con = mysql_connect($host, $user, $password);

	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}

	mysql_select_db($database, $con);

	$result = mysql_query("SELECT * FROM {$table}");
	$row = mysql_fetch_array($result);
	
	$owner = $row['owner'];
	$name = $row['name'];
	$directions = $row['directions'];
	
	$id = $row['recipie_id'];
	
	$result = mysql_query("SELECT amount FROM recipie_item WHERE recipie_id={$id}");
	$rows = mysql_num_rows($result);
	
	for($i = 0; $i < $rows/2; $i=$i+1)
	{
		$row = mysql_fetch_array($result);
		
		$ingredients1 = $ingredients1 . $row['amount'] . "<br>";
	}	
	for($i = $rows/2; $i < $rows; $i=$i+1)
	{
		$row = mysql_fetch_array($result);
		
		$ingredients2 = $ingredients2 . $row['amount'] . "<br>";
	}		
	
	mysql_close($con);
	
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="recipie_card.css">
	</head>
	
	<body>
	

		<div class="owner">
			<?PHP echo $owner; ?>
		</div>
		<div class="name">
			<?PHP echo $name; ?>
		</div>
		<div class="ingredients">
			
			<div class="ingredients1">
				<?PHP echo $ingredients1; ?>
			</div>
			<div class="ingredients2">
				<?PHP echo $ingredients2; ?>
			</div>
		</div>
		<div class="seperator"></div>
		<div class="directions">
			<?PHP echo $directions; ?>
		</div>
	</body>
</html>