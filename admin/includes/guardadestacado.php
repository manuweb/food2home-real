<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  guardadestacado.php
//
//  Lee datos de la tabla destacaos 
//
//  Llamado desde productosdestacados.js ->guardadestacado()
//
//*************************************************************************************************


$array = json_decode(json_encode($_POST), true);

$checking=false;


if ($array['inicio']!=true){
    $array['inicio']=0;
}
else {
    $array['inicio']=1;
}
if ($array['catalogo']!=true){
    $array['catalogo']=0;
}
 else {
    $array['catalogo']=1;
}   
$sql="INSERT INTO destacados (producto,inicio, catalogo, orden) VALUES (".$array['id'].",".$array['inicio'].",".$array['catalogo'].",".$array['orden'].");";



$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $checking=true;

}
$database->freeResults();
$json=array("valid"=>$checking);

echo json_encode($json); 

/*
$file = fopen("zz-destacadostxt.txt", "w");

fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "id: ". $array['id'] . PHP_EOL);

fwrite($file, "inicio: ". $array['inicio'] . PHP_EOL);
fwrite($file, "catalogo: ". $array['catalogo'] . PHP_EOL);
fwrite($file, "json: ". json_encode($json) . PHP_EOL);

fclose($file);
*/

?>

