<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');


$_POST = json_decode(file_get_contents('php://input'), true);

$array = json_decode(json_encode($_POST), true);

$checking=false;



$sql="SELECT modooscuro, primario,secundario, boton_inicio_texto, boton_menu_texto, boton_carrito_texto, estilo_boton_inicio, estilo_boton_menu, tam_boton_menu, estilo_boton_carrito, breadcrumbs, estilo_app FROM estilo WHERE id=1";


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
    $estilo_app = $row->estilo_app;
    $checking=true;
}
$database->freeResults();

$json=array("valid"=>$checking,"modooscuro"=>$oscuro,"primario"=>$primario,"secundario"=>$secundario,"boton_inicio"=>$boton_inicio,"boton_menu"=>$boton_menu,"boton_carrito"=>$boton_carrito,"estilo_boton_inicio"=>$estilo_boton_inicio,"estilo_boton_menu"=>$estilo_boton_menu,"tam_boton_menu"=>$tam_boton_menu,"estilo_boton_carrito"=>$estilo_boton_carrito,"breadcrumbs"=>$breadcrumbs,"estilo_app"=>$estilo_app);

echo json_encode($json);    



?>
