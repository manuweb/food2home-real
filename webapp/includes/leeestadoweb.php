<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";


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

$sql="SELECT estadoweb.estado AS estado, opcionescompra.fidelizacion AS fidelizacion, opcionescompra.porcentaje AS porcentaje,opcionescompra.tiempoenvio AS tiempoenvio, 
opcionescompra.cortesia AS cortesia, opcionescompra.portesgratis AS portesgratis, opcionescompra.importeportesgratis AS importeportesgratis,
opcionescompra.portesgratismensaje AS portesgratismensaje,
opcionescompra.norepartomensaje AS norepartomensaje,
opcionescompra.minimo AS pedidominimo, integracion.tipo AS integracion, empresa.movil AS movil, empresa.nombre_comercial AS nombre_comercial FROM estadoweb LEFT JOIN opcionescompra ON opcionescompra.id=estadoweb.id LEFT JOIN integracion ON integracion.id=opcionescompra.id LEFT JOIN empresa ON empresa.id=opcionescompra.id Where estadoweb.id=1";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result) {    
    $checking=true;
    $estado = $result->fetch_object();
    $on=$estado ->estado;
    $fidelizacion=$estado ->fidelizacion;
    $porcentaje=$estado ->porcentaje;
    $tiempoenvio=$estado ->tiempoenvio;
    $cortesia=$estado ->cortesia;
    $integracion=$estado ->integracion;
    $pedidominimo=$estado ->pedidominimo;
    $portesgratis=$estado ->portesgratis;
    $importeportesgratis=$estado ->importeportesgratis;
    $portesgratismensaje=$estado ->portesgratismensaje;
    //norepartomensaje
    $norepartomensaje=$estado ->norepartomensaje;
    $movil=$estado ->movil;
    $nombre_comercial=$estado ->nombre_comercial;
    
    $sql='SELECT id,alias, domicilio, telefono, lat, lng FROM tiendas;';
    //$database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    $id=[];
    $alias=[];
    $domicilio=[];
    $telefono=[];
    $lat=[];
    $lng=[];
    if ($result) {    
    
        while ($tiendas = $result->fetch_object()) {
            $id[]=$tiendas->id;
            $alias[]=$tiendas->alias;
            $domicilio[]=$tiendas->domicilio;
            $telefono[]=$tiendas->telefono;
            $lat[]=$tiendas->lat;
            $lng[]=$tiendas->lng;
        }
    }

}
$database->freeResults();

$json=array("valid"=>$checking, "movil"=>$movil, "on"=>$on,"fidelizacion"=>$fidelizacion, "porcentaje"=>$porcentaje, "tiempoenvio"=>$tiempoenvio, "cortesia"=>$cortesia, "integracion"=>$integracion, "pedidominimo"=>$pedidominimo,"id"=>$id,"alias"=>$alias,"domicilio"=>$domicilio,"telefono"=>$telefono,"lat"=>$lat,"lng"=>$lng, "portesgratis"=>$portesgratis, "importeportesgratis"=>$importeportesgratis, "portesgratismensaje"=>$portesgratismensaje, "norepartomensaje"=>$norepartomensaje, "nombre_comercial"=>$nombre_comercial, "idRedsys"=>$idRedsys);

ob_end_clean();
echo json_encode($json);    
/*
$file = fopen("estado.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);
fclose($file);
*/
?>
