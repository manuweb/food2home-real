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
$bizum=0;
$sql="SELECT bizum FROM redsys WHERE id=1;";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result1 = $database->execute();
if ($result) {    
    $conbiz = $result1->fetch_object();
    $bizum=$conbiz->bizum;
}

$sql="SELECT estadoweb.estado AS estado, 
opcionescompra.tipo_repartos AS tipo_repartos, opcionescompra.fidelizacion AS fidelizacion, opcionescompra.porcentaje AS porcentaje,opcionescompra.tiempoenvio AS tiempoenvio, 
opcionescompra.idBolsa AS idBolsa, productos.nombre as productoBolsa,productos.precio_web as precioBolsa,
opcionescompra.cortesia AS cortesia, opcionescompra.maximocarrito AS maximocarrito, 
opcionescompra.maximoproducto AS maximoproducto, opcionescompra.portesgratis AS portesgratis, opcionescompra.importeportesgratis AS importeportesgratis,
opcionescompra.portesgratismensaje AS portesgratismensaje,
opcionescompra.norepartomensaje AS norepartomensaje,
opcionescompra.minimo AS pedidominimo, opcionescompra.tipo_seleccion_horas AS tipo_seleccion_horas, opcionescompra.dias_vista AS dias_vista, integracion.tipo AS integracion,integracion.delivery, integracion.copias, integracion.usar_modo_quiosco, empresa.movil AS movil, empresa.nombre_comercial AS nombre_comercial FROM estadoweb LEFT JOIN opcionescompra ON opcionescompra.id=estadoweb.id LEFT JOIN integracion ON integracion.id=opcionescompra.id LEFT JOIN empresa ON empresa.id=opcionescompra.id  LEFT JOIN productos ON productos.id=opcionescompra.idBolsa Where estadoweb.id=1";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result) {    
    $checking=true;
    $estado = $result->fetch_object();
    $on=$estado ->estado;
    $tipo_repartos=$estado ->tipo_repartos;
    $fidelizacion=$estado ->fidelizacion;
    $porcentaje=$estado ->porcentaje;
    $tiempoenvio=$estado ->tiempoenvio;
    $cortesia=$estado ->cortesia;
    $maximocarrito=$estado->maximocarrito;
    $maximoproducto=$estado->maximoproducto;
    $integracion=$estado ->integracion;
    $copiasTickets=$estado ->copias;
    $delivery=$estado ->delivery;
    $pedidominimo=$estado ->pedidominimo;
    $portesgratis=$estado ->portesgratis;
    $importeportesgratis=$estado ->importeportesgratis;
    $portesgratismensaje=$estado ->portesgratismensaje;
    //norepartomensaje
    $norepartomensaje=$estado ->norepartomensaje;
    $movil=$estado ->movil;
    $nombre_comercial=$estado ->nombre_comercial;
    $tipo_seleccion_horas=$estado ->tipo_seleccion_horas;
    $dias_vista=$estado ->dias_vista;
    $idBolsa=$estado ->idBolsa;
    $productoBolsa=$estado ->productoBolsa;
    $precioBolsa=$estado ->precioBolsa;
    $incluyeQuiosco=$estado->usar_modo_quiosco;
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

$json=array("valid"=>$checking, "tipo_repartos"=>$tipo_repartos, "movil"=>$movil, "on"=>$on,"fidelizacion"=>$fidelizacion, "porcentaje"=>$porcentaje, "tiempoenvio"=>$tiempoenvio, "idBolsa"=>$idBolsa, "precioBolsa"=>$precioBolsa, "productoBolsa"=>$productoBolsa, "cortesia"=>$cortesia, "maximocarrito"=>$maximocarrito, "maximoproducto"=>$maximoproducto, "integracion"=>$integracion, "incluyeQuiosco"=>$incluyeQuiosco, "delivery"=>$delivery, "copiasTickets"=>$copiasTickets, "pedidominimo"=>$pedidominimo,"id"=>$id,"alias"=>$alias,"domicilio"=>$domicilio,"telefono"=>$telefono,"lat"=>$lat,"lng"=>$lng, "portesgratis"=>$portesgratis, "importeportesgratis"=>$importeportesgratis, "portesgratismensaje"=>$portesgratismensaje, "norepartomensaje"=>$norepartomensaje, "nombre_comercial"=>$nombre_comercial, "idRedsys"=>$idRedsys, "bizum"=>$bizum, "tipo_seleccion_horas"=>$tipo_seleccion_horas, "dias_vista"=>$dias_vista);

ob_end_clean();
echo json_encode($json);    
/*
$file = fopen("estado.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);
fclose($file);
*/
?>
