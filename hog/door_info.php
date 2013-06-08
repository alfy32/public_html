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

	$result = mysql_query("SELECT * FROM door_info WHERE style='{$_REQUEST['style']}'");
	
	$row = mysql_fetch_array($result);
	
	$style = $row['style'];
	$material = $row['material'];
	$design = $row['design'];
	$glass = $row['glass'];
	
	$result = mysql_query("SELECT * FROM door_thumbs WHERE style='{$_REQUEST['style']}'");

	$row = mysql_fetch_array($result);
	
	$thumb = $row['thumb'];

	mysql_close($con);

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Door Information</title>
		<link rel="stylesheet" type="text/css" href="door_info.css">
	</head>
	
	<body>
		<form class="form" action="door_info.php" method="get">
			<div class="left_side">
				<p class="door_image">
				<image  id="door_image" src="data/door_thumb.php?style=<?PHP echo $style ?>" width='130' height='251' />
				</p>
				
				<label class="door_style_label" id="door_style_label">Door Style</label>
				<input class="door_style" id="door_style" name="style" readonly value="<?PHP echo $style ?>">
				
				<label class="door_material_label" id="door_material_label">Door Material</label>
				<input class="door_material" id="door_material" readonly value="<?PHP echo $material ?>">
				
				<label class="glass_style_label" id="glass_style_label">Galss Style</label>
				<input class="glass_style" id="glass_style" readonly value="<?PHP echo $glass ?>">
				
				<label class="door_design_label" id="door_design_label">Door Design</label>
				<input class="door_design" id="door_design" readonly value="<?PHP echo $design ?>">
				
				<label class="door_cost_label" id="door_cost_label">Door Cost</label>
				<input class="door_cost" id="door_cost" readonly value="<?PHP echo $cost ?>">
			</div>	
			
			<div class="main">
			
				
				<div class="door">
					<p class="door_label">Door</p>
					<div class="door_box">
						<label class="door_size_label">Door Size</label>
						<select name="door_size" class="door_size" id="door_size" onchange="door_size_change()">
							
						</select>
						
						<label class="door_swing_label">Door Swing</label>
						<select class="door_swing" id="door_swing" onchange="update_cost()">
						</select>
						
						<label class="sill_label">Sill</label>
						<select class="sill" id="sill" onchange="update_cost()">
							<option></option>
						</select>
						
						<label class="hinge_color_label">Hinge Color</label>
						<select class="hinge_color" id="hinge_color" onchange="update_cost()">
						</select>
					</div>
				</div>
				
				<div class="customer_details">
					<p class="cutsomer_details_label">Customer Details</p>
					<div class="customer_details_box">
						<label class="door_location_label">Door Location</label>
						<input class="door_location" id="door_location" type="text">
						
						<label class="customer_label">Customer</label>
						<input class="customer" id="customer" type="text">
						
						<label class="job_label">Job</label>
						<input class="job" id="job" type="text">
						
						<label class="date_label">Date</label>
						<input class="date" id="date" type="text">
					</div>
				</div>
				
				<div class="jamb">
					<p class="jamb_label">Jamb</p>
					<div class="jamb_box">
						<label class="jamb_material_label">Jamb Material</label>
						<select class="jamb_material" id="jamb_material" onchange="jamb_material_change()">
						</select>
						
						<label class="jamb_size_label">Jamb Size</label>
						<select class="jamb_size" id="jamb_size" onchange="update_cost()">
						</select>
		
					</div>
				</div>
				
				<div class="notes">
					<p class="notes_label">Notes</p>
					<textarea class="notes_area">Notes</textarea>
				</div>
				
				<div class="other_options">
					<p class="other_options_label">Other Options</p> 
					<div class="other_options_box">
						<input type="checkbox">No Brickmould<br>
						<input type="checkbox">Deadbolt<br>
						<input type="checkbox">Dentil Shelf<br>
						<input type="checkbox">Outswing Sill<br>
						<input type="checkbox">ADA Sill<br>
						<input type="checkbox">Spring Hinge<br>
						<input type="checkbox">Cut Down<br>
		
					</div>
				</div>
				
				<div class="price">
					<p class="price_label">Price</p>
					<div class="price_box">
						<label class="cost_label">Cost</label>
						<input class="cost" type="text" readonly>
						
						<label class="percent_markup_label">Percent Markup</label>
						<input class="percent_markup" type="text">
						
						<label class="labor_cost_label">Labor Cost</label>
						<input class="labor_cost" type="text">
						
						<label class="taxes_percent_label">Taxes Percent</label>
						<input class="taxes_percent" type="text">
						
						<label class="total_label">Total</label>
						<input class="total" type="text">
					</div>
				</div>
			</div>
		</form>
	</body>
</html>

<script>
	
	update_door_size();
	update_sill();
	update_hinge_color();
	update_jamb_material();
	
	function door_size_change()
	{
		var single = document.getElementById('door_size').value;
		update_door_swing(single);
		update_cost();
	}
	function jamb_material_change()
	{
		var type = document.getElementById('jamb_material').value;
		update_jamb_size(type);
	}
	
	
	function update_door_size()
	{	
		var style = document.getElementById('door_style').value;
		get_list("door_size", "data/door_size_list.php?style="+style);
	}
	
	function update_door_swing(single)
	{
		get_list('door_swing',"data/door_swing_list.php?type="+single);
	}
	
	function update_sill()
	{
		get_list('sill',"data/pricelist_list.php?list=SILL");
	}
	
	function update_hinge_color()
	{
		get_list('hinge_color',"data/pricelist_list.php?list=HINGE");
	}
	
	function update_jamb_material()
	{
		get_list('jamb_material',"data/jamb_material_list.php");
	}
	
	function update_jamb_size(type)
	{
		get_list('jamb_size',"data/pricelist_list.php?list=JAMB_SIZE&type="+type);
	}
	
	function update_cost()
	{
	
	}
	
	function get_list(element_id, php)
	{	
		var xmlhttp;
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById(element_id).innerHTML=xmlhttp.responseText;
				}
			}
		xmlhttp.open("GET",php,true);
		xmlhttp.send();
	}
	
</script>