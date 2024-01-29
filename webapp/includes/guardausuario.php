<?php
/*
 *
 * Archivo: guardausuario.php
 *
 * Version: 1.0.1
 * Fecha  : 29/09/2023
 * Se recibe los datos de usuario.js (nuevo usuario) y se guardan en la tabla usuarios_app
 * Si el usuario existe devuelve error
 * tambien desde comprar.js -> comprapaso2Nuevo()
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
$json=array("valid"=>$checking);

$sql="SELECT username FROM usuarios_app WHERE username='".$array["usuario"]."';";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result->num_rows>0) {    
    
    $checking=false; //ya existe
    $usuario= $result->fetch_object();
    $username=$usuario ->username;
    //$_SESSION["autentificado"]=true;
    $json=array("valid"=>$checking, "existe"=>true);
}
else {
    if($array['usuario']!="") {
        $nacimiento=substr($array['nacimiento'],6,4).'-'.substr($array['nacimiento'],3,2).'-'.substr($array['nacimiento'],0,2);
        $sql="INSERT INTO usuarios_app ( username, clave, tratamiento, nombre, apellidos, nacimiento, telefono, publicidad) VALUES
        ('".$array['usuario']."', '".MD5($array['pass'])."', '".$array['tratamiento']."', '".$array['nombre']."', '".$array['apellidos']."', '".$nacimiento."', '".$array['telefono']."', '".$array['publi']."');";

        //$database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();

        if ($result) { 
            $checking=true;
            $sql2="SELECT max(id) AS maxid FROM usuarios_app;";
            //$database = DataBase::getInstance();
            $database->setQuery($sql2);
            $result = $database->execute();
            $usuario2= $result->fetch_object();
            

            $json=array("valid"=>$checking,"id"=>$usuario2->maxid);
        }	
        else {
            $json=array("valid"=>$checking);
        }
    }
    
}
$database->freeResults();



//$json=array("valid"=>$checking);

echo json_encode($json); 


/*
$file = fopen("zzz.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "sql2: ". $sql2 . PHP_EOL);
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);
fclose($file);
*/

?>
