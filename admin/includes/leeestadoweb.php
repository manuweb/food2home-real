<?php
session_start(); 
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');


$checking=false;
$idRedsys=0;
$sql="SELECT id, idrevo FROM metodospago WHERE esRedsys=1;";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result) {    
    $redsys = $result->fetch_object();
    $idRedsys=$redsys->idrevo;
}

$sql="SELECT estadoweb.estado, integracion.tipo,integracion.delivery, empresa.nombre_comercial, incluye.push, incluye.mail, incluye.promos, incluye.multi, incluye.tarifas, opcionescompra.dias_vista, opcionescompra.minimo AS pedidominimo, opcionescompra.importeportesgratis AS importeportesgratis FROM estadoweb LEFT JOIN integracion ON integracion.id=estadoweb.id LEFT JOIN opcionescompra ON integracion.id=opcionescompra.id LEFT JOIN empresa ON empresa.id=estadoweb.id LEFT JOIN incluye ON incluye.id=estadoweb.id Where estadoweb.id=1";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result) {    
    $checking=true;
    while ($estado = $result->fetch_object()) {
        $on=$estado ->estado;
        $integracion=$estado ->tipo;
        $delivery=$estado ->delivery;
        $tarifa=$estado ->tarifas;
        $nombre_comercial=$estado ->nombre_comercial;
        $push=$estado ->push;
        $mail=$estado ->mail;
        $promos=$estado ->promos;
        $multi=$estado ->multi;
        $dias_vista=$estado ->dias_vista;
        $importeportesgratis=$estado ->importeportesgratis;
        $pedidominimo=$estado ->pedidominimo;
     }	
    
    $sql='SELECT id,alias FROM tiendas;';
    //$database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    $id = []; 
    $alias = []; 
    if ($result->num_rows>0) {

        while ($tiendas = $result->fetch_object()) {
            $id[]=$tiendas->id;
            $alias[]=$tiendas->alias;
        }
    }
}
$database->freeResults();



$json=array("valid"=>$checking,"on"=>$on,"integracion"=>$integracion,"delivery"=>$delivery,"id"=>$id,"dias_vista"=>$dias_vista,"alias"=>$alias,"tarifa"=>$tarifa,"nombre_comercial"=>$nombre_comercial, "idRedsys"=>$idRedsys, "push"=>$push, "mail"=>$mail, "promos"=>$promos, "multi"=>$multi,"pedidominimo"=>$pedidominimo,"importeportesgratis"=>$importeportesgratis);

ob_end_clean();
echo json_encode($json);    



?>
