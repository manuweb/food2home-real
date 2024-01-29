<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;

for ($n=0;$n<count($array['productos']);$n++){
    $sql="UPDATE productos SET orden='".$n."' WHERE id='".$array['productos'][$n]."' AND tienda='".$array['tienda']."';";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    $database->freeResults();
  
    $checking=true;
}

$json=array("valid"=>$checking);

echo json_encode($json); 

/*
$file = fopen("texto.txt", "w");
fwrite($file, "categoria: ". $array['categoria'] . PHP_EOL);
fwrite($file, "elementos: ". count($array['productos']). PHP_EOL);

fwrite($file, "El 0: ". $array['productos'][0] . PHP_EOL);
fwrite($file, "El 1: ". $array['productos'][1] . PHP_EOL);
fclose($file);
*/
?>
