<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

 $_POST = json_decode(file_get_contents('php://input'), true);

$array = json_decode(json_encode($_POST), true);

$checking=false;

$fichero = fopen("../version.txt", "r");

while (!feof($fichero)){ 
    $linea = fgets($fichero);
    //$checking=true;        
} 
fclose($fichero); 


$fichero = fopen("https://updates.cloud-delivery.es/version.txt", "r");

while (!feof($fichero)){ 
    $update = fgets($fichero);
    $checking=true;        
} 
fclose($fichero); 




 

$json=array("valid"=>$checking,"version"=>$linea,"update"=>$update);
echo json_encode($json); 



?>
