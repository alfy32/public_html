<?php
$url = file_get_contents("http://midutahradio.com/ksvc");
$html = new DOMDocument();
$html->loadHTMLFile($url);

echo $html->getElementById("content");


?>