<!DOCTYPE html>
<html lang='en'>
	<head>
		<title>WCCR</title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" type="text/css" href="style.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  
		<script type="text/javascript" src="javascript.js"></script>
	</head>
	<body>
		<?php include_once 'calorieTable.php'; ?>
		
		<table>
			<caption>WCCR</caption>
			<tbody class="wccr">
				<tr class="1">
					<th>Item</th>
					<th>Gross Wt</th>
					<th>Tare</th>
					<th>Net Wt</th>
					<th>cal/g</th>
					<th>cal</th>
				</tr>
				<tr class="2">
					<th class='item'>wheaties</th>
					<td class='gross'>70</td>
					<td class='tare'></td>
					<td class='net'>70</td>
					<td class='calg'>3.70</td>
					<td class='cal'>259.0</td>
				</tr>
				<tr class="3">
					<th class='item'></th>
					<td class='gross'></td>
					<td class='tare'></td>
					<td class='net'></td>
					<td class='calg'></td>
					<td class='cal'></td>
				</tr>
				<tr class="4">
					<th class='item'></th>
					<td class='gross'></td>
					<td class='tare'></td>
					<td class='net'></td>
					<td class='calg'></td>
					<td class='cal'></td>
				</tr>
				<tr class="5">
					<th class='item'></th>
					<td class='gross'></td>
					<td class='tare'></td>
					<td class='net'></td>
					<td class='calg'></td>
					<td class='cal'></td>
				</tr>
				<tr class="6">
					<th class='item'></th>
					<td class='gross'></td>
					<td class='tare'></td>
					<td class='net'></td>
					<td class='calg'></td>
					<td class='cal'></td>
				</tr>
				<tr class="7">
					<th class='item'></th>
					<td class='gross'></td>
					<td class='tare'></td>
					<td class='net'></td>
					<td class='calg'></td>
					<td class='cal'></td>
				</tr>
			</tbody>
		</table>
		<?php include_once 'calorieTableOptions.php'; ?>
		<!--<button onclick="addRow()">Add Row</button>-->
		
		<table>
			<caption>Totals</caption>
			<tbody class="totals">
				<tr>
					<th></th>
					<th>Budgeted</th>
					<th>Totals</th>
					<th>Balance</th>
				</tr>
				<tr class="dailyTotal">
					<th>Daily Total Cal</th>
					<td class="budgeted">2300</td>
					<td class="totals"></td>
					<td class="balance"></td>
				</tr>
				<tr class="weeklyTotal">
					<th>Cumulative Weekly Total</th>
					<td class="budgeted"></td>
					<td class="totals"></td>
					<td class="balance"></td>
				</tr>	
			</tbody>
		</table>
	</body>
</html>