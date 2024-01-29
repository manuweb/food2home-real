<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  cambiactivometodo.php
//
//  Cambia activo en metodospago
//
//*************************************************************************************************
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;


$sql="UPDATE metodospago SET activo='".$array['valor']."' WHERE id='".$array['id']."';";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result) {  
    $checking=true;
}	
$database->freeResults();   
$json=array("valid"=>$checking);


echo json_encode($json); 


?>
