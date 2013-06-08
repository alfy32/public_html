<!DOCTYPE html>
<?php 
	if(isset($_REQUEST['submit']))
		echo "submit";
	else
		echo "no submit";
		
?>


<html>
	<head>
		<link rel="stylesheet" type="text/css" href="recipie.css">
		
		<script>
			function get_recipies()
			{
				var category = document.getElementById('category_select').value;
				var recipies = document.getElementById('recipies'); 
				
				if (window.XMLHttpRequest)
				{// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
				}
				else
				{// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange=function() {
					if (xmlhttp.readyState==4 && xmlhttp.status==200)
					{
						document.getElementById("recipies").innerHTML=xmlhttp.responseText;
					}
				}
				xmlhttp.open("GET","data/recipie_list_search.php?category="+category,true);
				xmlhttp.send();
			}
		</script>
		
	</head>
	
	<body>
		<form action="index.php" method="GET">
			<p class="category_label">Category</p>
			<select class="category_select" id="category_select" onclick="get_recipies()">
				<?PHP include 'data/category_list.php'; ?>
			</select>
			<p class="category_label">Recipies</p>
			<select name="recipie_id" class="category_select" id="recipies">
			</select><br><br>
			<input class="category_label" name="submit" type="submit">
		</form>
	</body>
</html>