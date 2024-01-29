<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);
$checking=false;

for ($n=0;$n<count($array['grupos']);$n++){
    $sql="UPDATE grupos SET orden='".$n."' WHERE id='".$array['grupos'][$n]."' AND tienda='".$array['tienda']."';";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    $database->freeResults();

    $checking=true;
}


$json=array("valid"=>$checking);

echo json_encode($json); 

?>
