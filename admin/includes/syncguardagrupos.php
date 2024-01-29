<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);


$checking=false;

$syncimagen=$array['syncimagen'];

$sql="SELECT * FROM grupos WHERE id='".$array['id']."';";




$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

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
     if ($syncimagen=='true'){
        $sql="INSERT INTO grupos (id, nombre, orden, imagen, impuesto, activo,imagen_app) VALUES ('".$array['id']."','".$array['nombre']."', '".$array['orden']."', '".$array['imagen']."', '".$array['impuesto']."', '".$array['activo']."','');";   
    }
    else {
     $sql="INSERT INTO grupos (id, nombre, orden, imagen, impuesto, activo,imagen_app) VALUES ('".$array['id']."','".$array['nombre']."', '".$array['orden']."', '', '".$array['impuesto']."', '".$array['activo']."','');"; 
    }   
    
}


$database->setQuery($sql);
$result = $database->execute();


if ($result->num_rows>0) {
   $checking=true; 
}
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
