<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL-2/DataBase.class.php";
header("Access-Control-Allow-Origin: *");


$checking=false;



$sql="SELECT id, nombre, porcentaje FROM impuestos ORDER BY id;";

$db = DataBase::getInstance();  
$db->setQuery($sql);  
$impuestos = $db->loadObjectList();  
//$db->freeResults();

if (count($impuestos)>0) {
    $checking=true;
    for ($n=0;$n<count($impuestos);$n++){
        $id[$n]=$impuestos[$n]->id;
        $nombre[$n]=$impuestos[$n]->nombre;
        $porcentaje[$n]=$impuestos[$n]->porcentaje;        
    }
    
}	

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"porcentaje"=>$porcentaje);

echo json_encode($json); 



?>
