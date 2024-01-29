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

$sql="SELECT CONVERT(textomail USING utf8) AS texto FROM tiposcorreos WHERE id=3";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) { 
    $camp = $result->fetch_object();
    $checking=true;
    $texto=$camp->texto;
}	

$database->freeResults();

$json=array("valid"=>$checking,"texto"=>$texto);

echo json_encode($json); 


?>