<?php
/*
 *
 * Archivo: masdatos.php
 *
 * Version: 1.0.2
 * Fecha  : 03/10/2023
 * Se usa en :masdatos.js ->mostrarMasDatos()
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

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=true;

$texto="";

$tex_ant='<div class="list media-list no-hairlines-between" style="margin-top: 15px;margin-left: 25px;border-radius: 28px; padding-right: 12px;"><ul style="margin-top: -5px;background-color: transparent;"><li>';

$tex_pos='</li></ul></div>';

//Perfil
$texto.=$tex_ant.'<a href="javascript:muestraMisDatos();" class="item-link item-content">';


    
$texto.='<div class="item-media" style="background-color: var(--fondo-mas-datos-icono);z-index:999;border-radius: 40px;width: 50px;height: 50px;"> <i class="f7-icons" style="margin-left: 10px;font-size:30px;">person_crop_circle</i></div>';
$texto.='<div class="item-inner" style="background-color: var(--fondo-mas-datos);border-radius: 10px;margin-left: -63px;margin-right: 20px;padding-left: 75px;padding-top: 20px;height: 70px;"> <div class="item-title-row lista-mas"><div class="item-title" style="font-size:16px;">Mis datos</div></div></div></a>';
$texto.=$tex_pos;


//domicilios
$texto.=$tex_ant.'<a href="javascript:muestraDomicilios();" class="item-link item-content">';
$texto.='<div class="item-media" style="background-color: var(--fondo-mas-datos-icono);z-index:999;border-radius: 40px;width: 50px;height: 50px;"> <i class="f7-icons" style="margin-left: 10px;font-size:30px;">house_alt_fill</i></div>';
$texto.='<div class="item-inner" style="background-color: var(--fondo-mas-datos);border-radius: 10px;margin-left: -63px;margin-right: 20px;padding-left: 75px;padding-top: 20px;height: 70px;"> <div class="item-title-row lista-mas"><div class="item-title" style="font-size:16px;">Mis domicilios</div></div></div></a>';
$texto.=$tex_pos;

/*
$texto.=$tex_ant.'<a href="javascript:muestraTarjetas();" class="item-link item-content">';
$texto.='<div class="item-media" style="background-color: lightgrey;border-radius: 40px;width: 50px;height: 50px;margin-top: 10px;"> <i class="f7-icons" style="margin-left: 10px;font-size:30px;">creditcard_fill</i></div>';
$texto.='<div class="item-inner" style="background-color: rgb(211 211 211 / 20%);border-radius: 10px;margin-left: -63px;margin-right: 20px;padding-left: 75px;padding-top: 20px;height: 70px;"> <div class="item-title-row lista-mas"><div class="item-title" style="font-size:16px;">Mis tarjetas</div></div></div></a>';
$texto.=$tex_pos;
*/

$texto.=$tex_ant.'<a href="javascript:buscalospedidos();" class="item-link item-content">';

$texto.='<div class="item-media" style="background-color: var(--fondo-mas-datos-icono);z-index:999;border-radius: 40px;width: 50px;height: 50px;"> <i class="material-icons" style="margin-left: 10px;font-size:30px;">shopping_bag</i></div>';
$texto.='<div class="item-inner" style="background-color: var(--fondo-mas-datos);border-radius: 10px;margin-left: -63px;margin-right: 20px;padding-left: 75px;padding-top: 20px;height: 70px;"> <div class="item-title-row lista-mas"><div class="item-title" style="font-size:16px;">Mis pedidos</div></div></div></a>';


$texto.=$tex_pos;

$texto.=$tex_ant.'<a href="javascript:buscalaspush();" class="item-link item-content">';


$texto.='<div class="item-media" style="background-color: var(--fondo-mas-datos-icono);z-index:999;border-radius: 40px;width: 50px;height: 50px;"> <i class="f7-icons" style="margin-left: 10px;font-size:30px;"><span class="badge color-red badage-noti-usu" style="left: 45px;top: 5px;"></span>bell_fill</i></div>';
$texto.='<div class="item-inner" style="background-color: var(--fondo-mas-datos);border-radius: 10px;margin-left: -63px;margin-right: 20px;padding-left: 75px;padding-top: 20px;height: 70px;"> <div class="item-title-row lista-mas"><div class="item-title" style="font-size:16px;">Notificaciones</div></div></div></a>';
$texto.=$tex_pos;

/*
$texto.=$tex_ant.'<a href="javascript:navegar(\'#view-nosotros\');" class="item-link item-content">';

$texto.='<div class="item-media" style="background-color: var(--fondo-mas-datos-icono);z-index:999;border-radius: 40px;width: 50px;height: 50px;margin-top: 10px;"> <i class="icon f7-icons if-not-md" style="margin-left: 10px;font-size:30px;">info_circle</i><i class="icon material-icons if-md" style="margin-left: 10px;font-size:30px;">info</i> </div>';
$texto.='<div class="item-inner" style="background-color: var(--fondo-mas-datos);border-radius: 10px;margin-left: -63px;margin-right: 20px;padding-left: 75px;padding-top: 20px;height: 70px;"> <div class="item-title-row lista-mas"><div class="item-title" style="font-size:16px;">Sobre nosotros</div></div></div></a>';
$texto.=$tex_pos;
*/

$json=array("valid"=>$checking,"texto"=>$texto);

echo json_encode($json); 

  









?>
