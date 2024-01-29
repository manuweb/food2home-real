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

$sql="SELECT estadoweb.estado, integracion.tipo, opcionescompra.tarifa, empresa.nombre_comercial FROM estadoweb LEFT JOIN integracion ON integracion.id=estadoweb.id LEFT JOIN opcionescompra ON integracion.id=opcionescompra.id LEFT JOIN empresa ON empresa.id=estadoweb.id Where estadoweb.id=1";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result) {    
    $checking=true;
    while ($estado = $result->fetch_object()) {
        $on=$estado ->estado;
        $integracion=$estado ->tipo;
        $tarifa=$estado ->tarifa;
        $nombre_comercial=$estado ->nombre_comercial;
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



$json=array("valid"=>$checking,"on"=>$on,"integracion"=>$integracion,"id"=>$id,"alias"=>$alias,"tarifa"=>$tarifa,"nombre_comercial"=>$nombre_comercial, "idRedsys"=>$idRedsys);

ob_end_clean();
echo json_encode($json);    



?>
