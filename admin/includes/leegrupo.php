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



$sql="SELECT grupos.id, grupos.nombre, grupos.imagen, grupos.activo, grupos.activo_web, grupos.activo_app, grupos.imagen_app, impuestos.porcentaje AS impuesto FROM grupos LEFT JOIN impuestos ON grupos.impuesto=impuestos.id WHERE grupos.id='".$array['id']."' AND grupos.tienda='".$array['tienda']."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $checking=true;
    while ($grupo = $result->fetch_object()) {
        $checking=true;
        $nombre=$grupo->nombre;
        $imagen=$grupo->imagen;
        $activo=$grupo->activo;
        $activo_web=$grupo->activo_web;
        $activo_app=$grupo->activo_app;
        $impuesto=$grupo->impuesto;
        $imagen_app=$grupo->imagen_app;
    }
    
}	
$database->freeResults();

$json=array("valid"=>$checking,"nombre"=>$nombre,"imagen"=>$imagen,"imagen_app"=>$imagen_app,"activo"=>$activo,"activo_web"=>$activo_web,"activo_app"=>$activo_app,"impuesto"=>$impuesto);


echo json_encode($json); 

/*
$file = fopen("texto.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fclose($file);
*/

?>
