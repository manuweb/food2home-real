<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
header("Access-Control-Allow-Origin: *");

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
include "../../webapp/functions.php";

$Revo = new PedidosRevo;
$resultado=$Revo->deletePedidoRevo(424, $tienda=0);
print_r($resultado);
?>
