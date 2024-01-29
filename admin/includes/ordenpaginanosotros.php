<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  ordenpaginanosotros.php
//
//  Lee datos de la tabla nosotros 
//
//  Llamado desde paginanosotros.js 
//
//*************************************************************************************************
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;

for ($n=0;$n<count($array['grupos']);$n++){
    $sql1="UPDATE nosotros SET orden='".$n."' WHERE id='".$array['grupos'][$n]."';";
    $database = DataBase::getInstance();
    $database->setQuery($sql1);
    $result = $database->execute();
    $database->freeResults();

    $checking=true;
}

ob_end_clean();



$json=array("valid"=>$checking);

echo json_encode($json); 



?>
