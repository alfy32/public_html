<!DOCTYPE html>
<html>
	<head>
		<title>New Recipie</title>
		
		<link rel="stylesheet" type="text/css" href="new.css">
		
		<script>
			var ingr_count = 1;

			function add_ingredient()
			{
				var ingredients = document.getElementById('add_ingredients');
				
				var amount = document.createElement('input');
				amount.name = "ingredient["+ingr_count+"][0]";
				amount.placeholder = "Amount";
				amount.setAttribute("class","amount");
				amount.setAttribute("list","amount");
				ingredients.appendChild(amount);
				amount.focus();
				
				var unit = document.createElement('input');
				unit.name = "ingredient["+ingr_count+"][1]";
				unit.placeholder = "Unit";
				unit.setAttribute("class","unit");
				unit.setAttribute("list","unit");
				ingredients.appendChild(unit);
				
				var ingredient = document.createElement('input');
				ingredient.name = "ingredient["+ingr_count+"][2]";
				ingredient.placeholder = "Ingredient";
				ingredient.setAttribute("class","ingredient");
				ingredient.setAttribute("list","ingredient");
				ingredients.appendChild(ingredient);
				
				var br = document.createElement('br');
				ingredients.appendChild(br);
				
				ingr_count++;
			}
		</script>
		
	</head>
	
	<body>

		<form action="data/recipie_data.php" method="get">
			<input type="text" class="owner" name="owner" placeholder="Recipie Owner">
			<input type="text" class="name" name="name" placeholder="Recipie Name"> 
			<br>
			<div id="add_ingredients" class="ingredients">
				<input type="text" class="amount" name="ingredient[0][0]" list="amount" placeholder="Amount"><input type="text" class="unit" name="ingredient[0][1]" list="unit" placeholder="Unit"><input type="text" class="ingredient" name="ingredient[0][2]" list="ingredient" placeholder="Ingredient"> 
				<br>
			</div>
			<input type="button" class="button" onclick="add_ingredient()" value="Add Ingredient"> 
			<br>
			<textarea type="text" class="directions" name="directions" placeholder="Directions..." rows="10" cols="50"></textarea> 
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
		</form>
		
	</body>
</html>