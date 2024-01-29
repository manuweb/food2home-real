<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
error_reporting(E_ALL);
ini_set('display_errors', '1');

$array = json_decode(json_encode($_POST), true);

$checking=false;



$sql="SELECT id, nombre, activo, idrevo, esRedsys FROM metodospago ORDER BY id;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $checking=true;
    $n=0;
    while ($metodos = $result->fetch_object()) {

        $id[$n]=$metodos->id;
        $nombre[$n]=$metodos->nombre;
        $activo[$n]=$metodos->activo;   
        $idrevo[$n]=$metodos->idrevo;   
        $esRedsys[$n]=$metodos->esRedsys;   
        $n++;
    }
    
}	
$database->freeResults();
    
$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"activo"=>$activo,"idrevo"=>$idrevo,"esRedsys"=>$esRedsys);

echo json_encode($json); 
/*
$file = fopen("texto2.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fclose($file);
*/
?>
