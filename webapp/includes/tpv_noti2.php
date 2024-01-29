<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
//*************************************************************************************************
//
//  tpv_noti.php
//
//
//*************************************************************************************************
include_once "../conexion.php";
include_once "../MySQL/DataBase.class.php";
include_once "../config.php";
include_once "../functions.php";

$sql="SELECT empresa.nombre_comercial,  redsys.MerchantCode, redsys.MerchantKey, redsys.terminal  FROM empresa LEFT JOIN redsys on redsys.id=empresa.id Where empresa.id=1";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

            
$empresa = $result->fetch_object();
$MerchantKey=$empresa->MerchantKey;

$database->freeResults(); 

$numero=$_GET['numero'];
            
$importe=$_GET['importe'];

$idpedido=$_GET['idpedido'];

$tipo='pedido';
//$url2='?pedido='.$pedido.'&importe='.$importe;

/*
include_once('Sermepa/Tpv/Tpv.php');
  try{
    $redsys = new Sermepa\Tpv\Tpv();
    $key = $MerchantKey;
   
   

    $parameters = $redsys->getMerchantParameters($_POST["Ds_MerchantParameters"]);
    $DsResponse = $parameters["Ds_Response"];
    $DsResponse += 0;
    if ($redsys->check($key, $_POST) && $DsResponse <= 99) {
*/
        $sql="SELECT tipo FROM integracion WHERE id=1";
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();

        if ($result) {
            $integra = $result->fetch_object();
            $integracion=$integra->tipo;
            if ($integracion==1){
                include 'nuevoPedidoaRevo.php';
                
                // actualiza pedido con la id de revo y lo pone como pagado
                $sql="UPDATE pedidos SET numeroRevo='".$revoid."', estadoPago='1' WHERE id='".$idpedido."';";
                $database->setQuery($sql);
                $result2 = $database->execute(); 
                echo 'SQL='.$sql."<br>";
                
            }
            else {
                include 'imprimeticket.php';
            }
            
            
            include 'envioemail.php';
        }
        $database->freeResults(); 
        
        
        /*
        $file = fopen('tpv_noti.txt', "w");
        fwrite($file, "Pedido: ". $pedido . PHP_EOL);
        fwrite($file, "importe: ". $importe . PHP_EOL);
         fwrite($file, "OK" . PHP_EOL);
        fwrite($file, "Nº: ". $parameters["Ds_Order"] . PHP_EOL);
        fwrite($file, "tarjeta: ". $parameters["Ds_Card_Number"] . PHP_EOL);
        
        fclose($file);
        */
        
        // pedido a REVO o a imprimir
        
        
    
 /*       
        
    } else {

            $file = fopen('tpv_noti.txt', "w");
            fwrite($file, "Pedido: ". $pedido . PHP_EOL);
            fwrite($file, "importe: ". $importe . PHP_EOL);
            fwrite($file, "ERROR" . PHP_EOL);
            fclose($file);

    }
} catch (\Sermepa\Tpv\TpvException $e) {
    echo $e->getMessage();
}

*/

echo 'Idrevo='.$revoid."<br>";
?>
