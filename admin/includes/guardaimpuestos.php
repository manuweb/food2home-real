<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  guardaimpuestos.php
//
//  guarda los impuestos  
//
//*************************************************************************************************
 $_POST = json_decode(file_get_contents('php://input'), true);

$array = json_decode(json_encode($_POST), true);

$checking=false;



$sql="UPDATE impuestos SET nombre='".$array['nombre']."', porcentaje='".$array['porcentaje']."' WHERE id='".$array['id']."';";

$db = DataBase::getInstance();  
$db->setQuery($sql);  
$impuestos = $db->loadObjectList();  
$db->freeResults();

if (count($impuestos)==0) {
    $checking=true;
    
}	

$json=array("valid"=>$checking);

echo json_encode($json); 




?>
