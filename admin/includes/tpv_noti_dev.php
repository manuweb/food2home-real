<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
/*
 *
 * Archivo: tpv_noti_dev.php
 *
 * Version: 1.0.0
 * Fecha  : 02/01/2024
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/functions.php";

$numero=$_GET['numero'];           
$importe=$_GET['importe'];
$idpedido=$_GET['idpedido'];
$tienda=$_GET['tienda'];

$RedSys = new RedSysMio;
$datosRedsys=$RedSys->DatosRedsys($tienda); //tienda

include_once('../../webapp/includes/Sermepa/Tpv/Tpv.php');


try{
    $redsys = new Sermepa\Tpv\Tpv();   
    $parameters = $redsys->getMerchantParameters($_POST["Ds_MerchantParameters"]);
    $DsResponse = $parameters["Ds_Response"];
    $DsResponse += 0;
    if ($redsys->check($datosRedsys['MerchantKey'], $response) and ($DsResponse <= 99 or $DsResponse == 900 or $DsResponse == 400)) {
        // ok
        echo 'OK';
        
    } 
    else {
        // NO ok
        echo 'NO OK';
            
    }
} catch (\Sermepa\Tpv\TpvException $e) {
    echo $e->getMessage();
}
?>
