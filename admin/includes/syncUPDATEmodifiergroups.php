<?php
//********************************************************
//
//  syncUPDATEmodifiergroups.php
//
//  guarda los modificadores sincronizados  
//
//*************************************************************************************************
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
/*
TRUNCATE `MenuCategories`;
TRUNCATE `MenuItems`;
TRUNCATE `modifierCategories`;
TRUNCATE `modifierGroups`;
TRUNCATE `modifiers`;

*/
$array = json_decode(json_encode($_POST), true);

$checking=false;



$sql="UPDATE modifierGroups SET modifierCategories_id='".$array['categoria']."' WHERE id='".$array['id']."';";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {
   $checking=true; 
}
$database->freeResults();


$json=array("valid"=>$checking);

echo json_encode($json); 



$file = fopen("zzz1.txt", "a");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "id: ". $array['id'] . PHP_EOL); 
fwrite($file, "activo: ". $array['activo'] . PHP_EOL); 
fclose($file);

?>
