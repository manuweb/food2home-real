<?php
/*
 *
 * Archivo: pedidoaRevo.php
 *
 * Version: 1.0.1
 * Fecha  : 04/01/2024
 * Se usa en :comprar.js ->finalizaPedido() 
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

$array = json_decode(json_encode($_POST), true);

$idpedido=$array['idpedido'];
//$numero=$array['numero'];   

//$idpedido = 89;
//$numero = 'PEZNMLGQ';

$idRedsys=0;
$sql="SELECT id, idrevo FROM metodospago WHERE esRedsys=1;";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $redsys = $result->fetch_object();
    $idRedsys=$redsys->idrevo;
}


$Revo = new PedidosRevo;
$datos=$Revo->BuscaDatos();

$Pedido = new RecomponePedido;
$order=$Pedido->DatosGlobalesPedido($idpedido);
$order['carrito']=$Pedido->LineasPedido($idpedido);

$resultado= $Revo->addPedidoRevo($order,$idRedsys);


$json=array("valid"=>true, "idRevo"=>$resultado);

ob_end_clean();


echo json_encode($json);



?>
