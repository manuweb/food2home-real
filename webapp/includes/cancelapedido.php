<?php
/*
 *
 * Archivo: cancelapedido.php
 *
 * Version: 1.0.1
 * Fecha  : 24/10/2023
 * Se usa en :comprar.js ->cancelaPedido() 
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
//*************************************************************************************************
//
//  addpedido.php
//
//  Crea pedido
//
//*************************************************************************************************

include_once "../conexion.php";
include_once "../MySQL/DataBase.class.php";


if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST) ) {

    $array = json_decode(json_encode($_POST), true);

}
$checking=false;

$idpedido=$array['idpedido'];

$sql="UPDATE pedidos SET estadoPago='-1' WHERE id='".$idpedido."' AND numeroRevo='0';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result) { 
    $checking=true;
    $database->freeResults();
    $sql="SELECT cliente, monedero FROM pedidos  WHERE id='".$idpedido."';";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();

    // Verificar si se obtuvieron resultados
    if ($result) { 
        $pedido = $result->fetch_object();
        $cliente=$pedido->cliente;
        $monedero=$pedido->monedero;
        if ($monedero>0){
            $sql="UPDATE clientes SET monedero=(monedero-".$monedero.") WHERE id='".$cliente."';";

            $database = DataBase::getInstance();
            $database->setQuery($sql);
            $result = $database->execute();
        }

    }

}

$database->freeResults();


$json=array("valid"=>$checking);

ob_end_clean();

echo json_encode($json);
?>
