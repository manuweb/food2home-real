<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");


$checking=false;



$sql="SELECT id, nombre, imagen FROM alergenos ORDER BY nombre;";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $checking=true;
    $n=0;
    while ($alergenos = $result->fetch_object()) {
        $id[$n]=$alergenos->id;
        $nombre[$n]=$alergenos->nombre;
        $imagen[$n]=$alergenos->imagen;    
        $n++;
    }
  
}	
$database->freeResults();
$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"imagen"=>$imagen);

echo json_encode($json); 



?>
