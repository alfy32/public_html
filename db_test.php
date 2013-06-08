<?php

define('DB_SERVER', 'localhost');
define('DB_USER', 'alan_alan');
define('DB_PASSWORD', 'ze3^717?0F-e');
define('DB_NAME', 'alan_salon');

$mysqli = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$stmt = $mysqli->prepare("INSERT INTO CountryLanguages VALUES ('name',?, ?, ?, ?)");
$stmt->bind_param('sssd', $code, $language, $official, $percent);

$code = 'DEU';
$language = 'Bavarian';
$official = "F";
$percent = 11.2;

/* execute prepared statement */
$stmt->execute();

printf("%d Row inserted.\n", $stmt->affected_rows);

/* close statement and connection */
$stmt->close();

printf ("New Record has id %d.\n", $mysqli->insert_id);

/* drop table */
$mysqli->query("DROP TABLE myCity");

/* close connection */
$mysqli->close();
?>
