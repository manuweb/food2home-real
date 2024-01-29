<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  ordenpaginainicio.php
//
//  Lee datos de la tabla inicio 
//
//  Llamado desde paginainicio.js ->paginainicio()
//
//*************************************************************************************************
 
$array = json_decode(json_encode($_POST), true);

$checking=false;

for ($n=0;$n<count($array['grupos']);$n++){
    $sql1="UPDATE destacados SET orden='".$n."' WHERE id='".$array['grupos'][$n]."';";
    $database = DataBase::getInstance();
    $database->setQuery($sql1);
    $result = $database->execute();
    $database->freeResults();

    $checking=true;
}
$json=array("valid"=>$checking);

ob_end_clean();
echo json_encode($json); 



?>
