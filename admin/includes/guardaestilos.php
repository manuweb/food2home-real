<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');



if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;



$sql="UPDATE estilo SET modooscuro='".$array['oscuro']."', primario='".$array['primario']."', secundario='".$array['secundario']."', boton_inicio_texto='".$array['texto_boton_inicio']."', boton_menu_texto='".$array['texto_boton_menu']."', boton_carrito_texto='".$array['texto_boton_carrito']."', estilo_boton_inicio='".$array['tipo_boton_inicio']."', estilo_boton_menu='".$array['tipo_boton_menu']."', tam_boton_menu='".$array['tam_boton_menu']."', estilo_boton_carrito='".$array['tipo_boton_carrito']."', breadcrumbs='".$array['breadcrumbs']."', estilo_app='".$array['estilo_app']."' WHERE id=1";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result) {    
    
    $checking=true;
}
$database->freeResults();

$json=array("valid"=>$checking);

echo json_encode($json);    


?>
