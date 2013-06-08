<?PHP



?>
<head>
<script>
var ingr_count = 1;

function add_ingredient()
{
	var ingredients = document.getElementById('add_ingredients');
	
	var amount = document.createElement('input');
	amount.name = "ingredient["+ingr_count+"]['amount']";
	amount.placeholder = "Amount";
	amount.setAttribute("list","amount");
	ingredients.appendChild(amount);
	amount.focus();
	
	var unit = document.createElement('input');
	unit.name = "ingredient["+ingr_count+"]['unit']";
	unit.placeholder = "Unit";
	unit.setAttribute("list","unit");
	ingredients.appendChild(unit);
	
	var ingredient = document.createElement('input');
	ingredient.name = "ingredient["+ingr_count+"]['ingredient']";
	ingredient.placeholder = "Ingredient";
	ingredient.setAttribute("list","ingredient");
	ingredients.appendChild(ingredient);
	
	var br = document.createElement('br');
	ingredients.appendChild(br);
	
	ingr_count++;
}
</script>

<input type="text" placeholder="Enter Recipe Title"><input type="text" placeholder="Enter the Owner of the recipie"><br>

<image src="food.jpg">

<div class="picture_side">
	
</div>
<br>

<input type="text" placeholder="Enter Recipie Description"><br><br>

Ingredients: <br>
<div id="add_ingredients">
	<input name="ingredient[0]['amount']" 		list="amount" 	  type="text" placeholder="Amount">
	<input name="ingredient[0]['unit']" 	    list="unit" 	  type="text" placeholder="Unit">
	<input name="ingredient[0]['ingredient']"	list="ingredient" type="text" placeholder="Ingredient"><br>
	
	<datalist id="amount">
		<?PHP include 'data/amount_list.php'; ?>
	</datalist>
	<datalist id="unit">
		<?PHP include 'data/unit_list.php'; ?>
	</datalist>
	<datalist id="ingredient">
		<?PHP include 'data/ingredient_list.php'; ?>
	</datalist>
	
</div>

<br>

<input type="button" value="Add Ingredient" onclick="add_ingredient()">
<input type="button" value="Add Category">

<br>
<br>

Directions: <br>
<textarea rows=10 cols=60></textarea>