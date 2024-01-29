<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
/*
 *
 * Archivo: imprimetcket.php
 *
 * Version: 1.0.2
 * Fecha  : 25/01/2024
 * Se usa en :si integracion =2 
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

include_once "../../webapp/conexion.php";
include_once "../../webapp/MySQL/DataBase.class.php";
include_once "../../config.php";
include_once "../../webapp/functions.php";

$array = json_decode(json_encode($_POST), true);



$tiket = new ImprimeTicket;
$resultado=$tiket->generaTicket($array['idpedido']);

$json=array("valid"=>$resultado);
ob_end_clean();

echo json_encode($json);




?>
