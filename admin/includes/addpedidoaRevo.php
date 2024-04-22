<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
include "../../webapp/functions.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);
//file_put_contents('zz-pedido_revo.txt', print_r($array, true));
$checking=false;

$idpedido=$array['idPedido'];

//$idpedido=108;
    
$sql="SELECT tipo FROM integracion WHERE id=1";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {
    $integra = $result->fetch_object();
    $integracion=$integra->tipo;
    if ($integracion==1){
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

        $revoid= $Revo->addPedidoRevo($order,$idRedsys);

        // actualiza pedido con la id de revo y lo pone como pagado

        $sql="UPDATE pedidos SET numeroRevo='".$revoid."', estadoPago='1' WHERE id='".$idpedido."';";
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result2 = $database->execute(); 
        /*
        if ($order['cliente']>0){
            $sql="UPDATE usuarios_app SET monedero=monedero+".($order['importe_fidelizacion']-$order['monedero'])." WHERE id=".$order['cliente'].";";
            $database->setQuery($sql);
            $result3 = $database->execute(); 
        }
        */
    }
    else {
        //include 'imprimeticket.php';
        $tiket = new ImprimeTicket;
        $resultado_tiket=$tiket->generaTicket($idpedido);

    }
    $checking=true;
}

$database->freeResults();  



$json=array("valid"=>$checking);

ob_end_clean();
echo json_encode($json); 

?>
