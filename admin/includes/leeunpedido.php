<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/functions.php";
header("Access-Control-Allow-Origin: *");


if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST) ) {
    $array = json_decode(json_encode($_POST), true);
}

//$numero='H137BL0T';
$idpedido=$array['idpedido'];


$checking=true;


$Pedido = new RecomponePedido;
$order=$Pedido->DatosGlobalesPedido($idpedido);
$order['carrito']=$Pedido->LineasPedido($idpedido);

$estadoPago=$order['estadoPago'];
$numero=$order['pedido'];

//file_put_contents("zz-pedido.txt", json_encode($order));
/*

$sql="SELECT orders.datos, pedidos.numero, pedidos.estadoPago FROM orders LEFT JOIN pedidos ON pedidos.id=orders.idPedido WHERE orders.idPedido='".$idpedido."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result) {
    $pedido = $result->fetch_object();
    
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
fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fwrite($file, "numero: ". $array['numero'] . PHP_EOL); 
fwrite($file, "sql: ". $sql . PHP_EOL); 
fclose($file);
*/



?>
