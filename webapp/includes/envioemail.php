<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
/*
 *
 * Archivo: envioemail.php
 *
 * Version: 1.0.1
 * Fecha  : 29/09/2023
 * Se usa en :varios sitios
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
include_once "../config.mail.php";
include_once "../PHPMailer/class.phpmailer.php";
include_once "../PHPMailer/class.smtp.php";
include_once "../functions.php";
//include_once "enviaemailcss.php";


$array = json_decode(json_encode($_POST), true);
// Se están enviando datos a través del método POST


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


$checking=false;


if ($array['tipo']=='contacto'){
        $contact=[
        'nombre'=>$array['nombre'],
        'email'=>$array['email'],
        'telefono'=>$array['telefono'],
        'comentario'=>$array['comentario']
    ];
    $datos=$miMail->CreaBodyTextoContacto($contact);
    $para=MAILEMPRESA;
}

if ($array['tipo']=='recupera'){
    $para=$array['mail'];
    $datos=$miMail->CreaBodyTextoRecupera($para);  
    
    
}

if ($array['tipo']=='nuevo'){
    $datos=$miMail->CreaBodyTextoNuevoUsuario($array['usuario']);
    $para=$array['usuario'];
}

if ($array['tipo']=='pedido'){
    $Pedido = new RecomponePedido;
    $order=$Pedido->DatosGlobalesPedido($array['idpedido']);
    $order['carrito']=$Pedido->LineasPedido($array['idpedido']);
    $para=$order['email'];
    $datos=$miMail->CreaBodyTextoPedido($order);   
}  

if ($array['tipo']=='devolucion'){
    $Pedido = new RecomponePedido;
    $order=$Pedido->DatosGlobalesPedido($array['idpedido']);
    $order['carrito']=$Pedido->LineasPedido($array['idpedido']);
    $para=$order['email'];
    
    $datos=$miMail->CreaBodyTextoDevolución($order['pedido'],$array['tarjeta'],$order['total']);
}

//devolución
/*
$numero='J55GRT';
$tarjeta=true;
$importe='21,25';
*/
//$datos=$miMail->CreaBodyTextoDevolución($numero,$tarjeta,$importe);

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


$json=array("valid"=>$checking, "msg"=>$msg);
ob_end_clean();

echo json_encode($json);




/*
$file = fopen("zz-email.html", "w");
fwrite($file, "Para: ". $para . PHP_EOL);
fwrite($file, "Para: ". $array['mail'] . PHP_EOL);
fwrite($file, "datos: ". json_encode($datos) . PHP_EOL);
//fwrite($file, "SQL: ". $sql . PHP_EOL);
//fwrite($file, "head: ". headmail() . PHP_EOL);
//fwrite($file, "pie: ". PIECORREO . PHP_EOL);
//fwrite($file, "Body: ". $phpmailer->Body . PHP_EOL);
fwrite($file,$phpmailer->Body . PHP_EOL);
//fwrite($file, "sender: -". MAILsender .'-'. PHP_EOL);
fwrite($file, "error: ". $phpmailer->ErrorInfo . PHP_EOL);
fclose($file);
/*
$file = fopen("email.TXT", "w");
fwrite($file, "error: ". $phpmailer->ErrorInfo . PHP_EOL);
fclose($file);
*/
?>
