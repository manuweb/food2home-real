<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
/*
 *
 * Archivo: leeunpedido.php
 *
 * Version: 1.0.1
 * Fecha  : 02/10/2023
 * Se usa en :masdatos.js ->verpedido()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/functions.php";
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST) ) {
    $array = json_decode(json_encode($_POST), true);
}
//$array = json_decode(json_encode($_POST), true);


//$numero='H137BL0T';
$idpedido=$array['idpedido'];


$checking=true;


$Pedido = new RecomponePedido;
$order=$Pedido->DatosGlobalesPedido($idpedido);
$order['carrito']=$Pedido->LineasPedido($idpedido);

$estadoPago=$order['estadoPago'];
$numero=$order['pedido'];

/*

$sql="SELECT orders.datos, pedidos.numero, pedidos.estadoPago FROM orders LEFT JOIN pedidos ON pedidos.id=orders.idPedido WHERE orders.idPedido='".$idpedido."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result) {
    $pedido = $result->fetch_object();
    $database->freeResults();
    $datos=$pedido->datos;
    $estadoPago=$pedido->estadoPago;
    $numero=$pedido->numero;
    $order = json_decode($datos,JSON_UNESCAPED_UNICODE);
    $checking=true;

} 
$database->freeResults();  
*/

$json=array("valid"=>$checking, "order"=>$order, "estadoPago"=>$estadoPago, "numero"=>$numero);

ob_end_clean();
echo json_encode($json); 

/*
$file = fopen("leeunpedido.txt", "w");


fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fwrite($file, "numero: ". $array['numero'] . PHP_EOL); 
fclose($file);
*/
?>
