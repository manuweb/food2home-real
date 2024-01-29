<?php
/*
 *
 * Archivo: login.php
 *
 * Version: 1.0.1
 * Fecha  : 29/09/2023
 * Se usa en :usuarios.js ->$('#my-login-screen .login-button').on('click', function ()...
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";


$array = json_decode(json_encode($_POST), true);

$checking=false;


$sql="SELECT usuarios_app.id,usuarios_app.tratamiento, usuarios_app.nombre, usuarios_app.apellidos, usuarios_app.nacimiento,usuarios_app.telefono, usuarios_app.username, usuarios_app.publicidad, usuarios_app.monedero, usuarios_app.grupoclientes, grupo_clientes.activo, grupo_clientes.porcentaje FROM usuarios_app LEFT JOIN grupo_clientes ON usuarios_app.grupoclientes=grupo_clientes.id WHERE username='".$array["username"]."' and clave='".md5($array["password"])."'";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result->num_rows>0) { 
    $usuario = $result->fetch_object();
    $checking=true;
    $id=$usuario ->id;
    $tratamiento=$usuario ->tratamiento;
    $nombre=$usuario ->nombre;
    $apellidos=$usuario ->apellidos;
    $telefono=$usuario ->telefono;
    $publicidad=$usuario ->publicidad;
     $nacimiento=substr($usuario ->nacimiento,8,2).'/'.substr($usuario ->nacimiento,5,2).'/'.substr($usuario ->nacimiento,0,4);
    $monedero=$usuario ->monedero;
    $porcentaje=$usuario ->porcentaje;
    $monedero_activo=$usuario ->activo;
    //$_SESSION["autentificado"]=true;
}	
$database->freeResults();
$json=array("valid"=>$checking,"id"=>$id,"tratamiento"=>$tratamiento,"nombre"=>$nombre,"apellidos"=>$apellidos,"nacimiento"=>$nacimiento,"telefono"=>$telefono,"publicidad"=>$publicidad,"monedero"=>$monedero,"porcentaje"=>$porcentaje,"monedero_activo"=>$monedero_activo);

echo json_encode($json);    

/*
$file = fopen("zzz.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);

fclose($file);

*/
?>
