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


if($array['activo_web']=='false'){
    $array['activo_web']=0;
}
else {
    $array['activo_web']=1;
}
if($array['activo_app']=='false'){
    $array['activo_app']=0;
}
else {
    $array['activo_app']=1;
}
if ($array['id']==0){
    $sql="INSERT INTO grupos (id, tienda, nombre, orden, imagen, impuesto, activo, activo_web, activo_app, imagen_app) VALUES (NULL, '0', '".$array['nombre']."', 0, '', 2, 0, '".$array['activo_app']."', '0', '');";
}
else {
    $sql="UPDATE grupos SET nombre='".$array['nombre']."', activo_web='".$array['activo_web']."', activo_app='".$array['activo_app']."' WHERE id='".$array['id']."' AND tienda='".$array['tienda']."'";
}





$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $checking=true;
}	
$database->freeResults();
$json=array("valid"=>$checking);
echo json_encode($json); 




?>
