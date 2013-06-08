<?PHP
	if($_REQUEST['edit'] == true)
	{
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
		
		$result = mysql_query("SELECT * FROM recipie WHERE recipie_id='{$_REQUEST['recipie_id']}'");
		$row = mysql_fetch_array($result);
		
		$recipie_id = $row['recipie_id'];
		$owner = $row['owner'];
		$name = $row['name'];
		$directions = $row['directions'];
		
		$ingredients = array();
		
		$result = mysql_query("SELECT recipie_item.amount, recipie_item.unit, ingredients.name " .
								"FROM recipie_item " .
								"INNER JOIN ingredients " .
								"ON recipie_item.ingr_id=ingredients.ingr_id " .
								"WHERE recipie_id={$recipie_id} " . 
								"ORDER BY recipie_item_id");
		
		for($i = 0; $row = mysql_fetch_array($result); $i=$i+1)
		{
			$ingredients[$i] = array();
			$ingredients[$i][0] = $row[0];
			$ingredients[$i][1] = $row[1];
			$ingredients[$i][2] = $row[2];
		}
		
		$categories = array();
		
		$result = mysql_query("SELECT * FROM recipie_category " .
								"INNER JOIN category " .
								"ON recipie_category.category_id=category.category_id " .
								"WHERE recipie_id={$recipie_id} ");
		
		for($i = 0; $row = mysql_fetch_array($result); $i=$i+1)
		{
			$categories[$i] = $row['name'];
		}
		
		mysql_close($con);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Recipie</title>
		
		<link rel="stylesheet" type="text/css" href="recipie.css">
		
		<script>
			var ingr_count = 0;

			function add_ingredient(amount_given, unit_given, ingredient_given)
			{
				var ingredients = document.getElementById('add_ingredients');
				
				var amount = document.createElement('input');
				amount.name = "ingredient["+ingr_count+"][0]";
				amount.id = "ingredient["+ingr_count+"][0]";
				amount.value = amount_given;
				amount.placeholder = "Amount";
				amount.setAttribute("class","amount");
				amount.setAttribute("list","amount");
				ingredients.appendChild(amount);
				amount.focus();
				
				var unit = document.createElement('input');
				unit.name = "ingredient["+ingr_count+"][1]";
				unit.id = "ingredient["+ingr_count+"][1]";
				unit.value = unit_given;
				unit.placeholder = "Unit";
				unit.setAttribute("class","unit");
				unit.setAttribute("list","unit");
				ingredients.appendChild(unit);
				
				var ingredient = document.createElement('input');
				ingredient.name = "ingredient["+ingr_count+"][2]";
				ingredient.id = "ingredient["+ingr_count+"][2]";
				ingredient.value = ingredient_given;
				ingredient.placeholder = "Ingredient";
				ingredient.setAttribute("class","ingredient");
				ingredient.setAttribute("list","ingredient");
				ingredients.appendChild(ingredient);
				
				var button = document.createElement('input');
				button.type = "button";
				button.id = "ingredient["+ingr_count+"][3]";
				button.value = "Remove";
				button.setAttribute("class","button");
				button.setAttribute("onclick","remove_ingredient("+ingr_count+")");
				ingredients.appendChild(button);
				
				var br = document.createElement('br');
				br.id = "ingredient["+ingr_count+"][4]";
				ingredients.appendChild(br);
				
				ingr_count++;
			}
			
			function remove_ingredient(num)
			{
				var ingredients = document.getElementById('add_ingredients');
			
				var amount = document.getElementById("ingredient["+num+"][0]");
				var unit = document.getElementById("ingredient["+num+"][1]");
				var ingredient = document.getElementById("ingredient["+num+"][2]");
				var button = document.getElementById("ingredient["+num+"][3]");
				var br = document.getElementById("ingredient["+num+"][4]");
				ingredients.removeChild(amount);
				ingredients.removeChild(unit);
				ingredients.removeChild(ingredient);
				ingredients.removeChild(button);
				ingredients.removeChild(br);
			}
			
			var category_count = 0;
			
			function add_category(category_given)
			{
				var categories = document.getElementById('categories');
				
				var cat = document.createElement('input');
				cat.name = "category["+category_count+"]";
				cat.id = "category["+category_count+"][0]";
				cat.value = category_given;
				cat.placeholder = "New Category";
				cat.setAttribute("class","category");
				cat.setAttribute("list","category");
				categories.appendChild(cat);
				cat.focus();
				
				var button = document.createElement('input');
				button.type = "button";
				button.id = "category["+category_count+"][1]";
				button.value = "Remove";
				button.setAttribute("class","button");
				button.setAttribute("onclick","remove_category("+category_count+")");
				categories.appendChild(button);
				
				var br = document.createElement('br');
				br.id = "category["+category_count+"][2]";
				categories.appendChild(br);
				
				category_count++;
			}
			
			function remove_category(num)
			{
				var categories = document.getElementById('categories');
			
				var cat = document.getElementById("category["+num+"][0]");
				var button = document.getElementById("category["+num+"][1]");
				var br = document.getElementById("category["+num+"][2]");
				categories.removeChild(cat);
				categories.removeChild(button);
				categories.removeChild(br);
			}
		</script>
		
	</head>
	
	<body>

		<form action="data/recipie_data.php" method="post">
			<input type="text" class="owner" name="owner" placeholder="Recipie Owner" value="<?PHP echo $owner ?>">
			<input type="text" class="name" name="name" placeholder="Recipie Name" value="<?PHP echo $name ?>"> 
			<br>
			<div id="add_ingredients" class="ingredients">
				<?PHP 	
					if($_REQUEST['edit'] == true)
					{
						foreach($ingredients as $ingredient)
						{
							echo '<script>add_ingredient("'.$ingredient[0].'","'.$ingredient[1].'","'.$ingredient[2].'");</script>';
						}
					}
					else
						echo '<script>add_ingredient("","","");</script>';
				?>
			</div>
			<input type="button" class="button" onclick="add_ingredient('','','')" value="Add Ingredient"> 
			<br>
			<textarea type="text" class="directions" name="directions" placeholder="Directions..." rows="10" cols="50"><?PHP echo $directions ?></textarea> 
			<br>
			<div id="categories">
				<?PHP 	
					if($_REQUEST['edit'] == true)
					{
						foreach($categories as $category)
						{
							echo '<script>add_category("'.$category.'");</script>';
						}
					}
					else
						echo '<script>add_ingredient("","","");</script>';
				?>
			</div>
			<input type="button" class="button" value="Add Category" onclick="add_category('')">
			<br>
			<br>
			<input type="submit" class="button">
			
			<datalist id="amount">
				<?PHP include 'data/amount_list.php'; ?>
			</datalist>
			<datalist id="unit">
				<?PHP include 'data/unit_list.php'; ?>
			</datalist>
			<datalist id="ingredient">
				<?PHP include 'data/ingredient_list.php'; ?>
			</datalist>
			<datalist id="category">
				<?PHP include 'data/category_list.php'; ?>
			</datalist>
		</form>
		
	</body>
</html>