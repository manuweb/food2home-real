<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  cambiaestadofidelidad.php
//
//  Cambia activo en fidelizacion
//
//*************************************************************************************************
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;

$sql="UPDATE opcionescompra SET fidelizacion='".$array['estado']."' WHERE id='1';";


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
$file = fopen("texto2.txt", "w");
fwrite($file, "sql: ".$sql. PHP_EOL);

fclose($file);
*/
?>
