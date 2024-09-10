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

$sql="SELECT categorias.id, categorias.nombre, categorias.imagen, categorias.activo, categorias.activo_web, categorias.activo_app, categorias.imagen_app, impuestos.porcentaje AS impuesto FROM categorias LEFT JOIN grupos ON grupos.id=categorias.grupo LEFT JOIN impuestos ON if (categorias.impuesto='', grupos.impuesto, categorias.impuesto)=impuestos.id WHERE categorias.id='".$array['id']."' AND categorias.tienda='".$array['tienda']."';";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $checking=true;
    while ($grupo = $result->fetch_object()) {
    
        $nombre=$grupo->nombre;
        if ($grupo->imagen!=''){
            $imagen[]=$grupo->imagen;
        }
        else {
            $imagen[]="";
        }
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
$file = fopen("leecategoria.txt", "w");
fwrite($file, "sql: ". $sql2 . PHP_EOL);

fclose($file);
*/

?>
