<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

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


include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include_once "../../webapp/config.php";
include "../../webapp/functions.php";



$array = json_decode(json_encode($_POST), true);


$idpedido=$array['idpedido'];
$idDelivery=$array['delivery'];


$Pedido = new RecomponePedido;
$order=$Pedido->DatosGlobalesPedido($idpedido);
$order['carrito']=$Pedido->LineasPedido($idpedido);

$checking=false;
$resultado='error';


if ($order['metodo']==1){
    // delivery
    
    $delivery = new Delivery;
    $datosD=$delivery->leeDeliverys($idDelivery);
    $variables=$delivery->leeLogicaDeliverys($datosD['logica']);

    
    $resultado=$delivery->enviaDeliverys($idDelivery,$variables,$order);
    
    
    $checking=true;
    $sql="INSERT INTO pedidos_delivery (id, idpedido, resultado) VALUES (NULL, '".$idpedido."', '".$resultado."');";
    
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) {
        $checking=true;
    }
    $database->freeResults(); 
    
    echo $sql;
}
else {
    $checking=true;
    $resultado='No es delivery';
}


$json=array("valid"=>$checking, "msg"=>$resultado);

ob_end_clean();


echo json_encode($json);



?>
