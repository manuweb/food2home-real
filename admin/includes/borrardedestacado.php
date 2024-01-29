<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  borrardeinicio.php
//
//  borra de inicio
//
//*************************************************************************************************
 
$array = json_decode(json_encode($_POST), true);

$checking=false;


$sql="DELETE FROM destacados WHERE id='".$array['id']."';";
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
