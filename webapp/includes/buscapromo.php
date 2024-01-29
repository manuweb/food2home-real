<?php
/*
 *
 * Archivo: buscapromo.php
 *
 * Version: 1.0.1
 * Fecha  : 11/10/2023
 * Se usa en :comprar.js ->tengocupon() 
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL-2/DataBase.class.php";


$array = json_decode(json_encode($_POST), true);

$checking=false;


$sql="SELECT id, nombre, codigo, activo,envio_recoger, usuario, grupo, dias,desde, hasta, maximo, tipo, logica FROM promos WHERE codigo='".$array['codigo']."'";


$db = DataBase::getInstance();  
$db->setQuery($sql);  
$promos = $db->loadObjectList();  
$db->freeResults();

if (count($promos)>0) {
    $checking=true;

    
    $id=$promos[0]->id;
    $nombre=$promos[0]->nombre;
    $codigo=$promos[0]->codigo;
    $activo=$promos[0]->activo;
    $dias=$promos[0]->dias;
    $desde=$promos[0]->desde; 
    $hasta=$promos[0]->hasta;
    $tipo=$promos[0]->tipo;   
    $logica=$promos[0]->logica;   
    $envio_recoger=$promos[0]->envio_recoger;   
    $usuario=$promos[0]->usuario;   
    $grupo=$promos[0]->grupo;   
    $maximo=$promos[0]->maximo;  
    $emitidos=0;
    $usupermitido='si';
    $valido='si';
    $motivo='';
    
    if (($id==1)||($id==2)){
        
        $sql2="SELECT codigo,limite,usado FROM cuponesespeciales WHERE codigo='".$array['codigo']."' AND usuario='".$array['usuario']."'";
        $db = DataBase::getInstance();  
        $db->setQuery($sql2);  
        $cupones = $db->loadObjectList();  
        $db->freeResults();
        if (count($cupones)>0){
            $limite=$cupones[0]->limite;
            $usado=$cupones[0]->usado;
            if ($usado==1){
                $valido='no';
                $motivo='Cupón ya usado';
            }
            $date = date('Y-m-d h:i:s', time());
            if ($limite<$date){
                $valido='no';
                $motivo='Cupón expirado';
            }
        }    
    } 
    if ($maximo>0){
        $sql="SELECT count(codigo) AS cantidad FROM cupones WHERE codigo='".$array['codigo']."'";
        $db = DataBase::getInstance();  
        $db->setQuery($sql);  
        $cupones = $db->loadObjectList();  
        $db->freeResults();
        $emitidos=$cupones[0]->cantidad;
    }
    if ($usuario==2){
        $sql="SELECT id FROM usuarios_app WHERE id='".$array['usuario']."'";
        $db = DataBase::getInstance();  
        $db->setQuery($sql);  
        $usus = $db->loadObjectList();  
        $db->freeResults();
        if (count($usus)>0){
            $usupermitido='si';
        }
        else {
            $usupermitido='no';
        }
    }
    if ($usuario==3){
        
        $sql="SELECT grupoclientes FROM usuarios_app WHERE grupoclientes IN ('".$grupo."')";
        $db = DataBase::getInstance();  
        $db->setQuery($sql);  
        $usus = $db->loadObjectList();  
        $db->freeResults();
        if (count($usus)>0){
            $usupermitido='si';
        }
        else {
            $usupermitido='no';
        }
    }
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
    
	
}

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"codigo"=>$codigo,"activo"=>$activo,"dias"=>$dias,"desde"=>$desde,"hasta"=>$hasta,"tipo"=>$tipo,"logica"=>$logica,"producto"=>$producto,"envio_recoger"=>$envio_recoger,"usuario"=>$usuario,"grupo"=>$grupo,"maximo"=>$maximo,"emitidos"=>$emitidos,"usupermitido"=>$usupermitido,"valido"=>$valido,"motivo"=>$motivo);

ob_end_clean();
echo json_encode($json); 

/*
$file = fopen("texto.txt", "w");
fwrite($file, "sql: ".$sql. PHP_EOL);
fwrite($file, "sql2: ".$sql2. PHP_EOL);
fwrite($file, "json: ".json_encode($json). PHP_EOL);
fwrite($file, "date: ".$date. PHP_EOL);
fwrite($file, "limi: ".$limite  . PHP_EOL);
fclose($file);
*/
?>
