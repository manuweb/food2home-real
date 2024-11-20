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
if($array['modifi']=='false'){
    $array['modifi']=0;
}
else {
    $array['modifi']=1;
}
if ($array['id']==0){
    
    $sql="INSERT INTO productos (id, tienda, nombre, categoria, orden, imagen, impuesto, activo, precio, info, alergias, precio_web, precio_app, activo_web, activo_app, imagen_app1, imagen_app2, imagen_app3, modifier_category_id, modifier_group_id, modificadores, esMenu) VALUES (NULL, '".$array['tienda']."', '".$array['nombre']."', '".$array['categoria']."', '0', '', '".$array['impuesto']."', '1', '".$array['precio_web']."', '".$array['info']."', '".$array['alergias']."', '".$array['precio_web']."', '".$array['precio_web']."', '".$array['activo_web']."', '0', '', '', '', '".$array['modifier_category_id']."', '".$array['modifier_group_id']."', '".$array['modifi']."', '0');";
}
else {
    

$sql="UPDATE productos SET info='".$array['info']."', activo_web='".$array['activo_web']."',  activo_app='".$array['activo_app']."',modificadores='".$array['modifi']."',precio_web='".$array['precio_web']."', modifier_category_id='".$array['modifier_category_id']."', modifier_group_id='".$array['modifier_group_id']."', alergias='".$array['alergias']."' WHERE id='".$array['id']."' AND tienda='".$array['tienda']."'";

}

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
/*
$file = fopen("zz-guiardaprod.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fclose($file);
*/
if ($result) { 
    $checking=true;

}	
$database->freeResults();

$json=array("valid"=>$checking);
echo json_encode($json); 


	


?>
