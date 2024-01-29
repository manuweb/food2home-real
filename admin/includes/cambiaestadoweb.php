<?php
session_start(); 
include "../../webapp/conexion.php";
include "../../webapp/MySQL-2/DataBase.class.php";
header('Access-Control-Allow-Origin: *');

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;
$on=$array['estado'];
if ($on!='true'){
    $on=0;
}
else {
     $on=1;
}

$sql="update estadoweb SET estado=".$on." where id=1";

$db = DataBase::getInstance();  
$db->setQuery($sql);  
//$estado = $db->loadObjectList();  
//$db->freeResults();

if ($db->alter()){
    $checking=true;
}	

$json=array("valid"=>$checking);
ob_end_clean();
echo json_encode($json);    

/*
$file = fopen("estado.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "on: ". $on . PHP_EOL);
fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fclose($file);
*/

?>
