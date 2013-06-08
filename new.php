<?php

$con = mysql_connect("mysql5.000webhost.com","a5755249_alfy","abl912");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("a5755249_books", $con);

mysql_query("INSERT INTO books (buy,name,school,subject,class,title,edition,author,isbn,notes)
VALUES   ('" . $_POST['buy'] . "','" . $_POST['name'] . "','" . $_POST['school'] . "','" . $_POST['subject'] . "','" . $_POST['class'] . "','" . $_POST['title'] . "','" . $_POST['edition'] . "','" . $_POST['author'] . "'," . $_POST['isbn'] . ",'" . $_POST['notes'] . "')");	

echo "

<!DOCTYPE HTML>
<html>


	<form id=\"form\" method=\"post\" action=\"books.php\"> 	
		<input name=\"school\" type=\"hidden\" value=\"" . $_POST['school'] . "\">
		<input name=\"class\" type=\"hidden\" value=\"" . $_POST['class'] . "\">
		<input name=\"subject\" type=\"hidden\" value=\"" . $_POST['subject'] . "\">
	</form>

<script>
document.forms['form'].submit();
</script>
	

</html>

";