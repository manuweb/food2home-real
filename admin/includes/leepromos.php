<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL-2/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);
$checking=false;



$sql="SELECT id, nombre, codigo,desde, hasta, tipo, logica FROM promos ORDER BY desde;";

$db = DataBase::getInstance();  
$db->setQuery($sql);  
$promos = $db->loadObjectList();  
//$db->freeResults();

if (count($promos)>0) {
    $checking=true;
    for ($n=0;$n<count($promos);$n++){
        $id[$n]=$promos[$n]->id;
        $nombre[$n]=$promos[$n]->nombre;
        $codigo[$n]=$promos[$n]->codigo;
        $desde[$n]=$promos[$n]->desde; 
        $hasta[$n]=$promos[$n]->hasta;
        $tipo[$n]=$promos[$n]->tipo;   
        $logica[$n]=$promos[$n]->logica;   
    }
    
}	

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"codigo"=>$codigo,"desde"=>$desde,"hasta"=>$hasta,"tipo"=>$tipo,"logica"=>$logica);

echo json_encode($json); 
/*
$file = fopen("texto.txt", "w");
fwrite($file, "sql: ".$sql. PHP_EOL);
fwrite($file, "json: ".json_encode($json). PHP_EOL);
fclose($file);
*/

?>
