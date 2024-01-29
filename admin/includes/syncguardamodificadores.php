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

$sql="SELECT * FROM modifiers WHERE id='".$array['id']."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result->num_rows>0) {
    $sql="UPDATE modifiers SET nombre='".$array['nombre']."', activo='".$array['activo']."', precio='".$array['precio']."', category_id='".$array['category_id']."', autoseleccionado='".$array['autoseleccionado']."' WHERE id='".$array['id']."';";  
    
}

else {
    $sql="INSERT INTO modifiers (id, nombre, precio, activo, category_id, autoseleccionado) VALUES ('".$array['id']."','".$array['nombre']."', '".$array['precio']."', '".$array['activo']."', '".$array['category_id']."', '".$array['autoseleccionado']."');";  
    
}

$database->setQuery($sql);
$result = $database->execute();


if ($result) {
   $checking=true; 
}
$database->freeResults();


$json=array("valid"=>$checking);

echo json_encode($json); 


/*
$file = fopen("txt.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "id: ". $array['id'] . PHP_EOL); 
fwrite($file, "activo: ". $array['activo'] . PHP_EOL); 
fclose($file);
*/
?>
