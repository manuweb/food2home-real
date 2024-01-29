<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;



if ($array['tipo']=='add'){
    $sql="UPDATE usuarios_app SET grupoclientes='".$array['id']."' WHERE id='".$array['cliente']."';";
}
else {
    $sql="UPDATE usuarios_app SET grupoclientes='0' WHERE id='".$array['cliente']."';";
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
