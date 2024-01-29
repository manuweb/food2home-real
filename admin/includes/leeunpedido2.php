<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
header("Access-Control-Allow-Origin: *");
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/functions.php";


$Pedido = new RecomponePedido;
$datos=$Pedido->DatosGlobalesPedido(5);
$lineas=$Pedido->LineasPedido(5);
echo '<pre>';
print_r ($datos);
echo '</pre>';
echo '<pre>';
print_r ($lineas);
echo '</pre>';

?>
