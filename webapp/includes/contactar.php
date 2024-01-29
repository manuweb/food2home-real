<?php
/*
 *
 * Archivo: recuperarclave.php
 *
 * Version: 1.0.1
 * Fecha  : 29/09/2023
 * Se usa en :usuario.js ->RecuperarClave()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../conexion.php";
include "../MySQL/DataBase.class.php";
include "../config.php";
include "../config.mail.php";

include "../PHPMailer/class.phpmailer.php";
include "../PHPMailer/class.smtp.php";

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;




$phpmailer = new PHPMailer();
$phpmailer->CharSet = 'UTF-8';

$phpmailer->Username = USUARIOMAIL;
$phpmailer->Password = CLAVEMAIL; 
$phpmailer->SMTPSecure = SMTPSecure;
$phpmailer->Host = HOSTMAIL; // GMail
$phpmailer->Port = PUERTOMAIL;
//

//$phpmailer->IsSMTP(); // use SMTP
$phpmailer->SMTPAuth = true;
 $phpmailer->Sender = MAILsender;
$phpmailer->SetFrom(MAILsender, NOMBREEmpresa);

$phpmailer->AddAddress(MAILsender); // recipients email
$phpmailer->IsHTML(true);





$phpmailer->Subject = 'Formulario de contacto';
$phpmailer->Body .= "<p><img src='".URLServidor."/webapp/img/empresa/".LOGOEMPRESA."' width='240' height='auto' ></p>";
$phpmailer->Body .= "<p>Ha sido contactado por:</p>";
$phpmailer->Body .= "<p>Nombre: <b>".$array['nombre']."</b></p>";
$phpmailer->Body .= "<p>Email: <b>".$array['email']."</b></p>";
$phpmailer->Body .= "<p>Teléfono: <b>".$array['telefono']."</b></p>";
$phpmailer->Body .= "<p>comentario:<br>".$array['comentario']."</p>";



if ($phpmailer->send()) {
    $checking=true;
    $msg="Mail enviado correctamente"; 

} else {
    $msg="Error enviando mail"; 
    //echo 'Mailer Error: ' . $mail->ErrorInfo;
}
 


$json=array("valid"=>$checking, "msg"=>$msg);

echo json_encode($json); 

/*
$file = fopen("email.txt", "w");
fwrite($file, "SQL: ". $sql . PHP_EOL);
fwrite($file, "Body: ". $phpmailer->Body . PHP_EOL);
fwrite($file, "sender: -". MAILsender .'-'. PHP_EOL);
fwrite($file, "error: ". $phpmailer->ErrorInfo . PHP_EOL);
*/

?>
