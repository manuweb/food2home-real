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

//$syncimagen=$array['syncimagen'];

$sql="select id, nombre, imagen,imagen_app1, orden, activo, activo_app, activo_web, esMenu FROM productos WHERE categoria='".$array['categoria']."' AND tienda='".$array['tienda']."' ORDER BY orden;";




$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $checking=true;
    while ($grupo = $result->fetch_object()) {
        $id[]=$grupo->id;
        $nombre[]=$grupo->nombre;
        //if ($grupo->imagen!=''){
            $imagen[]=$grupo->imagen;
        //}
        //else {
            //$imagen[]="";
        //}
        $imagen_app[]=$grupo->imagen_app1;
        $orden[]=$grupo->orden;
        $activo[]=$grupo->activo;
        $activo_web[]=$grupo->activo_web;
        $activo_app[]=$grupo->activo_app;
        $esMenu[]=$grupo->esMenu;
        
    }
    $checking=true;
}

$database->freeResults();
 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"imagen"=>$imagen,"imagen_app"=>$imagen_app,"orden"=>$orden,"activo"=>$activo,"activo_web"=>$activo_web,"activo_app"=>$activo_app,"esMenu"=>$esMenu);

echo json_encode($json); 



?>
