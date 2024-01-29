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
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$sql="DELETE FROM inicio WHERE id='".$array['id']."';";
$checking=false;

    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
 

if ($result) {
    $checking=true;
}	
   $database->freeResults();
$json=array("valid"=>$checking);

ob_end_clean();
echo json_encode($json); 


?>
