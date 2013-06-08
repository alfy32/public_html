<?php 
	require_once 'classes/Authenticate.php';	
	
	//// LOGIN SETUP ////
	$authentication = new Authenticate();
	$authentication->confirmMember();
	
	require_once 'includes/mobile.php';
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Accent Schedule</title>
		<link rel="stylesheet" type="text/css" href="css/mobile.css">
		<meta charset="UTF-8">
		<meta name="viewport" content="width=480; initial-scale=1.0; user-scalable=false;" />
		
		<!-- TO DISABLE CHACHING ON SAFARI -->
		<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="pragma" content="no-cache">
		<!-- 							   -->
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/mobile.js"></script>
	</head>
	<body>
		<header>
			<form method="POST" action="<?php echo htmlentities($_SERVER['SCRIPT_NAME']) ?>" >
				<div class="leftArrow"><input type="submit" name="leftArrow" value="<" /></div>
				<div class="dateCenter">
					<p class="dateHeader"><?php echo date("l, M j, Y", strtotime($date)); ?></p>	
					<input class="dateInput" name="date" type="date" style="display:none" value="<?php echo $date ?>" />
					<input class="dateSubmit" type="submit" name="changeDate" value="Change Date" style="display:none" />
				</div>
				<div class="rightArrow"><input type="submit" name="rightArrow" value=">" /></div>
			</form>	
		</header>
		<form class="employee" method="POST" action="<?php echo htmlentities($_SERVER['SCRIPT_NAME']); ?>" >
			<input type="hidden" name="date" value="<?php echo $date; ?>" />
			<p class='employeeName'><?php echo $employeeName;?></p>
			<select class='employeeSelect' name='employeeId' style="display:none">
			<?php
				$result = $database->query("SELECT employee_id, name_first FROM employee");

				while($result && $row = $result->fetch_assoc()){
					if($row['employee_id'] == $employeeId)
						$selected = "selected";
					else
						$selected = '';
						
					echo "<option value='$row[employee_id]' $selected>$row[name_first]</option>";
				}
			?>
			</select>
			<input class='changeEmployee' type='submit' name='changeEmployee' value='Change Employee' style='display:none' />
		</form>
		<div class="main"> 	<?php  //print_r($_POST);  ?>
			<table>
				<tbody>
					<?PHP
						// loops through each hour and ouputs the cells
						for($hour = 8; $hour < 18; $hour+=1)
						{
							for($min = 0; $min < 60; $min+=15)
							{
								$time = ($hour < 10 ? '0'.$hour : $hour).':'.($min < 10 ? '0'.$min : $min).":00";
								echo "<tr>\n";
								if($min == 0) echo getHourCell($hour);
								echo getCell(0,$date,$time,'left');				
								echo "</tr>\n";
							}
						}
						
					?>
				</tbody>
			</table>
		</div>
	</body>
</html>