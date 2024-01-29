<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  ordengrupomodificador.php
//
//  guarda el orden da las categorias en modifierGroups
//
//  Llamado desde modificadores.js -> muestrgrupoamodificadores()
//
//*************************************************************************************************
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;
$cate=$array['cate'];
$reemplazo=$cate[0];
for ($x=1;$x<count($array['cate']);$x++){
    $reemplazo=$reemplazo.",".$cate[$x];
}

$sql="UPDATE modifierGroups SET modifierCategories_id='".$reemplazo."' WHERE id='".$array['id']."';";

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
$file = fopen("guardagrupomodificadores.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);


fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fclose($file);
*/

?>
