<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;



$sql="SELECT fidelizacion, porcentaje FROM opcionescompra WHERE id='1';";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result->num_rows>0) {
    $checking=true;
    $opcionescompra = $result->fetch_object();
    $fidelizacion=$opcionescompra->fidelizacion;
    $porcentaje=$opcionescompra->porcentaje;
    
    

}	


$database->freeResults(); 
$json=array("valid"=>$checking,"fidelizacion"=>$fidelizacion,"porcentaje"=>$porcentaje);

echo json_encode($json); 




?>
