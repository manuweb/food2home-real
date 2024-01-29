<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  cambiactivonosotros.php
//
//  Cambia activo en nosotros
//
//*************************************************************************************************
 $_POST = json_decode(file_get_contents('php://input'), true);

$array = json_decode(json_encode($_POST), true);

$checking=false;


$sql="UPDATE nosotros SET web='".$array['valor']."' WHERE id='".$array['id']."';";


$db = DataBase::getInstance();  
$db->setQuery($sql);  
$inicio = $db->loadObjectList();  
$db->freeResults();

if (count($inicio)>=0) {
    $checking=true;
}	

$json=array("valid"=>$checking);


echo json_encode($json); 


?>
