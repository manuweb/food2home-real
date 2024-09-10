
<?php

/*
 *
 * Archivo: imprimetcket.php
 *
 * Version: 1.0.2
 * Fecha  : 25/01/2024
 * Se usa en :si integracion =2 despues de enviar pedido
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



$tiket = new ImprimeTicket;
for ($x=0;$x<$array['copias'];$x++){
    $resultado=$tiket->generaTicket($array['idpedido']);
}

$json=array("valid"=>$resultado);
ob_end_clean();

echo json_encode($json);




?>
