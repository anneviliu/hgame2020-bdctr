<?php
include "config.php";

$token = @$_GET['token'];
if (!isset($token)) {
    exit();
}
$sql = "select * from info where token=? ";
$stmt = $conn->prepare( $sql );
$stmt->bindValue( 1, $token );
$stmt->execute();
$res = $stmt->rowCount();

if ($res === 0) {
    echo "false";
}
else if ($res === 1){
    echo "ok";
}