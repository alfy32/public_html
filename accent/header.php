<link rel="stylesheet" type="text/css" href="header.css" />

<?php
	$date = date('l, F j, Y');
	
	if(isset($_POST['submit']))
	{	
		$date = date('l, F j, Y',strtotime($_POST['date']));
	}
?>

<header>
	
	<form class='date_changer' action='schedule.php' method='post'>
		<img class='logo_left' src='data/accent_salon.jpg' alt='Accent Logo' />
		<img class='logo_right' src='data/accent_salon.jpg' alt='Accent Logo' />
			<p type='text' class='date' id='date' name='date' 
			onclick='change_date(this,"<?php echo date('Y-m-d', strtotime($date)) ?>")' >
			<?php echo $date ?></p>
		
		
		
		
		<div class='new_date'>
			<span id='date_span'></span>
			<input type='hidden' id='submit' name='submit' value='Change Date' />
		</div>
	</form>
	
	
</header>

<script>
	var submit = document.getElementById('submit');
	var date_span = document.getElementById('date_span');
	
	function change_date(input, date) {
		//input.type = 'date';
		//input.readOnly = false;
		//input.value = date;
		date_span.innerHTML = "<input type='date' value='" + date + "' name='date' />";
		submit.type = 'submit';
	}
</script>