<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
/*
 *
 * Archivo: probarcampaign.php
 *
 * Version: 1.0.0
 * Fecha  : 21/08/2023
 * 
 * ENVIA EMAIL PRUEBA
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
include "../../webapp/config.mail.php";
include "../../webapp/PHPMailer/class.phpmailer.php";
include "../../webapp/PHPMailer/class.smtp.php";
include "../../webapp/includesapp/enviaemailcss.php";
include "../../webapp/functions.php";

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}

$array = json_decode(json_encode($_POST), true);
$checking=false;


$texto=$array['texto'];


$i = 0;

do {
    $i=strpos($texto, '[cupon-');
    if ($i >0){
        
        $j=strpos($texto, ']',$i);
        $cupon=substr($texto,$i+7,$j-$i-7);
        //echo "<br>".$cupon;
        $texto=str_replace('[cupon-'.$cupon.']',logicacupon($cupon),$texto);
    }
    else {
        $i=false;
    }
    //$texto=
} while ($i !=false);

$i = 0;
do {
    $i=strpos($texto, '[producto-');
    if ($i >0){
        
        $j=strpos($texto, ']',$i);
        $cupon=substr($texto,$i+10,$j-$i-10 );
        //echo "<br>".$cupon;
        $texto=str_replace('[producto-'.$cupon.']',productoemail($cupon),$texto);
    }
    else {
        $i=false;
    }
    //$texto=
} while ($i !=false);




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
$contact=[
    'subject'=>$array['nombre'],
    'textomail'=>$texto

];


$datos=$miMail->CreaBodyCampaign($contact);
$phpmailer->Subject = $datos['subject'];
$phpmailer->AddAddress($array['mail']); // recipients email


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

echo json_encode($json); 

/*
$file = fopen("email.txt", "w");

fwrite($file, "Body: ". $phpmailer->Body . PHP_EOL);
fwrite($file, "error: ". $phpmailer->ErrorInfo . PHP_EOL);
*/
//echo '<br>'.$texto.'<bR>';

//$textoCupon=logicacupon('dddd');
//echo logicacupon('ENVIOGRATIS');


