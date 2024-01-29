<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
header("Access-Control-Allow-Origin: *");

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
include "../../webapp/functions.php";


$Revo = new PedidosRevo;
$datos=$Revo->BuscaDatos();

$Pedido = new RecomponePedido;
$order=$Pedido->DatosGlobalesPedido(52);
$order['carrito']=$Pedido->LineasPedido(52);

$resultado= $Revo->addPedidoRevo($order);

echo $resultado;
/*
echo '<pre>';
print_r ($resultado);
echo '</pre>';
*/

?>
