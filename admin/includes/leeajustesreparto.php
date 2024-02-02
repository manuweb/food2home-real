<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;


$sql="SELECT opcionescompra.minimo, opcionescompra.portesgratis, opcionescompra.importeportesgratis, opcionescompra.portesgratismensaje, opcionescompra.norepartomensaje, opcionescompra.tiempoenvio, opcionescompra.cortesia, opcionescompra.maximocarrito, opcionescompra.pedidosportramoenvio,opcionescompra.pedidosportramococina, productos.nombre AS portes, opcionescompra.idEnvio, opcionescompra.ivaEnvio AS ivaEnvio, opcionescompra.tarifa AS tarifa FROM opcionescompra LEFT JOIN productos ON opcionescompra.idEnvio=productos.id WHERE opcionescompra.id=1";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result) {
    $grupo = $result->fetch_object();
    $tiempoenvio=$grupo->tiempoenvio;
    $cortesia=$grupo->cortesia;
    $maximocarrito=$grupo->maximocarrito;
    $minimo=$grupo->minimo;
    $portesgratis=$grupo->portesgratis;
    $importeportesgratis=$grupo->importeportesgratis;
    $portesgratismensaje=$grupo->portesgratismensaje;
    $norepartomensaje=$grupo->norepartomensaje;
    $pedidosportramoenvio=$grupo->pedidosportramoenvio;
    $pedidosportramococina=$grupo->pedidosportramococina;
    $portes=$grupo->portes;
    $iva=$grupo->ivaEnvio;
    $idenvio=$grupo->idEnvio;
    $tarifa=$grupo->tarifa;

    $checking=true;
}

$database->freeResults();

$json=array("valid"=>$checking, "minimo"=>$minimo, "tiempoenvio"=>$tiempoenvio, "cortesia"=>$cortesia, "maximocarrito"=>$maximocarrito, "pedidosportramoenvio"=>$pedidosportramoenvio, "pedidosportramococina"=>$pedidosportramococina, "portes"=>$portes, "iva"=>$iva, "idenvio"=>$idenvio, "tarifa"=>$tarifa, "portesgratis"=>$portesgratis, "importeportesgratis"=>$importeportesgratis, "portesgratismensaje"=>$portesgratismensaje, "norepartomensaje"=>$norepartomensaje);


echo json_encode($json); 
/*
$file = fopen("texto.txt", "w");
fwrite($file, "coor: ".count($zona). PHP_EOL);

fclose($file);

*/
?>
