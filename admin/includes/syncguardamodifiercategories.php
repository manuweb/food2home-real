<?php
//******************************************************
//
//  syncguardamodificadores.php
//
//  guarda los modificadores sincronizados  
//
//
//********************************************************
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);

$checking=false;

//$syncimagen=$array['syncimagen'];




$database = DataBase::getInstance();



$sql2="SELECT id FROM modifiers WHERE category_id='".$array['id']."';";

$database->setQuery($sql2);
$resultMod = $database->execute();

$lista=""; 

if ($resultMod){
    while ($modifiers = $resultMod->fetch_object()) {
         $lista.=$modifiers->id.",";
    }
    

    
    
    $lista=trim($lista, ',');

}

$sql="SELECT * FROM modifierCategories WHERE id='".$array['id']."';";


$database->setQuery($sql);
$resultModCat = $database->execute();

if ($resultModCat->num_rows>0){

    $sql="UPDATE modifierCategories SET nombre='".$array['nombre']."', activo='".$array['activo']."', opciones='".$array['opciones']."', forzoso='".$array['forzoso']."', modificadores='".$lista."'  WHERE id='".$array['id']."';";  

}

else {
    $sql="INSERT INTO modifierCategories (id, nombre, activo, opciones, forzoso, modificadores) VALUES ('".$array['id']."','".$array['nombre']."','".$array['activo']."','".$array['opciones']."','".$array['forzoso']."','".$lista."');";  

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

$file = fopen("zzzMc.txt", "a");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "sql2: ". $sql2 . PHP_EOL);
fwrite($file, "id: ". $array['id'] . PHP_EOL); 

fclose($file);
*/
?>
