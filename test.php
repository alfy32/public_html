<?php

echo "user agent: $_SERVER[HTTP_USER_AGENT]";

	for($i = 0; $i < 60; $i++) {
		$round = intval($i/30)*30;
		echo "$i , $round<br>";
	}




function roomInfoToHash($string) {
	$string = htmlspecialchars_decode($string);
	$string = str_replace("&nbsp;"," ",$string);
	$string = split(",",$string);
	
	$hash = array();
	
	foreach($string as $value) {
		$value = split(":",$value);
		$tag = trim($value[0]);
		$value = trim($value[1]);

		$hash[$tag] = $value;
	}
	return $hash;
}

$string = "Room: 107,&nbsp;&nbsp;Floor: 1,&nbsp;&nbsp;Capacity: 4 to 6";
print_r(roomInfoToHash($string)); echo "<br>";

$string = "Room: 112,&nbsp;&nbsp;Floor: 1,&nbsp;&nbsp;Capacity: 8 to 12";
print_r(roomInfoToHash($string)); echo "<br>";

$string = "Room: 113A,&nbsp;&nbsp;Floor: 1,&nbsp;&nbsp;Capacity: 2";
print_r(roomInfoToHash($string)); echo "<br>";

$string = "Room: 113B,&nbsp;&nbsp;Floor: 1,&nbsp;&nbsp;Capacity: 2";
print_r(roomInfoToHash($string)); echo "<br>";

$string = "Room: 114A,&nbsp;&nbsp;Floor: 1,&nbsp;&nbsp;Capacity: 2";
print_r(roomInfoToHash($string)); echo "<br>";

?>