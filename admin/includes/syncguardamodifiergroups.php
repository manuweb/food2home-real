<?php

//*************************************************************************************************
//
//  syncguardamodificadores.php
//
//  guarda los modificadores sincronizados  
//
//*************************************************************************************************
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);

$checking=false;

//$syncimagen=$array['syncimagen'];

$sql="SELECT * FROM modifierGroups WHERE id='".$array['id']."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result->num_rows>0) {
    $sql="UPDATE modifierGroups SET nombre='".$array['nombre']."' WHERE id='".$array['id']."';";  
    
}

else {
    $sql="INSERT INTO modifierGroups (id, nombre) VALUES ('".$array['id']."','".$array['nombre']."');";  
    
}

$database->setQuery($sql);
$result = $database->execute();


if ($result) {
   $checking=true; 
}
$database->freeResults();

$json=array("valid"=>$checking);

echo json_encode($json); 



$file = fopen("zz-mGtxt.txt", "a");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "id: ". $array['id'] . PHP_EOL); 
fwrite($file, "activo: ". $array['activo'] . PHP_EOL); 
fclose($file);

?>
