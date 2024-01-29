<?php
/*
 *
 * Archivo: cambiapreferenciadireccion.php
 *
 * Version: 1.0.1
 * Fecha  : 03/10/2023
 * Se usa en :masdatos.js ->cambiapreferenciadireccion()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;



$sql="UPDATE domicilios SET preferida=0 WHERE usuario='".$array['id']."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result ) {
     $checking=true;
}

$sql="UPDATE domicilios SET preferida=1 WHERE id='".$array['iddomi']."';";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result ) {
    $checking=true;  
}	

$database->freeResults();  

$json=array("valid"=>$checking);


echo json_encode($json); 


/*
$file = fopen("buscadirecciones.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);

fclose($file);
*/

?>
