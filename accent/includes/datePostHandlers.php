<?php

//// LEFT_ARROW GOTO PREVIOUS DAY ////	
if(isset($_POST['leftArrow'])) {
	$date = htmlentities($_POST['date']);
	$date = date("Y-m-d", strtotime($date." -1 day"));
}

//// RIGHT_ARROW GOTO TOMORROW ////
if(isset($_POST['rightArrow'])) {
	$date = htmlentities($_POST['date']);
	$date = date("Y-m-d", strtotime($date." +1 day"));
}

//// DATE_SUBMIT CHANGE DATE ////
if(isset($_POST['dateSubmit'])) {
	$date = htmlentities($_POST['date']);
	$date = date("Y-m-d", strtotime($date));
}

?>