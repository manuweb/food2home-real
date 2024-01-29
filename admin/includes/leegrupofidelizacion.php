<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;



$sql="select grupo_clientes.id, grupo_clientes.nombre, grupo_clientes.porcentaje, grupo_clientes.activo, count(usuarios_app.grupoclientes) AS cantidad FROM grupo_clientes LEFT JOIN usuarios_app on grupo_clientes.id=usuarios_app.grupoclientes WHERE grupo_clientes.id='".$array['id']."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();


if ($result->num_rows>0) {
    $checking=true;
    $grupos = $result->fetch_object();
    $id=$grupos->id;
    $nombre=$grupos->nombre;
    $porcentaje=$grupos->porcentaje;
    $activo=$grupos->activo;
    $cantidad=$grupos->cantidad;
}	

$database->freeResults(); $json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"porcentaje"=>$porcentaje,"activo"=>$activo,"cantidad"=>$cantidad);

echo json_encode($json); 


?>
