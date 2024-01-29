<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  cambiactivoinicio.php
//
//  Cambia activo en inicio
//
//*************************************************************************************************

$array = json_decode(json_encode($_POST), true);

$checking=false;


$sql="UPDATE inicio SET web='".$array['valor']."' WHERE id='".$array['id']."';";


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
