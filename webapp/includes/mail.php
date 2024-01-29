<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
header("Access-Control-Allow-Origin: *");

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
include "../../webapp/functions.php";

$para='m.sanchez@elmaestroweb.es';


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

//Recuperación de contraseña
//$datos=$miMail->CreaBodyTextoRecupera($para);
//$phpmailer->Subject = $datos['subject'];
//$phpmailer->AddAddress($para); // recipients email

// contacto Según llega de array
/*
$contact=[
    'nombre'=>'Manuel Sánchez',
    'email'=>'info@elmaestroweb.es',
    'telefono'=>'650952608',
    'comentario'=>'¿Que pasa picha?'

];
*/
//$datos=$miMail->CreaBodyTextoContacto($contact);

//devolución
/*
$numero='J55GRT';
$tarjeta=true;
$importe='21,25';
*/
//$datos=$miMail->CreaBodyTextoDevolución($numero,$tarjeta,$importe);

// nuevo usuario
//$datos=$miMail->CreaBodyTextoNuevoUsuario($para);


$idpedido=89;
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

echo $msg."<hr>";
echo "Body: ". $phpmailer->Body;

/*
Array
(
    [Username] => correo@food2home.es
    [Password] => Cloud.2023!
    [SMTPSecure] => tsl
    [Sender] => no-replay@food2home.es
    [NombreEmpresa] => Food2Home
    [url] => https://localhost
)
echo '<pre>';
print_r ($datos);
echo '</pre>';
*/

?>
