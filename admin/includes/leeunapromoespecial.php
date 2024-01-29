<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;



$sql="SELECT id, nombre, codigo,envio_recoger, usuario, grupo, dias,desde, hasta, maximo, tipo, logica FROM promos WHERE id='".$array['id']."'";



$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {
    $checking=true;
    $promos = $result->fetch_object();
   
    $id=$promos->id;
    $nombre=$promos->nombre;
    $codigo=$promos->codigo;
    $dias=$promos->dias;
    $desde=$promos->desde; 
    $hasta=$promos->hasta;
    $tipo=$promos->tipo;   
    $logica=$promos->logica;  
    $envio_recoger=$promos->envio_recoger;
    $usuario=$promos->usuario;   
    $grupo=$promos->grupo;   
    $maximo=$promos->maximo;   
    $producto='';
    if ($tipo==2){
        $porciones = explode("##", $logica);
        $sql="SELECT id, nombre FROM productos WHERE id='".$porciones[1]."'";
        
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        $prod = $result->fetch_object();
        $producto=$prod->id.'-'.$prod->nombre;
    }  
}	

$database->freeResults(); 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"codigo"=>$codigo,"dias"=>$dias,"desde"=>$desde,"hasta"=>$hasta,"tipo"=>$tipo,"logica"=>$logica,"producto"=>$producto,"envio_recoger"=>$envio_recoger,"usuario"=>$usuario,"grupo"=>$grupo,"maximo"=>$maximo);

echo json_encode($json); 





?>
