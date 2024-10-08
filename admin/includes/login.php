<?php
session_start(); 
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');



$array = json_decode(json_encode($_POST), true);

$checking=false;
$email="";


$sql="SELECT id, email, tipo,tienda FROM usuarios WHERE nick='".$array["username"]."' and clave='".md5($array["password"])."'";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result) {    
    $checking=true;
    $usuario = $result->fetch_object();


    if ($usuario ->email!="") {
        $checking=true;
        $id=$usuario ->id;
        $email=$usuario ->email;
        $tipo=$usuario ->tipo;
        $tienda=$usuario ->tienda;
        $_SESSION["autentificado"]=true;
        $nombre_fichero = 'first.txt';

        if (file_exists($nombre_fichero)) {
            $first=true;
        } else {
            $first=false;
        }
        
    }
}
$database->freeResults();

$json=array("valid"=>$checking, "id"=>$id,"email"=>$email, "tipo"=>$tipo, "tienda"=>$tienda, "first"=>$first);

echo json_encode($json);    



?>
