<?php

//*************************************************************************************************
//
//  syncguardamenumenuitem.php
//
//  guarda los menuitem sincronizados  
//
//*************************************************************************************************
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);

$checking=false;



$sql="SELECT * FROM MenuItems WHERE id='".$array['id']."';";


//id:id[n], activo:activo[n], orden:orden[n], precio:precio[n], producto:producto[n], category_id:category_id[n], modifier_group_id:modifier_group_id[n], addPrecioMod:addPrecioMod[n]},

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result->num_rows>0) {
    
    $sql="UPDATE MenuItems SET activo='".$array['activo']."', orden='".$array['orden']."', precio='".$array['precio']."', producto='".$array['producto']."', category_id='".$array['category_id']."', modifier_group_id='".$array['modifier_group_id']."', addPrecioMod='".$array['addPrecioMod']."'  WHERE id='".$array['id']."';";  
    
}

else {
    $sql="INSERT INTO MenuItems (id, activo, orden, precio, producto, category_id, modifier_group_id,addPrecioMod) VALUES ('".$array['id']."','".$array['activo']."','".$array['orden']."','".$array['precio']."','".$array['producto']."','".$array['category_id']."','".$array['modifier_group_id']."','".$array['addPrecioMod']."');";  
    
}

 

$database->setQuery($sql);
$result = $database->execute();


if ($result) {
   $checking=true; 
}
$database->freeResults();

$json=array("valid"=>$checking);

echo json_encode($json); 




//$file = fopen("zzz2.txt", "a");
//fwrite($file, "sql: ". $sql . PHP_EOL);
//fwrite($file, "id: ". $array['id'] . PHP_EOL); 
//fclose($file);

?>
