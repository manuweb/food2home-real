<?php
/*
 *
 * Archivo: pedidopagado.php
 *
 * Version: 1.0.1
 * Fecha  : 24/10/2023
 * Se usa en :comprar.js ->finalizaPedido() 
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
include_once "../conexion.php";
include_once "../MySQL/DataBase.class.php";


if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST) ) {

    $array = json_decode(json_encode($_POST), true);

}

$idpedido=$array['idpedido'];
$idrevo=$array['idrevo'];

$checking=false; 


$sql="UPDATE pedidos SET numeroRevo='".$idrevo."' WHERE id='".$idpedido."';";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result) { 
    $checking=true;

}
$database->freeResults();

$json=array("valid"=>$checking);

ob_end_clean();

echo json_encode($json);

?>
