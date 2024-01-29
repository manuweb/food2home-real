<?php

//*************************************************************************************************
//
//  syncguardamenucategories.php
//
//  guarda los menucategories sincronizados  
//
//*************************************************************************************************
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);

$checking=false;



$sql="SELECT * FROM MenuCategories WHERE id='".$array['id']."';";




//id:id[n], nombre:nombre[n], orden:orden[n], eleMulti:eleMulti[n], min:min[n], max:max[n], producto:producto[n]}, 
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result->num_rows>0) {
    
    $sql="UPDATE MenuCategories SET nombre='".$array['nombre']."', orden='".$array['orden']."', eleMulti='".$array['eleMulti']."', min='".$array['min']."', max='".$array['max']."', producto='".$array['producto']."'  WHERE id='".$array['id']."';";  
    
}

else {
    $sql="INSERT INTO MenuCategories (id, nombre, orden, eleMulti, min, max, producto) VALUES ('".$array['id']."','".$array['nombre']."','".$array['orden']."','".$array['eleMulti']."','".$array['min']."','".$array['max']."','".$array['producto']."');";  
    
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

$file = fopen("zz-mC.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "id: ". $array['id'] . PHP_EOL); 
 
fclose($file);
*/

?>
