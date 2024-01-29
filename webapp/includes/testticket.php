<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
/*
 *
 * Archivo: envioemail.php
 *
 * Version: 1.0.1
 * Fecha  : 29/09/2023
 * Se usa en :varios sitios
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include_once "../conexion.php";
include_once "../MySQL/DataBase.class.php";
include_once "../config.php";
include_once "../functions.php";






$tiket = new ImprimeTicket;
$resultado=$tiket->generaTicket(71);

echo "Resultado=".$resultado;




?>
