<?php
$dbms='mysql';
$host='localhost';
$dbName='bdctr_message';
$user='root';
$pass='yevi1gcqpqHSaOZVDI1CcRLaHHSJ5BYgImof';
//$pass='root';

$dsn="$dbms:host=$host;dbname=$dbName";
$conn = new PDO($dsn, $user, $pass, array(PDO::ATTR_PERSISTENT => true));
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>