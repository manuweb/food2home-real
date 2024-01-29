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





$phpmailer = new PHPMailer();
$phpmailer->CharSet = 'ISO-8859-1';
$phpmailer->Username = USUARIOMAIL;
$phpmailer->Password = CLAVEMAIL; 
$phpmailer->SMTPSecure = SMTPSecure;
$phpmailer->Host = HOSTMAIL;
$phpmailer->Port = PUERTOMAIL;
$phpmailer->SMTPAuth = true;
$phpmailer->Sender = MAILsender;
$phpmailer->SetFrom(MAILsender, NOMBREEmpresa);

$texto=$array['texto'];

/*
$texto='<table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;"><tbody><tr><td style="padding:40px 30px 30px 30px;text-align:center;font-size:24px;font-weight:bold;background-color:#d3d3d3"><a href="https://cloud-delivery.es" style="width:250px;max-width:80%;height:auto;border:none;text-decoration:none;color:#ffffff;">[logo]</a></td></tr><tr><td style="padding:30px;background-color:#ffffff;"><h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;" class="">Texto</h1><p class="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eget nibh elementum, luctus turpis et, ultricies urna. Suspendisse potenti. Nulla vitae egestas mi. Nulla tortor enim, imperdiet ac feugiat commodo, vestibulum at libero. Pellentesque interdum quis leo sit amet rhoncus. Proin efficitur mi</p><p class="">[producto-118]</p><p class="">&nbsp;non ex maximus aliquam. Suspendisse vel facilisis ex. Sed at enim sapien.</p></td></tr><tr><td style="padding:0;font-size:24px;line-height:28px;font-weight:bold;"><a href="https://cloud-delivery.es/" style="text-decoration:none;"><img src="https://cloud-delivery.es/webapp/img/3646374.png" width="600" alt="" style="width:100%;height:auto;display:block;border:none;text-decoration:none;color:#363636;"></a></td></tr><tr><td style="background-color:#d3d3d3;margin-bottom:10px;">&nbsp;</td></tr><tr><td style="padding:30px;background-color:#ffffff;"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eget nibh elementum, luctus turpis et, ultricies urna. Suspendisse potenti. Nulla vitae egestas mi. Nulla tortor enim, imperdiet ac feugiat commodo, vestibulum at libero. Pellentesque interdum quis leo sit amet rhoncus. Proin efficitur mi non ex maximus aliquam. Suspendisse vel facilisis ex. Sed at enim sapien.</p><h4 style="margin-top:0;margin-bottom:18px;">Cloud-Delivery</h4></td></tr></tbody></table> ';

// echo $texto;
$array['mail']='m.sanchez@elmaestroweb.es';
$array['nombre']='prueba';
*/

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
//echo $texto;
$phpmailer->IsHTML(true);
$phpmailer->AddAddress($array['mail']); 
$phpmailer->Subject = $array['nombre'];
$phpmailer->Body .=headmail().$cssmail;
$texto=str_replace('[logo]',"<img src='".URLServidor."webapp/img/empresa/".LOGOEMPRESA."' style='width:250px;max-width:80%;height:auto;border:none;text-decoration:none;color:#ffffff;'>",$texto);
$pie=PIECORREO;
$pie=str_replace('[txt-pie]',NOMBRECOMERCIAL." ".date("Y"),$pie);
    
$phpmailer->Body .= utf8_decode($texto);
$phpmailer->Body .= $pie.footmail();

if ($phpmailer->send()) {
    $checking=true;
    $msg="Mail enviado correctamente"; 

} else {
    $checking=false;
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


