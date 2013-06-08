<!DOCTYPE html>
<html>
	<head>
		<title>Schedule</title>
		<link rel="stylesheet" type="text/css" href="schedule_style.css">
		<meta charset="UTF-8">
	</head>
	<body>
		<table>
			<tbody>
				<tr>
					<th>Jill</th>
					<th></th>
				</tr>
				<?PHP
				$hr = 8;
				$mn = 15;
				$hour = 8;
				
					echo "<tr>\n";
					
					//col 0
					echo "<td>\n";
					for($min = 0; $min < 4; $min+=1)
					{
						if($hour == $hr && $min*15 == $mn) 
							echo "<p class='write'>Alan Christensen</p>\n";
						echo "<p onclick=\"show_time(0,'".(($hour-1)%12+1).":".($min*15)."')\" class='rmin".$min*15 ."'>".$min*15 ."</p>\n";
					}
					echo "</td>\n";
					
					//hours column
					echo "<td>\n";
					echo "<p class='hour'>".(($hour-1)%12+1)."</p>\n";
					if($hour > 7)
						echo "<p class='am'>AM</p>\n";
					else
						echo "<p class='pm'>PM</p>\n";
					echo "</td>\n";
					
				
				?>
			</tbody>
		</table>
		
	</body>
</html>

<script>

	function show_time(col,time)
	{
		$str = "Col: " + col + " Time: " + time;
		alert($str);
	}
</script>