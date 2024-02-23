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


$http=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");

$url=$http.'://' . $_SERVER["HTTP_HOST"];
define('LAURL',$url);



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
        $texto=str_replace('[cupon-'.$cupon.']',TextoCupon($cupon),$texto);
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


$texto=str_replace('[logo]',"<img src='".LAURL."/webapp/img/empresa/".LOGOEMPRESA."' style='width:250px;max-width:80%;height:auto;border:none;text-decoration:none;color:#ffffff;'>",$texto);


$phpmailer = new PHPMailer();
$phpmailer->CharSet = 'UTF-8';
$phpmailer->Username = USUARIOMAIL;
$phpmailer->Password = CLAVEMAIL;
$phpmailer->SMTPSecure = SMTPSecure;
$phpmailer->Host = HOSTMAIL;
$phpmailer->Port = PUERTOMAIL;
$phpmailer->SMTPAuth = true;
$phpmailer->Sender = MAILsender;;
$phpmailer->SetFrom(MAILsender, NOMBREEmpresa);
$phpmailer->IsHTML(true);
$phpmailer->Subject = $array['nombre'];
$phpmailer->AddAddress($array['mail']);

$phpmailer->Body =headmail();
$phpmailer->Body .= iconv("UTF-8", "UTF-8",$texto);
$phpmailer->Body .= iconv("UTF-8", "UTF-8",footmail());



if ($phpmailer->send()) {
    $checking=true;
    $msg="Mail enviado correctamente"; 

} else {
    $msg="Error enviando mail"; 
    //echo 'Mailer Error: ' . $mail->ErrorInfo;
    $file = fopen("zz-email.txt", "w");

    fwrite($file, "Body: ". $phpmailer->Body . PHP_EOL);
    fwrite($file, "error: ". $phpmailer->ErrorInfo . PHP_EOL);
}



$json=array("valid"=>$checking, "msg"=>$msg);

echo json_encode($json); 


function productoemail($id) {
    $sql="SELECT id, nombre, precio_web, info, imagen,imagen_app1  FROM productos WHERE id='".$id."'";
    
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();

    $prod = $result->fetch_object();
    
    
    $producto=$prod->nombre;
    $precio=$prod->precio_web;
    $info=$prod->info;
    if ($prod->imagen==''){
        $imagen=LAURL."/webapp/img/productos/".$prod[0]->imagen_app1;
    }
    else {
        $imagen=IMGREVO.$prod->imagen;
    }
    $txt='<div style="background-color: #d3d3d3 !important;;border-radius: 40px;"><br><h3 style="padding: 20px;padding-top: 0px;padding-bottom: 0;">'.$producto.'</h3><table style="width:100%;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;"><tr><td style="padding:10px;text-align:center;width:40%;" valign="top"><img src="'.$imagen.'" style="width:100%;height:auto;border-radius:50%;"></td><td style="padding:10px;text-align:left;width:60%;"><p>'.$info.'</p><h3 style="text-align: center;color:#ff0000;">'.$precio.' &euro;</h3></td></tr></table></div>';

    return $txt;
}


function TextoCupon($cupon){
    $devuelve='';
    $sql="SELECT desde, hasta, tipo,logica, dias FROM promos WHERE codigo='".$cupon."'";

    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result->num_rows>0) {
        $promos = $result->fetch_object();

        $tipo=$promos->tipo;  
        $desde=$promos->desde;
        $hasta=$promos->hasta;
        $dias=$promos->dias;
        $logica=$promos->logica; 
    }

    $hoy = date("d-m-Y h:m:s");
    //Incrementando x dias
    $textoDescuento='';


    $porciones = explode("##", $logica);

    if ($tipo==1){
        $textoDescuento='Tienes un <b>'.$porciones[0].'%</b> de descuento comprando un mínimo de <b>'.$porciones[1].'</b> &euro;.';
    }
    if ($tipo==2){
        $sql="SELECT id, nombre FROM productos WHERE id='".$porciones[1]."'";
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result->num_rows>0) {
            $prod = $result->fetch_object();

            $db->freeResults();
            $producto=$prod->nombre;
            $textoDescuento='Tienes un <b>'.$porciones[0].'%</b> de descuento al comprar <b><i>'.$producto.'</i></b>.';
        }
    }
    if ($tipo==3){
        $textoDescuento='Tienes Envío GRATIS';
        if($porciones[0]==''){
            $textoDescuento.='.';
        }
        else {
            $textoDescuento.=' comprando un mínimo de <b>'.$porciones[0].'</b> &euro;.';
        }
    }
    if ($tipo==4){
        $textoDescuento='Tienes <b>'.$porciones[0].'</b> &euro; de descuento al comprar un mínimo de <b>'.$porciones[0].'</b> &euro;.';
    }
    $database->freeResults();
        //0123-56-89 12:45
    $desdetxt=substr($desde,8,2).'/'.substr($desde,5,2).'/'.substr($desde,0,4).' ('.substr($desde,11,5).')';
    if ($cupon=='FELIZCUMPLE' || $cupon=='BIENVENIDA'){
        $desde=date("Y-m-d H:m");
        $hasta=date("Y-m-d H:m",strtotime($desde."+ ".$dias." days")); 
        //$hasta=$desde.'+'.$dias.' days';
    } 
     $desdetxt=substr($desde,8,2).'/'.substr($desde,5,2).'/'.substr($desde,0,4).' ('.substr($desde,11,5).')';$hastatxt=substr($hasta,8,2).'/'.substr($hasta,5,2).'/'.substr($hasta,0,4).' ('.substr($hasta,11,5).')';

    $devuelve='<h3 style="margin:0;"><img src="'.LAURL.'/webapp/img/cupon.png" width="64" height="64" alt="f" style="vertical-align: middle;padding: 10px;"><span style="border:solid 1px;border-radius:5px;">&nbsp;'.$cupon.'&nbsp;</span></h3><p>'.$textoDescuento.'<br><i>Usalo   desde el '.$desdetxt.' hasta el '.$hastatxt.'</i>.</p>';   

    return $devuelve;
}

function headmail(){
	$head='<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="ISO-8859-1"> <meta name="viewport" content="width=device-width,initial-scale=1"> <meta name="x-apple-disable-message-reformatting"> <title></title><!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]--> <style>table, td, div, h1, p{font-family: Arial, sans-serif;}@media screen and (max-width: 530px){.unsub{display: block; padding: 8px; margin-top: 14px; border-radius: 6px; background-color: #555555; text-decoration: none !important; font-weight: bold;}.col-lge{max-width: 100% !important;}}@media screen and (min-width: 531px){.col-sml{max-width: 27% !important;}.col-lge{max-width: 73% !important;}}</style></head><body style="margin:0;padding:0;word-spacing:normal;background-color:#d3d3d3;text-align: center;width: 100%;"> <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#d3d3d3 ;"> <table role="presentation" style="width:100%;border:none;border-spacing:0;"> <tr> <td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]-->';

    
    return $head;

}

function footmail(){
    $foot='<!--[if mso]> </td></tr></table><![endif]--> </td></tr></table></div></body></html>';
    $pie=PIECORREO;
    $pie=str_replace('[txt-pie]',NOMBRECOMERCIAL." ".date("Y"),$pie);
    $pie.=$foot;
    //
    return $pie;
}



/*
$file = fopen("email.txt", "w");

fwrite($file, "Body: ". $phpmailer->Body . PHP_EOL);
fwrite($file, "error: ". $phpmailer->ErrorInfo . PHP_EOL);
*/
//echo '<br>'.$texto.'<bR>';

//$textoCupon=logicacupon('dddd');
//echo logicacupon('ENVIOGRATIS');


