<pre>
<form action='index2.php' method='post'>
<?php
print_r($_POST);

	if(!isset($_POST['submit']))
	{
		echo "
Welcome to 20 questions!

Think of something an I'll guess what it is.

If I don't know the answer I'll ask you to teach me about the thing you were thinking 
about so I can get smarter.


<input type='submit' name='submit' value='go' />

";
	}
	else
	{
		if($_POST['submit'] == 'go')
		{
			echo "Is it living? <input type='submit' name='submit' value='Yes'/> <input type='submit' name='submit' value='No'/>";
		}
	}
?>
</pre>
</from>