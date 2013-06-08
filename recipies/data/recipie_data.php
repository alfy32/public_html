<?PHP

	echo 	"Name: " . $_POST['name'] . "<br>" .
			"Owner: " . $_POST['owner'] . "<br>" .
			"Directions: " . $_POST['directions'] . "<br>" ;
			
	foreach($_POST['ingredient'] as $ingredient)
	{
		echo "Amount: " . $ingredient[0] . " Unit: " . $ingredient[1] . " Ingredient: " . $ingredient[2] . "<br>";
	}
	
?>			

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

	mysql_query("INSERT INTO {$table} (name,directions,owner)" .
				"VALUES ('{$_POST['name']}','{$_POST['directions']}','{$_POST['owner']}')");
	
	$recipie_id = mysql_insert_id();
	
	foreach($_POST['ingredient'] as $ingredient)
	{
		$ingr_id = mysql_fetch_array(mysql_query("SELECT ingr_id FROM ingredients WHERE name='{$ingredient[2]}'"));
		$ingr_id = $ingr_id[0];
		
		if($ingr_id)
		{
			//the ingredient is already in the database
		}
		else
		{
			//the ingredient is not in the database
			mysql_query("INSERT INTO ingredients (name)" .
						"VALUES ('{$ingredient[2]}')");
		
			$ingr_id = mysql_insert_id();
		}
		
		mysql_query("INSERT INTO recipie_item (recipie_id,ingr_id,amount,unit)" .
					"VALUES ({$recipie_id},{$ingr_id},'{$ingredient[0]}','{$ingredient[1]}')");
		
		mysql_query("INSERT INTO amount (label) VALUES ('{$ingredient[0]}')");
		mysql_query("INSERT INTO unit (label) VALUES ('{$ingredient[1]}')");
	}
	
	foreach($_POST['category'] as $category)
	{
		$cat_id = mysql_fetch_array(mysql_query("SELECT category_id FROM category WHERE name='{$category}'"));
		$cat_id = $cat_id[0];
		if($cat_id)
		{
			//the category exists
		}
		else
		{
			mysql_query("INSERT INTO category (name) VALUES ('{$category}')");
			
			$cat_id = mysql_insert_id();
		}	
		
		mysql_query("INSERT INTO recipie_category (recipie_id,category_id) VALUES ({$recipie_id},{$cat_id})");
		
		echo "INSERT INTO recipie_category (recipie_id,category_id) VALUES ({$recipie_id},{$cat_id})";
	}
	
	mysql_close($con);
	
?>