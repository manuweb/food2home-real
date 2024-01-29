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
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
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


        $sql="SELECT tipo FROM integracion WHERE id=1";
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();

        if ($result) {
            $integra = $result->fetch_object();
            $integracion=$integra->tipo;
            if ($integracion==1){
                include 'pedidoaRevo.php';
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
        
        
    
        
        
    
?>
