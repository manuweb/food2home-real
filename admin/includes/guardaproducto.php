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

$sql="UPDATE productos SET info='".$array['info']."', activo_web='".$array['activo_web']."',  activo_app='".$array['activo_app']."',modificadores='".$array['modifi']."',precio_web='".$array['precio_web']."', precio_app='".$array['precio_app']."', modifier_category_id='".$array['modifier_category_id']."', modifier_group_id='".$array['modifier_group_id']."', alergias='".$array['alergias']."' WHERE id='".$array['id']."' AND tienda='".$array['tienda']."'";


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
