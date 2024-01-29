<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
/*
 *
 * Archivo: tpvdevolucion.php
 *
 * Version: 1.0.0
 * Fecha  : 02/01/2024
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

$array = json_decode(json_encode($_POST), true);
//$importe=$array['importe'];
//$numero=$array['$numero'];

$idpedido=$array['idpedido'];
//$idpedido=58;
$tienda=$array['tienda'];

$checking=false;

$Pedido = new RecomponePedido;
$order=$Pedido->DatosGlobalesPedido($idpedido);
$order['carrito']=$Pedido->LineasPedido($idpedido);


//$estadoPago=$order['estadoPago'];
$numero=substr($order['fecha'],0,4).$order['pedido'];
$importe=$order['total'];

//$importe='2.55';
//$numero='20242GXV1C84';
//$idpedido='92';


$tienda=0;
//$url2='?numero='.$numero.'&importe='.$importe.'&idpedido='.$idpedido.'&tienda='.$tienda;
include 'Sermepa/Tpv/Tpv.php';
$RedSys = new RedSysMio;
$datosRedsys=$RedSys->DatosRedsys($tienda); //tienda

/*
echo 'Importe: '.$importe.'<br>';
echo 'Pedido: '.$numero.'<br>';
echo 'NombreComercial: '.$datosRedsys['NombreComercial'].'<br>';
echo 'MerchantCode: '.$datosRedsys['MerchantCode'].'<br>';
echo 'MerchantKey: '.$datosRedsys['MerchantKey'].'<br>';
echo 'Terminal: '.$datosRedsys['terminal'].'<br>';
echo 'Modo: restLive'.'<br>';
echo 'url: '.$datosRedsys['url'].'<br>';
//echo 'url2:'.$url2.'<br>';
echo "<hr>";
*/
    

$redsys = new Sermepa\Tpv\Tpv();
$redsys->setAmount($importe);
$redsys->setOrder($numero);
$redsys->setMerchantcode($datosRedsys['MerchantCode']);
$redsys->setCurrency('978');
$redsys->setTransactiontype('3');
$redsys->setTerminal($datosRedsys['terminal']);
$redsys->setEnvironment('restLive'); 

$key = $datosRedsys['MerchantKey'];



try {

    
    $signature = $redsys->generateMerchantSignature($key);
    $redsys->setMerchantSignature($signature);
    $envio = $redsys->send();
    
    $response = json_decode($envio, true);
    
    $parameters = $redsys->getMerchantParameters($response['Ds_MerchantParameters']);
    $DsResponse = $parameters["Ds_Response"];
    $DsResponse += 0;
    //

    if ($redsys->check($key, $response) and ($DsResponse <= 99 or $DsResponse == 900 or $DsResponse == 400)) {
        //Si es todo correcto ya podemos hacer lo que necesitamos, para este ejemplo solo mostramos los datos.
        
        //print_r($parameters);
        $checking=true;
        
    } else {
        //acciones a realizar si ha sido erroneo
    }
} catch (Exception $e) {
    //echo $e;
}

$json=array("valid"=>$checking,"importe"=>$order['total'],"pedido"=>$order['pedido']);

ob_end_clean();
echo json_encode($json); 




?>
