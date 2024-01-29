<?php
/*
 *
 * Archivo: leepushnuevas.php
 *
 * Version: 1.0.1
 * Fecha  : 29/09/2023
 * Se usa en :masdatos.js ->MirarHora()
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

$checking=true;



    
    $sql="SELECT count(*) AS cantidad FROM push WHERE (cliente=0 OR cliente=".$array['cliente'].") AND fecha>'".$array['fecha']."';";

    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();

    // Verificar si se obtuvieron resultados
    if ($result) {
        $push = $result->fetch_object();
        $checking=true;
        $total=$push->cantidad;
    }
    $database->freeResults();




$json=array("valid"=>$checking, "total"=>$total);

echo json_encode($json); 

/*
$file = fopen("leepush.txt", "w");


fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fwrite($file, "sql: ". $sql . PHP_EOL); 


fclose($file);
*/

?>
