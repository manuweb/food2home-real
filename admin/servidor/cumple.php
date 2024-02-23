<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
header("Access-Control-Allow-Origin: *");
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
include "../../webapp/config.mail.php";
include "../../webapp/PHPMailer/class.phpmailer.php";
include "../../webapp/PHPMailer/class.smtp.php";


$http=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");

$url=$http.'://' . $_SERVER["HTTP_HOST"];
define('LAURL',$url);




$txtcupon=TextoCupon('FELIZCUMPLE');
$sql="SELECT textomail FROM tiposcorreos WHERE id=2;";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
$elmail = $result->fetch_object();
$texto=$elmail->textomail;

$texto=str_replace('[logo]',"<img src='".LAURL."/webapp/img/empresa/".LOGOEMPRESA."' style='width:250px;max-width:80%;height:auto;border:none;text-decoration:none;color:#ffffff;'>",$texto);
$texto=str_replace('[cuponBirthday]',$txtcupon,$texto);

$sql="SELECT username, nombre FROM usuarios_app  WHERE nacimiento LIKE '%-".date("m")."-".date("d")."';";



$database->setQuery($sql);
$result = $database->execute();

while ($usuarios = $result->fetch_object()) {
    $nombre=$usuarios->nombre;
    $mail=$usuarios->username;
    //echo 'Nombre:<b>'.$nombre.'</b> ('.$mail.')<br>';
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
    $phpmailer->Subject = 'Feliz cumpleaños';
    $phpmailer->AddAddress($mail);
    $eltexto=str_replace('[usuarioNombre]',$nombre,$texto);
    $phpmailer->Body =headmail();
    $phpmailer->Body .= iconv("UTF-8", "UTF-8",$eltexto);
    $phpmailer->Body .= iconv("UTF-8", "UTF-8",footmail());
    $phpmailer->send();
}




$database->freeResults();  



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

?>