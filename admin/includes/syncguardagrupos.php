<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);


$checking=false;

$syncimagen=$array['syncimagen'];
$syncimagen_png=$array['syncimagen_png'];

$sql="SELECT * FROM grupos WHERE id='".$array['id']."';";




$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

$existe='UPDATE';
// Verificar si se obtuvieron resultados
if ($result->num_rows>0) {
    $checking=true;
    if ($syncimagen=='true'){
        $sql="UPDATE grupos SET nombre='".$array['nombre']."', orden='".$array['orden']."', imagen='".$array['imagen']."', impuesto='".$array['impuesto']."', activo='".$array['activo']."' WHERE id='".$array['id']."';";   
    }
    else {
     $sql="UPDATE grupos SET nombre='".$array['nombre']."', orden='".$array['orden']."', impuesto='".$array['impuesto']."', activo='".$array['activo']."' WHERE id='".$array['id']."';";  
    }
}

else {
    $existe='NEW';
     if ($syncimagen=='true'){
        $sql="INSERT INTO grupos (id, nombre, orden, imagen, impuesto, activo,imagen_app) VALUES ('".$array['id']."','".$array['nombre']."', '".$array['orden']."', '".$array['imagen']."', '".$array['impuesto']."', '".$array['activo']."','');";   
    }
    else {
     $sql="INSERT INTO grupos (id, nombre, orden, imagen, impuesto, activo,imagen_app) VALUES ('".$array['id']."','".$array['nombre']."', '".$array['orden']."', '', '".$array['impuesto']."', '".$array['activo']."','');"; 
    }   
    
}


$database->setQuery($sql);
$result = $database->execute();

$file = fopen("syncgrupos.txt", "a+");
fwrite($file,  PHP_EOL); 

fwrite($file, "Grupo: ".$array['nombre']. ' ('.$array['id'].')'); 

if ($result) {
   $checking=true; 
    fwrite($file, " - ".$existe." - OK".  PHP_EOL); 
}
else {
    fwrite($file, " - ".$existe." - KO".  PHP_EOL);
}
fclose($file);
$database->freeResults();

$json=array("valid"=>$checking);

echo json_encode($json); 


/*
$file = fopen($array['id'].".txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "id: ". $array['id'] . PHP_EOL); 
fwrite($file, "activo: ". $array['activo'] . PHP_EOL); 
fclose($file);
*/
?>
