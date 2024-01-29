<?php

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
header('Access-Control-Allow-Origin: *');


$array = json_decode(json_encode($_POST), true);

$checking=false;

$tienda=$array['tienda'];
$id=$array['id'];
$tipo=$array['tipo'];
$activo=$array['activo'];
$tabla=$array['tabla'];
$sql="UPDATE ".$tabla." SET ".$tipo."=".$activo." WHERE tienda=".$tienda." AND id=".$id;

if ($tabla=='promo'){
    $sql="UPDATE promos SET activo=".$activo." WHERE id=".$id;

    
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
