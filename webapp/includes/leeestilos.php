<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
//*************************************************************************************************
//
//  leeesilos.php
//
//  Lee datos de la tabla inicio 
//
//  Llamado desde apps.js 
//
//*************************************************************************************************


$checking=false;


$sql="SELECT modooscuro, primario,secundario, boton_inicio_texto, boton_menu_texto, boton_carrito_texto, estilo_boton_inicio, estilo_boton_menu, tam_boton_menu, estilo_boton_carrito, breadcrumbs FROM estilo WHERE id=1";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result) {    
    $row = $database->loadObject(); 
    $oscuro = $row->modooscuro;
    $primario = $row->primario;
    $secundario = $row->secundario;
    $boton_inicio = $row->boton_inicio_texto;
    $boton_menu = $row->boton_menu_texto;
    $boton_carrito = $row->boton_carrito_texto;
    $estilo_boton_inicio = $row->estilo_boton_inicio;
    $estilo_boton_menu = $row->estilo_boton_menu;
    $tam_boton_menu = $row->tam_boton_menu;
    $estilo_boton_carrito = $row->estilo_boton_carrito;
    $breadcrumbs = $row->breadcrumbs;
    $checking=true;
}
$database->freeResults();

$json=array("valid"=>$checking,"modooscuro"=>$oscuro,"primario"=>$primario,"secundario"=>$secundario,"boton_inicio"=>$boton_inicio,"boton_menu"=>$boton_menu,"boton_carrito"=>$boton_carrito,"estilo_boton_inicio"=>$estilo_boton_inicio,"estilo_boton_menu"=>$estilo_boton_menu,"tam_boton_menu"=>$tam_boton_menu,"estilo_boton_carrito"=>$estilo_boton_carrito,"breadcrumbs"=>$breadcrumbs);


echo json_encode($json); 

//print_r($texto);


?>
