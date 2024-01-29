<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
header("Access-Control-Allow-Origin: *");

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
include "../../webapp/functions.php";


$Revo = new PedidosRevo;

$resultado=json_decode($Revo->deletePedidoRevo(343));
//echo $resultado ;
ob_end_clean();
echo '<pre>';
print_r ($resultado);
echo '</pre>';
echo 'Borrado:'.$resultado->deleted;
/*
echo '<pre>';
print_r ($resultado);
echo '</pre>';
*/

?>
