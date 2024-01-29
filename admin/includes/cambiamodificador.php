<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  cambiamodificador.php
//
//  Cambia dato de modificador
//
//*************************************************************************************************

$array = json_decode(json_encode($_POST), true);

$checking=false;

if ($array['cambia']=='activo'){
    $update="activo='".$array['valor']."'";
}
if ($array['cambia']=='autoseleccionado'){
    $update="autoseleccionado='".$array['valor']."'";
}
if ($array['cambia']=='nombre'){
    $update="nombre='".$array['valor']."'";
}
if ($array['cambia']=='precio'){
    $update="precio='".$array['valor']."'";
}
$sql="UPDATE modifiers SET ".$update." WHERE id='".$array['id']."';";



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
$file = fopen("zz-modif-texto.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "jason: ". json_encode($json) . PHP_EOL);
fclose($file);
*/

?>
