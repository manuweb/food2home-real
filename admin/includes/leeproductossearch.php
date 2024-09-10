<?php
/*
 *
 * Archivo: leeproductossearch.php
 *
 * Version: 1.0.0
 * Fecha  : 25/11/2022
 * Se usa en :tienda.js ->muestraproductoinicio()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";

//*************************************************************************************************
//
//  leeproductos.php
//
//  Lee datos de la tabla productos 
//
//  Llamado desde tienda.js ->muestraproductos()
//
//*************************************************************************************************
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);


$checking=false;




$sql="SELECT productos.id, productos.nombre FROM productos WHERE 1 ORDER BY productos.nombre";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();


if ($result->num_rows>0) {
    $checking=true;
    $n=0;
    while ($grupo = $result->fetch_object()) {

        $id[]=$grupo->id;
        $nombre[]=$grupo->nombre;
        
    }
    $checking=true;
}

$database->freeResults(); 
 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre);

echo json_encode($json); 
/*
$file = fopen("txt.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "json: ". json_encode($json) . PHP_EOL);

fclose($file);
*/
?>
