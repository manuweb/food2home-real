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
/*
$array['porcentaje']=15;
$array['nombre']='Promo';
$array['activo']=true;
*/
if($array['activo']=='false'){
    $array['activo']=0;
}
else {
    $array['activo']=1;
}

if($array['id']=='0'){
    $sql="INSERT INTO grupo_clientes (nombre,porcentaje,activo) VALUES ('".$array['nombre']."','".$array['porcentaje']."','".$array['activo']."');";
}
else{
    $sql="UPDATE grupo_clientes SET nombre='".$array['nombre']."', porcentaje='".$array['porcentaje']."', activo='".$array['activo']."' WHERE id='".$array['id']."'";
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

/*
$file = fopen("texto2.txt", "w");
fwrite($file, "sql: ".$sql. PHP_EOL);
fwrite($file, "post: ".json_encode($_POST). PHP_EOL);
fclose($file);
*/

?>
