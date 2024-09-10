<?php
/*
 *
 * Archivo: pedidoaRevo.php
 *
 * Version: 1.0.1
 * Fecha  : 04/01/2024
 * Se usa en :comprar.js ->finalizaPedido() 
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
ini_set('display_errors', 1);

error_reporting(E_ALL); 
    
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');


include_once "../conexion.php";
include_once "../MySQL/DataBase.class.php";
include_once "../config.php";
include_once "../functions.php";
include_once "../config.mail.php";
include_once "../PHPMailer/class.phpmailer.php";
include_once "../PHPMailer/class.smtp.php";
$array = json_decode(json_encode($_POST), true);

$idpedido=$array['idpedido'];
$numero=$array['numero'];   

//$idpedido = 157;
//$numero = 'T9RDO5JW';

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
$email=$order['email'];
$resultado= $Revo->addPedidoRevo($order,$idRedsys);

$tarjetasRegalo=[];
$carrito=$order['carrito'];
for ($x=0;$x<count($carrito);$x++){
    if($carrito[$x]['menu']==5){
        $sql="UPDATE tarjetas_regalo SET idRevo=".$resultado." WHERE idPedido=".$idpedido." AND idProducto=".$carrito[$x]['id'].";";
        
        $database->setQuery($sql);
        $result = $database->execute();
        $sql="SELECT uuid,nombre,email,precio FROM tarjetas_regalo WHERE idPedido=".$idpedido." AND idProducto=".$carrito[$x]['id'].";";
        $database->setQuery($sql);
        $resultT = $database->execute();
        while ($lintar = $resultT->fetch_object()) {
            $tarjetasRegalo[]=[
                'uuid'=>$lintar->uuid,
                'nombre'=>$lintar->nombre,
                'precio'=>$lintar->precio,
                'email'=>$lintar->email  
            ];
        }
        
        
    }
}


if (count($tarjetasRegalo)>0){
    $tr= $Revo->addTarjetasRegalo($tarjetasRegalo);
    $TRegalo = new TarjetaRegalo;
    
    for ($x=0;$x<count($tarjetasRegalo);$x++){
        $latarjeta=[
            'uuid'=>$tarjetasRegalo[$x]['uuid'],
            'nombre'=>$tarjetasRegalo[$x]['nombre'],
            'precio'=>$tarjetasRegalo[$x]['precio'],
            'nom_remite'=>$order['nombre'],
            'ape_remite'=>$order['apellidos']
        ];
        $latr= $TRegalo->creaTarjeta($latarjeta);
        
        
        $miMail = new MisMails;
        $datosM=$miMail->BuscaDatosMail();

        $phpmailer = new PHPMailer();
        $phpmailer->CharSet = 'UTF-8';
        $phpmailer->Username = $datosM['Username'];
        $phpmailer->Password = $datosM['Password'];
        $phpmailer->SMTPSecure = $datosM['SMTPSecure'];
        $phpmailer->Host = $datosM['Host'];
        $phpmailer->Port = $datosM['Port'];
        $phpmailer->SMTPAuth = true;
        $phpmailer->Sender = $datosM['Sender'];;
        $phpmailer->SetFrom($datosM['Sender'], $datosM['NombreEmpresa']);
        $phpmailer->IsHTML(true);

        //$cco=false;
        
        $datos=$miMail->CreaBodyTextoTarjetaRegalo($latarjeta);
        $para=$tarjetasRegalo[$x]['email'];
        $phpmailer->Subject = $datos['subject'];
        $phpmailer->AddAddress($para); // recipients email

        $phpmailer->Body =$miMail->CreaHeadMail();
        $phpmailer->Body .= iconv("UTF-8", "UTF-8",$datos['textomail']);
        $phpmailer->Body .= iconv("UTF-8", "UTF-8",$miMail->CreaPieMail());
        $phpmailer->addAttachment(__DIR__.'/includes/tarjetas/tr-'.$tarjetasRegalo[$x]['uuid'].'.png');

        if ($phpmailer->send()) {
            //echo "Mail enviado correctamente"; 

        } else {
            //echo "Error enviando mail<br>"; 
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
        
    }
    
}

//file_put_contents('zz-pedidoaRevo.txt', print_r($order, true));

$json=array("valid"=>true, "idRevo"=>$resultado);

ob_end_clean();


echo json_encode($json);



?>
