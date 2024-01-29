<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}

$array = json_decode(json_encode($_POST), true);
$checking=false;


$sql="select count(id) as total FROM usuarios_app;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) { 
    $usus = $result->fetch_object();
    $total=$usus->total;
}
$database->freeResults();

$sql="select grupo_clientes.id, grupo_clientes.nombre, grupo_clientes.porcentaje, grupo_clientes.activo, count(usuarios_app.grupoclientes) AS cantidad FROM grupo_clientes LEFT JOIN usuarios_app on grupo_clientes.id=usuarios_app.grupoclientes GROUP BY grupo_clientes.id;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) { 
    while ($grupos = $result->fetch_object()) {   
        $checking=true;
        $id[]=$grupos->id;
        $nombre[]=$grupos->nombre;
        $porcentaje[]=$grupos->porcentaje;
        $activo[]=$grupos->activo;
        $cantidad[]=$grupos->cantidad;
        $usuarios[]=$total;          
    } 
}	

$database->freeResults();

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"porcentaje"=>$porcentaje,"activo"=>$activo,"cantidad"=>$cantidad,"usuarios"=>$usuarios);

echo json_encode($json); 

/*
$file = fopen("texto.txt", "w");
fwrite($file, "sql: ".$sql. PHP_EOL);
fwrite($file, "usus: ".$total. PHP_EOL);
fwrite($file, "json: ".json_encode($json). PHP_EOL);
fclose($file);

*/

?>
