<?php
/*
 *
 * Archivo: tpv.php
 *
 * Version: 1.0.0
 * Fecha  : 29/11/2022
 * Se usa en :comprar.js ->terminarcompra() (if (tarjeta==1) )
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

include "../../webapp/conexion.php";
include "../../webapp/MySQL-2/DataBase.class.php";
include "../../webapp/config.php";

$http=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$nombre_archivo_me_llama=basename($_SERVER['SCRIPT_NAME']);
    
$url=$http.'://' . $_SERVER["HTTP_HOST"] .$_SERVER["REQUEST_URI"];
$url=str_replace($nombre_archivo_me_llama,'',$url);
$partes=explode("?", $url);
$url=$partes[0];

$array = json_decode(json_encode($_GET), true);

$importe=$array['importe'];
//$nombre=$array['nombre'];
//$numero=$array['numero'];
//$mm=$array['mm'];
//$aa=$array['aa'];
//$csv=$array['csv'];
$numero=$array['order'];
$idpedido=$array['idpedido'];
$mm=$array['mm'];
$tarjeta=$array['num_tar'];
$aa=$array['aa'];
$url2='?numero='.$numero.'&importe='.$importe.'&idpedido='.$idpedido;
    //importe='+importe+'&order='+obj.order+'&nombre='+(nombre+' '+apellidos);
 

include_once('Sermepa/Tpv/Tpv.php');
$sql="SELECT empresa.nombre_comercial,  redsys.MerchantCode, redsys.MerchantKey, redsys.terminal  FROM empresa LEFT JOIN redsys on redsys.id=empresa.id Where empresa.id=1";

$db = DataBase::getInstance();  
$db->setQuery($sql);  
$empresa = $db->loadObjectList();  
//$db->freeResults();



if (count($empresa)>0) {
    $checking=true;
    $nombre_comercial=$empresa[0]->nombre_comercial;
    $MerchantCode=$empresa[0]->MerchantCode;
    $MerchantKey=$empresa[0]->MerchantKey;
    $terminal=$empresa[0]->terminal;
    $modo='live';
    
    if($MerchantCode==''){
        $MerchantCode='999008881';
        
    }
    if($MerchantKey==''){
        $MerchantKey='sq7HjrUOBfKmC576ILgskD5srU870gJ7';
    }
    if($MerchantKey=='sq7HjrUOBfKmC576ILgskD5srU870gJ7'){
        $modo='test';
    }
    
    

}	

//importe='+importe+'&order='+obj.order+'&nombre='+(nombre+' '+apellidos);
try{
    //Key de ejemplo
    //$key = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
    //$MerchantKey

    $redsys = new Sermepa\Tpv\Tpv();
    //$redsys->setAmount(rand(10,600));
    $redsys->setAmount($importe);
    
    //$redsys->setOrder(time());
    $redsys->setOrder(date("Y").$numero);
    $redsys->setMerchantcode($MerchantCode);  //Reemplazar por el código que proporciona el banco
    $redsys->setCurrency('978');
    $redsys->setTransactiontype('0');
    $redsys->setTerminal('00'.$terminal);
    $redsys->setMethod('C'); //Solo pago con tarjeta, no mostramos iupay
    $redsys->setNotification($url.'tpv_noti.php'.$url2); //Url de notificacion
    //$redsys->setPan($tarjeta); 
    //$redsys->setExpiryDate($aa.$mm);
    
    $redsys->setUrlOk($url.'tpv_ok.php'.$url2); //Url OK
    $redsys->setUrlKo($url.'tpv_ko.php'.$url2); //Url KO

    $redsys->setVersion('HMAC_SHA256_V1');
    $redsys->setTradeName($nombre_comercial);

    $redsys->setProductDescription('Pedido on line');
    $redsys->setEnvironment($modo); 
    //Entorno test 
    // live para produccion
    

/*
    $redsys->setPan($numero); //Número de la tarjeta
    $redsys->setExpiryDate($aa.$mm); //AAMM (año y mes)
    $redsys->setCVV2($csv); //CVV2 de la tarjeta

*/
    
    //$redsys->setIdentifier();
    
    $signature = $redsys->generateMerchantSignature($MerchantKey);
    $redsys->setMerchantSignature($signature);

    $form = $redsys->createForm();
    
    
    
} catch (\Sermepa\Tpv\TpvException $e) {
    echo $e->getMessage();
}
echo $form;


?>
    <script>

    window.onload=function(){

                // Una vez cargada la página, el formulario se enviara automáticamente.

		document.forms["redsys_form"].submit();

    }

    </script>
<?php

?>
