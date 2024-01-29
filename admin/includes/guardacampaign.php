<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
header('Access-Control-Allow-Origin: *');

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}

$array = json_decode(json_encode($_POST), true);
$checking=false;
//01/34/6789
$fecha=substr($array['fecha'],6,4).'-'.substr($array['fecha'],3,2).'-'.substr($array['fecha'],0,2);
if ($array['id']=='0') {
    //insert
    $sql="INSERT INTO campaign (nombre, fecha, usuario, grupo, texto) VALUES ('".$array['nombre']."', '".$fecha."', '".$array['usuario']."', '".$array['grupo']."', '".$array['texto']."')";
}
else {
    $sql="UPDATE campaign SET nombre='".$array['nombre']."', fecha='".$fecha."', usuario='".$array['usuario']."', grupo='".$array['grupo']."', texto='".$array['texto']."' WHERE id='".$array['id']."'";
}

//$sql="SELECT id,nombre, fecha, realizada, usuario, grupo, texto FROM campaign".$where.";";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) { 
    $checking=true;
}	

$database->freeResults();

$json=array("valid"=>$checking);

echo json_encode($json); 

/*
$file = fopen("campaign.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "json: ". json_encode($json) . PHP_EOL);

fclose($file);
*/

?>
