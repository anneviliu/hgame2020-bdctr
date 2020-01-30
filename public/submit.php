<?php
include "config.php";
$result = null;
libxml_disable_entity_loader(false);
$xmlfile = file_get_contents('php://input');

try{
    $stmt = $conn->prepare("INSERT INTO info (id, chal_name, bd_level, bd_time) VALUES (:id, :chal_name, :bd_level, :bd_time)");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':chal_name', $chal_name);
    $stmt->bindParam(':bd_level', $level);
    $stmt->bindParam(':bd_time', $level);

    $dom = new DOMDocument();
    $dom->loadXML($xmlfile, LIBXML_NOENT | LIBXML_DTDLOAD);
    $creds = simplexml_import_dom($dom);
    $id = $creds->id;
    $chal_name = $creds->name;
    $level = $creds->level;
    $time = $creds->time;
    $stmt->execute();
    $result = sprintf("<result><code>%d</code><msg>%s</msg></result>",1,"已提交成功，正在为您安排打手");
}catch(Exception $e){
//    echo "Error: " . $e->getMessage();
    $result = sprintf("<result><code>%d</code><msg>%s</msg></result>",0,"提交失败！");
}
header('Content-Type: text/html; charset=utf-8');
echo $result;
?>