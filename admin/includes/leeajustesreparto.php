<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;


$sql="SELECT opcionescompra.minimo, 
opcionescompra.tipo_repartos, 
opcionescompra.portesgratis, opcionescompra.importeportesgratis, opcionescompra.portesgratismensaje, opcionescompra.norepartomensaje, opcionescompra.tiempoenvio, opcionescompra.cortesia, opcionescompra.maximocarrito, opcionescompra.maximoproducto, opcionescompra.pedidosportramoenvio, opcionescompra.pedidosportramococina, productos.nombre AS portes, opcionescompra.idEnvio,productos2.nombre AS bolsa, opcionescompra.idBolsa AS idBolsa, opcionescompra.tarifa AS tarifa, opcionescompra.tipo_seleccion_horas AS tipo_seleccion_horas, opcionescompra.dias_vista AS dias_vista FROM opcionescompra LEFT JOIN productos ON opcionescompra.idEnvio=productos.id  LEFT JOIN productos AS productos2 ON opcionescompra.idBolsa=productos2.id WHERE opcionescompra.id=1;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result) {
    $grupo = $result->fetch_object();
    $tipo_repartos=$grupo->tipo_repartos;
    $tiempoenvio=$grupo->tiempoenvio;
    $cortesia=$grupo->cortesia;
    $maximocarrito=$grupo->maximocarrito;
    $maximoproducto=$grupo->maximoproducto;
    $minimo=$grupo->minimo;
    $portesgratis=$grupo->portesgratis;
    $importeportesgratis=$grupo->importeportesgratis;
    $portesgratismensaje=$grupo->portesgratismensaje;
    $norepartomensaje=$grupo->norepartomensaje;
    $pedidosportramoenvio=$grupo->pedidosportramoenvio;
    $pedidosportramococina=$grupo->pedidosportramococina;
    $portes=$grupo->portes;
    $idBolsa=$grupo->idBolsa;
    $bolsa=$grupo->bolsa;
    $idenvio=$grupo->idEnvio;
    $tarifa=$grupo->tarifa;
    $tipo_seleccion_horas=$grupo->tipo_seleccion_horas;
    $dias_vista=$grupo->dias_vista;

    $checking=true;
}

$database->freeResults();

$json=array("valid"=>$checking, "tipo_repartos"=>$tipo_repartos, "minimo"=>$minimo, "tiempoenvio"=>$tiempoenvio, "cortesia"=>$cortesia, "maximocarrito"=>$maximocarrito, "maximoproducto"=>$maximoproducto, "pedidosportramoenvio"=>$pedidosportramoenvio, "pedidosportramococina"=>$pedidosportramococina, "portes"=>$portes, "bolsa"=>$bolsa, "idBolsa"=>$idBolsa, "idenvio"=>$idenvio, "tarifa"=>$tarifa, "portesgratis"=>$portesgratis, "importeportesgratis"=>$importeportesgratis, "portesgratismensaje"=>$portesgratismensaje, "norepartomensaje"=>$norepartomensaje, "tipo_seleccion_horas"=>$tipo_seleccion_horas, "dias_vista"=>$dias_vista);


echo json_encode($json); 
/*
$file = fopen("texto.txt", "w");
fwrite($file, "coor: ".count($zona). PHP_EOL);

fclose($file);

*/
?>
