<?php

$name = array(0=>"alan.christensen",
				1=>"a00140853", 
				2=>"a01085542",
				3=>"a00234034",
				4=>"a01203245",
				5=>"john.jensen",
				6=>"a01543234",
				7=>"a00234520",
				8=>"a00653304",
				9=>"a01033325");

for($i = 0; $i < 100; $i++)
{
	echo "mail<br>";
	$host = $i%5 == 0 ? "@gmail.com" : "aggiemail.usu.edu";
	
	switch($i%10)
	{
	case 1:
		$subject = "Homework Help";
		$message = "Hey John, Can you help me with my homework...";
		break;
	case 2:
		$subject = "I'm stuck on my paper";
		$message = "John, I have my paper started, but I'm just stuck...";
		break;	
	case 3:
		$subject = "Can I make an appointment";
		$message = "John, can I come by your office and have you read...";
		break;	
	case 4:
		$subject = "Stuck";
		$message = "Hey John, I'm stuck. I'm not sure where to start...";
		break;	
	case 5:
		$subject = "Paper";
		$message = "Hey John, How do I format my paper";
		break;	
	case 6:
		$subject = "Research";
		$message = "John, where is a good place to research...";
		break;	
	case 7:
		$subject = "Blind Draft";
		$message = "John, What is the blind draft...";
		break;	
	case 8:
		$subject = "Class";
		$message = "Hey John, I woke up sick today and wont make it to class today...";
		break;	
	case 9:
		$subject = "Office";
		$message = "John, Where is your office? ....";
		break;	
	case 0:
		$subject = "Due Date";
		$message = "John, when is the final draft due? ...";
		break;	
	};

	//send email
	$toemail = "a01072246@aggiemail.usu.edu";
	$fromemail = $name[$i%10].$host ;
  
	mail($toemail, $subject, $message, "From:" . $fromemail);
 }
?>