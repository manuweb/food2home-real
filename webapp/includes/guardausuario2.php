<?php
/*
 *
 * Archivo: guardausuario2.php
 *
 * Version: 1.0.1
 * Fecha  : 29/09/2023
 * Se recibe los datos de usuario.js y se verifica en la tabla usuarios_app
 * Si el usuario existe devuelve error
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
$json=array("valid"=>$checking);
$update="";
if ($array['pass']!=""){
    $update="clave='".MD5($array['pass'])."', ";
}

$sql="SELECT username FROM usuarios_app WHERE username='".$array["usuario"]."'AND id!='".$array['id']."';";

    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    $usuario= $database->getResultsAsArray();
if (count($usuario)>0) {
    $checking=false; //ya existe
    $username=$usuario[0] ->username;
    //$_SESSION["autentificado"]=true;
    $json=array("valid"=>$checking, "existe"=>true);
}
else {   
    // 01/34/6789
    $nacimiento=substr($array['nacimiento'],6,4).'-'.substr($array['nacimiento'],3,2).'-'.substr($array['nacimiento'],0,2);
    $sql="UPDATE usuarios_app SET username='".$array['usuario']."', ".$update." tratamiento='".$array['tratamiento']."', nombre='".$array['nombre']."', apellidos='".$array['apellidos']."', nacimiento='".$nacimiento."', telefono='".$array['telefono']."', publicidad='".$array['publi']."' WHERE id='".$array['id']."';";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) { 
        $checking=true;
        $json=array("valid"=>$checking,"id"=>$array['id']);
    }	

    
}

$database->freeResults();
$json=array("valid"=>$checking);

echo json_encode($json); 
/*
$file = fopen("usuario.txt", "w");
  
fwrite($file, $sql . PHP_EOL);    

fclose($file);    
*/
?>
