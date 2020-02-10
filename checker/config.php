<?php
$dbms='mysql';
$host='localhost';
$dbName='team_token';
$user='root';
$pass='5Om1HtOwkW31AYjHn3bOAyJDr5bSQriXRgUoNpK54ELSE';

$dsn="$dbms:host=$host;dbname=$dbName";
$conn = new PDO($dsn, $user, $pass, array(PDO::ATTR_PERSISTENT => true));
//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>