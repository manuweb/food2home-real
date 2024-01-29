<?php
/*
 *
 * Archivo: loginw.php
 *
 * Version: 1.0.1
 * Fecha  : 29/09/2023
 * Se recibe los datos de usuario.js (login) y se verifica en la tabla usuarios_app
 * Si el usuario no existe devuelve error
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;



$sql="SELECT usuarios_app.id,usuarios_app.tratamiento, usuarios_app.nombre, usuarios_app.apellidos, usuarios_app.nacimiento,usuarios_app.telefono, usuarios_app.username, usuarios_app.publicidad, usuarios_app.monedero, usuarios_app.grupoclientes, grupo_clientes.activo, grupo_clientes.porcentaje FROM usuarios_app LEFT JOIN grupo_clientes ON usuarios_app.grupoclientes=grupo_clientes.id WHERE username='".$array["username"]."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result) { 
    $usuario = $result->fetch_object();
    $checking=true;
    $id=$usuario ->id;
    $tratamiento=$usuario ->tratamiento;
    $nombre=$usuario ->nombre;
    $apellidos=$usuario ->apellidos;
    $telefono=$usuario ->telefono;
    $publicidad=$usuario ->publicidad;
    //0123-56-89
    $nacimiento=substr($usuario ->nacimiento,8,2).'/'.substr($usuario ->nacimiento,5,2).'/'.substr($usuario ->nacimiento,0,4);
    //$_SESSION["autentificado"]=true;
    $monedero=$usuario ->monedero;
    $porcentaje=$usuario ->porcentaje;
    $monedero_activo=$usuario ->activo;
}	
$database->freeResults();


$json=array("valid"=>$checking,"id"=>$id,"tratamiento"=>$tratamiento,"nombre"=>$nombre,"apellidos"=>$apellidos,"nacimiento"=>$nacimiento,"telefono"=>$telefono,"publicidad"=>$publicidad,"monedero"=>$monedero,"porcentaje"=>$porcentaje,"monedero_activo"=>$monedero_activo);
echo json_encode($json);    


?>
