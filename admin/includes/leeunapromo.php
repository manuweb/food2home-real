<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$_POST = json_decode(file_get_contents('php://input'), true);

$array = json_decode(json_encode($_POST), true);

$checking=false;



$sql="SELECT id, nombre, codigo, desde, hasta, envio_recoger,tipo, logica FROM promos WHERE id='".$array['id']."'";

$db = DataBase::getInstance();  
$db->setQuery($sql);  
$promos = $db->loadObjectList();  
$db->freeResults();

if (count($promos)>0) {
    $checking=true;
   
    $id=$promos[0]->id;
    $nombre=$promos[0]->nombre;
    $codigo=$promos[0]->codigo;
    $desde=$promos[0]->desde; 
    $hasta=$promos[0]->hasta;
    $envio_recoger=$promos[0]->envio_recoger;  
    $tipo=$promos[0]->tipo; 
    $logica=$promos[0]->logica;   
    $producto='';
    if ($tipo==2){
        $porciones = explode("##", $logica);
        $sql="SELECT id, nombre FROM productos WHERE id='".$porciones[1]."'";
        $db = DataBase::getInstance();  
        $db->setQuery($sql);  
        $prod = $db->loadObjectList();  
        $db->freeResults();
        $producto=$prod[0]->id.'-'.$prod[0]->nombre;

    }
        



     //'<option value="1">% Dto. Global</option>'+
                    //<option value="2">% Dto. Producto</option>'+
                    //<option value="3">Envío Gratis</option>'+
                    //<option value="4">Importe descuento</option>'+
    
}	

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"codigo"=>$codigo,"desde"=>$desde,"hasta"=>$hasta,"envio_recoger"=>$envio_recoger,"tipo"=>$tipo,"logica"=>$logica,"producto"=>$producto);

echo json_encode($json); 





?>
