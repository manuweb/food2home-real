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
include_once "../config.mail.php";
include_once "../PHPMailer/class.phpmailer.php";
include_once "../PHPMailer/class.smtp.php";

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
//$url2='?numero='.$numero.'&importe='.$importe.'&idpedido='.$idpedido;
 
include_once('Sermepa/Tpv/Tpv.php');
  try{
    $redsys = new Sermepa\Tpv\Tpv();
    $key = $MerchantKey;
   
   

    $parameters = $redsys->getMerchantParameters($_POST["Ds_MerchantParameters"]);
    $DsResponse = $parameters["Ds_Response"];
    $DsResponse += 0;
    if ($redsys->check($key, $_POST) && $DsResponse <= 99) {
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
                $database->setQuery($sql);
                $result2 = $database->execute(); 
                        
                if ($order['cliente']>0){
                    $sql="UPDATE usuarios_app SET monedero=monedero+".($order['importe_fidelizacion']-$order['monedero'])." WHERE id=".$order['cliente'].";";
                    $database->setQuery($sql);
                    $result3 = $database->execute(); 
                }
            }
            else {
                //include 'imprimeticket.php';
                $tiket = new ImprimeTicket;
                $resultado_tiket=$tiket->generaTicket($idpedido);
                
            }
            
            $miMail = new MisMails;
            $datos=$miMail->BuscaDatosMail();

            $phpmailer = new PHPMailer();
            $phpmailer->CharSet = 'UTF-8';
            $phpmailer->Username = $datos['Username'];
            $phpmailer->Password = $datos['Password'];
            $phpmailer->SMTPSecure = $datos['SMTPSecure'];
            $phpmailer->Host = $datos['Host'];
            $phpmailer->Port = $datos['Port'];
            $phpmailer->SMTPAuth = true;
            $phpmailer->Sender = $datos['Sender'];;
            $phpmailer->SetFrom($datos['Sender'], $datos['NombreEmpresa']);
            $phpmailer->IsHTML(true);
            $Pedido = new RecomponePedido;
            $order=$Pedido->DatosGlobalesPedido($idpedido);
            $order['carrito']=$Pedido->LineasPedido($idpedido);
            $para=$order['email'];
            $datos=$miMail->CreaBodyTextoPedido($order);   
            
            $phpmailer->Subject = $datos['subject'];
            $phpmailer->AddAddress($para); // recipients email

            $phpmailer->Body =$miMail->CreaHeadMail();
            $phpmailer->Body .= iconv("UTF-8", "UTF-8",$datos['textomail']);
            $phpmailer->Body .= iconv("UTF-8", "UTF-8",$miMail->CreaPieMail());


            if ($phpmailer->send()) {
                $checking=true;
                $msg="Mail enviado correctamente"; 

            } else {
                $msg="Error enviando mail"; 
                //echo 'Mailer Error: ' . $mail->ErrorInfo;
            }

        }
        $database->freeResults(); 
        
        
        /*
        $file = fopen('tpv_noti.txt', "w");
        fwrite($file, "Pedido: ". $idpedido . PHP_EOL);
        fwrite($file, "Numero: ". $numero . PHP_EOL);
        fwrite($file, "importe: ". $importe . PHP_EOL);
         fwrite($file, "OK" . PHP_EOL);
        fwrite($file, "Nº: ". $parameters["Ds_Order"] . PHP_EOL);
        fwrite($file, "tarjeta: ". $parameters["Ds_Card_Number"] . PHP_EOL);
        
        fclose($file);
        */
        
        // pedido a REVO o a imprimir
        
        
    
        
        
    } else {

            $file = fopen('tpv_noti.txt', "w");
            fwrite($file, "Pedido: ". $idpedido . PHP_EOL);
        fwrite($file, "Numero: ". $numero . PHP_EOL);
            fwrite($file, "importe: ". $importe . PHP_EOL);
            fwrite($file, "ERROR" . PHP_EOL);
        fwrite($file, "Nº: ". $parameters["Ds_Order"] . PHP_EOL);
        fwrite($file, "tarjeta: ". $parameters["Ds_Card_Number"] . PHP_EOL);
            fclose($file);

    }
} catch (\Sermepa\Tpv\TpvException $e) {
    echo $e->getMessage();
}
?>
